<?php
namespace Admin\Controller;
class MemberLevelController extends CommonController
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('MemberLevel');
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
        else{
            // 设置页面中的信息
            $this->assign(array(
                'page_name' => '添加会员级别',
                'page_btn_name' => '会员级别列表',
                'page_btn_url' => U('lst'),
            ));
            $this->display();
        }

    }
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('MemberLevel');
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
            $model = M('MemberLevel');
            $data = $model->find($id);
            $this->assign('data', $data);

            // 设置页面中的信息
            $this->assign(array(
                'page_name' => '修改会员级别',
                'page_btn_name' => '会员级别列表',
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
            $model=D('member_level');
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
    	$model = D('MemberLevel');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
            'count'=>$data['count']
    	));

		// 设置页面中的信息
		$this->assign(array(
			'page_name' => '会员级别列表',
			'page_btn_name' => '添加会员级别',
			'page_btn_url' => U('add'),
		));
    	$this->display();
    }
}