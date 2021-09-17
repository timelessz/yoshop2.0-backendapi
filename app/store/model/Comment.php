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

namespace app\store\model;

use app\common\library\helper;
use app\common\model\Comment as CommentModel;
use app\store\model\CommentImage as CommentImageModel;

/**
 * 商品评价模型
 * Class Comment
 * @package app\store\model
 */
class Comment extends CommentModel
{
    /**
     * 获取评价列表
     * @param array $param
     * @return mixed
     */
    public function getList(array $param = [])
    {
        // 检索查询条件
        $query = $this->setQueryFilter($param);
        // 查询列表数据
        return $query->with(['user.avatar', 'orderGoods' => ['image']])
            ->alias($this->name)
            ->field(["$this->name.*"])
            ->join('goods', "goods.goods_id = {$this->name}.goods_id")
            ->join('order', "order.order_id = {$this->name}.order_id")
            ->join('user', "user.user_id = {$this->name}.user_id")
            ->where("{$this->name}.is_delete", '=', 0)
            ->order(["{$this->name}.sort" => 'asc', "{$this->name}.create_time" => 'desc'])
            ->paginate(15);
    }

    /**
     * 检索查询条件
     * @param array $param
     * @return \think\db\BaseQuery
     */
    private function setQueryFilter(array $param)
    {
        // 实例化查询对象
        $query = $this->getNewQuery();
        // 查询参数
        $params = $this->setQueryDefaultValue($param, [
            'score' => 0,       // 评分 (10好评 20中评 30差评)
            'goodsName' => '',  // 商品名称/编码
            'orderNo' => '',    // 订单号
            'userId' => 0,      // 用户id
            'status' => -1      // 评价状态 -1全部
        ]);
        // 评分
        $params['score'] > 0 && $query->where("{$this->name}.score", '=', $params['score']);
        // 商品名称/编码
        !empty($params['goodsName']) && $query->where('goods.goods_name|goods.goods_no', 'like', "%{$params['goodsName']}%");
        // 订单号
        !empty($params['orderNo']) && $query->where('order.order_no', 'like', "%{$params['orderNo']}%");
        // 用户id
        $params['userId'] > 0 && $query->where("{$this->name}.user_id", '=', $params['userId']);
        // 评价状态
        $params['status'] > -1 && $query->where("{$this->name}.status", '=', $params['status']);
        return $query;
    }

    /**
     * 获取评价详情
     * @param int $commentId
     * @return Comment|array|null
     */
    public function getDetail(int $commentId)
    {
        // 评价详情
        $detail = static::detail($commentId, ['images.file']);
        // 图片ID集
        $detail['imageIds'] = helper::getArrayColumn($detail, 'image_id');
        // 图片列表
        $detail['imageList'] = helper::getArrayColumn($detail['images'], 'file');
        return $detail;
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool
     */
    public function edit(array $data)
    {
        return $this->transaction(function () use ($data) {
            // 更新商品图片记录
            CommentImageModel::updates((int)$this['comment_id'], $data['imageIds']);
            // 是否为图片评价
            $data['is_picture'] = !empty($data['images']);
            // 更新评论记录
            return $this->save($data);
        });
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 获取评价总数量
     * @return int|string
     */
    public function getCommentTotal()
    {
        return $this->where(['is_delete' => 0])->count();
    }

}
