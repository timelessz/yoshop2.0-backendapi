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

namespace app\admin\controller\store;

use app\admin\controller\Controller;
use app\admin\model\store\Menu as MenuModel;

/**
 * 商家后台菜单控制器
 * Class Menu
 * @package app\store\controller
 */
class Menu extends Controller
{
    /**
     * 菜单列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $model = new MenuModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 菜单详情
     * @param int $menuId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function info(int $menuId)
    {
        // 菜单详情
        $model = MenuModel::detail($menuId);
        return $this->renderSuccess(['info' => $model]);
    }

    /**
     * 添加菜单
     * @return array
     */
    public function add()
    {
        // 新增记录
        $model = new MenuModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新菜单
     * @param $menuId
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(int $menuId)
    {
        // 菜单详情
        $model = MenuModel::detail($menuId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 设置菜单绑定的Api
     * @param int $menuId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setApis(int $menuId)
    {
        // 菜单详情
        $model = MenuModel::detail($menuId);
        // 更新记录
        if ($model->setApis($this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 删除菜单
     * @param $menuId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete(int $menuId)
    {
        // 菜单详情
        $model = MenuModel::detail($menuId);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
