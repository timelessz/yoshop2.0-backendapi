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

namespace app\common\service\goods\source;

use app\common\service\BaseService;

/**
 * 商品来源抽象类
 * Class Basics
 * @package app\common\service\stock
 */
abstract class Basics extends BaseService
{
    /**
     * 更新商品库存 (针对下单减库存的商品)
     * @param $goodsList
     * @return mixed
     */
    abstract function updateGoodsStock($goodsList);

    /**
     * 更新商品库存销量（订单付款后）
     * @param $goodsList
     * @return mixed
     */
    abstract function updateStockSales($goodsList);

    /**
     * 回退商品库存
     * @param $goodsList
     * @param bool $isPayOrder 是否为已支付订单
     * @return mixed
     */
    abstract function backGoodsStock($goodsList, bool $isPayOrder = false);

}