<?php
/**
 * Date: 2017/7/16/0016
 * Time: 20:53
 */

namespace Home\Model;

use Think\Model;

class CommentReplyModel extends Model
{
    protected $insertFileds = array('comment_id','content');
    protected $_validate = array(
        array('comment_id', 'require', '参数错误', 1, 'regex', 3),
        array('content', '1,300', '评论内容只能在1-300个字符', 1, 'length', 3),
    );
    protected function _before_insert(&$data,$option){

        if(!session('m_id'))
        {
            $this->error='必须先登录';
            return false;
        }
        $data['content']=I('post.content');
        $data['member_id']=session('m_id');
        $data['addtime']=time();
    }
}