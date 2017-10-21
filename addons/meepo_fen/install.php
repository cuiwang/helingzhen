<?php
if(!pdo_tableexists('_meepo_fen_basic')){
	$sql = "CREATE TABLE `ims_meepo_fen_basic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_fen_click')){
	$sql = "CREATE TABLE `ims_meepo_fen_click` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` mediumint(8) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `openid` varchar(20) NOT NULL,
  `time` int(11) NOT NULL,
  `num` int(11) NOT NULL DEFAULT '0',
  `success` tinyint(2) NOT NULL,
  `father` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `father` (`father`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_fen_ip_log')){
	$sql = "CREATE TABLE `ims_meepo_fen_ip_log` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uniacid` mediumint(8) NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `uid` (`uid`),
  KEY `time` (`time`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_fen_reply')){
	$sql = "CREATE TABLE `ims_meepo_fen_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `old_basic_id` int(11) NOT NULL,
  `new_basic_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_fen_set')){
	$sql = "CREATE TABLE `ims_meepo_fen_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `pan_name` varchar(150) NOT NULL,
  `fans_num` int(11) NOT NULL,
  `couponid` int(5) NOT NULL,
  `set` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_fen_user')){
	$sql = "CREATE TABLE `ims_meepo_fen_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `ecs_userid` int(11) unsigned NOT NULL,
  `father` int(11) unsigned NOT NULL,
  `couponid` int(11) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `father` (`father`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}
if(!pdo_fieldexists('meepo_fen_set','set')){
	pdo_query("ALTER TABLE ".tablename('meepo_fen_set')." ADD COLUMN `set` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `couponid`");
}