<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_jing_donation` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned,
`title` varchar(255),
`thumb` varchar(255),
`description` varchar(255),
`starttime` int(10) unsigned,
`endtime` int(10) unsigned,
`content` text,
`company` varchar(255),
`account` varchar(255),
`fixed_money1` decimal(10,2) unsigned,
`fixed_money2` decimal(10,2) unsigned,
`fixed_money3` decimal(10,2) unsigned,
`fixed_money4` decimal(10,2) unsigned,
`tip` varchar(255),
`share_content1` varchar(255),
`share_content2` varchar(255),
`logo` varchar(255),
`share_title` varchar(255),
`share_pic` varchar(255),
`share_des` varchar(255),
`circle_name` varchar(255),
`text1` varchar(20),
`text2` varchar(20),
`numbers` int(10) NOT NULL,
`video` varchar(200) NOT NULL,
`need_remark` tinyint(1) NOT NULL,
`need_name` tinyint(1) NOT NULL,
`need_mobile` tinyint(1) NOT NULL,
`money` decimal(10,2) unsigned,
`thanks` varchar(255),
`xieyi` text,
`enabled` tinyint(1) unsigned,
`createtime` int(10) unsigned,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jing_donation_adv` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`advname` varchar(50),
`link` varchar(255) NOT NULL,
`thumb` varchar(255),
`displayorder` int(11),
`enabled` int(11),
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`),
KEY `indx_enabled` (`enabled`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jing_donation_dynamic` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`did` int(10),
`title` varchar(50),
`link` varchar(255) NOT NULL,
`thumb` varchar(255),
`description` varchar(255),
`content` text,
`createtime` int(10),
`enabled` int(11),
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`),
KEY `indx_enabled` (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jing_donation_invitation` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned,
`did` int(10) unsigned,
`openid` varchar(50),
`content` varchar(255),
`status` tinyint(1) unsigned,
`createtime` int(10) unsigned,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jing_donation_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned,
`did` int(10) unsigned,
`openid` varchar(50),
`ordersn` int(10) unsigned,
`price` decimal(10,2) unsigned,
`status` tinyint(1) unsigned,
`paytype` varchar(10),
`transid` varchar(255),
`remark` varchar(255) NOT NULL,
`realname` varchar(20) NOT NULL,
`mobile` varchar(11) NOT NULL,
`createtime` int(10) unsigned,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jing_donation_reply` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`donationid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jing_donation_user` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned,
`openid` varchar(50),
`unionid` varchar(50),
`nickname` varchar(20),
`sex` tinyint(1) unsigned,
`avatar` varchar(255),
`country` varchar(20),
`province` varchar(20),
`city` varchar(20),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jing_donation_yxz` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned,
`did` int(10) unsigned,
`openid` varchar(50),
`yxz` int(10) unsigned,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
