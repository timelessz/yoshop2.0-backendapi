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

namespace app\console\model\user;

use app\common\model\user\Grade as GradeModel;

/**
 * 用户会员等级模型
 * Class Grade
 * @package app\console\model\user
 */
class Grade extends GradeModel
{
    /**
     * 获取可用的会员等级列表
     * @param int $storeId
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getUsableList(int $storeId)
    {
        return (new static)->where('status', '=', 1)
            ->where('is_delete', '=', 0)
            ->where('store_id', '=', $storeId)
            ->order(['weight' => 'desc'])
            ->select();
    }

}