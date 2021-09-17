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

namespace app\console\service;

use app\common\library\helper;
use app\console\library\Tools;
use app\common\service\BaseService;
use app\console\model\UserCoupon as UserCouponModel;

/**
 * 服务类：用户优惠券
 * Class Order
 * @package app\console\service
 */
class UserCoupon extends BaseService
{
    /**
     * 设置优惠券过期状态
     * @param int $storeId
     * @return mixed
     */
    public function setExpired(int $storeId)
    {
        $model = new UserCouponModel;
        // 获取已过期的优惠券ID集
        $couponIds = $model->getExpiredCouponIds($storeId);
        // 记录日志
        Tools::taskLogs('UserCoupon', 'setExpired', [
            'storeId' => $storeId,
            'couponIds' => helper::jsonEncode($couponIds)
        ]);
        // 更新已过期状态
        return $model->setIsExpire($couponIds);
    }
}