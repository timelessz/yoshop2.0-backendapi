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

namespace app\api\model\user;

use app\api\service\User as UserService;
use app\common\model\user\BalanceLog as BalanceLogModel;

/**
 * 用户余额变动明细模型
 * Class BalanceLog
 * @package app\api\model\user
 */
class BalanceLog extends BalanceLogModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'store_id',
    ];

    /**
     * 获取账单明细列表
     * @return \think\Paginator
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DbException
     */
    public function getList()
    {
        // 当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 获取列表数据
        return $this->where('user_id', '=', $userId)
            ->order(['create_time' => 'desc'])
            ->paginate(15);
    }

}