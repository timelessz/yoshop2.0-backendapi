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

namespace app\store\model;

use app\common\library\helper;
use app\common\model\GoodsSku as GoodsSkuModel;
use app\common\enum\goods\SpecType as SpecTypeEnum;

/**
 * 商品规格模型
 * Class GoodsSku
 * @package app\store\model
 */
class GoodsSku extends GoodsSkuModel
{
    /**
     * 获取库存总数量 (根据sku列表数据)
     * @param array $skuList
     * @return float|int
     */
    public static function getStockTotal(array $skuList)
    {
        return helper::getArrayColumnSum($skuList, 'stock_num');
    }

    /**
     * 获取商品价格高低区间 (根据sku列表数据)
     * @param array $skuList
     * @return array
     */
    public static function getGoodsPrices(array $skuList)
    {
        $goodsPriceArr = helper::getArrayColumn($skuList, 'goods_price');
        return [min($goodsPriceArr), max($goodsPriceArr)];
    }

    /**
     * 获取划线价格高低区间 (根据sku列表数据)
     * @param array $skuList
     * @return array
     */
    public static function getLinePrices(array $skuList)
    {
        $linePriceArr = helper::getArrayColumn($skuList, 'line_price');
        return [min($linePriceArr), max($linePriceArr)];
    }

    /**
     * 生成skuList数据(写入goods_sku_id)
     * @param array $newSpecList
     * @param array $skuList
     * @return array
     */
    public static function getNewSkuList(array $newSpecList, array $skuList)
    {
        foreach ($skuList as &$skuItem) {
            $skuItem['specValueIds'] = static::getSpecValueIds($newSpecList, $skuItem['skuKeys']);
            $skuItem['goodsProps'] = static::getGoodsProps($newSpecList, $skuItem['skuKeys']);
            $skuItem['goods_sku_id'] = implode('_', $skuItem['specValueIds']);
        }
        return $skuList;
    }

    /**
     * 根据$skuKeys生成规格值id集
     * @param array $newSpecList
     * @param array $skuKeys
     * @return array
     */
    private static function getSpecValueIds(array $newSpecList, array $skuKeys)
    {
        $goodsSkuIdArr = [];
        foreach ($skuKeys as $skuKey) {
            $specValueItem = $newSpecList[$skuKey['groupKey']]['valueList'][$skuKey['valueKey']];
            $goodsSkuIdArr[] = $specValueItem['spec_value_id'];
        }
        return $goodsSkuIdArr;
    }

    /**
     * 根据$skuKeys生成规格属性记录
     * @param array $newSpecList
     * @param array $skuKeys
     * @return array
     */
    private static function getGoodsProps(array $newSpecList, array $skuKeys)
    {
        $goodsPropsArr = [];
        foreach ($skuKeys as $skuKey) {
            $groupItem = $newSpecList[$skuKey['groupKey']];
            $specValueItem = $groupItem['valueList'][$skuKey['valueKey']];
            $goodsPropsArr[] = [
                'group' => ['name' => $groupItem['spec_name'], 'id' => $groupItem['spec_id']],
                'value' => ['name' => $specValueItem['spec_value'], 'id' => $specValueItem['spec_value_id']]
            ];
        }
        return $goodsPropsArr;
    }

    /**
     * 新增商品sku记录
     * @param int $goodsId
     * @param array $newSkuList
     * @param int $specType
     * @return array|bool|false
     */
    public static function add(int $goodsId, int $specType = SpecTypeEnum::SINGLE, array $newSkuList = [])
    {
        // 单规格模式
        if ($specType === SpecTypeEnum::SINGLE) {
            return (new static)->save(array_merge($newSkuList, [
                'goods_id' => $goodsId,
                'goods_sku_id' => 0,
                'store_id' => self::$storeId
            ]));
        } // 多规格模式
        elseif ($specType === SpecTypeEnum::MULTI) {
            // 批量写入商品sku记录
            return static::increasedFroMulti($goodsId, $newSkuList);
        }
        return false;
    }

    /**
     * 更新商品sku记录
     * @param int $goodsId
     * @param int $specType
     * @param array $skuList
     * @return array|bool|false
     */
    public static function edit(int $goodsId, int $specType = SpecTypeEnum::SINGLE, array $skuList = [])
    {
        // 删除所有的sku记录
        static::deleteAll(['goods_id' => $goodsId]);
        // 新增商品sku记录
        return static::add($goodsId, $specType, $skuList);
    }

    /**
     * 批量写入商品sku记录
     * @param int $goodsId
     * @param array $skuList
     * @return array|false
     */
    public static function increasedFroMulti(int $goodsId, array $skuList)
    {
        $dataset = [];
        foreach ($skuList as $skuItem) {
            $dataset[] = array_merge($skuItem, [
                'goods_sku_id' => $skuItem['goods_sku_id'],
                'line_price' => $skuItem['line_price'] ?: 0.00,
                'goods_sku_no' => $skuItem['goods_sku_no'] ?: '',
                'stock_num' => $skuItem['stock_num'] ?: 0,
                'goods_weight' => $skuItem['goods_weight'] ?: 0,
                'goods_props' => $skuItem['goodsProps'],
                'spec_value_ids' => $skuItem['specValueIds'],
                'goods_id' => $goodsId,
                'store_id' => self::$storeId
            ]);
        }
        return (new static)->addAll($dataset);
    }
}
