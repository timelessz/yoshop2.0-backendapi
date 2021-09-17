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

namespace app\store\service\statistics;

use app\common\service\BaseService;
use app\store\service\statistics\data\Survey;
use app\store\service\statistics\data\Trade7days;
use app\store\service\statistics\data\GoodsRanking;
use app\store\service\statistics\data\UserExpendRanking;

/**
 * 数据概况服务类
 * Class Data
 * @package app\store\service\statistics
 */
class Data extends BaseService
{
    /**
     * 获取数据概况
     * @param null $startDate
     * @param null $endDate
     * @return array
     */
    public function getSurveyData($startDate = null, $endDate = null)
    {
        return (new Survey)->getSurveyData($startDate, $endDate);
    }

    /**
     * 近7日走势
     * @return array
     */
    public function getTransactionTrend()
    {
        return (new Trade7days)->getTransactionTrend();
    }

    /**
     * 商品销售榜
     * @return mixed
     */
    public function getGoodsRanking()
    {
        return (new GoodsRanking)->getGoodsRanking();
    }

    /**
     * 用户消费榜
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function geUserExpendRanking()
    {
        return (new UserExpendRanking)->getUserExpendRanking();
    }

}