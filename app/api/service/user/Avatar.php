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

namespace app\api\service\user;

use app\common\service\BaseService;
use app\api\model\Setting as SettingModel;
use app\api\model\UploadFile as UploadFileModel;
use app\common\enum\file\FileType as FileTypeEnum;
use app\common\library\Download;
use app\common\library\storage\Driver as Storage;

/**
 * 服务类: 用户头像
 * Class Avatar
 * @package app\api\service\user
 */
class Avatar extends BaseService
{
    // 存储配置
    private $config;

    /**
     * 构造函数
     * Avatar constructor.
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function __construct()
    {
        parent::__construct();
        // 存储配置
        $this->config = SettingModel::getItem('storage');
    }

    /**
     * 下载第三方头像并返回文件记录ID
     * @param string $avatarUrl
     * @return false|mixed
     */
    public function party(string $avatarUrl)
    {
        try {
            // 下载网络图片
            $filePath = $this->download($avatarUrl);
            // 上传到本地
            $fileInfo = $this->upload($filePath);
            // 新增文件记录
            return $this->record($fileInfo);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * 图片上传接口
     * @param string $filePath
     * @return false|mixed
     * @throws \think\Exception
     */
    public function upload(string $filePath = '')
    {
        // 实例化存储驱动
        $storage = new Storage($this->config);
        // 设置上传文件的信息
        $storage->setUploadFileByReal($filePath)
            ->setRootDirName((string)$this->storeId)
            ->setValidationScene('image')
            ->upload();
        // 执行文件上传
        if (!$storage->upload()) {
            throwError('图片上传失败：' . $storage->getError());
        }
        // 文件信息
        return $storage->getSaveFileInfo();
    }

    /**
     * 添加文件库记录
     * @param array $fileInfo
     * @return mixed
     */
    private function record(array $fileInfo)
    {
        $model = new UploadFileModel;
        $model->add($fileInfo, FileTypeEnum::IMAGE, 0);
        return (int)$model['file_id'];
    }

    /**
     * 下载网络图片
     * @param string $avatarUrl
     * @return string
     * @throws \app\common\exception\BaseException
     */
    private function download(string $avatarUrl)
    {
        $Download = new Download;
        return $Download->saveTempImage($this->storeId, $avatarUrl, 'avatar');
    }

}