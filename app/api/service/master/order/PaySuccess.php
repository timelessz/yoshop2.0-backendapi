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

namespace app\api\service\master\order;

use app\common\service\BaseService;

/**
 * 普通订单支付成功后的回调
 * Class PaySuccess
 * @package app\api\service\master\order
 */
class PaySuccess extends BaseService
{
    /**
     * 回调方法
     * @param $order
     * @return bool
     */
    public function onPaySuccess($order)
    {
        // 暂无业务逻辑
        return true;
    }

}