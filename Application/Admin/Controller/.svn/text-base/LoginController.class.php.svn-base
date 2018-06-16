<?php
/**
 * Date: 2017/7/14/0014
 * Time: 17:37
 */
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
    public function _empty(){
        $this->display('empty/empty');
    }
    public function login(){
        if(IS_POST)
        {
           $model=D('Admin');
           if($model->validate($model->_login_validate)->create())
           {
               if($model->login())
               {
                 $this->success('登陆成功',U('index/index',2));
                 exit();
               }
           }
           $this->error($model->getError());
        }
        else
        {
            $this->display();
        }

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
    public function logout(){
      D('Admin')->logout();
      $url=U('login/login');
      $str=<<<STR
<script>top.location.href="$url"</script>
STR;
      echo $str;
    }
}