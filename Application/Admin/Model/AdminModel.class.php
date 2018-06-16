<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
	protected $insertFields = array('username','password','cpassword','captcha');
	protected $updateFields = array('id','username','password','cpassword');
	protected $_validate = array(
		array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
		array('username', '', '用户名已存在！', 1, 'unique', 3),
		array('username', '1,30', '用户名的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		array('cpassword', 'password', '前后密码不一致！', 1, 'confirm', 3),
	);
    public $_login_validate = array(
        array('username', 'require', '用户名不能为空！', 1, 'regex', 3),
        array('password', 'require', '密码不能为空！', 1, 'regex', 3),
        array('captcha', 'require', '验证码不能为空！', 1, 'regex', 3),
        array('captcha', 'check_verify', '验证码错误', 1, 'callback'),
    );

	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($username = I('get.username'))
			$where['username'] = array('like', "%$username%");
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('first','首页');
        $page->setConfig('last','末页');
		$data['page'] = $page->show();
        $data['count']=$count;
		/************************************** 取数据 ******************************************/
//		select a.id,a.username,GROUP_CONCAT(c.role_name) as role_name from p39_admin as a left join p39_admin_role as b on a.id=b.admin_id left join p39_role as c on c.id=b.role_id GROUP BY a.id
		$data['data'] = $this->alias('a')->field('a.id,a.username,GROUP_CONCAT(c.role_name) as role_name')->join('left join p39_admin_role as b on a.id=b.admin_id left join p39_role as c on c.id=b.role_id')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
	    unset($data['cpassword']);//不需要cpassword
	    $data['password']=md5($data['password']);
	}
    // 添加后
    protected function _after_insert($data, $option)
    {
        $id=$data['id'];
        $role_id=I('post.role_id');
        $_role_id=array();
        $admin_role_model=M('admin_role');
        foreach ($role_id as $key=>$val)
        {
            $_role_id['admin_id']=$id;
            $_role_id['role_id']=$val;
            $admin_role_model->add($_role_id);
        }
    }
	// 修改前
	protected function _before_update(&$data, $option)
	{
        unset($data['cpassword']);//不需要cpassword
	    if($data['password']=="")
        {
            //证明不修改密码,将密码从数据中取出
            unset($data['password']);
        }
        else{
	        //不为空证明修改密码
            $data['password']=md5($data['password']);
        }
        //管理员角色，多对多，先添加后删除
        //删除
        $id=$option['where']['id'];
        $admin_role_model=M('admin_role');
        $where['admin_id']=array('eq',$id);
        $admin_role_model->where($where)->delete();
        //添加
        $role_id=I('post.role_id');
        if($id==1 && $role_id)
        {
            $this->error='超级管理员不允许有额外的角色';
            return false;
        }
        $_role_id=array();
        $admin_role_model=M('admin_role');
        foreach ($role_id as $key=>$val)
        {
            $_role_id['admin_id']=$id;
            $_role_id['role_id']=$val;
            $admin_role_model->add($_role_id);
        }
	}
	// 删除前
	protected function _before_delete($option)
	{
	   $id=$option['where']['id'];
        $admin_role_model=M('admin_role');
        $where['admin_id']=array('eq',$id);
        $admin_role_model->where($where)->delete();
	}
	/************************************ 其他方法 ********************************************/
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
            session('id',$res1['id']);
            session('username',$res1['username']);
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