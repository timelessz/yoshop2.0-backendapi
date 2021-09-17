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

namespace app\store\controller\user;

use app\store\controller\Controller;
use app\store\model\recharge\Order as OrderModel;

/**
 * 余额记录
 * Class Recharge
 * @package app\store\controller\user
 */
class Recharge extends Controller
{
    /**
     * 充值记录
     * @return mixed
     */
    public function order()
    {
        $model = new OrderModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

}
