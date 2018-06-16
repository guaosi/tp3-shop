<?php
/**
 * Date: 2017/7/15/0015
 * Time: 11:00
 */
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $cate_model=D('Admin/category');
        $nav_data=$cate_model->getNavData();
        $this->assign(array(
             'nav_data'=>$nav_data
        ));
    }
    public function _empty(){
        $this->display('Empty/empty');
    }
}