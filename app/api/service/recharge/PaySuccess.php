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

namespace app\api\service\recharge;

use app\common\service\BaseService;
use app\api\model\User as UserModel;
use app\api\model\recharge\Order as OrderModel;
use app\api\model\user\BalanceLog as BalanceLogModel;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\enum\recharge\order\PayStatus as PayStatusEnum;

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
     * @param int $payType 支付类型
     * @param array $payData 支付回调数据
     * @return bool
     */
    public function onPaySuccess(int $payType, $payData)
    {
        return $this->model->transaction(function () use ($payData) {
            // 更新订单状态
            $this->model->save([
                'pay_status' => PayStatusEnum::SUCCESS,
                'pay_time' => time(),
                'transaction_id' => $payData['transaction_id']
            ]);
            // 累积用户余额
            UserModel::setIncBalance((int)$this->user['user_id'], (float)$this->model['actual_money']);
            // 用户余额变动明细
            BalanceLogModel::add(SceneEnum::RECHARGE, [
                'user_id' => $this->user['user_id'],
                'money' => $this->model['actual_money'],
                'store_id' => $this->getStoreId(),
            ], ['order_no' => $this->model['order_no']]);
            return true;
        });
    }

}