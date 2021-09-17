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

namespace app\store\controller\market;

use app\store\controller\Controller;
use app\store\model\Coupon as CouponModel;
use app\store\model\UserCoupon as UserCouponModel;

/**
 * 优惠券管理
 * Class Coupon
 * @package app\store\controller\market
 */
class Coupon extends Controller
{
    /**
     * 列表记录
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new CouponModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 详情记录
     * @param int $couponId
     * @return array
     */
    public function detail(int $couponId)
    {
        $detail = CouponModel::detail($couponId);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 添加优惠券
     * @return array|mixed
     */
    public function add()
    {
        // 新增记录
        $model = new CouponModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 更新优惠券
     * @param $couponId
     * @return array|mixed
     */
    public function edit(int $couponId)
    {
        // 优惠券详情
        $model = CouponModel::detail($couponId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除优惠券
     * @param $couponId
     * @return array|mixed
     */
    public function delete(int $couponId)
    {
        // 优惠券详情
        $model = CouponModel::detail($couponId);
        // 更新记录
        if ($model->setDelete()) {
            return $this->renderSuccess('删除成功');
        }
        return $this->renderError($model->getError() ?: '删除成功');
    }

    /**
     * 领取记录
     * @return array
     */
    public function receive()
    {
        // 获取列表记录
        $model = new UserCouponModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

}
