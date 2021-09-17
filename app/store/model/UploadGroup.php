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

use app\store\model\UploadFile as UploadFileModel;
use app\common\model\UploadGroup as UploadGroupModel;

/**
 * 文件库分组模型
 * Class UploadGroup
 * @package app\store\model
 */
class UploadGroup extends UploadGroupModel
{
    /**
     * 获取列表记录
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList()
    {
        $list = $this->getAll();
        return $this->getTreeData($list);
    }

    /**
     * 获取所有分组
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getAll()
    {
        return $this->order(['sort', 'create_time'])->select();
    }

    /**
     * 获取树状列表
     * @param $list
     * @param int $parentId
     * @return array
     */
    private function getTreeData(&$list, int $parentId = 0)
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->getTreeData($list, $item['group_id']);
                !empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }
        return $data;
    }

    /**
     * 添加新记录
     * @param array $data
     * @return false|int
     */
    public function add(array $data)
    {
        return $this->save(array_merge([
            'store_id' => self::$storeId,
            'sort' => 100
        ], $data));
    }

    /**
     * 编辑记录
     * @param array $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(array $data)
    {
        // 判断上级分组是否为当前子级
        if ($data['parent_id'] > 0) {
            // 获取所有上级id集
            $parentIds = $this->getTopGroupIds($data['parent_id']);
            if (in_array($this['group_id'], $parentIds)) {
                $this->error = '上级分组不允许设置为当前子分组';
                return false;
            }
        }
        return $this->save($data) !== false;
    }

    /**
     * 获取所有上级id集
     * @param int $groupId
     * @param null|array $list
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getTopGroupIds(int $groupId, $list = null)
    {
        static $parentIds = [];
        is_null($list) && $list = $this->getAll();
        foreach ($list as $item) {
            if ($item['group_id'] == $groupId && $item['parent_id'] > 0) {
                $parentIds[] = $item['parent_id'];
                $this->getTopGroupIds($item['parent_id'], $list);
            }
        }
        return $parentIds;
    }

    /**
     * 删除商品分组
     * @return bool
     * @throws \Exception
     */
    public function remove()
    {
        // 判断是否存在下级分组
        if (static::detail(['parent_id' => $this['group_id']])) {
            $this->error = '当前分组下存在子分组，不允许删除';
            return false;
        }
        // 更新该分组下的所有文件
        UploadFileModel::updateBase(['group_id' => 0], ['group_id' => $this['group_id']]);
        // 删除分组记录
        return $this->delete();
    }

}
