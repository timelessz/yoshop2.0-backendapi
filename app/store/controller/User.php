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

use app\store\model\User as UserModel;

/**
 * 用户管理
 * Class User
 * @package app\store\controller
 */
class User extends Controller
{
    /**
     * 用户列表
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        // 用户列表
        $model = new UserModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 删除用户
     * @param int $userId
     * @return array
     */
    public function delete(int $userId)
    {
        // 用户详情
        $model = UserModel::detail($userId);
        if ($model->setDelete()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }

    /**
     * 用户充值
     * @param int $userId
     * @param string $target
     * @return array
     */
    public function recharge(int $userId, string $target)
    {
        // 用户详情
        $model = UserModel::detail($userId);
        if ($model->recharge($target, $this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 修改会员等级
     * @param int $userId
     * @return array
     */
    public function grade(int $userId)
    {
        // 用户详情
        $model = UserModel::detail($userId);
        if ($model->updateGrade($this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

}
