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

namespace app\store\model\store;

use app\common\library\helper;
use app\common\model\store\User as StoreUserModel;
use app\admin\service\store\User as StoreUserService;

/**
 * 商家用户模型
 * Class StoreUser
 * @package app\store\model
 */
class User extends StoreUserModel
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
     * 商家用户登录
     * @param array $data
     * @return array|bool|null|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login(array $data)
    {
        // 验证用户名密码是否正确
        if (!$userInfo = $this->getUserInfoByLogin($data)) {
            return false;
        }
        // 验证商城状态是否正常
        if (empty($userInfo['store']) || $userInfo['store']['is_delete']) {
            $this->error = '登录失败, 未找到当前商城信息';
            return false;
        }
        if ($userInfo['store']['is_recycle']) {
            $this->error = '登录失败, 当前商城已删除';
            return false;
        }
        // 记录登录状态, 并记录token
        $this->token = StoreUserService::login($userInfo->toArray());
        return $userInfo;
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
     * 获取登录用户信息
     * @param array $data
     * @return array|bool|null|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getUserInfoByLogin(array $data)
    {
        // 用户信息
        $useInfo = static::withoutGlobalScope()
            ->with(['store'])
            ->where(['user_name' => trim($data['username'])])
            ->find();
        if (empty($useInfo) || $useInfo['is_delete']) {
            $this->error = '登录失败, 该用户不存在或已删除';
            return false;
        }
        // 验证密码是否正确
        if (!password_verify($data['password'], $useInfo['password'])) {
            $this->error = '登录失败, 用户名或密码错误';
            return false;
        }
        return $useInfo;
    }

    /**
     * 获取用户列表
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList($param = [])
    {
        // 查询模型
        $query = $this->getNewQuery();
        // 查询参数
        $params = $this->setQueryDefaultValue($param, ['search' => '']);
        // 关键词搜索
        !empty($params['search']) && $query->where('user_name|real_name', 'like', "%{$params['search']}%");
        // 查询列表记录
        $list = $query->with(['role'])
            ->where('is_delete', '=', '0')
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate(15);
        // 整理所有角色id
        foreach ($list as &$item) {
            $item['roleIds'] = helper::getArrayColumn($item['role'], 'role_id');
        }
        return $list;
    }

    /**
     * 新增记录
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        if (self::checkExist($data['user_name'])) {
            $this->error = '用户名已存在';
            return false;
        }
        if ($data['password'] !== $data['password_confirm']) {
            $this->error = '确认密码不正确';
            return false;
        }
        if (empty($data['roles'])) {
            $this->error = '请选择所属角色';
            return false;
        }
        // 整理数据
        $data['password'] = encryption_hash($data['password']);
        $data['store_id'] = self::$storeId;
        $data['is_super'] = 0;

        // 事务处理
        $this->transaction(function () use ($data) {
            // 新增管理员记录
            $this->save($data);
            // 新增角色关系记录
            UserRole::increased((int)$this['store_user_id'], $data['roles']);
        });
        return true;
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool
     */
    public function edit(array $data)
    {
        if ($this['user_name'] !== $data['user_name']
            && self::checkExist($data['user_name'])) {
            $this->error = '用户名已存在';
            return false;
        }
        if (!empty($data['password']) && ($data['password'] !== $data['password_confirm'])) {
            $this->error = '确认密码不正确';
            return false;
        }
        if (empty($data['roles']) && !$this['is_super']) {
            $this->error = '请选择所属角色';
            return false;
        }
        if (!empty($data['password'])) {
            $data['password'] = encryption_hash($data['password']);
        } else {
            unset($data['password']);
        }
        $this->transaction(function () use ($data) {
            // 更新管理员记录
            $this->save($data);
            // 更新角色关系记录
            !$this['is_super'] && UserRole::updates((int)$this['store_user_id'], $data['roles']);
        });
        return true;
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        if ($this['is_super']) {
            $this->error = '超级管理员不允许删除';
            return false;
        }
        return $this->transaction(function () {
            // 删除对应的角色关系
            UserRole::deleteAll([['store_user_id', '=', (int)$this['store_user_id']]]);
            return $this->save(['is_delete' => 1]);
        });
    }

    /**
     * 更新当前管理员信息
     * @param array $data
     * @return bool
     */
    public function renew(array $data)
    {
        if (!empty($data['password']) && ($data['password'] !== $data['password_confirm'])) {
            $this->error = '确认密码不正确';
            return false;
        }
        if ($this['user_name'] !== $data['user_name']
            && self::checkExist($data['user_name'])) {
            $this->error = '用户名已存在';
            return false;
        }
        !empty($data['password']) && $data['password'] = encryption_hash($data['password']);
        // 更新管理员信息
        if ($this->save($data) === false) {
            return false;
        }
        // 更新登录状态
        StoreUserService::update($this->toArray());
        return true;
    }

}
