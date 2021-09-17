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

namespace app\api\service;

use app\api\model\Wxapp as WxappModel;
use app\api\service\User as UserService;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\library\wechat\WxPay;
use app\common\exception\BaseException;
use app\common\service\BaseService;

/**
 * 订单支付服务类
 * Class Payment
 * @package app\api\service
 */
class Payment extends BaseService
{
    /**
     * 构建订单支付参数
     * @param $order
     * @param $payType
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function orderPayment($order, $payType)
    {
        if ($payType == OrderPayTypeEnum::WECHAT) {
            return self::wechat(
                $order['order_id'],
                $order['order_no'],
                $order['pay_price'],
                OrderTypeEnum::ORDER
            );
        }
        return [];
    }

    /**
     * 构建微信支付
     * @param $orderId
     * @param $orderNo
     * @param $payPrice
     * @param $orderType
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function wechat(
        $orderId,
        $orderNo,
        $payPrice,
        $orderType = OrderTypeEnum::ORDER
    )
    {
        // 获取当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 获取第三方用户信息(微信)
        $oauth = UserService::getOauth($userInfo['user_id'], 'MP-WEIXIN');
        empty($oauth) && throwError('没有找到第三方用户信息oauth');
        // 统一下单API
        $WxPay = new WxPay(static::getWxConfig());
        return $WxPay->unifiedorder($orderNo, $oauth['oauth_id'], $payPrice, $orderType);
    }

    /**
     * 获取微信支付配置
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private static function getWxConfig()
    {
        return WxappModel::getWxappCache(getStoreId());
    }

}