<?php
/**
 * Date: 2017/7/14/0014
 * Time: 17:37
 */
namespace Home\Controller;
class MemberController extends EmptyController{
    public function login(){
        if(IS_POST)
        {
            $model=D('Admin/member');
            if($model->validate($model->_login_validate)->create())
            {
                if($model->login())
                {
                    $url=U('index/index');
                    $_url=session('returnurl');
                    if($_url)
                    {
                        $url=$_url;
                    }
                    $this->success('登陆成功',$url,2);
                    exit();
                }
            }
            $this->error($model->getError());
        }
        else
        {
            $this->assign(array(
                'page_name'=>'登陆',
                'page_keywords'=>'登陆',
                'page_description'=>'登陆',
            ));
            $this->display();
        }
    }
    public function minilogin(){
        if(IS_POST)
        {
            $model=D('Admin/member');
            if($model->validate($model->_login_validate)->create())
            {
                if($model->login())
                {
                    $this->success('登陆成功','',true);
                    exit();
                }
            }
            $this->error($model->getError(),'',true);
        }
        else
        {
            $this->assign(array(
                'page_name'=>'登陆',
                'page_keywords'=>'登陆',
                'page_description'=>'登陆',
            ));
            $this->display();
        }

    }
    public function regist(){
        if(IS_POST)
        {
            $data=I('post.');
            $model=D('Admin/member');
            if($model->create($data,1))
            {
                if($model->add())
                {
                    $this->success('注册成功',U('member/login'),2);
                    exit();
                }
            }
            $this->error($model->getError());
        }
        else
        {
            $this->assign(array(
                'page_name'=>'注册',
                'page_keywords'=>'注册',
                'page_description'=>'注册',
            ));
            $this->display();
        }

    }
    public function logout(){
      D('Admin/member')->logout();
      redirect('/');
    }
    public function checkcode(){
        $config =    array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    2,     // 验证码位数
            'useNoise'    =>    true, // 关闭验证码杂点
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }
    public function checkLoginByAjax(){
        $id=session('m_id');
        $info=array();
        $info['flag']=0;
        if($id)
        {
          $info['flag']=1;
          $info['name']=session('m_username');
        }
        echo json_encode($info);
    }
}