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

namespace app\api\controller;

use app\api\model\Setting as SettingModel;
use app\api\model\UploadFile as UploadFileModel;
use app\api\service\User as UserService;
use app\common\enum\Setting as SettingEnum;
use app\common\enum\file\FileType as FileTypeEnum;
use app\common\library\storage\Driver as StorageDriver;
use app\common\exception\BaseException;

/**
 * 文件库管理
 * Class Upload
 * @package app\api\controller
 */
class Upload extends Controller
{
    // 当前商城的上传设置
    private $config;

    /**
     * 构造方法
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function initialize()
    {
        parent::initialize();
        // 验证登录
        UserService::isLogin(true);
        // 存储配置信息
        $this->config = SettingModel::getItem(SettingEnum::STORAGE);
    }

    /**
     * 图片上传接口
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\Exception
     */
    public function image()
    {
        // 当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 实例化存储驱动
        $storage = new StorageDriver($this->config);
        // 设置上传文件的信息
        $storage->setUploadFile('file')
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
        $model->add($fileInfo, FileTypeEnum::IMAGE, $userId);
        // 图片上传成功
        return $this->renderSuccess(['fileInfo' => $model->toArray()], '图片上传成功');
    }
}
