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
use app\store\model\Express as ExpressModel;

/**
 * 物流公司
 * Class Express
 * @package app\store\controller\setting
 */
class Express extends Controller
{
    /**
     * 物流公司列表
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new ExpressModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取全部记录
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function all()
    {
        $model = new ExpressModel;
        $list = $model->getAll($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 删除物流公司
     * @param int $expressId
     * @return array
     * @throws \Exception
     */
    public function delete(int $expressId)
    {
        $model = ExpressModel::detail($expressId);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

    /**
     * 添加物流公司
     * @return array|mixed
     */
    public function add()
    {
        // 新增记录
        $model = new ExpressModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑物流公司
     * @param $expressId
     * @return array|mixed
     */
    public function edit(int $expressId)
    {
        // 模板详情
        $model = ExpressModel::detail($expressId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

}
