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

namespace app\api\controller\article;

use app\api\controller\Controller;
use app\api\model\article\Category as CategoryModel;

/**
 * 文章分类
 * Class Category
 * @package app\api\controller\article
 */
class Category extends Controller
{
    /**
     * 文章分类列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        $model = new CategoryModel;
        $list = $model->getShowList();
        return $this->renderSuccess(compact('list'));
    }

}