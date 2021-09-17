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

namespace app\common\enum\coupon;

use app\common\enum\EnumBasics;

/**
 * 枚举类：优惠券类型
 * Class CouponType
 * @package app\common\enum\coupon
 */
class CouponType extends EnumBasics
{
    // 满减券
    const FULL_DISCOUNT = 10;

    // 折扣券
    const DISCOUNT = 20;

    /**
     * 获取枚举类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::FULL_DISCOUNT => [
                'name' => '满减券',
                'value' => self::FULL_DISCOUNT
            ],
            self::DISCOUNT => [
                'name' => '折扣券',
                'value' => self::DISCOUNT
            ]
        ];
    }

}
