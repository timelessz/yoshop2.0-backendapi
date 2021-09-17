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

namespace app\common\enum\order\refund;

use app\common\enum\EnumBasics;

/**
 * 枚举类：售后单状态
 * Class RefundStatus
 * @package app\common\enum\order
 */
class RefundStatus extends EnumBasics
{
    // 进行中
    const NORMAL = 0;

    // 已拒绝
    const REJECTED = 10;

    // 已完成
    const COMPLETED = 20;

    // 已取消
    const CANCELLED = 30;

    /**
     * 获取枚举数据
     * @return array
     */
    public static function data()
    {
        return [
            self::NORMAL => [
                'name' => '进行中',
                'value' => self::NORMAL,
            ],
            self::REJECTED => [
                'name' => '已拒绝',
                'value' => self::REJECTED,
            ],
            self::COMPLETED => [
                'name' => '已完成',
                'value' => self::COMPLETED,
            ],
            self::CANCELLED => [
                'name' => '已取消',
                'value' => self::CANCELLED,
            ]
        ];
    }

}
