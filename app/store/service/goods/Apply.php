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

namespace app\store\service\goods;

use app\common\service\Goods as GoodsService;

class Apply extends GoodsService
{
    /**
     * 验证商品规格属性是否锁定
     * @param int $goodsId
     * @return bool
     */
    public static function checkSpecLocked(int $goodsId)
    {
        // 这里实现业务判断
        return false;
    }

    /**
     * 验证商品是否允许删除
     * @param int $goodsId
     * @return bool
     */
    public static function checkIsAllowDelete(int $goodsId)
    {
        // 这里实现业务判断
        return true;
    }

}
