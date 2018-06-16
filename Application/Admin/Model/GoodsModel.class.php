<?php

namespace Admin\Model;

use Think\Model;

class GoodsModel extends Model
{
    protected $insertFields = 'goods_name,market_price,shop_price,addtime,goods_desc,is_on_sale,brand_id,cat_id,type_id,promote_price,promote_start,promote_end,is_hot,is_ret,is_new,sort_num,is_floor';
    protected $updateFields = 'id,goods_name,market_price,shop_price,addtime,goods_desc,is_on_sale,brand_id,cat_id,type_id,promote_price,promote_start,promote_end,is_hot,is_ret,is_new,sort_num,is_floor';
    //定义验证规则
    protected $_validate = array(
        array('goods_name', 'require', '商品名称不能为空！', 1),
        array('market_price', 'require', '市场价格不能为空！', 1),
        array('market_price', 'currency', '市场价格必须是货币类型！', 1),
        array('shop_price', 'require', '本店价格不能为空！', 1),
        array('shop_price', 'currency', '本店价格必须是货币类型！', 1),

    );

    protected function _before_insert(&$data, $option)
    {
        if ($_FILES['logo']['size'] > 0) //有图片上传
        {
            $cfg = array(
                'rootPath' => WORK_PATH . UPLOAD_PATH
            );
            $upload = new \Think\Upload($cfg);
            $upload->maxSize = 1024 * 1024;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $info = $upload->upload($_FILES);
            if (!$info) {// 上传错误提示错误信息
                $this->error = $upload->getError();
                return false;
            } else {// 上传成功
                $data['logo'] = UPLOAD_PATH . $info['logo']['savepath'] . $info['logo']['savename'];
                $image = new \Think\Image();
                $image->open(WORK_PATH . $data['logo']);
                $data['sm_logo'] = UPLOAD_PATH . $info['logo']['savepath'] . 'sm_' . $info['logo']['savename'];
                $data['mid_logo'] = UPLOAD_PATH . $info['logo']['savepath'] . 'mid_' . $info['logo']['savename'];
                $data['big_logo'] = UPLOAD_PATH . $info['logo']['savepath'] . 'big_' . $info['logo']['savename'];
                $data['mbig_logo'] = UPLOAD_PATH . $info['logo']['savepath'] . 'mbig_' . $info['logo']['savename'];
                $image->thumb(700, 700)->save(WORK_PATH . $data['mbig_logo']);
                $image->thumb(350, 350)->save(WORK_PATH . $data['big_logo']);
                $image->thumb(150, 150)->save(WORK_PATH . $data['mid_logo']);
                $image->thumb(50, 50)->save(WORK_PATH . $data['sm_logo']);
            }
        }
        //修改时间戳
        //修改时间戳 data里的数据有问题，要用I获取post才行
        $data['promote_start'] = strtotime(I('post.promote_start'));
        $data['promote_end'] = strtotime(I('post.promote_end'));
        $data['addtime'] = time();
        $data['goods_desc'] = filterXSS($_POST['goods_desc']);
    }

    protected function _before_update(&$data, $option)
    {
        $id = $option['where']['id'];
//        标记要重新创建索引的商品
        $data['is_updated']=1;
        require_once './sphinxapi.php';
        $sphinx=new \SphinxClient();
        $sphinx->SetServer('localhost',9312);
        $sphinx->UpdateAttributes('goods',array('is_updated'),array($id=>array(1)));









        //商品属性的保存不能删除原来的，再加入新的，因为库存量参考的是ID，一旦库存量确定下来，那么证明商品属性不能再做修改
        //所以这里换一种方法，只能保证每种属性不重复，其次，就算修改成别的属性，也不能删除，也只是修改
        //循环每个goods_attr_id 有就证明是修改，没有就证明是添加操作
        $goods_attr_id = I('post.goods_attr_id');
        $attr_value = I('post.attr_value');
        if ($attr_value) {
            $goods_attr_model = D('goods_attr');
            //先将数组进行整理，整理成二维数组
            $attr_value_arr = array();
            $quchong_arr = array();
            $i = 0;
            foreach ($attr_value as $key => $val) {
                foreach ($val as $k => $v) {
                    $_quchong_arr = array();
                    $_quchong_arr['attr_id'] = $key;
                    $_quchong_arr['attr_value'] = $v;
                    if (!in_array($_quchong_arr, $quchong_arr)) {
                        $quchong_arr[] = $_quchong_arr;
                        $attr_value_arr[$i] = array(
                            'goods_attr_id' => $goods_attr_id[$i],
                            'attr_id' => $key,
                            'attr_value' => $v);
                    } else {
                        $this->error = "可选值重复，请重新选择";
                        return false;
                    }
                    $i++;
                }
            }
        }
        foreach ($attr_value_arr as $key => $val) {
            $new_attr_value = array();
            if ($val['goods_attr_id'] == '')//证明是新增
            {
                if ($val['attr_value'])  //新增里attr_value有值才证明是要增加的
                {
                    $new_attr_value['attr_value'] = $val['attr_value'];
                    $new_attr_value['attr_id'] = $val['attr_id'];
                    $new_attr_value['goods_id'] = $id;
                    $goods_attr_model->add($new_attr_value);
                }
            } else {    //证明是修改

                $new_attr_value['id'] = $val['goods_attr_id'];
                $new_attr_value['attr_value'] = $val['attr_value'];
                $goods_attr_model->save($new_attr_value);
            }
        }

        if ($_FILES['logo']['size'] > 0) //有图片上传
        {
            $cfg = array(
                'rootPath' => WORK_PATH . UPLOAD_PATH
            );
            $upload = new \Think\Upload($cfg);
            $upload->maxSize = 1024 * 1024;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $info = $upload->upload();
            if (!$info) {// 上传错误提示错误信息
                $this->error = $upload->getError();
                return false;
            } else {// 上传成功
                $data['logo'] = UPLOAD_PATH . $info['logo']['savepath'] . $info['logo']['savename'];
                $image = new \Think\Image();
                $image->open(WORK_PATH . $data['logo']);
                $data['sm_logo'] = UPLOAD_PATH . $info['logo']['savepath'] . 'sm_' . $info['logo']['savename'];
                $data['mid_logo'] = UPLOAD_PATH . $info['logo']['savepath'] . 'mid_' . $info['logo']['savename'];
                $data['big_logo'] = UPLOAD_PATH . $info['logo']['savepath'] . 'big_' . $info['logo']['savename'];
                $data['mbig_logo'] = UPLOAD_PATH . $info['logo']['savepath'] . 'mbig_' . $info['logo']['savename'];
                $image->thumb(700, 700)->save(WORK_PATH . $data['mbig_logo']);
                $image->thumb(350, 350)->save(WORK_PATH . $data['big_logo']);
                $image->thumb(150, 150)->save(WORK_PATH . $data['mid_logo']);
                $image->thumb(50, 50)->save(WORK_PATH . $data['sm_logo']);
                //删除旧的图片
                $res = $this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($id);
                delAllPhoto($res);
            }

        }
        $data['goods_desc'] = filterXSS($_POST['goods_desc']);  //商品描述
        //修改时间戳 data里的数据有问题，要用I获取post才行
        $data['promote_start'] = strtotime(I('post.promote_start'));
        $data['promote_end'] = strtotime(I('post.promote_end'));
    }

    public
    function search($flag = false, $pagesize = 5)
    {
        //搜索（条件）
        $gn = I('get.gn');  //商品名称
        $fp = I('get.fp');  //开始价格
        $tp = I('get.tp'); //结束价格
        $ios = I('get.ios'); //是否出售
        $fa = strtotime(I('get.fa')); //开始时间
        $ta = strtotime(I('get.ta')); //结束时间
        $bi = I('get.brand_id'); //所在品牌
        $ci = I('get.ci');//顶级分类ID
        $where = array();
        //商品名称
        if ($gn) {
            $where['a.goods_name'] = array('like', "%$gn%");
        }
        //价格
        if ($fp && $tp) {
            $where['a.shop_price'] = array('between', array($fp, $tp));
        } else if ($fp) {
            $where['a.shop_price'] = array('ngt', $fp);
        } else if ($tp) {
            $where['a.shop_price'] = array('nlt', $tp);
        }
        //出售
        if ($ios) {
            $where['a.is_on_sale'] = array('eq', $ios);
        }
        //时间
        if ($fa && $ta) {
            $where['a.addtime'] = array('between', array($fa, $ta));
        } else if ($fa) {
            $where['a.addtime'] = array('ngt', $fa);
        } else if ($ta) {
            $where['a.addtime'] = array('nlt', $ta);
        }
        if ($bi) {
            $where['a.brand_id'] = array('eq', $bi);
        }
        //顶级分类  扩展分类
        //同时要满足可以搜索到该分类下的所有子分类，这也是算的
        //这里使用自己封装的函数
        if ($ci) {
            $child_id = $this->getGoodsIdBycat($ci);
            $where['a.id'] = array('in', $child_id);
        }

        //排序
        $order = 'a.addtime';
        $oby = 'desc';
        $ob = I('get.orderby');
        if ($ob == 'time_asc') {
            $oby = 'asc';
        } else if ($ob == 'price_desc') {
            $order = 'shop_price';
        } else if ($ob == 'price_asc') {
            $order = 'shop_price';
            $oby = 'asc';
        }
        //与回收站共同使用一个函数，所以加入判断标志作为区分
        if ($flag != false) {
            $where['a.is_delete'] = array('eq', '是');
        } else {
            $where['a.is_delete'] = array('eq', '否');
        }
        //翻页数据
        $count = $this->alias('a')->where($where)->count();
        $Page = new \Think\Page($count, $pagesize);//
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('first', '首页');
        $Page->setConfig('last', '末页');
        $Page->rollPage = 5;
        $show = $Page->show();// 分页显示输出
        //取出数据
        $list = $this->alias('a')->field('a.*,b.brand_name,c.cate_name,GROUP_CONCAT(e.cate_name SEPARATOR "<br/>") as ext_cat_name')->join('left join p39_brand as b on a.brand_id=b.id left join p39_category as c on a.cat_id=c.id left join p39_ext_category as d on d.goods_id=a.id left join p39_category as e on e.id=d.cat_id')->where($where)->order("$order $oby")->group('a.id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $data = array(
            'List' => $list,
            'Show' => $show,
            'Count' => $count
        );
        return $data;
    }

    protected
    function _after_insert($data, $option)
    {
        //添加之后ID在$data['id']中 其他的时候在 $option['where']['id']
        $id = $data['id'];
        //添加商品属性值
        $attr_value = I('post.attr_value');
        if ($attr_value) {
            $goods_attr_model = D('goods_attr');
            $new_attr_value = array();
            foreach ($attr_value as $k => $v) {
                $unique_attr_value = array();
                foreach ($v as $key => $val) {
                    if ($val) {
                        if (!in_array($val, $unique_attr_value)) {

                            $unique_attr_value[] = $val;
                            $new_attr_value['attr_value'] = $val;
                            $new_attr_value['attr_id'] = $k;
                            $new_attr_value['goods_id'] = $id;
                            $goods_attr_model->add($new_attr_value);
                        }
                    }
                }
            }
        }

        //添加扩展分类
        $ext_cat_id = I('post.ext_cat_id');
        if ($ext_cat_id) {
            //去重
            $newdata = array();
            foreach ($ext_cat_id as $val) {
                if (!in_array($val, $newdata)) {
                    $newdata[] = $val;
                }
            }
            $cat_data = array();
            $model = D('Ext_category');
            foreach ($newdata as $val) {
                $cat_data['goods_id'] = $id;
                $cat_data['cat_id'] = $val;
                $model->add($cat_data);
            }
        }

        //添加会员价格
        $memberlevel = I('post.memberlevel');
        if ($memberlevel) {
            $data = array();
            $model = M('member_price');
            foreach ($memberlevel as $key => $val) {
                if (is_numeric($val)) {
                    $data['goods_id'] = $id;
                    $data['level_id'] = $key;
                    $data['price'] = $val;
                    $model->add($data);
                }
            }
        }

        //添加商品相册  判断是否有图片上传，最少要有一个图片上传成功
        //因为在同一个表单里，file的name有2个，会出错，所以这里要改写FILE数组为新的数组
        //然后再进行操作

        $flag = false;
        foreach ($_FILES['pic']['size'] as $key => $val) {
            if ($val > 0) {
                $flag = true;
                break;
            }
        }
        if ($flag)  //有至少一张图片上传
        {
            //这里需要改写file，修改新的类型
            $newFiles = array();
            foreach ($_FILES['pic'] as $key => $val) {
                foreach ($val as $k => $v) {
                    $newFiles[$k][$key] = $v;
                }
            }
            $cfg = array(
                'rootPath' => WORK_PATH . UPLOAD_PATH
            );
            $upload = new \Think\Upload($cfg);
            $upload->maxSize = 1024 * 1024;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $info = $upload->upload($newFiles);
            if (!$info) {// 上传错误提示错误信息
                $this->error = $upload->getError();
                return false;
            } else {// 上传成功
                foreach ($info as $key => $val) {
                    $picdata = array();
                    $picdata['goods_id'] = $id;
                    $picdata['pic'] = UPLOAD_PATH . $val['savepath'] . $val['savename'];
                    $picdata['sm_pic'] = UPLOAD_PATH . $val['savepath'] . 'sm_' . $val['savename'];
                    $picdata['mid_pic'] = UPLOAD_PATH . $val['savepath'] . 'mid_' . $val['savename'];
                    $picdata['big_pic'] = UPLOAD_PATH . $val['savepath'] . 'big_' . $val['savename'];
                    $image = new \Think\Image();
                    $image->open(WORK_PATH . $picdata['pic']);
                    $image->thumb(650, 650)->save(WORK_PATH . $picdata['big_pic']);
                    $image->thumb(350, 350)->save(WORK_PATH . $picdata['mid_pic']);
                    $image->thumb(50, 50)->save(WORK_PATH . $picdata['sm_pic']);
                    M('Goods_pic')->add($picdata);
                }
            }
        }
    }

    protected
    function _after_update($data, $option)
    {
        $goods_id = $data['id'];
        $where['goods_id'] = array('eq', $goods_id);


        //商品扩展分类的保存，逻辑是先把原来的删除，然后再载入新的数据，否则无法实现突然保存一条新的数据作为插入
        $ext_cat_id = I('post.ext_cat_id');
        $ext_category = M('ext_category');
        //先删除原来的
        $ext_category->where($where)->delete();
        //再插入新的
        if ($ext_cat_id) {
            //去重
            $cate_data = array();
            foreach ($ext_cat_id as $val) {
                if (!in_array($val, $cate_data)) {
                    $cate_data[] = $val;
                }
            }
            $cat_data = array();
            $model = D('Ext_category');
            foreach ($cate_data as $val) {
                $cat_data['goods_id'] = $goods_id;
                $cat_data['cat_id'] = $val;
                $model->add($cat_data);
            }
        }
        //商品价格的保存，逻辑是先把原来的删除，然后再载入新的数据，否则无法实现突然保存一条新的数据作为插入

        $memberlevel = I('post.memberlevel');
        $model = M('member_price');
        $data = array();
        //先删除原来的
        $model->where($where)->delete();
        //再插入新的
        foreach ($memberlevel as $key => $val) {
            if (is_numeric($val)) {
                $data['goods_id'] = $goods_id;
                $data['level_id'] = $key;
                $data['price'] = $val;
                $model->add($data);
            }
        }
        //商品相册的修改
        $flag = false;
        foreach ($_FILES['pic']['size'] as $key => $val) {
            if ($val > 0) {
                $flag = true;
                break;
            }
        }
        if ($flag)  //有至少一张图片上传
        {
            //这里需要改写file，修改新的类型
            $newFiles = array();
            foreach ($_FILES['pic'] as $key => $val) {
                foreach ($val as $k => $v) {
                    $newFiles[$k][$key] = $v;
                }
            }
            $cfg = array(
                'rootPath' => WORK_PATH . UPLOAD_PATH
            );
            $upload = new \Think\Upload($cfg);
            $upload->maxSize = 1024 * 1024;
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
            $info = $upload->upload($newFiles);
            if (!$info) {// 上传错误提示错误信息
                $this->error = $upload->getError();
                return false;
            } else {// 上传成功
                foreach ($info as $key => $val) {
                    $picdata = array();
                    $picdata['goods_id'] = $goods_id;
                    $picdata['pic'] = UPLOAD_PATH . $val['savepath'] . $val['savename'];
                    $picdata['sm_pic'] = UPLOAD_PATH . $val['savepath'] . 'sm_' . $val['savename'];
                    $picdata['mid_pic'] = UPLOAD_PATH . $val['savepath'] . 'mid_' . $val['savename'];
                    $picdata['big_pic'] = UPLOAD_PATH . $val['savepath'] . 'big_' . $val['savename'];
                    $image = new \Think\Image();
                    $image->open(WORK_PATH . $picdata['pic']);
                    $image->thumb(650, 650)->save(WORK_PATH . $picdata['big_pic']);
                    $image->thumb(350, 350)->save(WORK_PATH . $picdata['mid_pic']);
                    $image->thumb(50, 50)->save(WORK_PATH . $picdata['sm_pic']);
                    M('Goods_pic')->add($picdata);
                }
            }
        }
    }

    protected
    function _before_delete($option)
    {
        //当删除多个的时候(清空回收站)
        if ($option['where']['id']['1']) {
            $nowId = explode(',', $option['where']['id']['1']);
        } //当删除一个的时候
        else {
            //必须是一个数组才会执行foreach函数
            $nowId = array();
            $nowId[] = $option['where']['id'];
        }
        foreach ($nowId as $val) {
            //删除的时候需要删除三个 1.会员价格 2.商品图片 3.商品相册 4.商品的扩展分类 5.删除商品属性 6.删除库存量
            //1.删除商品的会员价格
            $id = $val;
            $where['goods_id'] = array('eq', $id);
            $model = M('member_price');
            $model->where($where)->delete();
            //2.删除商品图片 使用自己封装的delAllPhoto(传入一维数组)方法
            //取出所在路径
            $res1 = $this->field('logo,sm_logo,mid_logo,big_logo,mbig_logo')->find($id);
            //Goods表中正式删除交给控制器完成
            //图片删除
            delAllPhoto($res1);
            //3.删除商品相册
            $model1 = M('goods_pic');
            $res2 = $model1->field('pic,sm_pic,mid_pic,big_pic')->where($where)->select();
            //遍历二维数组
            foreach ($res2 as $key => $val) {
                delAllPhoto($val);
            }
            //删除表中数据
            $model1->where($where)->delete();
            //4.删除商品的扩展分类
            M('ext_category')->where($where)->delete();
            //5.删除商品属性
            M('goods_attr')->where($where)->delete();
            M('goods_attr')->where($where)->delete();
            //6.删除商品库存量
            M('goods_number')->where($where)->delete();
        }

    }

//搜索一个分类(主分类，扩展分类，以及他们的所有子分类)下的所有商品的ID
    public
    function getGoodsIdBycat($catId)
    {

        //先找出一个主分类(包括子分类)下的所有商品
        $cat_list = D('Admin/category')->getChildren($catId);
        //将主分类的ID与子分类的ID合并，一起进行搜索,此时cat_list既可以看作是主分类，也可以看作是扩展分类;
        $cat_list[] = $catId;
        $where['cat_id'] = array('in', $cat_list);
        $cat_children = $this->field('id')->where($where)->select();
        //因为这个下拉框的搜索内容是主分类跟扩展分类共用的，所以搜索完主分类的ID，再将其看作扩展分类，搜索
        //扩展分类(包括子分类)下的所有商品ID
        $ext_cat_children = M('ext_category')->field('goods_id as id')->where($where)->select();
        $dataarr = array();
        foreach ($cat_children as $val) {
            $dataarr[] = $val['id'];
        }
        foreach ($ext_cat_children as $val) {
            if (!in_array($val['id'], $dataarr)) {
                $dataarr[] = $val['id'];
            }
        }
        return $dataarr;
    }

    /**获取疯狂抢购的数据
     * @return string
     */
    public function getPromote($limitsize = 5)
    {
        $nowtime = time();
        $where['promote_price'] = array('gt', '0');
        $where['promote_start'] = array('elt', $nowtime);
        $where['promote_end'] = array('egt', $nowtime);
        $where['is_on_sale'] = array('eq', '是');
        $where['is_delete'] = array('eq', '否');
        $res = $this->where($where)->order('sort_num')->limit($limitsize)->select();
        return $res;
    }

    /**获取其他三个信息
     *  热卖|推荐|新品
     * @return string
     */
    public function getOtherRet($retType, $limitsize = 5)
    {

        $where['is_' . $retType] = array('eq', '是');
        $where['is_on_sale'] = array('eq', '是');
        $where['is_delete'] = array('eq', '否');
        $res = $this->where($where)->order('sort_num')->limit($limitsize)->select();
        return $res;
    }

    /**获取中间楼层的信息推荐
     * @return string
     */
    public function getFloorData()
    {
        $floor = S('floor');
        if ($floor) {
            return $floor;
        } else {
            //        先获取顶级分类
            $cate_model = D('category');
            $where['is_floor'] = array('eq', '是');
            $where['pid'] = array('eq', 0);
            $floor = $cate_model->where($where)->select();
//        根据顶级分类再获取顶级分类下的非推荐分类
            foreach ($floor as $key => $val) {
                $where1['is_floor'] = array('eq', '否');
                $where1['pid'] = array('eq', $val['id']);
                $floor[$key]['noret'] = $cate_model->where($where1)->select();
            }
//          根据顶级分类再获取顶级分类下的推荐分类
            foreach ($floor as $key => $val) {
                $goods_ids = $this->getGoodsIdBycat($val['id']);
//                     获取处理商品品牌列表（只需要在分类下商品的品牌即可）
//     select b.id,b.brand_name,b.logo from p39_goods as a left join p39_brand as b on a.brand_id=b.id
                $where3['a.id'] = array('in', $goods_ids);
                $where3['a.brand_id'] = array('gt', 0);
                $brand_info = $this->alias('a')->field('distinct b.id,b.logo,b.site_url')->join('left join p39_brand as b on a.brand_id=b.id')->where($where3)->limit(9)->select();
                $floor[$key]['brand'] = $brand_info;

                $where1['is_floor'] = array('eq', '是');
                $where1['pid'] = array('eq', $val['id']);
                $floor[$key]['ret'] = $cate_model->where($where1)->select();
                //           获取二级推荐分类下的所有商品
                foreach ($floor[$key]['ret'] as $k => $v) {
//                     获得该分类下所有商品id
                    $goods_ids = $this->getGoodsIdBycat($v['id']);
                    $where2['id'] = array('in', $goods_ids);
                    $where2['is_on_sale'] = array('eq', '是');
                    $where2['is_floor'] = array('eq', '是');
                    $where2['is_delete'] = array('eq', '否');
//                    获取商品信息
                    $goods_ids_arr = $this->where($where2)->order('sort_num')->limit(8)->select();
                    $floor[$key]['ret'][$k]['goods'] = $goods_ids_arr;

                }
            }
            S('floor', $floor, 3600);  //设置缓存
            return $floor;
        }

    }

//    根据商品ID获取一个商品的会员价格
    public function getMemberPrice($goods_id)
    {
        $nowtime = time();
//        先判断是否登陆
        $user_id = session('m_id');
//        商品的出售价
        $shop_price = $this->field('shop_price')->find($goods_id);
//        商品的促销价
        $promote_price = $this->field('promote_price')->where(array(
            'promote_price'=>array('gt',0),
            'promote_start' => array('elt', $nowtime),
            'promote_end' => array('egt', $nowtime),
            'id' => array('eq', $goods_id)
        ))->find();
        if ($promote_price['promote_price']) {
            $nowprice = min($shop_price['shop_price'], $promote_price['promote_price']);
        } else {
            $nowprice = $shop_price['shop_price'];
        }
        if ($user_id) {

            $member_model = D('member_price');
            $price = $member_model->field('price')->where(array(
                'level_id' => array('eq', session('member_id')),
                'goods_id' => array('eq', $goods_id)
            ))->find();
//          如果有设置会员价格，就返回会员价格，如果没设置就直接返回
            if ($price) {
//              比较哪个价格更低，更低的返回
                if ($price['price'] > $nowprice) {
                    return $nowprice;
                } else {
                    return $price['price'];
                }

            } else {
                return $nowprice;
            }
        } else {
            return $nowprice;
        }
    }
//    根据分类ID，以及get到的搜索条件搜索某一页下的商品
    public function cat_search($cat_id,$pagesize=8){
//           搜索条件
//        分类条件的设置
        $goods_id=$this->getGoodsIdBycat($cat_id);
        if($goods_id)
        {
            $where['a.id']=array('in',$goods_id);
        }
        else{
            $where['a.id']=array('eq',0);
        }

//        品牌条件的设置
        $brand_id=I('get.brand');
        if($brand_id)
        {
            $where['a.brand_id']=array('eq',$brand_id);
        }

//        价格条件的设置
        $price=I('get.price');
        if($price)
        {
            $price=explode('-',$price);
            $where['a.shop_price']=array('between',$price);
        }
//        商品属性的搜索条件
        $goods_attr_model=D('goods_attr');
        $attrgoodsId=NULL;    //用来存最终符合的商品ID
        foreach ($_GET as $key=>$val)
        {
          //开头名称为attr_的都是商品属性
//            比如一个属性 attr_10/黑色-颜色
//              搜索goods_attr 表时要用到 attr_value attr_id
            if(strpos($key,'attr_')===0)
            {
//                获取attr_id
                $attrid=str_replace('attr_','',$key);
//                获取attr_value
                $_attrvalue=strrchr($val,'-');
                $attrvalue=str_replace($_attrvalue,'',$val);
//                根据这些条件，搜索出商品ID
                $goodsIdByattr=$goods_attr_model->field('GROUP_CONCAT(goods_id) gid')->where(array(
                    'attr_value'=>array('eq',$attrvalue),
                    'attr_id'=>array('eq',$attrid),
                ))->find();
//                如果有商品
                if($goodsIdByattr)
                {
                    $gid=explode(',',$goodsIdByattr['gid']);
                  if($attrgoodsId===NULL)
                  {
//                      证明是第一个属性
                      $attrgoodsId=$gid;
                  }
                  else
                  {
//                      与上一个结果集取交集
                      $attrgoodsId=array_intersect($attrgoodsId,$gid);
                      if(empty($attrgoodsId))
                      {
//                          如果为空，证明没有符合的商品，可以结束了
                          $where['a.id']=array('eq',0);
                          break;
                      }
                  }
                }

//                如果没有商品，就没有搜索下去的意义了。直接退出，给予一个不可能条件
                else
                {
                    $attrgoodsId=NULL;  //将先前设置的设置为空
                    $where['a.id']=array('eq',0);
                    break;
                }
            }
        }
        if($attrgoodsId)
        {
//                如果最后还能进到这里，证明有所有条件都有足的商品
            //            最终要跟分类取交集
//            $where['a.id']=array('in',$attrgoodsId);
            $attrgoodsId=array_intersect($attrgoodsId,$goods_id);
            if(empty($attrgoodsId))
            {
                $where['a.id']=array('eq',0);
            }
            else
            {
                $where['a.id']=array('in',$attrgoodsId);
            }
        }
//        排序制作
        $oderby='xl';
        $oderway='desc';
        $odby=I('get.odby');
        if($odby)
        {
            if($odby=='addtime')
            {
                $oderby='a.addtime';
            }
            else if(strpos($odby,'price_')===0)
            {
                $oderby='a.shop_price';
                if($odby=='price_asc')
                {
                    $oderway='asc';
                }
            }




        }
//        制作翻页
        //翻页数据
//        $count = $this->alias('a')->where($where)->count();
        $count=$this->alias('a')->field("COUNT('a.id') goods_count,GROUP_CONCAT(a.id) goods_id")->where($where)->find();
        $count['goods_id']=explode(',',$count['goods_id']);
        $data['count']=$count['goods_count'];
        $data['goods_id']=$count['goods_id'];
        $Page = new \Think\Page($data['count'], $pagesize);//
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('first', '首页');
        $Page->setConfig('last', '末页');
        $Page->rollPage = 5;
        $data['page'] = $Page->show();// 分页显示输出
//         获取数据
        $where['is_on_sale']=array('eq','是');
        $where['is_delete']=array('eq','否');
        
//        并且根据销量降序排列
        $data['data']=$this->alias('a')->field('a.id,a.goods_name,a.mid_logo,a.shop_price,COUNT(distinct c.id) comment,SUM(distinct b.goods_number) xl')->join('left join p39_comment as c on a.id=c.goods_id left join p39_order_goods as b on (a.id=b.goods_id AND b.order_id IN (select id from p39_order where pay_status="是"))')->where($where)->group('a.id')->order("$oderby $oderway")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        return $data;

    }
//    根据关键字，以及get到的搜索条件搜索某一页下的商品
//    如果是根据关键字，就没有分类这一说了
    public function key_search($keyName,$pagesize=8){
//           搜索条件
//        关键字条件的设置
//          $goodsId=$this->alias('a')->field('GROUP_CONCAT(distinct a.id) gids')->join('left join p39_goods_attr b on a.id=b.goods_id')->where(array(
//              'a.is_on_sale'=>array('eq','是'),
//              'a.is_delete'=>array('eq','否'),
//              'a.goods_name'=>array('EXP',"like '%$keyName%' or a.goods_desc like '%$keyName%' or b.attr_value like '%$keyName%'")
//          ))->find();
//          使用sphinx搜索，搜索传入的商品id即可
        $goodsId=$keyName;
        if($goodsId){
//            $goods_id=explode(',',$goodsId['gids']);
            $where['a.id']=array('in',$goodsId);
        }
        else
        {
            $where['a.id']=array('eq',0);
        }
//        品牌条件的设置
        $brand_id=I('get.brand');
        if($brand_id)
        {
            $where['a.brand_id']=array('eq',$brand_id);
        }
//        价格条件的设置
        $price=I('get.price');
        if($price)
        {
            $price=explode('-',$price);
            $where['a.shop_price']=array('between',$price);
        }
//        商品属性的搜索条件
        $goods_attr_model=D('goods_attr');
        $attrgoodsId=NULL;    //用来存最终符合的商品ID
        foreach ($_GET as $key=>$val)
        {
            //开头名称为attr_的都是商品属性
//            比如一个属性 attr_10/黑色-颜色
//              搜索goods_attr 表时要用到 attr_value attr_id
            if(strpos($key,'attr_')===0)
            {
//                获取attr_id
                $attrid=str_replace('attr_','',$key);
//                获取attr_value
                $_attrvalue=strrchr($val,'-');
                $attrvalue=str_replace($_attrvalue,'',$val);
//                根据这些条件，搜索出商品ID
                $goodsIdByattr=$goods_attr_model->field('GROUP_CONCAT(goods_id) gid')->where(array(
                    'attr_value'=>array('eq',$attrvalue),
                    'attr_id'=>array('eq',$attrid),
                ))->find();

//                如果有商品
                if($goodsIdByattr)
                {
                    $gid=explode(',',$goodsIdByattr['gid']);
                    if($attrgoodsId===NULL)
                    {
//                      证明是第一个属性
                        $attrgoodsId=$gid;
                    }
                    else
                    {
//                      与上一个结果集取交集
                        $attrgoodsId=array_intersect($attrgoodsId,$gid);
                        if(empty($attrgoodsId))
                        {
//                          如果为空，证明没有符合的商品，可以结束了
                            $where['a.id']=array('eq',0);
                            break;
                        }
                    }
                }

//                如果没有商品，就没有搜索下去的意义了。直接退出，给予一个不可能条件
                else
                {
                    $attrgoodsId=NULL;  //将先前设置的设置为空
                    $where['a.id']=array('eq',0);
                    break;
                }
            }
        }
        if($attrgoodsId)
        {
//                如果最后还能进到这里，证明有所有条件都有足的商品
            //            最终要跟分类取交集
//            $where['a.id']=array('in',$attrgoodsId);
            $attrgoodsId=array_intersect($attrgoodsId,$goodsId);
            if(empty($attrgoodsId))
            {
                $where['a.id']=array('eq',0);
            }
            else
            {
                $where['a.id']=array('in',$attrgoodsId);
            }
        }
//        排序制作
        $oderby='xl';
        $oderway='desc';
        $odby=I('get.odby');
        if($odby)
        {
            if($odby=='addtime')
            {
                $oderby='a.addtime';
            }
            else if(strpos($odby,'price_')===0)
            {
                $oderby='a.shop_price';
                if($odby=='price_asc')
                {
                    $oderway='asc';
                }
            }




        }
//        制作翻页
        //翻页数据
//        $count = $this->alias('a')->where($where)->count();
        $count=$this->alias('a')->field("COUNT('a.id') goods_count,GROUP_CONCAT(a.id) goods_id")->where($where)->find();
        $count['goods_id']=explode(',',$count['goods_id']);
        $data['count']=$count['goods_count'];
        $data['goods_id']=$count['goods_id'];
        $Page = new \Think\Page($data['count'], $pagesize);//
        $Page->setConfig('prev', '上一页');
        $Page->setConfig('next', '下一页');
        $Page->setConfig('first', '首页');
        $Page->setConfig('last', '末页');
        $Page->rollPage = 5;
        $data['page'] = $Page->show();// 分页显示输出
//         获取数据
        $where['is_on_sale']=array('eq','是');
        $where['is_delete']=array('eq','否');

//        并且根据销量降序排列
        $data['data']=$this->alias('a')->field('a.id,a.goods_name,a.mid_logo,a.shop_price,COUNT(distinct c.id) comment,SUM(distinct b.goods_number) xl')->join('left join p39_comment as c on a.id=c.goods_id left join p39_order_goods as b on (a.id=b.goods_id AND b.order_id IN (select id from p39_order where pay_status="是"))')->where($where)->group('a.id')->order("$oderby $oderway")->limit($Page->firstRow . ',' . $Page->listRows)->select();

        return $data;

    }
}









