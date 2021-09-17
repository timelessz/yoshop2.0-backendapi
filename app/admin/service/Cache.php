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

namespace app\admin\service;

use app\common\service\BaseService;
use think\facade\Cache as CacheDriver;

/**
 * 清理缓存
 * Class Cache
 */
class Cache extends BaseService
{
    // 缓存驱动句柄
    /** @var $CacheDriver \think\Cache */
    private $cache;

    /**
     * 构造方法
     * Cache constructor.
     */
    public function initialize()
    {
        // 实例化缓存驱动
        $this->cache = CacheDriver::instance();
    }

    /**
     * 删除缓存
     * @param $data
     * @return bool
     */
    public function rmCache($data)
    {
        // 数据缓存
        if (in_array('data', $data['item'])) {
            // 强制模式
            $isForce = isset($data['isForce']) ? (bool)$data['isForce'] : false;
            // 清除缓存
            $isForce ? $this->cache->clear() : $this->cache->tag('cache')->clear();
        }
        // 临时文件
        if (in_array('temp', $data['item'])) {
            $paths = [
                'temp' => web_path() . 'temp/',
                'runtime' => runtime_root_path() . 'image/'
            ];
            foreach ($paths as $path) {
                $this->deleteFolder($path);
            }
        }
        return true;
    }

    /**
     * 递归删除指定目录下所有文件
     * @param $path
     * @return bool
     */
    private function deleteFolder($path)
    {
        if (!is_dir($path))
            return false;
        // 扫描一个文件夹内的所有文件夹和文件
        foreach (scandir($path) as $val) {
            // 排除目录中的.和..
            if (!in_array($val, ['.', '..', '.gitignore'])) {
                // 如果是目录则递归子目录，继续操作
                if (is_dir($path . $val)) {
                    // 子目录中操作删除文件夹和文件
                    $this->deleteFolder($path . $val . '/');
                    // 目录清空后删除空文件夹
                    rmdir($path . $val . '/');
                } else {
                    // 如果是文件直接删除
                    unlink($path . $val);
                }
            }
        }
        return true;
    }

}