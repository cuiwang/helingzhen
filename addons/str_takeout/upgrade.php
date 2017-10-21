<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_str_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `realname` varchar(15) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `is_verify` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `address` varchar(50) NOT NULL,
  `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_assign_board` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `queue_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(15) NOT NULL,
  `openid` varchar(64) NOT NULL,
  `guest_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number` varchar(20) NOT NULL,
  `position` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_notify` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_assign_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL,
  `guest_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `notify_num` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `starttime` varchar(10) NOT NULL,
  `endtime` varchar(10) NOT NULL,
  `prefix` varchar(10) NOT NULL COMMENT '前缀',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `position` int(10) unsigned NOT NULL DEFAULT '1',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根据这个时间,判断是否将position重新至0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_clerk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(15) NOT NULL,
  `nickname` varchar(15) NOT NULL,
  `openid` varchar(60) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `is_sys` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `version` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `default_sid` int(10) unsigned NOT NULL DEFAULT '0',
  `sms` varchar(255) NOT NULL,
  `notice` varchar(500) NOT NULL,
  `area_search` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `num_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店数量限制',
  `paytime_limit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_dish` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `price` varchar(200) NOT NULL,
  `unitname` varchar(10) NOT NULL DEFAULT '份',
  `total` int(10) NOT NULL DEFAULT '0',
  `sailed` int(10) unsigned NOT NULL,
  `grant_credit` int(10) unsigned NOT NULL DEFAULT '0',
  `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `thumb` varchar(60) NOT NULL,
  `recommend` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `label` varchar(5) NOT NULL,
  `show_group_price` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(500) NOT NULL,
  `first_order_limit` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `buy_limit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_dish_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_dish_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `oid` int(10) unsigned NOT NULL,
  `did` int(10) unsigned NOT NULL,
  `score` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `oid` (`oid`),
  KEY `did` (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `acid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `order_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `openid` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `address` varchar(100) NOT NULL,
  `note` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `num` tinyint(3) unsigned NOT NULL,
  `delivery_time` varchar(15) NOT NULL,
  `pay_type` varchar(15) NOT NULL,
  `dish` varchar(3000) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `is_notice` tinyint(3) NOT NULL DEFAULT '0',
  `comment` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `print_nums` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `person_num` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `table_id` int(10) unsigned NOT NULL DEFAULT '0',
  `table_info` varchar(20) NOT NULL,
  `delivery_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '外卖配送费',
  `grant_credit` int(10) unsigned NOT NULL DEFAULT '0',
  `is_grant` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_back` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_usecard` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `card_type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `card_fee` varchar(20) NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `reserve_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid_sid` (`uniacid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_order_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `grant_credit` int(10) unsigned NOT NULL DEFAULT '0',
  `data` varchar(3000) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid_sid` (`uniacid`,`sid`),
  KEY `uid` (`uniacid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_order_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `taste` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `serve` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `speed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL,
  `addtime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`),
  KEY `oid` (`oid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_order_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `oid` int(10) unsigned NOT NULL DEFAULT '0',
  `note` varchar(255) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`),
  KEY `oid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_order_print` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `oid` int(10) unsigned NOT NULL DEFAULT '0',
  `foid` varchar(50) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `print_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '打印机品牌1：飞蛾，2：宏信',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `addtime` (`addtime`),
  KEY `foid` (`foid`),
  KEY `uniacid` (`uniacid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_print` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `print_no` varchar(30) NOT NULL,
  `key` varchar(30) NOT NULL,
  `print_nums` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `qrcode_link` varchar(100) NOT NULL,
  `print_header` varchar(50) NOT NULL,
  `print_footer` varchar(50) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `print_type` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `table_id` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_reserve` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `time` varchar(15) NOT NULL,
  `table_cid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `oid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `dish_id` int(10) unsigned NOT NULL DEFAULT '0',
  `dish_num` int(10) unsigned NOT NULL DEFAULT '0',
  `dish_title` varchar(30) NOT NULL,
  `dish_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `is_complete` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(30) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `business_hours` varchar(200) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `send_price` smallint(5) unsigned NOT NULL DEFAULT '0',
  `delivery_price` smallint(5) unsigned NOT NULL DEFAULT '0',
  `delivery_time` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `serve_radius` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `delivery_area` varchar(50) NOT NULL,
  `thumbs` varchar(1000) NOT NULL,
  `district` varchar(40) NOT NULL,
  `address` varchar(50) NOT NULL,
  `location_x` varchar(15) NOT NULL,
  `location_y` varchar(15) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `notice_acid` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `dish_style` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `print_type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_meal` tinyint(3) unsigned DEFAULT '1',
  `is_takeout` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `is_assign` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `is_reserve` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `is_fast` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `mobile_verify` varchar(255) NOT NULL,
  `sns` varchar(255) NOT NULL,
  `forward_mode` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `comment_status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `comment_set` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `notice` varchar(100) NOT NULL COMMENT '公告',
  `content` varchar(255) NOT NULL,
  `area_id` int(10) unsigned NOT NULL DEFAULT '0',
  `copyright` varchar(255) NOT NULL,
  `sms` varchar(255) NOT NULL,
  `assign_mode` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `store_qrcode` varchar(500) NOT NULL,
  `assign_qrcode` varchar(500) NOT NULL,
  `table_qrcode_mode` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `slide_status` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `mobile` varchar(11) NOT NULL COMMENT '接收短信的手机',
  `is_sms` int(10) NOT NULL COMMENT '是否开启短信',
  `sms_id` varchar(20) NOT NULL COMMENT '短信模板ID',
  `email` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `secret` varchar(50) NOT NULL,
  `email_notice` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `title` (`title`),
  KEY `area_id` (`area_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL,
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `guest_num` tinyint(3) unsigned DEFAULT '0',
  `scan_num` int(10) unsigned NOT NULL DEFAULT '0',
  `qrcode` varchar(500) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid_sid` (`uniacid`,`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_tables_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(20) NOT NULL,
  `limit_price` varchar(20) NOT NULL,
  `reservation_price` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_tables_scan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `table_id` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`),
  KEY `table_id` (`table_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_user_trash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(15) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_str_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `realname` varchar(20) CHARACTER SET latin1 NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('str_account',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_account')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_account',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_account')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_account',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_account')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_account',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_account')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_address',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_address',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_address',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD `realname` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_address',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD `mobile` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_address',  'is_verify')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD `is_verify` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_address',  'address')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD `address` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_address',  'is_default')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD `is_default` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_address',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_address',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_address')." ADD KEY `uid` (`uid`);");
}
if(!pdo_fieldexists('str_area',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_area')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_area',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_area')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_area',  'title')) {
	pdo_query("ALTER TABLE ".tablename('str_area')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('str_area',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('str_area')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_area',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_area')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('str_assign_board',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_assign_board',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_board',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_board',  'queue_id')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `queue_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_board',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_board',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `mobile` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_assign_board',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `openid` varchar(64) NOT NULL;");
}
if(!pdo_fieldexists('str_assign_board',  'guest_num')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `guest_num` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_board',  'number')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `number` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_assign_board',  'position')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `position` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_board',  'status')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_assign_board',  'is_notify')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `is_notify` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_board',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_assign_board',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_assign_board',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_board')." ADD KEY `sid` (`sid`);");
}
if(!pdo_fieldexists('str_assign_queue',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_assign_queue',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_queue',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_queue',  'title')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `title` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_assign_queue',  'guest_num')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `guest_num` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_queue',  'notify_num')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `notify_num` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_assign_queue',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `starttime` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('str_assign_queue',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `endtime` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('str_assign_queue',  'prefix')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `prefix` varchar(10) NOT NULL COMMENT '前缀';");
}
if(!pdo_fieldexists('str_assign_queue',  'status')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_assign_queue',  'position')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `position` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_assign_queue',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '根据这个时间,判断是否将position重新至0';");
}
if(!pdo_indexexists('str_assign_queue',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_assign_queue',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_assign_queue')." ADD KEY `sid` (`sid`);");
}
if(!pdo_fieldexists('str_clerk',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_clerk',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_clerk',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_clerk',  'title')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD `title` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_clerk',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD `nickname` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_clerk',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD `openid` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('str_clerk',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_clerk',  'is_sys')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD `is_sys` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_clerk',  'email')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD `email` varchar(50) NOT NULL;");
}
if(!pdo_indexexists('str_clerk',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_clerk',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_clerk')." ADD KEY `sid` (`sid`);");
}
if(!pdo_fieldexists('str_config',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_config',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_config',  'version')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD `version` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_config',  'default_sid')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD `default_sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_config',  'sms')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD `sms` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('str_config',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD `notice` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('str_config',  'area_search')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD `area_search` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_config',  'num_limit')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD `num_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店数量限制';");
}
if(!pdo_fieldexists('str_config',  'paytime_limit')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD `paytime_limit` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('str_config',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_config')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('str_dish',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_dish',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_dish',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_dish',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `cid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_dish',  'title')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('str_dish',  'price')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `price` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('str_dish',  'unitname')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `unitname` varchar(10) NOT NULL DEFAULT '份';");
}
if(!pdo_fieldexists('str_dish',  'total')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `total` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_dish',  'sailed')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `sailed` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('str_dish',  'grant_credit')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `grant_credit` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_dish',  'is_display')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_dish',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `thumb` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('str_dish',  'recommend')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `recommend` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('str_dish',  'label')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `label` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists('str_dish',  'show_group_price')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `show_group_price` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_dish',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_dish',  'description')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `description` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('str_dish',  'first_order_limit')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `first_order_limit` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('str_dish',  'buy_limit')) {
	pdo_query("ALTER TABLE ".tablename('str_dish')." ADD `buy_limit` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_dish_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_dish_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_category')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_dish_category',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_category')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_dish_category',  'title')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_category')." ADD `title` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_dish_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_dish_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_category')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_dish_category',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_category')." ADD KEY `sid` (`sid`);");
}
if(!pdo_fieldexists('str_dish_comment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_comment')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_dish_comment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_comment')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('str_dish_comment',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_comment')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('str_dish_comment',  'oid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_comment')." ADD `oid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('str_dish_comment',  'did')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_comment')." ADD `did` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('str_dish_comment',  'score')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_comment')." ADD `score` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_indexexists('str_dish_comment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_comment')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_dish_comment',  'oid')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_comment')." ADD KEY `oid` (`oid`);");
}
if(!pdo_indexexists('str_dish_comment',  'did')) {
	pdo_query("ALTER TABLE ".tablename('str_dish_comment')." ADD KEY `did` (`did`);");
}
if(!pdo_fieldexists('str_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `acid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `groupid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'order_type')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `order_type` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'username')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `username` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `mobile` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `address` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'note')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `note` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `price` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'num')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `num` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'delivery_time')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `delivery_time` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'pay_type')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `pay_type` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'dish')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `dish` varchar(3000) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `status` tinyint(3) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_order',  'is_notice')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `is_notice` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `comment` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('str_order',  'print_nums')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `print_nums` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'person_num')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `person_num` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_order',  'table_id')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `table_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'table_info')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `table_info` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'delivery_fee')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `delivery_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '外卖配送费';");
}
if(!pdo_fieldexists('str_order',  'grant_credit')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `grant_credit` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'is_grant')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `is_grant` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'is_back')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `is_back` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'is_usecard')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `is_usecard` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'card_type')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `card_type` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order',  'card_fee')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `card_fee` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `card_id` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_order',  'reserve_time')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD `reserve_time` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_order',  'uniacid_sid')) {
	pdo_query("ALTER TABLE ".tablename('str_order')." ADD KEY `uniacid_sid` (`uniacid`,`sid`);");
}
if(!pdo_fieldexists('str_order_cart',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_order_cart',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_cart',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_cart',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_cart',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `groupid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_cart',  'num')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `num` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_cart',  'price')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('str_order_cart',  'grant_credit')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `grant_credit` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_cart',  'data')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `data` varchar(3000) NOT NULL;");
}
if(!pdo_fieldexists('str_order_cart',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_order_cart',  'uniacid_sid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD KEY `uniacid_sid` (`uniacid`,`sid`);");
}
if(!pdo_indexexists('str_order_cart',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_cart')." ADD KEY `uid` (`uniacid`,`uid`);");
}
if(!pdo_fieldexists('str_order_comment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_order_comment',  'oid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `oid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_comment',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_comment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_comment',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_comment',  'taste')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `taste` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_comment',  'serve')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `serve` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_comment',  'speed')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `speed` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_comment',  'note')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `note` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('str_order_comment',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_comment',  'status')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_indexexists('str_order_comment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_order_comment',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD KEY `sid` (`sid`);");
}
if(!pdo_indexexists('str_order_comment',  'oid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD KEY `oid` (`oid`);");
}
if(!pdo_indexexists('str_order_comment',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_order_comment')." ADD KEY `addtime` (`addtime`);");
}
if(!pdo_fieldexists('str_order_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_order_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_order_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_log')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_log',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_log')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_log',  'oid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_log')." ADD `oid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_log',  'note')) {
	pdo_query("ALTER TABLE ".tablename('str_order_log')." ADD `note` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('str_order_log',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_order_log')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_order_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_log')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_order_log',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_log')." ADD KEY `sid` (`sid`);");
}
if(!pdo_indexexists('str_order_log',  'oid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_log')." ADD KEY `oid` (`oid`);");
}
if(!pdo_fieldexists('str_order_print',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_order_print',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_print',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_print',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD `pid` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_print',  'oid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD `oid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_order_print',  'foid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD `foid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_order_print',  'status')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('str_order_print',  'print_type')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD `print_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '打印机品牌1：飞蛾，2：宏信';");
}
if(!pdo_fieldexists('str_order_print',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD `addtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('str_order_print',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD KEY `addtime` (`addtime`);");
}
if(!pdo_indexexists('str_order_print',  'foid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD KEY `foid` (`foid`);");
}
if(!pdo_indexexists('str_order_print',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_order_print',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('str_order_print')." ADD KEY `pid` (`pid`);");
}
if(!pdo_fieldexists('str_print',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_print',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_print',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `sid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('str_print',  'name')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `name` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_print',  'type')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `type` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_print',  'print_no')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `print_no` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('str_print',  'key')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `key` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('str_print',  'print_nums')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `print_nums` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_print',  'qrcode_link')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `qrcode_link` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('str_print',  'print_header')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `print_header` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_print',  'print_footer')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `print_footer` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_print',  'status')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_print',  'print_type')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD `print_type` int(10) NOT NULL;");
}
if(!pdo_indexexists('str_print',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_print',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_print')." ADD KEY `sid` (`sid`);");
}
if(!pdo_fieldexists('str_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_reply')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_reply',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_reply')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('str_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_reply',  'type')) {
	pdo_query("ALTER TABLE ".tablename('str_reply')." ADD `type` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_reply',  'table_id')) {
	pdo_query("ALTER TABLE ".tablename('str_reply')." ADD `table_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_reply')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('str_reserve',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_reserve')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_reserve',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_reserve')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_reserve',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_reserve')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_reserve',  'time')) {
	pdo_query("ALTER TABLE ".tablename('str_reserve')." ADD `time` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_reserve',  'table_cid')) {
	pdo_query("ALTER TABLE ".tablename('str_reserve')." ADD `table_cid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_reserve',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_reserve')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_reserve',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_reserve')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_reserve',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_reserve')." ADD KEY `sid` (`sid`);");
}
if(!pdo_fieldexists('str_stat',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_stat',  'oid')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `oid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_stat',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_stat',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_stat',  'dish_id')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `dish_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_stat',  'dish_num')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `dish_num` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_stat',  'dish_title')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `dish_title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('str_stat',  'dish_price')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `dish_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('str_stat',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_stat',  'is_complete')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD `is_complete` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_stat',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_stat',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD KEY `sid` (`sid`);");
}
if(!pdo_indexexists('str_stat',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_stat')." ADD KEY `addtime` (`addtime`);");
}
if(!pdo_fieldexists('str_store',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_store',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'title')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `logo` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `telephone` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'business_hours')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `business_hours` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'description')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'send_price')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `send_price` smallint(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'delivery_price')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `delivery_price` smallint(5) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'delivery_time')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `delivery_time` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'serve_radius')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `serve_radius` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'delivery_area')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `delivery_area` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `thumbs` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'district')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `district` varchar(40) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'address')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `address` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'location_x')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `location_x` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'location_y')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `location_y` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'status')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_store',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'notice_acid')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `notice_acid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `groupid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'dish_style')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `dish_style` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_store',  'print_type')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `print_type` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_store',  'is_meal')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `is_meal` tinyint(3) unsigned DEFAULT '1';");
}
if(!pdo_fieldexists('str_store',  'is_takeout')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `is_takeout` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_store',  'is_assign')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `is_assign` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('str_store',  'is_reserve')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `is_reserve` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('str_store',  'is_fast')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `is_fast` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('str_store',  'mobile_verify')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `mobile_verify` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'sns')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `sns` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'forward_mode')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `forward_mode` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'comment_status')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `comment_status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_store',  'comment_set')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `comment_set` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_store',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `notice` varchar(100) NOT NULL COMMENT '公告';");
}
if(!pdo_fieldexists('str_store',  'content')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `content` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'area_id')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `area_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_store',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `copyright` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'sms')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `sms` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'assign_mode')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `assign_mode` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_store',  'store_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `store_qrcode` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'assign_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `assign_qrcode` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'table_qrcode_mode')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `table_qrcode_mode` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_store',  'slide_status')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `slide_status` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('str_store',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `mobile` varchar(11) NOT NULL COMMENT '接收短信的手机';");
}
if(!pdo_fieldexists('str_store',  'is_sms')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `is_sms` int(10) NOT NULL COMMENT '是否开启短信';");
}
if(!pdo_fieldexists('str_store',  'sms_id')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `sms_id` varchar(20) NOT NULL COMMENT '短信模板ID';");
}
if(!pdo_fieldexists('str_store',  'email')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `email` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'code')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `code` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `secret` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_store',  'email_notice')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD `email_notice` int(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_store',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_store',  'title')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD KEY `title` (`title`);");
}
if(!pdo_indexexists('str_store',  'area_id')) {
	pdo_query("ALTER TABLE ".tablename('str_store')." ADD KEY `area_id` (`area_id`);");
}
if(!pdo_fieldexists('str_tables',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_tables',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables',  'title')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `title` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_tables',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `cid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables',  'guest_num')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `guest_num` tinyint(3) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables',  'scan_num')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `scan_num` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `qrcode` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('str_tables',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables',  'status')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('str_tables',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_tables',  'uniacid_sid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables')." ADD KEY `uniacid_sid` (`uniacid`,`sid`);");
}
if(!pdo_fieldexists('str_tables_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_tables_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_category')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables_category',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_category')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables_category',  'title')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_category')." ADD `title` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_tables_category',  'limit_price')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_category')." ADD `limit_price` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_tables_category',  'reservation_price')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_category')." ADD `reservation_price` varchar(20) NOT NULL;");
}
if(!pdo_indexexists('str_tables_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_category')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_tables_category',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_category')." ADD KEY `sid` (`sid`);");
}
if(!pdo_fieldexists('str_tables_scan',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_tables_scan',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables_scan',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables_scan',  'table_id')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD `table_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_tables_scan',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_tables_scan',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('str_tables_scan',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('str_tables_scan',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_tables_scan',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_tables_scan',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD KEY `sid` (`sid`);");
}
if(!pdo_indexexists('str_tables_scan',  'table_id')) {
	pdo_query("ALTER TABLE ".tablename('str_tables_scan')." ADD KEY `table_id` (`table_id`);");
}
if(!pdo_fieldexists('str_user_trash',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_user_trash',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_user_trash',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_user_trash',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_user_trash',  'username')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD `username` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_user_trash',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD `mobile` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_user_trash',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('str_user_trash',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_user_trash',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD KEY `sid` (`sid`);");
}
if(!pdo_indexexists('str_user_trash',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_user_trash')." ADD KEY `uid` (`uid`);");
}
if(!pdo_fieldexists('str_users',  'id')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('str_users',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_users',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_users',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('str_users',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD `realname` varchar(20) CHARACTER SET latin1 NOT NULL;");
}
if(!pdo_fieldexists('str_users',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD `nickname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('str_users',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD `mobile` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('str_users',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_indexexists('str_users',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('str_users',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('str_users')." ADD KEY `sid` (`sid`);");
}

?>