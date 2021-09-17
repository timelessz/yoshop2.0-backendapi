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
declare (strict_types=1);

namespace app\api\model;

use app\common\exception\BaseException;
use app\common\model\Article as ArticleModel;

/**
 * 商品评价模型
 * Class Article
 * @package app\api\model
 */
class Article extends ArticleModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'is_delete',
        'store_id',
        'create_time',
        'update_time'
    ];

    /**
     * 获取器：文章详情HTML实体转换回普通字符
     * @param $value
     * @return string
     */
    public function getArticleContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    /**
     * 获取文章详情并累计阅读次数
     * @param int $articleId 文章ID
     * @return static|null
     * @throws BaseException
     */
    public static function getDetail(int $articleId)
    {
        // 获取文章详情
        $detail = parent::detail($articleId, ['image']);
        if (empty($detail) || $detail['is_delete']) {
            throwError('很抱歉，当前文章不存在');
        }
        // 累积文章实际阅读数
        static::setIncActualViews($articleId);
        return $detail;
    }

    /**
     * 累积文章实际阅读数
     * @param int $articleId 文章ID
     * @param int $num 递增的数量
     * @return mixed
     */
    private static function setIncActualViews(int $articleId, int $num = 1)
    {
        return (new static)->setInc($articleId, 'actual_views', $num);
    }

    /**
     * 获取文章列表
     * @param int $categoryId
     * @param int $limit
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(int $categoryId = 0, int $limit = 15)
    {
        // 检索查询条件
        $filter = [];
        $categoryId > 0 && $filter[] = ['category_id', '=', $categoryId];
        // 获取列表数据
        return $this->withoutField(['content'])
            ->with(['image', 'category'])
            ->where($filter)
            ->where('status', '=', 1)
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc', 'create_time' => 'desc'])
            ->paginate($limit);
    }

}