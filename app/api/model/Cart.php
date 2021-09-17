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

namespace app\api\model;

use app\api\model\Goods as GoodsModel;
use app\api\model\GoodsSku as GoodsSkuModel;
use app\api\service\User as UserService;
use app\common\model\Cart as CartModel;
use app\common\enum\goods\Status as GoodsStatusEnum;
use app\common\exception\BaseException;

/**
 * 购物车管理
 * Class Cart
 * @package app\api\model
 */
class Cart extends CartModel
{
    /**
     * 加入购物车
     * @param int $goodsId 商品ID
     * @param string $goodsSkuId 商品sku唯一标识
     * @param int $goodsNum 商品数量
     * @return bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function add(int $goodsId, string $goodsSkuId, int $goodsNum)
    {
        // 判断是否已存在购物车记录
        $detail = $this->getInfo($goodsId, $goodsSkuId, false);
        // 如果已存在购物车记录, 则累计商品数量
        !empty($detail) && $goodsNum += $detail['goods_num'];
        // 验证商品的状态
        $this->checkGoodsStatus($goodsId, $goodsSkuId, $goodsNum);
        // 获取当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 实例化模型
        $model = $detail ?: (new static);
        return $model->save([
            'goods_id' => $goodsId,
            'goods_sku_id' => $goodsSkuId,
            'goods_num' => $goodsNum,
            'user_id' => $userId,
            'store_id' => self::$storeId,
        ]);
    }

    /**
     * 更新购物车记录
     * @param int $goodsId 商品ID
     * @param string $goodsSkuId 商品sku唯一标识
     * @param int $goodsNum 商品数量
     * @return bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sUpdate(int $goodsId, string $goodsSkuId, int $goodsNum)
    {
        // 验证商品的状态
        $this->checkGoodsStatus($goodsId, $goodsSkuId, $goodsNum);
        // 获取购物车记录
        $model = $this->getInfo($goodsId, $goodsSkuId, true);
        // 更新记录
        return $model->save(['goods_num' => $goodsNum]);
    }

    /**
     * 验证商品的状态
     * @param int $goodsId 商品ID
     * @param string $goodsSkuId 商品sku唯一标识
     * @param int $goodsNum 商品数量
     * @return bool
     * @throws BaseException
     */
    private function checkGoodsStatus(int $goodsId, string $goodsSkuId, int $goodsNum)
    {
        // 获取商品详情
        $goods = GoodsModel::detail($goodsId);
        // 商品不存在
        if (empty($goods) || $goods['is_delete']) {
            throwError('很抱歉, 商品信息不存在');
        }
        // 商品已下架
        if ($goods['status'] == GoodsStatusEnum::OFF_SALE) {
            throwError('很抱歉, 该商品已经下架');
        }
        // 获取SKU信息
        $skuInfo = GoodsSkuModel::detail($goodsId, $goodsSkuId);
        if ($skuInfo['stock_num'] < $goodsNum) {
            throwError('很抱歉, 该商品库存数量不足');
        }
        return true;
    }

    /**
     * 获取购物车记录
     * @param int $goodsId 商品ID
     * @param string $goodsSkuId 商品sku唯一标识
     * @param bool $isForce
     * @return static|bool
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getInfo(int $goodsId, string $goodsSkuId, bool $isForce = true)
    {
        // 获取当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 获取购物车记录
        $model = static::detail($userId, $goodsId, $goodsSkuId);
        if (empty($model)) {
            $isForce && throwError('购物车中没有该记录');
            return false;
        }
        return $model;
    }

    /**
     * 删除购物车中指定记录
     * @param array $cartIds 购物车ID集, 如果为空删除所有
     * @return bool
     * @throws BaseException
     */
    public function clear(array $cartIds = [])
    {
        // 获取当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 设置更新条件
        $where = [['user_id', '=', $userId]];
        // 购物车ID集
        !empty($cartIds) && $where[] = ['id', 'in', $cartIds];
        // 更新记录
        return $this->updateBase(['is_delete' => 1], $where);
    }

    /**
     * 获取当前用户购物车商品总数量
     * @return float
     * @throws BaseException
     */
    public function getCartTotal()
    {
        if (!UserService::isLogin()) return 0;
        $userId = UserService::getCurrentLoginUserId();
        return $this->where('user_id', '=', $userId)
            ->where('is_delete', '=', 0)
            ->sum('goods_num');
    }

}
