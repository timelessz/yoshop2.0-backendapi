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

namespace app\api\service\order\source\checkout;

use app\common\service\BaseService;
use app\api\model\User as UserModel;

/**
 * 订单结算台扩展基类
 * Class Basics
 * @package app\api\service\order\source\checkout
 */
abstract class Basics extends BaseService
{
    /* @var UserModel $user 当前用户信息 */
    protected $user;

    // 订单结算商品列表
    protected $goodsList = [];

    /**
     * 构造方法
     * Checkout constructor.
     * @param UserModel $user
     * @param array $goodsList
     */
    public function __construct($user, $goodsList)
    {
        parent::__construct();
        $this->user = $user;
        $this->goodsList = $goodsList;
    }

    /**
     * 验证商品列表
     * @return mixed
     */
    abstract public function validateGoodsList();

}