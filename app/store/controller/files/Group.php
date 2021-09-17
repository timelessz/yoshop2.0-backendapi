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

namespace app\store\controller\files;

use app\store\controller\Controller;
use app\store\model\UploadGroup as GroupModel;

/**
 * 文件分组
 * Class Group
 * @package app\store\controller\content
 */
class Group extends Controller
{
    /**
     * 文件分组列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        $model = new GroupModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 添加文件分组
     * @return array|string
     */
    public function add()
    {
        // 新增记录
        $model = new GroupModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess('添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑文件分组
     * @param int $groupId
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit(int $groupId)
    {
        // 分组详情
        $model = GroupModel::detail($groupId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除文件分组
     * @param int $groupId
     * @return array
     * @throws \Exception
     */
    public function delete(int $groupId)
    {
        $model = GroupModel::detail($groupId);
        if (!$model->remove()) {
            return $this->renderError($model->getError() ?: '删除失败');
        }
        return $this->renderSuccess('删除成功');
    }

}
