# 萤火商城V2.0开源版

#### 项目介绍
萤火商城V2.0，是2021年全新推出的一款轻量级、高性能、前后端分离的电商系统，支持微信小程序 + H5+ 公众号 + APP，前后端源码完全开源，看见及所得，完美支持二次开发，可学习可商用，让您快速搭建个性化独立商城。

    如果对您有帮助，您可以点右上角 “Star” 收藏一下 ，获取第一时间更新，谢谢！

#### 技术特点
* 前后端完全分离 (互不依赖 开发效率高)
* 采用PHP7.2 (强类型严格模式)
* Thinkphp6.0.5（轻量级PHP开发框架）
* Uni-APP（开发跨平台应用的前端框架）
* Ant Design Vue（企业级中后台产品UI组件库）
* RBAC（基于角色的权限控制管理）
* 部署运行的项目体积仅30多MB（真正的轻量化）

#### 页面展示
![前端展示](https://images.gitee.com/uploads/images/2021/0316/215102_7bcb0802_2166072.png "前端展示.png")
![后台-首页](https://images.gitee.com/uploads/images/2021/0316/215827_7df5251c_2166072.png "后台-首页.png")
![后台-页面设计](https://images.gitee.com/uploads/images/2021/0316/215839_2d4ebccc_2166072.png "后台-页面设计.png")
![后台-编辑商品](https://images.gitee.com/uploads/images/2021/0316/215848_9d54adff_2166072.png "后台-编辑商品.png")
![后台-订单详情](https://images.gitee.com/uploads/images/2021/0316/215855_8606fce3_2166072.png "后台-订单详情.png")

#### 系统演示

- 商城后台演示：https://shop2.yiovo.com/admin/
- 用户名和密码：admin yinghuo
![前端演示二维码](https://images.gitee.com/uploads/images/2021/0316/104516_3778337e_2166072.png "111.png")

#### 源码下载
1. 主商城端（又称后端、服务端，PHP开发 用于管理后台和提供api接口）

    下载地址：https://gitee.com/xany/yoshop2.0

2. 用户端（也叫客户端、前端，uniapp开发 用于生成H5和微信小程序）

    下载地址：https://gitee.com/xany/yoshop2.0-uniapp

2. 后台VUE端（指的是商城后台的前端代码，使用vue2编写，分store模块和admin模块）

    下载地址：https://gitee.com/xany/yoshop2.0-store

    下载地址：https://gitee.com/xany/yoshop2.0-admin

#### 环境要求
- CentOS 7.0+
- Nginx 1.10+
- PHP 7.1+
- MySQL 5.6+


#### 如何安装
##### 一、自动安装（推荐）

1. 将后端源码上传至服务器站点，并且将站点运行目录设置为/public
2. 在浏览器中输入站点域名 + /install，例如：https://www.你的域名.com/install
3. 根据页面提示，自动完成安装即可

##### 二、手动安装（不推荐）

1. 将后端源码上传至服务器站点，并且将站点运行目录设置为/public
2. 创建一个数据库，例如：yoshop2_db
3. 导入数据库表结构文件，路径：/public/install/data/install_struct.sql
4. 导入数据库默认数据文件，路径：/public/install/data/install_data.sql
5. 修改数据库连接文件，将数据库用户名密码等信息填写完整，路径/.env

#### 后台地址

- 超管后台：https://www.你的域名.com/admin
- 商户后台：https://www.你的域名.com/store
- 默认的账户密码：admin yinghuo

#### 定时任务
用于自动处理订单状态、优惠券状态、会员等级等
```sh
php think timer start
```
#### 版权须知

1. 允许个人学习研究使用，支持二次开发，允许商业用途（仅限自运营）。
2. 允许商业用途，但仅限自运营，如果商用必须保留版权信息，望自觉遵守。
3. 不允许对程序代码以任何形式任何目的的再发行或出售，否则将追究侵权者法律责任。


本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2017-2021 By 萤火科技 (https://www.yiovo.com) All rights reserved。





