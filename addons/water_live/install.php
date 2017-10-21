<?php
pdo_query("DROP TABLE IF EXISTS `ims_water_live_fans`;
CREATE TABLE IF NOT EXISTS `ims_water_live_fans` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`openid` varchar(300),
`nickname` varchar(100),
`headimgurl` varchar(300),
`fname` varchar(20),
`fmobile` varchar(20),
`address` varchar(300),
`flocation` varchar(300),
`balance` float DEFAULT '0',
`signtime` datetime,
`addtime` datetime,
`state` int(2) DEFAULT '0',
`status` int(2) DEFAULT '0',
`sharetime` datetime,
`sex` int(2) DEFAULT '0',
`fage` int(11) DEFAULT '18',
`faddress` varchar(300),
`fdesc` varchar(200),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_live_follow`;
CREATE TABLE IF NOT EXISTS `ims_water_live_follow` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`thefansid` int(11),
`fansid` int(11),
`addtime` datetime,
`state` int(2) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_live_like`;
CREATE TABLE IF NOT EXISTS `ims_water_live_like` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`topic` int(11),
`sectionid` int(11),
`fansid` int(11),
`nickname` varchar(300),
`headimgurl` varchar(300),
`openid` varchar(300),
`addtime` datetime,
`state` int(2) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_live_order`;
CREATE TABLE IF NOT EXISTS `ims_water_live_order` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`orderno` varchar(50),
`type` varchar(20),
`sectionid` int(11),
`sfansid` int(11),
`sopenid` varchar(300),
`fee` float DEFAULT '0',
`fansid` int(11),
`openid` varchar(300),
`nickname` varchar(300),
`headimgurl` varchar(300),
`addtime` datetime,
`msg` varchar(100),
`state` int(2) DEFAULT '0',
`paytime` datetime,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_live_reply`;
CREATE TABLE IF NOT EXISTS `ims_water_live_reply` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`sectionid` int(11),
`datato` int(11),
`toname` varchar(100),
`datafrom` int(11),
`nickname` varchar(100),
`content` varchar(300),
`addtime` datetime,
`state` int(2) DEFAULT '2',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_live_section`;
CREATE TABLE IF NOT EXISTS `ims_water_live_section` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`topicid` int(11),
`fansid` int(11),
`openid` varchar(300),
`nickname` varchar(300),
`headimgurl` varchar(300),
`content` varchar(3000),
`imgs` varchar(2000),
`addtime` datetime,
`toptime` datetime,
`settop` int(2) DEFAULT '0',
`state` int(2) DEFAULT '2',
`status` int(2) DEFAULT '0',
`scansum` int(11) DEFAULT '0',
`sharetitle` varchar(100),
`sharedesc` varchar(200),
`address` varchar(200),
`audiosrc` varchar(300),
`audiosid` varchar(300),
`audiotime` int(10) DEFAULT '0',
`vediosrc` varchar(300),
`fname` varchar(100),
`fmobile` varchar(50),
`showtitle` text,
`fee` float DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_live_talk`;
CREATE TABLE IF NOT EXISTS `ims_water_live_talk` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`thefansid` int(11),
`fansid` int(11),
`addtime` datetime,
`state` int(2) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_water_live_topic`;
CREATE TABLE IF NOT EXISTS `ims_water_live_topic` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`typeid` int(11),
`stitle` varchar(100) NOT NULL,
`sdesc` varchar(500),
`simg` varchar(300),
`hot` int(2) NOT NULL DEFAULT '0',
`new` int(2) NOT NULL DEFAULT '2',
`sindex` int(2) NOT NULL DEFAULT '0',
`state` int(2) NOT NULL DEFAULT '2',
`isaudio` int(2) DEFAULT '0',
`isvedio` int(2) DEFAULT '0',
`addtitle` varchar(50) DEFAULT '发表帖子',
`isadmin` int(2) DEFAULT '0',
`shorttitle` varchar(50) DEFAULT '简称',
`isgetinfo` int(2) DEFAULT '0',
`placeholder` varchar(100) DEFAULT '说点吧',
`maxchar` int(11) DEFAULT '800',
`issell` int(2) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
