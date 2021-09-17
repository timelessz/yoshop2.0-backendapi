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

namespace app\api\model;

use app\api\service\User as UserService;
use app\api\model\OrderGoods as OrderGoodsModel;
use app\common\model\OrderRefund as OrderRefundModel;
use app\common\enum\order\refund\RefundType as RefundTypeEnum;
use app\common\enum\order\refund\AuditStatus as AuditStatusEnum;
use app\common\enum\order\refund\RefundStatus as RefundStatusEnum;
use app\common\exception\BaseException;

/**
 * 售后单模型
 * Class OrderRefund
 * @package app\api\model
 */
class OrderRefund extends OrderRefundModel
{
    /**
     * 隐藏字段
     * @var array
     */
    protected $hidden = [
        'store_id',
        'update_time'
    ];

    /**
     * 追加字段
     * @var array
     */
    protected $append = [
        'state_text',   // 售后单状态文字描述
    ];

    /**
     * 售后单状态文字描述
     * @param $value
     * @param $data
     * @return string
     */
    public function getStateTextAttr($value, $data)
    {
        // 已完成
        if ($data['status'] == RefundStatusEnum::COMPLETED) {
            $text = [RefundTypeEnum::RETURN => '已同意退货并已退款', RefundTypeEnum::EXCHANGE => '已同意换货'];
            return $text[$data['type']];
        }
        // 已取消
        if ($data['status'] == RefundStatusEnum::CANCELLED) {
            return '已取消';
        }
        // 已拒绝
        if ($data['status'] == RefundStatusEnum::REJECTED) {
            // return '已拒绝';
            return $data['type'] == RefundTypeEnum::RETURN ? '已拒绝退货退款' : '已拒绝换货';
        }
        // 进行中
        if ($data['status'] == RefundStatusEnum::NORMAL) {
            if ($data['audit_status'] == AuditStatusEnum::WAIT) {
                return '等待审核中';
            }
            if ($data['type'] == RefundTypeEnum::RETURN) {
                return $data['is_user_send'] ? '已发货，待平台确认' : '已同意退货，请及时发货';
            }
        }
        return $value;
    }

    /**
     * 获取用户售后单列表
     * @param int $state 售后单状态 -1为全部
     * @return \think\Paginator
     * @throws \app\common\exception\BaseException
     * @throws \think\db\exception\DbException
     */
    public function getList(int $state = -1)
    {
        // 检索查询条件
        $filter = [];
        // 售后单状态
        $state > -1 && $filter[] = ['status', '=', $state];
        // 当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 查询列表记录
        return $this->with(['orderGoods.image'])
            ->where($filter)
            ->where('user_id', '=', $userId)
            ->order(['create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 获取当前用户的售后单详情
     * @param int $orderRefundId 售后单ID
     * @param bool $isWith 是否关联
     * @return static|null
     * @throws BaseException
     */
    public static function getDetail(int $orderRefundId, $isWith = false)
    {
        // 关联查询
        $with = $isWith ? ['orderGoods' => ['image'], 'images.file', 'address'] : [];
        // 获取记录
        $detail = static::detail([
            'user_id' => UserService::getCurrentLoginUserId(),
            'order_refund_id' => $orderRefundId
        ], $with);
        if (empty($detail)) throwError('未找到该售后单');
        return $detail;
    }

    /**
     * 订单商品详情
     * @param int $orderGoodsId 订单商品ID
     * @return \app\common\model\OrderGoods|null
     * @throws BaseException
     */
    public function getRefundGoods(int $orderGoodsId)
    {
        $goods = OrderGoodsModel::detail($orderGoodsId);
        if (isset($goods['refund']) && !empty($goods['refund'])) {
            throwError('当前商品已申请售后');
        }
        return $goods;
    }

    /**
     * 用户发货
     * @param $data
     * @return false|int
     */
    public function delivery(array $data)
    {
        if (
            $this['type'] != RefundTypeEnum::RETURN
            || $this['audit_status'] != AuditStatusEnum::REVIEWED
            || $this['is_user_send'] != 0
        ) {
            $this->error = '当前售后单不合法，不允许该操作';
            return false;
        }
        if ($data['expressId'] <= 0) {
            $this->error = '请选择物流公司';
            return false;
        }
        if (empty($data['expressNo'])) {
            $this->error = '请填写物流单号';
            return false;
        }
        return $this->save([
            'is_user_send' => 1,
            'send_time' => time(),
            'express_id' => (int)$data['expressId'],
            'express_no' => $data['expressNo'],
        ]);
    }

    /**
     * 新增售后单记录
     * @param int $orderGoodsId 订单商品ID
     * @param array $data 用户提交的表单数据
     * @return mixed
     * @throws BaseException
     */
    public function apply(int $orderGoodsId, array $data)
    {
        // 订单商品详情
        $goods = $this->getRefundGoods($orderGoodsId);
        return $this->transaction(function () use ($orderGoodsId, $data, $goods) {
            // 新增售后单记录
            $this->save([
                'order_goods_id' => $orderGoodsId,
                'order_id' => $goods['order_id'],
                'user_id' => UserService::getCurrentLoginUserId(),
                'type' => $data['type'],
                'apply_desc' => $data['content'],
                'audit_status' => AuditStatusEnum::WAIT,
                'status' => 0,
                'store_id' => self::$storeId
            ]);
            // 记录凭证图片关系
            if (isset($data['images']) && !empty($data['images'])) {
                $this->saveImages((int)$this['order_refund_id'], $data['images']);
            }
            return true;
        });
    }

    /**
     * 记录售后单图片
     * @param int $orderRefundId 售后单ID
     * @param array $images 图片列表
     * @return bool
     */
    private function saveImages(int $orderRefundId, array $images)
    {
        // 生成评价图片数据
        $data = [];
        foreach ($images as $imageId) {
            $data[] = [
                'order_refund_id' => $orderRefundId,
                'image_id' => $imageId,
                'store_id' => self::$storeId
            ];
        }
        return !empty($data) && (new OrderRefundImage)->addAll($data) !== false;
    }

}
