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

namespace app\common\enum\store\address;

use app\common\enum\EnumBasics;

/**
 * 枚举类：地址类型
 * Class Type
 * @package app\common\enum\store\address
 */
class Type extends EnumBasics
{
    // 发货地址
    const DELIVERY = 10;

    // 退货地址
    const RETURN = 20;

    /**
     * 获取类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::DELIVERY => [
                'name' => '发货地址',
                'value' => self::DELIVERY,
            ],
            self::RETURN => [
                'name' => '退货地址',
                'value' => self::RETURN
            ]
        ];
    }

}
