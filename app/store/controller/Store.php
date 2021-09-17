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

namespace app\store\controller;

use app\store\model\Store as StoreModel;

/**
 * 商家中心控制器
 * Class Store
 * @package app\store\controller
 */
class Store extends Controller
{
    /**
     * 获取当前登录的商城信息
     * @return array
     */
    public function info()
    {
        // 商城详情
        $model = StoreModel::detail($this->getStoreId());
        return $this->renderSuccess(['storeInfo' => $model]);
    }

    /**
     * 更新商城信息
     * @return array
     */
    public function update()
    {
        // 商城详情
        $model = StoreModel::detail($this->getStoreId());
        // 更新记录
        if (!$model->edit($this->postForm())) {
            return $this->renderError($model->getError() ?: '更新失败');
        }
        return $this->renderSuccess('更新成功');
    }

}
