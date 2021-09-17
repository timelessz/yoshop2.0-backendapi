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

namespace app\common\enum\user\grade\log;

use app\common\enum\EnumBasics;

/**
 * 会员等级变更记录表 -> 变更类型
 * Class ChangeType
 * @package app\common\enum\user\grade\log
 */
class ChangeType extends EnumBasics
{
    // 后台管理员设置
    const ADMIN_USER = 10;

    // 自动升级
    const AUTO_UPGRADE = 20;

}