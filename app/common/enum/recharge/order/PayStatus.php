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

namespace app\common\enum\recharge\order;

use app\common\enum\EnumBasics;

/**
 * 用户充值订单-支付状态枚举类
 * Class PayStatus
 * @package app\common\enum\recharge\order
 */
class PayStatus extends EnumBasics
{
    // 待支付
    const PENDING = 10;

    // 支付成功
    const SUCCESS = 20;

    /**
     * 获取订单类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::PENDING => [
                'name' => '未支付',
                'value' => self::PENDING,
            ],
            self::SUCCESS => [
                'name' => '已支付',
                'value' => self::SUCCESS,
            ],
        ];
    }

}