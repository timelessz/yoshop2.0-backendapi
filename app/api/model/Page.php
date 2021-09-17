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

namespace app\api\model;

use app\api\model\Goods as GoodsModel;
use app\api\model\Coupon as CouponModel;
use app\common\model\Page as PageModel;
use app\common\library\helper;

/**
 * 页面模型
 * Class Page
 * @package app\api\model
 */
class Page extends PageModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'store_id',
        'create_time',
        'update_time'
    ];

    /**
     * DIY页面详情
     * @param int|null $pageId 页面ID
     * @return array
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPageData(int $pageId = null)
    {
        // 页面详情
        $detail = $pageId > 0 ? parent::detail($pageId) : parent::getHomePage();
        if (empty($detail)) {
            throwError('很抱歉，未找到该页面');
        }
        // 页面diy元素
        $pageData = $detail['page_data'];
        // 获取动态数据
        foreach ($pageData['items'] as &$item) {
            // 移出默认数据
            if (array_key_exists('defaultData', $item)) {
                unset($item['defaultData']);
            }
            if ($item['type'] === 'window') {
                $item['data'] = array_values($item['data']);
            } else if ($item['type'] === 'goods') {
                $item['data'] = $this->getGoodsList($item);
            } else if ($item['type'] === 'coupon') {
                $item['data'] = $this->getCouponList($item);
            } else if ($item['type'] === 'article') {
                $item['data'] = $this->getArticleList($item);
            } else if ($item['type'] === 'special') {
                $item['data'] = $this->getSpecialList($item);
            }
        }
        return $pageData;
    }

    /**
     * 商品组件：获取商品列表
     * @param $item
     * @return array
     * @throws \think\db\exception\DbException
     */
    private function getGoodsList($item)
    {
        // 获取商品数据
        $model = new GoodsModel;
        if ($item['params']['source'] === 'choice') {
            // 数据来源：手动
            $goodsIds = helper::getArrayColumn($item['data'], 'goods_id');
            if (empty($goodsIds)) return [];
            $goodsList = $model->getListByIdsFromApi($goodsIds);
        } else {
            // 数据来源：自动
            $goodsList = $model->getList([
                'status' => 10,
                'categoryId' => $item['params']['auto']['category'],
                'sortType' => $item['params']['auto']['goodsSort'],
            ], $item['params']['auto']['showNum']);
        }
        if ($goodsList->isEmpty()) return [];
        // 格式化商品列表
        $data = [];
        foreach ($goodsList as $goods) {
            $data[] = [
                'goods_id' => $goods['goods_id'],
                'goods_name' => $goods['goods_name'],
                'selling_point' => $goods['selling_point'],
                'goods_image' => $goods['goods_images'][0]['preview_url'],
                'goods_price_min' => $goods['goods_price_min'],
                'goods_price_max' => $goods['goods_price_max'],
                'line_price_min' => $goods['line_price_min'],
                'line_price_max' => $goods['line_price_max'],
                'goods_sales' => $goods['goods_sales'],
            ];
        }
        return $data;
    }

    /**
     * 优惠券组件：获取优惠券列表
     * @param $item
     * @return \think\Collection
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getCouponList($item)
    {
        // 获取优惠券数据
        return (new CouponModel)->getList($item['params']['limit'], true);
    }

    /**
     * 文章组件：获取文章列表
     * @param $item
     * @return array
     * @throws \think\db\exception\DbException
     */
    private function getArticleList($item)
    {
        // 获取文章数据
        $model = new Article;
        $articleList = $model->getList($item['params']['auto']['category'], $item['params']['auto']['showNum']);
        return $articleList->isEmpty() ? [] : $articleList->toArray()['data'];
    }

    /**
     * 头条快报：获取头条列表
     * @param $item
     * @return array
     * @throws \think\db\exception\DbException
     */
    private function getSpecialList($item)
    {
        // 获取头条数据
        $model = new Article;
        $articleList = $model->getList($item['params']['auto']['category'], $item['params']['auto']['showNum']);
        return $articleList->isEmpty() ? [] : $articleList->toArray()['data'];
    }

}
