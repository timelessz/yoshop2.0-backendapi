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

use app\common\model\GoodsImage as GoodsImageModel;

/**
 * 商品图片模型
 * Class GoodsImage
 * @package app\store\model
 */
class GoodsImage extends GoodsImageModel
{
    /**
     * 批量写入商品图片记录
     * @param int $goodsId
     * @param array $imageIds
     * @return array|false
     */
    public static function increased(int $goodsId, array $imageIds)
    {
        $dataset = [];
        foreach ($imageIds as $imageId) {
            $dataset[] = [
                'image_id' => $imageId,
                'goods_id' => $goodsId,
                'store_id' => self::$storeId
            ];
        }
        return (new static)->addAll($dataset);
    }

    /**
     * 更新关系记录
     * @param $goodsId
     * @param array $imageIds 新的图片集
     * @return array|false
     * @throws \Exception
     */
    public static function updates(int $goodsId, $imageIds)
    {
        // 删除所有的sku记录
        static::deleteAll(['goods_id' => $goodsId]);
        // 批量写入商品图片记录
        return static::increased($goodsId, $imageIds);
    }

}
