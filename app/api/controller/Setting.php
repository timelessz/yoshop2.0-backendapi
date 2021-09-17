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

use app\api\service\Setting as SettingService;

/**
 * 商城设置控制器
 * Class Setting
 * @package app\store\controller
 */
class Setting extends Controller
{
    /**
     * 商城公共设置(仅展示可公开的信息)
     * @return array|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function data()
    {
        $service = new SettingService;
        $setting = $service->getPublic();
        return $this->renderSuccess(compact('setting'));
    }
}
