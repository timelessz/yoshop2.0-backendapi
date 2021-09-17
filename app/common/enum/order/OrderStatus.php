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
 * 枚举类：订单状态
 * Class OrderStatus
 * @package app\common\enum\order
 */
class OrderStatus extends EnumBasics
{
    // 进行中
    const NORMAL = 10;

    // 已取消
    const CANCELLED = 20;

    // 待取消
    const APPLY_CANCEL = 21;

    // 已完成
    const COMPLETED = 30;

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
            self::CANCELLED => [
                'name' => '已取消',
                'value' => self::CANCELLED,
            ],
            self::APPLY_CANCEL => [
                'name' => '待取消',
                'value' => self::APPLY_CANCEL,
            ],
            self::COMPLETED => [
                'name' => '已完成',
                'value' => self::COMPLETED,
            ],
        ];
    }

}