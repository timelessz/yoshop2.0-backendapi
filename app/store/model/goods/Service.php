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

use app\common\model\goods\Service as ServiceModel;
use app\store\model\goods\ServiceRel as ServiceRelModel;

/**
 * 商品服务与承诺模型
 * Class Service
 */
class Service extends ServiceModel
{
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
     * 获取列表记录
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param = [])
    {
        return $this->where($this->getFilter($param))
            ->where('is_delete', '=', 0)
            ->order(['sort', $this->getPk()])
            ->paginate();
    }

    /**
     * 获取查询条件
     * @param array $param
     * @return array
     */
    private function getFilter(array $param = [])
    {
        // 默认查询参数
        $params = $this->setQueryDefaultValue($param, ['search' => '']);
        // 检索查询条件
        $filter = [];
        !empty($params['search']) && $filter[] = ['name', 'like', "%{$params['search']}%"];
        return $filter;
    }

    /**
     * 新增记录
     * @param array $data
     * @return false|int
     */
    public function add(array $data)
    {
        $data['store_id'] = self::$storeId;
        return $this->save($data);
    }

    /**
     * 更新记录
     * @param array $data
     * @return bool|int
     */
    public function edit(array $data)
    {
        return $this->save($data) !== false;
    }

    /**
     * 删除记录(软删除)
     * @return bool
     * @throws \Exception
     */
    public function remove()
    {
        // 判断该服务是否被商品引用
        $goodsCount = ServiceRelModel::getCountByServiceId($this['service_id']);
        if ($goodsCount > 0) {
            $this->error = "该记录被{$goodsCount}个商品引用，不允许删除";
            return false;
        }
        return $this->save(['is_delete' => 1]) !== false;
    }

}
