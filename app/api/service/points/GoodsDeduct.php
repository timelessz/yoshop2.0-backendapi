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

namespace app\api\service\points;

use app\common\library\helper;
use app\api\model\Setting as SettingModel;
use app\common\service\BaseService;

/**
 * 订单积分抵扣服务类
 * Class GoodsDeduct
 * @package app\api\service\points
 */
class GoodsDeduct extends BaseService
{
    private $goodsList;

    /**
     * 构造方法
     * GoodsDeduct constructor.
     * @param $goodsList
     */
    public function __construct($goodsList)
    {
        parent::__construct();
        $this->goodsList = $goodsList;
    }

    public function setGoodsPoints($maxPointsNumCount, $actualPointsNum)
    {
        // 计算实际积分抵扣数量
        $this->setGoodsListPointsNum($maxPointsNumCount, $actualPointsNum);
        // 总抵扣数量
        $totalPointsNum = helper::getArrayColumnSum($this->goodsList, 'points_num');
        // 填充余数
        $this->setGoodsListPointsNumFill($actualPointsNum, $totalPointsNum);
        $this->setGoodsListPointsNumDiff($actualPointsNum, $totalPointsNum);
        // 计算实际积分抵扣金额
        $this->setGoodsListPointsMoney();
        return true;
    }

    /**
     * 计算实际积分抵扣数量
     * @param $maxPointsNumCount
     * @param $actualPointsNum
     */
    private function setGoodsListPointsNum($maxPointsNumCount, $actualPointsNum)
    {
        foreach ($this->goodsList as &$goods) {
            if (!$goods['is_points_discount']) continue;
            $goods['points_num'] = floor($goods['max_points_num'] / $maxPointsNumCount * $actualPointsNum);
        }
    }

    /**
     * 计算实际积分抵扣金额
     */
    private function setGoodsListPointsMoney()
    {
        $setting = SettingModel::getItem('points');
        foreach ($this->goodsList as &$goods) {
            if (!$goods['is_points_discount']) continue;
            $goods['points_money'] = helper::bcmul($goods['points_num'], $setting['discount']['discount_ratio']);
        }
    }

    private function setGoodsListPointsNumFill($actualPointsNum, $totalPointsNum)
    {
        if ($totalPointsNum === 0) {
            $temReducedMoney = $actualPointsNum;
            foreach ($this->goodsList as &$goods) {
                if (!$goods['is_points_discount']) continue;
                if ($temReducedMoney === 0) break;
                $goods['points_num'] = 1;
                $temReducedMoney--;
            }
        }
        return true;
    }

    private function setGoodsListPointsNumDiff($actualPointsNum, $totalPointsNum)
    {
        $tempDiff = $actualPointsNum - $totalPointsNum;
        foreach ($this->goodsList as &$goods) {
            if (!$goods['is_points_discount']) continue;
            if ($tempDiff < 1) break;
            $goods['points_num'] = $goods['points_num'] + 1;
            $tempDiff--;
        }
        return true;
    }

}