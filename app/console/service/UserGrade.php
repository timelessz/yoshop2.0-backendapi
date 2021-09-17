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

namespace app\console\service;

use app\common\library\helper;
use app\console\library\Tools;
use app\common\service\BaseService;
use app\console\model\User as UserModel;
use app\console\model\user\Grade as UserGradeModel;


/**
 * 服务类：会员等级
 * Class UserGrade
 * @package app\console\service
 */
class UserGrade extends BaseService
{
    /**
     * 设置用户的会员等级
     * @param int $storeId
     * @return array|bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setUserGrade(int $storeId)
    {
        // 获取所有会员等级
        $list = $this->getUsableList($storeId);
        // 遍历等级，根据升级条件 查询满足消费金额的用户列表，并且他的等级小于该等级
        $data = [];
        foreach ($list as $grade) {
            // 查询满足会员等级升级条件的用户列表
            $userList = UserModel::getUpgradeUserList($storeId, $grade, array_keys($data));
            // 遍历整理数据
            foreach ($userList as $user) {
                if (!isset($data[$user['user_id']])) {
                    $data[$user['user_id']] = [
                        'user_id' => $user['user_id'],
                        'old_grade_id' => $user['grade_id'],
                        'new_grade_id' => $grade['grade_id'],
                    ];
                }
            }
        }
        // 记录日志
        Tools::taskLogs('UserGrade', 'setUserGrade', [
            'storeId' => $storeId,
            'data' => $data
        ]);
        // 批量修改会员的等级
        return (new UserModel)->setBatchGrade($storeId, $data);
    }

    /**
     * 获取所有会员等级
     * @param int $storeId 商城ID
     * @return false|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function getUsableList(int $storeId)
    {
        return UserGradeModel::getUsableList($storeId);
    }
}