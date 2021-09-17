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

namespace app\admin\controller;

use app\common\exception\BaseException;
use app\admin\service\admin\User as AdminUserService;

/**
 * 超管后台控制器基类
 * Class Controller
 * @package app\admin\controller
 */
class Controller extends \app\BaseController
{
    // 商家登录信息
    protected $admin;

    // 当前控制器名称
    protected $controller = '';

    // 当前方法名称
    protected $action = '';

    // 当前路由uri
    protected $routeUri = '';

    // 当前路由：分组名称
    protected $group = '';

    // 登录验证白名单
    protected $allowAllAction = [
        // 登录页面
        'passport/login',
    ];

    // 无需全局layout
    protected $notLayoutAction = [];

    /**
     * 强制验证当前访问的控制器方法method
     * 例: [ 'login' => 'POST' ]
     * @var array
     */
    protected $methodRules = [];

    /**
     * 后台初始化
     * @throws \Exception
     */
    public function initialize()
    {
        // 设置管理员登录信息
        $this->setAdminInfo();
        // 当前路由信息
        $this->getRouteInfo();
        // 验证登录
        $this->checkLogin();
        // 强制验证当前访问的控制器方法method
        $this->checkMethodRules();
    }

    /**
     * 设置管理员登录信息
     */
    private function setAdminInfo()
    {
        $this->admin = AdminUserService::getLoginInfo();
    }

    /**
     * 解析当前路由参数 （分组名称、控制器名称、方法名）
     */
    protected function getRouteInfo()
    {
        // 控制器名称
        $this->controller = uncamelize($this->request->controller());
        // 方法名称
        $this->action = $this->request->action();
        // 控制器分组 (用于定义所属模块)
        $group = strstr($this->controller, '.', true);
        $this->group = $group !== false ? $group : $this->controller;
        // 当前uri
        $this->routeUri = "{$this->controller}/$this->action";
    }

    /**
     * 后台菜单配置
     * @return array
     */
    private function menus()
    {
        // 获取后台菜单内容 [app/admin/config/menus.php]
        $menus = \think\facade\Config::instance()->get('menus');
        foreach ($menus as $group => &$first) {
            $first['active'] = $group === $this->group;
            // 遍历：二级菜单
            if (isset($first['submenu'])) {
                foreach ($first['submenu'] as $secondKey => &$second) {
                    // 二级菜单所有uri
                    $secondUris = isset($second['uris']) ? $second['uris'] : [$second['index']];
                    // 二级菜单：active
                    !isset($second['active']) && $second['active'] = in_array($this->routeUri, $secondUris);
                }
            }
        }
        return $menus;
    }

    /**
     * 验证登录状态
     * @return bool
     * @throws BaseException
     */
    private function checkLogin()
    {
        // 验证当前请求是否在白名单
        if (in_array($this->routeUri, $this->allowAllAction)) {
            return true;
        }
        // 验证登录状态
        if (empty($this->admin) || (int)$this->admin['is_login'] !== 1) {
            throwError('请先登录后再访问', config('status.not_logged'));
        }
        return true;
    }

    /**
     * 返回封装后的 API 数据到客户端
     * @param int|null $status 状态码
     * @param string $message
     * @param array $data
     * @return array
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
        return $this->request->post(empty($key) ? '' : "{$key}/a");
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

    /**
     * 强制验证当前访问的控制器方法method
     * @throws BaseException
     */
    private function checkMethodRules()
    {
        if (!isset($this->methodRules[$this->action])) {
            return true;
        }
        $methodRule = $this->methodRules[$this->action];
        $currentMethod = $this->request->method();
        if (empty($methodRule)) {
            return true;
        }
        if (is_array($methodRule) && in_array($currentMethod, $methodRule)) {
            return true;
        }
        if (is_string($methodRule) && $methodRule == $currentMethod) {
            return true;
        }
        throwError('illegal request method');
    }

}
