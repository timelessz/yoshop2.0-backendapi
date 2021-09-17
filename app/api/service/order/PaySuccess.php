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

namespace app\api\service\order;

use app\common\service\BaseService;
use app\api\model\User as UserModel;
use app\api\model\Order as OrderModel;
use app\api\model\user\BalanceLog as BalanceLogModel;
use app\common\service\goods\source\Factory as StockFactory;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use think\facade\Event;

/**
 * 订单支付成功服务类
 * Class PaySuccess
 * @package app\api\service\order
 */
class PaySuccess extends BaseService
{
    // 订单模型
    public $model;

    // 当前用户信息
    private $user;

    /**
     * 构造函数
     * PaySuccess constructor.
     * @param $orderNo
     */
    public function __construct($orderNo)
    {
        parent::__construct();
        // 实例化订单模型
        $this->model = OrderModel::getPayDetail($orderNo);
        // 获取用户信息
        $this->user = UserModel::detail($this->model['user_id']);
    }

    /**
     * 获取订单详情
     * @return OrderModel|null
     */
    public function getOrderInfo()
    {
        return $this->model;
    }

    /**
     * 订单支付成功业务处理
     * @param $payType
     * @param array $payData
     * @return bool
     */
    public function onPaySuccess($payType, $payData = [])
    {
        if (empty($this->model)) {
            $this->error = '未找到该订单信息';
            return false;
        }
        // 更新付款状态
        $status = $this->updatePayStatus($payType, $payData);
        // 订单支付成功事件
        if ($status == true) {
            Event::trigger('OrderPaySuccess', ['order' => $this->model, 'orderType' => OrderTypeEnum::ORDER]);
        }
        return $status;
    }

    /**
     * 更新付款状态
     * @param $payType
     * @param array $payData
     * @return bool
     */
    private function updatePayStatus($payType, $payData = [])
    {
        // 验证余额支付时用户余额是否满足
        if ($payType == OrderPayTypeEnum::BALANCE) {
            if ($this->user['balance'] < $this->model['pay_price']) {
                $this->error = '用户余额不足，无法使用余额支付';
                return false;
            }
        }
        // 事务处理
        $this->model->transaction(function () use ($payType, $payData) {
            // 更新订单状态
            $this->updateOrderInfo($payType, $payData);
            // 累积用户总消费金额
            UserModel::setIncPayMoney($this->user['user_id'], (float)$this->model['pay_price']);
            // 记录订单支付信息
            $this->updatePayInfo($payType);
        });
        return true;
    }

    /**
     * 更新订单记录
     * @param int $payType
     * @param array $payData
     * @return false|int
     * @throws \Exception
     */
    private function updateOrderInfo(int $payType, array $payData)
    {
        // 更新商品库存、销量
        StockFactory::getFactory($this->model['order_source'])->updateStockSales($this->model['goods']);
        // 整理订单信息
        $order = [
            'pay_type' => $payType,
            'pay_status' => 20,
            'pay_time' => time()
        ];
        if ($payType == OrderPayTypeEnum::WECHAT) {
            $order['transaction_id'] = $payData['transaction_id'];
        }
        // 更新订单状态
        return $this->model->save($order);
    }

    /**
     * 记录订单支付信息
     * @param int $payType
     */
    private function updatePayInfo(int $payType)
    {
        // 余额支付
        if ($payType == OrderPayTypeEnum::BALANCE) {
            // 更新用户余额
            UserModel::setDecBalance((int)$this->user['user_id'], (float)$this->model['pay_price']);
            // 新增余额变动记录
            BalanceLogModel::add(SceneEnum::CONSUME, [
                'user_id' => (int)$this->user['user_id'],
                'money' => -$this->model['pay_price'],
            ], ['order_no' => $this->model['order_no']]);
        }
        // 微信支付
        if ($payType == OrderPayTypeEnum::WECHAT) {

        }
    }

}