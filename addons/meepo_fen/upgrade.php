<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_meepo_fen_basic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_fen_click` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_fen_coupon` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `couponid` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_fen_ip_log` (
  `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uniacid` mediumint(8) NOT NULL,
  `time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`log_id`),
  KEY `uid` (`uid`),
  KEY `time` (`time`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_fen_reply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `old_basic_id` int(11) NOT NULL,
  `new_basic_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_fen_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `pan_name` varchar(150) NOT NULL,
  `fans_num` int(11) NOT NULL,
  `couponid` int(5) NOT NULL,
  `set` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_fen_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `uid` int(11) unsigned NOT NULL,
  `ecs_userid` int(11) unsigned NOT NULL,
  `father` int(11) unsigned NOT NULL,
  `couponid` int(11) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `father` (`father`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('meepo_fen_basic',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_basic')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_fen_basic',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_basic')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_basic',  'content')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_basic')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_click',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_fen_click',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD `uniacid` mediumint(8) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_click',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD `ip` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_click',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD `openid` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_click',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD `time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_click',  'num')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD `num` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_fen_click',  'success')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD `success` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_click',  'father')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD `father` int(11) NOT NULL;");
}
if(!pdo_indexexists('meepo_fen_click',  'father')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD KEY `father` (`father`);");
}
if(!pdo_indexexists('meepo_fen_click',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_click')." ADD KEY `ip` (`ip`);");
}
if(!pdo_fieldexists('meepo_fen_coupon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_coupon')." ADD `id` tinyint(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_fen_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_coupon')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_coupon',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_coupon')." ADD `couponid` int(5) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_ip_log',  'log_id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_ip_log')." ADD `log_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_fen_ip_log',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_ip_log')." ADD `uid` mediumint(8) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_fen_ip_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_ip_log')." ADD `uniacid` mediumint(8) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_ip_log',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_ip_log')." ADD `time` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_fen_ip_log',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_ip_log')." ADD `ip` varchar(60) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('meepo_fen_ip_log',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_ip_log')." ADD KEY `uid` (`uid`);");
}
if(!pdo_indexexists('meepo_fen_ip_log',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_ip_log')." ADD KEY `time` (`time`);");
}
if(!pdo_indexexists('meepo_fen_ip_log',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_ip_log')." ADD KEY `ip` (`ip`);");
}
if(!pdo_fieldexists('meepo_fen_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_reply')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_fen_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_reply')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_reply',  'old_basic_id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_reply')." ADD `old_basic_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_reply',  'new_basic_id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_reply')." ADD `new_basic_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_set')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_fen_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_set')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_set',  'pan_name')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_set')." ADD `pan_name` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_set',  'fans_num')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_set')." ADD `fans_num` int(11) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_set',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_set')." ADD `couponid` int(5) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_set',  'set')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_set')." ADD `set` text;");
}
if(!pdo_fieldexists('meepo_fen_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_user')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_fen_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_user')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_user')." ADD `uid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_user',  'ecs_userid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_user')." ADD `ecs_userid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_user',  'father')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_user')." ADD `father` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_user',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_user')." ADD `couponid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('meepo_fen_user',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_user')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('meepo_fen_user',  'father')) {
	pdo_query("ALTER TABLE ".tablename('meepo_fen_user')." ADD KEY `father` (`father`);");
}

?>