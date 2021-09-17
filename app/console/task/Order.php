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

namespace app\console\task;

use app\console\service\Order as OrderService;
use app\console\model\Setting as SettingModel;

/**
 * 定时任务：商城订单
 * Class Order
 * @package app\console\task
 */
class Order extends Task
{
    // 当前任务唯一标识
    private $taskKey = 'Order';

    // 任务执行间隔时长 (单位:秒)
    protected $taskExpire = 60 * 30;

    // 当前商城ID
    private $storeId;

    /**
     * 任务处理
     * @param array $param
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function handle(array $param)
    {
        ['storeId' => $this->storeId] = $param;
        $this->setInterval($this->storeId, $this->taskKey, $this->taskExpire, function () {
            // echo $this->taskKey . PHP_EOL;
            // 未支付订单自动关闭
            $this->closeEvent();
            // 已发货订单自动确认收货
            $this->receiveEvent();
            // 已完成订单结算
            $this->settledEvent();
        });
    }

    /**
     * 未支付订单自动关闭
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function closeEvent()
    {
        // 自动关闭订单的天数
        $closeDays = (int)$this->getTradeSetting()['close_days'];
        // 执行自动关闭
        if ($closeDays > 0) {
            $service = new OrderService;
            $service->closeEvent($this->storeId, $closeDays);
        }
    }

    /**
     * 自动确认收货订单的天数
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function receiveEvent()
    {
        // 取消n天以前的的未付款订单
        $receiveDays = (int)$this->getTradeSetting()['receive_days'];
        // 执行自动确认收货
        if ($receiveDays > 0) {
            $service = new OrderService;
            $service->receiveEvent($this->storeId, $receiveDays);
        }
    }

    /**
     * 已完成订单自动结算
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function settledEvent()
    {
        // 取消n天以前的的未付款订单
        $refundDays = (int)$this->getTradeSetting()['refund_days'];
        // 执行自动确认收货
        if ($refundDays > 0) {
            $service = new OrderService;
            $service->settledEvent($this->storeId, $refundDays);
        }
    }

    /**
     * 获取商城交易设置
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getTradeSetting()
    {
        return SettingModel::getItem('trade', $this->storeId)['order'];
    }
}