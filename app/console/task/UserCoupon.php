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

use app\console\service\UserCoupon as UserCouponService;

/**
 * 定时任务：设置优惠券过期状态
 * Class UserCoupon
 * @package app\console\task
 */
class UserCoupon extends Task
{
    // 当前任务唯一标识
    private $taskKey = 'UserCoupon';

    // 任务执行间隔时长 (单位:秒)
    protected $taskExpire = 60 * 30;

    // 当前商城ID
    private $storeId;

    /**
     * 任务处理
     * @param array $param
     */
    public function handle(array $param)
    {
        ['storeId' => $this->storeId] = $param;
        $this->setInterval($this->storeId, $this->taskKey, $this->taskExpire, function () {
            // echo $this->taskKey . PHP_EOL;
            // 设置优惠券过期状态
            $this->setExpired();
        });
    }

    /**
     * 设置优惠券过期状态
     */
    private function setExpired()
    {
        $service = new UserCouponService;
        $service->setExpired($this->storeId);
    }

}