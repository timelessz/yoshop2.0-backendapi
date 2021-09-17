<?php

namespace app;

// 应用请求对象类
class Request extends \think\Request
{
    // 全局过滤规则
    protected $filter = ['my_trim', 'my_htmlspecialchars', 'filter_emoji'];

}
