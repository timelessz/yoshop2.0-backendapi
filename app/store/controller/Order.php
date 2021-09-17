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

namespace app\store\controller;

use app\store\model\Order as OrderModel;

/**
 * 订单管理
 * Class Order
 * @package app\store\controller
 */
class Order extends Controller
{
    /**
     * 订单列表
     * @param string $dataType
     * @return array
     */
    public function list(string $dataType)
    {
        // 订单列表
        $model = new OrderModel;
        $list = $model->getList($dataType, $this->request->param());
        return $this->renderSuccess(compact('dataType', 'list'));
    }

    /**
     * 订单详情
     * @param int $orderId
     * @return array
     */
    public function detail(int $orderId)
    {
        // 订单详情
        $model = new OrderModel;
        if (!$detail = $model->getDetail($orderId)) {
            return $this->renderError('未找到该订单记录');
        }
        return $this->renderSuccess(compact('detail'));
    }

}
