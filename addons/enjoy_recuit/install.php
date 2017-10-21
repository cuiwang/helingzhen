<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_basic` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`openid` varchar(100),
`uname` varchar(20),
`sex` varchar(10),
`age` varchar(10),
`ed` varchar(10),
`mobile` varchar(20),
`email` varchar(100),
`avatar` longtext,
`present` varchar(200),
`italy` int(2) DEFAULT '0',
`createtime` varchar(30),
`param_1` varchar(50),
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_card` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`openid` varchar(100) NOT NULL,
`cname` varchar(50) NOT NULL,
`param1` varchar(50),
`param2` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_culture` (
`id` int(5) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(50),
`cname` varchar(200),
`logo` varchar(200),
`email` varchar(200),
`mobile` varchar(50),
`place` varchar(200),
`intro` longtext,
`cact` longtext,
`culture` longtext,
`quest` longtext,
`share_title` varchar(500),
`share_desc` varchar(500),
`share_icon` varchar(500),
`share_credit` int(50) DEFAULT '0',
`createtime` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_deliver` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`openid` varchar(100) NOT NULL,
`pid` int(10) NOT NULL,
`createtime` int(30),
`param_1` varchar(50),
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_exper` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`openid` varchar(100) NOT NULL,
`company` varchar(100),
`position` varchar(100),
`salary` int(10),
`stime` varchar(50),
`etime` varchar(50),
`descript` varchar(1000),
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_fans` (
`uid` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`openid` varchar(40) NOT NULL DEFAULT '',
`proxy` varchar(40) NOT NULL DEFAULT '',
`unionid` varchar(40) NOT NULL DEFAULT '',
`nickname` varchar(20) NOT NULL DEFAULT '',
`gender` varchar(2) DEFAULT '',
`state` varchar(20) NOT NULL DEFAULT '',
`city` varchar(20) NOT NULL DEFAULT '',
`country` varchar(20) NOT NULL DEFAULT '',
`avatar` varchar(500) NOT NULL DEFAULT '',
`status` tinyint(4) NOT NULL DEFAULT '0',
PRIMARY KEY (`uid`),
KEY `uniacid` (`uniacid`),
KEY `openid` (`openid`),
KEY `proxy` (`proxy`),
KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_info` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`openid` varchar(100) NOT NULL,
`birth` varchar(50),
`register` varchar(200),
`address` varchar(200),
`marriage` varchar(10),
`weight` varchar(10),
`height` varchar(10),
`school` varchar(50),
`createtime` varchar(50),
`param_1` varchar(50),
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_position` (
`id` int(8) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`pname` varchar(50),
`hot` int(10) DEFAULT '0',
`sex` varchar(5),
`ed` varchar(10),
`height` int(5),
`weight` int(5),
`type` varchar(50),
`key` varchar(50),
`num` int(10),
`place` varchar(50),
`way` varchar(10),
`descript` varchar(5000),
`competence` varchar(5000),
`views` varchar(10) DEFAULT '0',
`deliveries` varchar(10) DEFAULT '0',
`stime` varchar(50),
`etime` varchar(50),
`play` int(2) DEFAULT '0',
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_position_range` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`pid` int(10) NOT NULL,
`maxage` int(10),
`minage` int(10),
`maxsalary` int(10),
`minsalary` int(10),
`maxexper` int(10),
`minexper` int(10),
`param_1` varchar(20),
`param_2` varchar(20),
`param_3` varchar(20),
`param_4` varchar(20),
`param_5` varchar(20),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_view` (
`id` int(100) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`pid` int(10),
`openid` varchar(100),
`time` varchar(100),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
