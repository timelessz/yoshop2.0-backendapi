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

namespace app\common\model\store;

use app\common\model\BaseModel;

/**
 * 商家用户角色模型
 * Class Role
 * @package app\common\model\admin
 */
class Role extends BaseModel
{
    // 定义表名
    protected $name = 'store_role';

    // 定义主键
    protected $pk = 'role_id';

    /**
     * 关联操作权限
     * @return \think\model\relation\HasMany
     */
    public function roleMenu()
    {
        return $this->hasMany('RoleMenu', 'role_id');
    }

    /**
     * 角色信息
     * @param $where
     * @return array|null|static
     */
    public static function detail($where)
    {
        return static::get($where);
    }

}
