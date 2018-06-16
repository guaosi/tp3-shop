<?php
/**
 * Date: 2017/7/16/0016
 * Time: 20:53
 */

namespace Home\Model;

use Think\Model;

class CommentModel extends Model
{
    protected $insertFileds = array('goods_id', 'star', 'content');
    protected $_validate = array(
        array('goods_id', 'require', '参数错误', 1, 'regex', 3),
        array('star',array(1,2,3,4,5),'星级必须是1-5之间',1,'in'),
        array('content', '1,300', '评论内容只能在1-300个字符', 1, 'length', 3),
    );
    protected function _before_insert(&$data,$option){
        $m_id=session('m_id');
        if(!$m_id)
        {
            $this->error='必须先登录';
            return false;
        }
        $goods_id=$data['goods_id'];
//        判断是否已经购买，只有付款成功的才能评论
        $order_model=D('order');
        $res=$order_model->alias('a')->join('left join p39_order_goods as b on a.id=b.order_id')->where(array(
            'a.member_id'=>array('eq',$m_id),
            'a.pay_status'=>array('eq','是'),
            'b.goods_id'=>array('eq',$goods_id)
        ))->select();
        if(!$res)
        {
            $this->error='您还没有买过这件商品，所以不能评论噢';
            return false;
        }
//        判断是否已经评论过了，一个商品只能评论一次
        $comment_model=M('comment');
        $res1=$comment_model->where(array(
            'member_id'=>array('eq',$m_id),
            'goods_id'=>array('eq',$goods_id)
        ))->select();
        if($res1)
        {
            $this->error='您已经评论过了,一件商品只能评论一次噢';
            return false;
        }

        $data['content']=I('post.content');
        $data['member_id']=session('m_id');
        $data['addtime']=time();
//        商品印象

        $yx_name=I('post.yx_name');
        $yx_name=str_replace('，',',',$yx_name);
        $_yx_name=explode(',',$yx_name);
        $yx_model=M('yinxiang');
       $yx_id=I('post.yx_id');
        foreach ($yx_id as $key=>$val)
        {
            $yx_model->where(array('id'=>array('eq',$val)))->setInc('yx_count',1);
        }

        foreach ($_yx_name as $key=>$val)
        {
            $val=trim($val);
            if(empty($val))
            {
                continue;
            }
//            先查看是否已经存在这个印象，存在就加1，不存在增加
          $info=$yx_model->where(array(
              'goods_id'=>array('eq',$goods_id),
              'yx_name'=>array('eq',$val)
          ))->find();
          if($info)
          {
              $yx_model->where(array(
                  'goods_id'=>array('eq',$goods_id),
                  'yx_name'=>array('eq',$val)
              ))->setInc('yx_count',1);
          }
          else
          {
              $yx_model->add(array(
                  'goods_id'=>$goods_id,
                  'yx_name'=>$val,
                  'yx_count'=>1
              ));
          }

        }
    }
    public function getComment($goods_id,$pagesize=5){
         $where['a.goods_id']=array('eq',$goods_id);
//         获取商品总数
         $count=$this->alias('a')->where($where)->count();
         $pagecount=ceil($count/$pagesize);
//         偏移量
        $p=max(1,(int)I('get.page',1));
        $offset=($p-1)*$pagesize;
//        当当前页数为1时，取出好评率以及商品印象
        if($p==1)
        {
//         计算好评率
            $commentinfo=$this->field('star')->where(array(
                'goods_id'=>array('eq',$goods_id)
            ))->select();
            $hao=0;
            $zhong=0;
            $cha=0;
            foreach ($commentinfo as $key=>$val)
            {
                if($val['star']==3)
                {
                    $zhong++;
                }
                elseif($val['star']>3)
                {
                    $hao++;
                }
                else
                {
                    $cha++;
                }
            }
            $zong=$hao+$zhong+$cha;
            $hao=round($hao/$zong,2)*100;
            $zhong=round($zhong/$zong,2)*100;
            $cha=round($cha/$zong,2)*100;
//             计算印象
            $yxmodel=D('yinxiang');
            $yx_info=$yxmodel->field('id,yx_name,yx_count')->where(array(
                'goods_id'=>$goods_id
            ))->order('yx_count desc')->limit(10)->select();
            $data['hao']=$hao;
            $data['zhong']=$zhong;
            $data['cha']=$cha;
            $data['yx']=$yx_info;

        }
//      取出数据
        $data['data']=$this->alias('a')->field("a.id,a.content,FROM_UNIXTIME(a.addtime, '%Y-%m-%d %H:%i:%s') as addtime,a.star,a.clicked,b.username,b.face,COUNT(c.id) as reply")->join('left join p39_member as b on a.member_id=b.id left join p39_comment_reply as c on a.id=c.comment_id')->where($where)->group('a.id')->limit("$offset,$pagesize")->order('a.id desc')->select();
        $data['pagecount']=$pagecount;
        $comment_reply=D('comment_reply');
        foreach ($data['data'] as $key => $val)
        {
              $data['data'][$key]['comment_reply']=$comment_reply->alias('a')->field("a.content,FROM_UNIXTIME(a.addtime, '%Y-%m-%d %H:%i:%s') as addtime,b.username,b.face")->where(array(
                  'a.comment_id'=>array('eq',$val['id']),
              ))->join('left join p39_member as b on a.member_id=b.id')->order('a.addtime desc')->select();
        }
         return $data;
    }
    public function usefuladd(){
          $m_id=session('m_id');
//          判断用户是否登陆
            if(!$m_id)
            {
                $this->error='必须先登录';
                return false;
            }
//            判断这条评论这个用户是否已经点击过
            $comment_id=I('get.comment_id');
            $comment_useful_model=M('comment_useful');
            $res=$comment_useful_model->where(array(
                'member_id'=>array('eq',$m_id),
                'comment_id'=>array('eq',$comment_id)
            ))->find();
            if($res)
            {
                $this->error='您已经认为这条评论有用过了噢~';
                return false;
            }
//            都没有，就添加入库
        $comment_useful_model->add(array(
            'member_id'=>$m_id,
            'comment_id'=>$comment_id
        ));
            $this->where(array(
                'id'=>array('eq',$comment_id)
            ))->setInc('clicked',1);
            return true;

    }
}