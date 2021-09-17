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

namespace app\console\task;

use think\facade\Event;
use app\console\model\Store as StoreModel;

/**
 * 商城定时任务
 * Class StoreTask
 * @package app\console\task
 */
class Store extends Task
{
    /**
     * 任务处理
     */
    public function handle()
    {
        echo 'StoreTask' . PHP_EOL;
        // 遍历商城列表并执行定时任务
        $storeIds = StoreModel::getStoreIds();
        foreach ($storeIds as $storeId) {
            // echo $storeId . PHP_EOL;
            // 定时任务：商城订单
            Event::trigger('Order', ['storeId' => $storeId]);
            // 定时任务：用户优惠券
            Event::trigger('UserCoupon', ['storeId' => $storeId]);
            // 定时任务：会员等级
            Event::trigger('UserGrade', ['storeId' => $storeId]);
        }
    }
}