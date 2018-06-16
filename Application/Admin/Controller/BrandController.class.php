<?php

namespace Admin\Controller;
class BrandController extends CommonController
{
    public function add()
    {
        if (IS_POST) {
            $model = D('Brand');
            if ($model->create(I('post.'), 1)) {
                if ($id = $model->add()) {
                    $this->success('添加成功！', U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        } else {
            // 设置页面中的信息
            $this->assign(array(
                'page_name' => '添加品牌',
                'page_btn_name' => '品牌列表',
                'page_btn_url' => U('lst'),
            ));
            $this->display();
        }

    }

    public function edit()
    {
        $id = I('get.id');
        if (IS_POST) {
            $model = D('Brand');
            if ($model->create(I('post.'), 2)) {
                if ($model->save() !== FALSE) {
                    $this->success('修改成功！', U('lst'));
                    exit;
                }
            }
            $this->error($model->getError());
        } else {
            $model = M('Brand');
            $data = $model->find($id);
            $this->assign('data', $data);

            // 设置页面中的信息
            $this->assign(array(
                'page_name' => '修改品牌',
                'page_btn_name' => '品牌列表',
                'page_btn_url' => U('lst'),
            ));
            $this->display();
        }

    }
    public function _empty(){
        $this->display('empty/empty');
    }


    public function lst()
    {
        $model = D('Brand');
        $data = $model->search(2);
        $this->assign(array(
            'Count' => $data['count'],
            'data' => $data['data'],
            'page' => $data['page'],
        ));

        // 设置页面中的信息
        $this->assign(array(
            'page_name' => '品牌列表',
            'page_btn_name' => '添加品牌',
            'page_btn_url' => U('add'),
        ));
        $this->display();
    }
}