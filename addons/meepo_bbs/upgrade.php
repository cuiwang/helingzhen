<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `typeid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_api` (
  `id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(132) NOT NULL DEFAULT '',
  `file` varchar(132) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_begging` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` varchar(64) NOT NULL DEFAULT '',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `fopenid` varchar(42) NOT NULL DEFAULT '',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `fee` float(6,2) unsigned NOT NULL DEFAULT '0.00',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(32) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `thumb` text NOT NULL,
  `ttid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_blacklist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_credit_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `deadline` datetime NOT NULL,
  `per_user_limit` int(11) NOT NULL DEFAULT '0',
  `cost` int(11) NOT NULL DEFAULT '0',
  `cost_type` int(11) NOT NULL DEFAULT '1' COMMENT '1系统积分 2会员积分 4,8等留作扩展',
  `price` int(11) NOT NULL DEFAULT '100',
  `content` text NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_credit_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(200) NOT NULL,
  `mobile` varchar(200) NOT NULL,
  `residedist` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_ec_chong_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` varchar(50) NOT NULL DEFAULT '',
  `type` varchar(32) NOT NULL DEFAULT '',
  `fee` float unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` float unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `transid` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `avatar` varchar(512) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `realname` varchar(20) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `qq` varchar(15) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_home_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fromopenid` varchar(50) NOT NULL DEFAULT '',
  `toopenid` varchar(50) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fromopenid` (`fromopenid`) USING BTREE,
  KEY `toopenid` (`toopenid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(42) NOT NULL DEFAULT '',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `log` text NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_msg_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板标题',
  `tpl_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板id',
  `template` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板内容',
  `tags` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板标签',
  `set` text NOT NULL,
  `type` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_msg_template_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(52) NOT NULL DEFAULT '',
  `set` text NOT NULL,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `tpl_id` varchar(124) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_navs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(132) NOT NULL DEFAULT '',
  `name` varchar(32) NOT NULL DEFAULT '',
  `link` varchar(132) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_o2o_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `shopid` int(11) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `set` text NOT NULL,
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `acid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_o2o_user_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `type` varchar(32) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `cid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_reply_ups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `caretetime` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_rss` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(32) NOT NULL DEFAULT '',
  `url` varchar(132) NOT NULL DEFAULT '',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `set` text,
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_share` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `set` text NOT NULL,
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_task` (
  `taskid` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `note` text NOT NULL,
  `num` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `maxnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `image` varchar(150) NOT NULL DEFAULT '',
  `filename` varchar(50) NOT NULL DEFAULT '',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `nexttime` int(10) unsigned NOT NULL DEFAULT '0',
  `nexttype` varchar(20) NOT NULL DEFAULT '',
  `credit` smallint(6) NOT NULL DEFAULT '0',
  `displayorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`taskid`),
  KEY `displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_task_user` (
  `uid` mediumint(8) unsigned NOT NULL,
  `username` char(15) NOT NULL DEFAULT '',
  `taskid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `credit` smallint(6) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `isignore` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`,`taskid`),
  KEY `isignore` (`isignore`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_threadclass` (
  `typeid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  `icon` varchar(255) NOT NULL DEFAULT '',
  `moderators` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `group` varchar(132) DEFAULT NULL,
  `look_group` varchar(232) DEFAULT NULL,
  `post_group` varchar(232) DEFAULT NULL,
  `isgood` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeid`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `fid` (`fid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_topic` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(132) DEFAULT NULL,
  `content` text,
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_topic_like` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`) USING BTREE,
  KEY `fid` (`fid`) USING BTREE,
  KEY `openid` (`openid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_topic_read` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_topic_replie` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text,
  `create_at` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `thumb` text NOT NULL,
  `fid` int(11) unsigned NOT NULL DEFAULT '0',
  `beggingid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_topic_share` (
  `id` int(11) NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_topics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(320) DEFAULT NULL,
  `tab` varchar(32) DEFAULT NULL,
  `last_reply_at` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `replycredit` int(11) unsigned NOT NULL DEFAULT '0',
  `tags` varchar(150) DEFAULT NULL,
  `ratetimes` int(11) unsigned NOT NULL DEFAULT '0',
  `rate` int(11) unsigned NOT NULL DEFAULT '0',
  `invisible` tinyint(1) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `content` text,
  `rnum` int(11) unsigned NOT NULL DEFAULT '0',
  `lnum` int(11) unsigned NOT NULL DEFAULT '0',
  `thumb` text,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `tid` (`tid`) USING BTREE,
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_meepo_bbs_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `acid` int(11) unsigned NOT NULL DEFAULT '0',
  `uid` int(11) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `time` int(11) unsigned NOT NULL DEFAULT '0',
  `online` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(32) NOT NULL DEFAULT '',
  `user_image` varchar(200) DEFAULT NULL COMMENT '用户头像',
  `from_user` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('meepo_bbs_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_adv',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_adv',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_adv',  'link')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD `link` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_adv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_adv',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_adv',  'typeid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD `typeid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_adv',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('meepo_bbs_adv',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('meepo_bbs_adv',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_indexexists('meepo_bbs_adv',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_adv')." ADD KEY `uniacid` (`uniacid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_api',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_api')." ADD `id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_api',  'title')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_api')." ADD `title` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_api',  'description')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_api')." ADD `description` varchar(132) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_api',  'file')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_api')." ADD `file` varchar(132) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `tid` varchar(64) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `fid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'type')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `type` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'fopenid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `fopenid` varchar(42) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `fee` float(6,2) unsigned NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'status')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `status` tinyint(2) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `transid` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'content')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `thumb` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_begging',  'ttid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_begging')." ADD `ttid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_blacklist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_blacklist')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_blacklist',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_blacklist')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_blacklist',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_blacklist')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `logo` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `amount` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'deadline')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `deadline` datetime NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'per_user_limit')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `per_user_limit` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'cost')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `cost` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'cost_type')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `cost_type` int(11) NOT NULL DEFAULT '1' COMMENT '1系统积分 2会员积分 4,8等留作扩展';");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'price')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `price` int(11) NOT NULL DEFAULT '100';");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'content')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_goods')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `realname` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `mobile` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'residedist')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `residedist` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'note')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `note` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'goods_id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `goods_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_credit_request',  'status')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_credit_request')." ADD `status` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `tid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'type')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `type` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `fee` float unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'num')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `num` float unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `status` tinyint(2) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_ec_chong_log',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_ec_chong_log')." ADD `transid` varchar(64) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'id';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `avatar` varchar(512) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `nickname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `realname` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `mobile` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `qq` varchar(15) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'email')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `email` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `address` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_fans',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD `gender` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('meepo_bbs_fans',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_fans')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('meepo_bbs_home_message',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_home_message',  'fromopenid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD `fromopenid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_home_message',  'toopenid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD `toopenid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_home_message',  'content')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_home_message',  'type')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD `type` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_home_message',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_home_message',  'status')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD `status` tinyint(2) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_home_message',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD `tid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_home_message',  'fromopenid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD KEY `fromopenid` (`fromopenid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_home_message',  'toopenid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD KEY `toopenid` (`toopenid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_home_message',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_home_message')." ADD KEY `tid` (`tid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_log')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_log')." ADD `openid` varchar(42) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_log')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_log',  'content')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_log')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_log',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_log')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_log',  'log')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_log')." ADD `log` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_log',  'type')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_log')." ADD `type` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_msg_template',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_msg_template',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_msg_template',  'title')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板标题';");
}
if(!pdo_fieldexists('meepo_bbs_msg_template',  'tpl_id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD `tpl_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板id';");
}
if(!pdo_fieldexists('meepo_bbs_msg_template',  'template')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD `template` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板内容';");
}
if(!pdo_fieldexists('meepo_bbs_msg_template',  'tags')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD `tags` varchar(1000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板标签';");
}
if(!pdo_fieldexists('meepo_bbs_msg_template',  'set')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD `set` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_msg_template',  'type')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template')." ADD `type` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_msg_template_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template_data')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_msg_template_data',  'title')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template_data')." ADD `title` varchar(52) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_msg_template_data',  'set')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template_data')." ADD `set` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_msg_template_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template_data')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_msg_template_data',  'tpl_id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_msg_template_data')." ADD `tpl_id` varchar(124) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_navs',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_navs')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_navs',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_navs')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_navs',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_navs')." ADD `icon` varchar(132) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_navs',  'name')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_navs')." ADD `name` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_navs',  'link')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_navs')." ADD `link` varchar(132) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_navs',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_navs')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_navs',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_navs')." ADD `displayorder` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_navs',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_navs')." ADD `enabled` tinyint(2) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD `shopid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user',  'set')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD `set` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user')." ADD `acid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user_log')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user_log',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user_log')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user_log')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user_log',  'type')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user_log')." ADD `type` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user_log',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user_log')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_o2o_user_log',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_o2o_user_log')." ADD `cid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_reply_ups',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_reply_ups')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_reply_ups',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_reply_ups')." ADD `rid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_reply_ups',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_reply_ups')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_reply_ups',  'caretetime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_reply_ups')." ADD `caretetime` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_reply_ups',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_reply_ups')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_reply_ups',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_reply_ups')." ADD KEY `rid` (`rid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_reply_ups',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_reply_ups')." ADD KEY `uid` (`uid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_reply_ups',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_reply_ups')." ADD KEY `uniacid` (`uniacid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_rss',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_rss')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_rss',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_rss')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_rss',  'title')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_rss')." ADD `title` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_rss',  'url')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_rss')." ADD `url` varchar(132) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_rss',  'status')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_rss')." ADD `status` tinyint(2) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_rss',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_rss')." ADD `fid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_set')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_set')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_set',  'set')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_set')." ADD `set` text;");
}
if(!pdo_fieldexists('meepo_bbs_set',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_set')." ADD `createtime` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_set')." ADD KEY `uniacid` (`uniacid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_share')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_share',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_share')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_share',  'set')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_share')." ADD `set` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_share',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_share')." ADD `createtime` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_share',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_share')." ADD `tid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'taskid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `taskid` smallint(6) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_task',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_task',  'available')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `available` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'name')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `name` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'note')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `note` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_task',  'num')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `num` mediumint(8) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'maxnum')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `maxnum` mediumint(8) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'image')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `image` varchar(150) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'filename')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `filename` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `starttime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `endtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'nexttime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `nexttime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'nexttype')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `nexttype` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `credit` smallint(6) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD `displayorder` smallint(6) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_task',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task')." ADD KEY `displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('meepo_bbs_task_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task_user')." ADD `uid` mediumint(8) unsigned NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_task_user',  'username')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task_user')." ADD `username` char(15) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_task_user',  'taskid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task_user')." ADD `taskid` smallint(6) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task_user',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task_user')." ADD `credit` smallint(6) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task_user',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task_user')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_task_user',  'isignore')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task_user')." ADD `isignore` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_task_user',  'isignore')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_task_user')." ADD KEY `isignore` (`isignore`,`dateline`);");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'typeid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `typeid` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `fid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'name')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `name` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `displayorder` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `icon` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'moderators')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `moderators` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'content')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `content` text;");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'group')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `group` varchar(132) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'look_group')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `look_group` varchar(232) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'post_group')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `post_group` varchar(232) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_threadclass',  'isgood')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD `isgood` tinyint(2) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_threadclass',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD KEY `uniacid` (`uniacid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_threadclass',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_threadclass')." ADD KEY `fid` (`fid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_topic',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_topic',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD `tid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic',  'title')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD `title` varchar(132) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_topic',  'content')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD `content` text;");
}
if(!pdo_fieldexists('meepo_bbs_topic',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD `createtime` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_topic',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD KEY `uniacid` (`uniacid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topic',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD KEY `uid` (`uid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topic',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic')." ADD KEY `tid` (`tid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_topic_like',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_topic_like',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD `openid` varchar(40) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_topic_like',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD `tid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_like',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_like',  'num')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD `num` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_like',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD `fid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_topic_like',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD KEY `tid` (`tid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topic_like',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD KEY `fid` (`fid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topic_like',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_like')." ADD KEY `openid` (`openid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_topic_read',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_read')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_topic_read',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_read')." ADD `openid` varchar(40) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_topic_read',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_read')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_read',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_read')." ADD `tid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_read',  'num')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_read')." ADD `num` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_topic_read',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_read')." ADD KEY `openid` (`openid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topic_read',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_read')." ADD KEY `tid` (`tid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_topic_replie',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_topic_replie',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_replie',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_replie',  'content')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD `content` text;");
}
if(!pdo_fieldexists('meepo_bbs_topic_replie',  'create_at')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD `create_at` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_replie',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD `tid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_replie',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD `thumb` text NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_topic_replie',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD `fid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_replie',  'beggingid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD `beggingid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_topic_replie',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD KEY `uniacid` (`uniacid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topic_replie',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD KEY `uid` (`uid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topic_replie',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_replie')." ADD KEY `tid` (`tid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_topic_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_share')." ADD `id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_topic_share',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_share')." ADD `openid` varchar(40) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_topic_share',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_share')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_share',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_share')." ADD `tid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topic_share',  'num')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_share')." ADD `num` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('meepo_bbs_topic_share',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_share')." ADD KEY `openid` (`openid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topic_share',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topic_share')." ADD KEY `tid` (`tid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'title')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `title` varchar(320) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'tab')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `tab` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'last_reply_at')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `last_reply_at` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `createtime` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'replycredit')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `replycredit` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'tags')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `tags` varchar(150) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'ratetimes')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `ratetimes` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'rate')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `rate` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'invisible')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `invisible` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `tid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `fid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'content')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `content` text;");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'rnum')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `rnum` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'lnum')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `lnum` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_topics',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD `thumb` text;");
}
if(!pdo_indexexists('meepo_bbs_topics',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD KEY `uniacid` (`uniacid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topics',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD KEY `tid` (`tid`) USING BTREE;");
}
if(!pdo_indexexists('meepo_bbs_topics',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_topics')." ADD KEY `uid` (`uid`) USING BTREE;");
}
if(!pdo_fieldexists('meepo_bbs_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('meepo_bbs_user',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `acid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `uid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'email')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'address')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'time')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `time` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'online')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `online` tinyint(2) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `ip` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'user_image')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `user_image` varchar(200) DEFAULT NULL COMMENT '用户头像';");
}
if(!pdo_fieldexists('meepo_bbs_user',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD `from_user` varchar(200) NOT NULL;");
}
if(!pdo_indexexists('meepo_bbs_user',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('meepo_bbs_user',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('meepo_bbs_user')." ADD KEY `indx_uniacid` (`uniacid`);");
}

?>