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

namespace app\api\service\passport;

use app\api\model\User as UserModel;
use app\api\service\user\Oauth as OauthService;
use app\api\service\user\Avatar as AvatarService;
use app\api\validate\passport\Login as ValidateLogin;
use app\common\exception\BaseException;
use app\common\service\BaseService;
use edward\captcha\facade\CaptchaApi;
use think\facade\Cache;

/**
 * 服务类：用户登录
 * Class Login
 * @package app\api\service\passport
 */
class Login extends BaseService
{
    // 用户信息 (登录成功后才记录)
    private $userInfo;

    // 用于生成token的自定义盐
    const TOKEN_SALT = 'user_salt';

    /**
     * 执行用户登录
     * @param array $data
     * @return bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login(array $data)
    {
        // 数据验证
        if (!$this->validate($data)) {
            return false;
        }
        // 自动登录注册
        $this->register($data);
        // 保存oauth信息
        $this->oauth($data);
        // 记录登录态
        return $this->session();
    }

    /**
     * 快捷登录：微信小程序用户
     * @param array $data
     * @return bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function mpWxLogin(array $data)
    {
        try {
            // 根据code换取openid
            $wxSession = OauthService::wxCode2Session($data['code']);
        } catch (BaseException $e) {
            // showError参数表示让前端显示错误
            throwError($e->getMessage(), null, ['showError' => true]);
            return false;
        }
        // 判断openid是否存在
        $userId = OauthService::getUserIdByOauthId($wxSession['openid'], 'MP-WEIXIN');
        // 获取用户信息
        $userInfo = !empty($userId) ? UserModel::detail($userId) : null;
        if (empty($userId) || empty($userInfo)) {
            $this->error = '第三方用户不存在';
            return false;
        }
        // 更新用户登录信息
        $this->updateUser($userInfo, true, $data);
        // 记录登录态
        return $this->session();
    }

    /**
     * 保存oauth信息
     * @param array $data
     * @return bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function oauth(array $data)
    {
        if ($data['isParty']) {
            $Oauth = new OauthService;
            return $Oauth->party((int)$this->userInfo['user_id'], $data['partyData']);
        }
        return true;
    }

    /**
     * 当前登录的用户信息
     * @return array
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * 自动登录注册
     * @param array $data
     * @return bool
     */
    private function register(array $data)
    {
        // 查询用户是否已存在
        $userInfo = UserModel::detail(['mobile' => $data['mobile']]);
        if ($userInfo) {
            // 用户存在: 更新登录信息
            return $this->updateUser($userInfo, $data['isParty'], $data['partyData']);
        } else {
            // 用户不存在: 新增用户
            return $this->createUser($data['mobile'], $data['isParty'], $data['partyData']);
        }
    }

    /**
     * 新增用户
     * @param string $mobile 手机号
     * @param bool $isParty 是否存在第三方用户信息
     * @param array $partyData 用户信息(第三方)
     * @return bool
     */
    private function createUser(string $mobile, bool $isParty, array $partyData = [])
    {
        // 用户信息
        $data = [
            'mobile' => $mobile,
            'nick_name' => hide_mobile($mobile),
            'platform' => getPlatform(),
            'last_login_time' => time(),
            'store_id' => $this->storeId
        ];
        // 写入用户信息(第三方)
        if ($isParty === true && !empty($partyData)) {
            $partyUserInfo = $this->partyUserInfo($partyData, true);
            $data = array_merge($data, $partyUserInfo);
        }
        // 新增用户记录
        $model = new UserModel;
        $status = $model->save($data);
        // 记录用户信息
        $this->userInfo = $model;
        return $status;
    }

    /**
     * 第三方用户信息
     * @param array $partyData 第三方用户信息
     * @param bool $isGetAvatarUrl 是否下载头像
     * @return array
     */
    private function partyUserInfo(array $partyData, bool $isGetAvatarUrl = true)
    {
        $partyUserInfo = $partyData['userInfo'];
        $data = [
            'nick_name' => $partyUserInfo['nickName'],
            'gender' => $partyUserInfo['gender']
        ];
        // 下载用户头像
        if ($isGetAvatarUrl) {
            $data['avatar_id'] = $this->partyAvatar($partyUserInfo['avatarUrl']);
        }
        return $data;
    }

    /**
     * 下载第三方头像并写入文件库
     * @param string $avatarUrl
     * @return int
     */
    private function partyAvatar(string $avatarUrl)
    {
        $Avatar = new AvatarService;
        $fileId = $Avatar->party($avatarUrl);
        return $fileId ? $fileId : 0;
    }

    /**
     * 更新用户登录信息
     * @param UserModel $userInfo
     * @param bool $isParty 是否存在第三方用户信息
     * @param array $partyData 用户信息(第三方)
     * @return bool
     */
    private function updateUser(UserModel $userInfo, bool $isParty, array $partyData = [])
    {
        // 用户信息
        $data = [
            'last_login_time' => time(),
            'store_id' => $this->storeId
        ];
        // 写入用户信息(第三方)
        if ($isParty === true && !empty($partyData)) {
            $partyUserInfo = $this->partyUserInfo($partyData, !$userInfo['avatar_id']);
            $data = array_merge($data, $partyUserInfo);
        }
        // 更新用户记录
        $status = $userInfo->save($data) !== false;
        // 记录用户信息
        $this->userInfo = $userInfo;
        return $status;
    }

    /**
     * 记录登录态
     * @return bool
     * @throws BaseException
     */
    private function session()
    {
        empty($this->userInfo) && throwError('未找到用户信息');
        // 登录的token
        $token = $this->getToken((int)$this->userInfo['user_id']);
        // 记录缓存, 30天
        Cache::set($token, [
            'user' => $this->userInfo,
            'store_id' => $this->storeId,
            'is_login' => true,
        ], 86400 * 30);
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
        $validate = new ValidateLogin;
        if (!$validate->check($data)) {
            $this->error = $validate->getError();
            return false;
        }
        // 验证短信验证码是否匹配
        if (!CaptchaApi::checkSms($data['smsCode'], $data['mobile'])) {
            $this->error = '短信验证码不正确';
            return false;
        }
        return true;
    }

    /**
     * 获取登录的token
     * @param int $userId
     * @return string
     */
    public function getToken(int $userId)
    {
        static $token = '';
        if (empty($token)) {
            $token = $this->makeToken($userId);
        }
        return $token;
    }

    /**
     * 生成用户认证的token
     * @param int $userId
     * @return string
     */
    public function makeToken(int $userId)
    {
        $storeId = $this->storeId;
        // 生成一个不会重复的随机字符串
        $guid = get_guid_v4();
        // 当前时间戳 (精确到毫秒)
        $timeStamp = microtime(true);
        // 自定义一个盐
        $salt = self::TOKEN_SALT;
        return md5("{$storeId}_{$timeStamp}_{$userId}_{$guid}_{$salt}");
    }

}