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

namespace app\api\model;

use app\common\model\Express as ExpressModel;

/**
 * 物流公司模型
 * Class Express
 * @package app\api\model
 */
class Express extends ExpressModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'kuaidi100_code',
        'sort',
        'store_id',
        'create_time',
        'update_time'
    ];

}