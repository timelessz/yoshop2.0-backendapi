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

use app\store\model\Goods as GoodsModel;
use app\common\model\Delivery as DeliveryModel;
use app\store\model\DeliveryRule as DeliveryRuleModel;

/**
 * 配送模板模型
 * Class Delivery
 * @package app\common\model
 */
class Delivery extends DeliveryModel
{
    /**
     * 获取全部
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAll()
    {
        $model = new static;
        return $model->order(['sort', $model->getPk()])->select();
    }

    /**
     * 获取列表
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList($param = [])
    {
        // 默认查询条件
        $params = $this->setQueryDefaultValue($param, ['search' => '']);
        // 检索查询条件
        $filter = [];
        !empty($params['search']) && $filter[] = ['name', 'like', "%{$params['search']}%"];
        // 查询列表数据
        return $this->with(['rule'])
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->order(['sort', $this->getPk()])
            ->paginate(15);
    }

    /**
     * 添加新记录
     * @param array $data
     * @return bool
     */
    public function add(array $data)
    {
        // 表单验证
        if (!$this->onValidate($data)) {
            return false;
        }
        $data['store_id'] = self::$storeId;
        // 事务处理
        $this->transaction(function () use ($data) {
            // 保存数据
            $this->save($data);
            // 添加模板区域及运费
            DeliveryRuleModel::increased((int)$this['delivery_id'], $data['rules']);
        });
        return true;
    }

    /**
     * 编辑记录
     * @param array $data
     * @return bool
     */
    public function edit(array $data)
    {
        // 表单验证
        if (!$this->onValidate($data)) {
            return false;
        }
        // 事务处理
        $this->transaction(function () use ($data) {
            // 保存数据
            $this->save($data);
            // 更新模板区域及运费
            DeliveryRuleModel::updates((int)$this['delivery_id'], $data['rules']);
        });
        return true;
    }

    /**
     * 表单验证
     * @param $data
     * @return bool
     */
    private function onValidate(array $data)
    {
        if (!isset($data['rules']) || empty($data['rules'])) {
            $this->error = '请选择可配送区域';
            return false;
        }
        return true;
    }

    /**
     * 删除记录
     * @return bool
     */
    public function remove()
    {
        // 验证运费模板是否被商品使用
        if (!$this->checkIsUseGoods($this['delivery_id'])) {
            return false;
        }
        // 删除运费模板
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 验证运费模板是否被商品使用
     * @param int $deliveryId
     * @return bool
     */
    private function checkIsUseGoods(int $deliveryId)
    {
        // 判断是否存在商品
        $goodsCount = (new GoodsModel)->getGoodsTotal(['delivery_id' => $deliveryId]);
        if ($goodsCount > 0) {
            $this->error = "该模板被{$goodsCount}个商品使用，不允许删除";
            return false;
        }
        return true;
    }

}
