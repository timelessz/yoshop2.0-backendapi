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

namespace app\api\controller\order;

use app\api\controller\Controller;
use app\api\model\Order as OrderModel;
use app\api\model\Comment as CommentModel;
use app\api\model\OrderGoods as OrderGoodsModel;

/**
 * 订单评价管理
 * Class Comment
 * @package app\api\controller\order
 */
class Comment extends Controller
{
    /**
     * 待评价订单商品列表
     * @param int $orderId
     * @return array
     * @throws \Exception
     * @throws \app\common\exception\BaseException
     * @throws \think\exception\DbException
     */
    public function list(int $orderId)
    {
        // 订单信息
        $orderInfo = OrderModel::getDetail($orderId);
        // 验证订单是否已完成
        $model = new CommentModel;
        if (!$model->checkOrderAllowComment($orderInfo)) {
            return $this->renderError($model->getError());
        }
        // 待评价商品列表
        $goodsList = OrderGoodsModel::getNotCommentGoodsList($orderId);
        if ($goodsList->isEmpty()) {
            return $this->renderError('该订单没有可评价的商品');
        }
        return $this->renderSuccess(compact('goodsList'));
    }

    /**
     * 创建商品评价
     * @param int $orderId
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function submit(int $orderId)
    {
        // 订单信息
        $orderInfo = OrderModel::getDetail($orderId);
        // 验证订单是否已完成
        $model = new CommentModel;
        if (!$model->checkOrderAllowComment($orderInfo)) {
            return $this->renderError($model->getError());
        }
        // 待评价商品列表
        $goodsList = OrderGoodsModel::getNotCommentGoodsList($orderId);
        if ($goodsList->isEmpty()) {
            return $this->renderError('该订单没有可评价的商品');
        }
        // 提交商品评价
        $model = new CommentModel;
        if ($model->increased($orderInfo, $goodsList, $this->postForm())) {
            return $this->renderSuccess([], '评价发表成功');
        }
        return $this->renderError($model->getError() ?: '评价发表失败');
    }

}