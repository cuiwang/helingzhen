<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay` (
`reid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`title` varchar(100) NOT NULL,
`description` varchar(1000) NOT NULL,
`content` text NOT NULL,
`information` varchar(500) NOT NULL,
`thumb` varchar(200) NOT NULL,
`inhome` tinyint(4) NOT NULL,
`createtime` int(10) NOT NULL,
`starttime` int(10) NOT NULL,
`endtime` int(10) unsigned NOT NULL,
`status` int(11) NOT NULL,
`kaishi` int(11) NOT NULL,
`jieshu` int(11) NOT NULL,
`tianshu` int(11) NOT NULL,
`pretotal` int(10) unsigned NOT NULL,
`pay` int(10) unsigned NOT NULL,
`xmshow` int(10) unsigned NOT NULL,
`xmname` varchar(50) NOT NULL,
`yuyuename` varchar(50) NOT NULL,
`noticeemail` varchar(50) NOT NULL,
`k_templateid` varchar(50) NOT NULL,
`kfid` varchar(50) NOT NULL,
`m_templateid` varchar(50) NOT NULL,
`accountsid` varchar(50) NOT NULL,
`tokenid` varchar(50) NOT NULL,
`appId` varchar(50) NOT NULL,
`templateId` varchar(50) NOT NULL,
`mobile` varchar(50) NOT NULL,
`mname` varchar(10) NOT NULL,
`skins` varchar(20) NOT NULL,
`kfirst` varchar(100) NOT NULL,
`kfoot` varchar(100) NOT NULL,
`mfirst` varchar(100) NOT NULL,
`mfoot` varchar(100) NOT NULL,
`share_url` varchar(100) NOT NULL,
`follow` tinyint(1),
`code` tinyint(1),
`is_time` tinyint(1),
`upsize` int(5) NOT NULL,
`icon` varchar(200) NOT NULL,
`is_list` tinyint(1) NOT NULL,
`subtitle` varchar(20) NOT NULL,
`is_num` tinyint(1) NOT NULL,
`numname` varchar(50) NOT NULL,
`srvtime` text NOT NULL,
`day` int(10) unsigned NOT NULL,
`timelist` tinyint(1) NOT NULL,
`mbgroup` int(10) unsigned NOT NULL,
`is_addr` tinyint(1),
`state1` varchar(20) NOT NULL,
`state2` varchar(20) NOT NULL,
`state3` varchar(20) NOT NULL,
`state4` varchar(20) NOT NULL,
`state5` varchar(20) NOT NULL,
`isdel` tinyint(1) NOT NULL,
`outlink` varchar(200) NOT NULL,
`isthumb` tinyint(1) NOT NULL,
`isreplace` tinyint(1) NOT NULL,
`image` varchar(250) NOT NULL,
`smsid` int(11) NOT NULL,
`daynum` int(11) NOT NULL,
`displayorder` int(3) NOT NULL,
`smstype` int(1) NOT NULL,
`iscard` tinyint(1),
`remove` varchar(100) NOT NULL,
`submit` varchar(20) NOT NULL,
`out1` varchar(100) NOT NULL,
`out2` varchar(100) NOT NULL,
`out3` varchar(100) NOT NULL,
`out4` varchar(100) NOT NULL,
`out5` varchar(100) NOT NULL,
`out6` varchar(100) NOT NULL,
`out7` varchar(100) NOT NULL,
`restrict` tinyint(1),
`role` int(11) NOT NULL,
`par` text NOT NULL,
`score_total` int(11) NOT NULL,
`score_vr` int(11) NOT NULL,
`score_num` int(11) NOT NULL,
`store` int(11) NOT NULL,
`switch` int(11) NOT NULL,
PRIMARY KEY (`reid`),
KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_data` (
`redid` bigint(20) NOT NULL AUTO_INCREMENT,
`reid` int(11) NOT NULL,
`rerid` int(11) NOT NULL,
`refid` int(11) NOT NULL,
`data` varchar(800) NOT NULL,
`displayorder` int(11) unsigned NOT NULL,
PRIMARY KEY (`redid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_fields` (
`refid` int(11) NOT NULL AUTO_INCREMENT,
`reid` int(11) NOT NULL,
`title` varchar(200) NOT NULL,
`type` varchar(20) NOT NULL,
`essential` tinyint(4) NOT NULL,
`bind` varchar(30) NOT NULL,
`value` varchar(300) NOT NULL,
`description` varchar(500) NOT NULL,
`displayorder` int(11) unsigned NOT NULL,
`image` varchar(250) NOT NULL,
`loc` int(11) NOT NULL,
PRIMARY KEY (`refid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_group` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
`groupid` varchar(100) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_info` (
`rerid` int(11) NOT NULL AUTO_INCREMENT,
`reid` int(11) NOT NULL,
`member` varchar(50) NOT NULL,
`mobile` varchar(11) NOT NULL,
`openid` varchar(50) NOT NULL,
`status` tinyint(1) NOT NULL,
`xmid` int(11) NOT NULL,
`price` decimal(10,2) NOT NULL,
`ordersn` varchar(20) NOT NULL,
`transid` varchar(30) NOT NULL,
`paystatus` tinyint(4) NOT NULL,
`paytype` tinyint(4) NOT NULL,
`yuyuetime` int(10) NOT NULL,
`kfinfo` varchar(100) NOT NULL,
`createtime` int(10) NOT NULL,
`address` varchar(1024) NOT NULL,
`num` int(3) NOT NULL,
`restime` varchar(50) NOT NULL,
`paydetail` varchar(100) NOT NULL,
`remit` varchar(250) NOT NULL,
`thumb` text NOT NULL,
`kf` varchar(50) NOT NULL,
`sid` int(11) NOT NULL,
PRIMARY KEY (`rerid`),
KEY `reid` (`reid`),
KEY `index_name` (`ordersn`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_reply` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_role` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
`roleid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_skins` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(50) NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(250) NOT NULL,
`description` varchar(100) NOT NULL,
`mode` tinyint(1) NOT NULL,
`status` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_slide` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`title` varchar(50),
`link` varchar(255),
`thumb` varchar(255),
`displayorder` int(11),
`enabled` int(11),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_enabled` (`enabled`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_staff` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
`nickname` varchar(50) NOT NULL,
`openid` varchar(50) NOT NULL,
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_xiangmu` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
`title` varchar(50) NOT NULL,
`price` decimal(10,2) NOT NULL,
`displayorder` tinyint(3) NOT NULL,
`isshow` tinyint(1) NOT NULL,
`prices` decimal(10,2) NOT NULL,
`type` tinyint(1) NOT NULL,
`daynum` int(4) NOT NULL,
`isc` tinyint(1),
`content` text NOT NULL,
`thumb` varchar(200) NOT NULL,
PRIMARY KEY (`id`),
KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
