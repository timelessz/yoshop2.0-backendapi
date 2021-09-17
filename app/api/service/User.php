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

namespace app\api\service;

use app\api\model\User as UserModel;
use app\api\model\UserOauth as UserOauthModel;
use app\common\service\BaseService;
use app\common\exception\BaseException;

/**
 * 用户服务类
 * Class User
 * @package app\api\service
 */
class User extends BaseService
{
    // 当前登录的会员信息
    private static $currentLoginUser;

    /**
     * 获取当前登录的用户信息 (快捷)
     * 可在api应用中的任意模块中调用此方法(controller model service)
     * 已登录情况下返回用户信息, 未登录返回false
     * @param bool $isForce 是否强制验证登录, 如果未登录将抛错
     * @return false|UserModel
     * @throws BaseException
     */
    public static function getCurrentLoginUser(bool $isForce = false)
    {
        $service = new static;
        if (empty(static::$currentLoginUser)) {
            static::$currentLoginUser = $service->getLoginUser();
            if (empty(static::$currentLoginUser)) {
                $isForce && throwError($service->getError(), config('status.not_logged'));
                return false;
            }
        }
        return static::$currentLoginUser;
    }

    /**
     * 获取当前登录的用户ID
     * getCurrentLoginUser方法的二次封装
     * @return mixed
     * @throws BaseException
     */
    public static function getCurrentLoginUserId()
    {
        $userInfo = static::getCurrentLoginUser(true);
        return $userInfo['user_id'];
    }

    /**
     * 获取第三方用户信息
     * @param int $userId 用户ID
     * @param string $oauthType 第三方登陆类型
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getOauth(int $userId, $oauthType = 'MP-WEIXIN')
    {
        return UserOauthModel::getOauth($userId, $oauthType);
    }

    /**
     * 验证是否已登录
     * @param bool $isForce 是否强制验证登录, 如果未登录将抛错
     * @return bool
     * @throws BaseException
     */
    public static function isLogin(bool $isForce = false)
    {
        return !empty(static::getCurrentLoginUser($isForce));
    }

    /**
     * 获取当前登录的用户信息
     * @return UserModel|bool|null
     */
    private function getLoginUser()
    {
        // 获取用户认证Token
        $token = $this->getToken();
        if ($token === false) return false;
        // 获取用户信息
        if (!$user = UserModel::getUserByToken($token)) {
            $this->error = '没有找到用户信息';
            return false;
        }
        return $user;
    }

    /**
     * 获取用户认证Token
     * @return bool|string
     */
    protected function getToken()
    {
        // 获取请求中的token
        $token = $this->request->header('Access-Token');
        // 调试模式下可通过param
        if (empty($token) && is_debug()) {
            $token = $this->request->param('Access-Token');
        }
        // 不存在token报错
        if (empty($token)) {
            $this->error = '缺少必要的参数token, 请先登录';
            return false;
        }
        return $token;
    }

}
