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

namespace app\store\service;

use app\common\library\helper;
use app\common\service\BaseService;
use app\store\model\User as UserModel;
use app\store\model\Goods as GoodsModel;
use app\store\model\Order as OrderModel;
use app\store\model\OrderRefund as OrderRefundModel;

/**
 * 商户数据服务类
 * Class Store
 * @package app\store\service
 */
class Home extends BaseService
{
    /* @var GoodsModel $GoodsModel */
    private $GoodsModel;

    /* @var OrderModel $GoodsModel */
    private $OrderModel;

    /* @var UserModel $GoodsModel */
    private $UserModel;

    /**
     * 构造方法
     */
    public function __construct()
    {
        parent::__construct();
        /* 初始化模型 */
        $this->GoodsModel = new GoodsModel;
        $this->OrderModel = new OrderModel;
        $this->UserModel = new UserModel;
    }

    /**
     * 后台首页数据
     * @return array
     */
    public function getData(): array
    {
        // 今天的日期
        $today = date('Y-m-d');
        // 昨天的日期
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        // 最近七天日期
        $lately7days = $this->getLately7days();
        $data = [
            // 实时概况
            'overview' => [
                // 销售额(元)
                'orderTotalPrice' => [
                    'tday' => $this->getOrderTotalPrice($today),
                    'ytd' => $this->getOrderTotalPrice($yesterday)
                ],
                // 支付订单数
                'orderTotal' => [
                    'tday' => $this->getPayOrderTotal($today),
                    'ytd' => $this->getPayOrderTotal($yesterday)
                ],
                // 新增会员数
                'newUserTotal' => [
                    'tday' => $this->getUserTotal($today),
                    'ytd' => $this->getUserTotal($yesterday)
                ],
                // 付款会员数
                'consumeUserTotal' => [
                    'tday' => $this->getPayOrderUserTotal($today),
                    'ytd' => $this->getPayOrderUserTotal($yesterday)
                ]
            ],
            // 数据统计
            'statistics' => [
                // 商品总数量
                'goodsTotal' => $this->getGoodsTotal(),
                // 会员总人数
                'userTotal' => $this->getUserTotal(),
                // 付款订单总量
                'orderTotal' => $this->getPayOrderTotal(),
                // 消费总人数
                'consumeUserTotal' => $this->getUserTotal(null, true)
            ],
            // 待办事项
            'pending' => [
                // 待发货订单
                'deliverOrderTotal' => $this->getNotDeliveredOrderTotal(),
                // 待处理售后单
                'refundTotal' => $this->getRefundTotal(),
                // 待付款订单(笔)
                'paidOrderTotal' => $this->getNotPayOrderTotal(),
                // 已售罄商品数量
                'soldoutGoodsTotal' => $this->getSoldoutGoodsTotal()
            ],
            // 交易走势
            'tradeTrend' => [
                // 最近七天日期
                'date' => $lately7days,
                'orderTotal' => $this->getOrderTotalByDate($lately7days),
                'orderTotalPrice' => $this->getOrderTotalPriceByDate($lately7days)
            ]
        ];
        return $data;
    }

    /**
     * 最近七天日期
     */
    private function getLately7days()
    {
        // 获取当前周几
        $date = [];
        for ($i = 0; $i < 7; $i++) {
            $date[] = date('Y-m-d', strtotime('-' . $i . ' days'));
        }
        return array_reverse($date);
    }

    /**
     * 获取商品总量
     * @return string
     */
    private function getGoodsTotal()
    {
        return number_format($this->GoodsModel->getGoodsTotal());
    }

    /**
     * 会员总人数
     * @param string $date 注册日期
     * @param true $isConsume 是否已消费
     * @return string
     */
    private function getUserTotal(string $date = null, $isConsume = null)
    {
        return number_format($this->UserModel->getUserTotal(compact('date', 'isConsume')));
    }

    /**
     * 获取已付款订单总量 (批量)
     * @param array $days
     * @return array
     */
    private function getOrderTotalByDate(array $days)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = $this->getPayOrderTotal($day);
        }
        return $data;
    }

    /**
     * 获取订单总金额(指定日期)
     * @param string $day
     * @return string
     */
    private function getOrderTotalPrice(string $day = null)
    {
        return helper::number2($this->OrderModel->getOrderTotalPrice($day, $day));
    }

    /**
     * 获取订单总金额 (批量)
     * @param array $days
     * @return array
     */
    private function getOrderTotalPriceByDate(array $days)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = $this->getOrderTotalPrice($day);
        }
        return $data;
    }

    /**
     * 获取某天的下单用户数
     * @param string $day
     * @return float|int
     */
    private function getPayOrderUserTotal(string $day)
    {
        return number_format($this->OrderModel->getPayOrderUserTotal($day));
    }

    /**
     * 获取订单总量
     * @param string $day
     * @return string
     */
    private function getPayOrderTotal(string $day = null)
    {
        return number_format($this->OrderModel->getPayOrderTotal($day, $day));
    }

    // 获取未发货订单数量
    private function getNotDeliveredOrderTotal()
    {
        return number_format($this->OrderModel->getNotDeliveredOrderTotal());
    }

    // 获取未付款订单数量
    private function getNotPayOrderTotal()
    {
        return number_format($this->OrderModel->getNotPayOrderTotal());
    }

    // 获取已售罄的商品
    private function getSoldoutGoodsTotal()
    {
        return number_format($this->GoodsModel->getSoldoutGoodsTotal());
    }

    // 获取待处理售后单数量
    private function getRefundTotal()
    {
        $model = new OrderRefundModel;
        return number_format($model->getRefundTotal());
    }

}
