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

namespace app\store\model\recharge;

use app\common\model\recharge\Order as OrderModel;

/**
 * 用户充值订单模型
 * Class Order
 * @package app\store\model\recharge
 */
class Order extends OrderModel
{
    /**
     * 获取订单列表
     * @param array $param
     * @return \think\Paginator
     */
    public function getList(array $param = [])
    {
        // 设置查询条件
        $filter = $this->getFilter($param);
        // 获取列表数据
        return $this->with(['user.avatar', 'order_plan'])
            ->alias('order')
            ->field('order.*')
            ->where($filter)
            ->join('user', 'user.user_id = order.user_id')
            ->order(['order.create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 设置查询条件
     * @param array $param
     * @return array
     */
    private function getFilter(array $param): array
    {
        // 设置默认的检索数据
        $params = $this->setQueryDefaultValue($param, [
            'user_id' => 0,          // 用户ID
            'search' => '',          // 查询内容
            'recharge_type' => 0,    // 充值方式
            'pay_status' => 0,       // 支付状态
            'betweenTime' => []     // 起止时间
        ]);
        // 检索查询条件
        $filter = [];
        // 用户ID
        $params['user_id'] > 0 && $filter[] = ['order.user_id', '=', $params['user_id']];
        // 用户昵称/订单号
        !empty($params['search']) && $filter[] = ['order.order_no|user.nick_name', 'like', "%{$params['search']}%"];
        // 充值方式
        $params['recharge_type'] > 0 && $filter[] = ['order.recharge_type', '=', (int)$params['recharge_type']];
        // 支付状态
        $params['pay_status'] > 0 && $filter[] = ['order.pay_status', '=', (int)$params['pay_status']];
        // 起止时间
        if (!empty($params['betweenTime'])) {
            $times = between_time($params['betweenTime']);
            $filter[] = ['order.pay_time', '>=', $times['start_time']];
            $filter[] = ['order.pay_time', '<', $times['end_time'] + 86400];
        }
        return $filter;
    }

}
