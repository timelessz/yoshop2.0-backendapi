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

namespace app\common\library\storage\engine;

use OSS\OssClient;
use OSS\Core\OssException;

/**
 * 阿里云存储引擎 (OSS)
 * Class Aliyun
 * @package app\common\library\storage\engine
 */
class Aliyun extends Basics
{
    /**
     * 执行上传
     * @return bool|mixed
     */
    public function upload()
    {
        try {
            $ossClient = new OssClient(
                $this->config['access_key_id'],
                $this->config['access_key_secret'],
                $this->config['domain'],
                true
            );
            $result = $ossClient->uploadFile(
                $this->config['bucket'],
                $this->getSaveFileInfo()['file_path'],
                $this->getRealPath()
            );
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * 删除文件
     * @param string $fileName
     * @return bool|mixed
     */
    public function delete(string $fileName)
    {
        try {
            $ossClient = new OssClient(
                $this->config['access_key_id'],
                $this->config['access_key_secret'],
                $this->config['domain'],
                true
            );
            $ossClient->deleteObject($this->config['bucket'], $fileName);
        } catch (OssException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        return true;
    }

}
