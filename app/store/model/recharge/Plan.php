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

namespace app\store\model\recharge;

use app\common\model\recharge\Plan as PlanModel;

/**
 * 会员充值套餐模型
 * Class Plan
 * @package app\store\model\recharge
 */
class Plan extends PlanModel
{
    /**
     * 列表记录
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
            ->order(['sort', $this->getPk()])
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
        $params = $this->setQueryDefaultValue($param, ['search' => '']);
        // 检索查询条件
        $filter = [];
        !empty($params['search']) && $filter[] = ['plan_name', 'like', "%{$params['search']}%"];
        return $filter;
    }

    /**
     * 新增记录
     * @param array $data
     * @return false|int
     */
    public function add(array $data): bool
    {
        $data['store_id'] = self::$storeId;
        return $this->save($data);
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool|int
     */
    public function edit(array $data): bool
    {
        return $this->save($data) !== false;
    }

    /**
     * 删除记录 (软删除)
     * @return bool|int
     */
    public function setDelete(): bool
    {
        return $this->save(['is_delete' => 1]) !== false;
    }

}
