<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_wz_tuan_address` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`openid` varchar(300) NOT NULL,
`cname` varchar(30) NOT NULL,
`tel` varchar(20) NOT NULL,
`province` varchar(20) NOT NULL,
`city` varchar(20) NOT NULL,
`county` varchar(20) NOT NULL,
`detailed_address` varchar(225) NOT NULL,
`uniacid` int(10) NOT NULL,
`addtime` varchar(45) NOT NULL,
`status` int(2) NOT NULL,
`type` int(2) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_admin` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`username` varchar(30) NOT NULL,
`password` varchar(20) NOT NULL,
`email` varchar(60) NOT NULL,
`tel` varchar(20) NOT NULL,
`uniacid` int(10),
`openid` varchar(100),
PRIMARY KEY (`id`),
UNIQUE KEY `username` (`username`),
UNIQUE KEY `openid` (`openid`),
UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_adv` (
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

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_arealimit` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`enabled` int(11) NOT NULL,
`arealimitname` varchar(56) NOT NULL,
`areas` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(255) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`isrecommand` int(10),
`description` varchar(500) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`enabled` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_collect` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`openid` varchar(200) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_dispatch` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`dispatchname` varchar(50),
`dispatchtype` int(11),
`displayorder` int(11),
`firstprice` decimal(10,2),
`secondprice` decimal(10,2),
`firstweight` int(11),
`secondweight` int(11),
`express` varchar(250),
`areas` text,
`carriers` text,
`enabled` int(11),
PRIMARY KEY (`id`),
KEY `idx_uniacid` (`uniacid`),
KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`gname` varchar(225) NOT NULL,
`fk_typeid` int(10) unsigned NOT NULL,
`gsn` varchar(50) NOT NULL,
`gnum` int(10) unsigned NOT NULL,
`groupnum` int(10) unsigned NOT NULL,
`mprice` decimal(10,2) NOT NULL,
`gprice` decimal(10,2) NOT NULL,
`oprice` decimal(10,2) NOT NULL,
`freight` decimal(10,2) NOT NULL,
`gdesc` longtext NOT NULL,
`gimg` varchar(225),
`isshow` tinyint(4) NOT NULL,
`salenum` int(10) unsigned NOT NULL,
`ishot` tinyint(4) NOT NULL,
`displayorder` int(11) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
`uniacid` int(10) NOT NULL,
`endtime` int(11) NOT NULL,
`yunfei_id` int(11) NOT NULL,
`is_discount` int(11) NOT NULL,
`credits` int(11) NOT NULL,
`is_hexiao` int(2) NOT NULL,
`hexiao_id` varchar(225) NOT NULL,
`is_share` int(2) NOT NULL,
`gdetaile` longtext NOT NULL,
`isnew` int(11) NOT NULL,
`isrecommand` int(11) NOT NULL,
`isdiscount` int(11) NOT NULL,
`hasoption` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_atlas` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`g_id` int(11) NOT NULL,
`thumb` varchar(145) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_imgs` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`fk_gid` int(10) NOT NULL,
`albumpath` varchar(225) NOT NULL,
`uniacid` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `fk_gid` (`fk_gid`),
UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_option` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goodsid` int(10),
`title` varchar(50),
`thumb` varchar(60),
`productprice` decimal(10,2),
`marketprice` decimal(10,2),
`costprice` decimal(10,2),
`stock` int(11),
`weight` decimal(10,2),
`displayorder` int(11),
`specs` text,
PRIMARY KEY (`id`),
KEY `indx_goodsid` (`goodsid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_param` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goodsid` int(10),
`title` varchar(50),
`value` text,
`displayorder` int(11),
PRIMARY KEY (`id`),
KEY `indx_goodsid` (`goodsid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_type` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`cname` varchar(30) NOT NULL,
`pid` int(10),
`uniacid` int(10),
PRIMARY KEY (`id`),
UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_group` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`groupnumber` varchar(115) NOT NULL,
`goodsid` int(11) NOT NULL,
`goodsname` varchar(1024) NOT NULL,
`groupstatus` int(11) NOT NULL,
`neednum` int(11) NOT NULL,
`lacknum` int(11) NOT NULL,
`starttime` varchar(225) NOT NULL,
`endtime` varchar(225) NOT NULL,
`uniacid` int(11) NOT NULL,
`grouptype` int(11) NOT NULL,
`isshare` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_member` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`openid` varchar(100) NOT NULL,
`nickname` varchar(50) NOT NULL,
`avatar` varchar(255) NOT NULL,
`tag` varchar(1000) NOT NULL,
`mobile` varchar(20) NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_order` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` varchar(45) NOT NULL,
`gnum` int(11) NOT NULL,
`openid` varchar(45) NOT NULL,
`ptime` varchar(45) NOT NULL,
`orderno` varchar(45) NOT NULL,
`price` varchar(45) NOT NULL,
`status` int(9) NOT NULL,
`addressid` int(11) NOT NULL,
`g_id` int(11) NOT NULL,
`tuan_id` int(11) NOT NULL,
`is_tuan` int(2) NOT NULL,
`createtime` varchar(45) NOT NULL,
`pay_type` int(4) NOT NULL,
`starttime` varchar(45) NOT NULL,
`endtime` int(45) NOT NULL,
`tuan_first` int(11) NOT NULL,
`express` varchar(50),
`expresssn` varchar(50),
`transid` varchar(50) NOT NULL,
`remark` varchar(100) NOT NULL,
`success` int(11) NOT NULL,
`addname` varchar(50) NOT NULL,
`mobile` varchar(50) NOT NULL,
`address` varchar(300) NOT NULL,
`goodsprice` varchar(45) NOT NULL,
`pay_price` varchar(45) NOT NULL,
`freight` varchar(45) NOT NULL,
`credits` int(11) NOT NULL,
`is_usecard` int(11) NOT NULL,
`is_hexiao` int(2) NOT NULL,
`hexiaoma` varchar(50) NOT NULL,
`veropenid` varchar(200) NOT NULL,
`sendtime` varchar(115) NOT NULL,
`gettime` varchar(115) NOT NULL,
`addresstype` int(11) NOT NULL,
`optionid` int(11) NOT NULL,
`checkpay` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_order_goods` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`fk_orderid` int(10) NOT NULL,
`fk_goodid` int(10) NOT NULL,
`uniacid` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `fk_orderid` (`fk_orderid`),
UNIQUE KEY `fk_goodid` (`fk_goodid`),
UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_order_print` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`sid` int(10) NOT NULL,
`pid` int(3) NOT NULL,
`oid` int(10) NOT NULL,
`foid` varchar(50) NOT NULL,
`status` int(3) NOT NULL,
`addtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_print` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`sid` int(10) NOT NULL,
`name` varchar(45) NOT NULL,
`print_no` varchar(50) NOT NULL,
`key` varchar(50) NOT NULL,
`member_code` varchar(50) NOT NULL,
`print_nums` int(3) NOT NULL,
`qrcode_link` varchar(100) NOT NULL,
`status` int(3) NOT NULL,
`mode` int(3) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_refund_record` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`type` int(11) NOT NULL,
`goodsid` int(11) NOT NULL,
`payfee` varchar(100) NOT NULL,
`refundfee` varchar(100) NOT NULL,
`transid` varchar(115) NOT NULL,
`refund_id` varchar(115) NOT NULL,
`refundername` varchar(100) NOT NULL,
`refundermobile` varchar(100) NOT NULL,
`goodsname` varchar(100) NOT NULL,
`createtime` varchar(45) NOT NULL,
`status` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`orderid` varchar(45) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_rules` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`rulesname` varchar(40) NOT NULL,
`rulesdetail` varchar(4000),
`uniacid` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `rulesname` (`rulesname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_saler` (
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

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_spec` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`description` varchar(1000) NOT NULL,
`displaytype` tinyint(3) unsigned NOT NULL,
`content` text NOT NULL,
`goodsid` int(11),
`displayorder` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_spec_item` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`specid` int(11),
`title` varchar(255),
`thumb` varchar(255),
`show` int(11),
`displayorder` int(11),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_specid` (`specid`),
KEY `indx_show` (`show`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_store` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`storename` varchar(255),
`address` varchar(255),
`tel` varchar(255),
`lat` varchar(255),
`lng` varchar(255),
`status` tinyint(3),
`createtime` varchar(45) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_uniacid` (`uniacid`),
KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_user` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`title` varchar(45) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_users` (
`id` int(10) NOT NULL,
`username` varchar(30) NOT NULL,
`password` varchar(20) NOT NULL,
`email` varchar(60) NOT NULL,
`tel` varchar(20) NOT NULL,
`uniacid` int(10) NOT NULL,
`openid` varchar(100) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `username` (`username`),
UNIQUE KEY `uniacid` (`uniacid`),
UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
