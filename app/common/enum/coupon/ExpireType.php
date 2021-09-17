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
 * 枚举类：优惠券到期类型
 * Class ExpireType
 * @package app\common\enum\coupon
 */
class ExpireType extends EnumBasics
{
    // 领取后
    const RECEIVE = 10;

    // 固定时间
    const FIXED_TIME = 20;

    /**
     * 获取枚举类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::RECEIVE => [
                'name' => '领取后',
                'value' => self::RECEIVE
            ],
            self::FIXED_TIME => [
                'name' => '固定时间',
                'value' => self::FIXED_TIME
            ]
        ];
    }

}
