<?php
/**
 * Date: 2017/7/14/0014
 * Time: 19:59
 */

namespace Admin\Controller;

use Think\Controller;

class CommonController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!session('id')) {
            $this->error('请先进行登陆', U('login/login'), 2);
            exit();
        }
        $model=D('Privilege');
        if (!$model->checkauth()) {
            $this->error('没有对应的权限');
        }
    }
    public function _empty(){
        $this->display('Empty/empty');
    }

}