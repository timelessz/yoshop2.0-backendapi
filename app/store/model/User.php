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
declare (strict_types=1);

namespace app\store\model;

use app\common\model\User as UserModel;

use app\store\model\UserOauth as UserOauthModel;
use app\store\model\user\GradeLog as GradeLogModel;
use app\store\model\user\PointsLog as PointsLogModel;
use app\store\model\user\BalanceLog as BalanceLogModel;
use app\store\service\store\User as StoreUserService;
use app\common\enum\user\balanceLog\Scene as SceneEnum;
use app\common\enum\user\grade\log\ChangeType as ChangeTypeEnum;
use app\common\library\helper;

/**
 * 用户模型
 * Class User
 * @package app\store\model
 */
class User extends UserModel
{
    // 充值类型枚举: 余额
    const RECHARGE_TYPE_BALANCE = 'balance';

    // 充值类型枚举: 积分
    const RECHARGE_TYPE_POINTS = 'points';

    /**
     * 获取当前用户总数
     * @param array $param 查询参数
     * @return int|string
     */
    public function getUserTotal(array $param = [])
    {
        // 检索查询条件
        $filter = $this->getUserTotalFilter($param);
        // 查询结果
        return $this->where($filter)
            ->where('is_delete', '=', '0')
            ->count();
    }

    /**
     * 获取当前用户总数的查询条件
     * @param array $param
     * @return array
     */
    private function getUserTotalFilter(array $param = [])
    {
        // 默认查询参数
        $params = $this->setQueryDefaultValue($param, [
            'date' => null,         // 注册日期 如: 2020-08-01
            'isConsume' => null,    // 是否已消费
        ]);
        // 检索查询条件
        $filter = [];
        if (!is_null($params['date'])) {
            $startTime = strtotime($params['date']);
            $filter[] = ['create_time', '>=', $startTime];
            $filter[] = ['create_time', '<', $startTime + 86400];
        }
        if (is_bool($params['isConsume'])) {
            $filter[] = ['pay_money', $params['isConsume'] ? '>' : '=', 0];
        }
        return $filter;
    }

    /**
     * 获取用户列表
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param = [])
    {
        // 检索查询条件
        $filter = $this->getFilter($param);
        // 获取用户列表
        return $this->with(['avatar', 'grade'])
            ->where($filter)
            ->where('is_delete', '=', '0')
            ->order(['create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 获取查询条件
     * @param array $param
     * @return array
     */
    private function getFilter(array $param = [])
    {
        // 默认查询条件
        $params = $this->setQueryDefaultValue($param, [
            'search' => '',     // 微信昵称
            'gender' => -1,     // 用户性别
            'grade' => 0,       // 用户等级
        ]);
        // 检索查询条件
        $filter = [];
        // 微信昵称
        !empty($params['search']) && $filter[] = ['nick_name|mobile', 'like', "%{$params['search']}%"];
        // 用户性别
        $params['gender'] > -1 && $filter[] = ['gender', '=', (int)$params['gender']];
        // 用户等级
        $params['grade'] > 0 && $filter[] = ['grade_id', '=', (int)$params['grade']];
        // 起止时间
        if (!empty($params['betweenTime'])) {
            $times = between_time($params['betweenTime']);
            $filter[] = ['create_time', '>=', $times['start_time']];
            $filter[] = ['create_time', '<', $times['end_time'] + 86400];
        }
        return $filter;
    }

    /**
     * 删除用户
     * @return bool|mixed
     */
    public function setDelete()
    {
        return $this->transaction(function () {
            // 将第三方用户信息记录标记删除
            UserOauthModel::updateBase(['is_delete' => 1], [
                ['user_id', '=', $this['user_id']]
            ]);
            // 标记为已删除
            return $this->save(['is_delete' => 1]);
        });
    }

    /**
     * 用户充值
     * @param string $target 充值类型
     * @param array $data 表单数据
     * @return bool
     */
    public function recharge(string $target, array $data)
    {
        // 当前操作人用户名
        $storeUserName = StoreUserService::getLoginInfo()['user']['user_name'];
        if ($target === self::RECHARGE_TYPE_BALANCE) {
            return $this->rechargeToBalance($storeUserName, $data['balance']);
        } elseif ($target === self::RECHARGE_TYPE_POINTS) {
            return $this->rechargeToPoints($storeUserName, $data['points']);
        }
        return false;
    }

    /**
     * 用户充值：余额
     * @param string $storeUserName
     * @param array $data
     * @return bool
     */
    private function rechargeToBalance(string $storeUserName, array $data)
    {
        if (!isset($data['money']) || $data['money'] === '' || $data['money'] < 0) {
            $this->error = '请输入正确的金额';
            return false;
        }
        // 判断充值方式，计算最终金额
        if ($data['mode'] === 'inc') {
            $diffMoney = $data['money'];
        } elseif ($data['mode'] === 'dec') {
            $diffMoney = -$data['money'];
        } else {
            $diffMoney = helper::bcsub($data['money'], $this['balance']);
        }
        // 更新记录
        $this->transaction(function () use ($storeUserName, $data, $diffMoney) {
            // 更新账户余额
            static::setIncBalance((int)$this['user_id'], (float)$diffMoney);
            // 新增余额变动记录
            BalanceLogModel::add(SceneEnum::ADMIN, [
                'user_id' => $this['user_id'],
                'money' => (float)$diffMoney,
                'remark' => $data['remark'],
            ], [$storeUserName]);
        });
        return true;
    }

    /**
     * 用户充值：积分
     * @param string $storeUserName
     * @param array $data
     * @return bool
     */
    private function rechargeToPoints(string $storeUserName, array $data)
    {
        if (!isset($data['value']) || $data['value'] === '' || $data['value'] < 0) {
            $this->error = '请输入正确的积分数量';
            return false;
        }
        // 判断充值方式，计算最终积分
        if ($data['mode'] === 'inc') {
            $diffMoney = $data['value'];
        } elseif ($data['mode'] === 'dec') {
            $diffMoney = -$data['value'];
        } else {
            $diffMoney = $data['value'] - $this['points'];
        }
        // 更新记录
        $this->transaction(function () use ($storeUserName, $data, $diffMoney) {
            // 更新账户积分
            $this->setInc($this['user_id'], 'points', $diffMoney);
            // 新增积分变动记录
            PointsLogModel::add([
                'user_id' => $this['user_id'],
                'value' => $diffMoney,
                'describe' => "后台管理员 [{$storeUserName}] 操作",
                'remark' => $data['remark'],
            ]);
        });
        return true;
    }

    /**
     * 修改用户等级
     * @param array $data
     * @return mixed
     */
    public function updateGrade(array $data)
    {
        // 变更前的等级id
        $oldGradeId = $this['grade_id'];
        return $this->transaction(function () use ($oldGradeId, $data) {
            // 更新用户的等级
            $status = $this->save(['grade_id' => $data['grade_id']]);
            // 新增用户等级修改记录
            if ($status) {
                (new GradeLogModel)->record([
                    'user_id' => $this['user_id'],
                    'old_grade_id' => $oldGradeId,
                    'new_grade_id' => $data['grade_id'],
                    'change_type' => ChangeTypeEnum::ADMIN_USER,
                    'remark' => $data['remark']
                ]);
            }
            return $status !== false;
        });
    }

    /**
     * 消减用户的实际消费金额
     * @param int $userId
     * @param float $expendMoney
     * @return mixed
     */
    public function setDecUserExpend(int $userId, float $expendMoney)
    {
        return $this->setDec(['user_id' => $userId], 'expend_money', $expendMoney);
    }

}
