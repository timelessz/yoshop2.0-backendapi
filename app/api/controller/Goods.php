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

use app\api\model\Goods as GoodsModel;

/**
 * 商品控制器
 * Class Goods
 * @package app\api\controller
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
        // 获取列表数据
        $model = new GoodsModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取商品详情
     * @param int $goodsId
     * @return array|\think\response\Json
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function detail(int $goodsId)
    {
        // 商品详情
        $model = new GoodsModel;
        $goodsInfo = $model->getDetails($goodsId);
        return $this->renderSuccess(['detail' => $goodsInfo]);
    }

}
