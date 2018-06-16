<?php
/**
 * Date: 2017/7/16/0016
 * Time: 20:53
 */

namespace Home\Model;

use Think\Model;

class CartModel extends Model
{
    protected $insertFileds = array('goods_id', 'attr_id', 'goods_number');
    protected $_validate = array(
        array('goods_id', 'require', '必须选择商品', 1, 'regex', 3),
        array('goods_number', 'checkgoodsnumber', '库存量不足', 1, 'callback'),
    );

    //检查库存量
    public function checkgoodsnumber($goodsNumber)
    {
        $attr_val = I('post.attr_id');
        sort($attr_val, SORT_NUMERIC);
        $attr = (string)implode(',', $attr_val);
        $goods_number_model = D('Admin/goods_number');
        $goods_number = $goods_number_model->field('goods_number')->where(array(
            'goods_id' => array('eq', I('post.goods_id')),
            'goods_attr_id' => array('eq', $attr)
        ))->find();
        $number = $goods_number['goods_number'];
        if ($number >= $goodsNumber) {
            return true;
        } else {
            return false;
        }
    }

    //因为要分登陆与未登录(数据库与cookie)  所以重写add方法
    public function add()
    {
        $m_id = session('m_id');
        $goods_id = I('post.goods_id');
        $attr_id = I('post.attr_id');
        sort($attr_id, SORT_NUMERIC);
        $attr_id = (string)implode(',', $attr_id);
        $goods_number = I('post.goods_number');
        if ($m_id) {
//      存入数据库
//            先看看是否已经有这个数据了
            $has = $this->field('id')->where(array(
                'goods_id' => array('eq', $goods_id),
                'attr_id' => array('eq', $attr_id),
            ))->find();
            if ($has) {
//               如果有就加上现在的
                $this->where(array(
                    'id' => array('eq', $has['id']),
                ))->setInc('goods_number', $goods_number);
            } else {
//               没有就新建
                $data['goods_id'] = $goods_id;
                $data['member_id'] = $m_id;
                $data['attr_id'] = $attr_id;
                $data['goods_number'] = $goods_number;
                parent::add($data);
            }
        } else {
//       存入cookie
            $info = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
//          拼一下形式
            $key = $goods_id . '-' . $attr_id;
//            判断是否已经存在，不存在就新增，存在就增加啊
            if ($info[$key]) {
                $info[$key] += $goods_number;
            } else {
                $info[$key] = $goods_number;
            }
            cookie(cart, serialize($info), 30 * 86400);
        }
        return true;
    }

    public function removeCookietoDb()
    {
        $m_id = session('m_id');
        if ($m_id) {
            $info = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
            foreach ($info as $key => $val) {

                $data = explode('-', $key);
                $has = $this->field('id')->where(array(
                    'goods_id' => array('eq', $data[0]),
                    'attr_id' => array('eq', $data[1]),
                ))->find();
                if ($has) {
//               如果有就加上现在的
                    $this->where(array(
                        'id' => array('eq', $has['id']),
                    ))->setInc('goods_number', $val);
                } else {
//               没有就新建
                    $data['goods_id'] = $data[0];
                    $data['member_id'] = $m_id;
                    $data['attr_id'] = $data[1];
                    $data['goods_number'] = $val;
                    parent::add($data);
                }
            }
//            清空cookie
            cookie('cart', null);
        }

    }

//    获取购物车里的数据
    public function cartList()
    {
//        登陆的时候显示数据库里的数据，未登陆的时候显示cookie里的数据
        $m_id = session('m_id');
        if ($m_id) {
           $res= $this->where(array(
                'member_id' => array('eq', $m_id)
            ))->select();
        } else {
           $_res=isset($_COOKIE['cart'])?unserialize($_COOKIE['cart']):array();
           $res=array();
//           将格式转换与上面的一致
            $i=0;
           foreach ($_res as $key=>$val)
           {
             $_key=explode('-',$key);
             $res[$i]['goods_id']=$_key[0];
             $res[$i]['attr_id']=$_key[1];
             $res[$i]['goods_number']=$val;
             $i++;
           }
        }
//        查询到商品的信息，商品名称,商品图片,购买价格,属性类型名称，属性名称 都保存到一个二维数组里，一起返回
        $goodsModel=D('Admin/Goods');
        $goodsattrModel=D('Admin/goods_attr');
        foreach ($res as $key=>$val)
        {

          $price=$goodsModel->getMemberPrice($val['goods_id']);
          $_goodsname=$goodsModel->field('goods_name,mid_logo')->find($val['goods_id']);
          $res[$key]['price']=$price;
          $res[$key]['goods_name']=$_goodsname['goods_name'];
          $res[$key]['mid_logo']=$_goodsname['mid_logo'];
          if($val['attr_id'])
          {
              $_val=explode(',',$val['attr_id']);
              foreach ($_val as $k=>$v)
              {
                  $info=$goodsattrModel->alias('a')->field('a.attr_value,b.attr_name')->join('left join p39_attribute as b on a.attr_id=b.id')->where(array(
                      'a.id'=>array('eq',$v)
                  ))->find();
                  $res[$key]['attribute'][]=array('attr_name'=>$info['attr_name'],'attr_value'=>$info['attr_value']);
              }

          }
        }
        return $res;
    }
    public function cleanCart(){
        $m_id=session('m_id');
        $this->where(array('member_id'=>array('eq',$m_id)))->delete();
    }
}