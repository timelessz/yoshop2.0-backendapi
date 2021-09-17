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

namespace app\console\model;

use app\common\model\Store as StoreModel;

/**
 * 商家记录表模型
 * Class Store
 * @package app\admin\model
 */
class Store extends StoreModel
{
    /**
     * 获取商城ID集
     * @param bool $isRecycle
     * @return array
     */
    public static function getStoreIds(bool $isRecycle = false)
    {
        $static = new static;
        return $static->where('is_recycle', '=', (int)$isRecycle)
            ->where('is_delete', '=', 0)
            ->order(['create_time' => 'desc', $static->getPk()])
            ->column('store_id');
    }
}