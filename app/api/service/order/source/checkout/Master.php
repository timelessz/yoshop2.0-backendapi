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

namespace app\api\service\order\source\checkout;

/**
 * 订单结算台-普通商品扩展类
 * Class Checkout
 * @package app\api\service\master
 */
class Master extends Basics
{
    /**
     * 验证商品列表
     * @return bool
     */
    public function validateGoodsList()
    {
        foreach ($this->goodsList as $goods) {
            // 判断商品是否下架
            if ($goods['status'] != 10) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 已下架";
                return false;
            }
            // 判断商品库存
            if ($goods['total_num'] > $goods['skuInfo']['stock_num']) {
                $this->error = "很抱歉，商品 [{$goods['goods_name']}] 库存不足";
                return false;
            }
        }
        return true;
    }

}