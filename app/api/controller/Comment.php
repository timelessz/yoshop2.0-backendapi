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

use app\api\model\Comment as CommentModel;

/**
 * 商品评价控制器
 * Class Comment
 * @package app\api\controller
 */
class Comment extends Controller
{
    /**
     * 商品评价列表
     * @param int $goodsId 商品ID
     * @param int|null $scoreType 评价评分
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list(int $goodsId, int $scoreType = null)
    {
        // 评价列表
        $model = new CommentModel;
        $list = $model->getCommentList($goodsId, $scoreType);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 商品评分总数
     * @param int $goodsId
     * @return array|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function total(int $goodsId)
    {
        // 指定评分总数
        $model = new CommentModel;
        $total = $model->getTotal($goodsId);
        return $this->renderSuccess(compact('total'));
    }

    /**
     * 商品评价列表 (限制数量, 用于商品详情页展示)
     * @param int $goodsId
     * @param int $limit
     * @return array|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function listRows(int $goodsId, int $limit = 5)
    {
        // 评价列表
        $model = new CommentModel;
        $list = $model->listRows($goodsId, $limit);
        // 评价总数量
        $total = $model->rowsTotal($goodsId);
        return $this->renderSuccess(compact('list', 'total'));
    }

}