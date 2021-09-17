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

namespace app\api\controller\recharge;

use app\api\controller\Controller;
use app\api\model\recharge\Plan as PlanModel;
use app\common\exception\BaseException;

/**
 * 充值套餐管理
 * Class Plan
 * @package app\api\controller\recharge
 */
class Plan extends Controller
{
    /**
     * 充值套餐列表
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new PlanModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

}