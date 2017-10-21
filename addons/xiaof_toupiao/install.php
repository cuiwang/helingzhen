<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`groups` tinyint(255) NOT NULL,
`verify` tinyint(255) NOT NULL,
`openid` varchar(50) NOT NULL,
`nickname` varchar(20) NOT NULL,
`avatar` varchar(255) NOT NULL,
`ip` bigint(20) NOT NULL,
`name` char(36) NOT NULL,
`phone` char(36) NOT NULL,
`pic` varchar(255) NOT NULL,
`sound` varchar(255) NOT NULL,
`describe` char(255) NOT NULL,
`detail` text,
`data` mediumtext NOT NULL,
`click` int(11) NOT NULL,
`share` int(11) NOT NULL,
`good` int(11) NOT NULL,
`open` tinyint(255) NOT NULL,
`double_at` int(10) NOT NULL,
`created_at` int(10) NOT NULL,
`updated_at` int(10) NOT NULL,
`poster` varchar(255) NOT NULL,
`locking_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `sid_2` (`sid`,`openid`),
UNIQUE KEY `sid_3` (`sid`,`phone`),
KEY `sid` (`sid`),
KEY `sid_4` (`sid`,`verify`),
KEY `groups` (`groups`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_acid` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`acid` int(11) NOT NULL,
`qrcode` varchar(255) NOT NULL,
PRIMARY KEY (`id`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_draw` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`prizeid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`uname` varchar(255) NOT NULL,
`avatar` varchar(255) NOT NULL,
`uses` tinyint(255) NOT NULL,
`attr` tinyint(255) NOT NULL,
`credit` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`num` int(11) NOT NULL,
`openid` varchar(50) NOT NULL,
`ip` bigint(20) NOT NULL,
`created_at` int(10) NOT NULL,
`bdelete_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `sid` (`sid`,`uid`),
KEY `sid_2` (`sid`,`attr`),
KEY `sid_3` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_drawlog` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`uname` varchar(255) NOT NULL,
`avatar` varchar(255) NOT NULL,
`attr` int(11) NOT NULL,
`data` varchar(255) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`fanid` int(11) NOT NULL,
`nickname` varchar(20) NOT NULL,
`avatar` varchar(255) NOT NULL,
`num` tinyint(255) NOT NULL,
`openid` varchar(50) NOT NULL,
`ip` bigint(20) NOT NULL,
`valid` tinyint(255) NOT NULL,
`unique_at` int(8) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `pid_2` (`pid`,`openid`,`unique_at`),
KEY `pid` (`pid`),
KEY `sid_2` (`sid`),
KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_manage` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`ip` bigint(20) NOT NULL,
`num` int(11) NOT NULL,
`operation` varchar(255) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `pid` (`pid`,`ip`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_pic` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`thumb` varchar(255) NOT NULL,
`url` varchar(255) NOT NULL,
`is_show` tinyint(255) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `sid` (`sid`,`pid`),
KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_rule` (
`rid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`action` tinyint(255) NOT NULL,
`keyword` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_safe` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`ip` bigint(20) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `ip` (`ip`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_setting` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`tit` varchar(255) NOT NULL,
`data` text NOT NULL,
`groups` text NOT NULL,
`unfollow` tinyint(255) NOT NULL,
`detail` text NOT NULL,
`bottom` text NOT NULL,
`click` int(11) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `unfollow` (`unfollow`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_smslog` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`phone` char(20) NOT NULL,
`ip` bigint(20) NOT NULL,
`created_at` int(10) NOT NULL,
`unique_at` int(8) NOT NULL,
PRIMARY KEY (`id`),
KEY `phone` (`phone`),
KEY `ip` (`ip`),
KEY `day` (`unique_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
