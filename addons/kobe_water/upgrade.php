<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hao_water_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '会员id',
  `uniacid` int(10) NOT NULL COMMENT '公众号ID',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `address_detail` varchar(255) NOT NULL COMMENT '送货地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hao_water_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `shop_id` int(11) NOT NULL COMMENT '商品ID',
  `shop_count` int(11) NOT NULL COMMENT '商品数量',
  `time` varchar(255) NOT NULL COMMENT '加入时间',
  `uniacid` int(10) NOT NULL COMMENT '公众号ID',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hao_water_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL COMMENT '类型名',
  `type_sort` int(11) NOT NULL COMMENT '排序',
  `type_image` varchar(255) NOT NULL COMMENT '类型logo',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hao_water_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL COMMENT '分类id',
  `goods_title` varchar(255) NOT NULL COMMENT '商品名',
  `goods_price` varchar(255) DEFAULT NULL,
  `goods_image` varchar(255) NOT NULL COMMENT '商品图片',
  `goods_introduction` text NOT NULL COMMENT '商品简介',
  `goods_sort` int(11) NOT NULL COMMENT '商品排序',
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hao_water_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL COMMENT '公众号ID',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `member_nickname` varchar(255) NOT NULL COMMENT '姓名',
  `member_phone` varchar(255) NOT NULL COMMENT '会员手机号',
  `member_image` varchar(300) NOT NULL COMMENT '会员图像',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hao_water_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordersn` int(11) NOT NULL COMMENT '订单标识',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `goodsId` varchar(255) NOT NULL COMMENT '商品ID',
  `fee` varchar(255) DEFAULT NULL,
  `order_number` varchar(225) NOT NULL COMMENT '订单号',
  `order_count` varchar(255) NOT NULL COMMENT '购买该商品数量',
  `order_time` varchar(255) NOT NULL COMMENT '下单时间',
  `order_payment_status` int(11) NOT NULL,
  `delivery_time` varchar(255) NOT NULL COMMENT '配送时间',
  `uniacid` int(10) NOT NULL COMMENT '公众号ID',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `userphone` varchar(255) NOT NULL COMMENT '用户联系方式',
  `useraddress` varchar(255) NOT NULL COMMENT '用户地址',
  `staff_phone` varchar(255) NOT NULL COMMENT '配送员电话',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hao_water_order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `ordersn` int(11) NOT NULL,
  `order_deliver_status` int(11) NOT NULL,
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hao_water_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  `APPKEY` varchar(255) NOT NULL COMMENT '模板key',
  `TempID` int(11) NOT NULL COMMENT '模板ID',
  `ORDER_STATUS_TempID` varchar(255) NOT NULL COMMENT '订单状态更新',
  `W_TempID` varchar(255) NOT NULL COMMENT '模板通知ID',
  `OPENID` varchar(255) NOT NULL COMMENT '通知管理openid',
  `telphone` varchar(255) NOT NULL COMMENT '客服电话',
  `title` varchar(255) NOT NULL COMMENT '顶部标题',
  `button` varchar(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('hao_water_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_address')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hao_water_address',  'member_id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_address')." ADD `member_id` int(11) NOT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('hao_water_address',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_address')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('hao_water_address',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_address')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('hao_water_address',  'address_detail')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_address')." ADD `address_detail` varchar(255) NOT NULL COMMENT '送货地址';");
}
if(!pdo_fieldexists('hao_water_cart',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_cart')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hao_water_cart',  'member_id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_cart')." ADD `member_id` int(11) NOT NULL COMMENT '会员ID';");
}
if(!pdo_fieldexists('hao_water_cart',  'shop_id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_cart')." ADD `shop_id` int(11) NOT NULL COMMENT '商品ID';");
}
if(!pdo_fieldexists('hao_water_cart',  'shop_count')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_cart')." ADD `shop_count` int(11) NOT NULL COMMENT '商品数量';");
}
if(!pdo_fieldexists('hao_water_cart',  'time')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_cart')." ADD `time` varchar(255) NOT NULL COMMENT '加入时间';");
}
if(!pdo_fieldexists('hao_water_cart',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_cart')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('hao_water_cart',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_cart')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('hao_water_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_category')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hao_water_category',  'type_name')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_category')." ADD `type_name` varchar(255) NOT NULL COMMENT '类型名';");
}
if(!pdo_fieldexists('hao_water_category',  'type_sort')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_category')." ADD `type_sort` int(11) NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('hao_water_category',  'type_image')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_category')." ADD `type_image` varchar(255) NOT NULL COMMENT '类型logo';");
}
if(!pdo_fieldexists('hao_water_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_category')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('hao_water_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_goods')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hao_water_goods',  'type_id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_goods')." ADD `type_id` int(11) NOT NULL COMMENT '分类id';");
}
if(!pdo_fieldexists('hao_water_goods',  'goods_title')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_goods')." ADD `goods_title` varchar(255) NOT NULL COMMENT '商品名';");
}
if(!pdo_fieldexists('hao_water_goods',  'goods_price')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_goods')." ADD `goods_price` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hao_water_goods',  'goods_image')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_goods')." ADD `goods_image` varchar(255) NOT NULL COMMENT '商品图片';");
}
if(!pdo_fieldexists('hao_water_goods',  'goods_introduction')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_goods')." ADD `goods_introduction` text NOT NULL COMMENT '商品简介';");
}
if(!pdo_fieldexists('hao_water_goods',  'goods_sort')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_goods')." ADD `goods_sort` int(11) NOT NULL COMMENT '商品排序';");
}
if(!pdo_fieldexists('hao_water_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_goods')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('hao_water_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_member')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hao_water_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_member')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('hao_water_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_member')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('hao_water_member',  'member_nickname')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_member')." ADD `member_nickname` varchar(255) NOT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('hao_water_member',  'member_phone')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_member')." ADD `member_phone` varchar(255) NOT NULL COMMENT '会员手机号';");
}
if(!pdo_fieldexists('hao_water_member',  'member_image')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_member')." ADD `member_image` varchar(300) NOT NULL COMMENT '会员图像';");
}
if(!pdo_fieldexists('hao_water_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hao_water_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `ordersn` int(11) NOT NULL COMMENT '订单标识';");
}
if(!pdo_fieldexists('hao_water_order',  'member_id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `member_id` int(11) NOT NULL COMMENT '会员ID';");
}
if(!pdo_fieldexists('hao_water_order',  'goodsId')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `goodsId` varchar(255) NOT NULL COMMENT '商品ID';");
}
if(!pdo_fieldexists('hao_water_order',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `fee` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hao_water_order',  'order_number')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `order_number` varchar(225) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('hao_water_order',  'order_count')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `order_count` varchar(255) NOT NULL COMMENT '购买该商品数量';");
}
if(!pdo_fieldexists('hao_water_order',  'order_time')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `order_time` varchar(255) NOT NULL COMMENT '下单时间';");
}
if(!pdo_fieldexists('hao_water_order',  'order_payment_status')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `order_payment_status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hao_water_order',  'delivery_time')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `delivery_time` varchar(255) NOT NULL COMMENT '配送时间';");
}
if(!pdo_fieldexists('hao_water_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('hao_water_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('hao_water_order',  'username')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `username` varchar(255) NOT NULL COMMENT '用户名';");
}
if(!pdo_fieldexists('hao_water_order',  'userphone')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `userphone` varchar(255) NOT NULL COMMENT '用户联系方式';");
}
if(!pdo_fieldexists('hao_water_order',  'useraddress')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `useraddress` varchar(255) NOT NULL COMMENT '用户地址';");
}
if(!pdo_fieldexists('hao_water_order',  'staff_phone')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order')." ADD `staff_phone` varchar(255) NOT NULL COMMENT '配送员电话';");
}
if(!pdo_fieldexists('hao_water_order_status',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order_status')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hao_water_order_status',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order_status')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('hao_water_order_status',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order_status')." ADD `openid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hao_water_order_status',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order_status')." ADD `ordersn` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hao_water_order_status',  'order_deliver_status')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order_status')." ADD `order_deliver_status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hao_water_order_status',  'time')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_order_status')." ADD `time` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hao_water_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hao_water_setting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('hao_water_setting',  'APPKEY')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `APPKEY` varchar(255) NOT NULL COMMENT '模板key';");
}
if(!pdo_fieldexists('hao_water_setting',  'TempID')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `TempID` int(11) NOT NULL COMMENT '模板ID';");
}
if(!pdo_fieldexists('hao_water_setting',  'ORDER_STATUS_TempID')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `ORDER_STATUS_TempID` varchar(255) NOT NULL COMMENT '订单状态更新';");
}
if(!pdo_fieldexists('hao_water_setting',  'W_TempID')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `W_TempID` varchar(255) NOT NULL COMMENT '模板通知ID';");
}
if(!pdo_fieldexists('hao_water_setting',  'OPENID')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `OPENID` varchar(255) NOT NULL COMMENT '通知管理openid';");
}
if(!pdo_fieldexists('hao_water_setting',  'telphone')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `telphone` varchar(255) NOT NULL COMMENT '客服电话';");
}
if(!pdo_fieldexists('hao_water_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `title` varchar(255) NOT NULL COMMENT '顶部标题';");
}
if(!pdo_fieldexists('hao_water_setting',  'button')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `button` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hao_water_setting',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('hao_water_setting')." ADD `copyright` varchar(255) NOT NULL;");
}

?>