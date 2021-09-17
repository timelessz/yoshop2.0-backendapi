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

namespace app\api\model;

use app\common\model\GoodsSku as GoodsSkuModel;

/**
 * 商品规格模型
 * Class GoodsSku
 * @package app\api\model
 */
class GoodsSku extends GoodsSkuModel
{
    /**
     * 规格图片
     * @return \think\model\relation\HasOne
     */
    public function image()
    {
        return parent::image()->bind(['image_url' => 'preview_url']);
    }

    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'store_id',
        'create_time',
        'update_time'
    ];

}
