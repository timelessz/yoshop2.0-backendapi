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
use app\admin\model\store\Api as ApiModel;

/**
 * 商家后台API权限控制器
 * Class Api
 * @package app\store\controller
 */
class Api extends Controller
{
    /**
     * 权限列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $model = new ApiModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 添加权限
     * @return array
     */
    public function add()
    {
        // 新增记录
        $model = new ApiModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新权限
     * @param $apiId
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(int $apiId)
    {
        // 权限详情
        $model = ApiModel::detail($apiId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除权限
     * @param $apiId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete(int $apiId)
    {
        // 权限详情
        $model = ApiModel::detail($apiId);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
