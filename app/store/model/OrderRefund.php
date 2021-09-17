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

namespace app\store\model;

use app\store\model\User as UserModel;
use app\common\model\OrderRefund as OrderRefundModel;
use app\common\enum\order\refund\RefundType as RefundTypeEnum;
use app\common\enum\order\refund\AuditStatus as AuditStatusEnum;
use app\common\enum\order\refund\RefundStatus as RefundStatusEnum;
use app\common\service\Message as MessageService;
use app\common\service\order\Refund as RefundService;

/**
 * 售后单模型
 * Class OrderRefund
 * @package app\api\model
 */
class OrderRefund extends OrderRefundModel
{
    /**
     * 获取售后单列表
     * @param array $param
     * @return mixed
     */
    public function getList(array $param = [])
    {
        // 检索查询条件
        $filter = $this->getFilter($param);
        // 获取列表数据
        return $this->alias('refund')
            ->field('refund.*, order.order_no')
            ->with(['orderGoods.image', 'orderData', 'user.avatar'])
            ->join('order', 'order.order_id = refund.order_id')
            ->join('user', 'user.user_id = order.user_id')
            ->where($filter)
            ->order(['refund.create_time' => 'desc', 'refund.' . $this->getPk()])
            ->paginate(10);
    }

    /**
     * 获取售后单详情
     * @param int $orderRefundId
     * @return OrderRefund|false|null
     */
    public function getDetail(int $orderRefundId)
    {
        return static::detail($orderRefundId, [
            'orderData', 'images.file', 'orderGoods.image', 'express', 'address', 'user'
        ]) ?: false;
    }

    /**
     * 检索查询条件
     * @param array $param
     * @return array
     */
    private function getFilter(array $param = []): array
    {
        // 默认查询条件
        $params = $this->setQueryDefaultValue($param, [
            'searchType' => '',     // 关键词类型 (10订单号 20会员昵称 30会员ID)
            'searchValue' => '',    // 关键词内容
            'refundType' => -1,      // 售后类型
            'refundStatus' => -1,    // 售后单状态
            'betweenTime' => [],    // 申请时间
        ]);
        // 检查查询条件
        $filter = [];
        // 关键词
        if (!empty($params['searchValue'])) {
            $searchWhere = [
                10 => ['order.order_no', 'like', "%{$params['searchValue']}%"],
                20 => ['user.nick_name', 'like', "%{$params['searchValue']}%"],
                30 => ['order.user_id', '=', (int)$params['searchValue']]
            ];
            array_key_exists($params['searchType'], $searchWhere) && $filter[] = $searchWhere[$params['searchType']];
        }
        // 起止时间
        if (!empty($params['betweenTime'])) {
            $times = between_time($params['betweenTime']);
            $filter[] = ['refund.create_time', '>=', $times['start_time']];
            $filter[] = ['refund.create_time', '<', $times['end_time'] + 86400];
        }
        // 售后类型
        $params['refundType'] > -1 && $filter[] = ['refund.type', '=', (int)$params['refundType']];
        // 处理状态
        $params['refundStatus'] > -1 && $filter[] = ['refund.status', '=', (int)$params['refundStatus']];
        return $filter;
    }

    /**
     * 商家审核
     * @param array $data
     * @return bool
     */
    public function audit(array $data): bool
    {
        if ($data['audit_status'] == AuditStatusEnum::REJECTED && empty($data['refuse_desc'])) {
            $this->error = '请输入拒绝原因';
            return false;
        }
        if ($data['audit_status'] == AuditStatusEnum::REVIEWED && empty($data['address_id'])) {
            $this->error = '请选择退货地址';
            return false;
        }
        $this->transaction(function () use ($data) {
            // 拒绝申请, 标记售后单状态为已拒绝
            $data['audit_status'] == AuditStatusEnum::REJECTED && $data['status'] = RefundStatusEnum::REJECTED;
            // 同意换货申请, 标记售后单状态为已完成
            $data['audit_status'] == AuditStatusEnum::REVIEWED && $this['type'] == RefundTypeEnum::EXCHANGE && $data['status'] = RefundStatusEnum::COMPLETED;
            // 更新退款单状态
            $this->save($data);
            // 同意售后申请, 记录退货地址
            if ($data['audit_status'] == AuditStatusEnum::REVIEWED) {
                (new OrderRefundAddress)->add((int)$this['order_refund_id'], (int)$data['address_id']);
            }
            // 订单详情
            $order = Order::detail($this['order_id']);
            // 发送消息通知
            MessageService::send('order.refund', [
                'refund' => $this,                  // 退款单信息
                'order_no' => $order['order_no']    // 订单信息
            ], $this['store_id']);
        });
        return true;
    }

    /**
     * 确认收货并退款
     * @param array $data
     * @return bool
     */
    public function receipt(array $data): bool
    {
        // 订单详情
        $order = Order::detail($this['order_id']);
        if ($data['refund_money'] > min($order['pay_price'], $this['orderGoods']['total_pay_price'])) {
            $this->error = '退款金额不能大于商品实付款金额';
            return false;
        }
        // 事务处理
        $this->transaction(function () use ($order, $data) {
            // 更新售后单状态
            $this->save([
                'refund_money' => $data['refund_money'],
                'is_receipt' => 1,
                'status' => RefundStatusEnum::COMPLETED
            ]);
            // 消减用户的实际消费金额
            // 条件：判断订单是否已结算
            if ($order['is_settled'] == true) {
                (new UserModel)->setDecUserExpend($order['user_id'], $data['refund_money']);
            }
            // 执行原路退款
            (new RefundService)->execute($order, $data['refund_money']);
            // 发送消息通知
            MessageService::send('order.refund', [
                'refund' => $this,                  // 退款单信息
                'order_no' => $order['order_no'],   // 订单信息
            ], $this['store_id']);
        });
        return true;
    }

    /**
     * 获取待处理售后单数量
     * @return int
     */
    public function getRefundTotal()
    {
        return $this->where('status', '=', RefundStatusEnum::NORMAL)->count();
    }

}
