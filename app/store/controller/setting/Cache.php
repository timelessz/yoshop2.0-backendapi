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

namespace app\store\controller\setting;

use app\common\library\helper;
use app\store\controller\Controller;
use think\facade\Cache as CacheDrive;

/**
 * 清理缓存
 * Class Index
 * @package app\store\controller
 */
class Cache extends Controller
{
    /**
     * 数据缓存项目(只显示key和name)
     * @return array
     */
    public function items()
    {
        $items = [];
        foreach ($this->getItems() as $key => $item) {
            $items[] = [
                'key' => $key,
                'name' => $item['name']
            ];
        }
        return $this->renderSuccess(compact('items'));
    }

    /**
     * 清理缓存
     * @return mixed
     */
    public function clear()
    {
        // 删除缓存
        $this->rmCache($this->postForm()['keys']);
        return $this->renderSuccess('操作成功');
    }

    /**
     * 数据缓存项目
     * @return array
     */
    private function getItems()
    {
        $storeId = $this->store['store_id'];
        return [
            'category' => [
                'type' => 'cache',
                'key' => "category_{$storeId}",
                'name' => '商品分类'
            ],
            'setting' => [
                'type' => 'cache',
                'key' => "setting_{$storeId}",
                'name' => '商城设置'
            ],
            'wxapp' => [
                'type' => 'cache',
                'key' => "wxapp_{$storeId}",
                'name' => '小程序设置'
            ],
            'temp' => [
                'type' => 'file',
                'name' => '临时图片',
                'dirPath' => [
                    'web' => web_path() . "temp/{$storeId}/",
                    'runtime' => runtime_root_path() . "/image/{$storeId}/",
                ]
            ],
        ];
    }

    /**
     * 删除缓存
     * @param $keys
     */
    private function rmCache(array $keys)
    {
        $cacheList = $this->getItems();
        $keys = array_intersect(array_keys($cacheList), $keys);
        foreach ($keys as $key) {
            $item = $cacheList[$key];
            if ($item['type'] === 'cache') {
                $cache = CacheDrive::instance();
                $cache->has($item['key']) && $cache->delete($item['key']);
            } elseif ($item['type'] === 'file') {
                $this->deltree($item['dirPath']);
            }
        }
    }

    /**
     * 删除目录下所有文件
     * @param $dirPath
     * @return bool
     */
    private function deltree($dirPath)
    {
        if (is_array($dirPath)) {
            foreach ($dirPath as $path)
                $this->deleteFolder($path);
        } else {
            return $this->deleteFolder($dirPath);
        }
        return true;
    }

    /**
     * 递归删除指定目录下所有文件
     * @param $path
     * @return bool
     */
    private function deleteFolder(string $path)
    {
        if (!is_dir($path)) return false;
        // 扫描一个文件夹内的所有文件夹和文件
        foreach (scandir($path) as $val) {
            // 排除目录中的.和..
            if (!in_array($val, ['.', '..'])) {
                // 如果是目录则递归子目录，继续操作
                if (is_dir("{$path}{$val}")) {
                    // 子目录中操作删除文件夹和文件
                    $this->deleteFolder("{$path}{$val}/");
                    // 目录清空后删除空文件夹
                    rmdir("{$path}{$val}/");
                } else {
                    // 如果是文件直接删除
                    unlink("{$path}{$val}");
                }
            }
        }
        return true;
    }

}
