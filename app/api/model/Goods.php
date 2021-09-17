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

use app\api\service\User as UserService;
use app\api\model\GoodsSpecRel as GoodsSpecRelModel;
use app\common\model\Goods as GoodsModel;
use app\common\enum\goods\Status as GoodsStatusEnum;
use app\common\library\helper;
use app\common\exception\BaseException;

/**
 * 商品模型
 * Class Goods
 * @package app\api\model
 */
class Goods extends GoodsModel
{
    /**
     * 隐藏字段
     * @var array
     */
    public $hidden = [
        'images',
        'delivery',
        'deduct_stock_type',
        'sales_initial',
        'sales_actual',
        'sort',
        'is_delete',
        'store_id',
        'create_time',
        'update_time'
    ];

    /**
     * 商品详情：HTML实体转换回普通字符
     * @param $value
     * @return string
     */
    public function getContentAttr($value)
    {
        return htmlspecialchars_decode((string)$value);
    }

    /**
     * 获取商品列表
     * @param array $param 查询条件
     * @param int $listRows 分页数量
     * @return mixed|\think\model\Collection|\think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param = [], int $listRows = 15)
    {
        // 整理查询参数
        $params = array_merge($param, ['status' => GoodsStatusEnum::ON_SALE]);
        // 获取商品列表
        $list = parent::getList($params, $listRows);
        if ($list->isEmpty()) {
            return $list;
        }
        // 隐藏冗余的字段
        $list->hidden(array_merge($this->hidden, ['content', 'goods_images', 'images']));
        // 整理列表数据并返回
        return $this->setGoodsListDataFromApi($list);
    }

    /**
     * 获取商品详情 (详细数据用于页面展示)
     * @param int $goodsId 商品id
     * @return mixed
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDetails(int $goodsId)
    {
        // 关联查询
        $with = ['images' => ['file'], 'skuList' => ['image']];
        // 获取商品记录
        $goodsInfo = static::detail($goodsId, $with);
        // 判断商品的状态
        if (empty($goodsInfo) || $goodsInfo['is_delete'] || $goodsInfo['status'] != GoodsStatusEnum::ON_SALE) {
            throwError('很抱歉，商品信息不存在或已下架');
        }
        // 设置商品展示的数据
        $goodsInfo = $this->setGoodsDataFromApi($goodsInfo);
        // 商品规格列表
        $goodsInfo['specList'] = GoodsSpecRelModel::getSpecList($goodsInfo['goods_id']);
        return $goodsInfo;
    }

    /**
     * 根据商品id集获取商品列表
     * @param array $goodsIds
     * @return mixed
     */
    public function getListByIdsFromApi(array $goodsIds)
    {
        // 获取商品列表
        $data = $this->getListByIds($goodsIds, GoodsStatusEnum::ON_SALE);
        // 整理列表数据并返回
        return $this->setGoodsListDataFromApi($data);
    }

    /**
     * 设置商品展示的数据 api模块
     * @param $data
     * @return mixed
     */
    private function setGoodsListDataFromApi($data)
    {
        return $this->setGoodsListData($data, function ($goods) {
            // 计算并设置商品会员价
            $this->setGoodsDataFromApi($goods);
        });
    }

    /**
     * 整理商品数据 api模块
     * @param $goodsInfo
     * @return mixed
     */
    private function setGoodsDataFromApi($goodsInfo)
    {
        return $this->setGoodsData($goodsInfo, function ($goods) {
            // 计算并设置商品会员价
            $this->setGoodsGradeMoney($goods);
        });
    }

    /**
     * 设置商品的会员价
     * @param Goods $goods
     * @throws BaseException
     */
    private function setGoodsGradeMoney(self $goods)
    {
        // 获取当前登录的用户信息
        $userInfo = UserService::getCurrentLoginUser();
        // 会员等级状态
        $gradeStatus = (!empty($userInfo) && $userInfo['grade_id'] > 0 && !empty($userInfo['grade']))
            && (!$userInfo['grade']['is_delete'] && $userInfo['grade']['status']);
        // 判断商品是否参与会员折扣
        if (!$gradeStatus || !$goods['is_enable_grade']) {
            $goods['is_user_grade'] = false;
            return;
        }
        // 商品单独设置了会员折扣
        if ($goods['is_alone_grade'] && isset($goods['alone_grade_equity'][$userInfo['grade_id']])) {
            // 折扣比例
            $discountRatio = helper::bcdiv($goods['alone_grade_equity'][$userInfo['grade_id']], 10);
        } else {
            // 折扣比例
            $discountRatio = helper::bcdiv($userInfo['grade']['equity']['discount'], 10);
        }
        if ($discountRatio > 0) {
            // 标记参与会员折扣
            $goods['is_user_grade'] = true;
            // 会员折扣价
            foreach ($goods['skuList'] as &$skuItem) {
                $skuItem['goods_price'] = helper::number2(helper::bcmul($skuItem['goods_price'], $discountRatio), true);
            }
        }
    }

}
