<?php
pdo_query("DROP TABLE IF EXISTS `ims_junsion_simpledaily_buy`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_buy` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) DEFAULT '0',
`ordersn` varchar(50) DEFAULT '',
`openid` varchar(50) DEFAULT '',
`styleid` int(10) DEFAULT '0',
`price` decimal(11,2) DEFAULT '0.00',
`transid` varchar(50) NOT NULL DEFAULT '0',
`status` tinyint(1) DEFAULT '0',
`createtime` int(11) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_cate`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_cate` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) DEFAULT '0',
`title` varchar(50) DEFAULT '',
`status` tinyint(1) DEFAULT '0',
`displayorder` int(10) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_comment`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_comment` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) DEFAULT '0',
`wid` int(10) DEFAULT '0',
`avatar` varchar(250) DEFAULT '',
`nickname` varchar(50) DEFAULT '',
`content` varchar(500) DEFAULT '0',
`createtime` int(11) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_good`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_good` (
`wid` int(11) DEFAULT '0',
`openid` varchar(50)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_member`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_member` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) DEFAULT '0',
`openid` varchar(50) DEFAULT '',
`avatar` varchar(255) DEFAULT '',
`nickname` varchar(50) DEFAULT '',
`status` tinyint(1) DEFAULT '0',
`authopenid` varchar(50) DEFAULT '',
`createtime` int(10) DEFAULT '0',
`buy_styleid` text,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_music`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_music` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) DEFAULT '0',
`cate` int(10) DEFAULT '0',
`title` varchar(50) DEFAULT '',
`url` varchar(255) DEFAULT '',
`status` tinyint(1) DEFAULT '0',
`sort` int(11) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_order`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) DEFAULT '0',
`openid` varchar(50) DEFAULT '',
`ordersn` varchar(50) DEFAULT '',
`status` tinyint(1) DEFAULT '0',
`price` decimal(11,2) DEFAULT '0.00',
`rate` decimal(11,2) DEFAULT '0.00',
`transaction_id` varchar(50) DEFAULT '',
`wid` int(10) DEFAULT '0',
`packet_status` tinyint(1) DEFAULT '0',
`packet_batch` varchar(50) DEFAULT '',
`packet_time` int(10) DEFAULT '0',
`createtime` int(10) DEFAULT '0',
`avatar` varchar(250) DEFAULT '',
`nickname` varchar(50) DEFAULT '',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_report`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_report` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) DEFAULT '0',
`content` varchar(255) DEFAULT '',
`status` tinyint(1) DEFAULT '0',
`sort` int(11) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_report_detail`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_report_detail` (
`weid` int(10) DEFAULT '0',
`wid` int(10) DEFAULT '0',
`openid` varchar(50) DEFAULT '',
`content` varchar(255) DEFAULT '',
`createtime` int(10) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_style`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_style` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) DEFAULT '0',
`title` varchar(50) DEFAULT '',
`path` varchar(250) DEFAULT '',
`status` tinyint(1) DEFAULT '0',
`createtime` int(11) DEFAULT '0',
`sort` int(11) DEFAULT '0',
`price` decimal(11,2) DEFAULT '0.00',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_junsion_simpledaily_works`;
CREATE TABLE IF NOT EXISTS `ims_junsion_simpledaily_works` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) DEFAULT '0',
`openid` varchar(50),
`authopenid` varchar(50),
`avatar` varchar(250),
`nickname` varchar(50),
`styleid` int(11) DEFAULT '0',
`musicid` int(11) DEFAULT '0',
`title` varchar(50),
`cover` varchar(250),
`imgs` text,
`read` int(11) DEFAULT '0',
`good` int(11) DEFAULT '0',
`type` tinyint(1) DEFAULT '0',
`special` int(11) DEFAULT '0',
`psw` varchar(32),
`status` tinyint(1) DEFAULT '0',
`find` tinyint(1) DEFAULT '0',
`sort` int(11) DEFAULT '0',
`createtime` int(11) DEFAULT '0',
`preview` int(11) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
