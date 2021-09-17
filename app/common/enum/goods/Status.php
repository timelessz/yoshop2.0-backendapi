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
 * 枚举类：商品状态
 * Class Status
 * @package app\common\enum\goods
 */
class Status extends EnumBasics
{
    // 上架
    const ON_SALE = 10;

    // 下架
    const OFF_SALE = 20;

    /**
     * 获取枚举类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::ON_SALE => [
                'name' => '上架',
                'value' => self::ON_SALE,
            ],
            self::OFF_SALE => [
                'name' => '下架',
                'value' => self::OFF_SALE,
            ],
        ];
    }
}
