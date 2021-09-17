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

namespace app\store\service;

use app\store\model\store\Api as ApiModel;
use app\store\model\store\MenuApi as MenuApiModel;
use app\store\model\store\UserRole as UserRoleModel;
use app\store\model\store\RoleMenu as RoleMenuModel;
use app\store\service\store\User as StoreUserService;

/**
 * 商家后台权限业务
 * Class Auth
 * @package app\admin\service
 */
class Auth
{
    // 实例句柄
    static public $instance;

    // 商家登录信息
    private $store;

    // 商家用户信息
    private $user;

    // 商家用户权限url
    private $apiUrls = [];

    /**
     * 公有化获取实例方法
     * @return Auth
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof Auth)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * 私有化构造方法
     * Auth constructor.
     */
    private function __construct()
    {
        // 商家登录信息
        $this->store = StoreUserService::getLoginInfo();
        // 当前用户信息
        !empty($this->store) && $this->user = $this->store['user'];
    }

    /**
     * 私有化克隆方法
     */
    private function __clone()
    {
    }

    /**
     * 验证指定url是否有访问权限
     * @param $url
     * @param bool $strict 严格模式($url必须全部有权)
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function checkPrivilege($url, bool $strict = true)
    {
        if (!is_array($url)) {
            return $this->checkAccess($url);
        }
        foreach ($url as $val) {
            $status = $this->checkAccess($val);
            if ($strict && !$status) return false;
            if (!$strict && $status) return true;
        }
        return true;
    }

    /**
     * 验证url的权限
     * @param $url
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function checkAccess($url)
    {
        // 域名白名单
        // config/allowapi.php
        $allowApis = config('allowapi');
        // 验证当前请求是否在白名单
        if (in_array($url, $allowApis)) {
            return true;
        }
        // 用户不存在 禁止访问
        if (empty($this->user)) {
            return false;
        }
        // 超级管理员无需验证
        if ($this->user['is_super']) {
            return true;
        }
        // 通配符支持
        foreach ($allowApis as $action) {
            if (strpos($action, '*') !== false
                && preg_match('/^' . str_replace('/', '\/', $action) . '/', $url)
            ) {
                return true;
            }
        }
        // 获取当前用户的权限url列表
        if (!in_array($url, $this->getAccessUrls())) {
            return false;
        }
        return true;
    }

    /**
     * 获取当前用户的权限url列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getAccessUrls()
    {
        if (empty($this->apiUrls)) {
            // 获取当前用户的角色ID集
            $roleIds = UserRoleModel::getRoleIdsByUserId($this->user['store_user_id']);
            // 获取已分配的菜单ID集
            $menuIds = RoleMenuModel::getMenuIds($roleIds);
            // 获取已分配的API的ID集
            $apiIds = MenuApiModel::getApiIds($menuIds);
            // 获取当前角色所有权限链接
            $this->apiUrls = ApiModel::getApiUrls($apiIds);
        }
        return $this->apiUrls;
    }

}
