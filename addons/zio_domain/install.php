<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_zio_domain` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`domain` varchar(40) NOT NULL,
`title` varchar(50) NOT NULL,
`entry` varchar(255) NOT NULL,
`url` varchar(255) NOT NULL,
`module` varchar(20) NOT NULL,
`eid` int(11) NOT NULL,
`ext` text,
`type` tinyint(3) NOT NULL,
`all` tinyint(3) NOT NULL,
`redirect` tinyint(3) NOT NULL,
`status` tinyint(3) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_uniacid` (`uniacid`),
KEY `idx_domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_zio_domain_group` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(40) NOT NULL,
`host` varchar(80) NOT NULL,
`groupid` int(11) NOT NULL,
`isaccount` tinyint(3) NOT NULL,
`limit` int(10) unsigned NOT NULL,
`ext` text,
`type` tinyint(3) NOT NULL,
`domain` tinyint(3) NOT NULL,
`right` tinyint(3) NOT NULL,
`status` tinyint(3) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_groupid` (`groupid`),
KEY `idx_isaccount` (`isaccount`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
