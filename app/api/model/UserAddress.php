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

namespace app\api\model;

use app\api\model\User as UserModel;
use app\api\service\User as UserService;
use app\common\model\UserAddress as UserAddressModel;
use app\common\exception\BaseException;

/**
 * 用户收货地址模型
 * Class UserAddress
 * @package app\common\model
 */
class UserAddress extends UserAddressModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'is_delete',
        'store_id',
        'create_time',
        'update_time'
    ];

//    /**
//     * 地区名称
//     * @param $value
//     * @param $data
//     * @return array
//     */
//    public function getRegionAttr($value, $data)
//    {
//        return array_values(parent::getRegionAttr($value, $data));
//    }

    /**
     * 获取收货地址列表
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws BaseException
     */
    public function getList()
    {
        $userId = UserService::getCurrentLoginUserId();
        return $this->where('user_id', '=', $userId)
            ->where('is_delete', '=', 0)
            ->select();
    }

    /**
     * 新增收货地址
     * @param array $data
     * @return mixed
     * @throws BaseException
     */
    public function add(array $data)
    {
        // 当前用户信息
        $user = UserService::getCurrentLoginUser(true);
        // 省市区ID
        list($data['province_id'], $data['city_id'], $data['region_id']) = $this->getRegionId($data);
        // 添加收货地址
        return $this->transaction(function () use ($user, $data) {
            $this->save([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'province_id' => $data['province_id'],
                'city_id' => $data['city_id'],
                'region_id' => $data['region_id'],
                'detail' => $data['detail'],
                'user_id' => $user['user_id'],
                'store_id' => self::$storeId
            ]);
            // 设为默认收货地址
            !$user['address_id'] && $this->setDefault((int)$this['address_id']);
            return true;
        });
    }

    /**
     * 格式化用户上传的省市区数据
     * @param array $data
     * @return array
     * @throws BaseException
     */
    private function getRegionId(array $data)
    {
        if (!isset($data['region'])) {
            throwError('省市区不能为空');
        }
        if (count($data['region']) != 3) {
            throwError('省市区数据不合法');
        }
        return array_map(function ($item) {
            return $item['value'];
        }, $data['region']);
    }

    /**
     * 编辑收货地址
     * @param array $data
     * @return bool
     * @throws BaseException
     */
    public function edit(array $data)
    {
        // 省市区ID
        list($data['province_id'], $data['city_id'], $data['region_id']) = $this->getRegionId($data);
        // 更新收货地址
        return $this->save([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'province_id' => $data['province_id'],
                'city_id' => $data['city_id'],
                'region_id' => $data['region_id'],
                'detail' => $data['detail']
            ]) !== false;
    }

    /**
     * 设为默认收货地址
     * @param int $addressIid
     * @return bool
     * @throws BaseException
     */
    public function setDefault(int $addressIid)
    {
        // 设为默认地址
        $userId = UserService::getCurrentLoginUserId();
        return UserModel::updateBase(['address_id' => $addressIid], ['user_id' => $userId]);
    }

    /**
     * 删除收货地址
     * @return bool
     * @throws BaseException
     */
    public function remove()
    {
        // 查询当前是否为默认地址
        $user = UserService::getCurrentLoginUser(true);
        // 清空默认地址
        if ($user['address_id'] == $this['address_id']) {
            UserModel::updateBase(['address_id' => 0], ['user_id' => $this['user_id']]);
        }
        // 标记为已删除
        return $this->save(['is_delete' => 1]);
    }

    /**
     * 收货地址详情
     * @param int $addressId
     * @return UserAddress|array|null
     * @throws BaseException
     */
    public static function detail(int $addressId)
    {
        $userId = UserService::getCurrentLoginUserId();
        $detail = self::get(['user_id' => $userId, 'address_id' => $addressId]);
        if (empty($detail)) {
            throwError('未找到该收货地址');
            return false;
        }
        return $detail;
    }

}
