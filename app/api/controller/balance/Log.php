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

namespace app\api\controller\balance;

use app\api\controller\Controller;
use app\api\model\user\BalanceLog as BalanceLogModel;

/**
 * 余额账单明细
 * Class Log
 * @package app\api\controller\balance
 */
class Log extends Controller
{
    /**
     * 余额账单明细列表
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new BalanceLogModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

}