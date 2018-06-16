<?php
namespace Admin\Controller;
class AttributeController extends CommonController
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Attribute');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst',array('type_id'=>I('post.type_id'))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '添加属性',
			'page_btn_name' => '属性列表',
			'page_btn_url' => U('lst',array('type_id'=>I('get.type_id'))),
		));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Attribute');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst',array('type_id'=>I('post.type_id'))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	$model = M('Attribute');
    	$data = $model->find($id);
    	$this->assign('data', $data);

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '修改属性',
			'page_btn_name' => '属性列表',
			'page_btn_url' => U('lst',array('type_id'=>I('get.type_id'))),
		));
		$this->display();
    }
    public function del()
    {
        if(IS_AJAX)
        {
            $id=I('get.id');
            $model=D('Attribute');
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
    	$model = D('Attribute');
    	$data = $model->search(2);
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
            'count'=>$data['count']
    	));

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '属性列表',
			'page_btn_name' => '添加属性',
			'page_btn_url' => U('add',array('type_id'=>I('get.type_id'))),
		));
    	$this->display();
    }
    public function _empty(){
        $this->display('empty/empty');
    }
}