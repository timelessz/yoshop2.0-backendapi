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

namespace app\store\controller\store;

use app\store\controller\Controller;
use app\store\model\store\Role as RoleModel;

/**
 * 商家用户角色控制器
 * Class StoreUser
 * @package app\store\controller
 */
class Role extends Controller
{
    /**
     * 角色列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        $model = new RoleModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 添加角色
     * @return array
     */
    public function add()
    {
        // 新增记录
        $model = new RoleModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新角色
     * @param $roleId
     * @return array|bool|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(int $roleId)
    {
        // 角色详情
        $model = RoleModel::detail($roleId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除角色
     * @param $roleId
     * @return array|bool
     * @throws \Exception
     */
    public function delete(int $roleId)
    {
        // 角色详情
        $model = RoleModel::detail($roleId);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
