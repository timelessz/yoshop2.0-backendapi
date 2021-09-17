<?php
declare (strict_types=1);

namespace app;

use think\Service;
use think\facade\Log;
use think\facade\Request;

/**
 * 应用服务类
 */
class AppService extends Service
{
    // 服务注册
    public function register()
    {

    }

    // 服务启动
    public function boot()
    {
//        // 记录访问日志
//        if (!is_debug()) {
//            Log::record('[ URL ] ' . print_r(Request::baseUrl(), true), 'begin');
//            Log::record('[ HEADER ] ' . print_r(Request::header(), true), 'begin');
//            Log::record('[ PARAM ] ' . print_r(Request::param(), true), 'begin');
//        }
    }
}
