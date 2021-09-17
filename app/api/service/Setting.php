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

namespace app\api\service;

use app\common\library\helper;
use app\common\service\BaseService;
use app\api\model\Setting as SettingModel;
use app\common\enum\Setting as SettingEnum;

/**
 * 服务类：商城设置
 * Class Setting
 * @package app\api\service
 */
class Setting extends BaseService
{
    /**
     * 商城公共设置
     * 这里的商城设置仅暴露可公开的设置项 例如分类页模板、积分名称
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPublic()
    {
        $data = [];
        //分类页模板设置
        $data[SettingEnum::PAGE_CATEGORY_TEMPLATE] = $this->getCatTplStyle();
        // 积分设置
        $data[SettingEnum::POINTS] = $this->getPoints();
        // 充值设置
        $data[SettingEnum::RECHARGE] = $this->getRecharge();
        return $data;
    }

    /**
     * 积分设置 (积分名称、积分描述)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getPoints()
    {
        $values = SettingModel::getItem(SettingEnum::POINTS);
        return helper::pick($values, ['points_name', 'describe']);
    }

    /**
     * 积分设置 (积分名称、积分描述)
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getRecharge()
    {
        $values = SettingModel::getItem(SettingEnum::RECHARGE);
        return helper::pick($values, ['is_entrance', 'is_custom', 'describe']);
    }

    /**
     * 获取分类页模板设置
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getCatTplStyle()
    {
        return SettingModel::getItem(SettingEnum::PAGE_CATEGORY_TEMPLATE);
    }
}