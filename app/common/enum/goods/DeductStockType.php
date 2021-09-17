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

namespace app\common\enum\goods;

use app\common\enum\EnumBasics;

/**
 * 枚举类：商品库存计算方式
 * Class DeductStockType
 * @package app\common\enum\goods
 */
class DeductStockType extends EnumBasics
{
    // 下单减库存
    const CREATE = 10;

    // 付款减库存
    const PAYMENT = 20;

    /**
     * 获取枚举类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::CREATE => [
                'name' => '下单减库存',
                'value' => self::CREATE,
            ],
            self::PAYMENT => [
                'name' => '付款减库存',
                'value' => self::PAYMENT,
            ],
        ];
    }

}
