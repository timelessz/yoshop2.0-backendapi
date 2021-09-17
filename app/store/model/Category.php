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

use app\common\model\Category as CategoryModel;

/**
 * 商品分类模型
 * Class Category
 * @package app\store\model
 */
class Category extends CategoryModel
{
    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add($data)
    {
        $data['store_id'] = self::$storeId;
        return $this->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit($data)
    {
        // 判断上级分类是否为当前子级
        if ($data['parent_id'] > 0) {
            // 获取所有上级id集
            $parentIds = $this->getTopCategoryIds($data['parent_id']);
            if (in_array($this['category_id'], $parentIds)) {
                $this->error = '上级分类不允许设置为当前子分类';
                return false;
            }
        }
        // 是否删除图片
        !isset($data['image_id']) && $data['image_id'] = 0;
        return $this->save($data) !== false;
    }

    /**
     * 获取所有上级id集
     * @param int $categoryId
     * @param null|array $list
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getTopCategoryIds(int $categoryId, $list = null)
    {
        static $parentIds = [];
        is_null($list) && $list = $this->getAll();
        foreach ($list as $item) {
            if ($item['category_id'] == $categoryId && $item['parent_id'] > 0) {
                $parentIds[] = $item['parent_id'];
                $this->getTopCategoryIds($item['parent_id'], $list);
            }
        }
        return $parentIds;
    }

    /**
     * 删除记录
     * @return bool
     */
    public function remove()
    {
        // 判断是否存在下级分类
        if (static::detail(['parent_id' => $this['category_id']])) {
            $this->error = '当前分类下存在子分类，不允许删除';
            return false;
        }
        // 判断该分类是否被商品引用
        $goodsCount = GoodsCategoryRel::getCountByCategoryId($this['category_id']);
        if ($goodsCount > 0) {
            $this->error = "该分类被{$goodsCount}个商品引用，不允许删除";
            return false;
        }
        // 删除分类记录
        return $this->delete();
    }

}
