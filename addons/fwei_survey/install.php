<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_fwei_survey` (
`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
`rid` int(10) unsigned,
`uniacid` int(10) unsigned,
`title` varchar(100),
`thumb` varchar(100),
`description` varchar(100),
`content` text,
`stime` int(10) unsigned,
`etime` int(10) unsigned,
`success_info` varchar(100),
`max_num` int(10) unsigned,
`num` int(10) unsigned,
`created` int(10) unsigned,
`credit` int(10) unsigned,
`coupon` int(10) unsigned,
PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fwei_survey_answer` (
`said` int(10) unsigned NOT NULL AUTO_INCREMENT,
`rid` int(10) unsigned,
`sid` int(10) unsigned,
`uniacid` int(10) unsigned,
`sqid` int(10) unsigned,
`uid` int(10) unsigned,
`content` text,
`created` int(10) unsigned,
PRIMARY KEY (`said`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fwei_survey_question` (
`sqid` int(10) unsigned NOT NULL AUTO_INCREMENT,
`rid` int(10) unsigned,
`uniacid` int(10) unsigned,
`sid` int(10) unsigned,
`title` varchar(100),
`description` varchar(100),
`type` varchar(10),
`extra` varchar(500),
`is_must` tinyint(1) unsigned,
`is_show` tinyint(1) unsigned,
`rule` varchar(100),
`defvalue` varchar(100),
`sort` smallint(5) unsigned,
`created` int(10) unsigned,
PRIMARY KEY (`sqid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
