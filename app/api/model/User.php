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

namespace app\api\model;

use think\facade\Cache;
use app\common\model\User as UserModel;

/**
 * 用户模型类
 * Class User
 * @package app\api\model
 */
class User extends UserModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'open_id',
        'is_delete',
        'store_id',
        'create_time',
        'update_time'
    ];

    /**
     * 获取器：隐藏手机号中间四位
     * @param $value
     * @return mixed
     */
    public function getMobileAttr($value)
    {
        if (strlen($value) === 11) {
            return hide_mobile($value);
        }
        return $value;
    }

    /**
     * 获取用户信息
     * @param $token
     * @return bool|static
     */
    public static function getUserByToken(string $token)
    {
        if (Cache::has($token)) {
            // 获取微信用户openid
            $userId = Cache::get($token)['user']['user_id'];
            // 获取用户信息s
            return self::detail($userId);
        }
        return false;
    }

}
