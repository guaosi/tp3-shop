<?php
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model
{
	protected $insertFields = array('shr_name','shr_tel','shr_prience','shr_city','shr_area','shr_address');
	protected $_validate = array(
		array('shr_name', 'require', '收货人姓名不能为空！', 1, 'regex', 3),
		array('shr_tel', 'require', '收货人电话不能为空！', 1, 'regex', 3),
		array('shr_tel', 'number', '电话只能是数字！', 1, 'regex', 3),
        array('shr_tel', '11', '电话只能是十一位数字！', 1, 'length', 3),
		array('shr_prience', 'require', '收货人所在省份不能为空！', 1, 'regex', 3),
		array('shr_city', 'require', '收货人所在城市不能为空！', 1, 'regex', 3),
		array('shr_area', 'require', '收货人所在地区不能为空！', 1, 'regex', 3),
		array('shr_address', 'require', '收货人所在详细地址不能为空！', 1, 'regex', 3),
	);
	protected function _before_insert(&$data,&$option)
    {
//        进行order的插入
//        先进行判断
//        1.先登录才能下单
        $m_id=session('m_id');
        if(!$m_id)
        {
            $this->error='请先登陆后再下单';
            return false;
        }
//        2.购物车中有商品才能下单
        $cartModel=D('Cart');
        $option['cart']=$cartinfo=$cartModel->cartList();
        if(!$cartinfo)
        {
            $this->error='购物车中没有商品,无法下单';
        }
//        3.检查库存量是否足够
//        加锁 锁要设置能全局存在的
        $this->fp=fopen('./order.lock','r');
        flock($this->fp,LOCK_EX);

        $goods_number_model=M('goods_number');
        $total_price=0;
        foreach ($cartinfo as $key=>$val)
        {
          $goods_number=$goods_number_model->field('goods_number')->where(array(
              'goods_id'=>array('eq',$val['goods_id']),
              'goods_attr_id'=>array('eq',$val['attr_id'])
          ))->find();

          if($goods_number['goods_number']<$val['goods_number'])
          {
              $this->error='下单失败,原因: 商品 '.$val['goods_name'].'库存量不足';
              return false;
          }
            $total_price=$total_price+$val['price']*$val['goods_number'];
        }
//        以上都没问题 讲数据加入订单表
        $data['member_id']=$m_id;
        $data['addtime']=time();
        $data['total_price']=$total_price;
//        为了三张表都能成功，开始事务进行判断
        $this->startTrans();
    }
    protected function _after_insert($data,$option)
    {
//     加入订单商品表
        $order_id=$data['id'];
        $cartinfo=$option['cart'];
        $data=array();
        $order_goods_model=M('order_goods');
        $goods_number_model=M('goods_number');
//        1.添加到商品订单表
        foreach ($cartinfo as $key=>$val)
        {
            $data['order_id']=$order_id;
            $data['goods_id']=$val['goods_id'];
            $data['goods_attr_id']=$val['attr_id'];
            $data['goods_number']=$val['goods_number'];
            $data['price']=$val['price'];
            $res=$order_goods_model->add($data);
            if(!$res)
            {
//                失败就回退
                $this->rollback();
                return false;
            }
            //        2.减少库存量
            $res=$goods_number_model->field('goods_number')->where(array(
                'goods_id'=>array('eq',$val['goods_id']),
                'goods_attr_id'=>array('eq',$val['attr_id'])
            ))->setDec('goods_number',$val['goods_number']);
             if($res===false)
             {
//                失败就回退
                 $this->rollback();
                 return false;
             }
        }
//        全部通过 提交事务
        $this->commit();
//             3.清空购物车

        $cart_model=D('cart');
        $cart_model->cleanCart();
        //        释放锁
        flock($this->fp,LOCK_UN);
        fclose($this->fp);
    }
    public function setpaid($order_id){
//          更新订单
        $data['id']=$order_id;
        $data['pay_status']='是';
        $data['pay_time']=time();
        $this->save($data);
//        更新会员积分
        $jifen=$this->field('total_price,member_id')->find($order_id);
        $memberModel=M('member');
        $memberModel->where(array(
            'id'=>array('eq',$jifen['member_id'])
        ))->setInc('jifen',$jifen['total_price']);
    }
    public function search($pageSize = 5)
    {
        /**************************************** 搜索 ****************************************/
        $m_id=session('m_id');
        $where = array();
            $where['member_id'] = array('eq', $m_id);
        /************************************* 翻页 ****************************************/
        $count = $this->alias('a')->where($where)->count();
        $nocount = $this->alias('a')->where(array(
            'member_id'=>array('eq',$m_id),
            'pay_status'=>array('eq','否')
        ))->count();
        $yescount = $this->alias('a')->where(array(
            'member_id'=>array('eq',$m_id),
            'pay_status'=>array('eq','是')
        ))->count();
        $page = new \Think\Page($count, $pageSize);
        // 配置翻页的样式
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('first','首页');
        $page->setConfig('last','末页');
        $data['page'] = $page->show();
        /************************************** 取数据 ******************************************/
        $data['data'] = $this->alias('a')->field('a.id,a.member_id,a.addtime,a.pay_status,a.total_price,a.shr_name,GROUP_CONCAT(distinct c.id) goods_id,GROUP_CONCAT(distinct c.sm_logo) sm_logo')->join('left join p39_order_goods as b on a.id=b.order_id left join p39_goods as c on c.id=b.goods_id')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
        $data['count']=$count;
        $data['nocount']=$nocount;
        $data['yescount']=$yescount;
        return $data;
    }
}