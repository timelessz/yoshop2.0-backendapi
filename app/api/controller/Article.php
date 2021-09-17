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

use app\api\model\Article as ArticleModel;

/**
 * 文章控制器
 * Class Article
 * @package app\api\controller
 */
class Article extends Controller
{
    /**
     * 文章列表
     * @param int $categoryId
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list(int $categoryId = 0)
    {
        $model = new ArticleModel;
        $list = $model->getList($categoryId);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 文章详情
     * @param int $articleId
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     */
    public function detail(int $articleId)
    {
        $detail = ArticleModel::getDetail($articleId);
        return $this->renderSuccess(compact('detail'));
    }
}
