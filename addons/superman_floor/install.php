<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_superman_floor` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`awardprompt` text NOT NULL,
`currentprompt` text NOT NULL,
`floorprompt` text NOT NULL,
`setting` text NOT NULL,
`rid` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_floor_172` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`dateline` int(10) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_floor_award` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`rid` int(10) unsigned NOT NULL,
`floors` varchar(1000) NOT NULL,
`title` varchar(255) NOT NULL,
`description` text NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_floor_winner` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`rid` int(10) unsigned NOT NULL,
`floor` int(4) unsigned NOT NULL,
`uid` int(4) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
`award_id` int(10) unsigned NOT NULL,
`ip` char(15) NOT NULL,
`status` tinyint(4) NOT NULL,
`realname` varchar(128) NOT NULL,
`mobile` varchar(20) NOT NULL,
`qq` varchar(20) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `rid_floor_UNIQUE` (`rid`,`floor`),
KEY `indx_uniacid` (`uniacid`),
KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
