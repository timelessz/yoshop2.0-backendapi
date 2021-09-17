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
use app\store\model\Order as OrderModel;

/**
 * 订单操作控制器
 * Class Operate
 * @package app\store\controller\order
 */
class Event extends Controller
{
    /**
     * 确认发货
     * @param int $orderId
     * @return array
     * @throws \Exception
     */
    public function delivery(int $orderId)
    {
        // 订单详情
        $model = OrderModel::detail($orderId);
        if ($model->delivery($this->postForm())) {
            return $this->renderSuccess('发货成功');
        }
        return $this->renderError($model->getError() ?: '发货失败');
    }

    /**
     * 修改订单价格
     * @param int $orderId
     * @return array
     */
    public function updatePrice(int $orderId)
    {
        // 订单详情
        $model = OrderModel::detail($orderId);
        if ($model->updatePrice($this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

    /**
     * 审核：用户取消订单
     * @param $orderId
     * @return array|bool
     */
    public function confirmCancel($orderId)
    {
        // 订单详情
        $model = OrderModel::detail($orderId);
        if ($model->confirmCancel($this->postForm())) {
            return $this->renderSuccess('操作成功');
        }
        return $this->renderError($model->getError() ?: '操作失败');
    }

}
