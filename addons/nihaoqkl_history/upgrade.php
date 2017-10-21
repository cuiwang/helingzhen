<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_addons_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned DEFAULT '0',
  `title` varchar(30) DEFAULT '',
  `summary` varchar(100) DEFAULT '',
  `url` varchar(300) DEFAULT '',
  `cover` varchar(100) DEFAULT '' COMMENT '封面',
  `create_time` int(10) unsigned DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT '0',
  `sort` tinyint(3) unsigned DEFAULT '0',
  `status` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_addons_history_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `keyword` varchar(30) DEFAULT '',
  `title` varchar(30) DEFAULT '',
  `sort` tinyint(2) unsigned DEFAULT '0',
  `status` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_addons_history_mode` (
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `mode` tinyint(30) unsigned DEFAULT '0' COMMENT '0无封面 1表示有封面',
  PRIMARY KEY (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ice_yzmhb` (
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
CREATE TABLE IF NOT EXISTS `ims_ice_yzmhb_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `code` varchar(64) NOT NULL DEFAULT '1',
  `openid` varchar(64) NOT NULL DEFAULT '',
  `yzmhbid` int(4) unsigned NOT NULL DEFAULT '1',
  `piciid` int(4) unsigned NOT NULL DEFAULT '1',
  `type` char(1) DEFAULT '1',
  `time` varchar(16) NOT NULL DEFAULT '1',
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ice_yzmhb_codenum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `hbid` int(4) unsigned NOT NULL DEFAULT '1',
  `count` int(10) unsigned NOT NULL DEFAULT '1',
  `type` char(1) DEFAULT '0',
  `usedcount` int(10) unsigned NOT NULL DEFAULT '0',
  `time` varchar(16) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ice_yzmhb_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `yzmhbid` varchar(32) DEFAULT '',
  `uniacid` int(10) DEFAULT '0',
  `prizeodds` int(11) DEFAULT NULL,
  `prizesum` int(11) DEFAULT NULL,
  `prizename` varchar(64) NOT NULL DEFAULT '',
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `money` varchar(16) DEFAULT '',
  `money_range` varchar(16) DEFAULT '',
  `time` varchar(32) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ice_yzmhb_sendlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `codeid` int(10) DEFAULT '1',
  `openid` varchar(64) DEFAULT '',
  `packetid` varchar(32) DEFAULT '',
  `yzmhbid` varchar(32) DEFAULT '',
  `money` varchar(64) DEFAULT '',
  `type` char(20) DEFAULT '',
  `status` varchar(20) DEFAULT '',
  `time` varchar(20) DEFAULT '1',
  `mark` varchar(128) DEFAULT '',
  `totalmember` int(10) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ice_yzmhb_user` (
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
if(!pdo_fieldexists('addons_history',  'id')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('addons_history',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('addons_history',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `cid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('addons_history',  'title')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `title` varchar(30) DEFAULT '';");
}
if(!pdo_fieldexists('addons_history',  'summary')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `summary` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('addons_history',  'url')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `url` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('addons_history',  'cover')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `cover` varchar(100) DEFAULT '' COMMENT '封面';");
}
if(!pdo_fieldexists('addons_history',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `create_time` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('addons_history',  'update_time')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `update_time` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('addons_history',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `sort` tinyint(3) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('addons_history',  'status')) {
	pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `status` tinyint(1) unsigned DEFAULT '1';");
}
if(!pdo_fieldexists('addons_history_cate',  'id')) {
	pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('addons_history_cate',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('addons_history_cate',  'keyword')) {
	pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `keyword` varchar(30) DEFAULT '';");
}
if(!pdo_fieldexists('addons_history_cate',  'title')) {
	pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `title` varchar(30) DEFAULT '';");
}
if(!pdo_fieldexists('addons_history_cate',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `sort` tinyint(2) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('addons_history_cate',  'status')) {
	pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `status` tinyint(1) unsigned DEFAULT '1';");
}
if(!pdo_fieldexists('addons_history_mode',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('addons_history_mode')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('addons_history_mode',  'mode')) {
	pdo_query("ALTER TABLE ".tablename('addons_history_mode')." ADD `mode` tinyint(30) unsigned DEFAULT '0' COMMENT '0无封面 1表示有封面';");
}
if(!pdo_fieldexists('ice_yzmhb',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_yzmhb',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb',  'title')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `title` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb',  'content')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `content` int(4) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb',  'time')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `time` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb',  'stime')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `stime` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb',  'etime')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `etime` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb',  'nick_name')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `nick_name` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb',  'send_name')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `send_name` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb',  'min_value')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `min_value` int(8) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('ice_yzmhb',  'max_value')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `max_value` int(8) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('ice_yzmhb',  'total_num')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `total_num` int(4) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb',  'wishing')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `wishing` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb',  'act_name')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `act_name` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `remark` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb',  'logo_imgurl')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `logo_imgurl` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `share_content` varchar(256) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `share_url` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb',  'share_imgurl')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `share_imgurl` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb')." ADD `status` tinyint(4) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'code')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `code` varchar(64) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `openid` varchar(64) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'yzmhbid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `yzmhbid` int(4) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'piciid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `piciid` int(4) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'type')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `type` char(1) DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'time')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `time` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_code',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD `status` tinyint(4) DEFAULT '1';");
}
if(!pdo_indexexists('ice_yzmhb_code',  'code')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_code')." ADD UNIQUE KEY `code` (`code`);");
}
if(!pdo_fieldexists('ice_yzmhb_codenum',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_codenum')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_yzmhb_codenum',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_codenum')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_codenum',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_codenum')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_codenum',  'hbid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_codenum')." ADD `hbid` int(4) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_codenum',  'count')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_codenum')." ADD `count` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_codenum',  'type')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_codenum')." ADD `type` char(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ice_yzmhb_codenum',  'usedcount')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_codenum')." ADD `usedcount` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('ice_yzmhb_codenum',  'time')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_codenum')." ADD `time` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_codenum',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_codenum')." ADD `status` tinyint(4) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'yzmhbid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `yzmhbid` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `uniacid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'prizeodds')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `prizeodds` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'prizesum')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `prizesum` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'prizename')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `prizename` varchar(64) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'type')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `type` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'money')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `money` varchar(16) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'money_range')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `money_range` varchar(16) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_prize',  'time')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_prize')." ADD `time` varchar(32) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'codeid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `codeid` int(10) DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `openid` varchar(64) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'packetid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `packetid` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'yzmhbid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `yzmhbid` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'money')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `money` varchar(64) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'type')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `type` char(20) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `status` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'time')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `time` varchar(20) DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'mark')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `mark` varchar(128) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_sendlist',  'totalmember')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_sendlist')." ADD `totalmember` int(10) DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_yzmhb_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_user')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_user')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_yzmhb_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_user')." ADD `openid` varchar(64) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_user')." ADD `nickname` varchar(64) DEFAULT '';");
}
if(!pdo_fieldexists('ice_yzmhb_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('ice_yzmhb_user')." ADD `headimgurl` varchar(255) DEFAULT '';");
}

?>