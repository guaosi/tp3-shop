<?php
namespace Admin\Model;
use Think\Model;
class BrandModel extends Model 
{
	protected $insertFields = array('brand_name','site_url');
	protected $updateFields = array('id','brand_name','site_url');
	protected $_validate = array(
		array('brand_name', 'require', '品牌名称不能为空！', 1, 'regex', 3),
		array('brand_name', '1,150', '品牌名称的值最长不能超过 150 个字符！', 1, 'length', 3),
		array('site_url', '1,150', '官方网址的值最长不能超过 150 个字符！', 2, 'length', 3),
	);
	public function search($pageSize = 5)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($brand_name = I('get.brand_name'))
			$where['brand_name'] = array('like', "%$brand_name%");
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('first','首页');
        $page->setConfig('last','末页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		$data['count']=$count;
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
    {
        if ($_FILES['logo']['size'] > 0) {
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
            }
        }
    }
	// 修改前
	protected function _before_update(&$data, $option)
    {
        $id = $option['where']['id'];
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
                //删除旧的图片
                $res = $this->field('logo')->find($id);
                delAllPhoto($res);
            }
        }
    }
	// 删除前
	protected function _before_delete($option)
	{
		$res=$this->field('logo')->find($option['where']['id']);
		delAllPhoto($res);
	}
	/************************************ 其他方法 ********************************************/
}