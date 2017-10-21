<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_address` (
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

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_adv` (
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

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_cart` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`projectid` int(11) NOT NULL,
`from_user` varchar(50) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_category` (
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

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_dispatch` (
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
`enabled` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_express` (
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

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_feedback` (
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

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_news` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned,
`title` varchar(255),
`thumb` varchar(255),
`cateid` int(10) unsigned,
`content` text,
`source` varchar(255),
`author` varchar(255),
`displayorder` int(10) unsigned,
`click` int(10) unsigned,
`is_display` tinyint(1) unsigned,
`createtime` int(10) unsigned,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_news_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned,
`title` varchar(255),
`displayorder` int(10) unsigned,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(50) NOT NULL,
`ordersn` varchar(20) NOT NULL,
`pid` int(10) unsigned NOT NULL,
`item_id` int(10) unsigned NOT NULL,
`price` varchar(10) NOT NULL,
`status` tinyint(4) NOT NULL,
`sendtype` tinyint(1) unsigned NOT NULL,
`paytype` tinyint(1) unsigned NOT NULL,
`transid` varchar(30) NOT NULL,
`return_type` tinyint(1) unsigned NOT NULL,
`remark` varchar(1000) NOT NULL,
`addressid` int(10) unsigned NOT NULL,
`expresscom` varchar(30) NOT NULL,
`expresssn` varchar(50) NOT NULL,
`express` varchar(200) NOT NULL,
`item_price` decimal(10,2),
`dispatchprice` decimal(10,2),
`dispatch` int(10),
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_order_ws` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned,
`from_user` varchar(50),
`nickname` varchar(20) NOT NULL,
`avatar` varchar(255) NOT NULL,
`ordersn` int(10) unsigned,
`price` decimal(10,2) unsigned,
`paytype` varchar(10),
`transid` varchar(20),
`status` tinyint(1) unsigned,
`remark` varchar(255),
`pid` int(10) unsigned,
`createtime` int(10) unsigned,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_project` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(50),
`displayorder` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`limit_price` decimal(10,2) NOT NULL,
`donenum` int(10) unsigned NOT NULL,
`finish_price` decimal(10,2) NOT NULL,
`starttime` int(10) unsigned,
`deal_days` int(10) unsigned NOT NULL,
`ishot` tinyint(1) unsigned NOT NULL,
`isrecommand` tinyint(1) unsigned,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`thumb` varchar(255),
`video` varchar(255) NOT NULL,
`brief` varchar(1000) NOT NULL,
`url` varchar(255) NOT NULL,
`content` text NOT NULL,
`nosubuser` tinyint(1) unsigned NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
`reason` varchar(255),
`createtime` int(10) unsigned NOT NULL,
`subsurl` varchar(500),
`show_type` int(10),
`type` tinyint(10),
`lianxiren` varchar(20),
`qq` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_project_item` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`pid` int(10) unsigned NOT NULL,
`price` decimal(10,2) NOT NULL,
`description` varchar(2000) NOT NULL,
`thumb` varchar(255) NOT NULL,
`limit_num` int(10) unsigned NOT NULL,
`donenum` int(10) unsigned NOT NULL,
`repaid_day` int(10) unsigned NOT NULL,
`return_type` tinyint(1) unsigned NOT NULL,
`delivery_fee` decimal(10,2) NOT NULL,
`dispatch` int(10) unsigned NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
