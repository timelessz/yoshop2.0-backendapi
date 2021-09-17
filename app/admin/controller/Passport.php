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

namespace app\admin\controller;

use app\admin\model\admin\User as UserModel;

/**
 * 超管后台认证
 * Class Passport
 * @package app\store\controller
 */
class Passport extends Controller
{
    /**
     * 强制验证当前访问的控制器方法method
     * @var array
     */
    protected $methodRules = [
        'login' => 'POST',
    ];

    /**
     * 超管后台登录
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     */
    public function login()
    {
        // 超管后台用户登录
        $model = new UserModel;
        if (($userInfo = $model->login($this->postData())) === false) {
            return $this->renderError($model->getError() ?: '登录失败');
        }
        return $this->renderSuccess([
            'userId' => $userInfo['admin_user_id'],
            'token' => $model->getToken()
        ], '登录成功');
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        return $this->renderSuccess('操作成功');
    }

}
