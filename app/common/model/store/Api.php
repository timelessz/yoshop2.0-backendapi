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

namespace app\common\model\store;

use app\common\model\BaseModel;

/**
 * 商家后台API权限模型
 * Class Api
 * @package app\common\model\admin
 */
class Api extends BaseModel
{
    // 定义表名
    protected $name = 'store_api';

    // 定义表主键
    protected $pk = 'api_id';

    /**
     * 获取所有权限
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected static function getAll()
    {
        $data = static::withoutGlobalScope()
            ->order(['sort', 'create_time'])
            ->select();
        return !$data->isEmpty() ? $data->toArray() : [];
    }

    /**
     * 权限信息
     * @param int|array $where
     * @return array|null|static
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($where)
    {
        $model = static::withoutGlobalScope();
        is_array($where) ? $model->where($where) : $model->where('api_id', '=', $where);
        return $model->find();
    }

    /**
     * 获取指定ID集的url
     * @param array $apiIds
     * @return array
     */
    public static function getApiUrls(array $apiIds)
    {
        return static::withoutGlobalScope()
            ->where('api_id', 'in', $apiIds)
            ->order(['sort', 'create_time'])
            ->column('url');
    }
}
