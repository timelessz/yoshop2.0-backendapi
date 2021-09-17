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

use app\common\model\Express as ExpressModel;

class Express extends ExpressModel
{
    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function add(array $data)
    {
        $data['store_id'] = self::$storeId;
        return $this->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     */
    public function edit(array $data)
    {
        return $this->save($data);
    }

    /**
     * 删除记录
     * @return bool
     * @throws \Exception
     */
    public function remove()
    {
        // 判断当前物流公司是否已被订单使用
        $Order = new Order;
        if ($orderCount = $Order->where(['express_id' => $this['express_id']])->count()) {
            $this->error = '当前物流公司已被' . $orderCount . '个订单使用，不允许删除';
            return false;
        }
        return $this->delete();
    }

}