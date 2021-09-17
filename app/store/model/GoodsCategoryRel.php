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

use app\common\model\GoodsCategoryRel as GoodsCategoryRelModel;

/**
 * 商品与分类关系模型
 * Class GoodsCategoryRel
 * @package app\store\model
 */
class GoodsCategoryRel extends GoodsCategoryRelModel
{
    /**
     * 根据分类ID获取记录总数量
     * @param int $categoryId
     * @return int
     */
    public static function getCountByCategoryId(int $categoryId)
    {
        return (new static)->alias('m')
            ->join('goods', 'goods.goods_id = m.goods_id')
            ->where('m.category_id', '=', $categoryId)
            ->where('goods.is_delete', '=', 0)
            ->count();
    }

    /**
     * 获取商品分类ID集
     * @param int $goodsId
     * @return array
     */
    public static function getCategoryIds(int $goodsId)
    {
        return (new static)->where('goods_id', '=', $goodsId)->column('category_id');
    }

    /**
     * 批量写入商品分类记录
     * @param int $goodsId
     * @param array $categoryIds
     * @return array|false
     */
    public static function increased(int $goodsId, array $categoryIds)
    {
        $dataset = [];
        foreach ($categoryIds as $categoryId) {
            $dataset[] = [
                'category_id' => $categoryId,
                'goods_id' => $goodsId,
                'store_id' => self::$storeId
            ];
        }
        return (new static)->addAll($dataset);
    }

    /**
     * 更新关系记录
     * @param $goodsId
     * @param array $categoryIds 新的分类集
     * @return array|false
     * @throws \Exception
     */
    public static function updates(int $goodsId, $categoryIds)
    {
        // 已分配的分类集
        $assignCategoryIds = self::getCategoryIdsByGoodsId($goodsId);
        // 找出删除的分类
        $deleteCategoryIds = array_diff($assignCategoryIds, $categoryIds);
        if (!empty($deleteCategoryIds)) {
            static::deleteAll([
                ['goods_id', '=', $goodsId],
                ['category_id', 'in', $deleteCategoryIds]
            ]);
        }
        // 找出添加的分类
        $newCategoryIds = array_diff($categoryIds, $assignCategoryIds);
        $dataset = [];
        foreach ($newCategoryIds as $categoryId) {
            $dataset[] = [
                'goods_id' => $goodsId,
                'category_id' => $categoryId,
                'store_id' => self::$storeId,
            ];
        }
        return (new static)->addAll($dataset);
    }

    /**
     * 获取指定商品的所有分类id
     * @param int $goodsId
     * @return array
     */
    public static function getCategoryIdsByGoodsId(int $goodsId)
    {
        return (new static)->where('goods_id', '=', $goodsId)->column('category_id');
    }

}
