<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_cyl_phone_adv` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`displayorder` int(10) unsigned NOT NULL,
`followurl` varchar(1000),
`thumb` varchar(1000),
`title` varchar(1000),
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_phone_business` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`openid` varchar(255) NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`categoryid` int(11) NOT NULL,
`logo` varchar(255) NOT NULL,
`title` varchar(30) NOT NULL,
`phone` varchar(25) NOT NULL,
`industry_1` varchar(20) NOT NULL,
`industry_2` varchar(20) NOT NULL,
`weixin` varchar(25) NOT NULL,
`net` varchar(255) NOT NULL,
`lng` varchar(255) NOT NULL,
`lat` varchar(255) NOT NULL,
`address` varchar(255) NOT NULL,
`ewm` varchar(255) NOT NULL,
`zy` text NOT NULL,
`desc` text NOT NULL,
`time` varchar(255) NOT NULL,
`status` int(11) NOT NULL,
`click` int(25) NOT NULL,
`recommended` int(2) NOT NULL,
`categoryid_2` int(11) NOT NULL,
`dpimg` varchar(255) NOT NULL,
`yjh` varchar(100) NOT NULL,
`nickname` varchar(30) NOT NULL,
`tx` varchar(255) NOT NULL,
`dnimg` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_phone_category` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(30) NOT NULL,
`orderno` int(10) unsigned NOT NULL,
`thumb` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
`rid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_phone_individual` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
`nickname` varchar(255) NOT NULL,
`phone` varchar(20) NOT NULL,
`wxh` varchar(255) NOT NULL,
`ewm` varchar(255) NOT NULL,
`status` int(10) NOT NULL,
`avatar` varchar(255) NOT NULL,
`address` varchar(255) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_phone_message` (
`id` int(20) unsigned NOT NULL AUTO_INCREMENT,
`contentid` int(20) NOT NULL,
`uniacid` int(20) NOT NULL,
`openid` varchar(255) NOT NULL,
`nickname` varchar(255) NOT NULL,
`content` text NOT NULL,
`time` varchar(255) NOT NULL,
`avatar` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_phone_order` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`business_id` int(11) NOT NULL,
`tid` varchar(255) NOT NULL,
`uid` varchar(25) NOT NULL,
`openid` varchar(255) NOT NULL,
`status` int(11) NOT NULL,
`time` varchar(25) NOT NULL,
`fee` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_phone_paihang` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`business_id` int(11) NOT NULL,
`click` int(11) NOT NULL,
`time` varchar(25) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_phone_push` (
`uniacid` int(25) NOT NULL,
`first` varchar(255) NOT NULL,
`keyword1` varchar(255) NOT NULL,
`keyword2` varchar(255) NOT NULL,
`remark` varchar(255) NOT NULL,
`link` varchar(255) NOT NULL,
`push` int(2) NOT NULL,
`kfid` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_phone_weixin` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(2) NOT NULL,
`openid` varchar(255) NOT NULL,
`weixin` varchar(255) NOT NULL,
`ewm` varchar(255) NOT NULL,
`nickname` varchar(255) NOT NULL,
`yjh` varchar(255) NOT NULL,
`recommended` int(2) NOT NULL,
`avatar` varchar(255) NOT NULL,
`time` varchar(25) NOT NULL,
`sex` int(2) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
