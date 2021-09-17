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

namespace app\common\library;

use app\common\exception\BaseException;

class Download
{
    /**
     * 获取网络图片到临时目录
     * @param int $storeId
     * @param string $url
     * @param string $prefix
     * @return string
     * @throws BaseException
     */
    public function saveTempImage(int $storeId, string $url, string $prefix = 'temp')
    {
        $savePath = $this->getSavePath($storeId, $prefix, $url);
        if (!file_exists($savePath)) {
            $result = $this->curl($url);
            empty($result) && throwError('获取到的图片内容为空');
            $this->fwrite($savePath, $result);
        }
        return $savePath;
    }

    /**
     * 写入文件
     * @param string $savePath
     * @param string $result
     * @return false|int
     */
    private function fwrite(string $savePath, string $result)
    {
        $fp = fopen($savePath, 'w');
        $status = fwrite($fp, $result);
        fclose($fp);
        return $status;
    }

    /**
     * 请求网络文件
     * @param string $url
     * @return bool|string
     */
    private function curl(string $url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 文件保存的路径
     * @param int $storeId
     * @param string $prefix
     * @param string $url
     * @return string
     */
    private function getSavePath(int $storeId, string $prefix, string $url)
    {
        $dirPath = $this->getDirPath($storeId);
        return $dirPath . '/' . $prefix . '_' . md5($url) . '.png';
    }

    /**
     * 文件保存的目录
     * @param int $storeId
     * @return string
     */
    private function getDirPath(int $storeId)
    {
        $dirPath = runtime_root_path() . "image/{$storeId}";
        !is_dir($dirPath) && mkdir($dirPath, 0755, true);
        return $dirPath;
    }

}