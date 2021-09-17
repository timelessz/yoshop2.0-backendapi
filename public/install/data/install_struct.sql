
DROP TABLE IF EXISTS `yoshop_admin_user`;
CREATE TABLE `yoshop_admin_user` (
  `admin_user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '登录密码',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`admin_user_id`),
  KEY `user_name` (`user_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='超管用户记录表';

DROP TABLE IF EXISTS `yoshop_article`;
CREATE TABLE `yoshop_article` (
  `article_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `title` varchar(300) NOT NULL DEFAULT '' COMMENT '文章标题',
  `show_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '列表显示方式(10小图展示 20大图展示)',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章分类ID',
  `image_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '封面图ID',
  `content` longtext NOT NULL COMMENT '文章内容',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章排序(数字越小越靠前)',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '文章状态(0隐藏 1显示)',
  `virtual_views` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟阅读量(仅用作展示)',
  `actual_views` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '实际阅读量',
  `is_delete` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`article_id`),
  KEY `category_id` (`category_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='文章记录表';

DROP TABLE IF EXISTS `yoshop_article_category`;
CREATE TABLE `yoshop_article_category` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章分类ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `status` tinyint(3) NOT NULL DEFAULT '1' COMMENT '状态(1显示 0隐藏)',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序方式(数字越小越靠前)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`category_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='文章分类表';

DROP TABLE IF EXISTS `yoshop_cart`;
CREATE TABLE `yoshop_cart` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_sku_id` varchar(255) NOT NULL COMMENT '商品sku唯一标识',
  `goods_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品数量',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`),
  KEY `goods_id_2` (`goods_id`,`goods_sku_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='购物车记录表';

DROP TABLE IF EXISTS `yoshop_category`;
CREATE TABLE `yoshop_category` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品分类ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `image_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类图片ID',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态(1显示 0隐藏)',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序方式(数字越小越靠前)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`category_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品分类表';

DROP TABLE IF EXISTS `yoshop_comment`;
CREATE TABLE `yoshop_comment` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '评价ID',
  `score` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '评分 (10好评 20中评 30差评)',
  `content` text NOT NULL COMMENT '评价内容',
  `is_picture` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为图片评价',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态(0隐藏 1显示)',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评价排序',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `order_goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单商品ID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `is_delete` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '软删除',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`comment_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `goods_id` (`goods_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品评价记录表';

DROP TABLE IF EXISTS `yoshop_comment_image`;
CREATE TABLE `yoshop_comment_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `comment_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '评价ID',
  `image_id` int(11) NOT NULL DEFAULT '0' COMMENT '图片id(关联文件记录表)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `comment_id` (`comment_id`) USING BTREE,
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品评价图片记录表';

DROP TABLE IF EXISTS `yoshop_coupon`;
CREATE TABLE `yoshop_coupon` (
  `coupon_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '优惠券ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `coupon_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '优惠券类型(10满减券 20折扣券)',
  `reduce_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '满减券-减免金额',
  `discount` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '折扣券-折扣率(0-100)',
  `min_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最低消费金额',
  `expire_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '到期类型(10领取后生效 20固定时间)',
  `expire_day` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '领取后生效-有效天数',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '固定时间-开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '固定时间-结束时间',
  `apply_range` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '适用范围(10全部商品 20指定商品 30排除商品)',
  `apply_range_config` text COMMENT '适用范围配置(json格式)',
  `total_num` int(11) NOT NULL DEFAULT '0' COMMENT '发放总数量(-1为不限制)',
  `receive_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '已领取数量',
  `describe` varchar(500) NOT NULL DEFAULT '' COMMENT '优惠券描述',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态(1显示 0隐藏)',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序方式(数字越小越靠前)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '软删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`coupon_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='优惠券记录表';

DROP TABLE IF EXISTS `yoshop_delivery`;
CREATE TABLE `yoshop_delivery` (
  `delivery_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '模板ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '模板名称',
  `method` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '计费方式(10按件数 20按重量)',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序方式(数字越小越靠前)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序d',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`delivery_id`,`is_delete`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='配送模板主表';

DROP TABLE IF EXISTS `yoshop_delivery_rule`;
CREATE TABLE `yoshop_delivery_rule` (
  `rule_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '规则ID',
  `delivery_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '配送模板ID',
  `region` text NOT NULL COMMENT '可配送区域(城市id集)',
  `region_text` text NOT NULL COMMENT '可配送区域(文字展示)',
  `first` double unsigned NOT NULL DEFAULT '0' COMMENT '首件(个)/首重(Kg)',
  `first_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '运费(元)',
  `additional` double unsigned NOT NULL DEFAULT '0' COMMENT '续件/续重',
  `additional_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '续费(元)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`rule_id`),
  KEY `delivery_id` (`delivery_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='配送模板区域及运费表';

DROP TABLE IF EXISTS `yoshop_express`;
CREATE TABLE `yoshop_express` (
  `express_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '物流公司ID',
  `express_name` varchar(255) NOT NULL DEFAULT '' COMMENT '物流公司名称',
  `kuaidi100_code` varchar(30) NOT NULL DEFAULT '' COMMENT '物流公司编码 (快递100)',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`express_id`),
  KEY `store_id` (`store_id`),
  KEY `kuaidi100_code` (`kuaidi100_code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='物流公司记录表';

DROP TABLE IF EXISTS `yoshop_goods`;
CREATE TABLE `yoshop_goods` (
  `goods_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_no` varchar(50) NOT NULL DEFAULT '' COMMENT '商品编码',
  `selling_point` varchar(500) NOT NULL DEFAULT '' COMMENT '商品卖点',
  `spec_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '商品规格(10单规格 20多规格)',
  `goods_price_min` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格(最低)',
  `goods_price_max` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格(最高)',
  `line_price_min` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '划线价格(最低)',
  `line_price_max` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '划线价格(最高)',
  `stock_total` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '库存总量(包含所有sku)',
  `deduct_stock_type` tinyint(3) unsigned NOT NULL DEFAULT '20' COMMENT '库存计算方式(10下单减库存 20付款减库存)',
  `content` longtext NOT NULL COMMENT '商品详情',
  `sales_initial` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '初始销量',
  `sales_actual` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '实际销量',
  `delivery_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '配送模板ID',
  `is_points_gift` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启积分赠送(1开启 0关闭)',
  `is_points_discount` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许使用积分抵扣(1允许 0不允许)',
  `is_alone_points_discount` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '积分抵扣设置(0默认抵扣 1单独设置抵扣)',
  `points_discount_config` varchar(500) NOT NULL DEFAULT '' COMMENT '单独设置积分抵扣的配置',
  `is_enable_grade` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启会员折扣(1开启 0关闭)',
  `is_alone_grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '会员折扣设置(0默认等级折扣 1单独设置折扣)',
  `alone_grade_equity` text COMMENT '单独设置折扣的配置',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '商品状态(10上架 20下架)',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`goods_id`),
  KEY `goods_no` (`goods_no`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品记录表';

DROP TABLE IF EXISTS `yoshop_goods_category_rel`;
CREATE TABLE `yoshop_goods_category_rel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` int(11) unsigned NOT NULL COMMENT '商品ID',
  `category_id` int(11) unsigned NOT NULL COMMENT '商品分类ID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `goods_id` (`goods_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品与分类关系记录表';

DROP TABLE IF EXISTS `yoshop_goods_image`;
CREATE TABLE `yoshop_goods_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `image_id` int(11) NOT NULL COMMENT '图片id(关联文件记录表)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品图片记录表';

DROP TABLE IF EXISTS `yoshop_goods_service`;
CREATE TABLE `yoshop_goods_service` (
  `service_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品服务ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '服务名称',
  `summary` varchar(500) NOT NULL DEFAULT '' COMMENT '概述',
  `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否默认(新增商品时)',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态(1显示 0隐藏)',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序方式(数字越小越靠前)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除(1已删除)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`service_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品服务与承诺记录表';

DROP TABLE IF EXISTS `yoshop_goods_service_rel`;
CREATE TABLE `yoshop_goods_service_rel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` int(11) unsigned NOT NULL COMMENT '商品ID',
  `service_id` int(11) unsigned NOT NULL COMMENT '服务承诺ID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  KEY `goods_id` (`goods_id`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品与服务承诺关系记录表';

DROP TABLE IF EXISTS `yoshop_goods_sku`;
CREATE TABLE `yoshop_goods_sku` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `goods_sku_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '商品sku唯一标识 (由规格id组成)',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `image_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规格图片ID',
  `goods_sku_no` varchar(100) NOT NULL DEFAULT '' COMMENT '商品sku编码',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `line_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品划线价',
  `stock_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '当前库存数量',
  `goods_weight` double unsigned NOT NULL DEFAULT '0' COMMENT '商品重量(Kg)',
  `goods_props` varchar(255) NOT NULL DEFAULT '' COMMENT 'SKU的规格属性(json格式)',
  `spec_value_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '规格值ID集(json格式)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku_idx` (`goods_id`,`goods_sku_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品规格表';

DROP TABLE IF EXISTS `yoshop_goods_spec_rel`;
CREATE TABLE `yoshop_goods_spec_rel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `spec_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规格组ID',
  `spec_value_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规格值ID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品与规格值关系记录表';

DROP TABLE IF EXISTS `yoshop_help`;
CREATE TABLE `yoshop_help` (
  `help_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '帮助标题',
  `content` text NOT NULL COMMENT '帮助内容',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除(1已删除)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`help_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='帮助中心记录表';

DROP TABLE IF EXISTS `yoshop_order`;
CREATE TABLE `yoshop_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_no` varchar(20) NOT NULL DEFAULT '' COMMENT '订单号',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品总金额(不含优惠折扣)',
  `order_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单金额(含优惠折扣)',
  `coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券ID',
  `coupon_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠券抵扣金额',
  `points_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '积分抵扣金额',
  `points_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分抵扣数量',
  `pay_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际付款金额(包含运费)',
  `update_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '后台修改的订单金额（差价）',
  `buyer_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '买家留言',
  `pay_type` tinyint(3) unsigned NOT NULL DEFAULT '20' COMMENT '支付方式(10余额支付 20微信支付)',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '付款状态(10未付款 20已付款)',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `delivery_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '配送方式(10快递配送)',
  `express_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '运费金额',
  `express_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '物流公司ID',
  `express_company` varchar(50) NOT NULL DEFAULT '' COMMENT '物流公司',
  `express_no` varchar(50) NOT NULL DEFAULT '' COMMENT '物流单号',
  `delivery_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '发货状态(10未发货 20已发货)',
  `delivery_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '发货时间',
  `receipt_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '收货状态(10未收货 20已收货)',
  `receipt_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收货时间',
  `order_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '订单状态(10进行中 20取消 21待取消 30已完成)',
  `points_bonus` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '赠送的积分数量',
  `is_settled` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '订单是否已结算(0未结算 1已结算)',
  `transaction_id` varchar(30) NOT NULL DEFAULT '' COMMENT '微信支付交易号',
  `is_comment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否已评价(0否 1是)',
  `order_source` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '订单来源(10普通订单)',
  `order_source_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '来源记录ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_no` (`order_no`) USING BTREE,
  KEY `store_id` (`store_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='订单记录表';

DROP TABLE IF EXISTS `yoshop_order_address`;
CREATE TABLE `yoshop_order_address` (
  `order_address_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '地址ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '省份ID',
  `city_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '城市ID',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '区/县ID',
  `detail` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`order_address_id`) USING BTREE,
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='订单收货地址记录表';

DROP TABLE IF EXISTS `yoshop_order_goods`;
CREATE TABLE `yoshop_order_goods` (
  `order_goods_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `image_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品封面图ID',
  `deduct_stock_type` tinyint(3) unsigned NOT NULL DEFAULT '20' COMMENT '库存计算方式(10下单减库存 20付款减库存)',
  `spec_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '规格类型(10单规格 20多规格)',
  `goods_sku_id` varchar(255) NOT NULL DEFAULT '' COMMENT '商品sku唯一标识',
  `goods_props` varchar(255) NOT NULL DEFAULT '' COMMENT 'SKU的规格属性(json格式)',
  `content` longtext NOT NULL COMMENT '商品详情',
  `goods_no` varchar(100) NOT NULL DEFAULT '' COMMENT '商品编码',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格(单价)',
  `line_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品划线价',
  `goods_weight` double unsigned NOT NULL DEFAULT '0' COMMENT '商品重量(Kg)',
  `is_user_grade` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否存在会员等级折扣',
  `grade_ratio` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '会员折扣比例(0-10)',
  `grade_goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员折扣的商品单价',
  `grade_total_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '会员折扣的总额差',
  `coupon_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠券折扣金额',
  `points_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '积分金额',
  `points_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分抵扣数量',
  `points_bonus` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '赠送的积分数量',
  `total_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '购买数量',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品总价(数量×单价)',
  `total_pay_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际付款价(折扣和优惠后)',
  `is_comment` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '是否已评价(0否 1是)',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `goods_source_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '来源记录ID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`order_goods_id`) USING BTREE,
  KEY `goods_id` (`goods_id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='订单商品记录表';

DROP TABLE IF EXISTS `yoshop_order_refund`;
CREATE TABLE `yoshop_order_refund` (
  `order_refund_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '售后单ID',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `order_goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单商品ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '售后类型(10退货退款 20换货)',
  `apply_desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '用户申请原因(说明)',
  `audit_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '商家审核状态(0待审核 10已同意 20已拒绝)',
  `refuse_desc` varchar(1000) NOT NULL DEFAULT '' COMMENT '商家拒绝原因(说明)',
  `refund_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际退款金额',
  `is_user_send` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '用户是否发货(0未发货 1已发货)',
  `send_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户发货时间',
  `express_id` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货物流公司ID',
  `express_no` varchar(32) NOT NULL DEFAULT '' COMMENT '用户发货物流单号',
  `is_receipt` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '商家收货状态(0未收货 1已收货)',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '售后单状态(0进行中 10已拒绝 20已完成 30已取消)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`order_refund_id`),
  KEY `order_id` (`order_id`),
  KEY `order_goods_id` (`order_goods_id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='售后单记录表';

DROP TABLE IF EXISTS `yoshop_order_refund_address`;
CREATE TABLE `yoshop_order_refund_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_refund_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '售后单ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在省份ID',
  `city_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在城市ID',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '所在区/县ID',
  `detail` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='售后单退货地址记录表';

DROP TABLE IF EXISTS `yoshop_order_refund_image`;
CREATE TABLE `yoshop_order_refund_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_refund_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '售后单ID',
  `image_id` int(11) NOT NULL DEFAULT '0' COMMENT '图片id(关联文件记录表)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `order_refund_id` (`order_refund_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='售后单图片记录表';

DROP TABLE IF EXISTS `yoshop_page`;
CREATE TABLE `yoshop_page` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '页面ID',
  `page_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '页面类型(10首页 20自定义页)',
  `page_name` varchar(255) NOT NULL DEFAULT '' COMMENT '页面名称',
  `page_data` longtext NOT NULL COMMENT '页面数据',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '软删除',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`page_id`),
  KEY `store_id` (`store_id`),
  KEY `page_type` (`page_type`,`store_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='店铺页面记录表';

DROP TABLE IF EXISTS `yoshop_recharge_order`;
CREATE TABLE `yoshop_recharge_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单ID',
  `order_no` varchar(20) NOT NULL DEFAULT '' COMMENT '订单号',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `recharge_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '充值方式(10自定义金额 20套餐充值)',
  `plan_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '充值套餐ID',
  `pay_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户支付金额',
  `gift_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '赠送金额',
  `actual_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际到账金额',
  `pay_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '支付状态(10待支付 20已支付)',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '付款时间',
  `transaction_id` varchar(30) NOT NULL DEFAULT '' COMMENT '微信支付交易号',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`order_id`),
  KEY `order_no` (`order_no`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='会员充值订单表';

DROP TABLE IF EXISTS `yoshop_recharge_order_plan`;
CREATE TABLE `yoshop_recharge_order_plan` (
  `order_plan_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `plan_id` int(11) unsigned NOT NULL COMMENT '主键ID',
  `plan_name` varchar(255) NOT NULL DEFAULT '' COMMENT '方案名称',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `gift_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '赠送金额',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`order_plan_id`),
  KEY `order_id` (`order_id`),
  KEY `plan_id` (`plan_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='会员充值订单套餐快照表';

DROP TABLE IF EXISTS `yoshop_recharge_plan`;
CREATE TABLE `yoshop_recharge_plan` (
  `plan_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `plan_name` varchar(255) NOT NULL DEFAULT '' COMMENT '套餐名称',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `gift_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '赠送金额',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`plan_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='会员充值套餐表';

DROP TABLE IF EXISTS `yoshop_region`;
CREATE TABLE `yoshop_region` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '区划信息ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '区划名称',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '区划编码',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '层级(1省级 2市级 3区/县级)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='省市区数据表';

DROP TABLE IF EXISTS `yoshop_spec`;
CREATE TABLE `yoshop_spec` (
  `spec_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '规格组ID',
  `spec_name` varchar(255) NOT NULL DEFAULT '' COMMENT '规格组名称',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`spec_id`),
  KEY `spec_name` (`spec_name`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品规格组记录表';

DROP TABLE IF EXISTS `yoshop_spec_value`;
CREATE TABLE `yoshop_spec_value` (
  `spec_value_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '规格值ID',
  `spec_value` varchar(255) NOT NULL COMMENT '规格值',
  `spec_id` int(11) NOT NULL COMMENT '规格组ID',
  `store_id` int(11) NOT NULL COMMENT '商城ID',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`spec_value_id`),
  KEY `spec_value` (`spec_value`),
  KEY `spec_id` (`spec_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商品规格值记录表';

DROP TABLE IF EXISTS `yoshop_store`;
CREATE TABLE `yoshop_store` (
  `store_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商城ID',
  `store_name` varchar(50) NOT NULL DEFAULT '' COMMENT '商城名称',
  `describe` varchar(500) NOT NULL DEFAULT '' COMMENT '商城简介',
  `logo_image_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城logo文件ID',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `is_recycle` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否回收',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商家(商户)记录表';

DROP TABLE IF EXISTS `yoshop_store_address`;
CREATE TABLE `yoshop_store_address` (
  `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '地址ID',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '地址类型(10发货地址 20退货地址)',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '省份ID',
  `city_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '城市ID',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '区/县ID',
  `detail` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`address_id`),
  KEY `type` (`type`),
  KEY `store_id` (`store_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商家地址记录表';

DROP TABLE IF EXISTS `yoshop_store_api`;
CREATE TABLE `yoshop_store_api` (
  `api_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '权限名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '权限url',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序(数字越小越靠前)',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`api_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商家后台api权限表';

DROP TABLE IF EXISTS `yoshop_store_menu`;
CREATE TABLE `yoshop_store_menu` (
  `menu_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `module` tinyint(3) NOT NULL DEFAULT '10' COMMENT '模块类型(10菜单 20操作)',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '菜单路径(唯一)',
  `action_mark` varchar(255) NOT NULL DEFAULT '' COMMENT '操作标识',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单ID',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序(数字越小越靠前)',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商家后台菜单记录表';

DROP TABLE IF EXISTS `yoshop_store_menu_api`;
CREATE TABLE `yoshop_store_menu_api` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `menu_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '菜单ID',
  `api_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户角色ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商家后台用户角色与菜单权限关系表';

DROP TABLE IF EXISTS `yoshop_store_role`;
CREATE TABLE `yoshop_store_role` (
  `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级角色ID',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序(数字越小越靠前)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商家用户角色表';

DROP TABLE IF EXISTS `yoshop_store_role_menu`;
CREATE TABLE `yoshop_store_role_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户角色ID',
  `menu_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '菜单ID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商家后台用户角色与菜单权限关系表';

DROP TABLE IF EXISTS `yoshop_store_setting`;
CREATE TABLE `yoshop_store_setting` (
  `key` varchar(30) NOT NULL COMMENT '设置项标示',
  `describe` varchar(255) NOT NULL DEFAULT '' COMMENT '设置项描述',
  `values` mediumtext NOT NULL COMMENT '设置内容（json格式）',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  UNIQUE KEY `unique_key` (`key`,`store_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商家设置记录表';

DROP TABLE IF EXISTS `yoshop_store_user`;
CREATE TABLE `yoshop_store_user` (
  `store_user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '登录密码',
  `real_name` varchar(255) NOT NULL DEFAULT '' COMMENT '姓名',
  `is_super` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否为超级管理员',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `sort` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序(数字越小越靠前)',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`store_user_id`),
  KEY `user_name` (`user_name`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商家用户记录表';

DROP TABLE IF EXISTS `yoshop_store_user_role`;
CREATE TABLE `yoshop_store_user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `store_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '超管用户ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `store_user_id` (`store_user_id`) USING BTREE,
  KEY `role_id` (`role_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='商家用户角色记录表';

DROP TABLE IF EXISTS `yoshop_upload_file`;
CREATE TABLE `yoshop_upload_file` (
  `file_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `group_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件分组ID',
  `channel` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '上传来源(10商户后台 20用户端)',
  `storage` varchar(10) NOT NULL DEFAULT '' COMMENT '存储方式',
  `domain` varchar(255) NOT NULL DEFAULT '' COMMENT '存储域名',
  `file_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '文件类型(10图片 20附件 30视频)',
  `file_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名称(仅显示)',
  `file_path` varchar(255) NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小(字节)',
  `file_ext` varchar(20) NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '文件封面',
  `uploader_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上传者用户ID',
  `is_recycle` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否在回收站',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`file_id`),
  KEY `group_id` (`group_id`),
  KEY `is_recycle` (`is_recycle`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='文件库记录表';

DROP TABLE IF EXISTS `yoshop_upload_group`;
CREATE TABLE `yoshop_upload_group` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分组ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '分组名称',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级分组ID',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`group_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='文件库分组记录表';

DROP TABLE IF EXISTS `yoshop_user`;
CREATE TABLE `yoshop_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `open_id` varchar(255) NOT NULL DEFAULT '' COMMENT '微信openid(唯一标示)',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `nick_name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `avatar_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '头像文件ID',
  `gender` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `country` varchar(50) NOT NULL DEFAULT '' COMMENT '国家',
  `province` varchar(50) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(50) NOT NULL DEFAULT '' COMMENT '城市',
  `address_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '默认收货地址',
  `balance` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户可用余额',
  `points` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户可用积分',
  `pay_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '用户总支付的金额',
  `expend_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '实际消费的金额(不含退款)',
  `grade_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '会员等级ID',
  `platform` varchar(20) NOT NULL DEFAULT '' COMMENT '注册来源的平台 (APP、H5、小程序等)',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_id`),
  KEY `openid` (`open_id`) USING BTREE,
  KEY `mobile` (`mobile`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='用户记录表';

DROP TABLE IF EXISTS `yoshop_user_address`;
CREATE TABLE `yoshop_user_address` (
  `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '收货人姓名',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `province_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '省份ID',
  `city_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '城市ID',
  `region_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '区/县ID',
  `detail` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`address_id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='用户收货地址表';

DROP TABLE IF EXISTS `yoshop_user_balance_log`;
CREATE TABLE `yoshop_user_balance_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `scene` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '余额变动场景(10用户充值 20用户消费 30管理员操作 40订单退款)',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变动金额',
  `describe` varchar(500) NOT NULL DEFAULT '' COMMENT '描述/说明',
  `remark` varchar(500) NOT NULL DEFAULT '' COMMENT '管理员备注',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='用户余额变动明细表';

DROP TABLE IF EXISTS `yoshop_user_coupon`;
CREATE TABLE `yoshop_user_coupon` (
  `user_coupon_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `coupon_id` int(11) unsigned NOT NULL COMMENT '优惠券ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `coupon_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '优惠券类型(10满减券 20折扣券)',
  `reduce_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '满减券-减免金额',
  `discount` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '折扣券-折扣率(0-100)',
  `min_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '最低消费金额',
  `expire_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '到期类型(10领取后生效 20固定时间)',
  `expire_day` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '领取后生效-有效天数',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '有效期开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '有效期结束时间',
  `apply_range` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '适用范围(10全部商品 20指定商品)',
  `apply_range_config` text COMMENT '适用范围配置(json格式)',
  `is_expire` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否过期(0未过期 1已过期)',
  `is_use` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否已使用(0未使用 1已使用)',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`user_coupon_id`),
  KEY `coupon_id` (`coupon_id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='用户优惠券记录表';

DROP TABLE IF EXISTS `yoshop_user_grade`;
CREATE TABLE `yoshop_user_grade` (
  `grade_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '等级ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '等级名称',
  `weight` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '等级权重(1-9999)',
  `upgrade` text NOT NULL COMMENT '升级条件',
  `equity` text NOT NULL COMMENT '等级权益(折扣率0-100)',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态(1启用 0禁用)',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`grade_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='用户会员等级表';

DROP TABLE IF EXISTS `yoshop_user_grade_log`;
CREATE TABLE `yoshop_user_grade_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `old_grade_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '变更前的等级ID',
  `new_grade_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '变更后的等级ID',
  `change_type` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '变更类型(10后台管理员设置 20自动升级)',
  `remark` varchar(500) DEFAULT '' COMMENT '管理员备注',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`log_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='用户会员等级变更记录表';

DROP TABLE IF EXISTS `yoshop_user_oauth`;
CREATE TABLE `yoshop_user_oauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `oauth_type` varchar(255) NOT NULL DEFAULT '' COMMENT '第三方登陆类型(MP-WEIXIN)',
  `oauth_id` varchar(100) NOT NULL DEFAULT '' COMMENT '第三方用户唯一标识 (uid openid)',
  `unionid` varchar(100) NOT NULL DEFAULT '' COMMENT '微信unionID',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `oauth_type` (`oauth_type`),
  KEY `store_id` (`store_id`),
  KEY `oauth_type_2` (`oauth_type`,`oauth_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10108 DEFAULT CHARSET=utf8 COMMENT='第三方用户信息表';

DROP TABLE IF EXISTS `yoshop_user_points_log`;
CREATE TABLE `yoshop_user_points_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `value` int(11) NOT NULL DEFAULT '0' COMMENT '变动数量',
  `describe` varchar(500) NOT NULL DEFAULT '' COMMENT '描述/说明',
  `remark` varchar(500) NOT NULL DEFAULT '' COMMENT '管理员备注',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '小程序商城ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`log_id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='用户积分变动明细表';

DROP TABLE IF EXISTS `yoshop_wxapp`;
CREATE TABLE `yoshop_wxapp` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '微信小程序ID',
  `app_id` varchar(50) NOT NULL DEFAULT '' COMMENT '小程序AppID',
  `app_secret` varchar(50) NOT NULL DEFAULT '' COMMENT '小程序AppSecret',
  `mchid` varchar(50) NOT NULL DEFAULT '' COMMENT '微信商户号ID',
  `apikey` varchar(255) NOT NULL DEFAULT '' COMMENT '微信支付密钥',
  `cert_pem` longtext COMMENT '证书文件cert',
  `key_pem` longtext COMMENT '证书文件key',
  `store_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商城ID',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `store_id` (`store_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10001 DEFAULT CHARSET=utf8 COMMENT='微信小程序记录表';
