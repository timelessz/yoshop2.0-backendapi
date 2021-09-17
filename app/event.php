<?php

// 事件定义文件
return [
    'bind' => [
    ],

    'listen' => [
        'AppInit' => [],
        'HttpRun' => [],
        'HttpEnd' => [],
        'LogLevel' => [],
        'LogWrite' => [],

        // 定时任务：商城模块
        'StoreTask' => [
            \app\console\task\Store::class,
        ],

        // 定时任务：商城订单
        'Order' => [
            \app\console\task\Order::class
        ],

        // 定时任务：用户优惠券
        'UserCoupon' => [
            \app\console\task\UserCoupon::class
        ],

        // 定时任务：会员等级
        'UserGrade' => [
            \app\console\task\UserGrade::class
        ],

    ],

];
