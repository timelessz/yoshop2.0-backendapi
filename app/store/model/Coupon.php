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

use app\common\model\Coupon as CouponModel;
use app\common\enum\coupon\CouponType as CouponTypeEnum;
use app\common\enum\coupon\ExpireType as ExpireTypeEnum;
use app\common\enum\coupon\ApplyRange as ApplyRangeEnum;

/**
 * 优惠券模型
 * Class Coupon
 * @package app\store\model
 */
class Coupon extends CouponModel
{
    /**
     * 获取列表记录
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param = [])
    {
        // 检索查询条件
        $filter = $this->getFilter($param);
        // 查询列表数据
        return $this->where($filter)
            ->where('is_delete', '=', 0)
            ->order(['sort', 'create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 检索查询条件
     * @param array $param
     * @return array
     */
    private function getFilter(array $param = []): array
    {
        // 默认查询条件
        $param = $this->setQueryDefaultValue($param, ['search' => '']);
        // 检索查询条件
        $filter = [];
        !empty($param['search']) && $filter[] = ['name', 'like', "%{$param['search']}%"];
        return $filter;
    }

    /**
     * 添加新记录
     * @param array $data
     * @return false|int
     */
    public function add(array $data)
    {
        $data['store_id'] = self::$storeId;
        return $this->save($this->createData($data));
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool|int
     */
    public function edit(array $data)
    {
        return $this->save($this->createData($data)) !== false;
    }

    /**
     * 创建数据
     * @param array $data
     * @return array
     */
    private function createData(array $data): array
    {
        // 折扣券记录有效期
        // 领取后生效
        if ($data['expire_type'] == ExpireTypeEnum::RECEIVE) {
            $data['start_time'] = $data['end_time'] = 0;
        } // 固定时间
        elseif ($data['expire_type'] == ExpireTypeEnum::FIXED_TIME) {
            $times = between_time($data['betweenTime']);
            $data['start_time'] = $times['start_time'];
            $data['end_time'] = $times['end_time'];
            $data['expire_day'] = 0;
        }
        // 适用范围
        if ($data['apply_range'] == ApplyRangeEnum::ALL) {
            $data['apply_range_config'] = [];
        }
        return $data;
    }

    /**
     * 删除记录 (软删除)
     * @return bool|int
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]) !== false;
    }

}
