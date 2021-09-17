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
use app\api\model\recharge\Order as OrderModel;
use app\common\exception\BaseException;

/**
 * 充值记录管理
 * Class Order
 * @package app\api\controller\recharge
 */
class Order extends Controller
{
    /**
     * 我的充值记录列表
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new OrderModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

}