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

namespace app\api\controller;

use app\BaseController;
use app\api\model\User as UserModel;
use app\api\model\Store as StoreModel;
use app\api\service\User as UserService;
use app\common\exception\BaseException;
use think\response\Json;

/**
 * API控制器基类
 * Class BaseController
 * @package app\store\controller
 */
class Controller extends BaseController
{
    // 当前商城ID
    protected $storeId;

    /**
     * API基类初始化
     * @throws BaseException
     */
    public function initialize()
    {
        // 当前商城id
        $this->storeId = $this->getStoreId();
        // 验证当前商城状态
        $this->checkStore();
    }

    /**
     * 获取当前商城id
     * @return int|null
     * @throws BaseException
     */
    protected function getStoreId()
    {
        $storeId = getStoreId();    // app/api/common.php
        if (empty($storeId)) {
            throwError('缺少必要的参数：storeId');
        }
        return $storeId;
    }

    /**
     * 验证当前商城状态
     * @return bool
     * @throws BaseException
     */
    private function checkStore()
    {
        // 获取当前商城信息
        $store = StoreModel::detail($this->storeId);
        if (empty($store)) {
            throwError('当前商城信息不存在');
        }
        if ($store['is_recycle'] || $store['is_delete']) {
            throwError('当前商城已删除');
        }
        return true;
    }

    /**
     * 获取当前用户信息
     * @param bool $isForce 强制验证登录
     * @return UserModel|bool|null
     * @throws BaseException
     */
    protected function getLoginUser(bool $isForce = true)
    {
        return UserService::getCurrentLoginUser($isForce);
    }

    /**
     * 返回封装后的 API 数据到客户端
     * @param int|null $status 状态码
     * @param string $message
     * @param array $data
     * @return array|Json
     */
    protected function renderJson(int $status = null, string $message = '', array $data = [])
    {
        return json(compact('status', 'message', 'data'));
    }

    /**
     * 返回操作成功json
     * @param array|string $data
     * @param string $message
     * @return array
     */
    protected function renderSuccess($data = [], string $message = 'success')
    {
        if (is_string($data)) {
            $message = $data;
            $data = [];
        }
        return $this->renderJson(config('status.success'), $message, $data);
    }

    /**
     * 返回操作失败json
     * @param string $message
     * @param array $data
     * @return array
     */
    protected function renderError(string $message = 'error', array $data = [])
    {
        return $this->renderJson(config('status.error'), $message, $data);
    }

    /**
     * 获取post数据 (数组)
     * @param $key
     * @return mixed
     */
    protected function postData($key = null)
    {
        return $this->request->post(is_null($key) ? '' : $key . '/a');
    }

    /**
     * 获取post数据 (数组)
     * @param $key
     * @return mixed
     */
    protected function postForm($key = 'form')
    {
        return $this->postData($key);
    }

}
