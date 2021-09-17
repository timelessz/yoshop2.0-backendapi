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


namespace app\common\enum\store\page\category;

use app\common\enum\EnumBasics;

/**
 * 枚举类：分类页模板样式
 * Class Style
 * @package app\common\enum\store\page\category
 */
class Style extends EnumBasics
{
    // 一级分类[大图]
    const ONE_LEVEL_BIG = 10;

    // 一级分类[小图]
    const ONE_LEVEL_SMALL = 11;

    // 二级分类
    const TWO_LEVEL = 20;

    /**
     * 获取类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::ONE_LEVEL_BIG => [
                'name' => '一级分类[大图]',
                'value' => self::ONE_LEVEL_BIG,
            ],
            self::ONE_LEVEL_SMALL => [
                'name' => '一级分类[小图]',
                'value' => self::ONE_LEVEL_SMALL
            ],
            self::TWO_LEVEL => [
                'name' => '二级分类',
                'value' => self::TWO_LEVEL
            ]
        ];
    }
}