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

namespace app\common\model;

/**
 * 售后单模型
 * Class OrderRefund
 * @package app\common\model\wxapp
 */
class OrderRefund extends BaseModel
{
    // 定义表名
    protected $name = 'order_refund';

    // 定义主键
    protected $pk = 'order_refund_id';

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User');
    }

    /**
     * 关联订单主表
     * @return \think\model\relation\BelongsTo
     */
    public function orderData()
    {
        return $this->belongsTo('Order');
    }

    /**
     * 关联订单商品表
     * @return \think\model\relation\BelongsTo
     */
    public function orderGoods()
    {
        return $this->belongsTo('OrderGoods')->withoutField(['content']);
    }

    /**
     * 关联图片记录表
     * @return \think\model\relation\HasMany
     */
    public function images()
    {
        return $this->hasMany('OrderRefundImage');
    }

    /**
     * 关联物流公司表
     * @return \think\model\relation\BelongsTo
     */
    public function express()
    {
        return $this->belongsTo('Express');
    }

    /**
     * 关联用户表
     * @return \think\model\relation\HasOne
     */
    public function address()
    {
        return $this->hasOne('OrderRefundAddress');
    }

    /**
     * 售后单详情
     * @param array|int $where
     * @param array $with
     * @return null|static
     */
    public static function detail($where, array $with = [])
    {
        return static::get($where, $with);
    }

}