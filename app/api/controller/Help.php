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

namespace app\api\controller;

use app\api\model\Help as HelpModel;

/**
 * 帮助中心
 * Class help
 * @package app\store\controller\wxapp
 */
class Help extends Controller
{
    /**
     * 获取列表记录
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new HelpModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

}
