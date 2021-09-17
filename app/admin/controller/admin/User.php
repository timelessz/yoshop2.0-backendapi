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

namespace app\admin\controller\admin;

use app\admin\controller\Controller;
use app\admin\model\admin\User as AdminUserModel;

/**
 * 超管后台管理员控制器
 * Class User
 * @package app\store\controller
 */
class User extends Controller
{
    /**
     * 强制验证当前访问的控制器方法method
     * 例: [ 'login' => 'POST' ]
     * @var array
     */
    protected $methodRules = [
        'detail' => 'GET',
        'renew' => 'POST',
    ];

    /**
     * 获取当前用户信息
     * @return array
     */
    public function detail()
    {
        $userInfo = AdminUserModel::detail($this->admin['user']['admin_user_id']);
        return $this->renderSuccess(['userInfo' => $userInfo]);
    }

    /**
     * 更新当前管理员信息
     * @return array|string
     * @throws \Exception
     */
    public function renew()
    {
        // 获取当前用户信息
        $model = AdminUserModel::detail($this->admin['user']['admin_user_id']);
        // 更新用户信息
        if ($model->renew($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }
}
