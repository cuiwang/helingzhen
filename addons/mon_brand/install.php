<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_brand` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) unsigned,
`bname` varchar(50) NOT NULL,
`intro` varchar(1000) NOT NULL,
`intro2` varchar(1000) NOT NULL,
`video_name` varchar(100),
`video_url` varchar(100),
`createtime` int(11) unsigned,
`pptname` varchar(100),
`ppt1` varchar(100),
`ppt2` varchar(100),
`ppt3` varchar(100),
`pic` varchar(100) NOT NULL,
`visitsCount` int(11),
`btnName` varchar(20),
`btnUrl` varchar(100),
`btnName2` varchar(20),
`btnUrl2` varchar(100),
`btnName3` varchar(20),
`btnUrl3` varchar(100),
`showMsg` int(1),
`tel` varchar(20),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_brand_image` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`bid` int(11) unsigned,
`title` varchar(50) NOT NULL,
`url` varchar(200) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_brand_message` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`createtime` int(11) unsigned,
`bid` int(11) unsigned,
`name` varchar(50) NOT NULL,
`tel` varchar(100) NOT NULL,
`content` varchar(1000) NOT NULL,
`address` varchar(200) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_brand_note` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`bid` int(11) unsigned,
`title` varchar(50) NOT NULL,
`note` varchar(1000),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_brand_product` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`bid` int(11) unsigned,
`pname` varchar(200) NOT NULL,
`image` varchar(200) NOT NULL,
`summary` varchar(200) NOT NULL,
`intro` varchar(1000),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_brand_reply` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`rid` int(10) unsigned NOT NULL,
`bid` int(10) unsigned NOT NULL,
`new_pic` varchar(200) NOT NULL,
`news_content` varchar(500) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
