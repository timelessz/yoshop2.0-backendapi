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

namespace app\api\model;

use app\api\service\User as UserService;
use app\common\exception\BaseException;
use app\common\model\Coupon as CouponModel;

/**
 * 优惠券模型
 * Class Coupon
 * @package app\api\model
 */
class Coupon extends CouponModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'receive_num',
        'is_delete',
        'create_time',
        'update_time',
    ];

    /**
     * 获取优惠券列表
     * @param int|null $limit 获取的数量
     * @param bool $onlyReceive 只显示可领取
     * @return \think\Collection
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(int $limit = null, bool $onlyReceive = false)
    {
        // 查询构造器
        $query = $this->getNewQuery();
        // 只显示可领取(未过期,未发完)的优惠券
        if ($onlyReceive) {
            $query->where('IF ( `total_num` > - 1, `receive_num` < `total_num`, 1 = 1 )')
                ->where('IF ( `expire_type` = 20, (`end_time` + 86400) >= ' . time() . ', 1 = 1 )');
        }
        // 查询数量
        $limit > 0 && $query->limit($limit);
        // 优惠券列表
        $couponList = $query->where('is_delete', '=', 0)
            ->order(['sort', 'create_time' => 'desc'])
            ->select();
        // 获取用户已领取的优惠券
        return $this->setIsReceive($couponList);
    }

    /**
     * 获取用户已领取的优惠券
     * @param $couponList
     * @return mixed
     * @throws BaseException
     */
    private function setIsReceive($couponList) {
        // 获取用户已领取的优惠券
        $userInfo = UserService::getCurrentLoginUser();
        if ($userInfo !== false) {
            $UserCouponModel = new UserCoupon;
            $userCouponIds = $UserCouponModel->getUserCouponIds($userInfo['user_id']);
            foreach ($couponList as $key => $item) {
                $couponList[$key]['is_receive'] = in_array($item['coupon_id'], $userCouponIds);
            }
        }
        return $couponList;
    }

    /**
     * 验证优惠券是否可领取
     * @return bool
     */
    public function checkReceive()
    {
        if ($this['total_num'] > -1 && $this['receive_num'] >= $this['total_num']) {
            $this->error = '优惠券已发完';
            return false;
        }
        if ($this['expire_type'] == 20 && ($this->getData('end_time') + 86400) < time()) {
            $this->error = '优惠券已过期';
            return false;
        }
        return true;
    }

    /**
     * 累计已领取数量
     * @return mixed
     */
    public function setIncReceiveNum()
    {
        return $this->setInc($this['coupon_id'], 'receive_num', 1);
    }

}
