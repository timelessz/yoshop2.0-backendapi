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

namespace app\store\service\store;

use app\common\service\BaseService;
use app\store\model\store\Menu as MenuModel;
use app\store\model\store\UserRole as UserRoleModel;
use app\store\model\store\RoleMenu as RoleMenuModel;
use app\store\service\store\User as UserService;

/**
 * 商家后台角色服务类
 * Class Role
 * @package app\store\service\store
 */
class Role extends BaseService
{
    /**
     * 获取当前登录用户菜单权限
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getLoginPermissions()
    {
        // 获取当前登录用户的ID
        $userInfo = UserService::getLoginInfo();
        // 根据当前用户ID获取有权限的菜单列表
        $permittedMenuList = static::getPermittedMenuList((int)$userInfo['user']['store_user_id']);
        // 生成权限列表
        $permissions = static::buildPermissions($permittedMenuList);
        return [
            // 是否为超级管理员, 拥有所有权限
            'isSuper' => $userInfo['user']['is_super'],
            // 权限列表
            'permissions' => $permissions
        ];
    }

    /**
     * 生成权限列表
     * @param $menuList
     * @return array
     */
    private static function buildPermissions($menuList)
    {
        $data = [];
        foreach ($menuList as $menu) {
            $data[] = [
                // 菜单唯一标示
                'permissionId' => $menu['path'],
                // 菜单名称
                'name' => $menu['name'],
                // 页面操作项 例如: 新增 编辑 删除
                'actionEntitySet' => static::getActionEntitySet($menu)
            ];
            !empty($menu['children']) && $data = array_merge($data, static::buildPermissions($menu['children']));
        }
        return $data;
    }

    /**
     * 整理页面操作项
     * @param $menu
     * @return array
     */
    private static function getActionEntitySet($menu)
    {
        if (!isset($menu['actions']) || empty($menu['actions'])) {
            return [];
        }
        $actionEntitySet = [];
        foreach ($menu['actions'] as $action) {
            $actionEntitySet[] = [
                'describe' => $action['name'],
                'action' => $action['action_mark'],
            ];
        }
        return $actionEntitySet;
    }

    private static function filterChildrenAction($menuList)
    {
        $list = [];
        foreach ($menuList as $item) {
            if ($item['module'] != 10) continue;
            if (!empty($item['children'])) {
                // 整理actions
                $item['actions'] = array_filter($item['children'], function ($val) {
                    return $val['module'] == 20;
                });
                // 整理children
                $item['children'] = static::filterChildrenAction($item['children']);
            }
            $list[] = $item;
        }
        return $list;
    }

    /**
     * 根据指定用户ID获取有权限的菜单列表
     * @param $storeUserId
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private static function getPermittedMenuList(int $storeUserId)
    {
        // 获取指定用户的所有角色ID
        $roleIds = UserRoleModel::getRoleIdsByUserId($storeUserId);
        // 根据角色ID集获取菜单列表集
        $menuIds = RoleMenuModel::getMenuIds($roleIds);
        // 获取指定角色ID的菜单列表
        $menuList = MenuModel::getListByIds($menuIds);
        //  整理菜单列表的actions
        return static::filterChildrenAction($menuList);
    }

}
