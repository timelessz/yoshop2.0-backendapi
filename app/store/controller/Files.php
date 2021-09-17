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

namespace app\store\controller;

use app\store\model\UploadFile as UploadFileModel;

/**
 * 文件库管理
 * Class Files
 * @package app\store\controller
 */
class Files extends Controller
{
    /**
     * 文件列表
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function list()
    {
        $model = new UploadFileModel;
        $list = $model->getList($this->request->param());
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 编辑文件
     * @param int $fileId
     * @return array
     */
    public function edit(int $fileId)
    {
        // 文件详情
        $model = UploadFileModel::detail($fileId);
        // 更新记录
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess('更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 删除文件(批量)
     * @param array $fileIds 文件id集
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delete(array $fileIds)
    {
        $model = new UploadFileModel;
        if (!$model->setDelete($fileIds)) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

    /**
     * 移动文件到指定分组(批量)
     * @param int $groupId
     * @param array $fileIds
     * @return array
     */
    public function moveGroup(int $groupId, array $fileIds)
    {
        $model = new UploadFileModel;
        if (!$model->moveGroup($groupId, $fileIds)) {
            return $this->renderError($model->getError() ?: '操作失败');
        }
        return $this->renderSuccess('操作成功');
    }

}
