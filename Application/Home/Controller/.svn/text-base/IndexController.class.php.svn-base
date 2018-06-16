<?php
namespace Home\Controller;
class IndexController extends CommonController
{
    public function index()
    {
//        防止页面雪崩
        $fp=uniqid();
        file_put_contents('./piao/'.$fp,$fp);
        $goods_model=D('Admin/goods');
       //获取疯狂抢购的信息
         $promote_data=$goods_model->getPromote();
         $hot_data=$goods_model->getOtherRet("hot");
         $new_data=$goods_model->getOtherRet("new");
         $ret_data=$goods_model->getOtherRet("ret");
         $floor=$goods_model->getFloorData();
        $this->assign(array(
            'floor'=>$floor,
            'prompte'=>$promote_data,
            'hot'=>$hot_data,
            'new'=>$new_data,
            'ret'=>$ret_data,
            'page_name'=>'首页',
            'page_keywords'=>'首页',
            'page_description'=>'首页',
            'show_nav'=>1
        ));
        $this->display();
    }
}