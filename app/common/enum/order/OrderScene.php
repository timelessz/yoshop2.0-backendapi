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

namespace app\common\enum\order;

use app\common\enum\EnumBasics;

/**
 * 枚举类：订单场景
 * Class OrderScene
 * @package app\common\enum\order
 */
class OrderScene extends EnumBasics
{
    // 订单创建时
    const CREATE = 10;

    // 订单付款时
    const PAYMENT = 20;

    // 订单发货时
    const DELIVERY = 30;

    // 订单完成时
    const COMPLETE = 40;

    // 订单取消时
    const CANCEL = 50;

    /**
     * 获取枚举数据
     * @return array
     */
    public static function data()
    {
        return [
            self::CREATE => [
                'name' => '订单创建时',
                'value' => self::CREATE,
            ],
            self::PAYMENT => [
                'name' => '订单付款时',
                'value' => self::PAYMENT,
            ],
            self::DELIVERY => [
                'name' => '订单发货时',
                'value' => self::DELIVERY,
            ],
            self::COMPLETE => [
                'name' => '订单完成时',
                'value' => self::COMPLETE,
            ],
            self::CANCEL => [
                'name' => '订单取消时',
                'value' => self::COMPLETE,
            ]
        ];
    }

}