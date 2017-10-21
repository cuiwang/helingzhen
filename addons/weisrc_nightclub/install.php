<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_activity` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`logo` varchar(200) NOT NULL,
`content` text,
`tel` varchar(20) NOT NULL,
`address` varchar(200) NOT NULL,
`url` varchar(200) NOT NULL,
`start_time` int(10) NOT NULL,
`end_time` int(10) NOT NULL,
`isfirst` tinyint(1) NOT NULL,
`status` tinyint(1) NOT NULL,
`displayorder` tinyint(3) NOT NULL,
`dateline` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_activity_feedback` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`activityid` int(11) NOT NULL,
`parentid` int(11),
`from_user` varchar(100),
`nickname` varchar(30),
`headimgurl` varchar(500),
`content` varchar(600),
`top` tinyint(1) NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1),
`dateline` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_activity_user` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`activityid` int(10) unsigned NOT NULL,
`from_user` varchar(100),
`nickname` varchar(100),
`headimgurl` varchar(500),
`title` varchar(200),
`username` varchar(100),
`tel` varchar(30),
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_goods` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`thumb` varchar(100) NOT NULL,
`description` varchar(1000) NOT NULL,
`marketprice` varchar(10) NOT NULL,
`productprice` varchar(10) NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_introduce` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`title` varchar(50) NOT NULL,
`logo` varchar(200) NOT NULL,
`info` varchar(1000) NOT NULL,
`content` text NOT NULL,
`savewinerule` text NOT NULL,
`tel` varchar(20) NOT NULL,
`location_p` varchar(100) NOT NULL,
`location_c` varchar(100) NOT NULL,
`location_a` varchar(100) NOT NULL,
`hours` varchar(200) NOT NULL,
`address` varchar(200) NOT NULL,
`contact` varchar(100) NOT NULL,
`consume` varchar(100) NOT NULL,
`wifi` varchar(200) NOT NULL,
`place` varchar(200) NOT NULL,
`lat` decimal(18,10) NOT NULL,
`lng` decimal(18,10) NOT NULL,
`displayorder` tinyint(3) NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_neighbor_feedback` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`from_user` varchar(100),
`nickname` varchar(30),
`headimgurl` varchar(500),
`content` varchar(600),
`top` tinyint(1) NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1),
`dateline` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_neighbor_user` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(100),
`nickname` varchar(100),
`headimgurl` varchar(500),
`username` varchar(100),
`weixin` varchar(50),
`tel` varchar(30),
`qq` varchar(30),
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1),
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_photo` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`url` varchar(200) NOT NULL,
`description` varchar(1000) NOT NULL,
`attachment` varchar(100) NOT NULL,
`from_user` varchar(100),
`nickname` varchar(100),
`likecount` int(10) unsigned NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`isfirst` tinyint(1) NOT NULL,
`mode` tinyint(1) NOT NULL,
`checked` tinyint(1) NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_product` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`logo` varchar(200) NOT NULL,
`content` text NOT NULL,
`url` varchar(200) NOT NULL,
`isfirst` tinyint(1) NOT NULL,
`top` tinyint(1) NOT NULL,
`displayorder` tinyint(3) NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_savewine_log` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(100),
`nickname` varchar(100),
`headimgurl` varchar(500),
`savenumber` varchar(100) NOT NULL,
`title` varchar(200),
`username` varchar(100),
`tel` varchar(30),
`remark` text NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1) NOT NULL,
`takeouttime` int(10) unsigned NOT NULL,
`savetime` int(10) unsigned NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_setting` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`bg` varchar(500) NOT NULL,
`pagesize` int(10) unsigned NOT NULL,
`topcolor` varchar(20) NOT NULL,
`topbgcolor` varchar(20) NOT NULL,
`announcebordercolor` varchar(20) NOT NULL,
`announcebgcolor` varchar(20) NOT NULL,
`announcecolor` varchar(20) NOT NULL,
`storestitlecolor` varchar(20) NOT NULL,
`storesstatuscolor` varchar(20) NOT NULL,
`showcity` tinyint(1) NOT NULL,
`settled` tinyint(1) unsigned NOT NULL,
`feedback_show_enable` tinyint(1) NOT NULL,
`feedback_check_enable` tinyint(1) NOT NULL,
`photo_check_enable` tinyint(1) NOT NULL,
`scroll_announce` varchar(500) NOT NULL,
`scroll_announce_speed` tinyint(2) unsigned NOT NULL,
`scroll_announce_link` varchar(500) NOT NULL,
`scroll_announce_enable` tinyint(1) NOT NULL,
`copyright` varchar(500) NOT NULL,
`copyright_link` varchar(500) NOT NULL,
`appid` varchar(300) NOT NULL,
`secret` varchar(300) NOT NULL,
`share_title` varchar(100) NOT NULL,
`share_image` varchar(500) NOT NULL,
`share_desc` varchar(200) NOT NULL,
`share_cancel` varchar(200) NOT NULL,
`share_url` varchar(200) NOT NULL,
`share_num` int(10) NOT NULL,
`follow_url` varchar(200) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
`tplinfowine` varchar(500) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
