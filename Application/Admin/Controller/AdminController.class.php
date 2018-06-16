<?php
namespace Admin\Controller;
class AdminController extends CommonController
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Admin');
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
         //取出角色信息
        $role=M('Role')->select();
		// 设置页面中的信息
		$this->assign(array(
		    'Role'=>$role,
			'page_name' => '添加管理员',
			'page_btn_name' => '管理员列表',
			'page_btn_url' => U('lst'),
		));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Admin');
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
    	$model = M('Admin');
    	$data = $model->find($id);
    	$this->assign('data', $data);
        //取出角色信息
        $role=M('Role')->select();
        //取出已经设置的角色信息
        $where['admin_id']=array('eq',$id);
        $role_id=M('admin_role')->field('role_id')->where($where)->select();
        $_role_id=array_column($role_id,'role_id');
		// 设置页面中的信息
		$this->assign(array(
            'Role'=>$role,
            'role_id'=>$_role_id,
			'page_name' => '修改管理员',
			'page_btn_name' => '管理员列表',
			'page_btn_url' => U('lst'),
		));
		$this->display();
    }
    public function del()
    {
        if(IS_AJAX)
        {
            $id=I('get.id');
            $model=D('Admin');
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
    	$model = D('Admin');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
            'count'=>$data['count']
    	));

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '管理员列表',
			'page_btn_name' => '添加管理员',
			'page_btn_url' => U('add'),
		));
    	$this->display();
    }
}