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
declare (strict_types=1);

namespace app\admin\controller;

use app\admin\model\Store as StoreModel;

/**
 * 小程序商城管理
 * Class Store
 * @package app\admin\controller
 */
class Store extends Controller
{
    /**
     * 强制验证当前访问的控制器方法method
     * @var array
     */
    protected $methodRules = [
        'index' => 'GET',
        'recycle' => 'GET',
        'add' => 'POST',
        'move' => 'POST',
        'delete' => 'POST',
    ];

    /**
     * 小程序商城列表
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function index()
    {
        // 商城列表
        $model = new StoreModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 回收站列表
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function recycle()
    {
        // 商城列表
        $model = new StoreModel;
        $list = $model->getList(true);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 回收小程序
     * @param int $storeId
     * @return array
     */
    public function recovery(int $storeId)
    {
        // 小程序详情
        $model = StoreModel::detail($storeId);
        if (!$model->recycle()) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 移出回收站
     * @param int $storeId
     * @return array
     */
    public function move(int $storeId)
    {
        // 小程序详情
        $model = StoreModel::detail($storeId);
        if (!$model->recycle(false)) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    public function add()
    {
        return $this->renderError('很抱歉，免费版暂不支持多开商城');
    }

}
