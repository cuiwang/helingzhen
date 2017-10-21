<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_album` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`title` varchar(255),
`subtitle` varchar(255),
`hid` int(11),
`sort` tinyint(4) unsigned,
`jianjie` text,
`pic` text,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_bill` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`hid` int(11),
`weid` int(11),
`title` varchar(255),
`pic` varchar(255),
`pic1` varchar(255),
`pic2` varchar(255),
`pic3` varchar(255),
`pic4` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_expert_comment` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`hid` int(11),
`weid` int(11),
`title` varchar(255),
`expert_name` varchar(20),
`zhiwei` varchar(255),
`sort` tinyint(4) unsigned,
`jianjie` text,
`content` text,
`thumb` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_fell` (
`yid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`hid` int(11),
`title` varchar(255),
`sort` tinyint(4) unsigned,
`yinxiang_number` int(11) unsigned,
`isshow` tinyint(1),
`createtime` int(11),
PRIMARY KEY (`yid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_fell_record` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`hid` int(11),
`weid` int(11),
`fromuser` varchar(255),
`title` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_full_view` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`hsid` varchar(255),
`title` varchar(255),
`quanjinglink` varchar(500),
`pic_qian` varchar(1023),
`pic_hou` varchar(1023),
`pic_zuo` varchar(1023),
`pic_you` varchar(1023),
`pic_shang` varchar(1023),
`pic_xia` varchar(1023),
`sort` int(11),
`status` tinyint(4),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_head` (
`hid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`title` varchar(255),
`pic` varchar(255),
`desc` varchar(255),
`xcname` varchar(255),
`headpic` varchar(255),
`apartpic` varchar(255),
`video` varchar(255),
`dist` varchar(20),
`city` varchar(20),
`province` varchar(20),
`jw_addr` varchar(255),
`lng` varchar(12),
`lat` varchar(12),
`jianjie` text,
`xiangmu` text,
`jiaotong` text,
`addr` varchar(255),
`yyurl` varchar(500),
`xwurl` varchar(500),
`hyurl` varchar(500),
`tel` varchar(50),
`lxname` varchar(50),
`hyname` varchar(50),
`yyname` varchar(50),
`xwname` varchar(50),
`yxname` varchar(50),
`hxname` varchar(50),
`jjname` varchar(50),
`createtime` int(11),
PRIMARY KEY (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_house` (
`hsid` int(11) NOT NULL AUTO_INCREMENT,
`hid` int(11),
`weid` int(11),
`title` varchar(255),
`sid` int(11),
`louceng` smallint(1),
`mianji` varchar(255),
`fang` tinyint(4),
`ting` tinyint(4),
`sort` tinyint(4) unsigned,
`jianjie` text,
`pic` text,
`picjson` text,
`createtime` int(11),
PRIMARY KEY (`hsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_reply` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`rid` int(10) unsigned NOT NULL,
`hid` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_sub` (
`sid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`hid` int(11),
`title` varchar(255),
`sort` tinyint(4) unsigned,
`jianjie` text,
PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
