<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_nsign_add` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`shop` text NOT NULL,
`title` text NOT NULL,
`description` text NOT NULL,
`thumb` text NOT NULL,
`content` text NOT NULL,
`type` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_nsign_prize` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`name` text NOT NULL,
`type` text NOT NULL,
`award` text NOT NULL,
`time` int(11) NOT NULL,
`num` int(11) NOT NULL,
`status` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_nsign_record` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`username` text NOT NULL,
`today_rank` int(11) NOT NULL,
`sign_time` int(11) NOT NULL,
`last_sign_time` int(11) NOT NULL,
`continue_sign_days` int(11) NOT NULL,
`maxcontinue_sign_days` int(11) NOT NULL,
`total_sign_num` int(11) NOT NULL,
`maxtotal_sign_num` int(11) NOT NULL,
`first_sign_days` int(11) NOT NULL,
`maxfirst_sign_days` int(11) NOT NULL,
`credit` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_nsign_reply` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`title` text NOT NULL,
`picture` text NOT NULL,
`description` text NOT NULL,
`content` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
