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

namespace app\api\controller;

use app\common\library\wechat\WxPay;
use app\common\exception\BaseException;

/**
 * 支付成功异步通知接口
 * Class Notify
 * @package app\api\controller
 */
class Notify
{
    /**
     * 支付成功异步通知(微信小程序-微信支付)
     * @throws BaseException
     */
    public function wxpay()
    {
        // 微信支付组件：验证异步通知
        $WxPay = new WxPay();
        return $WxPay->notify();
    }
}
