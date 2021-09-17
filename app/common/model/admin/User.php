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

namespace app\common\model\admin;

use app\common\model\BaseModel;

/**
 * 超管后台用户模型
 * Class User
 * @package app\common\model\admin
 */
class User extends BaseModel
{
    // 定义表名
    protected $name = 'admin_user';

    // 定义主键
    protected $pk = 'admin_user_id';

    /**
     * 超管用户信息
     * @param $id
     * @return array|null|static
     */
    public static function detail(int $id)
    {
        return static::get($id);
    }

}