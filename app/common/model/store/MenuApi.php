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
 * 商家后台用户角色与菜单权限关系表模型
 * Class MenuApi
 * @package app\common\model\store
 */
class MenuApi extends BaseModel
{
    // 定义表名
    protected $name = 'store_menu_api';

    // 定义表主键
    protected $pk = 'id';

    protected $updateTime = false;

    // 不允许全局查询store_id
    protected $isGlobalScopeStoreId = false;

}
