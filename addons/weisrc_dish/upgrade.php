<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `pwd` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `storeid` varchar(1000) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `pay_account` varchar(200) NOT NULL,
  `accountname` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `salt` varchar(10) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL,
  `role` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1:店长,2:店员',
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(15) NOT NULL,
  `areaid` int(10) NOT NULL DEFAULT '0' COMMENT '区域id',
  `is_admin_order` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_notice_order` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_notice_queue` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_notice_service` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_notice_boss` tinyint(1) NOT NULL DEFAULT '0',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `url` varchar(200) NOT NULL,
  `thumb` varchar(500) NOT NULL,
  `position` tinyint(1) NOT NULL,
  `starttime` int(10) NOT NULL,
  `endtime` int(10) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` varchar(300) NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `parentid` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_blacklist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_businesslog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `price` varchar(20) DEFAULT NULL,
  `haveprice` varchar(20) DEFAULT NULL,
  `totalprice` varchar(20) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `handletime` int(10) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `dining_mode` tinyint(1) unsigned NOT NULL,
  `charges` varchar(20) DEFAULT NULL,
  `successprice` varchar(20) DEFAULT NULL,
  `business_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `trade_no` varchar(200) DEFAULT '0',
  `payment_no` varchar(200) DEFAULT '0',
  `result` varchar(1000) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL,
  `price` varchar(10) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `packvalue` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_cashlog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(200) DEFAULT '',
  `logtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '日志类型1佣金2配送佣金',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型1微信2余额',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '申请金额',
  `charges` decimal(10,2) DEFAULT '0.00' COMMENT '手续费',
  `successprice` decimal(10,2) DEFAULT '0.00' COMMENT '到帐金额',
  `totalprice` decimal(10,2) DEFAULT '0.00' COMMENT '余额',
  `trade_no` varchar(200) DEFAULT '0',
  `payment_no` varchar(200) DEFAULT '0',
  `remark` varchar(1000) DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `handletime` int(10) DEFAULT '0' COMMENT '处理时间',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `parentid` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  `is_meal` tinyint(1) NOT NULL,
  `is_delivery` tinyint(1) NOT NULL,
  `is_snack` tinyint(1) NOT NULL,
  `is_reservation` tinyint(1) NOT NULL,
  `rebate` decimal(5,2) NOT NULL DEFAULT '10.00' COMMENT '打折费率',
  `is_discount` int(2) NOT NULL DEFAULT '0' COMMENT '是否开启打折',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_collection` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_commission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `agentid` int(10) unsigned NOT NULL,
  `ordersn` varchar(100) DEFAULT NULL,
  `from_user` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `dateline` int(10) DEFAULT NULL,
  `level` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_coupon` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `storeid` int(10) NOT NULL,
  `levelid` tinyint(1) NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(500) NOT NULL,
  `attr_type` tinyint(1) NOT NULL,
  `ruletype` tinyint(1) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `gmoney` decimal(18,2) NOT NULL,
  `dmoney` decimal(18,2) NOT NULL,
  `content` text NOT NULL,
  `totalcount` int(10) NOT NULL,
  `usercount` int(10) NOT NULL,
  `ticket_ty` tinyint(1) NOT NULL,
  `ticket_id` int(10) NOT NULL,
  `needscore` int(10) NOT NULL,
  `starttime` int(10) NOT NULL,
  `endtime` int(10) NOT NULL,
  `displayorder` tinyint(4) NOT NULL,
  `dateline` int(10) NOT NULL,
  `is_meal` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否店内点餐',
  `is_delivery` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否外卖订餐',
  `is_snack` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支持快餐',
  `is_reservation` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支持预定',
  `is_shouyin` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支持收银',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_credits_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '',
  `credittype` varchar(100) DEFAULT '' COMMENT '积分类型',
  `num` decimal(10,2) DEFAULT '0.00',
  `operator` varchar(100) DEFAULT '',
  `remark` varchar(1000) DEFAULT NULL COMMENT '内容',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_delivery_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `storeid` int(10) NOT NULL DEFAULT '0' COMMENT '门店id',
  `orderid` int(10) NOT NULL DEFAULT '0' COMMENT '订单id',
  `delivery_id` int(10) NOT NULL DEFAULT '0' COMMENT '配送员id',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_deliveryarea` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店',
  `title` varchar(50) NOT NULL COMMENT '区域名称',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_dispatcharea` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店',
  `title` varchar(50) NOT NULL COMMENT '区域名称',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_distance` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `begindistance` int(10) unsigned NOT NULL,
  `enddistance` int(10) unsigned NOT NULL,
  `sendingprice` decimal(10,2) DEFAULT '0.00' COMMENT '起送价格',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `freeprice` decimal(10,2) DEFAULT '0.00',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_email_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `email_enable` tinyint(1) unsigned NOT NULL,
  `email_host` varchar(50) DEFAULT NULL,
  `email_send` varchar(100) DEFAULT NULL,
  `email_pwd` varchar(20) DEFAULT NULL,
  `email_user` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `email_business_tpl` varchar(200) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `headimgurl` varchar(500) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `sex` tinyint(1) unsigned NOT NULL,
  `lat` decimal(18,10) NOT NULL,
  `lng` decimal(18,10) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `storeid` int(11) DEFAULT NULL,
  `totalcount` int(10) DEFAULT NULL,
  `totalprice` decimal(18,2) NOT NULL,
  `avgprice` decimal(18,2) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `paytime` int(10) DEFAULT NULL,
  `lasttime` int(10) DEFAULT NULL,
  `agentid2` int(11) DEFAULT NULL,
  `agentid3` int(11) DEFAULT NULL,
  `noticetime` int(10) DEFAULT NULL,
  `agentid` int(11) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `is_commission` tinyint(1) DEFAULT '1',
  `commission_price` decimal(18,2) NOT NULL DEFAULT '0.00',
  `delivery_price` decimal(18,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT NULL,
  `star` tinyint(1) NOT NULL,
  `content` varchar(500) DEFAULT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_fengniao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `orderid` int(11) DEFAULT '0',
  `open_order_code` varchar(200) DEFAULT '' COMMENT '蜂鸟配送开放平台返回的订单号',
  `partner_order_code` varchar(200) DEFAULT '' COMMENT '商户自己的订单号',
  `order_status` int(11) DEFAULT '0' COMMENT '状态码',
  `push_time` int(11) DEFAULT '0' COMMENT '状态推送时间(毫秒)',
  `carrier_driver_name` varchar(200) DEFAULT '' COMMENT '蜂鸟配送员姓名',
  `carrier_driver_phone` varchar(200) DEFAULT '' COMMENT '蜂鸟配送员电话',
  `description` varchar(200) DEFAULT '' COMMENT '描述信息',
  `address` varchar(200) DEFAULT '' COMMENT '定点次日达服务独有的字段: 微仓地址',
  `latitude` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '定点次日达服务独有的字段: 微仓纬度',
  `longitude` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '定点次日达服务独有的字段: 微仓经度',
  `cancel_reason` int(11) DEFAULT '0' COMMENT '订单取消原因. 1:用户取消, 2:商家取消',
  `error_code` varchar(200) DEFAULT '' COMMENT '错误编码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL,
  `ccate` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `recommend` tinyint(1) unsigned NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `thumb` varchar(500) NOT NULL,
  `unitname` varchar(5) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `taste` varchar(1000) NOT NULL,
  `isspecial` tinyint(1) unsigned NOT NULL,
  `marketprice` varchar(10) NOT NULL,
  `productprice` varchar(10) NOT NULL,
  `credit` int(10) NOT NULL,
  `subcount` int(10) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `lasttime` int(10) NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL,
  `counts` int(10) NOT NULL,
  `sales` int(10) unsigned NOT NULL,
  `today_counts` int(10) NOT NULL,
  `labelid` int(10) unsigned NOT NULL,
  `packvalue` varchar(10) NOT NULL,
  `content` text,
  `week` varchar(100) NOT NULL DEFAULT '1,2,3,4,5,6,0' COMMENT '星期',
  `memberprice` varchar(10) NOT NULL DEFAULT '' COMMENT '会员价',
  `delivery_commission_money` decimal(10,2) DEFAULT '0.00',
  `commission_money1` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级佣金',
  `commission_money2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级佣金',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `price` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_intelligent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `name` int(10) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_mealtime` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `begintime` varchar(20) DEFAULT NULL,
  `endtime` varchar(20) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_nave` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `type` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `link` varchar(200) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(30) NOT NULL,
  `totalnum` tinyint(4) DEFAULT NULL,
  `totalprice` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `reply` varchar(1000) NOT NULL,
  `meal_time` varchar(50) NOT NULL,
  `counts` tinyint(4) DEFAULT NULL,
  `seat_type` tinyint(1) DEFAULT NULL,
  `carports` tinyint(3) DEFAULT NULL,
  `dining_mode` tinyint(1) unsigned NOT NULL,
  `remark` varchar(1000) NOT NULL,
  `tables` varchar(10) NOT NULL,
  `print_sta` tinyint(1) DEFAULT NULL,
  `sign` tinyint(1) NOT NULL,
  `isfinish` tinyint(1) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `transid` varchar(30) NOT NULL,
  `goodsprice` decimal(10,2) DEFAULT NULL,
  `dispatchprice` decimal(10,2) DEFAULT NULL,
  `isemail` tinyint(1) NOT NULL,
  `issms` tinyint(1) NOT NULL,
  `istpl` tinyint(1) NOT NULL,
  `credit` varchar(10) NOT NULL,
  `paydetail` varchar(1000) NOT NULL,
  `ispay` tinyint(1) NOT NULL,
  `tablezonesid` varchar(10) NOT NULL,
  `isfeedback` tinyint(1) NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `packvalue` varchar(10) NOT NULL,
  `quyu` varchar(100) NOT NULL,
  `rechargeid` int(10) NOT NULL DEFAULT '0',
  `quicknum` varchar(30) NOT NULL DEFAULT '0001',
  `tea_money` decimal(10,2) DEFAULT '0.00',
  `service_money` decimal(10,2) DEFAULT '0.00',
  `discount_money` decimal(10,2) DEFAULT '0.00',
  `ismerge` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否合并的单子',
  `delivery_id` int(10) NOT NULL DEFAULT '0' COMMENT '配送员id',
  `delivery_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deliveryareaid` int(10) NOT NULL DEFAULT '0' COMMENT '配送点id',
  `delivery_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `is_append` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否加单',
  `append_dish` tinyint(2) NOT NULL DEFAULT '0' COMMENT '加菜',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `delivery_notice` tinyint(1) NOT NULL DEFAULT '0',
  `isvip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员',
  `finishtime` int(10) DEFAULT '0' COMMENT '完成时间',
  `confirmtime` int(10) DEFAULT '0' COMMENT '确认时间',
  `paytime` int(10) DEFAULT '0' COMMENT '付款时间',
  `delivery_finish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已配送时间',
  `newlimitprice` varchar(500) NOT NULL DEFAULT '',
  `oldlimitprice` varchar(500) NOT NULL DEFAULT '',
  `newlimitpricevalue` varchar(10) NOT NULL DEFAULT '',
  `oldlimitpricevalue` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` varchar(10) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_order_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店编号',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单编号',
  `content` varchar(1000) DEFAULT NULL COMMENT '内容',
  `fromtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '来源',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_print_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `storeid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_print_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `print_usr` varchar(50) DEFAULT NULL,
  `print_status` tinyint(1) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_print_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `print_status` tinyint(1) NOT NULL,
  `print_type` tinyint(1) NOT NULL,
  `print_usr` varchar(50) DEFAULT NULL,
  `print_nums` tinyint(3) DEFAULT NULL,
  `print_top` varchar(40) DEFAULT NULL,
  `print_bottom` varchar(40) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `qrcode_status` tinyint(1) NOT NULL,
  `qrcode_url` varchar(200) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `member_code` varchar(100) DEFAULT NULL,
  `feyin_key` varchar(100) DEFAULT NULL,
  `is_print_all` tinyint(1) NOT NULL,
  `print_goodstype` varchar(500) DEFAULT NULL,
  `printid` int(10) NOT NULL,
  `yilian_type` tinyint(1) DEFAULT NULL,
  `api_key` varchar(100) DEFAULT NULL,
  `print_label` varchar(500) DEFAULT NULL,
  `is_meal` tinyint(1) NOT NULL,
  `is_delivery` tinyint(1) NOT NULL,
  `is_snack` tinyint(1) NOT NULL,
  `is_reservation` tinyint(1) NOT NULL,
  `position_type` tinyint(1) DEFAULT '1',
  `jinyun_type` tinyint(1) DEFAULT '1',
  `is_shouyin` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支持收银',
  `api_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '打印机api类型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_queue_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `queueid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `from_user` varchar(200) NOT NULL,
  `num` varchar(100) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `usercount` int(10) unsigned NOT NULL,
  `isnotify` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_queue_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `limit_num` int(10) unsigned NOT NULL,
  `prefix` varchar(50) NOT NULL,
  `starttime` varchar(50) NOT NULL,
  `endtime` varchar(50) NOT NULL,
  `notify_number` int(10) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_recharge` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `storeid` int(10) NOT NULL DEFAULT '0' COMMENT '门店id',
  `title` varchar(200) NOT NULL COMMENT '活动名称',
  `recharge_value` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值多少',
  `give_value` int(10) NOT NULL DEFAULT '0' COMMENT '赠送多少',
  `total` int(10) NOT NULL DEFAULT '0' COMMENT '几期',
  `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `content` text NOT NULL,
  `displayorder` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_recharge_record` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rechargeid` int(10) NOT NULL DEFAULT '0',
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `from_user` varchar(100) DEFAULT '',
  `storeid` int(10) NOT NULL DEFAULT '0' COMMENT '门店id',
  `orderid` int(10) NOT NULL DEFAULT '0' COMMENT '订单id',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `displayorder` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `remark` text NOT NULL COMMENT '备注',
  `givetime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `description` varchar(1000) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_reservation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `tablezonesid` int(10) unsigned NOT NULL,
  `time` varchar(200) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `label` varchar(50) NOT NULL DEFAULT '' COMMENT '标签',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_savewine_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT NULL,
  `savenumber` varchar(100) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `tel` varchar(30) DEFAULT NULL,
  `remark` text NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `takeouttime` int(10) unsigned NOT NULL,
  `savetime` int(10) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_service_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `thumb` varchar(200) DEFAULT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `entrance_type` tinyint(1) unsigned NOT NULL,
  `entrance_storeid` tinyint(1) unsigned NOT NULL,
  `order_enable` tinyint(1) unsigned NOT NULL,
  `dining_mode` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) DEFAULT NULL,
  `istplnotice` tinyint(1) NOT NULL,
  `tplneworder` varchar(200) DEFAULT NULL,
  `tpluser` text,
  `searchword` varchar(1000) DEFAULT NULL,
  `mode` tinyint(1) NOT NULL,
  `tplnewqueue` varchar(200) DEFAULT NULL,
  `is_notice` tinyint(1) unsigned NOT NULL,
  `sms_enable` tinyint(1) unsigned NOT NULL,
  `sms_username` varchar(20) DEFAULT NULL,
  `sms_pwd` varchar(20) DEFAULT NULL,
  `sms_mobile` varchar(20) DEFAULT NULL,
  `email_enable` tinyint(1) unsigned NOT NULL,
  `email_host` varchar(50) DEFAULT NULL,
  `email_send` varchar(100) DEFAULT NULL,
  `email_pwd` varchar(20) DEFAULT NULL,
  `email_user` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tpltype` tinyint(1) NOT NULL,
  `sms_id` tinyint(11) unsigned NOT NULL,
  `is_sms` int(10) NOT NULL,
  `tploperator` varchar(200) DEFAULT NULL,
  `commission_level` tinyint(1) unsigned NOT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_desc` varchar(300) DEFAULT NULL,
  `share_image` varchar(500) DEFAULT NULL,
  `commission2_rate_max` decimal(10,2) unsigned NOT NULL,
  `commission2_value_max` int(10) unsigned NOT NULL,
  `commission3_rate_max` decimal(10,2) unsigned NOT NULL,
  `commission3_value_max` int(10) unsigned NOT NULL,
  `is_commission` tinyint(1) unsigned NOT NULL,
  `commission1_rate_max` decimal(10,2) unsigned NOT NULL,
  `commission1_value_max` int(10) unsigned NOT NULL,
  `commission_settlement` tinyint(1) unsigned NOT NULL,
  `getcash_price` int(10) unsigned NOT NULL,
  `fee_rate` decimal(10,2) unsigned NOT NULL,
  `fee_min` int(10) unsigned NOT NULL,
  `fee_max` int(10) unsigned NOT NULL,
  `follow_url` varchar(500) DEFAULT NULL,
  `isneedfollow` tinyint(1) DEFAULT NULL,
  `wechat` tinyint(1) NOT NULL,
  `alipay` tinyint(1) NOT NULL,
  `credit` tinyint(1) NOT NULL,
  `delivery` tinyint(1) NOT NULL,
  `is_speaker` tinyint(1) NOT NULL,
  `link_card` varchar(500) DEFAULT NULL,
  `link_sign` varchar(500) DEFAULT NULL,
  `link_sign_name` varchar(100) DEFAULT NULL,
  `link_card_name` varchar(100) DEFAULT NULL,
  `tplboss` varchar(200) NOT NULL DEFAULT '',
  `tplapplynotice` varchar(200) NOT NULL DEFAULT '',
  `commission_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分销模式',
  `commission_money_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '佣金模式',
  `delivery_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '配送通知模式',
  `delivery_commission_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '配送佣金模式',
  `delivery_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `delivery_cash_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现最低金额',
  `delivery_rate` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '佣金费率',
  `delivery_order_max` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '配送订单数量限制',
  `delivery_auto_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '自动推送配送员时间设置',
  `delivery_finish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '自动完成已配送订单',
  `link_recharge` varchar(500) DEFAULT '',
  `link_recharge_name` varchar(100) DEFAULT '',
  `is_auto_address` tinyint(1) NOT NULL DEFAULT '0',
  `is_show_home` tinyint(1) NOT NULL DEFAULT '1',
  `is_operator_pwd` tinyint(1) NOT NULL DEFAULT '0',
  `is_contain_delivery` tinyint(1) NOT NULL DEFAULT '1',
  `tiptype` tinyint(1) NOT NULL DEFAULT '1',
  `tipbtn` tinyint(1) NOT NULL DEFAULT '1',
  `tipqrcode` varchar(500) DEFAULT '',
  `operator_pwd` varchar(50) DEFAULT '',
  `apiclient_cert` tinyint(1) NOT NULL DEFAULT '0',
  `apiclient_key` tinyint(1) NOT NULL DEFAULT '0',
  `rootca` tinyint(1) NOT NULL DEFAULT '0',
  `follow_title` varchar(500) DEFAULT '',
  `follow_desc` varchar(500) DEFAULT '',
  `follow_logo` varchar(500) DEFAULT '',
  `site_logo` varchar(500) DEFAULT '',
  `statistics` text NOT NULL,
  `fengniao_appid` varchar(200) DEFAULT '',
  `fengniao_key` varchar(200) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_sms_checkcode` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `checkcode` varchar(100) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_sms_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `sms_enable` tinyint(1) unsigned NOT NULL,
  `sms_verify_enable` tinyint(1) unsigned NOT NULL,
  `sms_username` varchar(20) DEFAULT NULL,
  `sms_pwd` varchar(20) DEFAULT NULL,
  `sms_mobile` varchar(20) DEFAULT NULL,
  `sms_verify_tpl` varchar(120) DEFAULT NULL,
  `sms_business_tpl` varchar(120) DEFAULT NULL,
  `sms_user_tpl` varchar(120) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  `is_sms` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_sncode` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `couponid` int(10) NOT NULL,
  `storeid` int(10) NOT NULL,
  `weid` int(10) NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `title` varchar(40) NOT NULL,
  `sncode` varchar(100) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `isshow` tinyint(1) NOT NULL,
  `winningtime` int(10) NOT NULL,
  `usetime` int(10) NOT NULL,
  `dateline` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_store_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `order_enable` tinyint(1) unsigned NOT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_stores` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `areaid` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `logo` varchar(500) NOT NULL,
  `info` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `tel` varchar(20) NOT NULL,
  `location_p` varchar(100) NOT NULL,
  `location_c` varchar(100) NOT NULL,
  `location_a` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `place` varchar(200) NOT NULL,
  `lat` decimal(18,10) NOT NULL,
  `lng` decimal(18,10) NOT NULL,
  `password` varchar(20) NOT NULL,
  `hours` varchar(200) NOT NULL,
  `recharging_password` varchar(20) NOT NULL,
  `thumb_url` varchar(1000) DEFAULT NULL,
  `enable_wifi` tinyint(1) unsigned NOT NULL,
  `enable_card` tinyint(1) unsigned NOT NULL,
  `enable_room` tinyint(1) unsigned NOT NULL,
  `enable_park` tinyint(1) unsigned NOT NULL,
  `is_show` tinyint(1) NOT NULL,
  `is_meal` tinyint(1) NOT NULL,
  `is_delivery` tinyint(1) NOT NULL,
  `sendingprice` varchar(10) NOT NULL,
  `displayorder` tinyint(3) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `is_sms` tinyint(1) NOT NULL,
  `dateline` int(10) NOT NULL,
  `dispatchprice` decimal(10,2) DEFAULT NULL,
  `is_hot` tinyint(1) NOT NULL,
  `freeprice` decimal(10,2) DEFAULT NULL,
  `begintime` varchar(20) DEFAULT NULL,
  `announce` varchar(1000) NOT NULL,
  `endtime` varchar(20) DEFAULT NULL,
  `consume` varchar(20) NOT NULL,
  `level` tinyint(1) NOT NULL,
  `is_rest` tinyint(1) NOT NULL,
  `typeid` int(10) NOT NULL,
  `from_user` varchar(200) NOT NULL,
  `delivery_within_days` int(10) NOT NULL,
  `delivery_radius` decimal(18,1) NOT NULL,
  `not_in_delivery_radius` tinyint(1) NOT NULL,
  `btn_reservation` varchar(100) NOT NULL,
  `btn_eat` varchar(100) NOT NULL,
  `btn_delivery` varchar(100) NOT NULL,
  `btn_snack` varchar(100) NOT NULL,
  `btn_queue` varchar(100) NOT NULL,
  `btn_intelligent` varchar(100) NOT NULL,
  `is_snack` tinyint(1) NOT NULL,
  `is_reservation` tinyint(1) NOT NULL,
  `is_queue` tinyint(1) NOT NULL,
  `is_intelligent` tinyint(1) NOT NULL,
  `coupon_title1` varchar(100) NOT NULL,
  `coupon_title2` varchar(100) NOT NULL,
  `coupon_title3` varchar(100) NOT NULL,
  `coupon_link1` varchar(200) NOT NULL,
  `coupon_link2` varchar(200) NOT NULL,
  `coupon_link3` varchar(200) NOT NULL,
  `qq` varchar(20) NOT NULL,
  `weixin` varchar(20) NOT NULL,
  `is_hour` tinyint(1) NOT NULL,
  `default_jump` tinyint(2) NOT NULL,
  `is_savewine` tinyint(1) NOT NULL,
  `notice_space_time` int(10) DEFAULT NULL,
  `is_operator1` tinyint(1) NOT NULL,
  `is_operator2` tinyint(1) NOT NULL,
  `kefu_qrcode` varchar(500) NOT NULL,
  `reservation_announce` varchar(1000) NOT NULL,
  `is_business` tinyint(1) NOT NULL,
  `business_id` int(10) NOT NULL,
  `wechat` tinyint(1) NOT NULL,
  `alipay` tinyint(1) NOT NULL,
  `credit` tinyint(1) NOT NULL,
  `delivery` tinyint(1) NOT NULL,
  `delivery_isnot_today` tinyint(1) NOT NULL,
  `is_speaker` tinyint(1) NOT NULL,
  `screen_mode` tinyint(1) NOT NULL,
  `screen_title` varchar(200) NOT NULL,
  `screen_bg` varchar(500) NOT NULL,
  `screen_bottom` varchar(200) NOT NULL,
  `begintime1` varchar(20) DEFAULT NULL,
  `endtime1` varchar(20) DEFAULT NULL,
  `begintime2` varchar(20) DEFAULT NULL,
  `endtime2` varchar(20) DEFAULT NULL,
  `area` varchar(1000) NOT NULL,
  `default_jump_url` varchar(500) NOT NULL DEFAULT '' COMMENT '默认链接',
  `thumbs` text NOT NULL,
  `is_locktables` tinyint(1) NOT NULL DEFAULT '0',
  `is_dispatcharea` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用配送区域',
  `is_brand` tinyint(1) NOT NULL DEFAULT '0' COMMENT '品牌商家',
  `is_meal_pay_confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店内点餐在线支付是否需要确认',
  `is_list` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在列表显示',
  `is_add_dish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加菜',
  `is_newlimitprice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '新顾客满减',
  `is_oldlimitprice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '老顾客满减',
  `is_add_order` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加单',
  `is_shouyin` tinyint(1) NOT NULL DEFAULT '0',
  `is_bank_pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支持银行支付',
  `bank_pay_id` int(10) NOT NULL DEFAULT '0' COMMENT '银行活动ID',
  `is_vtiny_bankpay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支持银行支付',
  `vtiny_bankpay_id` int(10) NOT NULL DEFAULT '0' COMMENT '银行活动ID',
  `vtiny_bankpay_url` varchar(500) NOT NULL DEFAULT '' COMMENT '链接',
  `is_auto_confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否自动确认订单',
  `is_order_autoconfirm` tinyint(1) NOT NULL DEFAULT '0',
  `btn_shouyin` varchar(100) NOT NULL DEFAULT '收银',
  `is_reservation_dish` tinyint(1) NOT NULL DEFAULT '1',
  `reservation_days` int(10) NOT NULL DEFAULT '7' COMMENT '预订天数',
  `is_reservation_today` tinyint(1) NOT NULL DEFAULT '1',
  `reservation_tip` varchar(200) NOT NULL DEFAULT '请输入备注，人数口味等等（可不填）',
  `reservation_wechat` tinyint(1) NOT NULL DEFAULT '1',
  `reservation_alipay` tinyint(1) NOT NULL DEFAULT '1',
  `reservation_credit` tinyint(1) NOT NULL DEFAULT '1',
  `reservation_delivery` tinyint(1) NOT NULL DEFAULT '1',
  `business_type` tinyint(1) NOT NULL DEFAULT '0',
  `business_openid` varchar(200) NOT NULL DEFAULT '',
  `business_username` varchar(200) NOT NULL DEFAULT '',
  `business_alipay` varchar(200) NOT NULL DEFAULT '',
  `business_wechat` varchar(200) NOT NULL DEFAULT '',
  `is_ld_wxserver` tinyint(1) DEFAULT NULL,
  `ld_wxserver_url` varchar(255) DEFAULT NULL,
  `is_default_rate` tinyint(1) NOT NULL DEFAULT '1' COMMENT '默认1,自定义2',
  `getcash_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最低提现金额',
  `fee_rate` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现费率',
  `fee_min` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现手续费最低金额',
  `fee_max` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现手续费最高金额',
  `is_tea_money` tinyint(1) NOT NULL DEFAULT '0',
  `tea_money` decimal(10,2) DEFAULT '0.00',
  `tea_tip` varchar(200) NOT NULL DEFAULT '',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_delivery_nowtime` tinyint(1) NOT NULL DEFAULT '1',
  `is_jueqi_ymf` tinyint(1) NOT NULL DEFAULT '0',
  `jueqi_host` varchar(200) NOT NULL DEFAULT '',
  `jueqi_customerId` varchar(200) NOT NULL DEFAULT '',
  `is_order_tip` tinyint(1) NOT NULL DEFAULT '0',
  `default_user_count` int(10) unsigned NOT NULL DEFAULT '5' COMMENT '默认的用餐人数',
  `is_delivery_distance` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支持配送距离',
  `is_fengniao` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `tablezonesid` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `user_count` int(10) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `url` varchar(500) NOT NULL,
  `print_label` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_tables_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `tablesid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `from_user` varchar(200) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_tablezones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `limit_price` int(10) unsigned NOT NULL,
  `reservation_price` int(10) unsigned NOT NULL,
  `table_count` int(10) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `service_rate` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_template` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `template_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_tpl_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `storeid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `ordersn` varchar(100) DEFAULT NULL,
  `from_user` varchar(100) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `result` varchar(200) DEFAULT NULL,
  `dateline` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `parentid` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '链接',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_useraddress` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `realname` varchar(20) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `address` varchar(300) NOT NULL DEFAULT '',
  `doorplate` varchar(300) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT '1',
  `isdefault` tinyint(1) NOT NULL DEFAULT '0',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `dateline` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('weisrc_dish_account',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'pwd')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `pwd` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `mobile` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'email')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `email` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `from_user` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `storeid` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'pay_account')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `pay_account` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'accountname')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `accountname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'password')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `password` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'salt')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `salt` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `username` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'role')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `role` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1:店长,2:店员';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'lastvisit')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `lastvisit` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'lastip')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `lastip` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_account',  'areaid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `areaid` int(10) NOT NULL DEFAULT '0' COMMENT '区域id';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'is_admin_order')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `is_admin_order` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'is_notice_order')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `is_notice_order` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'is_notice_queue')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `is_notice_queue` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'is_notice_service')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `is_notice_service` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'is_notice_boss')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `is_notice_boss` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('weisrc_dish_account',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_account')." ADD `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `url` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `thumb` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'position')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `position` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `starttime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `endtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `displayorder` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_ad',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_ad')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_address')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_address',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_address')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_address',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_address')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_address',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_address')." ADD `realname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_address',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_address')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_address',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_address')." ADD `address` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_address',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_address')." ADD `dateline` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_area',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_area')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_area',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_area')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_area',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_area')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_area',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_area')." ADD `parentid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_area',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_area')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_area',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_area')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_area',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_area')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_blacklist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_blacklist')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_blacklist',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_blacklist')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_blacklist',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_blacklist')." ADD `from_user` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_blacklist',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_blacklist')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_blacklist',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_blacklist')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_indexexists('weisrc_dish_blacklist',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_blacklist')." ADD KEY `idx_openid` (`from_user`);");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `price` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'haveprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `haveprice` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'totalprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `totalprice` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'handletime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `handletime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'dining_mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `dining_mode` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'charges')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `charges` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'successprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `successprice` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'business_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `business_type` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'trade_no')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `trade_no` varchar(200) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'payment_no')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `payment_no` varchar(200) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_businesslog',  'result')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_businesslog')." ADD `result` varchar(1000) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_cart',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cart')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_cart',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cart')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_cart',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cart')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_cart',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cart')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_cart',  'goodstype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cart')." ADD `goodstype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_cart',  'price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cart')." ADD `price` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_cart',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cart')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_cart',  'total')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cart')." ADD `total` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_cart',  'packvalue')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cart')." ADD `packvalue` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `storeid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `from_user` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'logtype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `logtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '日志类型1佣金2配送佣金';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型1微信2余额';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `price` decimal(10,2) DEFAULT '0.00' COMMENT '申请金额';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'charges')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `charges` decimal(10,2) DEFAULT '0.00' COMMENT '手续费';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'successprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `successprice` decimal(10,2) DEFAULT '0.00' COMMENT '到帐金额';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'totalprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `totalprice` decimal(10,2) DEFAULT '0.00' COMMENT '余额';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'trade_no')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `trade_no` varchar(200) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'payment_no')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `payment_no` varchar(200) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `remark` varchar(1000) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'handletime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `handletime` int(10) DEFAULT '0' COMMENT '处理时间';");
}
if(!pdo_fieldexists('weisrc_dish_cashlog',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_cashlog')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `parentid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `enabled` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'is_meal')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `is_meal` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'is_delivery')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `is_delivery` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'is_snack')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `is_snack` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'is_reservation')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `is_reservation` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_category',  'rebate')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `rebate` decimal(5,2) NOT NULL DEFAULT '10.00' COMMENT '打折费率';");
}
if(!pdo_fieldexists('weisrc_dish_category',  'is_discount')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_category')." ADD `is_discount` int(2) NOT NULL DEFAULT '0' COMMENT '是否开启打折';");
}
if(!pdo_fieldexists('weisrc_dish_collection',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_collection')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_collection',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_collection')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_collection',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_collection')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_collection',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_collection')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_collection',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_collection')." ADD `dateline` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `weid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'agentid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `agentid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `ordersn` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `from_user` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `price` decimal(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_commission',  'level')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_commission')." ADD `level` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `storeid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'levelid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `levelid` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `thumb` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'attr_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `attr_type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'ruletype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `ruletype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'gmoney')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `gmoney` decimal(18,2) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'dmoney')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `dmoney` decimal(18,2) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'totalcount')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `totalcount` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'usercount')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `usercount` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'ticket_ty')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `ticket_ty` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'ticket_id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `ticket_id` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'needscore')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `needscore` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `starttime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `endtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `displayorder` tinyint(4) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `dateline` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'is_meal')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `is_meal` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否店内点餐';");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'is_delivery')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `is_delivery` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否外卖订餐';");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'is_snack')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `is_snack` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支持快餐';");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'is_reservation')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `is_reservation` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支持预定';");
}
if(!pdo_fieldexists('weisrc_dish_coupon',  'is_shouyin')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_coupon')." ADD `is_shouyin` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支持收银';");
}
if(!pdo_fieldexists('weisrc_dish_credits_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_credits_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_credits_record',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_credits_record')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_credits_record',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_credits_record')." ADD `from_user` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_credits_record',  'credittype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_credits_record')." ADD `credittype` varchar(100) DEFAULT '' COMMENT '积分类型';");
}
if(!pdo_fieldexists('weisrc_dish_credits_record',  'num')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_credits_record')." ADD `num` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_credits_record',  'operator')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_credits_record')." ADD `operator` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_credits_record',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_credits_record')." ADD `remark` varchar(1000) DEFAULT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('weisrc_dish_credits_record',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_credits_record')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_delivery_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_delivery_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_delivery_record',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_delivery_record')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('weisrc_dish_delivery_record',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_delivery_record')." ADD `storeid` int(10) NOT NULL DEFAULT '0' COMMENT '门店id';");
}
if(!pdo_fieldexists('weisrc_dish_delivery_record',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_delivery_record')." ADD `orderid` int(10) NOT NULL DEFAULT '0' COMMENT '订单id';");
}
if(!pdo_fieldexists('weisrc_dish_delivery_record',  'delivery_id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_delivery_record')." ADD `delivery_id` int(10) NOT NULL DEFAULT '0' COMMENT '配送员id';");
}
if(!pdo_fieldexists('weisrc_dish_delivery_record',  'price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_delivery_record')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_delivery_record',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_delivery_record')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_delivery_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_delivery_record')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_dish_deliveryarea',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_deliveryarea')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_deliveryarea',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_deliveryarea')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('weisrc_dish_deliveryarea',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_deliveryarea')." ADD `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店';");
}
if(!pdo_fieldexists('weisrc_dish_deliveryarea',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_deliveryarea')." ADD `title` varchar(50) NOT NULL COMMENT '区域名称';");
}
if(!pdo_fieldexists('weisrc_dish_deliveryarea',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_deliveryarea')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('weisrc_dish_deliveryarea',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_deliveryarea')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('weisrc_dish_deliveryarea',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_deliveryarea')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('weisrc_dish_deliveryarea',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_deliveryarea')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_deliveryarea',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_deliveryarea')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_dish_dispatcharea',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_dispatcharea')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_dispatcharea',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_dispatcharea')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('weisrc_dish_dispatcharea',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_dispatcharea')." ADD `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属门店';");
}
if(!pdo_fieldexists('weisrc_dish_dispatcharea',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_dispatcharea')." ADD `title` varchar(50) NOT NULL COMMENT '区域名称';");
}
if(!pdo_fieldexists('weisrc_dish_dispatcharea',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_dispatcharea')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('weisrc_dish_dispatcharea',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_dispatcharea')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_dispatcharea',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_dispatcharea')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_dish_distance',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_distance')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_distance',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_distance')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_distance',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_distance')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_distance',  'begindistance')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_distance')." ADD `begindistance` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_distance',  'enddistance')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_distance')." ADD `enddistance` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_distance',  'sendingprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_distance')." ADD `sendingprice` decimal(10,2) DEFAULT '0.00' COMMENT '起送价格';");
}
if(!pdo_fieldexists('weisrc_dish_distance',  'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_distance')." ADD `dispatchprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_distance',  'freeprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_distance')." ADD `freeprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_distance',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_distance')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'email_enable')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `email_enable` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'email_host')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `email_host` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'email_send')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `email_send` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'email_pwd')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `email_pwd` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'email_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `email_user` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'email')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `email` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'email_business_tpl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `email_business_tpl` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_email_setting',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_email_setting')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `from_user` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `nickname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `headimgurl` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `username` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `mobile` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `address` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `sex` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `lat` decimal(18,10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `lng` decimal(18,10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `status` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `storeid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'totalcount')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `totalcount` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'totalprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `totalprice` decimal(18,2) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'avgprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `avgprice` decimal(18,2) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `uid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `paytime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `lasttime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'agentid2')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `agentid2` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'agentid3')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `agentid3` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'noticetime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `noticetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'agentid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `agentid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'country')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `country` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'province')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'city')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'is_commission')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `is_commission` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'commission_price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `commission_price` decimal(18,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_fans',  'delivery_price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD `delivery_price` decimal(18,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_indexexists('weisrc_dish_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fans')." ADD KEY `indx_rid` (`id`);");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `weid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `from_user` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'star')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `star` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `content` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `displayorder` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_feedback',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_feedback')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `orderid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'open_order_code')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `open_order_code` varchar(200) DEFAULT '' COMMENT '蜂鸟配送开放平台返回的订单号';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'partner_order_code')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `partner_order_code` varchar(200) DEFAULT '' COMMENT '商户自己的订单号';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'order_status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `order_status` int(11) DEFAULT '0' COMMENT '状态码';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'push_time')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `push_time` int(11) DEFAULT '0' COMMENT '状态推送时间(毫秒)';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'carrier_driver_name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `carrier_driver_name` varchar(200) DEFAULT '' COMMENT '蜂鸟配送员姓名';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'carrier_driver_phone')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `carrier_driver_phone` varchar(200) DEFAULT '' COMMENT '蜂鸟配送员电话';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'description')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `description` varchar(200) DEFAULT '' COMMENT '描述信息';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `address` varchar(200) DEFAULT '' COMMENT '定点次日达服务独有的字段: 微仓地址';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'latitude')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `latitude` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '定点次日达服务独有的字段: 微仓纬度';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'longitude')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `longitude` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '定点次日达服务独有的字段: 微仓经度';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'cancel_reason')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `cancel_reason` int(11) DEFAULT '0' COMMENT '订单取消原因. 1:用户取消, 2:商家取消';");
}
if(!pdo_fieldexists('weisrc_dish_fengniao',  'error_code')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_fengniao')." ADD `error_code` varchar(200) DEFAULT '' COMMENT '错误编码';");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `pcate` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `ccate` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'recommend')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `recommend` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `displayorder` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `thumb` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'unitname')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `unitname` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'description')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'taste')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `taste` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'isspecial')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `isspecial` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `marketprice` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `productprice` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `credit` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'subcount')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `subcount` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `lasttime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `deleted` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'counts')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `counts` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `sales` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'today_counts')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `today_counts` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'labelid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `labelid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'packvalue')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `packvalue` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `content` text;");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'week')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `week` varchar(100) NOT NULL DEFAULT '1,2,3,4,5,6,0' COMMENT '星期';");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'memberprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `memberprice` varchar(10) NOT NULL DEFAULT '' COMMENT '会员价';");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'delivery_commission_money')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `delivery_commission_money` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'commission_money1')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `commission_money1` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '一级佣金';");
}
if(!pdo_fieldexists('weisrc_dish_goods',  'commission_money2')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods')." ADD `commission_money2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '二级佣金';");
}
if(!pdo_fieldexists('weisrc_dish_goods_option',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods_option')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_goods_option',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods_option')." ADD `goodsid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_goods_option',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods_option')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_goods_option',  'price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods_option')." ADD `price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_goods_option',  'stock')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods_option')." ADD `stock` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_goods_option',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods_option')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_goods_option',  'specs')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods_option')." ADD `specs` text;");
}
if(!pdo_indexexists('weisrc_dish_goods_option',  'indx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_goods_option')." ADD KEY `indx_goodsid` (`goodsid`);");
}
if(!pdo_fieldexists('weisrc_dish_intelligent',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_intelligent')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_intelligent',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_intelligent')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_intelligent',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_intelligent')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_intelligent',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_intelligent')." ADD `name` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_intelligent',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_intelligent')." ADD `content` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_intelligent',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_intelligent')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_intelligent',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_intelligent')." ADD `enabled` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_mealtime',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_mealtime')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_mealtime',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_mealtime')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_mealtime',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_mealtime')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_mealtime',  'begintime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_mealtime')." ADD `begintime` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_mealtime',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_mealtime')." ADD `endtime` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_mealtime',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_mealtime')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_mealtime',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_mealtime')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_indexexists('weisrc_dish_mealtime',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_mealtime')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('weisrc_dish_nave',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_nave')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_nave',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_nave')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_nave',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_nave')." ADD `type` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_nave',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_nave')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_nave',  'link')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_nave')." ADD `link` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_nave',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_nave')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_nave',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_nave')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `ordersn` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'totalnum')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `totalnum` tinyint(4) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'totalprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `totalprice` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `status` tinyint(4) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `paytype` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `username` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `address` varchar(250) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `tel` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'reply')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `reply` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'meal_time')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `meal_time` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'counts')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `counts` tinyint(4) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'seat_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `seat_type` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'carports')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `carports` tinyint(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'dining_mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `dining_mode` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `remark` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'tables')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `tables` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'print_sta')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `print_sta` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'sign')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `sign` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'isfinish')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `isfinish` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `transid` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'goodsprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `goodsprice` decimal(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `dispatchprice` decimal(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'isemail')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `isemail` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'issms')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `issms` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'istpl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `istpl` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `credit` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'paydetail')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `paydetail` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'ispay')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `ispay` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'tablezonesid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `tablezonesid` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'isfeedback')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `isfeedback` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `couponid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'packvalue')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `packvalue` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'quyu')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `quyu` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order',  'rechargeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `rechargeid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'quicknum')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `quicknum` varchar(30) NOT NULL DEFAULT '0001';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'tea_money')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `tea_money` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'service_money')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `service_money` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'discount_money')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `discount_money` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'ismerge')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `ismerge` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否合并的单子';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'delivery_id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `delivery_id` int(10) NOT NULL DEFAULT '0' COMMENT '配送员id';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'delivery_status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `delivery_status` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'deliveryareaid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `deliveryareaid` int(10) NOT NULL DEFAULT '0' COMMENT '配送点id';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'delivery_money')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `delivery_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '佣金';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'is_append')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `is_append` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否加单';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'append_dish')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `append_dish` tinyint(2) NOT NULL DEFAULT '0' COMMENT '加菜';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'delivery_notice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `delivery_notice` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'isvip')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `isvip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '会员';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'finishtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `finishtime` int(10) DEFAULT '0' COMMENT '完成时间';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'confirmtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `confirmtime` int(10) DEFAULT '0' COMMENT '确认时间';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `paytime` int(10) DEFAULT '0' COMMENT '付款时间';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'delivery_finish_time')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `delivery_finish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已配送时间';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'newlimitprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `newlimitprice` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'oldlimitprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `oldlimitprice` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'newlimitpricevalue')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `newlimitpricevalue` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_order',  'oldlimitpricevalue')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order')." ADD `oldlimitpricevalue` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_order_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_order_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order_goods',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_goods')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order_goods',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_goods')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order_goods',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_goods')." ADD `goodsid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order_goods',  'price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_goods')." ADD `price` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_goods')." ADD `total` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order_goods',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_goods')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_order_log',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_log')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_order_log',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_log')." ADD `storeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店编号';");
}
if(!pdo_fieldexists('weisrc_dish_order_log',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_log')." ADD `orderid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单编号';");
}
if(!pdo_fieldexists('weisrc_dish_order_log',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_log')." ADD `content` varchar(1000) DEFAULT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('weisrc_dish_order_log',  'fromtype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_log')." ADD `fromtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '来源';");
}
if(!pdo_fieldexists('weisrc_dish_order_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_log')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_dish_order_log',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_order_log')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_print_label',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_label')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_print_label',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_label')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_label',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_label')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_label',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_label')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_label',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_label')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_label',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_label')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_order')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_print_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_order',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_order')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_order',  'print_usr')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_order')." ADD `print_usr` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_order',  'print_status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_order')." ADD `print_status` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_order',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_order')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'print_status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `print_status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'print_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `print_type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'print_usr')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `print_usr` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'print_nums')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `print_nums` tinyint(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'print_top')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `print_top` varchar(40) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'print_bottom')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `print_bottom` varchar(40) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'qrcode_status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `qrcode_status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'qrcode_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `qrcode_url` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `type` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'member_code')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `member_code` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'feyin_key')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `feyin_key` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'is_print_all')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `is_print_all` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'print_goodstype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `print_goodstype` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'printid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `printid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'yilian_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `yilian_type` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'api_key')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `api_key` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'print_label')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `print_label` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'is_meal')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `is_meal` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'is_delivery')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `is_delivery` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'is_snack')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `is_snack` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'is_reservation')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `is_reservation` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'position_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `position_type` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'jinyun_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `jinyun_type` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'is_shouyin')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `is_shouyin` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否支持收银';");
}
if(!pdo_fieldexists('weisrc_dish_print_setting',  'api_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_print_setting')." ADD `api_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '打印机api类型';");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'queueid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `queueid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `from_user` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'num')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `num` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `mobile` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'usercount')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `usercount` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'isnotify')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `isnotify` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_order',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_order')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'limit_num')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `limit_num` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'prefix')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `prefix` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `starttime` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `endtime` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'notify_number')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `notify_number` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_queue_setting',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_queue_setting')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `storeid` int(10) NOT NULL DEFAULT '0' COMMENT '门店id';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `title` varchar(200) NOT NULL COMMENT '活动名称';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'recharge_value')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `recharge_value` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值多少';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'give_value')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `give_value` int(10) NOT NULL DEFAULT '0' COMMENT '赠送多少';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'total')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `total` int(10) NOT NULL DEFAULT '0' COMMENT '几期';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `displayorder` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_dish_recharge',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge')." ADD `dateline` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'rechargeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `rechargeid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `from_user` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `storeid` int(10) NOT NULL DEFAULT '0' COMMENT '门店id';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `orderid` int(10) NOT NULL DEFAULT '0' COMMENT '订单id';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `displayorder` tinyint(4) NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `remark` text NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'givetime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `givetime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间';");
}
if(!pdo_fieldexists('weisrc_dish_recharge_record',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_recharge_record')." ADD `dateline` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reply',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD `type` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reply',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reply',  'picture')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD `picture` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reply',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('weisrc_dish_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reply')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_fieldexists('weisrc_dish_reservation',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reservation')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_reservation',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reservation')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reservation',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reservation')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reservation',  'tablezonesid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reservation')." ADD `tablezonesid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reservation',  'time')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reservation')." ADD `time` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reservation',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reservation')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reservation',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reservation')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_reservation',  'label')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_reservation')." ADD `label` varchar(50) NOT NULL DEFAULT '' COMMENT '标签';");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `from_user` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'savenumber')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `savenumber` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `username` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `tel` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `remark` text NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `displayorder` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'takeouttime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `takeouttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'savetime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `savetime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_savewine_log',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_savewine_log')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_service_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_service_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_service_log',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_service_log')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_service_log',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_service_log')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_service_log',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_service_log')." ADD `from_user` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_service_log',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_service_log')." ADD `content` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_service_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_service_log')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_service_log',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_service_log')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `thumb` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'entrance_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `entrance_type` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'entrance_storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `entrance_storeid` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'order_enable')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `order_enable` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'dining_mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `dining_mode` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'istplnotice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `istplnotice` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tplneworder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tplneworder` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tpluser')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tpluser` text;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'searchword')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `searchword` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `mode` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tplnewqueue')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tplnewqueue` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'is_notice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `is_notice` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'sms_enable')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `sms_enable` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'sms_username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `sms_username` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'sms_pwd')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `sms_pwd` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'sms_mobile')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `sms_mobile` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'email_enable')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `email_enable` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'email_host')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `email_host` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'email_send')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `email_send` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'email_pwd')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `email_pwd` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'email_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `email_user` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'email')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `email` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tpltype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tpltype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'sms_id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `sms_id` tinyint(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'is_sms')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `is_sms` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tploperator')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tploperator` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission_level')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission_level` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `share_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `share_desc` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'share_image')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `share_image` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission2_rate_max')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission2_rate_max` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission2_value_max')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission2_value_max` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission3_rate_max')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission3_rate_max` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission3_value_max')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission3_value_max` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'is_commission')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `is_commission` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission1_rate_max')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission1_rate_max` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission1_value_max')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission1_value_max` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission_settlement')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission_settlement` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'getcash_price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `getcash_price` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'fee_rate')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `fee_rate` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'fee_min')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `fee_min` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'fee_max')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `fee_max` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `follow_url` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'isneedfollow')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `isneedfollow` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'wechat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `wechat` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'alipay')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `alipay` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `credit` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'delivery')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `delivery` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'is_speaker')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `is_speaker` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'link_card')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `link_card` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'link_sign')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `link_sign` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'link_sign_name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `link_sign_name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'link_card_name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `link_card_name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tplboss')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tplboss` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tplapplynotice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tplapplynotice` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission_mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分销模式';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'commission_money_mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `commission_money_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '佣金模式';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'delivery_mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `delivery_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '配送通知模式';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'delivery_commission_mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `delivery_commission_mode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '配送佣金模式';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'delivery_money')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `delivery_money` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '佣金';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'delivery_cash_price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `delivery_cash_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现最低金额';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'delivery_rate')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `delivery_rate` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '佣金费率';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'delivery_order_max')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `delivery_order_max` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '配送订单数量限制';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'delivery_auto_time')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `delivery_auto_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '自动推送配送员时间设置';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'delivery_finish_time')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `delivery_finish_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '自动完成已配送订单';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'link_recharge')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `link_recharge` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'link_recharge_name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `link_recharge_name` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'is_auto_address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `is_auto_address` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'is_show_home')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `is_show_home` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'is_operator_pwd')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `is_operator_pwd` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'is_contain_delivery')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `is_contain_delivery` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tiptype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tiptype` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tipbtn')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tipbtn` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'tipqrcode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `tipqrcode` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'operator_pwd')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `operator_pwd` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'apiclient_cert')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `apiclient_cert` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'apiclient_key')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `apiclient_key` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'rootca')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `rootca` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'follow_title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `follow_title` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'follow_desc')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `follow_desc` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'follow_logo')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `follow_logo` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'site_logo')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `site_logo` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'statistics')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `statistics` text NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'fengniao_appid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `fengniao_appid` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_setting',  'fengniao_key')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_setting')." ADD `fengniao_key` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_sms_checkcode',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_checkcode')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_sms_checkcode',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_checkcode')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_checkcode',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_checkcode')." ADD `from_user` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_checkcode',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_checkcode')." ADD `mobile` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_checkcode',  'checkcode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_checkcode')." ADD `checkcode` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_checkcode',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_checkcode')." ADD `status` tinyint(1) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_checkcode',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_checkcode')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'sms_enable')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `sms_enable` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'sms_verify_enable')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `sms_verify_enable` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'sms_username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `sms_username` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'sms_pwd')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `sms_pwd` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'sms_mobile')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `sms_mobile` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'sms_verify_tpl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `sms_verify_tpl` varchar(120) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'sms_business_tpl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `sms_business_tpl` varchar(120) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'sms_user_tpl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `sms_user_tpl` varchar(120) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sms_setting',  'is_sms')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sms_setting')." ADD `is_sms` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `couponid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `storeid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `from_user` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `title` varchar(40) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'sncode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `sncode` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'money')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `money` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `isshow` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'winningtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `winningtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'usetime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `usetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_sncode',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_sncode')." ADD `dateline` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_store_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_store_setting')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_store_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_store_setting')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_store_setting',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_store_setting')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_store_setting',  'order_enable')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_store_setting')." ADD `order_enable` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_store_setting',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_store_setting')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'areaid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `areaid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `logo` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'info')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `info` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `tel` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `location_p` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `location_c` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `location_a` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `address` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'place')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `place` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `lat` decimal(18,10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `lng` decimal(18,10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'password')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `password` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'hours')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `hours` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'recharging_password')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `recharging_password` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `thumb_url` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'enable_wifi')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `enable_wifi` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'enable_card')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `enable_card` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'enable_room')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `enable_room` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'enable_park')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `enable_park` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_show` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_meal')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_meal` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_delivery')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_delivery` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'sendingprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `sendingprice` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `displayorder` tinyint(3) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_sms')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_sms` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `dateline` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `dispatchprice` decimal(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_hot')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_hot` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'freeprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `freeprice` decimal(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'begintime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `begintime` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'announce')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `announce` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `endtime` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'consume')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `consume` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'level')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `level` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_rest')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_rest` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'typeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `typeid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `from_user` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'delivery_within_days')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `delivery_within_days` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'delivery_radius')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `delivery_radius` decimal(18,1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'not_in_delivery_radius')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `not_in_delivery_radius` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'btn_reservation')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `btn_reservation` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'btn_eat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `btn_eat` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'btn_delivery')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `btn_delivery` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'btn_snack')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `btn_snack` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'btn_queue')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `btn_queue` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'btn_intelligent')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `btn_intelligent` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_snack')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_snack` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_reservation')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_reservation` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_queue')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_queue` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_intelligent')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_intelligent` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'coupon_title1')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `coupon_title1` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'coupon_title2')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `coupon_title2` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'coupon_title3')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `coupon_title3` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'coupon_link1')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `coupon_link1` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'coupon_link2')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `coupon_link2` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'coupon_link3')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `coupon_link3` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `qq` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `weixin` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_hour')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_hour` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'default_jump')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `default_jump` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_savewine')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_savewine` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'notice_space_time')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `notice_space_time` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_operator1')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_operator1` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_operator2')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_operator2` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'kefu_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `kefu_qrcode` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'reservation_announce')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `reservation_announce` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_business')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_business` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'business_id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `business_id` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'wechat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `wechat` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'alipay')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `alipay` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `credit` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'delivery')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `delivery` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'delivery_isnot_today')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `delivery_isnot_today` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_speaker')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_speaker` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'screen_mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `screen_mode` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'screen_title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `screen_title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'screen_bg')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `screen_bg` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'screen_bottom')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `screen_bottom` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'begintime1')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `begintime1` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'endtime1')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `endtime1` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'begintime2')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `begintime2` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'endtime2')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `endtime2` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'area')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `area` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'default_jump_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `default_jump_url` varchar(500) NOT NULL DEFAULT '' COMMENT '默认链接';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `thumbs` text NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_locktables')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_locktables` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_dispatcharea')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_dispatcharea` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用配送区域';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_brand')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_brand` tinyint(1) NOT NULL DEFAULT '0' COMMENT '品牌商家';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_meal_pay_confirm')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_meal_pay_confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '店内点餐在线支付是否需要确认';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_list')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_list` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否在列表显示';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_add_dish')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_add_dish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加菜';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_newlimitprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_newlimitprice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '新顾客满减';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_oldlimitprice')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_oldlimitprice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '老顾客满减';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_add_order')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_add_order` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加单';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_shouyin')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_shouyin` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_bank_pay')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_bank_pay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支持银行支付';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'bank_pay_id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `bank_pay_id` int(10) NOT NULL DEFAULT '0' COMMENT '银行活动ID';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_vtiny_bankpay')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_vtiny_bankpay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支持银行支付';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'vtiny_bankpay_id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `vtiny_bankpay_id` int(10) NOT NULL DEFAULT '0' COMMENT '银行活动ID';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'vtiny_bankpay_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `vtiny_bankpay_url` varchar(500) NOT NULL DEFAULT '' COMMENT '链接';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_auto_confirm')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_auto_confirm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否自动确认订单';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_order_autoconfirm')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_order_autoconfirm` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'btn_shouyin')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `btn_shouyin` varchar(100) NOT NULL DEFAULT '收银';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_reservation_dish')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_reservation_dish` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'reservation_days')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `reservation_days` int(10) NOT NULL DEFAULT '7' COMMENT '预订天数';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_reservation_today')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_reservation_today` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'reservation_tip')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `reservation_tip` varchar(200) NOT NULL DEFAULT '请输入备注，人数口味等等（可不填）';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'reservation_wechat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `reservation_wechat` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'reservation_alipay')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `reservation_alipay` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'reservation_credit')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `reservation_credit` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'reservation_delivery')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `reservation_delivery` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'business_type')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `business_type` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'business_openid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `business_openid` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'business_username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `business_username` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'business_alipay')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `business_alipay` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'business_wechat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `business_wechat` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_ld_wxserver')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_ld_wxserver` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'ld_wxserver_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `ld_wxserver_url` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_default_rate')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_default_rate` tinyint(1) NOT NULL DEFAULT '1' COMMENT '默认1,自定义2';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'getcash_price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `getcash_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最低提现金额';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'fee_rate')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `fee_rate` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '提现费率';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'fee_min')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `fee_min` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现手续费最低金额';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'fee_max')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `fee_max` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '提现手续费最高金额';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_tea_money')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_tea_money` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'tea_money')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `tea_money` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'tea_tip')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `tea_tip` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_delivery_nowtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_delivery_nowtime` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_jueqi_ymf')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_jueqi_ymf` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'jueqi_host')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `jueqi_host` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'jueqi_customerId')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `jueqi_customerId` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_order_tip')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_order_tip` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'default_user_count')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `default_user_count` int(10) unsigned NOT NULL DEFAULT '5' COMMENT '默认的用餐人数';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_delivery_distance')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_delivery_distance` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否支持配送距离';");
}
if(!pdo_fieldexists('weisrc_dish_stores',  'is_fengniao')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_stores')." ADD `is_fengniao` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'tablezonesid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `tablezonesid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'user_count')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `user_count` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `url` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables',  'print_label')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables')." ADD `print_label` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_tables_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_tables_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables_order',  'tablesid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables_order')." ADD `tablesid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables_order',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables_order')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables_order')." ADD `from_user` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tables_order',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tables_order')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'limit_price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `limit_price` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'reservation_price')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `reservation_price` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'table_count')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `table_count` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tablezones',  'service_rate')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tablezones')." ADD `service_rate` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('weisrc_dish_template',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_template')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_template',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_template')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_template',  'template_name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_template')." ADD `template_name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tpl_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tpl_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_tpl_log',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tpl_log')." ADD `weid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tpl_log',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tpl_log')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tpl_log',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tpl_log')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tpl_log',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tpl_log')." ADD `ordersn` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tpl_log',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tpl_log')." ADD `from_user` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tpl_log',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tpl_log')." ADD `content` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tpl_log',  'result')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tpl_log')." ADD `result` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_tpl_log',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_tpl_log')." ADD `dateline` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_type')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_type',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_type')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_type',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_type')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_type',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_type')." ADD `parentid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_type',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_type')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_type',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_type')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_type',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_type')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_type',  'url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_type')." ADD `url` varchar(200) NOT NULL DEFAULT '' COMMENT '链接';");
}
if(!pdo_fieldexists('weisrc_dish_type',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_type')." ADD `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '图片';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `from_user` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `realname` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `mobile` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `address` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'doorplate')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `doorplate` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `gender` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'isdefault')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `isdefault` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('weisrc_dish_useraddress',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_dish_useraddress')." ADD `dateline` int(10) NOT NULL DEFAULT '0';");
}

?>