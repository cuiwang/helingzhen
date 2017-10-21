<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_fenlei` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`name` varchar(200) NOT NULL,
`thumb` varchar(200),
`displayorder` int(10) NOT NULL,
`parentid` int(10) NOT NULL,
`enabled` tinyint(1) NOT NULL,
`nid` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_flash` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`url` varchar(200) NOT NULL,
`attachment` varchar(100) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_news` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`tel` varchar(100) NOT NULL,
`weixin` varchar(100) NOT NULL,
`fenleiid` int(10),
`address` varchar(200),
`status` int(1) NOT NULL,
`ding` int(1) NOT NULL,
`slei` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_setting` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`thumb` varchar(1024) NOT NULL,
`name` varchar(100),
`gao` int(20) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_tougao` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`tel` varchar(100) NOT NULL,
`address` varchar(200),
`status` int(1) NOT NULL,
`weixin` varchar(100),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
