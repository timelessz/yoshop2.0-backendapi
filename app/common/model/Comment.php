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
 * 商品评价模型
 * Class Comment
 * @package app\common\model
 */
class Comment extends BaseModel
{
    // 定义表名
    protected $name = 'comment';

    // 定义主键
    protected $pk = 'comment_id';

    /**
     * 所属订单
     * @return \think\model\relation\BelongsTo
     */
    public function orderData()
    {
        return $this->belongsTo('Order');
    }

    /**
     * 订单商品
     * @return \think\model\relation\BelongsTo
     */
    public function orderGoods()
    {
        return $this->belongsTo('OrderGoods')
            ->field(['order_goods_id', 'goods_id', 'goods_name', 'image_id', 'goods_props', 'order_id']);
    }

    /**
     * 关联用户表
     * @return \think\model\relation\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User')->field(['user_id', 'nick_name', 'avatar_id']);
    }

    /**
     * 关联评价图片表
     * @return \think\model\relation\HasMany
     */
    public function images()
    {
        return $this->hasMany('CommentImage')->order(['id']);
    }

    /**
     * 详情记录
     * @param int $commentId
     * @param array $with
     * @return static|array|null
     */
    public static function detail(int $commentId, array $with = [])
    {
        return static::get($commentId, $with);
    }

    /**
     * 添加评论图片
     * @param array $images
     * @return array|false
     */
    protected function addCommentImages(array $images)
    {
        $data = array_map(function ($imageId) {
            return [
                'image_id' => $imageId,
                'store_id' => self::$storeId
            ];
        }, $images);
        return $this->image()->saveAll($data) !== false;
    }

}
