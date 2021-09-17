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

namespace app\store\controller;

use app\store\model\Setting as SettingModel;
use app\store\model\UploadFile as UploadFileModel;
use app\common\enum\Setting as SettingEnum;
use app\common\enum\file\FileType as FileTypeEnum;
use app\common\library\storage\Driver as StorageDriver;

/**
 * 文件库管理
 * Class Upload
 * @package app\store\controller
 */
class Upload extends Controller
{
    // 当前商城的上传设置
    private $config;

    /**
     * 构造方法
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function initialize()
    {
        parent::initialize();
        // 存储配置信息
        $this->config = SettingModel::getItem(SettingEnum::STORAGE);
    }

    /**
     * 图片上传接口
     * @param int $groupId 分组ID
     * @return array
     * @throws \think\Exception
     */
    public function image(int $groupId = 0)
    {
        // 实例化存储驱动
        $storage = new StorageDriver($this->config);
        // 设置上传文件的信息
        $storage->setUploadFile('iFile')
            ->setRootDirName((string)$this->getStoreId())
            ->setValidationScene('image')
            ->upload();
        // 执行文件上传
        if (!$storage->upload()) {
            return $this->renderError('图片上传失败：' . $storage->getError());
        }
        // 文件信息
        $fileInfo = $storage->getSaveFileInfo();
        // 添加文件库记录
        $model = new UploadFileModel;
        $model->add($fileInfo, FileTypeEnum::IMAGE, $groupId);
        // 图片上传成功
        return $this->renderSuccess(['fileInfo' => $model->toArray()], '图片上传成功');
    }

}
