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

// 应用公共函数库文件

use app\store\service\Auth;
use app\common\service\store\User as StoreUserService;

/**
 * 验证指定url是否有访问权限
 * @param string|array $url
 * @param bool $strict 严格模式
 * @return bool
 */
function checkPrivilege($url, $strict = true)
{
    try {
        return Auth::getInstance()->checkPrivilege($url, $strict);
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * 日期转换时间戳(不保留时间)
 * 例如: 2020-04-01 08:15:08 => 1585670400
 * @param string $date
 * @return false|int
 */
function str2time_date(string $date)
{
    return strtotime(date('Y-m-d', strtotime($date)));
}

/**
 * 格式化起止时间(为了兼容前端RangePicker组件)
 * 2020-04-01T08:15:08.891Z => 1585670400
 * @param array $times
 * @return array
 */
function between_time(array $times)
{
    foreach ($times as &$time) {
        $time = trim($time, '&quot;');
        $time = str2time_date($time);
    }
    return ['start_time' => current($times), 'end_time' => next($times)];
}
