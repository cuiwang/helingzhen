<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_han_sheka_list` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11),
`classid` int(10),
`title` varchar(50),
`thumb` varchar(200) NOT NULL,
`description` varchar(255) NOT NULL,
`music` varchar(200) NOT NULL,
`cardbg` varchar(200) NOT NULL,
`params` text NOT NULL,
`tempid` int(11) NOT NULL,
`cardid` varchar(100) NOT NULL,
`is_index` tinyint(2) NOT NULL,
`lang` tinyint(3) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_han_sheka_reply` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`title` varchar(50),
`weid` int(11),
`cid` int(11) NOT NULL,
`is_show` tinyint(2) NOT NULL,
`follow_switch` tinyint(2) NOT NULL,
`zdyurl` varchar(255),
`f_logo` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_han_sheka_zhufu` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11),
`cardfrom` varchar(200) NOT NULL,
`cardto` varchar(200) NOT NULL,
`cardbody` varchar(1000) NOT NULL,
`cid` int(11) NOT NULL,
`cardto_left` varchar(100) NOT NULL,
`cardto_top` varchar(100) NOT NULL,
`cardbody_width` varchar(100) NOT NULL,
`cardbody_left` varchar(100) NOT NULL,
`cardbody_top` varchar(100) NOT NULL,
`cardfrom_left` varchar(100) NOT NULL,
`cardfrom_top` varchar(100) NOT NULL,
`panel_top` varchar(100) NOT NULL,
`panel_left` varchar(100) NOT NULL,
`panel_width` varchar(100) NOT NULL,
`panel_height` varchar(100) NOT NULL,
`panel_color` varchar(100) NOT NULL,
`panel_bg` varchar(100) NOT NULL,
`panel_alpha` varchar(100) NOT NULL,
`lang` tinyint(3) NOT NULL,
`font_size` int(3) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
