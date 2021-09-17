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

namespace app\admin\model\store;

use app\common\exception\BaseException;
use app\common\model\store\User as StoreUserModel;

/**
 * 商家用户模型
 * Class StoreUser
 * @package app\admin\model
 */
class User extends StoreUserModel
{
    /**
     * 商家用户登录
     * @param int $storeId
     * @throws BaseException
     * @throws \think\Exception
     */
    public function login(int $storeId)
    {
        // 获取该商户管理员用户信息
        $userInfo = $this->getSuperStoreUser($storeId);
        if (empty($userInfo)) {
            throwError('超级管理员用户信息不存在');
        }
    }

    /**
     * 获取该商户管理员用户信息
     * @param int $storeId
     * @return static|null
     */
    private function getSuperStoreUser(int $storeId)
    {
        return static::detail(['store_id' => $storeId, 'is_super' => 1], ['wxapp']);
    }

    /**
     * 删除小程序下的商家用户
     * @param $storeId
     * @return false|int
     */
    public static function setDelete(int $storeId)
    {
        static::update(['is_delete' => '1'], ['store_id' => $storeId]);
        return true;
    }

}
