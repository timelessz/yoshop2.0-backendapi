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

use app\store\model\store\Address as AddressModel;
use app\common\model\OrderRefundAddress as OrderRefundAddressModel;

/**
 * 售后单退货地址模型
 * Class OrderRefundAddress
 * @package app\store\model
 */
class OrderRefundAddress extends OrderRefundAddressModel
{
    /**
     * 新增售后单退货地址记录
     * @param int $orderRefundId
     * @param int $storeAddressId
     * @return bool
     */
    public function add(int $orderRefundId, int $storeAddressId)
    {
        // 获取地址详情
        $address = AddressModel::detail($storeAddressId);
        // 新增退货地址记录
        return $this->save([
            'order_refund_id' => $orderRefundId,
            'name' => $address['name'],
            'phone' => $address['phone'],
            'province_id' => $address['province_id'],
            'city_id' => $address['city_id'],
            'region_id' => $address['region_id'],
            'detail' => $address['detail'],
            'store_id' => self::$storeId
        ]);
    }

}