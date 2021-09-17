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

namespace app\admin\model;

use app\common\model\Store as StoreModel;

/**
 * 商家记录表模型
 * Class Store
 * @package app\admin\model
 */
class Store extends StoreModel
{
    /**
     * 获取列表数据
     * @param bool $isRecycle
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(bool $isRecycle = false)
    {
        return $this->where('is_recycle', '=', (int)$isRecycle)
            ->where('is_delete', '=', 0)
            ->order(['create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 移入移出回收站
     * @param bool $isRecycle
     * @return false|int
     */
    public function recycle($isRecycle = true)
    {
        return $this->save(['is_recycle' => (int)$isRecycle]);
    }

}
