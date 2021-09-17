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
 * 枚举类：优惠券适用范围
 * Class ApplyRange
 * @package app\common\enum\coupon
 */
class ApplyRange extends EnumBasics
{
    // 全部商品
    const ALL = 10;

    // 指定商品
    const SOME = 20;

    // 排除商品
    const EXCLUDE = 30;

    /**
     * 获取枚举类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::ALL => [
                'name' => '全部商品',
                'value' => self::ALL
            ],
            self::SOME => [
                'name' => '指定商品',
                'value' => self::SOME
            ],
            self::EXCLUDE => [
                'name' => '排除商品',
                'value' => self::EXCLUDE
            ]
        ];
    }

}
