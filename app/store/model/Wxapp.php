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
declare (strict_types=1);

namespace app\store\model;

use think\facade\Cache;
use app\common\model\Wxapp as WxappModel;

/**
 * 微信小程序模型
 * Class Wxapp
 * @package app\store\model
 */
class Wxapp extends WxappModel
{
    /**
     * 更新小程序设置
     * @param $data
     * @return mixed
     */
    public function edit(array $data)
    {
        // 默认数据
        $data['cert_pem'] = $data['cert_pem'] ?? '';
        $data['key_pem'] = $data['key_pem'] ?? '';
        // 事务处理
        return $this->transaction(function () use ($data) {
            // 删除wxapp缓存
            self::deleteCache();
            // 写入微信支付证书文件
            $this->writeCertPemFiles($data['cert_pem'], $data['key_pem']);
            // 更新小程序设置
            return $this->save($data);
        });
    }

    /**
     * 写入cert证书文件
     * @param string|null $certPem
     * @param string|null $keyPem
     * @return bool
     */
    private function writeCertPemFiles(string $certPem = '', string $keyPem = '')
    {
        if (empty($certPem) && empty($keyPem)) {
            return false;
        }
        // 证书目录
        $filePath = base_path() . 'common/library/wechat/cert/' . self::$storeId . '/';
        // 目录不存在则自动创建
        if (!is_dir($filePath)) {
            mkdir($filePath, 0755, true);
        }
        // 写入cert.pem文件
        if (!empty($certPem)) {
            file_put_contents($filePath . 'cert.pem', $certPem);
        }
        // 写入key.pem文件
        if (!empty($keyPem)) {
            file_put_contents($filePath . 'key.pem', $keyPem);
        }
        return true;
    }

    /**
     * 删除wxapp缓存
     * @return bool
     */
    public static function deleteCache()
    {
        return Cache::delete('wxapp_' . self::$storeId);
    }

}
