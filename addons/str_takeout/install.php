<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_str_account` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_address` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`realname` varchar(15) NOT NULL,
`mobile` varchar(15) NOT NULL,
`is_verify` tinyint(3) unsigned NOT NULL,
`address` varchar(50) NOT NULL,
`is_default` tinyint(3) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_area` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`title` varchar(30) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_assign_board` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`queue_id` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`mobile` varchar(15) NOT NULL,
`openid` varchar(64) NOT NULL,
`guest_num` tinyint(3) unsigned NOT NULL,
`number` varchar(20) NOT NULL,
`position` int(10) unsigned NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`is_notify` tinyint(3) unsigned NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_assign_queue` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`title` varchar(20) NOT NULL,
`guest_num` tinyint(3) unsigned NOT NULL,
`notify_num` tinyint(3) unsigned NOT NULL,
`starttime` varchar(10) NOT NULL,
`endtime` varchar(10) NOT NULL,
`prefix` varchar(10) NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`position` int(10) unsigned NOT NULL,
`updatetime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_clerk` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`title` varchar(15) NOT NULL,
`nickname` varchar(15) NOT NULL,
`openid` varchar(60) NOT NULL,
`addtime` int(10) unsigned NOT NULL,
`is_sys` tinyint(3) unsigned NOT NULL,
`email` varchar(50) NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_config` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`version` tinyint(3) unsigned NOT NULL,
`default_sid` int(10) unsigned NOT NULL,
`sms` varchar(255) NOT NULL,
`notice` varchar(500) NOT NULL,
`area_search` tinyint(3) unsigned NOT NULL,
`num_limit` int(10) unsigned NOT NULL,
`paytime_limit` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_dish` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`cid` int(10) unsigned NOT NULL,
`title` varchar(30) NOT NULL,
`price` varchar(200) NOT NULL,
`unitname` varchar(10) NOT NULL,
`total` int(10) NOT NULL,
`sailed` int(10) unsigned NOT NULL,
`grant_credit` int(10) unsigned NOT NULL,
`is_display` tinyint(3) unsigned NOT NULL,
`thumb` varchar(60) NOT NULL,
`recommend` tinyint(3) unsigned NOT NULL,
`label` varchar(5) NOT NULL,
`show_group_price` tinyint(3) unsigned NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`description` varchar(500) NOT NULL,
`first_order_limit` tinyint(3) unsigned NOT NULL,
`buy_limit` tinyint(3) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_dish_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`title` varchar(20) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
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
`uniacid` int(10) unsigned NOT NULL,
`acid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`groupid` int(10) unsigned NOT NULL,
`order_type` tinyint(3) unsigned NOT NULL,
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
`addtime` int(10) unsigned NOT NULL,
`status` tinyint(3) NOT NULL,
`is_notice` tinyint(3) NOT NULL,
`comment` tinyint(3) unsigned NOT NULL,
`print_nums` tinyint(3) unsigned NOT NULL,
`person_num` tinyint(3) unsigned NOT NULL,
`table_id` int(10) unsigned NOT NULL,
`table_info` varchar(20) NOT NULL,
`delivery_fee` decimal(10,2) unsigned NOT NULL,
`grant_credit` int(10) unsigned NOT NULL,
`is_grant` tinyint(3) unsigned NOT NULL,
`is_back` tinyint(3) unsigned NOT NULL,
`is_usecard` tinyint(3) unsigned NOT NULL,
`card_type` tinyint(3) unsigned NOT NULL,
`card_fee` varchar(20) NOT NULL,
`card_id` varchar(50) NOT NULL,
`reserve_time` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid_sid` (`uniacid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_order_cart` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`groupid` int(10) unsigned NOT NULL,
`num` int(10) unsigned NOT NULL,
`price` decimal(10,2) unsigned NOT NULL,
`grant_credit` int(10) unsigned NOT NULL,
`data` varchar(3000) NOT NULL,
`addtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid_sid` (`uniacid`,`sid`),
KEY `uid` (`uniacid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_order_comment` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`oid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`taste` tinyint(3) unsigned NOT NULL,
`serve` tinyint(3) unsigned NOT NULL,
`speed` tinyint(3) unsigned NOT NULL,
`note` varchar(255) NOT NULL,
`addtime` int(10) NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`),
KEY `oid` (`oid`),
KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_order_log` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`oid` int(10) unsigned NOT NULL,
`note` varchar(255) NOT NULL,
`addtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`),
KEY `oid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_order_print` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`pid` tinyint(3) unsigned NOT NULL,
`oid` int(10) unsigned NOT NULL,
`foid` varchar(50) NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`print_type` tinyint(3) unsigned NOT NULL,
`addtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `addtime` (`addtime`),
KEY `foid` (`foid`),
KEY `uniacid` (`uniacid`),
KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_print` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`name` varchar(20) NOT NULL,
`type` tinyint(3) unsigned NOT NULL,
`print_no` varchar(30) NOT NULL,
`key` varchar(30) NOT NULL,
`print_nums` tinyint(3) unsigned NOT NULL,
`qrcode_link` varchar(100) NOT NULL,
`print_header` varchar(50) NOT NULL,
`print_footer` varchar(50) NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`print_type` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_reply` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`rid` int(10) unsigned NOT NULL,
`type` tinyint(3) unsigned NOT NULL,
`table_id` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_reserve` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`time` varchar(15) NOT NULL,
`table_cid` int(10) unsigned NOT NULL,
`addtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_stat` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`oid` int(10) unsigned NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`dish_id` int(10) unsigned NOT NULL,
`dish_num` int(10) unsigned NOT NULL,
`dish_title` varchar(30) NOT NULL,
`dish_price` decimal(10,2) unsigned NOT NULL,
`addtime` int(10) NOT NULL,
`is_complete` tinyint(3) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`),
KEY `addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_store` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`title` varchar(30) NOT NULL,
`logo` varchar(100) NOT NULL,
`telephone` varchar(15) NOT NULL,
`business_hours` varchar(200) NOT NULL,
`description` varchar(1000) NOT NULL,
`send_price` smallint(5) unsigned NOT NULL,
`delivery_price` smallint(5) unsigned NOT NULL,
`delivery_time` tinyint(3) unsigned NOT NULL,
`serve_radius` tinyint(3) unsigned NOT NULL,
`delivery_area` varchar(50) NOT NULL,
`thumbs` varchar(1000) NOT NULL,
`district` varchar(40) NOT NULL,
`address` varchar(50) NOT NULL,
`location_x` varchar(15) NOT NULL,
`location_y` varchar(15) NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`notice_acid` int(10) unsigned NOT NULL,
`groupid` int(10) unsigned NOT NULL,
`dish_style` tinyint(3) unsigned NOT NULL,
`print_type` tinyint(3) unsigned NOT NULL,
`is_meal` tinyint(3) unsigned,
`is_takeout` tinyint(3) unsigned NOT NULL,
`is_assign` tinyint(3) unsigned NOT NULL,
`is_reserve` tinyint(3) unsigned NOT NULL,
`is_fast` tinyint(3) unsigned NOT NULL,
`mobile_verify` varchar(255) NOT NULL,
`sns` varchar(255) NOT NULL,
`forward_mode` tinyint(3) unsigned NOT NULL,
`comment_status` tinyint(3) unsigned NOT NULL,
`comment_set` tinyint(3) unsigned NOT NULL,
`notice` varchar(100) NOT NULL,
`content` varchar(255) NOT NULL,
`area_id` int(10) unsigned NOT NULL,
`copyright` varchar(255) NOT NULL,
`sms` varchar(255) NOT NULL,
`assign_mode` tinyint(3) unsigned NOT NULL,
`store_qrcode` varchar(500) NOT NULL,
`assign_qrcode` varchar(500) NOT NULL,
`table_qrcode_mode` tinyint(3) unsigned NOT NULL,
`slide_status` tinyint(3) unsigned NOT NULL,
`mobile` varchar(11) NOT NULL,
`is_sms` int(10) NOT NULL,
`sms_id` varchar(20) NOT NULL,
`email` varchar(50) NOT NULL,
`code` varchar(50) NOT NULL,
`secret` varchar(50) NOT NULL,
`email_notice` int(3) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `title` (`title`),
KEY `area_id` (`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_tables` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`title` varchar(20) NOT NULL,
`cid` int(10) unsigned NOT NULL,
`guest_num` tinyint(3) unsigned,
`scan_num` int(10) unsigned NOT NULL,
`qrcode` varchar(500) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid_sid` (`uniacid`,`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_tables_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`title` varchar(20) NOT NULL,
`limit_price` varchar(20) NOT NULL,
`reservation_price` varchar(20) NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_tables_scan` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`table_id` int(10) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
`nickname` varchar(50) NOT NULL,
`avatar` varchar(255) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`),
KEY `table_id` (`table_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_user_trash` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`username` varchar(15) NOT NULL,
`mobile` varchar(15) NOT NULL,
`addtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`),
KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_str_users` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`sid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`realname` varchar(20) NOT NULL,
`nickname` varchar(20) NOT NULL,
`mobile` varchar(15) NOT NULL,
`avatar` varchar(255) NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
