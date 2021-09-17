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

namespace app\console\library;

/**
 * 工具类
 * Class Tools
 * @package app\console\library
 */
class Tools
{
    /**
     * 为定时任务写日志
     * @param string $taskKey
     * @param string $method
     * @param array $param
     */
    static function taskLogs(string $taskKey, string $method, array $param = [])
    {
        return log_record(['name' => '定时任务', 'Task-Key' => $taskKey, 'method' => $method, 'param' => $param]);
    }

}