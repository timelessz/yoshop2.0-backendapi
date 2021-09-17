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

namespace app\api\listener\order;

use app\common\service\Message as MessageService;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\OrderSource as OrderSourceEnum;
use app\common\exception\BaseException;

/**
 * 订单支付成功后扩展类
 * Class PaySuccess
 * @package app\api\behavior\order
 */
class PaySuccess
{
    // 订单信息
    private $order;

    // 订单类型
    private $orderType;

    // 当前商城ID
    private $storeId;

    /**
     * 订单来源回调业务映射类
     * @var array
     */
    protected $sourceCallbackClass = [
        OrderSourceEnum::MASTER => \app\api\service\master\order\PaySuccess::class,
    ];

    /**
     * 执行句柄
     * @param array $params
     * @return bool
     * @throws BaseException
     */
    public function handle(array $params)
    {
        // 解构赋值: 订单模型、订单类型
        ['order' => $order, 'orderType' => $orderType] = $params;
        // 设置当前类的属性
        $this->setAttribute($order, $orderType);
        // 订单公共事件
        $this->onCommonEvent();
        // 订单来源回调业务
        $this->onSourceCallback();
        return true;
    }

    /**
     * 设置当前类的属性
     * @param $order
     * @param int $orderType
     */
    private function setAttribute($order, $orderType = OrderTypeEnum::ORDER)
    {
        $this->order = $order;
        $this->storeId = $this->order['store_id'];
        $this->orderType = $orderType;
    }

    /**
     * 订单公共业务
     * @throws BaseException
     */
    private function onCommonEvent()
    {
        // 发送消息通知
        MessageService::send('order.payment', [
            'order' => $this->order,
            'order_type' => $this->orderType,
        ], $this->storeId);
    }

    /**
     * 订单来源回调业务
     * @return bool
     */
    private function onSourceCallback()
    {
        if (!isset($this->order['order_source'])) {
            return false;
        }
        if (!isset($this->sourceCallbackClass[$this->order['order_source']])) {
            return false;
        }
        $class = $this->sourceCallbackClass[$this->order['order_source']];
        return !is_null($class) ? (new $class)->onPaySuccess($this->order) : false;
    }

}