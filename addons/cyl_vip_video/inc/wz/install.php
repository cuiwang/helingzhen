<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(5) NOT NULL,
`title` varchar(255) NOT NULL,
`uid` varchar(25) NOT NULL,
`openid` varchar(255) NOT NULL,
`time` varchar(15) NOT NULL,
`video_url` text NOT NULL,
`share` int(3) NOT NULL,
`yvideo_url` text NOT NULL,
`type` varchar(25) NOT NULL,
`index` int(2) NOT NULL,
`video_id` int(11) NOT NULL,
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`parentid` int(10) NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`name` varchar(20) NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`url` varchar(1000) NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_hdp` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`title` varchar(255) NOT NULL,
`thumb` varchar(1000) NOT NULL,
`link` varchar(1000) NOT NULL,
`out_link` varchar(1000) NOT NULL,
`type` varchar(15) NOT NULL,
`sort` int(5) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_keyword` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`title` varchar(25) NOT NULL,
`card_id` varchar(25) NOT NULL,
`num` int(11) NOT NULL,
`day` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_keyword_id` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`openid` varchar(1000) NOT NULL,
`uniacid` int(11) NOT NULL,
`pwd` varchar(25) NOT NULL,
`card_id` varchar(25) NOT NULL,
`day` int(11) NOT NULL,
`status` int(2) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_manage` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`title` varchar(25) NOT NULL,
`thumb` varchar(1000) NOT NULL,
`year` varchar(25) NOT NULL,
`star` varchar(25) NOT NULL,
`type` varchar(25) NOT NULL,
`actor` varchar(25) NOT NULL,
`video_url` text NOT NULL,
`desc` text NOT NULL,
`time` varchar(25) NOT NULL,
`screen` varchar(25) NOT NULL,
`cid` int(3) NOT NULL,
`pid` int(3) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_member` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`openid` varchar(255) NOT NULL,
`uid` varchar(25) NOT NULL,
`nickname` varchar(255) NOT NULL,
`avatar` varchar(1000) NOT NULL,
`end_time` varchar(15) NOT NULL,
`is_pay` int(2) NOT NULL,
`time` varchar(15) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(5) NOT NULL,
`openid` varchar(255) NOT NULL,
`uid` varchar(25) NOT NULL,
`status` int(2) NOT NULL,
`fee` decimal(10,2) NOT NULL,
`time` varchar(15) NOT NULL,
`tid` varchar(255) NOT NULL,
`day` int(5) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
