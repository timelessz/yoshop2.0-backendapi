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

namespace app\store\service\statistics\data;

use app\store\model\User as UserModel;
use app\common\service\BaseService;

/**
 * 数据统计-用户消费榜
 * Class UserExpendRanking
 * @package app\store\service\statistics\data
 */
class UserExpendRanking extends BaseService
{
    /**
     * 用户消费榜
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserExpendRanking()
    {
        return (new UserModel)->field(['user_id', 'nick_name', 'expend_money'])
            ->where('is_delete', '=', 0)
            ->order(['expend_money' => 'DESC'])
            ->limit(10)
            ->select();
    }

}