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

namespace app\common\library\storage;

class FileValidate extends \think\Validate
{
    // 验证规则
    protected $rule = [
        // 文件大小: 2MB = (1024 * 1024 * 2) = 2097152 字节
        // 文件扩展名: jpg,jpeg,png,bmp,gif
        'image' => 'filesize:2097152|fileExt:jpg,jpeg,png,bmp,gif',
    ];

    // 错误提示信息
    protected $message = [
        'image.filesize' => '文件大小不能超出2MB',
        'image.fileExt' => '文件扩展名有误',
    ];

    // 验证场景
    protected $scene = [
        'image' => ['image'],
    ];

}
