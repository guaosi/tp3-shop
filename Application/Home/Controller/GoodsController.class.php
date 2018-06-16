<?php
/**
 * Date: 2017/7/15/0015
 * Time: 11:00
 */
namespace Home\Controller;
class GoodsController extends CommonController
{
    public function getMemberPriceByAjax(){
//        获取商品的会员价格
        if(IS_AJAX)
        {
            $goods_id=I('get.id');
            $goods_model=D('Admin/Goods');
            $price=$goods_model->getMemberPrice($goods_id);
            $data['price']=$price;
            echo json_encode($data);
        }

    }
    public function goods(){
        $goods_id=I('get.id');
        $goods_model=D('Admin/Goods');

        //获取该商品所有信息

        $goods_info=$goods_model->where(array(
            'is_on_sale'=>array('eq','是'),
            'is_delete'=>array('eq','f否')
        ))->find($goods_id);
        if(!$goods_info)
        {
            $this->error('该商品已经下架或者删除');
            exit();
        }
//        获取商品相册
        $goods_pic_model=M('goods_pic');
        $goods_pic_info=$goods_pic_model->where(array(
            'goods_id'=>array('eq',$goods_id)
        ))->select();
//        获取商品属性
        $goods_attr_model=M('goods_attr');
        $goods_attr_info=$goods_attr_model->field('a.id,a.attr_value,b.attr_name,b.attr_type')->alias('a')->join('left join p39_attribute as b on a.attr_id=b.id')->where(array(
              'goods_id'=>array('eq',$goods_id),
        ))->select();
//        将属性分为可选与不可选  并且将他们的数据进行处理
        $uniarr=array();
        $mularr=array();
        foreach ($goods_attr_info as $key=>$val)
        {
            if($val['attr_type']=='可选')
            {
              $mularr[$val['attr_name']][]=$val;
            }
            else if($val['attr_type']=='唯一')
            {
                $uniarr[]=$val;
            }
        }
        //获取商品会员价格
        $goods_member_model=M('member_price');
        $goods_member=$goods_member_model->alias('a')->field('a.id,a.price,b.level_name')->join('left join p39_member_level as b on a.level_id=b.id')->where(array(
            'a.goods_id'=>array('eq',$goods_id)
        ))->select();
        $category_model=D('Admin/category');
        //获取该商品的主分类以及主分类的父分类，作为面包屑导航
        $category_info=$category_model->getparent($goods_info['cat_id']);
        $this->assign(array(
            'goods_member'=>$goods_member,
            'goods_pic'=>$goods_pic_info,
             'uniarr'=>$uniarr,
            'mularr'=>$mularr,
            'goods_info'=>$goods_info,
            'category_info'=>$category_info,
            'page_name'=>'商品详情',
            'page_keywords'=>'商品详情',
            'page_description'=>'商品详情',
            'show_nav'=>0
        ));
        $this->display();
    }
//    获取浏览历史
    public function getHistory(){
        $goods_id=I('get.id');
//        进行cookie保存
//        如果原先保存过cookie，就取回原先的值，如果没有，就返回一个空数组开始
//        cookie不能保存数组，所以只能将数组序列化
        $historydata=isset($_COOKIE['history']) ? unserialize(cookie('history')):array();
//         将id插入到第一个位置
        if($goods_id)
        {
            array_unshift($historydata,$goods_id);
//        去重
            $historydata=array_unique($historydata);
//        是否大于6个，只取6个
            if($historydata>5)
            {
                $_history=array_slice($historydata,0,5);
            }
//      序列化数组，存入cookie
            cookie('history',serialize($_history),30*86400);
//        取出商品信息，并且按照取的循序来排列
            $ids=implode(',',$_history);
        }
         else
         {
             $ids=implode(',',$historydata);
         }
        $goods_model=D('Admin/Goods');
        $hisinfo=$goods_model->field('id,goods_name,mid_logo')->where(array(
            'is_on_sale'=>array('eq','是'),
            'is_delete'=>array('eq','否'),
            'id'=>array('in',$ids),
        ))->order("field(id,$ids)")->select();
        echo json_encode($hisinfo);
    }
//    清空浏览历史
    public function cleanHistory(){
        cookie('history',null);
    }
}