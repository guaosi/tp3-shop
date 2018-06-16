<?php
namespace Admin\Controller;
class PrivilegeController extends CommonController
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Privilege');
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
		$parentModel = D('Privilege');
		$parentData = $parentModel->getTree();
		$this->assign('parentData', $parentData);

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '添加权限',
			'page_btn_name' => '权限列表',
			'page_btn_url' => U('lst'),
		));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Privilege');
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
    	$model = M('Privilege');
    	$data = $model->find($id);
    	$this->assign('data', $data);
		$parentModel = D('Privilege');
		$parentData = $parentModel->getTree();
		$children = $parentModel->getChildren($id);
		$this->assign(array(
			'parentData' => $parentData,
			'children' => $children,
		));

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '修改权限',
			'page_btn_name' => '权限列表',
			'page_btn_url' => U('lst'),
		));
		$this->display();
    }
    public function del()
    {
        if(IS_AJAX)
        {
            $id=I('get.id');
            $model=D('Privilege');
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
    	$model = D('Privilege');
		$data = $model->getTree();
    	$this->assign(array(
    		'data' => $data,
    	));

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '权限列表',
			'page_btn_name' => '添加权限',
			'page_btn_url' => U('add'),
		));
    	$this->display();
    }
}