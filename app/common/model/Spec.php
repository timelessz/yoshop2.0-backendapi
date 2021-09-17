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

namespace app\common\model;

/**
 * 规格/属性(组)模型
 * Class Spec
 * @package app\common\model
 */
class Spec extends BaseModel
{
    // 定义表名
    protected $name = 'spec';

    // 定义主键
    protected $pk = 'spec_id';

    protected $updateTime = false;

}
