<?php
namespace Home\Controller;

class CartController extends EmptyController
{
    public function add(){
        if(IS_POST)
        {
           $model=D('Cart');
           if($model->create(I('post.'),1))
           {
               if($model->add())
               {
                   $this->success('添加成功',U('Cart/lst'),2);
                   exit();
               }
           }
           $this->error($model->getError());
        }
    }
    public function lst(){
        $model=D('Cart');
        $res=$model->cartList();
//        dump($res);
//        exit();
        $this->assign('res',$res);
        $this->assign(array(
            'page_name'=>'购物车详情',
            'page_keywords'=>'购物车详情',
            'page_description'=>'购物车详情',
        ));
        $this->display();
    }
    public function del(){
//      分为登陆与未登录状态
        $m_id=session('m_id');
        if($m_id)
        {
           $model=D('Cart');
           if($model->where(array('member_id'=>array('eq',$m_id)))->delete()!==false)
           {
               $this->success('清空成功',U('Cart/lst'),2);
               exit();
           }
            $this->error('清空失败');
        }
         else
         {
             cookie('cart',null);
             $this->success('清空成功',U('Cart/lst'),2);
         }
    }
    public function delGoodsByAjax(){
        if(IS_AJAX) {
            $id = I('get.id');
            $m_id = session('m_id');
//            登陆删数据库
            if ($m_id) {
                $model = D('Cart');
                if ($model->delete($id) !== false) {
                    $data = array(
                        'flag' => 1
                    );
                    echo json_encode($data);
                } else {
                    $data = array(
                        'flag' => 0
                    );
                    echo json_encode($data);
                }
            }
            //        未登录删cookie
            else
            {
                $goods_id=I('get.goodsid');
                $attr_id=I('get.attrid');
                $key=$goods_id.'-'.$attr_id;
                $data=isset($_COOKIE['cart'])?unserialize($_COOKIE['cart']):array();
                unset($data[$key]);
                cookie('cart',serialize($data),86400*30);
                $data=array(
                    'flag'=>1
                );
                echo json_encode($data);
            }
        }
    }
    public function getGoodsByAjax(){
        if(IS_AJAX)
        {
            $model=D('Cart');
            $res=$model->cartList();
            echo json_encode($res);
        }
    }
}