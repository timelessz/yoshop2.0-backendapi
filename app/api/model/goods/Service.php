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

namespace app\api\model\goods;

use app\common\model\goods\Service as ServiceModel;
use app\api\model\goods\ServiceRel as ServiceRelModel;

/**
 * 商品服务与承诺模型
 * Class Service
 */
class Service extends ServiceModel
{
    // 隐藏的字段
    protected $hidden = [
        'is_default',
        'status',
        'sort',
        'is_delete',
        'store_id',
        'update_time',
    ];

    /**
     * 获取指定商品的服务与承诺
     * @param int $goodsId
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getListByGoods(int $goodsId)
    {
        // 获取指定商品的服务承诺id集
        $serviceIds = ServiceRelModel::getServiceIds($goodsId);
        // 获取服务与承诺列表
        return $this->where('service_id', 'in', $serviceIds)
            ->where('status', '=', 1)
            ->where('is_delete', '=', 0)
            ->order(['sort', $this->getPk()])
            ->select();
    }

}
