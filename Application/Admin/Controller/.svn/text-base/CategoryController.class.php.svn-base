<?php
namespace Admin\Controller;
class CategoryController extends CommonController
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('category');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst'));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
        else
        {
            //这里用生成无限级分类
            $category=D('Category')->getTree();
            // 设置页面中的信息
            $this->assign(array(
                'category'=>$category,
                'page_name' => '添加分类',
                'page_btn_name' => '分类列表',
                'page_btn_url' => U('lst'),
            ));
            $this->display();
        }

    }
    public function edit()
    {

    	if(IS_POST)
    	{
    		$model = D('category');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst'));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	else
        {
            //获取单前要修改的记录的信息
            $id = I('get.id');
            $model = D('category');
            $data = $model->find($id);
            //获取递归树
            $category=$model->getTree();
            $this->assign('data', $data);
            //不能作为自己将自己作为上一级分类或者将自己的子类作为上一级分类，否则无限递归
            //所以这里要去除 自己以及自己的子类的显示

            //先获取到所有的子分类ID,然后到view中判断
            $child_id=$model->getChildren($id);
            //将自己的ID也放入子分类中
            $child_id[]=$id;

            // 设置页面中的信息
            $this->assign(array(
                'child_id'=>$child_id,
                'category'=>$category,
                'page_name' => '修改分类',
                'page_btn_name' => '分类列表',
                'page_btn_url' => U('lst'),
            ));
            $this->display();
        }

    }
    public function del()
    {
    	if(IS_AJAX)
        {
            $id=I('get.id');
            $model=D('category');
            if($model->delete($id)!==false)
            {
                $data=array(
                    'flag'=>1
                );
                echo json_encode($data);
            }
            else
            {
                $data=array(
                    'flag'=>0
                );
                echo json_encode($data);
            }
        }
    }
    public function lst()
    {
    	$model = D('category');
    	$data = $model->getTree();
    	$this->assign(array(
    		'data' => $data,
    	));

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '分类列表',
			'page_btn_name' => '分类品牌',
			'page_btn_url' => U('add'),
		));
    	$this->display();
    }
    public function _empty(){
        $this->display('empty/empty');
    }
}