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

use app\api\model\UserCoupon as UserCouponModel;
use app\api\service\User as UserService;
use app\common\exception\BaseException;

/**
 * 用户优惠券
 * Class Coupon
 * @package app\api\controller
 */
class MyCoupon extends Controller
{
    /**
     * 用户优惠券列表
     * @return mixed
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        $userId = UserService::getCurrentLoginUserId();
        $model = new UserCouponModel;
        $list = $model->getList($userId, $this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 领取优惠券
     * @param int $couponId
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function receive(int $couponId)
    {
        $model = new UserCouponModel;
        if ($model->receive($couponId)) {
            return $this->renderSuccess([], '领取成功');
        }
        return $this->renderError($model->getError() ?: '领取失败');
    }

}