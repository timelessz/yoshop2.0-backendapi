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

namespace app\common\enum;

/**
 * 枚举类：支付类型
 * Class PayType
 * @package app\common\enum
 */
class PayType extends EnumBasics
{
    // 商城订单
    const ORDER = 10;

    // 余额充值
    const RECHARGE = 100;

    /**
     * 获取订单类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::ORDER => [
                'name' => '商城订单',
                'value' => self::ORDER,
            ],
            self::RECHARGE => [
                'name' => '余额充值',
                'value' => self::RECHARGE,
            ],
        ];
    }

    /**
     * 获取订单类型名称
     * @return array
     */
    public static function getTypeName()
    {
        static $names = [];
        if (empty($names)) {
            foreach (self::data() as $item)
                $names[$item['value']] = $item['name'];
        }
        return $names;
    }

}