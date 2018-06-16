<?php
/**
 * Date: 2017/7/14/0014
 * Time: 17:37
 */
namespace Home\Controller;
class MyController extends CommonController {
    public function order()
    {
        if(!session('m_id'))
        {
          session('returnurl',U('My/order'));
          $this->error('请先登陆',U('Member/login'),2);
        }
        $model=D('Admin/order');
        $data=$model->search();
        $this->assign(array(
            'yescount'=>$data['yescount'],
            'nocount'=>$data['nocount'],
            'count' => $data['count'],
            'data' => $data['data'],
            'page' => $data['page'],
        ));
        $this->assign(array(

            'show_nav'=>0,
            'page_name'=>'我的订单',
        ));
        $this->display();
    }
    public function del()
    {
        if (IS_AJAX) {
            $id = I('get.id');
            $model = D('Admin/order');
            $m_id=session('m_id');
            $res=$model->where(array(
                'member_id'=>array('eq',$m_id),
                'id'=>array('eq',$id),
                'pay_status'=>array('eq','否')
            ))->find();
            if($res)
            {
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
        }
    }
}