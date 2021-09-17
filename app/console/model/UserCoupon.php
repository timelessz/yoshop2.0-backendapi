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

namespace app\console\model;

use app\common\model\UserCoupon as UserCouponModel;

/**
 * 用户优惠券模型
 * Class UserCoupon
 * @package app\console\model
 */
class UserCoupon extends UserCouponModel
{
    // 是否允许全局查询store_id
    protected $isGlobalScopeStoreId = false;

    /**
     * 获取已过期的优惠券ID集
     * @param int $storeId
     * @return array
     */
    public function getExpiredCouponIds(int $storeId)
    {
        $time = time();
        return $this->where('is_expire', '=', 0)
            ->where('is_use', '=', 0)
            ->where("IF ( `expire_type` = 20,
                    (`end_time` + 86400) < {$time},
                    ( `create_time` + (`expire_day` * 86400)) < {$time} )")
            ->where('store_id', '=', $storeId)
            ->column('user_coupon_id');
    }

    /**
     * 设置优惠券过期状态
     * @param array $couponIds
     * @return false|int
     */
    public function setIsExpire(array $couponIds)
    {
        if (empty($couponIds)) {
            return false;
        }
        return $this->updateBase(['is_expire' => 1], [['user_coupon_id', 'in', $couponIds]]);
    }

}
