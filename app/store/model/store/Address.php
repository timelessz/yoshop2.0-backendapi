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

namespace app\store\model\store;

use app\common\model\store\Address as StoreAddressModel;

/**
 * 商家地址模型
 * Class Address
 * @package app\store\model
 */
class Address extends StoreAddressModel
{
    /**
     * 获取列表
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param = [])
    {
        return $this->where($this->getFilter($param))
            ->where('is_delete', '=', 0)
            ->order(['sort', $this->getPk()])
            ->paginate(15);
    }

    /**
     * 获取全部记录
     * @param array $param
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAll(array $param = [])
    {
        return $this->where($this->getFilter($param))
            ->where('is_delete', '=', 0)
            ->order(['sort', $this->getPk()])
            ->select();
    }

    /**
     * 根据param检索查询条件
     * @param array $param
     * @return array
     */
    private function getFilter(array $param = [])
    {
        // 默认查询条件
        $params = $this->setQueryDefaultValue($param, [
            'type' => 0,    // 地址类型(10发货地址 20退货地址)
            'search' => '',   // 收货人姓名/联系电话
        ]);
        // 检索查询条件
        $filter = [];
        $params['type'] > 0 && $filter[] = ['type', '=', $params['type']];
        !empty($params['search']) && $filter[] = ['name|phone', 'like', "%{$params['search']}%"];
        return $filter;
    }

    /**
     * 添加新记录
     * @param array $data
     * @return false|int
     */
    public function add(array $data)
    {
        list($data['province_id'], $data['city_id'], $data['region_id']) = $data['cascader'];
        $data['store_id'] = self::$storeId;
        return $this->save($data);
    }

    /**
     * 编辑记录
     * @param array $data
     * @return bool|int
     */
    public function edit(array $data)
    {
        list($data['province_id'], $data['city_id'], $data['region_id']) = $data['cascader'];
        return $this->save($data);
    }

    /**
     * 删除记录
     * @return bool|int
     */
    public function remove()
    {
        return $this->save(['is_delete' => 1]);
    }

}
