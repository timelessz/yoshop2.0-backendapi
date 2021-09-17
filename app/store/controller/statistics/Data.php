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

namespace app\store\controller\statistics;

use app\store\controller\Controller;
use app\store\service\statistics\Data as StatisticsDataService;

/**
 * 数据概况
 * Class Data
 * @package app\store\controller\statistics
 */
class Data extends Controller
{
    // 数据概况服务类
    /* @var $service StatisticsDataService */
    private $service;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function initialize()
    {
        parent::initialize();
        // 实例化数据概况服务类
        $this->service = new StatisticsDataService;
    }

    /**
     * 数据统计主页
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function data()
    {
        // 获取数据
        $data = [
            // 数据概况
            'overview' => $this->service->getSurveyData(),
            // 近七日交易走势
            'tradeTrend' => $this->service->getTransactionTrend(),
            // 商品销售榜
            'goodsRanking' => $this->service->getGoodsRanking(),
            // 用户消费榜
            'userExpendRanking' => $this->service->geUserExpendRanking(),
        ];
        return $this->renderSuccess(compact('data'));
    }

    /**
     * 数据概况API
     * @param null $startDate
     * @param null $endDate
     * @return array
     */
    public function survey($startDate = null, $endDate = null)
    {
        // 获取数据概况
        $data = $this->service->getSurveyData($startDate, $endDate);
        return $this->renderSuccess($data);
    }
}
