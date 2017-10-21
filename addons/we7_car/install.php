<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_we7car_album` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`type_id` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`thumb` varchar(100) NOT NULL,
`content` varchar(1000) NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`isview` tinyint(1) unsigned NOT NULL,
`type` tinyint(1) unsigned NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_album_photo` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`albumid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`description` varchar(1000) NOT NULL,
`attachment` varchar(100) NOT NULL,
`ispreview` tinyint(1) unsigned NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid` (`weid`),
KEY `ims_albumid_order` (`albumid`,`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_brand` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`listorder` int(11) NOT NULL,
`title` varchar(25) NOT NULL,
`officialweb` varchar(100) NOT NULL,
`logo` varchar(100) NOT NULL,
`description` text NOT NULL,
`createtime` int(10) NOT NULL,
`status` tinyint(4) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_weid_order` (`weid`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_care` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`from_user` varchar(50) NOT NULL,
`brand_id` int(10) unsigned NOT NULL,
`brand_cn` varchar(50) NOT NULL,
`series_id` int(10) unsigned NOT NULL,
`series_cn` varchar(50) NOT NULL,
`type_id` int(10) unsigned NOT NULL,
`type_cn` varchar(50) NOT NULL,
`car_note` varchar(50) NOT NULL,
`car_no` varchar(50) NOT NULL,
`car_userName` varchar(50) NOT NULL,
`car_mobile` varchar(15) NOT NULL,
`car_startTime` int(10) unsigned NOT NULL,
`car_photo` varchar(100) NOT NULL,
`car_insurance_lastDate` int(10) unsigned NOT NULL,
`car_insurance_lastCost` mediumint(10) unsigned NOT NULL,
`car_care_mileage` int(11) NOT NULL,
`car_care_lastDate` int(10) unsigned NOT NULL,
`car_care_lastCost` mediumint(10) unsigned NOT NULL,
`createtime` int(10) NOT NULL,
`isshow` tinyint(1) NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid` (`weid`),
KEY `ims_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_message_list` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`nickname` varchar(30),
`info` varchar(200),
`fid` int(11),
`isshow` tinyint(1),
`create_time` int(11),
`from_user` varchar(50),
PRIMARY KEY (`id`),
KEY `ims_weid` (`weid`),
KEY `ims_fid_time` (`fid`,`create_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_message_set` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`thumb` varchar(200) NOT NULL,
`status` int(1) NOT NULL,
`isshow` tinyint(1) NOT NULL,
`create_time` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_news` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`iscommend` tinyint(1) NOT NULL,
`ishot` tinyint(1) unsigned NOT NULL,
`category_id` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`template` varchar(100) NOT NULL,
`description` varchar(1000) NOT NULL,
`content` text NOT NULL,
`thumb` varchar(100) NOT NULL,
`source` varchar(50) NOT NULL,
`author` varchar(50) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_category_id` (`category_id`),
KEY `ims_weid` (`weid`),
KEY `ims_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_news_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`description` varchar(100) NOT NULL,
`thumb` varchar(60) NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid_title` (`weid`,`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_order_data` (
`id` bigint(20) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`srid` int(11) NOT NULL,
`sfid` int(11) NOT NULL,
`data` varchar(500) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_sid` (`sid`),
KEY `ims_srid` (`srid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_order_fields` (
`fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
`sid` int(11) unsigned NOT NULL,
`title` varchar(200) NOT NULL,
`type` varchar(20) NOT NULL,
`value` varchar(300) NOT NULL,
PRIMARY KEY (`fid`),
KEY `ims_sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_order_list` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`sid` int(10) unsigned NOT NULL,
`yytype` tinyint(11) NOT NULL,
`from_user` varchar(50) NOT NULL,
`username` varchar(50) NOT NULL,
`mobile` varchar(15) NOT NULL,
`brand` int(10) unsigned NOT NULL,
`brand_cn` varchar(15) NOT NULL,
`serie` int(10) unsigned NOT NULL,
`serie_cn` varchar(15) NOT NULL,
`type` int(10) unsigned NOT NULL,
`type_cn` varchar(15) NOT NULL,
`contact` varchar(50) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
`createtime` int(10) NOT NULL,
`note` varchar(255) NOT NULL,
`status` tinyint(4) NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_sid` (`sid`),
KEY `ims_createtime` (`createtime`),
KEY `ims_dateline` (`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_order_set` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`yytype` tinyint(2) NOT NULL,
`pertotal` tinyint(3) unsigned NOT NULL,
`description` varchar(500) NOT NULL,
`start_time` int(10) unsigned NOT NULL,
`end_time` int(10) unsigned NOT NULL,
`address` varchar(200) NOT NULL,
`mobile` varchar(30) NOT NULL,
`location_x` float NOT NULL,
`location_y` float NOT NULL,
`topbanner` varchar(150),
`isshow` tinyint(3) unsigned NOT NULL,
`note` varchar(50) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid` (`weid`),
KEY `ims_createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_series` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`bid` int(11) NOT NULL,
`listorder` int(11) NOT NULL,
`title` varchar(50) NOT NULL,
`subtitle` varchar(20) NOT NULL,
`thumb` varchar(200) NOT NULL,
`description` text NOT NULL,
`createtime` int(10) NOT NULL,
`status` tinyint(4) NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid_order` (`weid`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_services` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`listorder` int(11) NOT NULL,
`kefuname` varchar(50) NOT NULL,
`headthumb` varchar(200) NOT NULL,
`kefutel` varchar(20) NOT NULL,
`pre_sales` tinyint(2) NOT NULL,
`aft_sales` tinyint(2) NOT NULL,
`description` text NOT NULL,
`createtime` int(10) NOT NULL,
`status` tinyint(4) NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_set` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`description` varchar(1000) NOT NULL,
`address` varchar(60) NOT NULL,
`opentime` varchar(60) NOT NULL,
`pre_consult` varchar(60) NOT NULL,
`aft_consult` varchar(60) NOT NULL,
`thumbArr` varchar(500) NOT NULL,
`weicar_logo` varchar(200) NOT NULL,
`shop_logo` varchar(200) NOT NULL,
`guanhuai_thumb` varchar(200) NOT NULL,
`typethumb` varchar(70) NOT NULL,
`yuyue1thumb` varchar(70) NOT NULL,
`yuyue2thumb` varchar(70) NOT NULL,
`kefuthumb` varchar(70) NOT NULL,
`messagethumb` varchar(70) NOT NULL,
`carethumb` varchar(70) NOT NULL,
`status` int(1) NOT NULL,
`isshow` tinyint(1) NOT NULL,
`tools` varchar(50) NOT NULL,
`create_time` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_we7car_type` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`listorder` int(11) NOT NULL,
`title` varchar(50) NOT NULL,
`weid` int(11) NOT NULL,
`bid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`pyear` varchar(10) NOT NULL,
`price1` varchar(50) NOT NULL,
`price2` varchar(50) NOT NULL,
`thumb` varchar(100) NOT NULL,
`thumbArr` varchar(500) NOT NULL,
`description` varchar(512) NOT NULL,
`output` varchar(10) NOT NULL,
`gearnum` varchar(10) NOT NULL,
`gear_box` varchar(30) NOT NULL,
`xiangceid` int(11) NOT NULL,
`createtime` int(10) NOT NULL,
`status` tinyint(4) NOT NULL,
PRIMARY KEY (`id`),
KEY `ims_weid_order` (`weid`,`listorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
