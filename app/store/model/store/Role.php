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

namespace app\store\model\store;

use app\common\library\helper;
use app\common\model\store\Role as RoleModel;
use app\store\model\store\RoleMenu as RoleMenuModel;

/**
 * 商家用户角色模型
 * Class Role
 * @package app\store\model\store
 */
class Role extends RoleModel
{
    /**
     * 获取角色列表
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
     * 获取所有角色
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getAll()
    {
        // 获取列表数据
        $list = $this->addHidden(['roleMenu'])
            ->with(['roleMenu'])
            ->order(['sort' => 'asc', 'create_time' => 'asc'])
            ->select();
        // 整理角色的菜单ID集
        return $this->getRoleMenuIds($list);
    }

    /**
     * 整理菜单的api ID集
     * @param $list
     * @return mixed
     */
    private function getRoleMenuIds($list)
    {
        foreach ($list as &$item) {
            if (!empty($item['roleMenu'])) {
                $item['menuIds'] = helper::getArrayColumn($item['roleMenu'], 'menu_id');
            }
        }
        return $list;
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
                $children = $this->getTreeData($list, $item['role_id']);
                !empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }
        return $data;
    }

    /**
     * 新增记录
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        if (empty($data['menus'])) {
            $this->error = '菜单权限不能为空，请重新选择';
            return false;
        }
        $this->transaction(function () use ($data) {
            // 新增角色记录
            $data['store_id'] = self::$storeId;
            $this->save($data);
            // 新增角色菜单关系记录
            RoleMenuModel::increased((int)$this['role_id'], $data['menus']);
        });
        return true;
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(array $data)
    {
        if (empty($data['menus'])) {
            $this->error = '菜单权限不能为空，请重新选择';
            return false;
        }
        // 判断上级角色是否为当前子级
        if ($data['parent_id'] > 0) {
            // 获取所有上级id集
            $parentIds = $this->getTopRoleIds($data['parent_id']);
            if (in_array($this['role_id'], $parentIds)) {
                $this->error = '上级角色不允许设置为当前子角色';
                return false;
            }
        }
        $this->transaction(function () use ($data) {
            // 更新角色记录
            $this->allowField(['role_name', 'parent_id', 'sort'])->save($data);
            // 更新角色菜单关系记录
            RoleMenuModel::updates((int)$this['role_id'], $data['menus']);
        });
        return true;
    }

    /**
     * 获取所有上级id集
     * @param int $roleId
     * @param null|array $list
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getTopRoleIds(int $roleId, $list = null)
    {
        static $parentIds = [];
        is_null($list) && $list = $this->getAll();
        foreach ($list as $item) {
            if ($item['role_id'] == $roleId && $item['parent_id'] > 0) {
                $parentIds[] = $item['parent_id'];
                $this->getTopRoleIds($item['parent_id'], $list);
            }
        }
        return $parentIds;
    }

    /**
     * 删除记录
     * @return bool
     * @throws \Exception
     */
    public function remove()
    {
        // 判断是否存在下级角色
        if (static::detail(['parent_id' => $this['role_id']])) {
            $this->error = '当前角色下存在子角色，不允许删除';
            return false;
        }
        // 判断当前角色下存在用户
        if (UserRole::isExistsUserByRoleId($this['role_id'])) {
            $this->error = '当前角色下存在用户，不允许删除';
            return false;
        }
        // 删除对应的菜单关系
        RoleMenuModel::deleteAll(['role_id' => $this['role_id']]);
        return $this->delete();
    }

}
