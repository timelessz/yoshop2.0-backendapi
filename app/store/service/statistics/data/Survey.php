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

use app\common\library\helper;
use app\common\service\BaseService;
use app\store\model\User as UserModel;
use app\store\model\Order as OrderModel;
use app\store\model\Goods as GoodsModel;
use app\store\model\recharge\Order as RechargeOrderModel;
use app\common\enum\order\PayStatus as PayStatusEnum;
use app\common\enum\recharge\order\PayStatus as RechargePayStatusEnum;

/**
 * 数据概况
 * Class Survey
 * @package app\store\service\statistics\data
 */
class Survey extends BaseService
{
    /**
     * 获取数据概况
     * @param null $startDate
     * @param null $endDate
     * @return array
     */
    public function getSurveyData($startDate = null, $endDate = null)
    {
        return [
            // 用户数量
            'userTotal' => $this->getUserTotal($startDate, $endDate),
            // 消费人数
            'consumeUsers' => $this->getConsumeUsers($startDate, $endDate),
            // 支付订单数
            'orderTotal' => $this->getOrderTotal($startDate, $endDate),
            // 销售额(元)
            'orderTotalPrice' => $this->getOrderTotalPrice($startDate, $endDate),
            // 商品总量
            'goodsTotal' => $this->getGoodsTotal($startDate, $endDate),
            // 用户充值总额
            'rechargeTotalMoney' => $this->getRechargeTotalMoney($startDate, $endDate),
        ];
    }

    /**
     * 获取用户总量
     * @param null $startDate
     * @param null $endDate
     * @return string
     */
    private function getUserTotal($startDate = null, $endDate = null)
    {
        // 检索查询条件
        $filter = [];
        if (!is_null($startDate) && !is_null($endDate)) {
            $filter[] = ['create_time', '>=', strtotime($startDate)];
            $filter[] = ['create_time', '<', strtotime($endDate) + 86400];
        }
        // 查询总记录
        $value = (new UserModel)->where($filter)
            ->where('is_delete', '=', '0')
            ->count();
        return number_format($value);
    }

    /**
     * 消费人数
     * @param null $startDate
     * @param null $endDate
     * @return string
     */
    public function getConsumeUsers($startDate = null, $endDate = null)
    {
        // 检索查询条件
        $filter = [];
        if (!is_null($startDate) && !is_null($endDate)) {
            $filter[] = ['pay_time', '>=', strtotime($startDate)];
            $filter[] = ['pay_time', '<', strtotime($endDate) + 86400];
        }
        // 查询总记录
        $value = (new OrderModel)->field('user_id')
            ->where($filter)
            ->where('pay_status', '=', PayStatusEnum::SUCCESS)
            //  ->where('order_status', '<>', OrderStatusEnum::CANCELLED)
            ->where('is_delete', '=', '0')
            ->group('user_id')
            ->count();
        return number_format($value);
    }

    /**
     * 获取订单总量
     * @param null $startDate
     * @param null $endDate
     * @return string
     */
    private function getOrderTotal($startDate = null, $endDate = null)
    {
        return number_format((new OrderModel)->getPayOrderTotal($startDate, $endDate));
    }

    /**
     * 付款订单总额
     * @param null $startDate
     * @param null $endDate
     * @return string
     */
    private function getOrderTotalPrice($startDate = null, $endDate = null)
    {
        return helper::number2((new OrderModel)->getOrderTotalPrice($startDate, $endDate));
    }

    /**
     * 获取商品总量
     * @param null $startDate
     * @param null $endDate
     * @return string
     */
    private function getGoodsTotal($startDate = null, $endDate = null)
    {
        // 检索查询条件
        $filter = [];
        if (!is_null($startDate) && !is_null($endDate)) {
            $filter[] = ['create_time', '>=', strtotime($startDate)];
            $filter[] = ['create_time', '<', strtotime($endDate) + 86400];
        }
        // 查询总记录
        $value = (new GoodsModel)->where($filter)
            ->where('is_delete', '=', 0)
            ->count();
        return number_format($value);
    }

    /**
     * 用户充值总额
     * @param null $startDate
     * @param null $endDate
     * @return float|int
     */
    private function getRechargeTotalMoney($startDate = null, $endDate = null)
    {
        // 检索查询条件
        $filter = [];
        if (!is_null($startDate) && !is_null($endDate)) {
            $filter[] = ['pay_time', '>=', strtotime($startDate)];
            $filter[] = ['pay_time', '<', strtotime($endDate) + 86400];
        }
        // 查询总记录
        $value = (new RechargeOrderModel)->where($filter)
            ->where('pay_status', '=', RechargePayStatusEnum::SUCCESS)
            ->sum('actual_money');
        return helper::number2($value);
    }

}
