<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_xc_lv_adv` (
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

CREATE TABLE IF NOT EXISTS `ims_xc_lv_cart` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`goodsid` int(11) NOT NULL,
`goodstype` tinyint(1) NOT NULL,
`from_user` varchar(50) NOT NULL,
`total` int(10) unsigned NOT NULL,
`optionid` int(10),
`marketprice` decimal(10,2),
PRIMARY KEY (`id`),
KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(255) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`isrecommand` int(10),
`description` varchar(500) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`catetitle` varchar(255),
`enabled` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_dispatch` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`dispatchname` varchar(50),
`dispatchtype` int(11),
`displayorder` int(11),
`firstprice` decimal(10,2),
`secondprice` decimal(10,2),
`firstweight` int(11),
`secondweight` int(11),
`express` int(11),
`description` text,
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_express` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`express_name` varchar(50),
`displayorder` int(11),
`express_price` varchar(10),
`express_area` varchar(100),
`express_url` varchar(255),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_feedback` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
`type` tinyint(1) unsigned NOT NULL,
`status` tinyint(1) NOT NULL,
`feedbackid` varchar(30) NOT NULL,
`transid` varchar(30) NOT NULL,
`reason` varchar(1000) NOT NULL,
`solution` varchar(1000) NOT NULL,
`remark` varchar(1000) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_weid` (`weid`),
KEY `idx_feedbackid` (`feedbackid`),
KEY `idx_createtime` (`createtime`),
KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_firm` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`city` varchar(255),
`city2` varchar(255),
`stime` datetime,
`xq` varchar(255),
`mark` text,
`xingming` varchar(255),
`mobile` varchar(20),
`haoma` varchar(255),
`status` int(1),
`openid` varchar(200),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_goods` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`type` tinyint(1) unsigned NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`thumb` varchar(100) NOT NULL,
`unit` varchar(5) NOT NULL,
`description` varchar(1000) NOT NULL,
`content` text NOT NULL,
`goodssn` varchar(50) NOT NULL,
`productsn` varchar(50) NOT NULL,
`marketprice` decimal(10,2) NOT NULL,
`productprice` decimal(10,2) NOT NULL,
`costprice` decimal(10,2) NOT NULL,
`originalprice` decimal(10,2) NOT NULL,
`total` int(10) unsigned NOT NULL,
`totalcnf` int(11),
`sales` int(10) unsigned NOT NULL,
`spec` varchar(5000) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
`weight` decimal(10,2) NOT NULL,
`credit` decimal(10,2) NOT NULL,
`maxbuy` int(11),
`usermaxbuy` int(10) unsigned NOT NULL,
`hasoption` int(11),
`dispatch` int(11),
`thumb_url` text,
`isnew` int(11),
`ishot` int(11),
`isdiscount` int(11),
`isrecommand` int(11),
`istime` int(11),
`timestart` int(11),
`timeend` int(11),
`viewcount` int(11),
`deleted` tinyint(3) unsigned NOT NULL,
`starttime` int(11),
`endtime` int(11),
`xingcheng` text,
`time2` varchar(500),
`xingji` int(10),
`detime` varchar(5000) NOT NULL,
`zhi` text NOT NULL,
`location` varchar(255),
`han` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_goods_option` (
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

CREATE TABLE IF NOT EXISTS `ims_xc_lv_goods_param` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goodsid` int(10),
`title` varchar(50),
`value` text,
`displayorder` int(11),
PRIMARY KEY (`id`),
KEY `indx_goodsid` (`goodsid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_member_address` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(50) unsigned NOT NULL,
`username` varchar(20) NOT NULL,
`mobile` varchar(11) NOT NULL,
`zipcode` varchar(6) NOT NULL,
`province` varchar(32) NOT NULL,
`city` varchar(32) NOT NULL,
`district` varchar(32) NOT NULL,
`address` varchar(512) NOT NULL,
`isdefault` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_uinacid` (`uniacid`),
KEY `idx_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(50) NOT NULL,
`ordersn` varchar(20) NOT NULL,
`price` varchar(10) NOT NULL,
`status` tinyint(4) NOT NULL,
`sendtype` tinyint(1) unsigned NOT NULL,
`paytype` tinyint(1) unsigned NOT NULL,
`transid` varchar(30) NOT NULL,
`goodstype` tinyint(1) unsigned NOT NULL,
`remark` varchar(1000) NOT NULL,
`address` varchar(1024) NOT NULL,
`expresscom` varchar(30) NOT NULL,
`expresssn` varchar(50) NOT NULL,
`express` varchar(200) NOT NULL,
`goodsprice` decimal(10,2),
`dispatchprice` decimal(10,2),
`dispatch` int(10),
`paydetail` varchar(255) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
`retime` datetime,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_order_goods` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`orderid` int(10) unsigned NOT NULL,
`goodsid` int(10) unsigned NOT NULL,
`price` decimal(10,2),
`total` int(10) unsigned NOT NULL,
`optionid` int(10),
`createtime` int(10) unsigned NOT NULL,
`optionname` text,
`ettotal` int(10),
`productprice` decimal(10,2),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_product` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`goodsid` int(11) NOT NULL,
`productsn` varchar(50) NOT NULL,
`title` varchar(1000) NOT NULL,
`marketprice` decimal(10,0) unsigned NOT NULL,
`productprice` decimal(10,0) unsigned NOT NULL,
`total` int(11) NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`spec` varchar(5000) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_spec` (
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

CREATE TABLE IF NOT EXISTS `ims_xc_lv_spec_item` (
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

");
