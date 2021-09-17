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

use app\common\exception\BaseException;
use app\store\model\Goods as GoodsModel;

/**
 * 商品管理控制器
 * Class Goods
 * @package app\store\controller
 */
class Goods extends Controller
{
    /**
     * 商品列表
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        // 获取列表记录
        $model = new GoodsModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 根据商品ID集获取列表记录
     * @param array $goodsIds
     * @return array
     */
    public function listByIds(array $goodsIds)
    {
        // 获取列表记录
        $model = new GoodsModel;
        $list = $model->getListByIds($goodsIds);
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 商品详情
     * @param int $goodsId
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function detail(int $goodsId)
    {
        // 获取商品详情
        $model = new GoodsModel;
        $goodsInfo = $model->getDetail($goodsId);
        return $this->renderSuccess(compact('goodsInfo'));
    }

    /**
     * 添加商品
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add()
    {
        $model = new GoodsModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑商品
     * @param int $goodsId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(int $goodsId)
    {
        // 商品详情
        $model = GoodsModel::detail($goodsId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 修改商品状态(上下架)
     * @param array $goodsIds 商品id集
     * @param bool $state 为true表示上架
     * @return array
     */
    public function state(array $goodsIds, bool $state)
    {
        $model = new GoodsModel;
        if (!$model->setStatus($goodsIds, $state)) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 删除商品
     * @param array $goodsIds
     * @return array
     */
    public function delete(array $goodsIds)
    {
        $model = new GoodsModel;
        if (!$model->setDelete($goodsIds)) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }
}
