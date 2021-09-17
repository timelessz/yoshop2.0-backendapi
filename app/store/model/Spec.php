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
declare (strict_types=1);

namespace app\store\model;

use app\common\library\helper;
use app\common\model\Spec as SpecModel;
use app\store\model\SpecValue as SpecValueModel;

/**
 * 规格组模型
 * Class Spec
 * @package app\store\model
 */
class Spec extends SpecModel
{
    /**
     * 规格组写入数据库并生成ID集
     * 此时的$specList是用户端传来的
     * @param array $specList
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getNewSpecList(array $specList)
    {
        // 规格组名称合集
        $names = helper::getArrayColumn($specList, 'spec_name');
        // 获取到已存在的规格组
        $alreadyData = static::getListByNames($names);
        // 遍历整理新的规格集
        foreach ($specList as $key => &$item) {
            $alreadyItem = helper::getArrayItemByColumn($alreadyData, 'spec_name', $item['spec_name']);
            if (!empty($alreadyItem)) {
                // 规格名已存在的记录spec_id
                $item['spec_id'] = $alreadyItem['spec_id'];
            } else {
                // 规格名不存在的新增记录
                $result = static::add($item);
                $item['spec_id'] = (int)$result['spec_id'];
            }
            // 规格值写入数据库并生成id
            $item['valueList'] = SpecValueModel::getNewValueList((int)$item['spec_id'], $item['valueList']);
        }
        return $specList;
    }

    /**
     * 新增规格组记录
     * @param array $item
     * @return static|\think\Model
     */
    private static function add(array $item)
    {
        // 拿到所有的规格组名称集
        // 获取到已存在的
        return self::create([
            'spec_name' => $item['spec_name'],
            'store_id' => self::$storeId
        ]);
    }

    /**
     * 根据规格组名称集获取列表
     * @param array $names
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private static function getListByNames(array $names)
    {
        return (new static)->where('spec_name', 'in', $names)->select();
    }
}
