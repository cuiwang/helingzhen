<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_fineness_admire` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`aid` int(10) unsigned NOT NULL,
`author` varchar(255) NOT NULL,
`openid` varchar(255) NOT NULL,
`ordersn` varchar(255) NOT NULL,
`thumb` varchar(500) NOT NULL,
`status` varchar(2) NOT NULL,
`price` decimal(10,2) NOT NULL,
`createtime` int(10) NOT NULL,
`paytype` tinyint(1) unsigned NOT NULL,
`transid` varchar(100) NOT NULL,
`tid` varchar(100) NOT NULL,
`plid` varchar(100) NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_admire_set` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`aid` int(10) unsigned NOT NULL,
`price` decimal(10,1),
`createtime` int(10) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_adv` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`pcateid` int(11),
`link` varchar(255),
`title` varchar(255),
`thumb` varchar(255),
`pid` int(10) unsigned,
`zanNum` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_adv_er` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`thumb` varchar(500) NOT NULL,
`link` varchar(500) NOT NULL,
`type` tinyint(1) unsigned NOT NULL,
`description` varchar(500) NOT NULL,
`status` varchar(2) NOT NULL,
`createtime` int(10) NOT NULL,
`weid` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_article` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`musicurl` varchar(100) NOT NULL,
`content` mediumtext,
`credit` varchar(255),
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`template` varchar(300) NOT NULL,
`templatefile` varchar(300) NOT NULL,
`author` varchar(300) NOT NULL,
`bg_music_switch` varchar(1) NOT NULL,
`clickNum` int(10) unsigned NOT NULL,
`thumb` varchar(500) NOT NULL,
`description` varchar(500) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`outLink` varchar(500),
`type` varchar(10) NOT NULL,
`kid` int(10) unsigned NOT NULL,
`rid` int(10) unsigned NOT NULL,
`tel` varchar(15) NOT NULL,
`zanNum` int(10) unsigned NOT NULL,
`iscomment` tinyint(1),
`isadmire` tinyint(2) unsigned NOT NULL,
`admiretxt` varchar(30) NOT NULL,
`openid` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_article_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`thumb` varchar(1024) NOT NULL,
`description` varchar(100) NOT NULL,
`template` varchar(300) NOT NULL,
`templatefile` varchar(300) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
`type` varchar(10) NOT NULL,
`kid` int(10) unsigned NOT NULL,
`rid` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_article_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`aid` int(10) unsigned NOT NULL,
`read` int(11) NOT NULL,
`like` int(11) NOT NULL,
`comment` int(11) NOT NULL,
`openid` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_aid` (`aid`),
KEY `idx_openid` (`openid`),
KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_article_report` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`openid` varchar(255) NOT NULL,
`aid` int(11),
`cate` varchar(255) NOT NULL,
`cons` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_comment` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`aid` int(10) unsigned NOT NULL,
`author` varchar(255) NOT NULL,
`openid` varchar(255) NOT NULL,
`thumb` varchar(500) NOT NULL,
`js_cmt_input` varchar(500) NOT NULL,
`js_cmt_reply` varchar(500) NOT NULL,
`status` varchar(2) NOT NULL,
`praise_num` int(10) unsigned NOT NULL,
`createtime` int(10) NOT NULL,
`updatetime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_fineness_sysset` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`guanzhuUrl` varchar(255),
`guanzhutitle` varchar(255),
`historyUrl` varchar(255),
`copyright` varchar(255),
`cnzz` varchar(800),
`appid` varchar(255),
`appsecret` varchar(255),
`appid_share` varchar(255),
`appsecret_share` varchar(255),
`logo` varchar(255),
`tjgzh` varchar(255),
`tjgzhUrl` varchar(255),
`isopen` varchar(1),
`title` varchar(255),
`footlogo` varchar(255),
`iscomment` varchar(1),
`isget` varchar(1),
`mchid` varchar(255),
`shkey` varchar(500),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
