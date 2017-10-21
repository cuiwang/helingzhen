<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_address` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`openid` varchar(150) NOT NULL,
`username` varchar(20) NOT NULL,
`mobile` varchar(11) NOT NULL,
`zipcode` varchar(6) NOT NULL,
`province` varchar(32) NOT NULL,
`city` varchar(32) NOT NULL,
`district` varchar(32) NOT NULL,
`address` varchar(512) NOT NULL,
`isdefault` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_adv` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`advname` varchar(50),
`link` varchar(255),
`thumb` varchar(255),
`displayorder` int(11),
`enabled` int(11),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_enabled` (`enabled`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_aloneorder` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`openid` varchar(110) NOT NULL,
`orderno` varchar(145) NOT NULL,
`status` int(11) NOT NULL,
`goodsid` int(11) NOT NULL,
`paytype` int(11),
`num` int(11) NOT NULL,
`paytime` int(11),
`sendtime` int(11),
`taketime` int(11),
`price` decimal(11,2) NOT NULL,
`createtime` int(11),
`realname` varchar(45) NOT NULL,
`mobile` varchar(32) NOT NULL,
`address` varchar(145) NOT NULL,
`express` varchar(145),
`expressn` int(11),
`remark` text,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_cart` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(115) NOT NULL,
`period_number` varchar(145) NOT NULL,
`uniacid` int(11) NOT NULL,
`title` varchar(145) NOT NULL,
`ip` varchar(145) NOT NULL,
`ipaddress` varchar(145) NOT NULL,
`num` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_cartsetting` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`num` int(11) NOT NULL,
`is_show` int(11) NOT NULL,
`type` int(11) NOT NULL,
`allnum` varchar(225),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(255) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`isrecommand` int(10),
`description` varchar(500) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`enabled` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_comcode` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`numa` varchar(20) NOT NULL,
`numb` varchar(11) NOT NULL,
`periods` varchar(50) NOT NULL,
`pid` int(11) NOT NULL,
`wincode` int(11) NOT NULL,
`arecord` longtext NOT NULL,
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_consumerecord` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`openid` varchar(145) NOT NULL,
`num` int(11) NOT NULL,
`status` int(2) NOT NULL,
`period_number` varchar(145) NOT NULL,
`createtime` varchar(145) NOT NULL,
`ip` varchar(45) NOT NULL,
`ipaddress` varchar(145) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_coupon` (
`couponid` int(10) unsigned NOT NULL AUTO_INCREMENT,
`merchantid` int(11) NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`type` tinyint(4) NOT NULL,
`title` varchar(30) NOT NULL,
`couponsn` varchar(50) NOT NULL,
`description` text,
`discount` decimal(10,2) NOT NULL,
`condition` decimal(10,2) NOT NULL,
`starttime` int(10) unsigned NOT NULL,
`endtime` int(10) unsigned NOT NULL,
`limit` int(11) NOT NULL,
`dosage` int(11) unsigned NOT NULL,
`amount` int(11) unsigned NOT NULL,
`thumb` varchar(500) NOT NULL,
`credit` int(10) unsigned NOT NULL,
`credittype` varchar(20) NOT NULL,
`module` varchar(30) NOT NULL,
`use_module` tinyint(3) unsigned NOT NULL,
`daylimit` int(11) NOT NULL,
PRIMARY KEY (`couponid`),
KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_coupon_record` (
`recid` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`grantmodule` varchar(50) NOT NULL,
`granttime` int(10) unsigned NOT NULL,
`usemodule` varchar(50) NOT NULL,
`usetime` int(10) unsigned NOT NULL,
`status` tinyint(4) NOT NULL,
`operator` varchar(30) NOT NULL,
`remark` varchar(300) NOT NULL,
`couponid` int(10) unsigned NOT NULL,
`clerk_id` int(10) unsigned NOT NULL,
`merchantid` int(11) NOT NULL,
`firstopenid` varchar(145) NOT NULL,
`secondopenid` varchar(145) NOT NULL,
`gettime` varchar(45) NOT NULL,
`endtime` varchar(45) NOT NULL,
`couponnum` int(11) NOT NULL,
`coupon_number` varchar(145) NOT NULL,
`usedcouponnum` int(11) NOT NULL,
PRIMARY KEY (`recid`),
KEY `couponid` (`uid`,`grantmodule`,`usemodule`,`status`),
KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_discuss` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(45) NOT NULL,
`content` varchar(225) NOT NULL,
`parentid` int(11) NOT NULL,
`status` int(11) NOT NULL,
`createtime` varchar(32) NOT NULL,
`uniacid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_goods_atlas` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`g_id` int(11) NOT NULL,
`thumb` varchar(145) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_goodslist` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`merchant_id` int(11) NOT NULL,
`title` varchar(100),
`category_parentid` int(11) NOT NULL,
`category_childid` int(11) NOT NULL,
`price` int(10),
`canyurenshu` int(10) unsigned,
`periods` smallint(6) unsigned,
`maxperiods` smallint(5) unsigned,
`picarr` text,
`content` mediumtext,
`createtime` int(10) unsigned,
`pos` tinyint(4) unsigned,
`status` int(11) NOT NULL,
`isnew` int(11) NOT NULL,
`ishot` int(11) NOT NULL,
`jiexiao_time` int(11) NOT NULL,
`couponid` int(11) NOT NULL,
`init_money` int(11) NOT NULL,
`maxnum` int(11) NOT NULL,
`sort` int(11) NOT NULL,
`next_init_money` int(11) NOT NULL,
`automatic` varchar(145) NOT NULL,
`is_alert` int(2) NOT NULL,
`is_alone` int(2) NOT NULL,
`aloneprice` decimal(10,2) NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`),
KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_hexiao` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(50) NOT NULL,
`discount` decimal(10,2) NOT NULL,
`hexiaoperson` varchar(32) NOT NULL,
`usedperson` varchar(32) NOT NULL,
`createtime` int(11) NOT NULL,
`uniacid` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_in` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`data` varchar(50) NOT NULL,
`type` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
KEY `indx_displayorder` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_invite` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`beinvited_openid` varchar(145) NOT NULL,
`invite_openid` varchar(145) NOT NULL,
`createtime` varchar(145) NOT NULL,
`credit1` int(11) NOT NULL,
`type` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_login_session` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(40) NOT NULL,
`ip` varchar(32) NOT NULL,
`updatetime` varchar(32) NOT NULL,
`status` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`count` int(11) NOT NULL,
`locktime` varchar(45) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_machineset` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`period_number` varchar(145) NOT NULL,
`machine_num` int(11) NOT NULL,
`createtime` varchar(145) NOT NULL,
`start_time` varchar(145) NOT NULL,
`end_time` varchar(145) NOT NULL,
`next_time` varchar(145) NOT NULL,
`status` int(2) NOT NULL,
`max_num` int(11) NOT NULL,
`timebucket` varchar(145) NOT NULL,
`is_followed` int(2) NOT NULL,
`goodsid` int(11) NOT NULL,
`all_buy` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_member` (
`mid` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(145) NOT NULL,
`uniacid` int(11) NOT NULL,
`mobile` varchar(25) NOT NULL,
`email` varchar(145) NOT NULL,
`credit1` decimal(10,2) NOT NULL,
`credit2` decimal(10,2) NOT NULL,
`createtime` varchar(145) NOT NULL,
`nickname` varchar(145) NOT NULL,
`realname` varchar(145) NOT NULL,
`avatar` varchar(445) NOT NULL,
`gender` int(2) NOT NULL,
`vip` int(2) NOT NULL,
`address` varchar(225) NOT NULL,
`nationality` varchar(30) NOT NULL,
`resideprovince` varchar(30) NOT NULL,
`residecity` varchar(30) NOT NULL,
`residedist` varchar(30) NOT NULL,
`account` varchar(145) NOT NULL,
`password` varchar(145) NOT NULL,
`status` int(2) NOT NULL,
`type` int(2) NOT NULL,
`ip` varchar(35) NOT NULL,
`is_buy` int(11) NOT NULL,
`salt` varchar(32) NOT NULL,
`unionid` varchar(142) NOT NULL,
`appopenid` varchar(142) NOT NULL,
`userstatus` int(2) NOT NULL,
PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_merchant` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(145) NOT NULL,
`logo` varchar(225) NOT NULL,
`industry` varchar(45) NOT NULL,
`address` varchar(115) NOT NULL,
`linkman_name` varchar(145) NOT NULL,
`linkman_mobile` varchar(145) NOT NULL,
`uniacid` int(11) NOT NULL,
`createtime` varchar(115) NOT NULL,
`thumb` varchar(255) NOT NULL,
`detail` varchar(1222) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_navi` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`link` varchar(255) NOT NULL,
`thumb` varchar(255) NOT NULL,
`displayorder` int(11) NOT NULL,
`enabled` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_notice` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`title` varchar(255) NOT NULL,
`content` text,
`enabled` int(11) NOT NULL,
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_period` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goodsid` int(11) NOT NULL,
`periods` int(11) NOT NULL,
`nickname` varchar(145) NOT NULL,
`avatar` varchar(225) NOT NULL,
`openid` varchar(145) NOT NULL,
`partakes` int(11) NOT NULL,
`code` varchar(45) NOT NULL,
`endtime` varchar(145) NOT NULL,
`jiexiao_time` int(11) NOT NULL,
`confirmtime` int(11) NOT NULL,
`taketime` int(11) NOT NULL,
`realname` varchar(20) NOT NULL,
`mobile` varchar(11) NOT NULL,
`address` varchar(200) NOT NULL,
`express` varchar(45) NOT NULL,
`expressn` varchar(145) NOT NULL,
`sendtime` varchar(145) NOT NULL,
`codes` longtext NOT NULL,
`uniacid` int(11) NOT NULL,
`shengyu_codes` int(11) NOT NULL,
`zong_codes` int(11) NOT NULL,
`period_number` varchar(145) NOT NULL,
`canyurenshu` int(11) NOT NULL,
`status` int(4) NOT NULL,
`scale` int(11) NOT NULL,
`createtime` varchar(145) NOT NULL,
`recordid` int(11) NOT NULL,
`allcodes` longtext NOT NULL,
`comment` varchar(2048) NOT NULL,
`machine_time` varchar(145) NOT NULL,
`sort` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `period_number` (`period_number`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_rechargerecord` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`openid` varchar(245) NOT NULL,
`num` int(11) NOT NULL,
`createtime` varchar(145) NOT NULL,
`transid` varchar(145) NOT NULL,
`status` int(11) NOT NULL,
`paytype` int(2) NOT NULL,
`ordersn` varchar(145) NOT NULL,
`type` int(2) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_record` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`openid` varchar(50) NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`goodsid` int(10) unsigned NOT NULL,
`ordersn` varchar(20) NOT NULL,
`status` smallint(4) NOT NULL,
`transid` varchar(30) NOT NULL,
`count` int(10) unsigned NOT NULL,
`code` longtext,
`createtime` int(10) unsigned NOT NULL,
`period_number` varchar(145) NOT NULL,
PRIMARY KEY (`id`),
KEY `ordersn` (`ordersn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_saler` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`storeid` varchar(225),
`uniacid` int(11),
`openid` varchar(255),
`nickname` varchar(145) NOT NULL,
`avatar` varchar(225) NOT NULL,
`status` tinyint(3),
PRIMARY KEY (`id`),
KEY `idx_storeid` (`storeid`),
KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_setting` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`key` varchar(30) NOT NULL,
`value` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_showprize` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goodsid` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`openid` varchar(300) NOT NULL,
`title` varchar(200) NOT NULL,
`detail` varchar(1000) NOT NULL,
`period_number` varchar(45) NOT NULL,
`createtime` varchar(145) NOT NULL,
`status` int(11) NOT NULL,
`goodstitle` varchar(145) NOT NULL,
`thumbs` varchar(2048) NOT NULL,
`type` int(11) NOT NULL,
`speechcount` int(11) NOT NULL,
`count` int(11) NOT NULL,
`praise` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_smstpl` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`type` varchar(32) NOT NULL,
`smstplid` varchar(32) NOT NULL,
`data` text NOT NULL,
`status` smallint(2) NOT NULL,
`createtime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_syssetting` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`key` varchar(200) NOT NULL,
`value` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_withdraw` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`openid` varchar(225) NOT NULL,
`createtime` varchar(45) NOT NULL,
`number` int(11) NOT NULL,
`status` int(2) NOT NULL,
`type` int(2) NOT NULL,
`order_no` varchar(225) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
