<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_ewei_money_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `sum` float DEFAULT NULL,
  `info` int(11) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `prizetype` varchar(10) DEFAULT NULL,
  `award_sn` varchar(50) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `consumetime` int(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `exchange` double DEFAULT NULL,
  `useable` double DEFAULT NULL,
  `shopUrl` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_money_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `isplay` tinyint(1) DEFAULT NULL,
  `info` tinyint(1) DEFAULT NULL,
  `from_user` varchar(50) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `sum` float DEFAULT NULL,
  `remain` int(11) NOT NULL,
  `max_score` float NOT NULL,
  `alltimes` int(11) NOT NULL,
  `daytimes` int(11) NOT NULL,
  `lasttime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_money_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `isfollow` tinyint(1) NOT NULL,
  `isshow` tinyint(1) DEFAULT NULL,
  `info` int(11) DEFAULT NULL,
  `c_rate_one` tinyint(1) DEFAULT NULL,
  `c_rate_two` tinyint(1) DEFAULT NULL,
  `c_rate_three` tinyint(1) DEFAULT NULL,
  `c_rate_four` tinyint(1) DEFAULT NULL,
  `c_rate_five` tinyint(1) DEFAULT NULL,
  `c_rate_six` tinyint(1) DEFAULT NULL,
  `c_rate_seven` tinyint(1) DEFAULT NULL,
  `c_rate_eight` tinyint(1) DEFAULT NULL,
  `c_rate_nine` tinyint(1) DEFAULT NULL,
  `game_time` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `start_picurl` varchar(200) DEFAULT NULL,
  `reg_first` tinyint(1) NOT NULL,
  `max_sum` int(11) NOT NULL,
  `min_sum` int(11) NOT NULL,
  `total_remain` int(11) NOT NULL,
  `remain` int(11) NOT NULL,
  `remain_stime` int(11) NOT NULL,
  `remain_etime` int(11) NOT NULL,
  `remain_name` varchar(50) NOT NULL,
  `remain_sm` varchar(15) NOT NULL,
  `valid_time` varchar(100) NOT NULL,
  `remain_rule` varchar(100) NOT NULL,
  `rule` text NOT NULL,
  `description` text NOT NULL,
  `alltimes` int(3) unsigned NOT NULL,
  `daytimes` int(11) NOT NULL,
  `homeurl` varchar(300) NOT NULL,
  `homepicurl` varchar(200) DEFAULT NULL,
  `followurl` varchar(300) NOT NULL,
  `homename` varchar(50) NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `view_times` int(11) NOT NULL,
  `play_times` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_money_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `appid` varchar(255) DEFAULT NULL,
  `appsecret` varchar(255) DEFAULT NULL,
  `appid_share` varchar(255) DEFAULT NULL,
  `appsecret_share` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('ewei_money_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_money_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `rid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `uid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'sum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `sum` float DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'info')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `info` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `from_user` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'name')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `name` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `description` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `prizetype` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'award_sn')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `award_sn` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `consumetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `status` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'exchange')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `exchange` double DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'useable')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `useable` double DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_award',  'shopUrl')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_award')." ADD `shopUrl` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_money_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'isplay')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `isplay` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'info')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `info` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `nickname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'sum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `sum` float DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'remain')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `remain` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'max_score')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `max_score` float NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'alltimes')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `alltimes` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'daytimes')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `daytimes` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_fans',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_fans')." ADD `lasttime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_money_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'isfollow')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `isfollow` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `isshow` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'info')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `info` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'c_rate_one')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `c_rate_one` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'c_rate_two')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `c_rate_two` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'c_rate_three')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `c_rate_three` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'c_rate_four')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `c_rate_four` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'c_rate_five')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `c_rate_five` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'c_rate_six')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `c_rate_six` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'c_rate_seven')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `c_rate_seven` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'c_rate_eight')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `c_rate_eight` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'c_rate_nine')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `c_rate_nine` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'game_time')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `game_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'start_picurl')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `start_picurl` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'reg_first')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `reg_first` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'max_sum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `max_sum` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'min_sum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `min_sum` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'total_remain')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `total_remain` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'remain')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `remain` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'remain_stime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `remain_stime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'remain_etime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `remain_etime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'remain_name')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `remain_name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'remain_sm')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `remain_sm` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'valid_time')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `valid_time` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'remain_rule')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `remain_rule` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `rule` text NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `description` text NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'alltimes')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `alltimes` int(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'daytimes')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `daytimes` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'homeurl')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `homeurl` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'homepicurl')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `homepicurl` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'followurl')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `followurl` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'homename')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `homename` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `starttime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `endtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `create_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'view_times')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `view_times` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_reply',  'play_times')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_reply')." ADD `play_times` int(11) NOT NULL;");
}
if(!pdo_fieldexists('ewei_money_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_money_sysset',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_sysset')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_sysset',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_sysset')." ADD `appid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_sysset',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_sysset')." ADD `appsecret` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_sysset',  'appid_share')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_sysset')." ADD `appid_share` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_money_sysset',  'appsecret_share')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_sysset')." ADD `appsecret_share` varchar(255) DEFAULT NULL;");
}
if(!pdo_indexexists('ewei_money_sysset',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_money_sysset')." ADD KEY `indx_weid` (`weid`);");
}

?>