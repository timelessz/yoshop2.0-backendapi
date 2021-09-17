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

use app\common\model\store\RoleMenu as RoleMenuModel;

/**
 * 商家后台用户角色与菜单权限关系模型
 * Class RoleMenu
 * @package app\store\model\store
 */
class RoleMenu extends RoleMenuModel
{
    /**
     * 新增关系记录
     * @param int $roleId
     * @param array $menuIds
     * @return array|false
     */
    public static function increased(int $roleId, array $menuIds)
    {
        $data = [];
        foreach ($menuIds as $menuId) {
            $data[] = [
                'role_id' => $roleId,
                'menu_id' => $menuId,
                'store_id' => self::$storeId,
            ];
        }
        return (new static)->addAll($data);
    }

    /**
     * 更新关系记录
     * @param int $roleId
     * @param array $newMenus
     * @return array|false
     */
    public static function updates(int $roleId, array $newMenus)
    {
        // 已分配的权限集
        $assignMenuIds = self::getMenuIds([$roleId]);
        /**
         * 找出删除的权限
         * 假如已有的权限集合是A，界面传递过得权限集合是B
         * 权限集合A当中的某个权限不在权限集合B当中，就应该删除
         * 使用 array_diff() 计算补集
         */
        if ($deleteMenuIds = array_diff($assignMenuIds, $newMenus)) {
            self::deleteAll([
                ['role_id', '=', $roleId],
                ['menu_id', 'in', $deleteMenuIds]
            ]);
        }
        /**
         * 找出添加的权限
         * 假如已有的权限集合是A，界面传递过得权限集合是B
         * 权限集合B当中的某个权限不在权限集合A当中，就应该添加
         * 使用 array_diff() 计算补集
         */
        $newMenuIds = array_diff($newMenus, $assignMenuIds);
        $data = [];
        foreach ($newMenuIds as $menuId) {
            $data[] = [
                'role_id' => $roleId,
                'menu_id' => $menuId,
                'store_id' => self::$storeId,
            ];
        }
        return (new static)->addAll($data);
    }

    /**
     * 获取指定角色的所有菜单id
     * @param array $roleIds 角色ID集
     * @return array
     */
    public static function getMenuIds(array $roleIds)
    {
        return (new self)->where('role_id', 'in', $roleIds)->column('menu_id');
    }

    /**
     * 删除记录
     * @param array $where
     * @return int
     */
    public static function deleteAll(array $where)
    {
        return (new static)->where($where)->delete();
    }

}
