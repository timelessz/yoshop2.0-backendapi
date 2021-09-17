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
declare (strict_types=1);

namespace app\console\service;

use app\console\model\Order as OrderModel;
use app\common\service\BaseService;
use app\common\service\Order as OrderService;
use app\common\service\order\Complete as OrderCompleteService;
use app\common\enum\order\OrderStatus as OrderStatusEnum;
use app\common\library\helper;
use app\console\library\Tools;

/**
 * 服务类：订单模块
 * Class Order
 * @package app\console\service
 */
class Order extends BaseService
{
    /**
     * 未支付订单自动关闭
     * @param int $storeId
     * @param int $closeDays 自定关闭订单天数
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function closeEvent(int $storeId, int $closeDays)
    {
        // 截止时间
        $deadlineTime = time() - ((int)$closeDays * 86400);
        // 查询截止时间未支付的订单
        $model = new OrderModel;
        $list = $model->getListByClose($storeId, $deadlineTime);
        // 订单ID集
        $orderIds = helper::getArrayColumn($list, 'order_id');
        if (!empty($orderIds)) {
            // 取消订单事件
            foreach ($list as $order) {
                OrderService::cancelEvent($order);
            }
            // 批量更新订单状态为已取消
            $model->onBatchUpdate($orderIds, ['order_status' => OrderStatusEnum::CANCELLED]);
        }
        // 记录日志
        Tools::taskLogs('Order', 'closeEvent', [
            'storeId' => $storeId,
            'closeDays' => $closeDays,
            'deadlineTime' => $deadlineTime,
            'orderIds' => helper::jsonEncode($orderIds)
        ]);
    }

    /**
     * 已发货订单自动确认收货
     * @param int $storeId
     * @param int $receiveDays 自动收货天数
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function receiveEvent(int $storeId, int $receiveDays)
    {
        // 截止时间
        $deadlineTime = time() - ((int)$receiveDays * 86400);
        // 查询截止时间未确认收货的订单ID集
        $model = new OrderModel;
        $orderIds = $model->getOrderIdsByReceive($storeId, $deadlineTime);
        // 更新订单收货状态
        if (!empty($orderIds)) {
            // 批量更新订单状态为已收货
            $model->onUpdateReceived($orderIds);
            // 批量处理已完成的订单
            $this->onReceiveCompleted($storeId, $orderIds);
        }
        // 记录日志
        Tools::taskLogs('Order', 'receiveEvent', [
            'storeId' => $storeId,
            'receiveDays' => $receiveDays,
            'deadlineTime' => $deadlineTime,
            'orderIds' => helper::jsonEncode($orderIds)
        ]);
    }

    /**
     * 已完成订单自动结算
     * @param int $storeId
     * @param int $refundDays 售后期限天数
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function settledEvent(int $storeId, int $refundDays)
    {
        // 截止时间
        $deadlineTime = time() - ((int)$refundDays * 86400);
        // 查询截止时间确认收货的订单ID集
        $model = new OrderModel;
        $list = $model->getOrderListBySettled($storeId, $deadlineTime);
        // 订单ID集
        $orderIds = helper::getArrayColumn($list, 'order_id');
        // 订单结算服务
        if (!empty($orderIds)) {
            $OrderCompleteService = new OrderCompleteService();
            $OrderCompleteService->settled($list);
        }
        // 记录日志
        Tools::taskLogs('Order', 'settledEvent', [
            'storeId' => $storeId,
            'refundDays' => $refundDays,
            'deadlineTime' => $deadlineTime,
            'orderIds' => helper::jsonEncode($orderIds)
        ]);
    }

    /**
     * 批量处理已完成的订单
     * @param int $storeId 商城ID
     * @param array $orderIds 订单ID集
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function onReceiveCompleted(int $storeId, array $orderIds)
    {
        // 获取已完成的订单列表
        $model = new OrderModel;
        $list = $model->getListByOrderIds($storeId, $orderIds);
        // 执行订单完成后的操作
        if (!$list->isEmpty()) {
            $OrderCompleteService = new OrderCompleteService();
            $OrderCompleteService->complete($list, $storeId);
        }
        return true;
    }

}