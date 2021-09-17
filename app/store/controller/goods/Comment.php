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

namespace app\store\controller\goods;

use app\store\controller\Controller;
use app\store\model\Comment as CommentModel;

/**
 * 商品评价管理
 * Class Comment
 * @package app\store\controller\goods
 */
class Comment extends Controller
{
    /**
     * 评价列表
     * @return array
     */
    public function list()
    {
        $model = new CommentModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 评价详情
     * @param int $commentId
     * @return array|bool|string
     */
    public function detail(int $commentId)
    {
        // 评价详情
        $model = new CommentModel;
        $detail = $model->getDetail($commentId);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 编辑评价
     * @param int $commentId
     * @return array
     */
    public function edit(int $commentId)
    {
        // 评价详情
        $model = CommentModel::detail($commentId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除评价
     * @param int $commentId
     * @return array|bool
     */
    public function delete(int $commentId)
    {
        // 评价详情
        $model = CommentModel::detail($commentId);
        if (!$model->setDelete()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
