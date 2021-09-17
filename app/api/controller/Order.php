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

use app\api\model\Order as OrderModel;
use app\api\model\Setting as SettingModel;
use app\store\model\Express as ExpressModel;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\exception\BaseException;

/**
 * 我的订单控制器
 * Class Order
 * @package app\api\controller
 */
class Order extends Controller
{
    /**
     * 获取当前用户待处理的订单数量
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function todoCounts()
    {
        $model = new OrderModel;
        $counts = $model->getTodoCounts();
        return $this->renderSuccess(compact('counts'));
    }

    /**
     * 我的订单列表
     * @param string $dataType 订单类型 (all全部 payment待付款 received待发货 deliver待收货 comment待评价)
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DbException
     */
    public function list(string $dataType)
    {
        $model = new OrderModel;
        $list = $model->getList($dataType);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 订单详情信息
     * @param int $orderId 订单ID
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function detail(int $orderId)
    {
        // 订单详情
        $model = OrderModel::getUserOrderDetail($orderId);
        return $this->renderSuccess([
            'order' => $model,  // 订单详情
            'setting' => [
                // 积分名称
                'points_name' => SettingModel::getPointsName(),
            ],
        ]);
    }

    /**
     * 获取物流信息
     * @param int $orderId 订单ID
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function express(int $orderId)
    {
        // 订单信息
        $order = OrderModel::getDetail($orderId);
        if (!$order['express_no']) {
            return $this->renderError('没有物流信息');
        }
        // 获取物流信息
        $model = ExpressModel::detail($order['express_id']);
        $express = $model->dynamic($model['express_name'], $model['kuaidi100_code'], $order['express_no']);
        if ($express === false) {
            return $this->renderError($model->getError());
        }
        return $this->renderSuccess(compact('express'));
    }

    /**
     * 取消订单
     * @param int $orderId
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function cancel(int $orderId)
    {
        $model = OrderModel::getDetail($orderId);
        if ($model->cancel()) {
            return $this->renderSuccess('订单取消成功');
        }
        return $this->renderError($model->getError() ?: '订单取消失败');
    }

    /**
     * 确认收货
     * @param int $orderId
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function receipt(int $orderId)
    {
        $model = OrderModel::getDetail($orderId);
        if ($model->receipt()) {
            return $this->renderSuccess('确认收货成功');
        }
        return $this->renderError($model->getError());
    }

    /**
     * 立即支付
     * @param int $orderId 订单ID
     * @param int $payType 支付方式
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function pay(int $orderId, int $payType = OrderPayTypeEnum::WECHAT)
    {
        // 获取订单详情
        $model = OrderModel::getUserOrderDetail($orderId);
        // 订单支付事件
        if (!$model->onPay($payType)) {
            return $this->renderError($model->getError() ?: '订单支付失败');
        }
        // 构建微信支付请求
        $payment = $model->onOrderPayment($model, $payType);
        // 支付状态提醒
        return $this->renderSuccess([
            'order_id' => $model['order_id'],   // 订单id
            'pay_type' => $payType,             // 支付方式
            'payment' => $payment               // 微信支付参数
        ]);
    }

}
