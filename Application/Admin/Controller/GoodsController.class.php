<?php
namespace Admin\Controller;
class GoodsController extends CommonController
{
	// 显示和处理表单
	public function add()
	{
      if(IS_POST)
      {
          set_time_limit(0);
         $post=I('post.');
         $model=D('Goods');
         if($model->create($post,1))
         {
            if($model->add())
            {
                $this->success('商品添加成功',U('lst'),2);
                exit();
            }
         }
         $error=$model->getError();
         $this->error($error,U('add'),2);
      }
      else
      {
          //取出会员价格
          $memberlevel=M('member_level')->field('id,level_name')->select();
          //取出主分类的无限级
          $category=D('category')->getTree();
          $this->assign(array(
              'category'=>$category,
              'memberlevel'=>$memberlevel,
              'page_name'=>'添加商品',
              'page_btn_name'=>'商品列表',
              'page_btn_url'=>U('Goods/lst')
          ));
          $this->display();
      }
	}
	
	// 商品列表页
	public function lst()
	{

        $model=D('Goods');
	    $data=$model->search();
	    $category=D('category')->getTree();
	    $this->assign('data',$data['List']);
	    $this->assign('category',$category);
	    $this->assign('page',$data['Show']);
	    $this->assign('Count',$data['Count']);
	    $this->assign(array(
            'page_name'=>'商品列表',
        'page_btn_name'=>'添加商品',
        'page_btn_url'=>U('Goods/add')   
        )
        );
		$this->display();
	}
	public function edit(){
	    $model=D('Goods');
	    if(IS_POST)
        {
            $post=I('post.');
            if($model->create($post,2))
            {
               if($model->save()!==false)
               {
                   $this->success('数据更新成功',U('lst'),2);
                   exit();
               }
            }
            $error=$model->getError();
            $this->error($error,U('edit',array('id'=>$post['id'])),2);
        }
        else
        {
            $id=I('get.id');
            //级别的读取
            $memberlevel=M('member_level')->field('id,level_name')->select();
            $where['goods_id']=array('eq',$id);
            //会员价格的读取
            //有用的数据只有level_id跟price，goods_id钩子函数会提供
            $price=M('member_price')->field('level_id,price')->where($where)->select();
  //因为两个都是二维数组，且同属于一个地方应用，所以这里要用for循环将二维数组合并
            $newdata=array();
            foreach ($memberlevel as $key => $val)
            {
                $newdata[$key]['level_id']=$val['id'];
               foreach ($price as $k=>$v)
               {
                   if($val['id']==$v['level_id'])
                   {
                     $newdata[$key]['price']=$v['price'];
                   }
               }
                $newdata[$key]['level_name']=$val['level_name'];
            }
            $res=$model->find($id);
            //商品相册的读取
            $res1=M('goods_pic')->field('id,mid_pic')->where($where)->select();
            //取出主分类的无限级 制作扩展分类
            $category=D('category')->getTree();
            //取出扩展分类的数据
            $ext_category=M('ext_category')->where($where)->select();
            //取出商品属性的数据 //这里必须用属性表作为主表，否则表单显示不完全
            $type_id=M('goods')->field('type_id')->find($id);
            $where1['type_id']=array('eq',$type_id['type_id']);
            $goods_attr=M('Attribute')->alias('a')->field('a.id as a_id,a.attr_name,a.attr_type,a.attr_option_values,a.type_id,b.*')->join('left join p39_goods_attr as b on a.id=b.attr_id and goods_id='.$id)->where($where1)->select();
            $this->assign('data',$res);
            $this->assign(array(
                'goods_attr'=>$goods_attr,
                'ext_category'=>$ext_category,
                'category'=>$category,
                'mid_pic'=>$res1,
                'price'=>$newdata,
                'page_name'=>'修改商品',
                'page_btn_name'=>'商品列表',
                'page_btn_url'=>U('Goods/lst')
            ));
            $this->display();
        }
    }
    public function del(){  //这边使用的是保存
	    if(IS_AJAX)
        {
            $model=M('Goods');  //因为这边是用虚拟删除，使用的是save方法，用M防止触发save的钩子函数
            $data['id']=I('get.id');
            $data['is_delete']='是';
            $flag=array(
                'flag'=>0
            );
            if($model->save($data)!==false)
            {
                $flag=array(
                    'flag'=>1
                );
            }
            echo json_encode($flag);
        }
    }
    public function recycle()
    {

        $model=D('Goods');
        $data=$model->search(true);
        $category=D('category')->getTree();
        $this->assign('category',$category);
        $this->assign('data',$data['List']);
        $this->assign('page',$data['Show']);
        $this->assign('Count',$data['Count']);
        $this->assign(array(
                'page_name'=>'回收站',
                'page_btn_name'=>'清空回收站',
                'page_btn_url'=>'#'
            )
        );
        $this->display();
    }
    public function delAll(){
     if(IS_AJAX)
     {   //每个ID都要删除三样东西  1.会员价格 2.商品图片 3.商品相册
         $id=I('get.id');
         $allflag=I('get.flag');
         $model=D('Goods');
         $flag=array(
             'flag'=>0
         );
         if($id)  //说明是删除一个
         {
             if($model->delete($id)!==false)
             {
                 $flag=array(
                     'flag'=>1
                 );
             }
         }
         if($allflag=='all')  //说明是删除所有
         {
             $where['is_delete']=array('eq','是');
             $res=$model->field('id')->where($where)->select();
             $ids='';
             foreach ($res as $key => $val)
             {
                $ids.=$val['id'].',';
             }
             $newids=rtrim($ids,',');
             if($model->delete($newids)!==false)
             {
                 $flag=array(
                     'flag'=>1
                 );
             }
         }
         echo json_encode($flag);
     }
    }
    public function delPic(){
        if(IS_AJAX)
        {
            $id=I('get.id'); //这里传入的是商品相册的ID
            $model=M('Goods_pic');
            $res=$model->field('pic,sm_pic,mid_pic,big_pic')->select($id);
            $flag=array(
                'flag'=>0
            );
            if($model->delete($id)!==false)
            {
                foreach ($res as $key=>$val)
                {
                    delAllPhoto($val);
                }
                $flag=array(
                    'flag'=>1
                );
            }
            echo json_encode($flag);

        }
    }
    public function getAttrByAjax(){
        if(IS_AJAX)
        {
            $type_id=I('get.type_id');
            $res=D('Attribute')->getGoodsAttr($type_id);
            echo  json_encode($res);
        }

    }
    public function delAttrByAjax(){
        if(IS_AJAX)
        {
            $model=M('goods_attr');
            $model1=M('goods_number');
            $goods_id=addslashes(I('get.goods_id'));
            $goods_attr_id=I('get.goods_attr_id');
            $flag=array(
                'flag'=>0
            );
            if($model->delete($goods_attr_id)!==false)
            {
                $model1->where(array(
                    'goods_id'=>array('EXP',"=$goods_id and FIND_IN_SET($goods_attr_id,goods_attr_id)")
                ))->delete();
                $flag=array(
                    'flag'=>1
                );
            }
            echo json_encode($flag);
        }

    }
    public function changeTypeByAjax(){
        if(IS_AJAX)
        {
            $goods_id=I('get.goods_id');
            $where['goods_id']=array('eq',$goods_id);
            M('goods_attr')->where($where)->delete();
            M('goods_number')->where($where)->delete();
        }

    }
    public function goodsNumber(){
        //四合一，增删改查统一到这一个里面
        $goods_id=I('get.id');
        $goods_attr_model=M('goods_attr');
        $goods_number_model=M('goods_number');
        $goods_attr_arr=$goods_attr_model->alias('a')->field('b.attr_name')->join('left join p39_attribute as b on a.attr_id=b.id')->
        where(array(
            'goods_id'=>array('eq',$goods_id),
            'attr_type'=>array('eq','可选')
        ))->select();
        $attr_name=array();  //存放属性的类型
        foreach ($goods_attr_arr as $key=>$val)
        {
            if(!in_array($val['attr_name'],$attr_name))
            {
                $attr_name[]=$val['attr_name'];
            }
        }
        if(IS_POST)
        {
            $goods_id=I('post.goods_id');
            $where['goods_id']=$goods_id;
            //修改添加合二为一，每有提交时，先删除原来的再插入
            $goods_number_model->where($where)->delete();
            //添加(修改)操作
          $attr_id=I('post.attr_id');
          $goods_number=I('post.goods_number');

          $rate=count($attr_id)/count($goods_number);
          $_goods_number=array();
          $i=0;
          foreach ($goods_number as $key=>$val)
          {
              $lin_arr=array();
              for($k=0;$k<$rate;$k++)
              {
                  $lin_arr[]=$attr_id[$i];
                  $i++;
              }

              sort($lin_arr,SORT_NUMERIC);
              $_lin_arr=implode(',',$lin_arr);
             $_goods_number['goods_id']=$goods_id;
             $_goods_number['goods_number']=$val;
             $_goods_number['goods_attr_id']=$_lin_arr;
              $goods_number_model->add($_goods_number);

          }
          $this->success('添加成功',U('lst'),2);
        }
        else
        {
            //想法,使用组合的方式展示所有
            //1.选获取有多少种属性类型

         load('@/zuhe');
         //存放属性的值，因为要通过attr_id来分类，所以顺带取出attr_id
         $new=$goods_attr_model->alias('a')->field('a.attr_id,a.attr_value')->join('left join p39_attribute as b on a.attr_id=b.id')->
         where(array(
             'goods_id'=>array('eq',$goods_id),
             'attr_type'=>array('eq','可选')
         ))->select();
            //存放属性的id值，因为要通过attr_id来分类，所以顺带取出attr_id
         $new_ids=$goods_attr_model->alias('a')->field('a.attr_id,a.id')->join('left join p39_attribute as b on a.attr_id=b.id')->
            where(array(
                'goods_id'=>array('eq',$goods_id),
                'attr_type'=>array('eq','可选')
            ))->select();
         $_new=array();
         foreach ($new as $k=>$v)
         {
             $_new[$v['attr_id']][]=$v['attr_value'];
         }
         $_new_ids=array();
         foreach ($new_ids as $k=>$v)
          {
                $_new_ids[$v['attr_id']][]=$v['id'];
          }
          //将索引重置为0开始  因为调用的组合函数要用
         $_new=array_values($_new);
         $_new_ids=array_values($_new_ids);
         $res1=getArrSet($_new);
         $res2=getArrSet($_new_ids);
         //显示与修改合二为一，取出原有值,因为保存是有按顺序的，所以在视图中按顺序遍历即可
            $where['goods_id']=$goods_id;
            $goods_number=$goods_number_model->field('goods_number')->where($where)->select();
         $this->assign(
             array(
                 'goods_number'=>$goods_number,
                 'attr_type'=>$attr_name,
                 'attr_name'=>$res1,
                 'attr_value'=>$res2,
                 'page_name' => '库存量列表',
                 'page_btn_name' => '商品列表',
                 'page_btn_url' => U('lst'),
             )
         );
         $this->display();
        }


    }
}