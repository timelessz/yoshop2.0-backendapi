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

namespace app\api\service;

use app\api\model\Cart as CartModel;
use app\api\model\Goods as GoodsModel;
use app\api\model\GoodsSku as GoodsSkuModel;
use app\api\service\User as UserService;
use app\common\exception\BaseException;
use app\common\library\helper;
use app\common\service\BaseService;

/**
 * 服务类: 购物车
 * Class Cart
 * @package app\api\service
 */
class Cart extends BaseService
{
    /**
     * 购物车商品列表(用于购物车页面)
     * @param array $cartIds 购物车记录ID集
     * @return array|\think\Collection
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList(array $cartIds = [])
    {
        // 购物车列表
        $cartList = $this->getCartList($cartIds);
        // 整理商品ID集
        $goodsIds = helper::getArrayColumn($cartList, 'goods_id');
        if (empty($goodsIds)) return [];
        // 获取商品列表
        $goodsList = $this->getGoodsListByIds($goodsIds);
        // 整理购物车商品列表
        foreach ($cartList as $cartIdx => $item) {
            // 查找商品, 商品不存在则删除该购物车记录
            $result = $this->findGoods($goodsList, $item);
            if ($result !== false) {
                $cartList[$cartIdx]['goods'] = $result;
            } else {
                $this->clear([$item['id']]);
                unset($cartList[$cartIdx]);
            }
        }
        return $cartList;
    }

    /**
     * 获取购物车商品列表(用于订单结算台)
     * @param array $cartIds 购物车记录ID集
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderGoodsList(array $cartIds = [])
    {
        // 购物车列表
        $cartList = $this->getList($cartIds);
        // 订单商品列表
        $goodsList = [];
        foreach ($cartList as $item) {
            // 商品记录
            $goods = $item['goods'];
            // 商品单价
            $goods['goods_price'] = $goods['skuInfo']['goods_price'];
            // 商品购买数量
            $goods['total_num'] = $item['goods_num'];
            // 商品SKU索引
            $goods['goods_sku_id'] = $item['goods_sku_id'];
            // 商品购买总金额
            $goods['total_price'] = helper::bcmul($goods['goods_price'], $item['goods_num']);
            $goodsList[] = $goods;
        }
        return $goodsList;
    }

    /**
     * 检索查询商品
     * @param mixed $goodsList 商品列表
     * @param CartModel $item 购物车记录
     * @return false|mixed
     */
    private function findGoods($goodsList, CartModel $item)
    {
        // 查找商品记录
        $result = helper::getArrayItemByColumn($goodsList, 'goods_id', $item['goods_id']);
        if (empty($result)) {
            return false;
        }
        // 获取当前选择的商品SKU信息
        $result['skuInfo'] = GoodsSkuModel::detail($result['goods_id'], $item['goods_sku_id']);
        // 这里需要用到clone, 因对象是引用传递 后面的值会覆盖前面的
        return clone $result;
    }

    /**
     * 删除购物车中指定记录
     * @param array $cartIds
     * @return bool
     * @throws BaseException
     */
    public function clear(array $cartIds = [])
    {
        $model = new CartModel;
        return $model->clear($cartIds);
    }

    /**
     * 根据商品ID集获取商品列表
     * @param array $goodsIds
     * @return mixed
     */
    private function getGoodsListByIds(array $goodsIds)
    {
        $model = new GoodsModel;
        return $model->getListByIdsFromApi($goodsIds);
    }

    /**
     * 获取当前用户的购物车记录
     * @param array $cartIds 购物车记录ID集
     * @return \think\Collection
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getCartList(array $cartIds = [])
    {
        // 当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 购物车记录模型
        $model = new CartModel;
        // 检索查询条件
        $filter = [];
        if (!empty($cartIds)) {
            $filter[] = ['id', 'in', $cartIds];
        }
        // 查询列表记录
        return $model->where($filter)
            ->where('user_id', '=', $userId)
            ->where('is_delete', '=', 0)
            ->select();
    }


}