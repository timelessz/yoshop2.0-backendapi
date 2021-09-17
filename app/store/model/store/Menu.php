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

use app\common\model\store\Menu as MenuModel;

/**
 * 商家后台菜单模型
 * Class Menu
 * @package app\store\model\store
 */
class Menu extends MenuModel
{
    // 隐藏的字段
    protected $hidden = [
        'action_mark',
        'sort',
        'create_time',
        'update_time'
    ];

    /**
     * 根据菜单ID集获取列表
     * @param array $menuIds
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getListByIds(array $menuIds)
    {
        // 菜单列表
        $list = static::getAll([['menu_id', 'in', $menuIds]]);
        // 整理菜单绑定的apiID集
        return (new static)->getTreeData($list);
    }

}
