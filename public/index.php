<?php

// [ 应用入口文件 ]
namespace think;

// 检测PHP环境
if (version_compare(PHP_VERSION, '7.1.0', '<')) die('require PHP > 7.1.0 !');

// 加载核心文件
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);

