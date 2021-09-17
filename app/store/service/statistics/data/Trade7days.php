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

namespace app\store\service\statistics\data;

use app\common\service\BaseService;
use app\store\model\Order as OrderModel;
use app\common\library\helper;

/**
 * 近7日走势
 * Class Trade7days
 * @package app\store\service\statistics\data
 */
class Trade7days extends BaseService
{
    /* @var OrderModel $GoodsModel */
    private $OrderModel;

    /**
     * 构造方法
     */
    public function __construct()
    {
        parent::__construct();
        /* 初始化模型 */
        $this->OrderModel = new OrderModel;
    }

    /**
     * 近7日走势
     * @return array
     */
    public function getTransactionTrend()
    {
        // 最近七天日期
        $lately7days = $this->getLately7days();
        return [
            'date' => $lately7days,
            'orderTotal' => $lately7days,
            'orderTotalPrice' => $this->getOrderTotalPriceByDate($lately7days)
        ];
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
     * 获取某天的总销售额
     * @param null $day
     * @return string
     */
    private function getOrderTotalPrice($day = null)
    {
        return helper::number2($this->OrderModel->getOrderTotalPrice($day, $day));
    }

    /**
     * 获取订单总量 (指定日期)
     * @param $days
     * @return array
     */
    private function getOrderTotalPriceByDate($days)
    {
        $data = [];
        foreach ($days as $day) {
            $data[] = $this->getOrderTotalPrice($day);
        }
        return $data;
    }

}