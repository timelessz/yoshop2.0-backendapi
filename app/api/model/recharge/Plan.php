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

namespace app\api\model\recharge;

use app\common\model\recharge\Plan as PlanModel;

/**
 * 用户充值订单模型
 * Class Plan
 * @package app\api\model\recharge
 */
class Plan extends PlanModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'sort',
        'is_delete',
        'store_id',
        'create_time',
        'update_time',
    ];

    /**
     * 获取器：充值金额
     * @param $value
     * @return int
     */
    public function getMoneyAttr($value)
    {
        return ($value == $intValue = (int)$value) ? $intValue : $value;
    }

    /**
     * 获取器：赠送金额
     * @param $value
     * @return int
     */
    public function getGiftMoneyAttr($value)
    {
        return ($value == $intValue = (int)$value) ? $intValue : $value;
    }

    /**
     * 获取可用的充值套餐列表
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList()
    {
        // 获取列表数据
        return $this->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'money' => 'desc', 'create_time' => 'desc'])
            ->select();
    }

    /**
     * 根据自定义充值金额匹配满足的套餐
     * @param $payPrice
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getMatchPlan($payPrice)
    {
        return (new static)->where('money', '<=', $payPrice)
            ->where('is_delete', '=', 0)
            ->order(['money' => 'desc'])
            ->find();
    }

}