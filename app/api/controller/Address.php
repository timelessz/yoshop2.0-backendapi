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

use app\api\service\User as UserService;
use app\api\model\UserAddress as UserAddressModel;
use app\common\exception\BaseException;

/**
 * 收货地址管理
 * Class Address
 * @package app\api\controller
 */
class Address extends Controller
{
    /**
     * 收货地址列表
     * @return array|\think\response\Json
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function list()
    {
        // 获取收货地址列表
        $model = new UserAddressModel;
        $list = $model->getList();
        return $this->renderSuccess(compact('list'));
    }

    /**
     * 获取当前用户默认收货地址
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function defaultId()
    {
        $useInfo = UserService::getCurrentLoginUser(true);
        return $this->renderSuccess(['defaultId' => $useInfo['address_id']]);
    }

    /**
     * 收货地址详情
     * @param int $addressId 地址ID
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function detail(int $addressId)
    {
        $detail = UserAddressModel::detail($addressId);
        return $this->renderSuccess(compact('detail'));
    }

    /**
     * 添加收货地址
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function add()
    {
        $model = new UserAddressModel;
        if ($model->add($this->postForm())) {
            return $this->renderSuccess([], '添加成功');
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }

    /**
     * 编辑收货地址
     * @param int $addressId 地址ID
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function edit(int $addressId)
    {
        $model = UserAddressModel::detail($addressId);
        if ($model->edit($this->postForm())) {
            return $this->renderSuccess([], '更新成功');
        }
        return $this->renderError($model->getError() ?: '更新失败');
    }

    /**
     * 设为默认地址
     * @param int $addressId 地址ID
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function setDefault(int $addressId)
    {
        $model = UserAddressModel::detail($addressId);
        if ($model->setDefault((int)$model['address_id'])) {
            return $this->renderSuccess([], '设置成功');
        }
        return $this->renderError($model->getError() ?: '设置失败');
    }

    /**
     * 删除收货地址
     * @param int $addressId 地址ID
     * @return array|\think\response\Json
     * @throws BaseException
     */
    public function remove(int $addressId)
    {
        $model = UserAddressModel::detail($addressId);
        if ($model->remove()) {
            return $this->renderSuccess([], '删除成功');
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }

}
