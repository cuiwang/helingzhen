<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_amouse_house` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`title` varchar(25) NOT NULL,
`price` varchar(100) NOT NULL,
`square_price` varchar(100) NOT NULL,
`area` varchar(100) NOT NULL,
`house_type` varchar(100) NOT NULL,
`floor` varchar(100) NOT NULL,
`orientation` varchar(100) NOT NULL,
`type` varchar(2) NOT NULL,
`status` varchar(2) NOT NULL,
`recommed` int(1) NOT NULL,
`contacts` varchar(100) NOT NULL,
`phone` varchar(13) NOT NULL,
`introduction` text NOT NULL,
`openid` varchar(25) NOT NULL,
`createtime` int(10) NOT NULL,
`thumb3` varchar(1000) NOT NULL,
`thumb4` varchar(1000) NOT NULL,
`thumb1` varchar(1000) NOT NULL,
`thumb2` varchar(1000) NOT NULL,
`place` varchar(1000) NOT NULL,
`lat` varchar(1000) NOT NULL,
`lng` varchar(1000) NOT NULL,
`location_p` varchar(1000) NOT NULL,
`location_c` varchar(1000) NOT NULL,
`location_a` varchar(1000) NOT NULL,
`brokerage` varchar(1000) NOT NULL,
`jjrmobile` varchar(13),
`broker` varchar(200),
`isshow` int(10),
`defcity` varchar(1000),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_amouse_house_slide` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`url` varchar(200) NOT NULL,
`slide` varchar(200) NOT NULL,
`listorder` int(10) unsigned NOT NULL,
`isshow` tinyint(1) unsigned NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
