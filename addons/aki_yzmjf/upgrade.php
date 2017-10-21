<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `rid` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(16) NOT NULL DEFAULT '1',
  `content` int(4) unsigned NOT NULL DEFAULT '1',
  `time` varchar(16) NOT NULL DEFAULT '1',
  `stime` varchar(16) NOT NULL DEFAULT '1',
  `etime` varchar(16) NOT NULL DEFAULT '1',
  `nick_name` varchar(32) DEFAULT '',
  `send_name` varchar(32) DEFAULT '',
  `min_value` int(8) unsigned NOT NULL DEFAULT '0',
  `max_value` int(8) unsigned NOT NULL DEFAULT '0',
  `total_num` int(4) unsigned NOT NULL DEFAULT '1',
  `wishing` varchar(128) DEFAULT '',
  `act_name` varchar(32) DEFAULT '',
  `remark` varchar(128) DEFAULT '',
  `logo_imgurl` varchar(128) DEFAULT '',
  `share_content` varchar(256) DEFAULT '',
  `share_url` varchar(128) DEFAULT '',
  `share_imgurl` varchar(128) DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `code` varchar(64) NOT NULL DEFAULT '',
  `openid` varchar(64) NOT NULL DEFAULT '',
  `yzmjfid` int(4) unsigned NOT NULL DEFAULT '0',
  `jifen` decimal(10,2) DEFAULT '0.00',
  `piciid` int(4) unsigned NOT NULL DEFAULT '0',
  `type` char(1) DEFAULT '',
  `time` varchar(16) NOT NULL DEFAULT '',
  `status` char(1) DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf_codenum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `hbid` int(4) unsigned NOT NULL DEFAULT '1',
  `count` int(10) unsigned NOT NULL DEFAULT '1',
  `jifen` decimal(10,2) DEFAULT '0.00',
  `type` char(1) DEFAULT '',
  `usedcount` int(10) unsigned NOT NULL DEFAULT '0',
  `time` varchar(16) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf_sendlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `piciid` int(10) DEFAULT '0',
  `codeid` int(10) DEFAULT '0',
  `openid` varchar(64) DEFAULT '',
  `yzmjfid` varchar(32) DEFAULT '',
  `jifen` decimal(10,2) DEFAULT '0.00',
  `status` varchar(20) DEFAULT '',
  `time` varchar(20) DEFAULT '1',
  `mark` varchar(128) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_aki_yzmjf_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `openid` varchar(64) DEFAULT '',
  `nickname` varchar(64) DEFAULT '',
  `headimgurl` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('aki_yzmjf',  'id')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('aki_yzmjf',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf',  'title')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `title` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf',  'content')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `content` int(4) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf',  'time')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `time` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf',  'stime')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `stime` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf',  'etime')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `etime` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf',  'nick_name')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `nick_name` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf',  'send_name')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `send_name` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf',  'min_value')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `min_value` int(8) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('aki_yzmjf',  'max_value')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `max_value` int(8) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('aki_yzmjf',  'total_num')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `total_num` int(4) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf',  'wishing')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `wishing` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf',  'act_name')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `act_name` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `remark` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf',  'logo_imgurl')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `logo_imgurl` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `share_content` varchar(256) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `share_url` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf',  'share_imgurl')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `share_imgurl` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf',  'status')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf')." ADD `status` tinyint(4) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'id')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'code')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `code` varchar(64) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `openid` varchar(64) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'yzmjfid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `yzmjfid` int(4) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'jifen')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `jifen` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'piciid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `piciid` int(4) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'type')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `type` char(1) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'time')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `time` varchar(16) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_code',  'status')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD `status` char(1) DEFAULT '';");
}
if(!pdo_indexexists('aki_yzmjf_code',  'code')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_code')." ADD UNIQUE KEY `code` (`code`);");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'id')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'hbid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `hbid` int(4) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'count')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `count` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'jifen')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `jifen` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'type')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `type` char(1) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'usedcount')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `usedcount` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'time')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `time` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_codenum',  'status')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_codenum')." ADD `status` tinyint(4) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'piciid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `piciid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'codeid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `codeid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `openid` varchar(64) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'yzmjfid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `yzmjfid` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'jifen')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `jifen` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'status')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `status` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'time')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `time` varchar(20) DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_sendlist',  'mark')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_sendlist')." ADD `mark` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('aki_yzmjf_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_user')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_user')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('aki_yzmjf_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_user')." ADD `openid` varchar(64) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_user')." ADD `nickname` varchar(64) DEFAULT '';");
}
if(!pdo_fieldexists('aki_yzmjf_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('aki_yzmjf_user')." ADD `headimgurl` varchar(255) DEFAULT '';");
}

?>