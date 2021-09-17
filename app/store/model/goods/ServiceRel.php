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

namespace app\store\model\goods;

use app\common\model\goods\ServiceRel as ServiceRelModel;

/**
 * 商品服务与承诺模型
 * Class ServiceRel
 */
class ServiceRel extends ServiceRelModel
{
    /**
     * 根据服务ID获取记录总数量
     * @param int $serviceId
     * @return int
     */
    public static function getCountByServiceId(int $serviceId)
    {
        return (new static)->where('service_id', '=', $serviceId)->count();
    }

    /**
     * 批量写入商品服务记录
     * @param int $goodsId
     * @param array $serviceIds
     * @return array|false
     */
    public static function increased(int $goodsId, array $serviceIds)
    {
        $dataset = [];
        foreach ($serviceIds as $serviceId) {
            $dataset[] = [
                'service_id' => $serviceId,
                'goods_id' => $goodsId,
                'store_id' => self::$storeId
            ];
        }
        return (new static)->addAll($dataset);
    }

    /**
     * 更新关系记录
     * @param $goodsId
     * @param array $serviceIds 新的服务集
     * @return array|false
     * @throws \Exception
     */
    public static function updates(int $goodsId, array $serviceIds)
    {
        // 已分配的服务集
        $assignServiceIds = self::getServiceIds($goodsId);
        // 找出删除的服务
        $deleteServiceIds = array_diff($assignServiceIds, $serviceIds);
        if (!empty($deleteServiceIds)) {
            static::deleteAll([
                ['goods_id', '=', $goodsId],
                ['service_id', 'in', $deleteServiceIds]
            ]);
        }
        // 找出添加的服务
        $newServiceIds = array_diff($serviceIds, $assignServiceIds);
        $dataset = [];
        foreach ($newServiceIds as $serviceId) {
            $dataset[] = [
                'goods_id' => $goodsId,
                'service_id' => $serviceId,
                'store_id' => self::$storeId,
            ];
        }
        return (new static)->addAll($dataset);
    }

}
