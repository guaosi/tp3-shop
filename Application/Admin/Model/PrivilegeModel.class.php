<?php
namespace Admin\Model;
use Think\Model;
class PrivilegeModel extends Model 
{
	protected $insertFields = array('pri_name','module_naame','controller_naame','action_naame','pid');
	protected $updateFields = array('id','pri_name','module_naame','controller_naame','action_naame','pid');
	protected $_validate = array(
		array('pri_name', 'require', '权限名称不能为空！', 1, 'regex', 3),
		array('pri_name', '1,30', '权限名称的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('module_naame', '1,30', '模块名称的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('controller_naame', '1,30', '控制器名称的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('action_naame', '1,30', '方法名称的值最长不能超过 30 个字符！', 2, 'length', 3),
	);
	/************************************* 递归相关方法 *************************************/
	public function getTree()
	{
		$data = $this->select();
		return $this->_reSort($data);
	}
	private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['pid'] == $parent_id)
			{
				$v['level'] = $level;
				$ret[] = $v;
				$this->_reSort($data, $v['id'], $level+1, FALSE);
			}
		}
		return $ret;
	}
	public function getChildren($id)
	{
		$data = $this->select();
		return $this->_children($data, $id);
	}
	private function _children($data, $parent_id=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['pid'] == $parent_id)
			{
				$ret[] = $v['id'];
				$this->_children($data, $v['id'], FALSE);
			}
		}
		return $ret;
	}
    public function _before_delete($option)
    {
        // 先找出所有的子分类
        $children = $this->getChildren($option['where']['id']);
        // 如果有子分类都删除掉
        $this->delete(implode(',',$children));
    }
	/************************************ 其他方法 ********************************************/
    public function checkauth(){
        //检测是否有对应权限
        $ControllerName=ucfirst(CONTROLLER_NAME);
        if(session('id')==1)
        {
            //超级管理员拥有所有权限
            return true;
        }
        if($ControllerName=='Index')
        {
            //默认都有Index权限
            return true;
        }
        //都没有，则是普通用户访问特定模块
        $ModuleName=strtolower(MODULE_NAME);
        $ActionName=strtolower(ACTION_NAME);
//        select * from p39_admin_role as a left join p39_pri_role as b on a.role_id=b.role_id left join p39_privilege as c on c.id=b.pri_id where a.admin_id=3
        //拼写查询语句
        $where['admin_id']=session('id');
        $where['module_name']=ucfirst($ModuleName);
        $where['controller_name']=ucfirst($ControllerName);
        $where['action_name']=$ActionName;
        $res=D('admin_role')->alias('a')->join('left join p39_pri_role as b on a.role_id=b.role_id left join p39_privilege as c on c.id=b.pri_id')->where($where)->select();
        if($res)
        {
            //有数据则证明有该权限
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * @获得前两级权限，做成四维数组
     */
    public function getBtn()
    {
        $model=M('Privilege');
        $id=session('id');
        //如果是超级管理员，则显示所有
      if($id==1)
      {
          $btnres=$model->select();
      }
//      否则显示自己拥有的
      else {
//  SELECT DISTINCT c.id,c.module_name,c.controller_name,c.action_name,c.pid FROM p39_admin_role AS a LEFT JOIN p39_pri_role AS b ON a.role_id = b.role_id LEFT JOIN p39_privilege AS c ON c.id = b.pri_id WHERE a.admin_id = 3
          //获取该id下的所有权限
          $admin_role_model = M('admin_role');
          $where['a.admin_id'] = array('eq', $id);
          $btnres = $admin_role_model->alias('a')->field('DISTINCT c.id,c.pri_name,c.module_name,c.controller_name,c.action_name,c.pid')->join('LEFT JOIN p39_pri_role AS b ON a.role_id = b.role_id LEFT JOIN p39_privilege AS c ON c.id = b.pri_id')->where($where)->select();
      }

      //将所有的数据进行处理，只要顶级跟二级权限
        $btn = array();
          foreach ($btnres as $key=>$val)
          {
              //第一次遍历，如果pid等于0，证明是顶级权限
              if($val['pid']==0)
              {
                foreach ($btnres as $k=>$v)
                {
                    //相等，则证明是这个顶级权限的二级权限
                    if($val['id']==$v['pid'])
                    {
                        //先将这个数组放入该顶级权限进行临时保存
                        $val['children'][]=$v;
                    }
                }
                //二级权限全部找完后，再放入自己创建的数组，最后返回
                  $btn[]=$val;
              }
          }
          return $btn;
      }
}