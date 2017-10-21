<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_addcard` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`rid` int(10) NOT NULL,
`pcate` int(10) NOT NULL,
`uniacid` int(11),
`buynum` varchar(255) NOT NULL,
`cardname` varchar(255) NOT NULL,
`cardnum` int(10) NOT NULL,
`cardid` varchar(255) NOT NULL,
`cardprize` decimal(10,2) NOT NULL,
`most_num_times` int(11),
`today_most_times` int(11),
`getendtime` int(10),
`getstarttime` int(10),
`isstartusing` int(10) unsigned NOT NULL,
`createtime` varchar(20),
PRIMARY KEY (`id`),
KEY `indx_rid` (`rid`),
KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_address` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
`realname` varchar(20) NOT NULL,
`mobile` varchar(11) NOT NULL,
`province` varchar(30) NOT NULL,
`city` varchar(30) NOT NULL,
`area` varchar(30) NOT NULL,
`address` varchar(300) NOT NULL,
`isdefault` tinyint(3) unsigned NOT NULL,
`deleted` tinyint(3) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_adv` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`advname` varchar(50),
`link` varchar(255) NOT NULL,
`thumb` varchar(255),
`displayorder` int(11),
`enabled` int(11),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_enabled` (`enabled`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_cardticket` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10),
`createtime` varchar(20),
`ticket` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_cart` (
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

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(255) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`isrecommand` int(10),
`bianhao` int(10),
`description` varchar(500) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`enabled` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_dispatch` (
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

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_express` (
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

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_feedback` (
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

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_goods` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`sales_num` int(10) unsigned NOT NULL,
`type` tinyint(1) unsigned NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`thumb` varchar(255),
`unit` varchar(5) NOT NULL,
`description` varchar(1000) NOT NULL,
`content` text NOT NULL,
`goodssn` varchar(100) NOT NULL,
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
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_goods_option` (
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

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_goods_param` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goodsid` int(10),
`title` varchar(50),
`value` text,
`displayorder` int(11),
PRIMARY KEY (`id`),
KEY `indx_goodsid` (`goodsid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_hexiaoaward` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
`realname` varchar(20) NOT NULL,
`mobile` varchar(11) NOT NULL,
`category` varchar(30) NOT NULL,
`status` tinyint(1) NOT NULL,
`starttime` int(10),
`endtime` int(10),
`pwid` varchar(50) NOT NULL,
`pwsn` varchar(100) NOT NULL,
`title` varchar(35),
`used_cardid` varchar(50),
`createtime` int(10),
`orderid` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_hexiaopeople` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
`avatar` varchar(255),
`realname` varchar(20) NOT NULL,
`mobile` varchar(11) NOT NULL,
`category` varchar(30) NOT NULL,
`status` tinyint(1) NOT NULL,
`starttime` int(10),
`endtime` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(50) NOT NULL,
`ordersn` varchar(20) NOT NULL,
`tandid` varchar(50) NOT NULL,
`price` varchar(10) NOT NULL,
`status` tinyint(4) NOT NULL,
`statuss` tinyint(1) NOT NULL,
`sendtype` tinyint(1) unsigned NOT NULL,
`paytype` tinyint(1) unsigned NOT NULL,
`transid` varchar(30) NOT NULL,
`goodstype` tinyint(1) unsigned NOT NULL,
`remark` varchar(1000) NOT NULL,
`addressid` int(10) unsigned NOT NULL,
`realname` varchar(20) NOT NULL,
`mobile` varchar(11) NOT NULL,
`address` varchar(300) NOT NULL,
`expresscom` varchar(30) NOT NULL,
`expresssn` varchar(50) NOT NULL,
`express` varchar(200) NOT NULL,
`goodsprice` decimal(10,2),
`dispatchprice` decimal(10,2),
`dispatch` int(10),
`paydetail` varchar(255) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
`paytime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_order_goods` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`orderid` varchar(50) NOT NULL,
`goodsid` int(10) unsigned NOT NULL,
`price` decimal(10,2),
`total` int(10) unsigned NOT NULL,
`optionid` int(10),
`createtime` int(10) unsigned NOT NULL,
`pici` varchar(10) NOT NULL,
`pwid` varchar(100) NOT NULL,
`title` varchar(35),
`used_cardid` varchar(50),
`cardid` varchar(50),
`category` varchar(50),
`optionname` text,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_pici` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`rid` int(11),
`pici` int(10),
`rulename` varchar(50),
`codenum` int(11),
`is_qrcode` varchar(100),
`is_qrcode2` int(11),
`createtime` int(10),
`status` tinyint(1),
PRIMARY KEY (`id`),
KEY `indx_rid` (`rid`),
KEY `indx_uniacid` (`uniacid`),
KEY `indx_pici` (`pici`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_product` (
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

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_pw` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`rid` int(10) unsigned NOT NULL,
`pici` int(10) NOT NULL,
`pwid` varchar(50) NOT NULL,
`pwsn` varchar(100) NOT NULL,
`title` varchar(35),
`rulename` varchar(50),
`iscqr` int(11),
`isused` int(11),
`used_cardid` varchar(50),
`endtime` int(10),
`used_times` int(10),
`activation_time` int(10),
`num` int(10),
`category` varchar(50),
`status` tinyint(1),
`ishexiao` tinyint(1) unsigned NOT NULL,
`bianhao` int(10),
`createtime` int(11) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_rid` (`rid`),
KEY `indx_pwid` (`pwid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_spec` (
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

CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_spec_item` (
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
