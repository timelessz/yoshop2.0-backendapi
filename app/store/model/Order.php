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

namespace app\store\model;

use think\facade\Request;
use app\common\model\Order as OrderModel;
use app\common\service\Order as OrderService;
use app\common\service\Message as MessageService;
use app\common\service\order\Refund as RefundService;
use app\common\enum\order\OrderStatus as OrderStatusEnum;
use app\common\enum\order\PayStatus as PayStatusEnum;
use app\common\enum\order\DeliveryType as DeliveryTypeEnum;
use app\common\enum\order\ReceiptStatus as ReceiptStatusEnum;
use app\common\enum\order\DeliveryStatus as DeliveryStatusEnum;
use app\common\library\helper;

/**
 * 订单管理
 * Class Order
 * @package app\store\model
 */
class Order extends OrderModel
{
    // 全部订单
    const LIST_TYPE_ALL = 'all';

    // 待发货订单
    const LIST_TYPE_DELIVERY = 'delivery';

    // 待收货订单
    const LIST_TYPE_RECEIPT = 'receipt';

    // 待付款订单
    const LIST_TYPE_PAY = 'pay';

    // 已完成订单
    const LIST_TYPE_COMPLETE = 'complete';

    // 已取消
    const LIST_TYPE_CANCEL = 'cancel';

    /**
     * 订单详情页数据
     * @param int $orderId
     * @return Order|array|false|null
     */
    public function getDetail(int $orderId)
    {
        return static::detail($orderId, [
            'user',
            'address',
            'goods' => ['image'],
            'express',
        ]) ?: false;
    }

    /**
     * 订单列表
     * @param string $dataType 订单类型
     * @param array $param
     * @return mixed
     */
    public function getList(string $dataType = self::LIST_TYPE_ALL, array $param = [])
    {
        // 检索查询条件
        $filter = $this->getQueryFilter($param);
        // 设置订单类型条件
        $dataTypeFilter = $this->getFilterDataType($dataType);
        // 获取数据列表
        return $this->with(['goods.image', 'user.avatar'])
            ->alias('order')
            ->field('order.*')
            ->leftJoin('user', 'user.user_id = order.user_id')
            ->where($dataTypeFilter)
            ->where($filter)
            ->where('order.is_delete', '=', 0)
            ->order(['order.create_time' => 'desc'])
            ->paginate(10);
    }

    /**
     * 订单列表(全部)
     * @param string $dataType 订单类型
     * @param array $query
     * @return mixed
     */
    public function getListAll(string $dataType = self::LIST_TYPE_ALL, array $query = [])
    {
        // 检索查询条件
        $queryFilter = $this->getQueryFilter($query);
        // 设置订单类型条件
        $dataTypeFilter = $this->getFilterDataType($dataType);
        // 获取数据列表
        return $this->with(['goods.image', 'address', 'user.avatar'])
            ->alias('order')
            ->field('order.*')
            ->join('user', 'user.user_id = order.user_id')
            ->where($dataTypeFilter)
            ->where($queryFilter)
            ->where('order.is_delete', '=', 0)
            ->order(['order.create_time' => 'desc'])
            ->select();
    }

    /**
     * 设置检索查询条件
     * @param array $param
     * @return array
     */
    private function getQueryFilter(array $param): array
    {
        // 默认参数
        $params = $this->setQueryDefaultValue($param, [
            'searchType' => '',     // 关键词类型 (10订单号 20会员昵称 30会员ID)
            'searchValue' => '',    // 关键词内容
            'orderSource' => -1,    // 订单来源
            'payType' => -1,        // 支付方式
            'deliveryType' => -1,   // 配送方式
            'betweenTime' => [],    // 起止时间
            'userId' => 0,          // 会员ID
        ]);
        // 检索查询条件
        $filter = [];
        // 关键词
        if (!empty($params['searchValue'])) {
            $searchWhere = [
                10 => ['order.order_no', 'like', "%{$params['searchValue']}%"],
                20 => ['user.nick_name', 'like', "%{$params['searchValue']}%"],
                30 => ['order.user_id', '=', (int)$params['searchValue']]
            ];
            array_key_exists($params['searchType'], $searchWhere) && $filter[] = $searchWhere[$params['searchType']];
        }
        // 起止时间
        if (!empty($params['betweenTime'])) {
            $times = between_time($params['betweenTime']);
            $filter[] = ['order.create_time', '>=', $times['start_time']];
            $filter[] = ['order.create_time', '<', $times['end_time'] + 86400];
        }
        // 订单来源
        $params['orderSource'] > -1 && $filter[] = ['order_source', '=', (int)$params['orderSource']];
        // 支付方式
        $params['payType'] > -1 && $filter[] = ['pay_type', '=', (int)$params['payType']];
        // 配送方式
        $params['deliveryType'] > -1 && $filter[] = ['delivery_type', '=', (int)$params['deliveryType']];
        // 用户id
        $params['userId'] > 0 && $filter[] = ['order.user_id', '=', (int)$params['userId']];
        return $filter;
    }

    /**
     * 设置订单类型条件
     * @param string $dataType
     * @return array
     */
    private function getFilterDataType(string $dataType = self::LIST_TYPE_ALL): array
    {
        // 数据类型
        $filter = [];
        switch ($dataType) {
            case self::LIST_TYPE_ALL:
                $filter = [];
                break;
            case self::LIST_TYPE_DELIVERY:
                $filter = [
                    ['pay_status', '=', PayStatusEnum::SUCCESS],
                    ['delivery_status', '=', DeliveryStatusEnum::NOT_DELIVERED],
                    ['order_status', 'in', [OrderStatusEnum::NORMAL, OrderStatusEnum::APPLY_CANCEL]]
                ];
                break;
            case self::LIST_TYPE_RECEIPT:
                $filter = [
                    ['pay_status', '=', PayStatusEnum::SUCCESS],
                    ['delivery_status', '=', DeliveryStatusEnum::DELIVERED],
                    ['receipt_status', '=', ReceiptStatusEnum::NOT_RECEIVED]
                ];
                break;
            case self::LIST_TYPE_PAY:
                $filter[] = ['pay_status', '=', PayStatusEnum::PENDING];
                $filter[] = ['order_status', '=', OrderStatusEnum::NORMAL];
                break;
            case self::LIST_TYPE_COMPLETE:
                $filter[] = ['order_status', '=', OrderStatusEnum::COMPLETED];
                break;
            case self::LIST_TYPE_CANCEL:
                $filter[] = ['order_status', '=', OrderStatusEnum::CANCELLED];
                break;
        }
        return $filter;
    }

    /**
     * 确认发货(单独订单)
     * @param $data
     * @return array|bool|false
     * @throws \Exception
     */
    public function delivery($data)
    {
        // 转义为订单列表
        $orderList = [$this];
        // 验证订单是否满足发货条件
        if (!$this->verifyDelivery($orderList)) {
            return false;
        }
        // 整理更新的数据
        $updateList = [[
            'order_id' => $this['order_id'],
            'express_id' => $data['express_id'],
            'express_no' => $data['express_no']
        ]];
        // 更新订单发货状态
        $this->updateToDelivery($updateList);
        // 获取已发货的订单
        $completed = self::detail($this['order_id'], ['user', 'address', 'goods', 'express']);
        // 发送消息通知
        $this->sendDeliveryMessage([$completed]);
        return true;
    }

    /**
     * 确认发货后发送消息通知
     * @param $orderList
     * @return bool
     */
    private function sendDeliveryMessage($orderList)
    {
        // 发送消息通知
        foreach ($orderList as $item) {
            MessageService::send('order.delivery', ['order' => $item], self::$storeId);
        }
        return true;
    }

    /**
     * 更新订单发货状态(批量)
     * @param $orderList
     * @return bool
     */
    private function updateToDelivery($orderList)
    {
        // 整理更新的数据
        $data = [];
        foreach ($orderList as $item) {
            $data[] = [
                'data' => [
                    'express_no' => $item['express_no'],
                    'express_id' => $item['express_id'],
                    'delivery_status' => 20,
                    'delivery_time' => time(),
                ],
                'where' => ['order_id' => $item['order_id']]
            ];
        }
        // 批量更新
        $this->updateAll($data);
        return true;
    }

    /**
     * 验证订单是否满足发货条件
     * @param $orderList
     * @return bool
     */
    private function verifyDelivery($orderList)
    {
        foreach ($orderList as $order) {
            if (
                $order['pay_status'] != PayStatusEnum::SUCCESS
                || $order['delivery_type'] != DeliveryTypeEnum::EXPRESS
                || $order['delivery_status'] != DeliveryStatusEnum::NOT_DELIVERED
            ) {
                $this->error = "订单号[{$order['order_no']}] 不满足发货条件!";
                return false;
            }
        }
        return true;
    }

    /**
     * 修改订单价格
     * @param array $data
     * @return bool
     */
    public function updatePrice(array $data)
    {
        if ($this['pay_status'] != PayStatusEnum::PENDING) {
            $this->error = '该订单不合法';
            return false;
        }
        // 实际付款金额
        $payPrice = helper::bcadd($data['order_price'], $data['express_price']);
        if ($payPrice <= 0) {
            $this->error = '订单实付款价格不能为0.00元';
            return false;
        }
        // 改价的金额差价
        $updatePrice = helper::bcsub($data['order_price'], $this['order_price']);
        // 更新订单记录
        return $this->save([
                'order_no' => $this->orderNo(), // 修改订单号, 否则微信支付提示重复
                'pay_price' => $payPrice,
                'update_price' => $updatePrice,
                'express_price' => $data['express_price']
            ]) !== false;
    }

    /**
     * 审核：用户取消订单
     * @param array $data
     * @return bool|mixed
     */
    public function confirmCancel(array $data)
    {
        // 判断订单是否有效
        if ($this['pay_status'] != PayStatusEnum::SUCCESS) {
            $this->error = '该订单不合法';
            return false;
        }
        // 订单取消事件
        return $this->transaction(function () use ($data) {
            if ($data['status'] == true) {
                // 执行退款操作
                (new RefundService)->execute($this);
                // 订单取消事件
                OrderService::cancelEvent($this);
            }
            // 更新订单状态
            return $this->save(['order_status' => $data['status'] ? OrderStatusEnum::CANCELLED : OrderStatusEnum::NORMAL]);
        });
    }

    /**
     * 获取已付款订单总数 (可指定某天)
     * @param null $startDate
     * @param null $endDate
     * @return int|string
     */
    public function getPayOrderTotal($startDate = null, $endDate = null)
    {
        $filter = [
            ['pay_status', '=', PayStatusEnum::SUCCESS],
            ['order_status', '<>', OrderStatusEnum::CANCELLED]
        ];
        if (!is_null($startDate) && !is_null($endDate)) {
            $filter[] = ['pay_time', '>=', strtotime($startDate)];
            $filter[] = ['pay_time', '<', strtotime($endDate) + 86400];
        }
        return $this->getOrderTotal($filter);
    }

    /**
     * 获取未发货订单数量
     * @return int
     */
    public function getNotDeliveredOrderTotal()
    {
        $filter = [
            ['pay_status', '=', PayStatusEnum::SUCCESS],
            ['delivery_status', '=', DeliveryStatusEnum::NOT_DELIVERED],
            ['order_status', 'in', [OrderStatusEnum::NORMAL, OrderStatusEnum::APPLY_CANCEL]]
        ];
        return $this->getOrderTotal($filter);
    }

    /**
     * 获取未付款订单数量
     * @return int
     */
    public function getNotPayOrderTotal()
    {
        $filter = [
            ['pay_status', '=', PayStatusEnum::PENDING],
            ['order_status', '=', OrderStatusEnum::NORMAL]
        ];
        return $this->getOrderTotal($filter);
    }

    /**
     * 获取订单总数
     * @param array $filter
     * @return int
     */
    private function getOrderTotal(array $filter = [])
    {
        // 获取订单总数量
        return $this->where($filter)
            ->where('is_delete', '=', 0)
            ->count();
    }

    /**
     * 获取某天的总销售额
     * @param null $startDate
     * @param null $endDate
     * @return float|int
     */
    public function getOrderTotalPrice($startDate = null, $endDate = null)
    {
        // 查询对象
        $query = $this->getNewQuery();
        // 设置查询条件
        if (!is_null($startDate) && !is_null($endDate)) {
            $query->where('pay_time', '>=', strtotime($startDate))
                ->where('pay_time', '<', strtotime($endDate) + 86400);
        }
        // 总销售额
        return $query->where('pay_status', '=', PayStatusEnum::SUCCESS)
            ->where('order_status', '<>', OrderStatusEnum::CANCELLED)
            ->where('is_delete', '=', 0)
            ->sum('pay_price');
    }

    /**
     * 获取某天的下单用户数
     * @param string $day
     * @return float|int
     */
    public function getPayOrderUserTotal(string $day)
    {
        $startTime = strtotime($day);
        return $this->field('user_id')
            ->where('pay_time', '>=', $startTime)
            ->where('pay_time', '<', $startTime + 86400)
            ->where('pay_status', '=', PayStatusEnum::SUCCESS)
            ->where('is_delete', '=', '0')
            ->group('user_id')
            ->count();
    }

}
