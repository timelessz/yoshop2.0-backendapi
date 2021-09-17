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

use app\common\model\store\Api as ApiModel;
use app\common\library\helper;

/**
 * 商家用户权限模型
 * Class Api
 * @package app\store\model\store
 */
class Api extends ApiModel
{
    /**
     * 获取权限列表 jstree格式
     * @param int $role_id 当前角色id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getJsTree($role_id = null)
    {
        $apiIds = is_null($role_id) ? [] : RoleAccess::getAccessIds($role_id);
        $jsTree = [];
        foreach ($this->getAll() as $item) {
            $jsTree[] = [
                'id' => $item['api_id'],
                'parent' => $item['parent_id'] > 0 ? $item['parent_id'] : '#',
                'text' => $item['name'],
                'state' => [
                    'selected' => (in_array($item['api_id'], $apiIds) && !$this->hasChildren($item['api_id']))
                ]
            ];
        }
        return helper::jsonEncode($jsTree);
    }

    /**
     * 是否存在子集
     * @param $api_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function hasChildren($api_id)
    {
        foreach (self::getAll() as $item) {
            if ($item['parent_id'] == $api_id)
                return true;
        }
        return false;
    }

}