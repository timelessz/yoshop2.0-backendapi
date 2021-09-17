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

namespace app\common\enum\file;

use app\common\enum\EnumBasics;

/**
 * 枚举类：文件存储类型
 * Class Storage
 * @package app\common\enum\file
 */
class Storage extends EnumBasics
{
    // 本地
    const LOCAL = 'local';

    // 七牛云
    const QINIU = 'qiniu';

    // 阿里云
    const ALIYUN = 'aliyun';

    // 腾讯云
    const QCLOUD = 'qcloud';

    /**
     * 获取枚举类型值
     * @return array
     */
    public static function data()
    {
        return [
            self::LOCAL => [
                'name' => '本地',
                'value' => self::LOCAL,
            ],
            self::QINIU => [
                'name' => '七牛云',
                'value' => self::QINIU,
            ],
            self::ALIYUN => [
                'name' => '阿里云',
                'value' => self::ALIYUN,
            ],
            self::QCLOUD => [
                'name' => '腾讯云',
                'value' => self::QCLOUD,
            ],
        ];
    }
}
