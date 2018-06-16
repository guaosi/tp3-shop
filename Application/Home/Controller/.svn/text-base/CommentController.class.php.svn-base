<?php
namespace Home\Controller;
class CommentController extends EmptyController
{
     public function add(){
         if(IS_POST)
         {
             $Comment_model=D('Comment');
             if($Comment_model->create())
             {
                 $id=$Comment_model->add();
                 if($id)
                 {
                     $this->success(array(
                         'id'=>$id,
                         'm_id'=>session('m_id'),
                         'm_username'=>session('m_username'),
                         'm_face'=>session('m_face'),
                          'addtime'=>date("Y-m-d H:i:s"),
                         'star'=>I('post.star'),
                         'content'=>I('post.content'),
                     ),'',true);
                     exit();
                 }
             }
             $this->error($Comment_model->getError(),'',true);
         }
     }
     public function getCommentByAjax(){
         if(IS_AJAX)
         {
             $model=D('Comment');
             $goods_id=I('get.goods_id');
             $info=$model->getComment($goods_id);
             echo json_encode($info);
         }

     }
     public function reply(){
         if(IS_AJAX)
         {
             $model=D('CommentReply');
             if($model->create())
             {
                 if($model->add())
                 {
                     $this->success(array(
                         'm_username'=>session('m_username'),
                         'm_face'=>session('m_face'),
                         'addtime'=>date("Y-m-d H:i:s"),
                         'content'=>I('post.content'),
                     ),'',true);
                     exit();
                 }
             }
             $this->error($model->getError(),'',true);
         }

     }
     public function usefulByAjax(){
         $Comment_model=D('Comment');
         if($Comment_model->usefuladd())
         {
             $this->success('有用+1~','',true);
         }
         $this->error($Comment_model->getError(),'',true);
     }
}