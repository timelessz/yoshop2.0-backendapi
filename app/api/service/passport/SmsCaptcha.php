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

use app\api\validate\passport\SmsCaptcha as ValidateSmsCaptcha;
use app\common\service\BaseService;
use app\common\service\Message as MessageService;
use edward\captcha\facade\CaptchaApi;

/**
 * 服务类：发送短信验证码
 * Class SmsCaptcha
 * @package app\api\service\passport
 */
class SmsCaptcha extends BaseService
{
    /**
     * 发送短信验证码
     * @param array $data
     * @return bool
     */
    public function sendSmsCaptcha(array $data)
    {
        // 数据验证
        if (!$this->validate($data)) return false;
        // 生成验证码
        $smsCaptcha = CaptchaApi::createSMS($data['mobile']);
        // 发送短信
        $status = MessageService::send('passport.captcha', [
            'code' => $smsCaptcha['code'],
            'mobile' => $smsCaptcha['key']
        ], $this->storeId);
        if (!$status) {
            $this->error = '短信发送失败';
            return false;
        }
        return true;
    }

    /**
     * 数据验证
     * @param array $data
     * @return bool
     */
    private function validate(array $data)
    {
        // 数据验证
        $validate = new ValidateSmsCaptcha;
        if (!$validate->check($data)) {
            $this->error = $validate->getError();
            return false;
        }
        // 验证图形验证码
        if (!CaptchaApi::check($data['captchaCode'], $data['captchaKey'])) {
            $this->error = '图形验证码不正确';
            return false;
        }
        return true;
    }
}