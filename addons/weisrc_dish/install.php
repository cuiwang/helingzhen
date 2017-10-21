<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_account` (
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
`from_user` varchar(100),
`status` tinyint(1) unsigned NOT NULL,
`dateline` int(10),
PRIMARY KEY (`id`),
KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_businesslog` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`storeid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`price` varchar(20),
`haveprice` varchar(20),
`totalprice` varchar(20),
`status` tinyint(1) unsigned NOT NULL,
`handletime` int(10),
`dateline` int(10),
`dining_mode` tinyint(1) unsigned NOT NULL,
`charges` varchar(20),
`successprice` varchar(20),
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
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
`weid` int(10) unsigned,
`storeid` int(10) unsigned NOT NULL,
`orderid` int(10) unsigned NOT NULL,
`agentid` int(10) unsigned NOT NULL,
`ordersn` varchar(100),
`from_user` varchar(100),
`price` decimal(10,2),
`status` tinyint(1) NOT NULL,
`dateline` int(10),
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
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_email_setting` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`storeid` int(10) unsigned NOT NULL,
`email_enable` tinyint(1) unsigned NOT NULL,
`email_host` varchar(50),
`email_send` varchar(100),
`email_pwd` varchar(20),
`email_user` varchar(100),
`email` varchar(100),
`email_business_tpl` varchar(200),
`dateline` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_fans` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`from_user` varchar(50),
`nickname` varchar(50),
`headimgurl` varchar(500),
`username` varchar(50),
`mobile` varchar(50),
`address` varchar(200),
`sex` tinyint(1) unsigned NOT NULL,
`lat` decimal(18,10) NOT NULL,
`lng` decimal(18,10) NOT NULL,
`status` tinyint(1),
`dateline` int(10),
`storeid` int(11),
`totalcount` int(10),
`totalprice` decimal(18,2) NOT NULL,
`avgprice` decimal(18,2) NOT NULL,
`uid` int(11),
`paytime` int(10),
`lasttime` int(10),
`agentid2` int(11),
`agentid3` int(11),
`noticetime` int(10),
`agentid` int(11),
`country` varchar(50),
`province` varchar(50),
`city` varchar(50),
PRIMARY KEY (`id`),
KEY `indx_rid` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_feedback` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned,
`storeid` int(10) unsigned NOT NULL,
`orderid` int(10) unsigned NOT NULL,
`from_user` varchar(100),
`star` tinyint(1) NOT NULL,
`content` varchar(500),
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10),
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
PRIMARY KEY (`id`)
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
`begintime` varchar(20),
`endtime` varchar(20),
`status` tinyint(1) unsigned NOT NULL,
`dateline` int(10),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
`totalnum` tinyint(4),
`totalprice` varchar(10) NOT NULL,
`status` tinyint(4) NOT NULL,
`paytype` tinyint(1) unsigned NOT NULL,
`username` varchar(50) NOT NULL,
`address` varchar(250) NOT NULL,
`tel` varchar(50) NOT NULL,
`reply` varchar(1000) NOT NULL,
`meal_time` varchar(50) NOT NULL,
`counts` tinyint(4),
`seat_type` tinyint(1),
`carports` tinyint(3),
`dining_mode` tinyint(1) unsigned NOT NULL,
`remark` varchar(1000) NOT NULL,
`tables` varchar(10) NOT NULL,
`print_sta` tinyint(1),
`sign` tinyint(1) NOT NULL,
`isfinish` tinyint(1) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
`transid` varchar(30) NOT NULL,
`goodsprice` decimal(10,2),
`dispatchprice` decimal(10,2),
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
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_print_label` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`storeid` int(10) unsigned NOT NULL,
`weid` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_print_order` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`orderid` int(10) unsigned NOT NULL,
`print_usr` varchar(50),
`print_status` tinyint(1),
`dateline` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_print_setting` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`storeid` int(10) unsigned NOT NULL,
`title` varchar(200),
`print_status` tinyint(1) NOT NULL,
`print_type` tinyint(1) NOT NULL,
`print_usr` varchar(50),
`print_nums` tinyint(3),
`print_top` varchar(40),
`print_bottom` varchar(40),
`dateline` int(10),
`qrcode_status` tinyint(1) NOT NULL,
`qrcode_url` varchar(200),
`type` varchar(50),
`member_code` varchar(100),
`feyin_key` varchar(100),
`is_print_all` tinyint(1) NOT NULL,
`print_goodstype` varchar(500),
`printid` int(10) NOT NULL,
`yilian_type` tinyint(1),
`api_key` varchar(100),
`print_label` varchar(500),
`is_meal` tinyint(1) NOT NULL,
`is_delivery` tinyint(1) NOT NULL,
`is_snack` tinyint(1) NOT NULL,
`is_reservation` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_savewine_log` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`storeid` int(10) unsigned NOT NULL,
`from_user` varchar(100),
`savenumber` varchar(100) NOT NULL,
`title` varchar(200),
`username` varchar(100),
`tel` varchar(30),
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
`from_user` varchar(100),
`content` varchar(1000),
`status` tinyint(1) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_setting` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(50),
`thumb` varchar(200),
`storeid` int(10) unsigned NOT NULL,
`entrance_type` tinyint(1) unsigned NOT NULL,
`entrance_storeid` tinyint(1) unsigned NOT NULL,
`order_enable` tinyint(1) unsigned NOT NULL,
`dining_mode` tinyint(1) unsigned NOT NULL,
`dateline` int(10),
`istplnotice` tinyint(1) NOT NULL,
`tplneworder` varchar(200),
`tpluser` text,
`searchword` varchar(1000),
`mode` tinyint(1) NOT NULL,
`tplnewqueue` varchar(200),
`is_notice` tinyint(1) unsigned NOT NULL,
`sms_enable` tinyint(1) unsigned NOT NULL,
`sms_username` varchar(20),
`sms_pwd` varchar(20),
`sms_mobile` varchar(20),
`email_enable` tinyint(1) unsigned NOT NULL,
`email_host` varchar(50),
`email_send` varchar(100),
`email_pwd` varchar(20),
`email_user` varchar(100),
`email` varchar(100),
`tpltype` tinyint(1) NOT NULL,
`sms_id` tinyint(11) unsigned NOT NULL,
`is_sms` int(10) NOT NULL,
`tploperator` varchar(200),
`commission_level` tinyint(1) unsigned NOT NULL,
`share_title` varchar(200),
`share_desc` varchar(300),
`share_image` varchar(500),
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
`follow_url` varchar(500),
`isneedfollow` tinyint(1),
`wechat` tinyint(1) NOT NULL,
`alipay` tinyint(1) NOT NULL,
`credit` tinyint(1) NOT NULL,
`delivery` tinyint(1) NOT NULL,
`is_speaker` tinyint(1) NOT NULL,
`link_card` varchar(500),
`link_sign` varchar(500),
`link_sign_name` varchar(100),
`link_card_name` varchar(100),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_sms_checkcode` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(100),
`mobile` varchar(30),
`checkcode` varchar(100),
`status` tinyint(1) unsigned,
`dateline` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_sms_setting` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`storeid` int(10) unsigned NOT NULL,
`sms_enable` tinyint(1) unsigned NOT NULL,
`sms_verify_enable` tinyint(1) unsigned NOT NULL,
`sms_username` varchar(20),
`sms_pwd` varchar(20),
`sms_mobile` varchar(20),
`sms_verify_tpl` varchar(120),
`sms_business_tpl` varchar(120),
`sms_user_tpl` varchar(120),
`dateline` int(10),
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
`dateline` int(10),
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
`thumb_url` varchar(1000),
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
`dispatchprice` decimal(10,2),
`is_hot` tinyint(1) NOT NULL,
`freeprice` decimal(10,2),
`begintime` varchar(20),
`announce` varchar(1000) NOT NULL,
`endtime` varchar(20),
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
`notice_space_time` int(10),
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
`begintime1` varchar(20),
`endtime1` varchar(20),
`begintime2` varchar(20),
`endtime2` varchar(20),
`area` varchar(1000) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_tables_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`tablesid` int(10) unsigned NOT NULL,
`storeid` int(10) unsigned NOT NULL,
`from_user` varchar(200) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_template` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`template_name` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_dish_tpl_log` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned,
`storeid` int(10) unsigned NOT NULL,
`orderid` int(10) unsigned NOT NULL,
`ordersn` varchar(100),
`from_user` varchar(100),
`content` varchar(1000),
`result` varchar(200),
`dateline` int(10),
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
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
