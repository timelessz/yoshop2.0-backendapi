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

namespace app\api\validate\order;

use think\Validate;

/**
 * 验证类：订单提交
 * Class Checkout
 * @package app\api\validate\order
 */
class Checkout extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [

        // 商品id
        'goodsId' => [
            'require',
            'number',
            'gt' => 0
        ],

        // 购买数量
        'goodsNum' => [
            'require',
            'number',
            'gt' => 0
        ],

        // 商品sku_id
        'goodsSkuId' => [
            'require',
        ],

//        // 购物车id集
//        'cartIds' => [
//            'require',
//        ],

    ];

    /**
     * 验证场景
     * @var array
     */
    protected $scene = [
        'buyNow' => ['goodsId', 'goodsNum', 'goodsSkuId'],
//        'cart' => ['cartIds'],
    ];

}
