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

use think\facade\Cache;

/**
 * 定时任务监听器
 * Class Listener
 * @package app\console\task
 */
class Task
{
    /**
     * 定时执行任务
     * @param int $storeId
     * @param string $key
     * @param int|null $expire
     * @param callable $callback
     * @return mixed
     */
    protected function setInterval(int $storeId, string $key, int $expire, callable $callback)
    {
        if (!$this->hasTaskId($storeId, $key)) {
            $this->setTaskId($storeId, $key, $expire);
            return $callback();
        }
    }

    /**
     * 获取任务ID
     * @param int $storeId
     * @param string $key
     * @return mixed
     */
    protected function hasTaskId(int $storeId, string $key)
    {
        return Cache::has("Listener:$storeId:$key");
    }

    /**
     * 设置任务ID
     * 用于实现定时任务的间隔时间, 如果任务ID存在并未过期, 则不执行任务
     * @param int $storeId
     * @param string $key
     * @param int $expire 任务ID过期时长(单位:秒)
     * @return bool
     */
    protected function setTaskId(int $storeId, string $key, int $expire = 60)
    {
        return Cache::set("Listener:$storeId:$key", true, $expire);
    }

}