<?php
namespace Admin\Controller;
class RoleController extends CommonController
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Role');
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
        //取出所有权限(无限级)
        $privilege_model=D('Privilege');
        $privilege_tree=$privilege_model->getTree();
		// 设置页面中的信息
		$this->assign(array(
		    'privilege_tree'=>$privilege_tree,
			'page_name' => '添加角色',
			'page_btn_name' => '角色列表',
			'page_btn_url' => U('lst'),
		));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
    	$where['role_id']=array('eq',$id);
    	if(IS_POST)
    	{
    		$model = D('Role');
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
    	$model = M('Role');
    	$data = $model->find($id);
    	$this->assign('data', $data);
        //取出所有权限(无限级)
        $privilege_model=D('Privilege');
        $privilege_tree=$privilege_model->getTree();
        //取出已经保存在角色权限表的信息
        $pri_id=M('pri_role')->field('pri_id')->where($where)->select();
        $_pri_id=array_column($pri_id,'pri_id');
		// 设置页面中的信息
		$this->assign(array(
		    'pri_id'=>$_pri_id,
            'privilege_tree'=>$privilege_tree,
			'page_name' => '修改角色',
			'page_btn_name' => '角色列表',
			'page_btn_url' => U('lst'),
		));
		$this->display();
    }
    public function del()
    {
        if(IS_AJAX)
        {
            $id=I('get.id');
            $model=D('Role');
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
    	$model = D('Role');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
            'count'=>$data['count']
    	));

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '角色列表',
			'page_btn_name' => '添加角色',
			'page_btn_url' => U('add'),
		));
    	$this->display();
    }
}