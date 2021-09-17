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

namespace app\api\service\order\source;

use app\common\service\BaseService;
use app\common\enum\order\OrderStatus as OrderStatusEnum;
use app\common\enum\order\PayStatus as OrderPayStatusEnum;

abstract class Basics extends BaseService
{
    /**
     * 判断订单是否允许付款
     * @param $order
     * @return mixed
     */
    abstract public function checkOrderStatusOnPay($order);

    /**
     * 判断商品状态、库存 (未付款订单)
     * @param $goodsList
     * @return mixed
     */
    abstract protected function checkGoodsStatusOnPay($goodsList);

    /**
     * 判断订单状态(公共)
     * @param $order
     * @return bool
     */
    protected function checkOrderStatusOnPayCommon($order)
    {

        // 判断订单状态
        if (
            $order['order_status'] != OrderStatusEnum::NORMAL
            || $order['pay_status'] != OrderPayStatusEnum::PENDING
        ) {
            $this->error = '很抱歉，当前订单不合法，无法支付';
            return false;
        }
        return true;
    }

}