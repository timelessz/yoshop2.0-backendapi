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

namespace app\store\controller\client;

use app\store\controller\Controller;
use app\store\model\Wxapp as WxappModel;

/**
 * 微信小程序管理
 * Class Wxapp
 * @package app\store\controller
 */
class Wxapp extends Controller
{
    /**
     * 微信小程序详情
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function detail()
    {
        // 当前小程序信息
        $detail = WxappModel::detail($this->storeId);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 微信小程序设置
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setting()
    {
        // 当前小程序信息
        $model = WxappModel::detail($this->storeId);
        // 更新小程序设置
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

}
