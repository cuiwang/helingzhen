<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `oauth_openid` varchar(50) DEFAULT '',
  `avatar` varchar(200) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `realname` varchar(50) DEFAULT '',
  `mobile` varchar(50) DEFAULT '',
  `qq` varchar(50) DEFAULT '',
  `email` varchar(50) DEFAULT '',
  `address` varchar(50) DEFAULT '',
  `status` int(1) DEFAULT '0',
  `wordcount` int(11) DEFAULT '0',
  `sharecount` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0' COMMENT '剩余游戏次数',
  `createtime` int(11) DEFAULT '0',
  `award` int(11) DEFAULT '0',
  `astatus` tinyint(1) DEFAULT '0',
  `choice` tinyint(1) DEFAULT '0',
  `lasttime` int(11) DEFAULT '0' COMMENT '最后增长时间',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`,`rid`),
  KEY `award` (`award`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_record` (
  `rid` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `word` varchar(11) DEFAULT '',
  `createtime` varchar(20) DEFAULT NULL,
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_share` (
  `openid` varchar(50) DEFAULT '',
  `avatar` varchar(200) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `pid` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  KEY `openid` (`openid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_slog` (
  `rid` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('junsion_netcollect_player',  'id')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `openid` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'oauth_openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `oauth_openid` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `avatar` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `nickname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `realname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `mobile` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `qq` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'email')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `email` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'address')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `address` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'status')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `status` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'wordcount')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `wordcount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'sharecount')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `sharecount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'times')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `times` int(11) DEFAULT '0' COMMENT '剩余游戏次数';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'award')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `award` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'astatus')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `astatus` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'choice')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `choice` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_player',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `lasttime` int(11) DEFAULT '0' COMMENT '最后增长时间';");
}
if(!pdo_indexexists('junsion_netcollect_player',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD KEY `openid` (`openid`,`rid`);");
}
if(!pdo_indexexists('junsion_netcollect_player',  'award')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD KEY `award` (`award`);");
}
if(!pdo_fieldexists('junsion_netcollect_record',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_record')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_record',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_record')." ADD `pid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_record',  'word')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_record')." ADD `word` varchar(11) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_record')." ADD `createtime` varchar(20) DEFAULT NULL;");
}
if(!pdo_indexexists('junsion_netcollect_record',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_record')." ADD KEY `pid` (`pid`);");
}
if(!pdo_fieldexists('junsion_netcollect_share',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `openid` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_share',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `avatar` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_share',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `nickname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('junsion_netcollect_share',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `pid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_share',  'times')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_share',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_share',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('junsion_netcollect_share',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD KEY `openid` (`openid`,`pid`);");
}
if(!pdo_fieldexists('junsion_netcollect_slog',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_slog')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_slog',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_slog')." ADD `pid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_netcollect_slog',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_slog')." ADD `openid` varchar(50) DEFAULT '';");
}
if(!pdo_indexexists('junsion_netcollect_slog',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_netcollect_slog')." ADD KEY `openid` (`openid`);");
}

?>