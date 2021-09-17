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

namespace app\store\model\user;

use app\common\model\user\Grade as GradeModel;

use app\store\model\User as UserModel;

/**
 * 用户会员等级模型
 * Class Grade
 * @package app\store\model\user
 */
class Grade extends GradeModel
{
    // 表单验证场景: 新增
    const FORM_SCENE_ADD = 'add';

    // 表单验证场景: 编辑
    const FORM_SCENE_EDIT = 'edit';

    /**
     * 获取全部记录
     * @param array $param
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAll(array $param)
    {
        return $this->where($this->getFilter($param))
            ->where('is_delete', '=', 0)
            ->order(['weight', $this->getPk()])
            ->select();
    }

    /**
     * 获取列表记录
     * @param array $param
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getList(array $param)
    {
        return $this->where($this->getFilter($param))
            ->where('is_delete', '=', 0)
            ->order(['weight', $this->getPk()])
            ->paginate();
    }

    /**
     * 获取查询条件
     * @param array $param
     * @return array
     */
    private function getFilter(array $param)
    {
        // 默认查询条件
        $params = $this->setQueryDefaultValue($param, [
            'status' => -1  // 状态(1启用 0禁用 -1全部)
        ]);
        // 检索查询条件
        $filter = $params['status'] > -1 ? ['status' => (int)$params['status']] : [];
        return $filter;
    }

    /**
     * 新增记录
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function add(array $data)
    {
        if (!$this->validateForm($data, self::FORM_SCENE_ADD)) {
            return false;
        }
        $data['store_id'] = self::$storeId;
        return $this->save($data);
    }

    /**
     * 编辑记录
     * @param $data
     * @return false|int
     */
    public function edit(array $data)
    {
        if (!$this->validateForm($data, self::FORM_SCENE_EDIT)) {
            return false;
        }
        return $this->save($data) !== false;
    }

    /**
     * 软删除
     * @return false|int
     */
    public function setDelete()
    {
        // 判断该等级下是否存在会员
        if (UserModel::checkExistByGradeId((int)$this['grade_id'])) {
            $this->error = '该会员等级下存在用户，不允许删除';
            return false;
        }
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 表单验证
     * @param $data
     * @param string $scene
     * @return bool
     */
    private function validateForm(array $data, string $scene = self::FORM_SCENE_ADD)
    {
        if ($scene === self::FORM_SCENE_ADD) {
            // 需要判断等级权重是否已存在
            if (self::checkExistByWeight($data['weight'])) {
                $this->error = '等级权重已存在';
                return false;
            }
        } elseif ($scene === self::FORM_SCENE_EDIT) {
            // 需要判断等级权重是否已存在
            if (self::checkExistByWeight($data['weight'], $this['grade_id'])) {
                $this->error = '等级权重已存在';
                return false;
            }
        }
        return true;
    }

}
