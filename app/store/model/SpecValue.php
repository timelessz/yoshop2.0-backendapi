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

use app\common\library\helper;
use app\common\model\SpecValue as SpecValueModel;

/**
 * 规格/属性(值)模型
 * Class SpecValue
 * @package app\store\model
 */
class SpecValue extends SpecValueModel
{
    /**
     * 规格值写入数据库并生成id
     * @param int $specId
     * @param array $valueList
     * @return array
     */
    public static function getNewValueList(int $specId, array $valueList)
    {
        // 规格组名称合集
        $values = helper::getArrayColumn($valueList, 'spec_value');
        // 获取到已存在的规格值
        $alreadyData = static::getListByValues($specId, $values);
        // 遍历整理新的规格集
        foreach ($valueList as $key => &$item) {
            $alreadyItem = helper::getArrayItemByColumn($alreadyData, 'spec_value', $item['spec_value']);
            if (!empty($alreadyItem)) {
                // 规格值已存在的记录spec_value_id
                $item['spec_value_id'] = $alreadyItem['spec_value_id'];
            } else {
                // 规格值不存在的新增记录
                $result = static::add($specId, $item);
                $item['spec_value_id'] = $result['spec_value_id'];
            }
        }
        return $valueList;
    }

    /**
     * 新增规格组记录
     * @param int $specId
     * @param array $item
     * @return Spec|\think\Model
     */
    private static function add(int $specId, array $item)
    {
        return self::create([
            'spec_value' => $item['spec_value'],
            'spec_id' => $specId,
            'store_id' => self::$storeId
        ]);
    }

    /**
     * 根据规格组名称集获取列表
     * @param int $specId
     * @param array $values
     * @return \think\Collection
     */
    private static function getListByValues(int $specId, array $values)
    {
        return (new static)->where('spec_id', '=', $specId)
            ->where('spec_value', 'in', $values)
            ->select();
    }

}
