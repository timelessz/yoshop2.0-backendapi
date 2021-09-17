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

namespace app\store\controller\setting;

use app\store\controller\Controller;
use app\store\model\Delivery as DeliveryModel;

/**
 * 配送设置
 * Class Delivery
 * @package app\store\controller\setting
 */
class Delivery extends Controller
{
    /**
     * 配送模板列表
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new DeliveryModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取所有记录
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function all()
    {
        $model = new DeliveryModel;
        $list = $model->getAll();
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取详情记录
     * @param int $deliveryId
     * @return array
     */
    public function detail(int $deliveryId)
    {
        $detail = DeliveryModel::detail($deliveryId, ['rule']);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 添加配送模板
     * @return array
     */
    public function add()
    {
        // 新增记录
        $model = new DeliveryModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑配送模板
     * @param int $deliveryId
     * @return array
     */
    public function edit(int $deliveryId)
    {
        // 模板详情
        $model = DeliveryModel::detail($deliveryId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除模板
     * @param int $deliveryId
     * @return array
     */
    public function delete(int $deliveryId)
    {
        // 模板详情
        $model = DeliveryModel::detail($deliveryId);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
