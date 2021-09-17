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

namespace app\api\service\coupon;

use app\common\service\BaseService;
use app\common\library\helper;

/**
 * 订单优惠券折扣服务类
 * Class GoodsDeduct
 * @package app\api\service\coupon
 */
class GoodsDeduct extends BaseService
{
    private $actualReducedMoney;

    public function setGoodsCouponMoney($goodsList, $reducedMoney)
    {
        // 统计订单商品总金额,(单位分)
        $orderTotalPrice = 0;
        foreach ($goodsList as &$goods) {
            $goods['total_price'] *= 100;
            $orderTotalPrice += $goods['total_price'];
        }
        // 计算实际抵扣金额
        $this->setActualReducedMoney($reducedMoney, $orderTotalPrice);
        // 实际抵扣金额为0，
        if ($this->actualReducedMoney > 0) {
            // 计算商品的价格权重
            $goodsList = $this->getGoodsListWeight($goodsList, $orderTotalPrice);
            // 计算商品优惠券抵扣金额
            $this->setGoodsListCouponMoney($goodsList);
            // 总抵扣金额
            $totalCouponMoney = helper::getArrayColumnSum($goodsList, 'coupon_money');
            $this->setGoodsListCouponMoneyFill($goodsList, $totalCouponMoney);
            $this->setGoodsListCouponMoneyDiff($goodsList, $totalCouponMoney);
        }
        return $goodsList;
    }

    public function getActualReducedMoney()
    {
        return $this->actualReducedMoney;
    }

    private function setActualReducedMoney($reducedMoney, $orderTotalPrice)
    {
        $reducedMoney *= 100;
        $this->actualReducedMoney = ($reducedMoney >= $orderTotalPrice) ? $orderTotalPrice - 1 : $reducedMoney;
    }

    private function arraySortByWeight($goodsList)
    {
        return array_sort($goodsList, 'weight', true);
    }

    private function getGoodsListWeight($goodsList, $orderTotalPrice)
    {
        foreach ($goodsList as &$goods) {
            $goods['weight'] = $goods['total_price'] / $orderTotalPrice;
        }
        return $this->arraySortByWeight($goodsList);
    }

    private function setGoodsListCouponMoney(&$goodsList)
    {
        foreach ($goodsList as &$goods) {
            $goods['coupon_money'] = helper::bcmul($this->actualReducedMoney, $goods['weight'], 0);
        }
        return true;
    }

    private function setGoodsListCouponMoneyFill(&$goodsList, $totalCouponMoney)
    {
        if ($totalCouponMoney === 0) {
            $temReducedMoney = $this->actualReducedMoney;
            foreach ($goodsList as &$goods) {
                if ($temReducedMoney === 0) break;
                $goods['coupon_money'] = 1;
                $temReducedMoney--;
            }
        }
        return true;
    }

    private function setGoodsListCouponMoneyDiff(&$goodsList, $totalCouponMoney)
    {
        $tempDiff = $this->actualReducedMoney - $totalCouponMoney;
        foreach ($goodsList as &$goods) {
            if ($tempDiff < 1) break;
            $goods['coupon_money']++ && $tempDiff--;
        }
        return true;
    }

}