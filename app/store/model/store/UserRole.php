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

use app\common\model\store\UserRole as UserRoleModel;

/**
 * 商家用户角色模型
 * Class UserRole
 * @package app\store\model\store
 */
class UserRole extends UserRoleModel
{
    /**
     * 新增关系记录
     * @param int $storeUserId
     * @param array $roleIds
     * @return array|false
     */
    public static function increased(int $storeUserId, array $roleIds)
    {
        $data = [];
        foreach ($roleIds as $roleId) {
            $data[] = [
                'store_user_id' => $storeUserId,
                'role_id' => $roleId,
                'store_id' => self::$storeId,
            ];
        }
        return (new static)->addAll($data);
    }

    /**
     * 更新关系记录
     * @param int $storeUserId
     * @param array $newRoles 新的角色集
     * @return array|false
     * @throws \Exception
     */
    public static function updates(int $storeUserId, array $newRoles)
    {
        // 已分配的角色集
        $assignRoleIds = self::getRoleIdsByUserId($storeUserId);
        // 找出删除的角色
        $deleteRoleIds = array_diff($assignRoleIds, $newRoles);
        if (!empty($deleteRoleIds)) {
            self::deleteAll([
                ['store_user_id', '=', $storeUserId],
                ['role_id', 'in', $deleteRoleIds]
            ]);
        }
        // 找出添加的角色
        $newRoleIds = array_diff($newRoles, $assignRoleIds);
        $data = [];
        foreach ($newRoleIds as $roleId) {
            $data[] = [
                'store_user_id' => $storeUserId,
                'role_id' => $roleId,
                'store_id' => self::$storeId,
            ];
        }
        return (new static)->addAll($data);
    }

    /**
     * 获取指定管理员的所有角色id
     * @param int $storeUserId
     * @return array
     */
    public static function getRoleIdsByUserId(int $storeUserId)
    {
        return (new static)->where('store_user_id', '=', $storeUserId)->column('role_id');
    }

    /**
     * 根据角色ID判断是否存在用户
     * @param int $roleId
     * @return bool
     */
    public static function isExistsUserByRoleId(int $roleId)
    {
        return !!(new static)->where('role_id', '=', $roleId)->count();
    }
}
