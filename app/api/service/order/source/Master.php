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

use app\api\model\GoodsSku as GoodsSkuModel;

/**
 * 订单来源-普通订单扩展类
 * Class Master
 * @package app\api\service\order\source
 */
class Master extends Basics
{
    /**
     * 判断订单是否允许付款
     * @param $order
     * @return bool
     */
    public function checkOrderStatusOnPay($order)
    {
        // 判断订单状态
        if (!$this->checkOrderStatusOnPayCommon($order)) {
            return false;
        }
        // 判断商品状态、库存
        if (!$this->checkGoodsStatusOnPay($order['goods'])) {
            return false;
        }
        return true;
    }

    /**
     * 判断商品状态、库存 (未付款订单)
     * @param $goodsList
     * @return bool
     */
    protected function checkGoodsStatusOnPay($goodsList)
    {
        foreach ($goodsList as $goods) {
            // 判断商品是否下架
            if (
                empty($goods['goods'])
                || $goods['goods']['status'] != 10
            ) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 已下架";
                return false;
            }
            // 获取商品的sku信息
            $goodsSku = $this->getOrderGoodsSku($goods['goods_id'], $goods['goods_sku_id']);
            // sku已不存在
            if (empty($goodsSku)) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] sku已不存在，请重新下单";
                return false;
            }
            // 付款减库存
            if ($goods['deduct_stock_type'] == 20 && $goods['total_num'] > $goodsSku['stock_num']) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 库存不足";
                return false;
            }
        }
        return true;
    }

    /**
     * 获取指定的商品sku信息
     * @param $goodsId
     * @param $goodsSkuId
     * @return GoodsSkuModel|array|null
     */
    private function getOrderGoodsSku($goodsId, $goodsSkuId)
    {
        return GoodsSkuModel::detail($goodsId, $goodsSkuId);
    }

}