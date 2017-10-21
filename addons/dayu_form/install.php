<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_dayu_form` (
`reid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`title` varchar(100) NOT NULL,
`description` varchar(1000) NOT NULL,
`content` text NOT NULL,
`information` varchar(500) NOT NULL,
`thumb` varchar(200) NOT NULL,
`inhome` tinyint(4) NOT NULL,
`starttime` int(10) NOT NULL,
`endtime` int(10) unsigned NOT NULL,
`status` int(1) NOT NULL,
`member` varchar(20) NOT NULL,
`phone` varchar(20) NOT NULL,
`noticeemail` varchar(50) NOT NULL,
`k_templateid` varchar(50) NOT NULL,
`kfid` varchar(50) NOT NULL,
`m_templateid` varchar(50) NOT NULL,
`kfirst` varchar(100) NOT NULL,
`kfoot` varchar(100) NOT NULL,
`mfirst` varchar(100) NOT NULL,
`mfoot` varchar(100) NOT NULL,
`mobile` varchar(50) NOT NULL,
`adds` varchar(20) NOT NULL,
`skins` varchar(20) NOT NULL,
`custom_status` int(1) NOT NULL,
`mbgroup` int(10) unsigned NOT NULL,
`outlink` varchar(250) NOT NULL,
`isinfo` tinyint(1) NOT NULL,
`isvoice` tinyint(1) NOT NULL,
`isrevoice` tinyint(1) NOT NULL,
`ivoice` tinyint(1) NOT NULL,
`voice` varchar(50) NOT NULL,
`voicedec` varchar(50) NOT NULL,
`isloc` tinyint(1) NOT NULL,
`isrethumb` tinyint(1) NOT NULL,
`isrecord` tinyint(1) NOT NULL,
`isget` tinyint(1) NOT NULL,
`credit` decimal(10,2) NOT NULL,
`smsid` int(11) NOT NULL,
`smsnotice` int(11) NOT NULL,
`smstype` int(1) NOT NULL,
`agreement` varchar(50) NOT NULL,
`paixu` int(1) NOT NULL,
`field` tinyint(1) NOT NULL,
`fields` text NOT NULL,
`avatar` tinyint(1) NOT NULL,
`bcolor` varchar(10) NOT NULL,
`pluraltit` varchar(50) NOT NULL,
`plural` tinyint(1) NOT NULL,
`par` text NOT NULL,
`linkage` text NOT NULL,
`score_total` int(11) NOT NULL,
`score_vr` int(11) NOT NULL,
`score_num` int(11) NOT NULL,
`createtime` int(10) NOT NULL,
`list` tinyint(1) NOT NULL,
`cid` int(11) NOT NULL,
PRIMARY KEY (`reid`),
KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_custom` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`raply` varchar(200) NOT NULL,
`displayorder` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_data` (
`redid` bigint(20) NOT NULL AUTO_INCREMENT,
`reid` int(11) NOT NULL,
`rerid` int(11) NOT NULL,
`refid` int(11) NOT NULL,
`data` varchar(800) NOT NULL,
`displayorder` int(11) NOT NULL,
PRIMARY KEY (`redid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_fields` (
`refid` int(11) NOT NULL AUTO_INCREMENT,
`reid` int(11) NOT NULL,
`title` varchar(200) NOT NULL,
`type` varchar(20) NOT NULL,
`essential` tinyint(1) NOT NULL,
`only` tinyint(1) NOT NULL,
`bind` varchar(30) NOT NULL,
`value` varchar(255) NOT NULL,
`description` varchar(255) NOT NULL,
`image` varchar(255) NOT NULL,
`loc` tinyint(1) NOT NULL,
`displayorder` int(11) unsigned NOT NULL,
PRIMARY KEY (`refid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_info` (
`rerid` int(11) NOT NULL AUTO_INCREMENT,
`reid` int(11) NOT NULL,
`member` varchar(50) NOT NULL,
`mobile` varchar(11) NOT NULL,
`address` varchar(100) NOT NULL,
`openid` varchar(50) NOT NULL,
`thumb` text NOT NULL,
`voice` varchar(250) NOT NULL,
`revoice` varchar(250) NOT NULL,
`rethumb` varchar(250) NOT NULL,
`loc_x` varchar(20) NOT NULL,
`loc_y` varchar(20) NOT NULL,
`status` tinyint(4) NOT NULL,
`yuyuetime` int(10) NOT NULL,
`kf` varchar(50) NOT NULL,
`kfinfo` varchar(100) NOT NULL,
`var1` varchar(250) NOT NULL,
`var2` varchar(250) NOT NULL,
`var3` varchar(250) NOT NULL,
`icredit` tinyint(1) NOT NULL,
`file` text NOT NULL,
`linkage` text NOT NULL,
`kid` int(11) NOT NULL,
`commentid` int(11) NOT NULL,
`createtime` int(10) NOT NULL,
PRIMARY KEY (`rerid`),
KEY `reid` (`reid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_linkage` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`reid` int(11) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`parentid` int(11) unsigned NOT NULL,
`displayorder` int(5) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_reply` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_role` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
`roleid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_staff` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
`nickname` varchar(50) NOT NULL,
`openid` varchar(50) NOT NULL,
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
