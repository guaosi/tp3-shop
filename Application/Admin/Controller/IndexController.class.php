<?php
namespace Admin\Controller;
class IndexController extends CommonController
{
    public function index()
    {
    	$this->display();
    }
    public function top()
    {
    	$this->display();
    }
    public function menu()
    {
        $btn=D('privilege')->getBtn();
//        dump($btn);
//        exit();
        $this->assign('btn',$btn);
    	$this->display();
    }
    public function main()
    {
    	$this->display();
    }
    
}