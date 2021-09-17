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

use app\api\model\Goods as GoodsModel;
use app\api\model\Setting as SettingModel;
use app\api\model\GoodsSku as GoodsSkuModel;
use app\api\service\User as UserService;
use app\api\service\Payment as PaymentService;
use app\api\service\order\PaySuccess as OrderPaySuccesService;
use app\api\service\order\source\Factory as OrderSourceFactory;
use app\common\model\Order as OrderModel;
use app\common\service\Order as OrderService;
use app\common\service\order\Complete as OrderCompleteService;
use app\common\enum\Setting as SettingEnum;
use app\common\enum\OrderType as OrderTypeEnum;
use app\common\enum\order\PayType as OrderPayTypeEnum;
use app\common\enum\order\PayStatus as PayStatusEnum;
use app\common\enum\order\OrderStatus as OrderStatusEnum;
use app\common\enum\order\DeliveryType as DeliveryTypeEnum;
use app\common\enum\order\ReceiptStatus as ReceiptStatusEnum;
use app\common\enum\order\DeliveryStatus as DeliveryStatusEnum;
use app\common\library\helper;
use app\common\exception\BaseException;

/**
 * 订单模型
 * Class Order
 * @package app\api\model
 */
class Order extends OrderModel
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
     * 待支付订单详情
     * @param string $orderNo 订单号
     * @return null|static
     */
    public static function getPayDetail(string $orderNo)
    {
        return self::detail(['order_no' => $orderNo, 'pay_status' => 10, 'is_delete' => 0], ['goods', 'user']);
    }

    /**
     * 订单支付事件
     * @param int $payType
     * @return bool
     */
    public function onPay(int $payType = OrderPayTypeEnum::WECHAT)
    {
        // 判断订单状态
        $orderSource = OrderSourceFactory::getFactory($this['order_source']);
        if (!$orderSource->checkOrderStatusOnPay($this)) {
            $this->error = $orderSource->getError();
            return false;
        }
        // 余额支付
        if ($payType == OrderPayTypeEnum::BALANCE) {
            return $this->onPaymentByBalance($this['order_no']);
        }
        return true;
    }

    /**
     * 构建支付请求的参数
     * @param self $order 订单信息
     * @param string $payType 订单支付方式
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onOrderPayment(self $order, int $payType)
    {
        if ($payType == OrderPayTypeEnum::WECHAT) {
            return $this->onPaymentByWechat($order);
        }
        return [];
    }

    /**
     * 构建微信支付请求
     * @param self $order 订单详情
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function onPaymentByWechat(self $order)
    {
        return PaymentService::wechat(
            $order['order_id'],
            $order['order_no'],
            $order['pay_price'],
            OrderTypeEnum::ORDER
        );
    }

    /**
     * 立即购买：获取订单商品列表
     * @param int $goodsId 商品ID
     * @param string $goodsSkuId 商品SKU
     * @param int $goodsNum 购买数量
     * @return mixed
     * @throws BaseException
     */
    public function getOrderGoodsListByNow(int $goodsId, string $goodsSkuId, int $goodsNum)
    {
        // 获取商品列表
        $model = new GoodsModel;
        $goodsList = $model->getListByIdsFromApi([$goodsId]);
        if ($goodsList->isEmpty()) {
            throwError('未找到商品信息');
        }
        // 隐藏冗余的属性
        $goodsList->hidden(array_merge($model->hidden, ['content', 'goods_images', 'images']));
        foreach ($goodsList as &$item) {
            // 商品sku信息
            $item['skuInfo'] = GoodsSkuModel::detail($item['goods_id'], $goodsSkuId);
            // 商品单价
            $item['goods_price'] = $item['skuInfo']['goods_price'];
            // 商品购买数量
            $item['total_num'] = $goodsNum;
            // 商品SKU索引
            $item['goods_sku_id'] = $item['skuInfo']['goods_sku_id'];
            // 商品购买总金额
            $item['total_price'] = helper::bcmul($item['goods_price'], $goodsNum);
        }
        return $goodsList;
    }

    /**
     * 余额支付标记订单已支付
     * @param string $orderNo 订单号
     * @return bool
     */
    public function onPaymentByBalance(string $orderNo)
    {
        // 获取订单详情
        $service = new OrderPaySuccesService($orderNo);
        // 发起余额支付
        $status = $service->onPaySuccess(OrderPayTypeEnum::BALANCE);
        if (!$status) {
            $this->error = $service->getError();
        }
        return $status;
    }

    /**
     * 获取用户订单列表
     * @param string $type 订单类型 (all全部 payment待付款 received待发货 deliver待收货 comment待评价)
     * @return \think\Paginator
     * @throws BaseException
     * @throws \think\db\exception\DbException
     */
    public function getList($type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment':
                $filter['pay_status'] = PayStatusEnum::PENDING;
                $filter['order_status'] = OrderStatusEnum::NORMAL;
                break;
            case 'delivery':
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = DeliveryStatusEnum::NOT_DELIVERED;
                $filter['order_status'] = OrderStatusEnum::NORMAL;
                break;
            case 'received':
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = DeliveryStatusEnum::DELIVERED;
                $filter['receipt_status'] = ReceiptStatusEnum::NOT_RECEIVED;
                $filter['order_status'] = OrderStatusEnum::NORMAL;
                break;
            case 'comment':
                $filter['is_comment'] = 0;
                $filter['order_status'] = OrderStatusEnum::COMPLETED;
                break;
        }
        // 当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 查询列表数据
        return $this->with(['goods.image'])
            ->where($filter)
            ->where('user_id', '=', $userId)
            ->where('is_delete', '=', 0)
            ->order(['create_time' => 'desc'])
            ->paginate(15);
    }

    /**
     * 取消订单
     * @return bool|mixed
     */
    public function cancel()
    {
        if ($this['delivery_status'] == DeliveryStatusEnum::DELIVERED) {
            $this->error = '已发货订单不可取消';
            return false;
        }
        // 订单取消事件
        return $this->transaction(function () {
            // 订单是否已支付
            $isPay = $this['pay_status'] == PayStatusEnum::SUCCESS;
            // 订单取消事件
            $isPay == false && OrderService::cancelEvent($this);
            // 更新订单状态: 已付款的订单设置为"待取消", 等待后台审核
            return $this->save(['order_status' => $isPay ? OrderStatusEnum::APPLY_CANCEL : OrderStatusEnum::CANCELLED]);
        });
    }

    /**
     * 确认收货
     * @return bool|mixed
     */
    public function receipt()
    {
        // 验证订单是否合法
        // 条件1: 订单必须已发货
        // 条件2: 订单必须未收货
        if ($this['delivery_status'] != 20 || $this['receipt_status'] != 10) {
            $this->error = '该订单不合法';
            return false;
        }
        return $this->transaction(function () {
            // 更新订单状态
            $status = $this->save([
                'receipt_status' => 20,
                'receipt_time' => time(),
                'order_status' => 30
            ]);
            // 执行订单完成后的操作
            $OrderCompleteService = new OrderCompleteService(OrderTypeEnum::ORDER);
            $OrderCompleteService->complete([$this], static::$storeId);
            return $status;
        });
    }

    /**
     * 获取当前用户订单数量
     * @param string $type 订单类型 (all全部 payment待付款 received待发货 deliver待收货 comment待评价)
     * @return int
     * @throws BaseException
     */
    public function getCount($type = 'all')
    {
        // 筛选条件
        $filter = [];
        // 订单数据类型
        switch ($type) {
            case 'all':
                break;
            case 'payment':
                $filter['pay_status'] = PayStatusEnum::PENDING;
                break;
            case 'received':
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = DeliveryStatusEnum::DELIVERED;
                $filter['receipt_status'] = ReceiptStatusEnum::NOT_RECEIVED;
                break;
            case 'delivery':
                $filter['pay_status'] = PayStatusEnum::SUCCESS;
                $filter['delivery_status'] = DeliveryStatusEnum::NOT_DELIVERED;
                $filter['order_status'] = OrderStatusEnum::NORMAL;
                break;
            case 'comment':
                $filter['is_comment'] = 0;
                $filter['order_status'] = OrderStatusEnum::COMPLETED;
                break;
        }
        // 当前用户ID
        $userId = UserService::getCurrentLoginUserId();
        // 查询数据
        return $this->where('user_id', '=', $userId)
            ->where('order_status', '<>', 20)
            ->where($filter)
            ->where('is_delete', '=', 0)
            ->count();
    }

    /**
     * 获取用户订单详情(含关联数据)
     * @param int $orderId 订单ID
     * @return Order|array|null
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getUserOrderDetail(int $orderId)
    {
        // 关联查询
        $with = [
            'goods' => ['image', 'goods', 'refund'],
            'address', 'express'
        ];
        // 查询订单记录
        $order = static::getDetail($orderId, $with);
        // 该订单是否允许申请售后
        $order['isAllowRefund'] = static::isAllowRefund($order);
        return $order;
    }

    /**
     * 获取用户订单详情(仅订单记录)
     * @param int $orderId
     * @param array $with
     * @return Order|array|null
     * @throws BaseException
     */
    public static function getDetail(int $orderId, $with = [])
    {
        // 查询订单记录
        $order = static::detail([
            'order_id' => $orderId,
            'user_id' => UserService::getCurrentLoginUserId(),
        ], $with);
        empty($order) && throwError('订单不存在');
        return $order;
    }

    /**
     * 当前订单是否允许申请售后
     * @param Order $order
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private static function isAllowRefund(self $order)
    {
        // 必须是已发货的订单
        if ($order['delivery_status'] != DeliveryStatusEnum::DELIVERED) {
            return false;
        }
        // 允许申请售后期限(天)
        $refundDays = SettingModel::getItem(SettingEnum::TRADE)['order']['refund_days'];
        // 不允许售后
        if ($refundDays == 0) {
            return false;
        }
        // 当前时间超出允许申请售后期限
        if (
            $order['receipt_status'] == ReceiptStatusEnum::RECEIVED
            && time() > ($order->getData('receipt_time') + ((int)$refundDays * 86400))
        ) {
            return false;
        }
        return true;
    }

    /**
     * 获取当前用户待处理的订单数量
     * @return array
     * @throws BaseException
     */
    public function getTodoCounts()
    {
        return [
            'payment' => $this->getCount('payment'),
            'delivery' => $this->getCount('delivery'),
            'received' => $this->getCount('received')
        ];
    }

    /**
     * 设置错误信息
     * @param $error
     */
    protected function setError($error)
    {
        empty($this->error) && $this->error = $error;
    }

    /**
     * 是否存在错误
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->error);
    }

}
