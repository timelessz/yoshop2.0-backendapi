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

namespace app\api\service\passport;

use app\common\service\BaseService;
use edward\captcha\facade\CaptchaApi;

class Captcha extends BaseService
{
    /**
     * 图形验证码
     * @return array|\think\response\Json
     */
    public function create()
    {
        $data = CaptchaApi::create();
        return [
            'base64' => str_replace("\r\n", '', $data['base64']),
            'key' => $data['key'],
            'md5' => $data['md5']
        ];
    }
}