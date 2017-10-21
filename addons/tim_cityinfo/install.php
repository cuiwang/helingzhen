<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_tim_city_cate` (
`cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(255) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`description` varchar(500) NOT NULL,
`model` tinyint(2) unsigned NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`enabled` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_tim_city_dislike` (
`dislike_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`event_id` int(11) NOT NULL,
`mem_id` int(10) NOT NULL,
PRIMARY KEY (`dislike_id`),
KEY `mem_id` (`mem_id`),
KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_tim_city_event` (
`event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`mem_id` int(10) NOT NULL,
`cate_id` int(10),
`event_title` varchar(100),
`event_content` text,
`job_name` varchar(20),
`salary` varchar(20),
`company` varchar(30),
`company_scale` varchar(20),
`create_time` int(10),
`is_agree` int(2),
`job_require` varchar(400),
`recruit_num` int(3),
`house_address` varchar(200),
`house_style` varchar(100),
`house_dolar` int(6),
`house_area` float,
`house_new` varchar(20),
`house_orient` varchar(20),
`house_floor` varchar(20),
`starttime` int(10),
`endtime` int(10),
`first_fee` double,
`is_first` tinyint(2),
`realname` varchar(100),
`mem_tel` varchar(15),
`address` varchar(100),
`short_img` varchar(200),
`fresh` int(2),
`read_num` int(10),
`location` varchar(20),
PRIMARY KEY (`event_id`),
KEY `cate_id` (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_tim_city_like` (
`like_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`event_id` int(11) NOT NULL,
`mem_id` int(10) NOT NULL,
PRIMARY KEY (`like_id`),
KEY `mem_id` (`mem_id`),
KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_tim_city_member` (
`mem_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`openid` varchar(200) NOT NULL,
`mem_name` varchar(100),
`realname` varchar(100),
`mem_photo` varchar(200),
`mem_pass` varchar(200),
`mem_tel` varchar(15),
`address` varchar(100),
`mem_dolar` double,
PRIMARY KEY (`mem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_tim_city_recharge` (
`recharge_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`recharge_no` varchar(30),
`mem_id` int(10) NOT NULL,
`recharge_dolar` double,
`create_time` int(10),
PRIMARY KEY (`recharge_id`),
KEY `mem_id` (`mem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_tim_city_set` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`appid` varchar(200),
`appsecret` varchar(200),
`logo` varchar(200),
`footer_info` varchar(200),
`title` varchar(200),
`first_perfee` double,
`default_img` varchar(200),
`fresh_fee` double,
`is_agree` tinyint(2),
`is_quyu` tinyint(2),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_tim_city_slide` (
`slide_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`slide_pic` varchar(200),
`slide_title` varchar(100),
`slide_url` varchar(200),
PRIMARY KEY (`slide_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
