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

use app\common\model\Help as HelpModel;

/**
 * 模型类：帮助中心
 * Class Help
 * @package app\store\model
 */
class Help extends HelpModel
{
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
     * 删除记录
     * @return bool
     */
    public function setDelete()
    {
        return $this->save(['is_delete' => 1]);
    }

}
