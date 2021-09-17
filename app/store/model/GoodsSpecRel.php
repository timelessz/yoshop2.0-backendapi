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

namespace app\store\model;

use app\common\model\GoodsSpecRel as GoodsSpecRelModel;

/**
 * 商品规格关系模型
 * Class GoodsSpecRel
 * @package app\store\model
 */
class GoodsSpecRel extends GoodsSpecRelModel
{
    /**
     * 批量写入商品与规格值关系记录
     * @param int $goodsId
     * @param array $specList
     * @return array|false
     */
    public static function increased(int $goodsId, array $specList)
    {
        $dataset = [];
        foreach ($specList as $item) {
            foreach ($item['valueList'] as $specValueItem) {
                $dataset[] = [
                    'goods_id' => $goodsId,
                    'spec_id' => $item['spec_id'],
                    'spec_value_id' => $specValueItem['spec_value_id'],
                    'store_id' => self::$storeId
                ];
            }
        }
        return (new static)->addAll($dataset);
    }

    /**
     * 批量更新商品与规格值关系记录
     * @param int $goodsId
     * @param array $specList
     * @return array|false
     */
    public static function updates(int $goodsId, array $specList)
    {
        // 删除所有的记录
        static::deleteAll(['goods_id' => $goodsId]);
        // 批量新增记录
        return static::increased($goodsId, $specList);
    }

}
