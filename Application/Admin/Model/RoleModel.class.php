<?php
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model 
{
	protected $insertFields = array('role_name');
	protected $updateFields = array('id','role_name');
	protected $_validate = array(
		array('role_name', 'require', '角色名不能为空！', 1, 'regex', 3),
		array('role_name', '', '角色名已存在！', 1, 'unique', 3),
		array('role_name', '1,30', '角色名的值最长不能超过 30 个字符！', 1, 'length', 3),
	);
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($role_name = I('get.role_name'))
			$where['role_name'] = array('like', "%$role_name%");
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
        //select a.id,a.role_name,GROUP_CONCAT(c.pri_name)  as pri_name from p39_role as a left join p39_pri_role as b on a.id=b.role_id left join p39_privilege as c on c.id=b.pri_id GROUP BY a.id
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')->field('a.id,a.role_name,GROUP_CONCAT(c.pri_name) as pri_name')->where($where)->join('left join p39_pri_role as b on a.id=b.role_id left join p39_privilege as c on c.id=b.pri_id')->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
	}
    // 添加后
    protected function _after_insert($data, $option)
    {
       $id=$data['id'];
       $pri_id=I('post.pri_id');
       $pri_role_model=M('pri_role');
       $_pri_id=array();
       foreach ($pri_id as $key=>$val)
       {
           $_pri_id['pri_id']=$val;
           $_pri_id['role_id']=$id;
         $pri_role_model->add($_pri_id);
       }
    }
	// 修改前
	protected function _before_update(&$data, $option)
	{
	    //先删除，再添加权限
	    $id=$option['where']['id'];
	    $where['role_id']=array('eq',$id);
	    $model=M('pri_role');
	    $model->where($where)->delete();
        $pri_id=I('post.pri_id');
        $_pri_id=array();
        foreach ($pri_id as $key=>$val)
        {
            $_pri_id['pri_id']=$val;
            $_pri_id['role_id']=$id;
            $model->add($_pri_id);
        }
	}
	// 删除前
	protected function _before_delete($option)
	{
        $id=$option['where']['id'];
        $where['role_id']=array('eq',$id);
        $model=M('pri_role');
        $model->where($where)->delete();
	}
	/************************************ 其他方法 ********************************************/
}