<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model
{
	protected $insertFields = array('username','password','cpassword','captcha','must_click');
	protected $updateFields = array('id','username','password','cpassword','captcha','must_click');
	protected $_validate = array(
        array('must_click', 'require', '用户注册协议必须同意！', 1, 'regex', 3),
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '', '用户名已存在！', 1, 'unique', 3),
		array('username', '3,30', '用户名只能在3到30个字符之间！', 1, 'length', 3),
        array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		array('password', '6,20', '密码只能在6到20个字符之间！', 1, 'length', 3),
		array('cpassword', 'password', '前后密码不一致！', 1, 'confirm', 3),
        array('captcha', 'require', '验证码不能为空！', 1, 'regex', 1),
        array('captcha', 'check_verify', '验证码错误', 1, 'callback'),
	);
    public $_login_validate = array(
        array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
        array('password', 'require', '密码不能为空！', 1, 'regex', 3),
        array('captcha', 'require', '验证码不能为空！', 1, 'regex', 3),
        array('captcha', 'check_verify', '验证码错误', 1, 'callback'),
    );

    public function _before_insert(&$data,$option)
    {
       $data['password']=md5($data['password']);
    }
    //因为没有数据表login，是用模型必须有login数据表才行，所以只能放在一个有数据表的模型中
    public function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
    public function login(){
    $username=I('post.username');
    $password=I('post.password');
    $where['username']=array('eq',$username);
    $res1=$this->where($where)->find();
    if($res1)
    {
        if($res1['password']==md5($password))
        {
            //都通过证明登陆成功，记录一下session;
            session('m_id',$res1['id']);
            session('m_username',$res1['username']);
            session('m_face',$res1['face']);
//            获取会员的等级 存入session
             $jifen=$res1['jifen'];
             $member_level_model=D('member_level');
             $member_id=$member_level_model->where(array(
                 'jifen_bottom'=>array('elt',$jifen),
                 'jifen_top'=>array('egt',$jifen)
             ))->find();
             session('member_id',$member_id['id']);
//             将cookie里的购物车整合到数据库中
            $cart=D('Cart');
            $cart->removeCookietoDb();
           return true;
        }
        else
        {
            $this->error='密码错误';
            return false;
        }
    }
    else
    {
        $this->error='用户名不存在';
        return false;
    }
    }
    public function logout(){
//        退出清除session
        session(null);
    }
}