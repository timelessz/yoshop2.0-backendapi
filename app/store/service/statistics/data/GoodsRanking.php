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
use app\store\model\OrderGoods as OrderGoodsModel;
use app\common\enum\order\OrderStatus as OrderStatusEnum;
use app\common\enum\order\PayStatus as OrderPayStatusEnum;

/**
 * 数据统计-商品销售榜
 * Class GoodsRanking
 * @package app\store\service\statistics\data
 */
class GoodsRanking extends BaseService
{
    /**
     * 商品销售榜
     * @return mixed
     */
    public function getGoodsRanking()
    {
        return (new OrderGoodsModel)->alias('o_goods')
            ->field([
                'goods_id',
                // 这里采用聚合函数获取goods_name, 因为mysql5.7使用group的报错问题
                'MAX(goods_name) AS goods_name',
                'SUM(total_pay_price) AS sales_volume',
                'SUM(total_num) AS total_sales_num'
            ])
            ->join('order', 'order.order_id = o_goods.order_id')
            ->where('order.pay_status', '=', OrderPayStatusEnum::SUCCESS)
            ->where('order.order_status', '<>', OrderStatusEnum::CANCELLED)
            // 这里如果写入goods_name的话，会出现同商品ID不同name的数据
            ->group('goods_id')
            // order：此处按总销售额排序，如需按销量改为total_sales_num
            ->order(['sales_volume' => 'DESC'])
            ->limit(10)
            ->select();
    }

}
