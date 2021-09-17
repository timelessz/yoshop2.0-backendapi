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

namespace app\store\model\article;

use app\store\model\Article as ArticleModel;
use app\common\model\article\Category as CategoryModel;

/**
 * 文章分类模型
 * Class Category
 * @package app\store\model\article
 */
class Category extends CategoryModel
{
    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add(array $data)
    {
        // 保存记录
        $data['store_id'] = self::$storeId;
        return $this->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     */
    public function edit(array $data)
    {
        // 保存记录
        return $this->save($data);
    }

    /**
     * 删除商品分类
     * @param int $categoryId
     * @return bool
     * @throws \Exception
     */
    public function remove(int $categoryId)
    {
        // 判断是否存在文章
        $articleCount = ArticleModel::getArticleTotal(['category_id' => $categoryId]);
        if ($articleCount > 0) {
            $this->error = '该分类下存在' . $articleCount . '个文章，不允许删除';
            return false;
        }
        // 删除记录
        return $this->delete();
    }

}
