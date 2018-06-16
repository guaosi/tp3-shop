<?php
namespace Home\Controller;
class OrderController extends EmptyController
{
    public function add(){
        if(!session('m_id'))
        {
            session('returnurl',U('Order/add'));
            $this->error('请先登陆',U('Member/login'),2);
            exit();
        }
        if(IS_POST)
        {
//            die;
           $model=D('Admin/Order');
           if($model->create(I('post.'),1))
           {
               if($order_id=$model->add())
               {
                   $this->success('下单成功',U('Order/lst',array('orderId'=>$order_id)),2);
                   exit();
               }
           }
           $this->error($model->getError());
        }
        else{
            $model=D('Cart');
            $res=$model->cartList();
//        dump($res);
//        exit();
            $this->assign('res',$res);
            $this->assign(array(
                'page_name'=>'订单详情',
                'page_keywords'=>'订单详情',
                'page_description'=>'订单详情',
            ));
            $this->display();
        }
    }
    public function lst(){
        if(!session('m_id'))
        {
            $this->error('请先登陆',U('Member/login'),2);
            exit();
        }
        if(!I('get.orderId'))
        {
            $this->error('请根据订单查看',U('index/index'),2);
            exit();
        }
        $orderModel=D('Order');
        $res=$orderModel->where(array(
            'member_id'=>session('m_id'),
            'id'=>I('get.orderId')
        ))->find();
        if(!$res)
        {
            $this->error('不允许查看别人的订单',U('index/index'),2);
            exit();
        }
        $this->assign(array(
            'page_name'=>'成功提交订单',
            'page_keywords'=>'成功提交订单',
            'page_description'=>'成功提交订单',
        ));
        $this->display();
    }
    public function receive(){
        require './alipay/notify_url.php';
    }
    public function pay(){
        if(!session('m_id'))
        {
            $this->error('请先登陆',U('Member/login'),2);
            exit();
        }
        if(IS_POST)
        {
            if(!I('post.orderId'))
            {
                $this->error('请根据订单查看',U('index/index'),2);
                exit();
            }
            $orderModel=D('Order');
            $res=$orderModel->where(array(
                'member_id'=>session('m_id'),
                'id'=>I('post.orderId')
            ))->find();
            if(!$res)
            {
                $this->error('不允许查看别人的订单',U('index/index'),2);
                exit();
            }
            $model=D('Admin/order');
            $orderId=I('post.orderId');
            $res=$model->find($orderId);
            require './alipay2/pagepay/pagepay.php';
        }
    }
    public function notify(){
        require './alipay2/notify_url.php';
    }
    public function returnurl(){
        $this->display('Order/pay_success');
    }

}