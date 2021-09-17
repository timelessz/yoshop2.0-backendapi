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

use app\api\service\User as UserService;
use app\common\exception\BaseException;
use app\common\model\UserCoupon as UserCouponModel;
use app\common\enum\coupon\ExpireType as ExpireTypeEnum;
use app\common\enum\coupon\ApplyRange as ApplyRangeEnum;
use app\common\library\helper;

/**
 * 用户优惠券模型
 * Class UserCoupon
 * @package app\api\model
 */
class UserCoupon extends UserCouponModel
{
    /**
     * 获取用户优惠券列表
     * @param int $userId
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(int $userId, array $param)
    {
        $filter = $this->getFilter($param);
        return $this->where($filter)
            ->where('user_id', '=', $userId)
            ->paginate();
    }

    /**
     * 检索查询条件
     * @param array $param
     * @return array|mixed
     */
    private function getFilter(array $param = [])
    {
        // 设置默认查询参数
        $params = $this->setQueryDefaultValue($param, [
            'dataType' => 'all',     // all:全部 isUsable:可用的 isExpire:已过期 isUse:已使用
            'amount' => null,        // 订单消费金额
        ]);
        // 检索列表类型
        $filter = [];
        // 可用的优惠券
        if ($params['dataType'] === 'isUsable') {
            $filter[] = ['is_use', '=', 0];
            $filter[] = ['is_expire', '=', 0];
            $filter[] = ['start_time', '<=', time()];
            $filter[] = ['end_time', '>', time()];
        }
        // 已过期的优惠券
        if ($params['dataType'] === 'isExpire') {
            $filter[] = ['is_expire', '=', 1];
        }
        // 已使用的优惠券
        if ($params['dataType'] === 'isUse') {
            $filter[] = ['is_use', '=', 1];
        }
        // 订单消费金额
        $params['amount'] > 0 && $filter[] = ['min_price', '<=', $params['amount']];
        return $filter;
    }

    /**
     * 获取用户优惠券总数量(可用)
     * @param int $userId
     * @return int
     */
    public function getCount(int $userId)
    {
        return $this->where('user_id', '=', $userId)
            ->where('is_use', '=', 0)
            ->where('is_expire', '=', 0)
            ->count();
    }

    /**
     * 获取用户优惠券ID集
     * @param $userId
     * @return array
     */
    public function getUserCouponIds(int $userId)
    {
        return $this->where('user_id', '=', $userId)->column('coupon_id');
    }

    /**
     * 领取优惠券
     * @param int $couponId 优惠券ID
     * @return bool
     * @throws BaseException
     */
    public function receive(int $couponId)
    {
        // 当前用户信息
        $userInfo = UserService::getCurrentLoginUser(true);
        // 获取优惠券信息
        $coupon = Coupon::detail($couponId);
        // 验证优惠券是否可领取
        if (!$this->checkReceive($userInfo, $coupon)) {
            return false;
        }
        // 添加领取记录
        return $this->add($userInfo, $coupon);
    }

    /**
     * 添加领取记录
     * @param $user
     * @param Coupon $coupon
     * @return bool
     */
    private function add($user, Coupon $coupon)
    {
        // 计算有效期
        if ($coupon['expire_type'] == ExpireTypeEnum::RECEIVE) {
            $startTime = time();
            $endTime = $startTime + ($coupon['expire_day'] * 86400);
        } else {
            $startTime = $coupon->getData('start_time');
            $endTime = $coupon->getData('end_time');
        }
        // 整理领取记录
        $data = [
            'coupon_id' => $coupon['coupon_id'],
            'name' => $coupon['name'],
            'coupon_type' => $coupon['coupon_type'],
            'reduce_price' => $coupon['reduce_price'],
            'discount' => $coupon['discount'],
            'min_price' => $coupon['min_price'],
            'expire_type' => $coupon['expire_type'],
            'expire_day' => $coupon['expire_day'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'apply_range' => $coupon['apply_range'],
            'apply_range_config' => $coupon['apply_range_config'],
            'user_id' => $user['user_id'],
            'store_id' => self::$storeId
        ];
        // 事务处理
        return $this->transaction(function () use ($data, $coupon) {
            // 添加领取记录
            $status = $this->save($data);
            if ($status) {
                // 更新优惠券领取数量
                $coupon->setIncReceiveNum();
            }
            return $status;
        });
    }

    /**
     * 验证优惠券是否可领取
     * @param mixed $userInfo 当前登录用户信息
     * @param Coupon $coupon 优惠券详情
     * @return bool
     */
    private function checkReceive($userInfo, Coupon $coupon)
    {
        if (!$coupon) {
            $this->error = '优惠券不存在';
            return false;
        }
        if (!$coupon->checkReceive()) {
            $this->error = $coupon->getError();
            return false;
        }
        // 验证是否已领取
        $userCouponIds = $this->getUserCouponIds($userInfo['user_id']);
        if (in_array($coupon['coupon_id'], $userCouponIds)) {
            $this->error = '该优惠券已领取';
            return false;
        }
        return true;
    }

    /**
     * 订单结算优惠券列表
     * @param int $userId 用户id
     * @param float $orderPayPrice 订单商品总金额
     * @return array|mixed
     * @throws \think\db\exception\DbException
     */
    public static function getUserCouponList(int $userId, float $orderPayPrice)
    {
        // 获取用户可用的优惠券列表
        $list = (new static)->getList($userId, ['dataType' => 'isUsable', 'amount' => $orderPayPrice]);
        $data = [];
        foreach ($list as $coupon) {
            // 有效期范围内
            if ($coupon['start_time'] > time()) continue;
            $key = $coupon['user_coupon_id'];
            $data[$key] = [
                'user_coupon_id' => $coupon['user_coupon_id'],
                'name' => $coupon['name'],
                'coupon_type' => $coupon['coupon_type'],
                'reduce_price' => $coupon['reduce_price'],
                'discount' => $coupon['discount'],
                'min_price' => $coupon['min_price'],
                'expire_type' => $coupon['expire_type'],
                'start_time' => $coupon['start_time'],
                'end_time' => $coupon['end_time'],
                'apply_range' => $coupon['apply_range'],
                'apply_range_config' => $coupon['apply_range_config']
            ];
            // 计算打折金额
            if ($coupon['coupon_type'] == 20) {
                $reducePrice = helper::bcmul($orderPayPrice, $coupon['discount'] / 10);
                $data[$key]['reduced_price'] = helper::bcsub($orderPayPrice, $reducePrice);
            } else
                $data[$key]['reduced_price'] = $coupon['reduce_price'];
        }
        // 根据折扣金额排序并返回
        return array_sort($data, 'reduced_price', true);
    }

    /**
     * 判断当前优惠券是否满足订单使用条件
     * @param $couponList
     * @param array $orderGoodsIds 订单商品ID集
     * @return mixed
     */
    public static function couponListApplyRange(array $couponList, array $orderGoodsIds)
    {
        // 名词解释(is_apply)：允许用于抵扣当前订单
        foreach ($couponList as &$item) {
            if ($item['apply_range'] == ApplyRangeEnum::ALL) {
                // 1. 全部商品
                $item['is_apply'] = true;
            } elseif ($item['apply_range'] == ApplyRangeEnum::SOME) {
                // 2. 指定商品, 判断订单商品是否存在可用
                $applyGoodsIds = array_intersect($item['apply_range_config']['applyGoodsIds'], $orderGoodsIds);
                $item['is_apply'] = !empty($applyGoodsIds);
            } elseif ($item['apply_range'] == ApplyRangeEnum::EXCLUDE) {
                // 2. 排除商品, 判断订单商品是否全部都在排除行列
                $excludedGoodsIds = array_intersect($item['apply_range_config']['excludedGoodsIds'], $orderGoodsIds);
                $item['is_apply'] = count($excludedGoodsIds) != count($orderGoodsIds);
            }
            !$item['is_apply'] && $item['not_apply_info'] = '该优惠券不支持当前商品';
        }
        return $couponList;
    }
}
