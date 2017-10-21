<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_shopping3_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `tel` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_adminset` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '设置能开多少个门店',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_biz_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `nickname` varchar(100) NOT NULL,
  `sms_type` tinyint(2) NOT NULL,
  `store_tpl` varchar(120) DEFAULT '' COMMENT '商家提醒模板id',
  `openid` varchar(100) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店',
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `price` varchar(10) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `express_name` varchar(50) DEFAULT NULL,
  `displayorder` int(11) NOT NULL,
  `express_price` varchar(10) DEFAULT NULL,
  `express_area` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `sex` varchar(2) NOT NULL,
  `login_nums` int(11) DEFAULT NULL,
  `login_time` int(10) DEFAULT NULL,
  `crteate_time` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0,拉黑1正常',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_fans_like` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `fansid` int(11) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `goodsid` int(10) unsigned NOT NULL DEFAULT '0',
  `checked` tinyint(1) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_genius` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店',
  `rens` tinyint(4) NOT NULL,
  `displayorder` tinyint(4) NOT NULL,
  `sort` tinyint(4) DEFAULT NULL,
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `nums` tinyint(4) NOT NULL,
  `dishes` text NOT NULL COMMENT '菜品的ID，以逗号隔开',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isindex` tinyint(1) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `goodssn` varchar(50) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` varchar(10) NOT NULL DEFAULT '',
  `productprice` varchar(10) NOT NULL DEFAULT '',
  `total` int(10) NOT NULL DEFAULT '0',
  `sellnums` int(10) NOT NULL DEFAULT '0',
  `thumb_url` varchar(1000) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `hits` int(10) DEFAULT NULL,
  `label` varchar(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(30) NOT NULL,
  `transid` varchar(50) NOT NULL,
  `expressprice` varchar(5) NOT NULL COMMENT '快递费',
  `totalnum` tinyint(4) NOT NULL,
  `totalprice` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `sendstatus` tinyint(1) NOT NULL COMMENT '配送状态',
  `order_type` tinyint(2) NOT NULL,
  `ispay` tinyint(1) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `seat_type` tinyint(2) NOT NULL,
  `guest_name` varchar(30) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `sex` tinyint(2) NOT NULL,
  `guest_address` varchar(200) NOT NULL,
  `time_day` varchar(12) NOT NULL,
  `time_hour` varchar(4) NOT NULL,
  `time_second` varchar(4) NOT NULL,
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `secretid` varchar(4) NOT NULL,
  `print_sta` tinyint(2) NOT NULL,
  `sms_sta` varchar(3) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `desk` varchar(10) NOT NULL,
  `nums` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `description` varchar(30) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `wtype` tinyint(4) NOT NULL COMMENT '0是查单 1是统计',
  `sex` tinyint(4) NOT NULL COMMENT '0是先生 1是女士',
  `openidstr` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `notice_acid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id',
  `paytype1` tinyint(1) NOT NULL,
  `paytype2` tinyint(1) NOT NULL,
  `paytype3` tinyint(1) NOT NULL,
  `mail_status` tinyint(1) NOT NULL,
  `mail_smtp` varchar(50) NOT NULL,
  `mail_user` varchar(50) NOT NULL,
  `mail_psw` varchar(50) NOT NULL,
  `mail_to` varchar(50) NOT NULL,
  `print_status` tinyint(1) NOT NULL,
  `print_type` tinyint(2) NOT NULL,
  `print_usr` varchar(50) NOT NULL,
  `print_nums` tinyint(3) NOT NULL,
  `print_top` varchar(1000) NOT NULL,
  `print_bottom` varchar(1000) NOT NULL,
  `sms_status` tinyint(1) NOT NULL,
  `sms_type` tinyint(2) NOT NULL COMMENT '0商家，1客户，2both',
  `sms_phone` varchar(20) NOT NULL,
  `sms_from` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1是打印机自己发，2是短信平台',
  `sms_secret` varchar(80) NOT NULL,
  `sms_text` varchar(200) NOT NULL,
  `sms_resgister` tinyint(1) NOT NULL DEFAULT '1',
  `order_limit` tinyint(4) NOT NULL,
  `sms_user` varchar(50) NOT NULL DEFAULT '',
  `sending_price` varchar(10) NOT NULL DEFAULT '' COMMENT '起送价格',
  `address_list` varchar(500) NOT NULL DEFAULT '',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  `store_tpl` varchar(120) DEFAULT '' COMMENT '商家提醒模板id',
  `store_msg_tpl` varchar(120) DEFAULT '' COMMENT '商家短信模板',
  `member_tpl` varchar(120) DEFAULT '' COMMENT '客户下单成功提醒模板id',
  `ordretype1` tinyint(1) NOT NULL DEFAULT '1',
  `ordretype2` tinyint(1) NOT NULL DEFAULT '1',
  `ordretype3` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping3_shop` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '商家logo',
  `content` text NOT NULL COMMENT '简介',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `desk_list` varchar(1000) NOT NULL DEFAULT '',
  `room_list` varchar(1000) NOT NULL DEFAULT '',
  `yy_start_time` varchar(5) NOT NULL DEFAULT '02:00',
  `yy_end_time` varchar(5) NOT NULL DEFAULT '23:59',
  `thumbs` varchar(1000) DEFAULT NULL,
  `is_notice` varchar(1) DEFAULT '0' COMMENT '是否关闭通知',
  `shop_notice` varchar(100) DEFAULT NULL,
  `address_list` varchar(100) DEFAULT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  `enabled` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_feedbackid` (`feedbackid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `goodssn` varchar(50) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `spec` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `maxbuy` int(11) DEFAULT '0',
  `usermaxbuy` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最多购买数量',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` int(11) DEFAULT '0',
  `ishot` int(11) DEFAULT '0',
  `isdiscount` int(11) DEFAULT '0',
  `isrecommand` int(11) DEFAULT '0',
  `istime` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `address` varchar(1024) NOT NULL DEFAULT '' COMMENT '收货地址信息',
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `paydetail` varchar(255) NOT NULL COMMENT '支付详情',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shopping_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('shopping3_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_address')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_address',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_address')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_address',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_address')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_address',  'username')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_address')." ADD `username` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_address',  'address')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_address')." ADD `address` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_address',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_address')." ADD `tel` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_adminset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_adminset')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_adminset',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_adminset')." ADD `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('shopping3_adminset',  'num')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_adminset')." ADD `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '设置能开多少个门店';");
}
if(!pdo_fieldexists('shopping3_adminset',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_adminset')." ADD `createtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping3_biz_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_biz_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_biz_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_biz_set')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_biz_set',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_biz_set')." ADD `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id';");
}
if(!pdo_fieldexists('shopping3_biz_set',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_biz_set')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_biz_set',  'sms_type')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_biz_set')." ADD `sms_type` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_biz_set',  'store_tpl')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_biz_set')." ADD `store_tpl` varchar(120) DEFAULT '' COMMENT '商家提醒模板id';");
}
if(!pdo_fieldexists('shopping3_biz_set',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_biz_set')." ADD `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_biz_set',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_biz_set')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('shopping3_biz_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_biz_set')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('shopping3_cart',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_cart')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_cart',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_cart')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_cart',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_cart')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_cart',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_cart')." ADD `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店';");
}
if(!pdo_fieldexists('shopping3_cart',  'goodstype')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_cart')." ADD `goodstype` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping3_cart',  'price')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_cart')." ADD `price` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_cart',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_cart')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_cart',  'total')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_cart')." ADD `total` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_cart',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_cart')." ADD `create_time` int(10) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_category')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('shopping3_category',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_category')." ADD `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店';");
}
if(!pdo_fieldexists('shopping3_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('shopping3_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('shopping3_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('shopping3_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_category')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('shopping3_express',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_express')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_express',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_express')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_express',  'express_name')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_express')." ADD `express_name` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_express',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_express')." ADD `displayorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_express',  'express_price')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_express')." ADD `express_price` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_express',  'express_area')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_express')." ADD `express_area` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_fans',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `phone` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'password')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `password` varchar(40) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'username')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `username` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `sex` varchar(2) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'login_nums')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `login_nums` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'login_time')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `login_time` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'crteate_time')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `crteate_time` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0,拉黑1正常';");
}
if(!pdo_fieldexists('shopping3_fans_like',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans_like')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_fans_like',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans_like')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans_like',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans_like')." ADD `fansid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans_like',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans_like')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans_like',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans_like')." ADD `goodsid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping3_fans_like',  'checked')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans_like')." ADD `checked` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_fans_like',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_fans_like')." ADD `create_time` int(10) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_genius',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_genius',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_genius',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `shopid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店';");
}
if(!pdo_fieldexists('shopping3_genius',  'rens')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `rens` tinyint(4) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_genius',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `displayorder` tinyint(4) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_genius',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `sort` tinyint(4) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_genius',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `thumb` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_genius',  'nums')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `nums` tinyint(4) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_genius',  'dishes')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `dishes` text NOT NULL COMMENT '菜品的ID，以逗号隔开';");
}
if(!pdo_fieldexists('shopping3_genius',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_genius')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_goods',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id';");
}
if(!pdo_fieldexists('shopping3_goods',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping3_goods',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping3_goods',  'type')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟';");
}
if(!pdo_fieldexists('shopping3_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping3_goods',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping3_goods',  'isindex')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `isindex` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `thumb` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_goods',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `unit` varchar(5) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_goods',  'description')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `description` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_goods',  'content')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('shopping3_goods',  'goodssn')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `goodssn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_goods',  'productsn')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `productsn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_goods',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `marketprice` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_goods',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `productprice` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `total` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping3_goods',  'sellnums')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `sellnums` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping3_goods',  'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `thumb_url` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_goods',  'hits')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `hits` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_goods',  'label')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_goods')." ADD `label` varchar(2) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id';");
}
if(!pdo_fieldexists('shopping3_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `ordersn` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `transid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'expressprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `expressprice` varchar(5) NOT NULL COMMENT '快递费';");
}
if(!pdo_fieldexists('shopping3_order',  'totalnum')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `totalnum` tinyint(4) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'totalprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `totalprice` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为成功';");
}
if(!pdo_fieldexists('shopping3_order',  'sendtype')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提';");
}
if(!pdo_fieldexists('shopping3_order',  'sendstatus')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `sendstatus` tinyint(1) NOT NULL COMMENT '配送状态';");
}
if(!pdo_fieldexists('shopping3_order',  'order_type')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `order_type` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'ispay')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `ispay` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付';");
}
if(!pdo_fieldexists('shopping3_order',  'seat_type')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `seat_type` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'guest_name')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `guest_name` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `tel` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `sex` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'guest_address')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `guest_address` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'time_day')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `time_day` varchar(12) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'time_hour')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `time_hour` varchar(4) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'time_second')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `time_second` varchar(4) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `remark` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_order',  'secretid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `secretid` varchar(4) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'print_sta')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `print_sta` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'sms_sta')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `sms_sta` varchar(3) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'desk')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `desk` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order',  'nums')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order')." ADD `nums` tinyint(4) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_order_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order_goods',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order_goods')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order_goods',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order_goods')." ADD `goodsid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order_goods')." ADD `total` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping3_order_goods',  'description')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order_goods')." ADD `description` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_order_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_order_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_reply')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping3_reply',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_reply')." ADD `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id';");
}
if(!pdo_fieldexists('shopping3_reply',  'wtype')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_reply')." ADD `wtype` tinyint(4) NOT NULL COMMENT '0是查单 1是统计';");
}
if(!pdo_fieldexists('shopping3_reply',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_reply')." ADD `sex` tinyint(4) NOT NULL COMMENT '0是先生 1是女士';");
}
if(!pdo_fieldexists('shopping3_reply',  'openidstr')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_reply')." ADD `openidstr` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `shopid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id';");
}
if(!pdo_fieldexists('shopping3_set',  'notice_acid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `notice_acid` int(10) NOT NULL DEFAULT '0' COMMENT '店铺id';");
}
if(!pdo_fieldexists('shopping3_set',  'paytype1')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `paytype1` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'paytype2')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `paytype2` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'paytype3')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `paytype3` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'mail_status')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `mail_status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'mail_smtp')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `mail_smtp` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'mail_user')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `mail_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'mail_psw')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `mail_psw` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'mail_to')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `mail_to` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'print_status')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `print_status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'print_type')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `print_type` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'print_usr')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `print_usr` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'print_nums')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `print_nums` tinyint(3) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'print_top')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `print_top` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'print_bottom')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `print_bottom` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'sms_status')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `sms_status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'sms_type')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `sms_type` tinyint(2) NOT NULL COMMENT '0商家，1客户，2both';");
}
if(!pdo_fieldexists('shopping3_set',  'sms_phone')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `sms_phone` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'sms_from')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `sms_from` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1是打印机自己发，2是短信平台';");
}
if(!pdo_fieldexists('shopping3_set',  'sms_secret')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `sms_secret` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'sms_text')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `sms_text` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'sms_resgister')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `sms_resgister` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping3_set',  'order_limit')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `order_limit` tinyint(4) NOT NULL;");
}
if(!pdo_fieldexists('shopping3_set',  'sms_user')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `sms_user` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_set',  'sending_price')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `sending_price` varchar(10) NOT NULL DEFAULT '' COMMENT '起送价格';");
}
if(!pdo_fieldexists('shopping3_set',  'address_list')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `address_list` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_set',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省';");
}
if(!pdo_fieldexists('shopping3_set',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市';");
}
if(!pdo_fieldexists('shopping3_set',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区';");
}
if(!pdo_fieldexists('shopping3_set',  'store_tpl')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `store_tpl` varchar(120) DEFAULT '' COMMENT '商家提醒模板id';");
}
if(!pdo_fieldexists('shopping3_set',  'store_msg_tpl')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `store_msg_tpl` varchar(120) DEFAULT '' COMMENT '商家短信模板';");
}
if(!pdo_fieldexists('shopping3_set',  'member_tpl')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `member_tpl` varchar(120) DEFAULT '' COMMENT '客户下单成功提醒模板id';");
}
if(!pdo_fieldexists('shopping3_set',  'ordretype1')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `ordretype1` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping3_set',  'ordretype2')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `ordretype2` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping3_set',  'ordretype3')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_set')." ADD `ordretype3` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping3_shop',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping3_shop',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('shopping3_shop',  'title')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists('shopping3_shop',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '商家logo';");
}
if(!pdo_fieldexists('shopping3_shop',  'content')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `content` text NOT NULL COMMENT '简介';");
}
if(!pdo_fieldexists('shopping3_shop',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('shopping3_shop',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省';");
}
if(!pdo_fieldexists('shopping3_shop',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市';");
}
if(!pdo_fieldexists('shopping3_shop',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区';");
}
if(!pdo_fieldexists('shopping3_shop',  'address')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `address` varchar(200) NOT NULL COMMENT '地址';");
}
if(!pdo_fieldexists('shopping3_shop',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('shopping3_shop',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('shopping3_shop',  'desk_list')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `desk_list` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_shop',  'room_list')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `room_list` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping3_shop',  'yy_start_time')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `yy_start_time` varchar(5) NOT NULL DEFAULT '02:00';");
}
if(!pdo_fieldexists('shopping3_shop',  'yy_end_time')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `yy_end_time` varchar(5) NOT NULL DEFAULT '23:59';");
}
if(!pdo_fieldexists('shopping3_shop',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `thumbs` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_shop',  'is_notice')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `is_notice` varchar(1) DEFAULT '0' COMMENT '是否关闭通知';");
}
if(!pdo_fieldexists('shopping3_shop',  'shop_notice')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `shop_notice` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_shop',  'address_list')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `address_list` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('shopping3_shop',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping3_shop')." ADD `createtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_address',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_address',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping_address',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `realname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('shopping_address',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('shopping_address',  'province')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `province` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('shopping_address',  'city')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `city` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('shopping_address',  'area')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `area` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('shopping_address',  'address')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `address` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('shopping_address',  'isdefault')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_address',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('shopping_address')." ADD `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_adv',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_adv',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_adv',  'link')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD `link` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_adv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_adv',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('shopping_adv',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('shopping_adv',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('shopping_adv',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_adv')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('shopping_cart',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_cart')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_cart',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_cart')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_cart',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_cart')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('shopping_cart',  'goodstype')) {
	pdo_query("ALTER TABLE ".tablename('shopping_cart')." ADD `goodstype` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping_cart',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('shopping_cart')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping_cart',  'total')) {
	pdo_query("ALTER TABLE ".tablename('shopping_cart')." ADD `total` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_cart',  'optionid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_cart')." ADD `optionid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_cart',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_cart')." ADD `marketprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_indexexists('shopping_cart',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_cart')." ADD KEY `idx_openid` (`from_user`);");
}
if(!pdo_fieldexists('shopping_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_category')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('shopping_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('shopping_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('shopping_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('shopping_category')." ADD `thumb` varchar(255) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists('shopping_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('shopping_category',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('shopping_category')." ADD `isrecommand` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('shopping_category')." ADD `description` varchar(500) NOT NULL COMMENT '分类介绍';");
}
if(!pdo_fieldexists('shopping_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('shopping_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('shopping_category')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('shopping_dispatch',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_dispatch',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_dispatch',  'dispatchname')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `dispatchname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_dispatch',  'dispatchtype')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `dispatchtype` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_dispatch',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_dispatch',  'firstprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `firstprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_dispatch',  'secondprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `secondprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_dispatch',  'firstweight')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `firstweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_dispatch',  'secondweight')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `secondweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_dispatch',  'express')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `express` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_dispatch',  'description')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `description` text;");
}
if(!pdo_fieldexists('shopping_dispatch',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD `enabled` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('shopping_dispatch',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('shopping_dispatch',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_dispatch')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('shopping_express',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_express')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_express',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_express')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_express',  'express_name')) {
	pdo_query("ALTER TABLE ".tablename('shopping_express')." ADD `express_name` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_express',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_express')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_express',  'express_price')) {
	pdo_query("ALTER TABLE ".tablename('shopping_express')." ADD `express_price` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_express',  'express_area')) {
	pdo_query("ALTER TABLE ".tablename('shopping_express')." ADD `express_area` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_express',  'express_url')) {
	pdo_query("ALTER TABLE ".tablename('shopping_express')." ADD `express_url` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('shopping_express',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_express')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('shopping_express',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_express')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('shopping_feedback',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_feedback',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_feedback',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping_feedback',  'type')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎';");
}
if(!pdo_fieldexists('shopping_feedback',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝';");
}
if(!pdo_fieldexists('shopping_feedback',  'feedbackid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号';");
}
if(!pdo_fieldexists('shopping_feedback',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `transid` varchar(30) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('shopping_feedback',  'reason')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `reason` varchar(1000) NOT NULL COMMENT '理由';");
}
if(!pdo_fieldexists('shopping_feedback',  'solution')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `solution` varchar(1000) NOT NULL COMMENT '期待解决方案';");
}
if(!pdo_fieldexists('shopping_feedback',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `remark` varchar(1000) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('shopping_feedback',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('shopping_feedback',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('shopping_feedback',  'idx_feedbackid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD KEY `idx_feedbackid` (`feedbackid`);");
}
if(!pdo_indexexists('shopping_feedback',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_indexexists('shopping_feedback',  'idx_transid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_feedback')." ADD KEY `idx_transid` (`transid`);");
}
if(!pdo_fieldexists('shopping_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_goods',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'type')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟';");
}
if(!pdo_fieldexists('shopping_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping_goods',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_goods',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `unit` varchar(5) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_goods',  'description')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `description` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_goods',  'content')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('shopping_goods',  'goodssn')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `goodssn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_goods',  'productsn')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `productsn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_goods',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_goods',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `productprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_goods',  'costprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `costprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_goods',  'originalprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价';");
}
if(!pdo_fieldexists('shopping_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `total` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'totalcnf')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减';");
}
if(!pdo_fieldexists('shopping_goods',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `sales` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'spec')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `spec` varchar(5000) NOT NULL;");
}
if(!pdo_fieldexists('shopping_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_goods',  'weight')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `weight` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_goods',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `credit` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_goods',  'maxbuy')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `maxbuy` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'usermaxbuy')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `usermaxbuy` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最多购买数量';");
}
if(!pdo_fieldexists('shopping_goods',  'hasoption')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `hasoption` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `dispatch` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `thumb_url` text;");
}
if(!pdo_fieldexists('shopping_goods',  'isnew')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `isnew` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'ishot')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `ishot` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'isdiscount')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `isdiscount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `isrecommand` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'istime')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `istime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'timestart')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `timestart` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'timeend')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `timeend` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'viewcount')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `viewcount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods')." ADD `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods_option',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_goods_option',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `goodsid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods_option',  'title')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_goods_option',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `thumb` varchar(60) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_goods_option',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `productprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_goods_option',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `marketprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_goods_option',  'costprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `costprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_goods_option',  'stock')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `stock` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods_option',  'weight')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `weight` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_goods_option',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods_option',  'specs')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD `specs` text;");
}
if(!pdo_indexexists('shopping_goods_option',  'indx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD KEY `indx_goodsid` (`goodsid`);");
}
if(!pdo_indexexists('shopping_goods_option',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_option')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('shopping_goods_param',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_param')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_goods_param',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_param')." ADD `goodsid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_goods_param',  'title')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_param')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_goods_param',  'value')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_param')." ADD `value` text;");
}
if(!pdo_fieldexists('shopping_goods_param',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_param')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('shopping_goods_param',  'indx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_param')." ADD KEY `indx_goodsid` (`goodsid`);");
}
if(!pdo_indexexists('shopping_goods_param',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_goods_param')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('shopping_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `ordersn` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('shopping_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `price` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('shopping_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功';");
}
if(!pdo_fieldexists('shopping_order',  'sendtype')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提';");
}
if(!pdo_fieldexists('shopping_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付';");
}
if(!pdo_fieldexists('shopping_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
if(!pdo_fieldexists('shopping_order',  'goodstype')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `remark` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_order',  'addressid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `addressid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `address` varchar(1024) NOT NULL DEFAULT '' COMMENT '收货地址信息';");
}
if(!pdo_fieldexists('shopping_order',  'expresscom')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `expresscom` varchar(30) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_order',  'expresssn')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `expresssn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_order',  'express')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `express` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shopping_order',  'goodsprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `goodsprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_order',  'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `dispatchprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_order',  'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `dispatch` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_order',  'paydetail')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `paydetail` varchar(255) NOT NULL COMMENT '支付详情';");
}
if(!pdo_fieldexists('shopping_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_order_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_order_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_order_goods',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order_goods')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_order_goods',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order_goods')." ADD `goodsid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_order_goods',  'price')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order_goods')." ADD `price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('shopping_order_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order_goods')." ADD `total` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping_order_goods',  'optionid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order_goods')." ADD `optionid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_order_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_order_goods',  'optionname')) {
	pdo_query("ALTER TABLE ".tablename('shopping_order_goods')." ADD `optionname` text;");
}
if(!pdo_fieldexists('shopping_product',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_product',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('shopping_product',  'productsn')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD `productsn` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping_product',  'title')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD `title` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('shopping_product',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD `marketprice` decimal(10,0) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_product',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD `productprice` decimal(10,0) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_product',  'total')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD `total` int(11) NOT NULL;");
}
if(!pdo_fieldexists('shopping_product',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shopping_product',  'spec')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD `spec` varchar(5000) NOT NULL;");
}
if(!pdo_indexexists('shopping_product',  'idx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_product')." ADD KEY `idx_goodsid` (`goodsid`);");
}
if(!pdo_fieldexists('shopping_spec',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_spec',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_spec',  'title')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shopping_spec',  'description')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('shopping_spec',  'displaytype')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec')." ADD `displaytype` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shopping_spec',  'content')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('shopping_spec',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec')." ADD `goodsid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_spec',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_spec_item',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shopping_spec_item',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_spec_item',  'specid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD `specid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_spec_item',  'title')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_spec_item',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('shopping_spec_item',  'show')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD `show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('shopping_spec_item',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('shopping_spec_item',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('shopping_spec_item',  'indx_specid')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD KEY `indx_specid` (`specid`);");
}
if(!pdo_indexexists('shopping_spec_item',  'indx_show')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD KEY `indx_show` (`show`);");
}
if(!pdo_indexexists('shopping_spec_item',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('shopping_spec_item')." ADD KEY `indx_displayorder` (`displayorder`);");
}

?>