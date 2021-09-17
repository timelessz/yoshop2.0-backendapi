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

namespace app\admin\controller\setting;

use app\admin\controller\Controller;
use app\admin\service\Cache as CacheService;

/**
 * 清理缓存
 * Class Index
 * @package app\admin\controller
 */
class Cache extends Controller
{
    /**
     * 清理缓存
     * @return array|string
     * @throws \Exception
     */
    public function clear()
    {
        // 清理缓存
        $CacheService = new CacheService;
        if (!$CacheService->rmCache($this->postForm())) {
            return $this->renderError($CacheService->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

}
