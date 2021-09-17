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

namespace app\common\model;

/**
 * 模型类：帮助中心
 * Class Help
 * @package app\common\model
 */
class Help extends BaseModel
{
    // 定义表名
    protected $name = 'help';

    // 定义主键
    protected $pk = 'help_id';

    /**
     * 获取列表记录
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList()
    {
        return $this->where('is_delete', '=', 0)
            ->order(['sort', $this->getPk()])
            ->paginate();
    }

    /**
     * 帮助详情
     * @param int $helpId
     * @return null|static
     */
    public static function detail(int $helpId)
    {
        return self::get($helpId);
    }

}
