<?php
// +----------------------------------------------------------------------
// | 萤火商城系统 [ 致力于通过产品和服务，帮助商家高效化开拓市场 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://www.yiovo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 这不是一个自由软件，不允许对程序代码以任何形式任何目的的再发行
// +----------------------------------------------------------------------
// | Author: 萤火科技 <admin@yiovo.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\store\controller;

use app\store\model\Page as PageModel;

/**
 * 店铺页面管理
 * Class Page
 * @package app\store\controller\wxapp
 */
class Page extends Controller
{
    /**
     * 页面列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        $model = new PageModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 页面设计默认数据
     * @return array
     */
    public function defaultData()
    {
        $model = new PageModel;
        return $this->renderSuccess([
            'page' => $model->getDefaultPage(),
            'items' => $model->getDefaultItems()
        ]);
    }

    /**
     * 页面详情
     * @param int $pageId
     * @return array
     */
    public function detail(int $pageId)
    {
        $detail = PageModel::detail($pageId);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 新增页面
     * @return array|mixed
     */
    public function add()
    {
        $model = new PageModel;
        if (!$model->add($this->postForm())) {
            return $this->renderError($model->getError() ?: '添加失败');
        }
        return $this->renderSuccess('添加成功');
    }

    /**
     * 编辑页面
     * @param int $pageId
     * @return array|mixed
     */
    public function edit(int $pageId)
    {
        $model = PageModel::detail($pageId);
        if (!$model->edit($this->postForm())) {
            return $this->renderError($model->getError() ?: '更新失败');
        }
        return $this->renderSuccess('更新成功');
    }

    /**
     * 删除页面
     * @param int $pageId
     * @return array
     */
    public function delete(int $pageId)
    {
        // 帮助详情
        $model = PageModel::detail($pageId);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 设置默认首页
     * @param int $pageId
     * @return array
     */
    public function setHome(int $pageId)
    {
        // 帮助详情
        $model = PageModel::detail($pageId);
        if (!$model->setHome()) {
            return $this->renderError($model->getError() ?: '设置失败');
        }
        return $this->renderSuccess('设置成功');
    }

}
