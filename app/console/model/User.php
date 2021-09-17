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

namespace app\console\model;

use app\common\model\User as UserModel;
use app\common\model\user\Grade as UserGradeModel;
use app\console\model\user\GradeLog as GradeLogModel;
use app\common\enum\user\grade\log\ChangeType as ChangeTypeEnum;

/**
 * 用户模型
 * Class User
 * @package app\console\model
 */
class User extends UserModel
{
    /**
     * 查询满足会员等级升级条件的用户列表
     * @param int $storeId 商城ID
     * @param UserGradeModel $upgradeGrade 会员等级
     * @param array $excludedUserIds 排除的会员ID集
     * @return mixed
     */
    public static function getUpgradeUserList(int $storeId, UserGradeModel $upgradeGrade, $excludedUserIds = [])
    {
        // 实例化查询对象
        $query = (new static)->getNewQuery();
        // 检索查询条件
        if (!empty($excludedUserIds)) {
            $query->where('user.user_id', 'not in', $excludedUserIds);
        }
        // 查询列表记录
        return $query->alias('user')
            ->field(['user.user_id', 'user.grade_id'])
            ->join('user_grade grade', 'grade.grade_id = user.grade_id', 'LEFT')
            ->where(function ($query) use ($upgradeGrade) {
                $query->where('user.grade_id', '=', 0);
                $query->whereOr('grade.weight', '<', $upgradeGrade['weight']);
            })
            ->where('user.expend_money', '>=', $upgradeGrade['upgrade']['expend_money'])
            ->where('user.store_id', '=', $storeId)
            ->where('user.is_delete', '=', 0)
            ->select();
    }

    /**
     * 批量设置会员等级
     * @param int $storeId 商城ID
     * @param array $data 会员等级更新数据
     * @return bool
     */
    public function setBatchGrade(int $storeId, array $data)
    {
        // 批量更新会员等级的数据
        $userData = [];
        // 批量新增会员等级变更记录的数据
        $logData = [];
        foreach ($data as $item) {
            $userData[] = [
                'where' => ['user_id' => $item['user_id']],
                'data' => ['grade_id' => $item['new_grade_id']]
            ];
            $logData[] = [
                'user_id' => $item['user_id'],
                'old_grade_id' => $item['old_grade_id'],
                'new_grade_id' => $item['new_grade_id'],
                'change_type' => ChangeTypeEnum::AUTO_UPGRADE,
                'store_id' => $storeId,
            ];
        }
        // 批量更新会员等级
        $this->updateAll($userData);
        // 批量新增会员等级变更记录
        (new GradeLogModel)->records($logData);
        return true;
    }

}
