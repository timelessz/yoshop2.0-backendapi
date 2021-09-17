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

use app\common\model\UserCoupon as UserCouponModel;

/**
 * 用户优惠券模型
 * Class UserCoupon
 * @package app\store\model
 */
class UserCoupon extends UserCouponModel
{
    /**
     * 获取列表记录
     * @param array $param
     * @return mixed
     */
    public function getList(array $param = [])
    {
        // 设置查询条件
        $filter = $this->getFilter($param);
        // 获取列表数据
        return $this->with(['user.avatar'])
            ->alias('m')
            ->field('m.*')
            ->join('user', 'user.user_id = m.user_id')
            ->where($filter)
            ->order(['m.create_time' => 'desc', $this->getPk()])
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
            'userId' => 0,          // 会员ID
            'couponName' => '',     // 优惠券名称
            'nickName' => '',       // 会员昵称
            'betweenTime' => [],    // 起止时间
        ]);
        // 检索查询条件
        $filter = [];
        // 用户ID
        $params['userId'] > 0 && $filter[] = ['m.user_id', '=', $params['userId']];
        // 会员昵称
        !empty($params['couponName']) && $filter[] = ['m.name', 'like', "%{$params['couponName']}%"];
        // 会员昵称
        !empty($params['nickName']) && $filter[] = ['user.nick_name', 'like', "%{$params['nickName']}%"];
        // 起止时间
        if (!empty($params['betweenTime'])) {
            $times = between_time($params['betweenTime']);
            $filter[] = ['m.create_time', '>=', $times['start_time']];
            $filter[] = ['m.create_time', '<', $times['end_time'] + 86400];
        }
        return $filter;
    }

}
