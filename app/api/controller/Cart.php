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

use app\api\model\Cart as CartModel;
use app\api\service\Cart as CartService;
use app\common\exception\BaseException;

/**
 * 购物车管理
 * Class Cart
 * @package app\api\controller
 */
class Cart extends Controller
{
    /**
     * 购物车商品列表
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        // 购物车商品列表
        $service = new CartService;
        $list = $service->getList();
        // 购物车商品总数量
        $cartTotal = (new CartModel)->getCartTotal();
        return $this->renderSuccess(compact('cartTotal', 'list'));
    }

    /**
     * 购物车商品总数量
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function total() {
        $model = new CartModel;
        $cartTotal = $model->getCartTotal();
        return $this->renderSuccess(compact('cartTotal'));
    }

    /**
     * 加入购物车
     * @param int $goodsId 商品id
     * @param string $goodsSkuId 商品sku索引
     * @param int $goodsNum 商品数量
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add(int $goodsId, string $goodsSkuId, int $goodsNum)
    {
        $model = new CartModel;
        if (!$model->add($goodsId, $goodsSkuId, $goodsNum)) {
            return $this->renderError($model->getError() ?: '加入购物车失败');
        }
        // 购物车商品总数量
        $cartTotal = $model->getCartTotal();
        return $this->renderSuccess(compact('cartTotal'), '加入购物车成功');
    }

    /**
     * 更新购物车商品数量
     * @param int $goodsId 商品id
     * @param string $goodsSkuId 商品sku索引
     * @param int $goodsNum 商品数量
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function update(int $goodsId, string $goodsSkuId, int $goodsNum)
    {
        $model = new CartModel;
        if (!$model->sUpdate($goodsId, $goodsSkuId, $goodsNum)) {
            return $this->renderError($model->getError() ?: '更新失败');
        }
        // 购物车商品总数量
        $cartTotal = $model->getCartTotal();
        return $this->renderSuccess(compact('cartTotal'), '更新成功');
    }

    /**
     * 删除购物车中指定记录
     * @param array $cartIds 购物车ID集, 如果为空删除所有
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function clear(array $cartIds = [])
    {
        $model = new CartModel;
        if (!$model->clear($cartIds)) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        // 购物车商品总数量
        $cartTotal = $model->getCartTotal();
        return $this->renderSuccess(compact('cartTotal'), '操作成功');
    }

}
