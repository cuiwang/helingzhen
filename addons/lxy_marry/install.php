<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_lxy_marry_info` (
`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
`weid` bigint(20) unsigned,
`fromuser` varchar(32),
`sid` bigint(20) unsigned,
`name` varchar(25),
`tel` varchar(25),
`rs` smallint(1),
`zhufu` varchar(255),
`ctime` datetime,
`type` tinyint(1),
PRIMARY KEY (`id`),
KEY `idx_sid_openid` (`sid`,`fromuser`),
KEY `idx_sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_marry_list` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) unsigned,
`title` varchar(50),
`art_pic` varchar(255),
`bg_pic` varchar(255) NOT NULL,
`donghua_pic` varchar(255),
`suolue_pic` varchar(255),
`xl_name` varchar(255),
`xn_name` varchar(255),
`is_front` varchar(255),
`tel` varchar(25),
`hy_time` datetime,
`dist` varchar(20),
`city` varchar(20),
`province` varchar(20),
`hy_addr` varchar(255),
`jw_addr` varchar(255),
`lng` varchar(12),
`lat` varchar(12),
`video` varchar(255),
`music` varchar(255),
`hs_pic` text,
`pwd` varchar(255),
`word` varchar(500),
`erweima_pic` varchar(255),
`copyright` varchar(512),
`createtime` int(11) unsigned,
`sendtitle` varchar(255) NOT NULL,
`senddescription` varchar(500) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_marry_reply` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`rid` int(10) unsigned NOT NULL,
`marryid` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
