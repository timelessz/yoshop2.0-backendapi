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

namespace app\common\model;

/**
 * 模型类：购物车
 * Class Cart
 * @package app\common\model
 */
class Cart extends BaseModel
{
    // 定义表名
    protected $name = 'cart';

    // 定义主键
    protected $pk = 'id';

    /**
     * 详情记录
     * @param int $userId 用户ID
     * @param int $goodsId 商品ID
     * @param string $goodsSkuId 商品sku唯一标识
     * @return array|static|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail(int $userId, int $goodsId, string $goodsSkuId)
    {
        return (new static)->where('user_id', '=', $userId)
            ->where('goods_id', '=', $goodsId)
            ->where('goods_sku_id', '=', $goodsSkuId)
            ->where('is_delete', '=', 0)
            ->find();
    }

}