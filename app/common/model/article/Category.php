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

namespace app\common\model\article;

use app\common\model\BaseModel;

/**
 * 文章分类模型
 * Class Category
 * @package app\common\model
 */
class Category extends BaseModel
{
    // 定义表名
    protected $name = 'article_category';

    // 定义主键
    protected $pk = 'category_id';

    /**
     * 分类详情
     * @param int $categoryId
     * @return static|null
     */
    public static function detail(int $categoryId)
    {
        return static::get($categoryId);
    }

    /**
     * 获取列表
     * @param array $where
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $where = [])
    {
        return $this->where($where)
            ->order(['sort', $this->getPk()])
            ->select();
    }

}
