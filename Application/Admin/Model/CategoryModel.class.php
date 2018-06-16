<?php

namespace Admin\Model;

use Think\Model;

class CategoryModel extends Model
{
    protected $insertFields = array('cate_name', 'pid', 'is_floor');
    protected $updateFields = array('id', 'cate_name', 'pid', 'is_floor');
    protected $_validate = array(
        array('cate_name', 'require', '分类名称不能为空！', 1, 'regex', 3),
    );

    // 添加前
    protected function _before_insert(&$data, $option)
    {

    }

    // 修改前
    protected function _before_update(&$data, $option)
    {

    }

    // 删除前
    protected function _before_delete($option)
    {
        $pid = $option['where']['id'];
        $ids = $this->getChildren($pid);
        $newids = implode(',', $ids);
        //这里用M方法删除，直接用$this会循环调用钩子函数
        M('Category')->delete($newids);
    }
    /************************************ 其他方法 ********************************************/
    //通过一个中间层(可有可无，封装程度的不同而已)
    public function getTree()
    {
        $res = $this->select();
        return $this->_getTree($res);
    }

    //生成无限级树
    public function _getTree($data, $pid = 0, $level = 0)
    {
        static $ret1 = array();
        foreach ($data as $key => $val) {
            if ($val['pid'] == $pid) {
                $val['level'] = $level;
                $ret1[] = $val;
                $this->_getTree($data, $val['id'], $level + 1);
            }
        }
        return $ret1;
    }

    //通过一个中间层(可有可无，封装程度的不同而已)
    public function getChildren($pid)
    {
        $res = $this->select();
        //因为一个页面里有可能多次调用该方法，所以要加入一个flag,每一次新的执行
        //就清空一次ret数组
        return $this->_getChildren($res, $pid, true);
    }

    //通过一个ID，获得该分类下的所有子ID
    public function _getChildren($res, $pid, $flag = false)
    {
        static $ret = array();  //静态数组在一次页面中不会改变自己的值
        if ($flag) {
            $ret = array();
        }
        foreach ($res as $key => $val) {
            if ($val['pid'] == $pid) {
                $ret[] = $val['id'];
                $this->_getChildren($res, $val['id']);
            }
        }
        return $ret;
    }

    public function getNavData()
    {
        //前台导航页数据的获取
        $sbtn = S('btn');  //检测是否已经设置缓存，设置的话就直接返回，否则再重新从数据库获取
        if ($sbtn) {
            return $sbtn;
        } else {


            $nav = array();
            $allData = $this->select();
            foreach ($allData as $key => $val) {
//            获取到顶级权限的数据
                if ($val['pid'] == 0) {
                    foreach ($allData as $k => $v) {
//                    获取到二级权限的数据
                        if ($val['id'] == $v['pid']) {

//                        获取三级权限的数据
                            foreach ($allData as $k1 => $v1) {
                                if ($v['id'] == $v1['pid']) {
                                    $v['children'][] = $v1;
                                }
                            }
                            $val['children'][] = $v;
                        }
                    }
                    $btn[] = $val;
                }
            }
            S('btn', $btn, 3600);  //设置缓存
            return $btn;
        }
    }

    /**获得一个分类的所有父分类直到顶级分类
     * @return array
     */
    public function getparent($cat_id)
    {
        static $parentinfo = array();
        $infoarr = $this->field('id,cate_name,pid')->find($cat_id);
        $parentinfo[] = $infoarr;
        if ($infoarr['pid'] > 0) {
            //证明还有父级
            $this->getparent($infoarr['pid']);
        }
        return $parentinfo;
    }
    //显示商品筛选条件
    public function getsearchBygoodsId($goods_ids)
    {
        $ret=array();
//             1.获取品牌
        $goods_model=D('Admin/goods');
//
//        $goods_ids = $goods_model->getGoodsIdBycat($cat_id);  //返回的是一个数组
//                     获取处理商品品牌列表（只需要在分类下商品的品牌即可）
//     select b.id,b.brand_name,b.logo from p39_goods as a left join p39_brand as b on a.brand_id=b.id
        $where['a.id'] = array('in', $goods_ids);
        $where['a.brand_id'] = array('gt', 0);

        $brand_info = $goods_model->alias('a')->field('distinct b.id,b.brand_name')->join('left join p39_brand as b on a.brand_id=b.id')->where($where)->limit(9)->select();
        $ret['brand']=$brand_info;
//            2 .获取价格区间
//        获得最高价最低价
         $goods_info=$goods_model->field('MAX(shop_price) max_price,MIN(shop_price) min_price')->where(array(
             'id'=>array('in',$goods_ids)
         ))->find();
//         计算商品总量
         $goodsCount=count($goods_ids);
        $priceSetion=$goods_info['max_price']-$goods_info['min_price'];
//        商品总量大于6 才有价格区间，否则没有
        if($goodsCount>6)
        {
//            根据极差来决定分段
            if($priceSetion < 100)
                $setion=2;
            elseif($priceSetion>=100&&$priceSetion<1000)
                $setion=4;
            elseif($priceSetion>=1000&&$priceSetion<10000)
                $setion=6;
            else
                $setion=7;
//            获得每段的价格区间
            $prSetion=ceil($priceSetion/$setion);
            $priarr=array();
            $floorPrice=(int)$goods_info['min_price'];
            for ($i=0;$i<$setion;$i++)
            {
//                构造分段
                if($i!=0)
                {
                    $finalprice=$floorPrice+$prSetion-1;
                }else
                {
                    $finalprice=$floorPrice+$prSetion;
                }
               $priarr[]=$floorPrice.'-'.$finalprice;
               $floorPrice=$finalprice+1;
            }
            $ret['price']=$priarr;
        }
//            3.获取商品属性
//          select DISTINCT a.attr_id,a.attr_value,b.attr_name from p39_goods_attr as a LEFT JOIN p39_attribute as b on a.attr_id=b.id
        $goods_attr_model=D('goods_attr');
        $goods_attr_info=$goods_attr_model->alias('a')->field('DISTINCT a.attr_id,a.attr_value,b.attr_name')->join('LEFT JOIN p39_attribute as b on a.attr_id=b.id')->where(array(
            'a.goods_id'=>array('in',$goods_ids)
        ))->select();
         $_goods_attr_info=array();
         foreach ($goods_attr_info as $key=>$val)
        {
             $_goods_attr_info[$val['attr_name']][]=array($val['attr_id'],$val['attr_value']);
        }
        $ret['attr']=$_goods_attr_info;
         return $ret;
    }
}