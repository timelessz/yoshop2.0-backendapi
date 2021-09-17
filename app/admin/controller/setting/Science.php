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

namespace app\admin\controller\setting;

use app\admin\controller\Controller;
use app\common\library\helper;

/**
 * 环境检测
 * Class Science
 * @package app\admin\controller\setting
 */
class Science extends Controller
{
    /**
     * 环境检测
     * @return array
     */
    public function info()
    {
        return $this->renderSuccess([
            'scienceInfo' => [
                'server' => $this->server(),        // 服务器信息
                'phpinfo' => $this->phpinfo(),      // PHP环境要求
                'writeable' => $this->writeable(),  // 目录权限监测
            ]
        ]);
    }

    /**
     * 服务器信息
     * @return array
     */
    private function server()
    {
        return [
            [
                'name' => '服务器操作系统',
                'key' => 'system',
                'value' => PHP_OS,
                'status' => PHP_SHLIB_SUFFIX === 'dll' ? 'warning' : 'normal',
                'remark' => '建议使用 Linux 系统以提升程序性能'
            ],
            [
                'name' => 'Web服务器环境',
                'key' => 'webserver',
                'value' => $this->request->server('SERVER_SOFTWARE'),
                'status' => PHP_SAPI === 'isapi' ? 'warning' : 'normal',
                'remark' => '建议使用 Apache 或 Nginx 以提升程序性能'
            ],
            [
                'name' => 'PHP版本',
                'key' => 'php',
                'value' => PHP_VERSION,
                'status' => version_compare(PHP_VERSION, '7.1.0') === -1 ? 'danger' : 'normal',
                'remark' => 'PHP版本必须为 7.1.0 以上'
            ],
            [
                'name' => '文件上传限制',
                'key' => 'upload_max',
                'value' => @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'unknow',
                'status' => 'normal',
                'remark' => ''
            ],
            [
                'name' => '程序运行目录',
                'key' => 'web_path',
                'value' => str_replace('\\', '/', web_path()),
                'status' => 'normal',
                'remark' => ''
            ],
        ];
    }

    /**
     * PHP环境要求
     * get_loaded_extensions()
     * @return array
     */
    private function phpinfo()
    {
        return [
            [
                'name' => 'PHP版本',
                'key' => 'php_version',
                'value' => '7.1.0及以上',
                'status' => version_compare(PHP_VERSION, '7.1.0') === -1 ? 'danger' : 'normal',
                'remark' => 'PHP版本必须为 7.1.0及以上'
            ],
            [
                'name' => 'CURL',
                'key' => 'curl',
                'value' => '支持',
                'status' => extension_loaded('curl') && function_exists('curl_init') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持CURL, 系统无法正常运行'
            ],
            [
                'name' => 'OpenSSL',
                'key' => 'openssl',
                'value' => '支持',
                'status' => extension_loaded('openssl') ? 'normal' : 'danger',
                'remark' => '没有启用OpenSSL, 将无法访问微信平台的接口'
            ],
            [
                'name' => 'PDO',
                'key' => 'pdo',
                'value' => '支持',
                'status' => extension_loaded('PDO') && extension_loaded('pdo_mysql') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持PDO, 系统无法正常运行'
            ],
            [
                'name' => 'GD',
                'key' => 'gd',
                'value' => '支持',
                'status' => extension_loaded('gd') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持GD, 系统无法正常生成图片'
            ],
            [
                'name' => 'BCMath',
                'key' => 'bcmath',
                'value' => '支持',
                'status' => extension_loaded('bcmath') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持BCMath, 系统无法正常运行'
            ],
            [
                'name' => 'mbstring',
                'key' => 'mbstring',
                'value' => '支持',
                'status' => extension_loaded('mbstring') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持mbstring, 系统无法正常运行'
            ],
            [
                'name' => 'SimpleXML',
                'key' => 'simplexML',
                'value' => '支持',
                'status' => extension_loaded('SimpleXML') ? 'normal' : 'danger',
                'remark' => '您的PHP环境不支持SimpleXML, 系统无法解析xml 无法使用微信支付'
            ],
        ];
    }

    /**
     * 目录权限监测
     */
    private function writeable()
    {
        $paths = [
            'uploads' => realpath(web_path()) . '/uploads/',
            'temp' => realpath(web_path()) . '/temp/',
            'wxpay_log' => realpath(base_path()) . '/common/library/wechat/logs/',
            'wxpay_cert' => realpath(base_path()) . '/common/library/wechat/cert/',
        ];
        return [
            [
                'name' => '文件上传目录',
                'key' => 'uploads',
                'value' => str_replace('\\', '/', $paths['uploads']),
                'status' => helper::checkWriteable($paths['uploads']) ? 'normal' : 'danger',
                'remark' => '目录不可写，系统将无法正常上传文件'
            ],
            [
                'name' => '临时文件目录',
                'key' => 'temp',
                'value' => str_replace('\\', '/', $paths['temp']),
                'status' => helper::checkWriteable($paths['temp']) ? 'normal' : 'danger',
                'remark' => '目录不可写，系统将无法正常写入文件'
            ],
//            [
//                'name' => '微信支付日志目录',
//                'key' => 'wxpay_log',
//                'value' => str_replace('\\', '/', $paths['wxpay_log']),
//                'status' => helper::checkWriteable($paths['wxpay_log']) ? 'normal' : 'danger',
//                'remark' => '目录不可写，系统将无法正常写入文件'
//            ],
            [
                'name' => '微信支付证书目录',
                'key' => 'wxpay_cert',
                'value' => str_replace('\\', '/', $paths['wxpay_cert']),
                'status' => helper::checkWriteable($paths['wxpay_cert']) ? 'normal' : 'danger',
                'remark' => '目录不可写，系统将无法正常写入文件'
            ],
        ];
    }

}
