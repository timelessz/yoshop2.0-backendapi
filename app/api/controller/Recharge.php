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

namespace app\api\controller;

use app\api\model\recharge\Order as OrderModel;
use app\api\service\Payment as PaymentService;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\exception\BaseException;

/**
 * 用户充值管理
 * Class Recharge
 * @package app\api\controller
 */
class Recharge extends Controller
{
    /**
     * 确认充值
     * @param int|null $planId 方案ID
     * @param float|string|null $customMoney 自定义金额
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function submit(int $planId = null, $customMoney = null)
    {
        if (getPlatform() !== 'MP-WEIXIN') {
            return $this->renderError('很抱歉，余额充值暂时仅支持微信小程序端');
        }
        // 生成充值订单
        $model = new OrderModel;
        if (!$model->createOrder($planId, (float)$customMoney)) {
            return $this->renderError($model->getError() ?: '充值失败');
        }
        // 构建微信支付
        $payment = PaymentService::wechat(
            $model['order_id'],
            $model['order_no'],
            $model['pay_price'],
            OrderTypeEnum::RECHARGE
        );
        // 充值状态提醒
        $message = ['success' => '充值成功', 'error' => '订单未支付'];
        return $this->renderSuccess(compact('payment', 'message'));
    }

}