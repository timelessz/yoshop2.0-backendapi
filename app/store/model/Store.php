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

use app\common\model\Store as StoreModel;

/**
 * 商家记录表模型
 * Class Store
 * @package app\store\model
 */
class Store extends StoreModel
{
    /**
     * 更新记录
     * @param array $data
     * @return bool
     */
    public function edit(array $data)
    {
        // 是否删除图片
        !isset($data['logo_image_id']) && $data['logo_image_id'] = 0;
        return $this->save($data) !== false;
    }

}
