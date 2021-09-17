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

namespace app\admin\model\store;

use app\common\model\store\Api as ApiModel;

/**
 * 商家后台API权限模型
 * Class Api
 * @package app\admin\model\store
 */
class Api extends ApiModel
{
    /**
     * 获取权限列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList()
    {
        $all = static::getAll();
        return $this->getTreeData($all);
    }

    /**
     * 新增记录
     * @param $data
     * @return false|int
     */
    public function add(array $data)
    {
        return $this->allowField(['name', 'parent_id', 'url', 'sort'])->save($data);
    }

    /**
     * 更新记录
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit($data)
    {
        // 判断上级角色是否为当前子级
        if ($data['parent_id'] > 0) {
            // 获取所有上级id集
            $parentIds = $this->getTopApiIds($data['parent_id']);
            if (in_array($this['api_id'], $parentIds)) {
                $this->error = '上级权限不允许设置为当前子权限';
                return false;
            }
        }
        return $this->allowField(['name', 'parent_id', 'url', 'sort'])->save($data) !== false;
    }

    /**
     * 删除权限
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     */
    public function remove()
    {
        // 判断是否存在下级权限
        if (self::detail(['parent_id' => $this['api_id']])) {
            $this->error = '当前权限下存在子权限，请先删除';
            return false;
        }
        return $this->delete();
    }

    /**
     * 获取所有上级id集
     * @param int $apiId
     * @param null $all
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getTopApiIds(int $apiId, $all = null)
    {
        static $ids = [];
        is_null($all) && $all = $this->getAll();
        foreach ($all as $item) {
            if ($item['api_id'] == $apiId && $item['parent_id'] > 0) {
                $ids[] = $item['parent_id'];
                $this->getTopApiIds($item['parent_id'], $all);
            }
        }
        return $ids;
    }

    /**
     * 获取树状列表
     * @param array $list
     * @param int $parentId
     * @return array
     */
    private function getTreeData(array &$list, int $parentId = 0)
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->getTreeData($list, (int)$item['api_id']);
                !empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }
        return $data;
    }

}
