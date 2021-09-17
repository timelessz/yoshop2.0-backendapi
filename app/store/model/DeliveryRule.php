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

namespace app\store\model;

use app\common\model\DeliveryRule as DeliveryRuleModel;

/**
 * 配送模板区域及运费模型
 * Class DeliveryRule
 * @package app\store\model
 */
class DeliveryRule extends DeliveryRuleModel
{
    /**
     * 批量写入记录
     * @param int $deliveryId
     * @param array $rules
     * @return array|false
     */
    public static function increased(int $deliveryId, array $rules)
    {
        $dataset = [];
        foreach ($rules as $ruleItem) {
            $dataset[] = [
                'delivery_id' => $deliveryId,
                'region' => $ruleItem['region'],
                'region_text' => $ruleItem['region_text'],
                'first' => $ruleItem['first'],
                'first_fee' => $ruleItem['first_fee'],
                'additional' => $ruleItem['additional'],
                'additional_fee' => $ruleItem['additional_fee'],
                'store_id' => self::$storeId
            ];
        }
        return (new static)->addAll($dataset);
    }

    /**
     * 更新关系记录
     * @param $deliveryId
     * @param array $rules 新的图片集
     * @return array|false
     * @throws \Exception
     */
    public static function updates(int $deliveryId, array $rules)
    {
        // 删除所有的记录
        static::deleteAll(['delivery_id' => $deliveryId]);
        // 批量写入记录
        return static::increased($deliveryId, $rules);
    }

}
