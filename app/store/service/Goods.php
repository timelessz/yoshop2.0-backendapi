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

namespace app\store\service;

use app\common\service\Goods as GoodsService;
use app\store\service\goods\Apply as GoodsApplyService;

/**
 * 商品服务类
 * Class Goods
 * @package app\store\service
 */
class Goods extends GoodsService
{
    /**
     * 验证商品是否允许删除
     * @param $goodsId
     * @return bool
     */
    public static function checkIsAllowDelete($goodsId)
    {
        return GoodsApplyService::checkIsAllowDelete($goodsId);
    }

    /**
     * 商品规格是否允许编辑
     * @param int $goodsId
     * @return bool
     */
    public static function checkSpecLocked(int $goodsId)
    {
        return GoodsApplyService::checkSpecLocked($goodsId);
    }

}
