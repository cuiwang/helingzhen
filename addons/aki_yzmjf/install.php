<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uid` int(10) unsigned NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`rid` int(10) unsigned NOT NULL,
`title` varchar(16) NOT NULL,
`content` int(4) unsigned NOT NULL,
`time` varchar(16) NOT NULL,
`stime` varchar(16) NOT NULL,
`etime` varchar(16) NOT NULL,
`nick_name` varchar(32),
`send_name` varchar(32),
`min_value` int(8) unsigned NOT NULL,
`max_value` int(8) unsigned NOT NULL,
`total_num` int(4) unsigned NOT NULL,
`wishing` varchar(128),
`act_name` varchar(32),
`remark` varchar(128),
`logo_imgurl` varchar(128),
`share_content` varchar(256),
`share_url` varchar(128),
`share_imgurl` varchar(128),
`status` tinyint(4) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf_code` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uid` int(10) unsigned NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`code` varchar(64) NOT NULL,
`openid` varchar(64) NOT NULL,
`yzmjfid` int(4) unsigned NOT NULL,
`jifen` decimal(10,2),
`piciid` int(4) unsigned NOT NULL,
`type` char(1),
`time` varchar(16) NOT NULL,
`status` char(1),
PRIMARY KEY (`id`),
UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf_codenum` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uid` int(10) unsigned NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`hbid` int(4) unsigned NOT NULL,
`count` int(10) unsigned NOT NULL,
`jifen` decimal(10,2),
`type` char(1),
`usedcount` int(10) unsigned NOT NULL,
`time` varchar(16) NOT NULL,
`status` tinyint(4) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf_sendlist` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uid` int(10) unsigned NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`piciid` int(10),
`codeid` int(10),
`openid` varchar(64),
`yzmjfid` varchar(32),
`jifen` decimal(10,2),
`status` varchar(20),
`time` varchar(20),
`mark` varchar(128),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf_user` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uid` int(10) unsigned NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`openid` varchar(64),
`nickname` varchar(64),
`headimgurl` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
