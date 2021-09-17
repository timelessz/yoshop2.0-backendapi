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
declare (strict_types=1);

namespace app\admin\model\admin;

use app\common\model\admin\User as UserModel;
use app\admin\service\admin\User as AdminUserService;

/**
 * 超管后台用户模型
 * Class User
 * @package app\admin\model\admin
 */
class User extends UserModel
{
    /**
     * 隐藏的字段
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    // 用户登录token
    private $token;

    /**
     * 超管后台用户登录
     * @param array $data
     * @return array|bool|null|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login(array $data)
    {
        // 验证用户名密码是否正确
        if (!$user = $this->getUserInfoByLogin($data)) {
            $this->error = '登录失败, 用户名或密码错误';
            return false;
        }
        // 记录登录状态, 并记录token
        $this->token = AdminUserService::login($user->toArray());
        return $user;
    }

    /**
     * 返回生成的token
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * 获取登录的用户信息
     * @param $data
     * @return array|false|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getUserInfoByLogin(array $data)
    {
        // 用户信息
        $useInfo = static::withoutGlobalScope()
            ->where(['user_name' => $data['username']])
            ->find();
        if (empty($useInfo)) return false;
        // 验证密码是否正确
        if (!password_verify($data['password'], $useInfo['password'])) {
            return false;
        }
        return $useInfo;
    }

    /**
     * 更新当前管理员信息
     * @param $data
     * @return bool
     */
    public function renew(array $data)
    {
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        // 更新管理员信息
        if ($this->save([
                'user_name' => $data['user_name'],
                'password' => encryption_hash($data['password']),
            ]) === false) {
            return false;
        }
        // 更新登录信息
        AdminUserService::update($this->toArray());
        return true;
    }

}
