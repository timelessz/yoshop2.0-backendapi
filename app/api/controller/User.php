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

namespace app\api\controller;

use app\common\exception\BaseException;
use app\api\model\UserCoupon as UserCouponModel;
use app\api\service\User as UserService;

/**
 * 用户管理
 * Class User
 * @package app\api
 */
class User extends Controller
{
    /**
     * 当前用户详情
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function info()
    {
        // 当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 获取用户头像
        $userInfo['avatar'];
        // 获取会员等级
        $userInfo['grade'];
        return $this->renderSuccess(compact('userInfo'));
    }

    /**
     * 账户资产
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function assets()
    {
        // 当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 用户优惠券模型
        $model = new UserCouponModel;
        // 返回数据
        return $this->renderSuccess([
            'assets' => [
                'balance' => $userInfo['balance'],  // 账户余额
                'points' => $userInfo['points'],    // 会员积分
                'coupon' => $model->getCount($userInfo['user_id']),    // 优惠券数量(可用)
            ]
        ]);
    }
}
