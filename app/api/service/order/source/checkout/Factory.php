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

namespace app\api\service\order\source\checkout;

use app\common\service\BaseService;
use app\common\enum\order\OrderSource as OrderSourceEnum;

/**
 * 订单结算台扩展工厂类
 * Class Factory
 * @package app\common\service\stock
 */
class Factory extends BaseService
{
    // 订单来源的结算台服务类
    private static $class = [
        OrderSourceEnum::MASTER => Master::class,
    ];

    /**
     * 根据订单来源获取商品库存类
     * @param $user
     * @param $goodsList
     * @param int $orderSource
     * @return mixed
     */
    public static function getFactory($user, $goodsList, $orderSource = OrderSourceEnum::MASTER)
    {
        return new static::$class[$orderSource]($user, $goodsList);
    }

}