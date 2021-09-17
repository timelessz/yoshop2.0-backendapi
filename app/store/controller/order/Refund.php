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

namespace app\store\controller\order;

use app\store\controller\Controller;
use app\store\model\OrderRefund as OrderRefundModel;

/**
 * 售后管理
 * Class Refund
 * @package app\store\controller\order
 */
class Refund extends Controller
{
    /**
     * 售后单列表
     * @return array
     */
    public function list()
    {
        $model = new OrderRefundModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 售后单详情
     * @param int $orderRefundId
     * @return array
     */
    public function detail(int $orderRefundId)
    {
        // 售后单详情
        $model = new OrderRefundModel;
        if (!$detail = $model->getDetail($orderRefundId)) {
            return $this->renderError('未找到该售后单记录');
        }
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 商家审核
     * @param int $orderRefundId
     * @return array|bool
     */
    public function audit(int $orderRefundId)
    {
        // 售后单详情
        $model = OrderRefundModel::detail($orderRefundId);
        // 确认审核
        if ($model->audit($this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 确认收货并退款
     * @param int $orderRefundId
     * @return array|bool
     */
    public function receipt(int $orderRefundId)
    {
        // 售后单详情
        $model = OrderRefundModel::detail($orderRefundId);
        // 确认收货并退款
        if ($model->receipt($this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

}