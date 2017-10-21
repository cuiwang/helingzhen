<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_redpacket` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `banner_pic` varchar(100) NOT NULL,
  `begintime` int(11) unsigned DEFAULT NULL,
  `endtime` int(11) unsigned DEFAULT NULL,
  `createtime` int(11) unsigned DEFAULT NULL,
  `countlimit` int(5) NOT NULL,
  `visitsCount` int(11) DEFAULT '0',
  `incomelimit` float(10,2) unsigned NOT NULL DEFAULT '0.00',
  `careurl` varchar(200) NOT NULL,
  `shareImg` varchar(200) NOT NULL,
  `shareTitle` varchar(200) NOT NULL,
  `shareContent` varchar(200) NOT NULL,
  `rule` varchar(1000) NOT NULL,
  `daylimit` int(5) DEFAULT '0',
  `totallimit` int(5) DEFAULT '0',
  `limitType` int(1) DEFAULT '0',
  `start` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值',
  `step` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '固定金额',
  `steprandom` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '随机金额',
  `steptype` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '随机金额',
  `addp` int(5) NOT NULL DEFAULT '100',
  `packetsummary` varchar(1000) NOT NULL COMMENT '活动摘要',
  `sharetip` varchar(1000) NOT NULL,
  `fanpaitip` varchar(1000) NOT NULL,
  `carebtn` varchar(100) NOT NULL,
  `awardtip` varchar(200) NOT NULL,
  `cardbg` varchar(1000) NOT NULL,
  `sortcount` int(5) DEFAULT '10',
  `sharebtn` varchar(10) NOT NULL,
  `fsharebtn` varchar(10) NOT NULL,
  `fanpaiurl` varchar(500) DEFAULT NULL,
  `fanpaimustfollow` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_redpacket_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0',
  `point` decimal(10,2) DEFAULT '0.00',
  `name` varchar(255) DEFAULT '',
  `num` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_redpacket_firend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '分享用户的id',
  `pid` int(10) NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `nickname` varchar(100) NOT NULL COMMENT '昵称',
  `sex` varchar(10) NOT NULL COMMENT '性别',
  `headimgurl` varchar(500) NOT NULL COMMENT '头像',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  `income` float(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_redpacket_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL,
  `new_title` varchar(100) NOT NULL,
  `new_pic` varchar(200) NOT NULL,
  `new_desc` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_redpacket_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(200) NOT NULL COMMENT 'appid',
  `weid` int(11) unsigned DEFAULT NULL,
  `secret` varchar(200) NOT NULL COMMENT 'secret',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_redpacket_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `access_token` varchar(1000) NOT NULL,
  `expires_in` int(11) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_redpacket_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `tel` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT '姓名',
  `nickname` varchar(100) NOT NULL COMMENT '昵称',
  `sex` varchar(10) NOT NULL COMMENT '性别',
  `headimgurl` varchar(500) NOT NULL COMMENT '头像',
  `income` float(10,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  `helpcount` int(11) DEFAULT '0' COMMENT '助力次数',
  `status` int(10) DEFAULT '0',
  `awardid` int(10) DEFAULT '0',
  `awardtime` int(10) DEFAULT '0',
  `virtual` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('redpacket',  'id')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('redpacket',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `weid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket',  'name')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `name` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket',  'banner_pic')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `banner_pic` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'begintime')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `begintime` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `endtime` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `createtime` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket',  'countlimit')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `countlimit` int(5) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'visitsCount')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `visitsCount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket',  'incomelimit')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `incomelimit` float(10,2) unsigned NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('redpacket',  'careurl')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `careurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'shareImg')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `shareImg` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'shareTitle')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `shareTitle` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'shareContent')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `shareContent` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `rule` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'daylimit')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `daylimit` int(5) DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket',  'totallimit')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `totallimit` int(5) DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket',  'limitType')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `limitType` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket',  'start')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `start` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值';");
}
if(!pdo_fieldexists('redpacket',  'step')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `step` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '固定金额';");
}
if(!pdo_fieldexists('redpacket',  'steprandom')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `steprandom` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '随机金额';");
}
if(!pdo_fieldexists('redpacket',  'steptype')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `steptype` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '随机金额';");
}
if(!pdo_fieldexists('redpacket',  'addp')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `addp` int(5) NOT NULL DEFAULT '100';");
}
if(!pdo_fieldexists('redpacket',  'packetsummary')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `packetsummary` varchar(1000) NOT NULL COMMENT '活动摘要';");
}
if(!pdo_fieldexists('redpacket',  'sharetip')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `sharetip` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'fanpaitip')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `fanpaitip` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'carebtn')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `carebtn` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'awardtip')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `awardtip` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'cardbg')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `cardbg` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'sortcount')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `sortcount` int(5) DEFAULT '10';");
}
if(!pdo_fieldexists('redpacket',  'sharebtn')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `sharebtn` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'fsharebtn')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `fsharebtn` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('redpacket',  'fanpaiurl')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `fanpaiurl` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket',  'fanpaimustfollow')) {
	pdo_query("ALTER TABLE ".tablename('redpacket')." ADD `fanpaimustfollow` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_award')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('redpacket_award',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_award')." ADD `pid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket_award',  'point')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_award')." ADD `point` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('redpacket_award',  'name')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_award')." ADD `name` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('redpacket_award',  'num')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_award')." ADD `num` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket_firend',  'id')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_firend')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('redpacket_firend',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_firend')." ADD `uid` int(10) NOT NULL DEFAULT '0' COMMENT '分享用户的id';");
}
if(!pdo_fieldexists('redpacket_firend',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_firend')." ADD `pid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket_firend',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_firend')." ADD `openid` varchar(50) NOT NULL COMMENT '用户唯一身份ID';");
}
if(!pdo_fieldexists('redpacket_firend',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_firend')." ADD `nickname` varchar(100) NOT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('redpacket_firend',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_firend')." ADD `sex` varchar(10) NOT NULL COMMENT '性别';");
}
if(!pdo_fieldexists('redpacket_firend',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_firend')." ADD `headimgurl` varchar(500) NOT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('redpacket_firend',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_firend')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '日期';");
}
if(!pdo_fieldexists('redpacket_firend',  'income')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_firend')." ADD `income` float(10,2) unsigned NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('redpacket_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('redpacket_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket_reply',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_reply')." ADD `pid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('redpacket_reply',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_reply')." ADD `new_title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('redpacket_reply',  'new_pic')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_reply')." ADD `new_pic` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('redpacket_reply',  'new_desc')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_reply')." ADD `new_desc` varchar(500) NOT NULL;");
}
if(!pdo_indexexists('redpacket_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_reply')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_fieldexists('redpacket_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('redpacket_setting',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_setting')." ADD `appid` varchar(200) NOT NULL COMMENT 'appid';");
}
if(!pdo_fieldexists('redpacket_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_setting')." ADD `weid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket_setting',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_setting')." ADD `secret` varchar(200) NOT NULL COMMENT 'secret';");
}
if(!pdo_fieldexists('redpacket_token',  'id')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_token')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('redpacket_token',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_token')." ADD `weid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket_token',  'access_token')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_token')." ADD `access_token` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('redpacket_token',  'expires_in')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_token')." ADD `expires_in` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('redpacket_token',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_token')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '日期';");
}
if(!pdo_fieldexists('redpacket_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('redpacket_user',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `pid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `openid` varchar(50) NOT NULL COMMENT '用户唯一身份ID';");
}
if(!pdo_fieldexists('redpacket_user',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `tel` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('redpacket_user',  'name')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `name` varchar(100) NOT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('redpacket_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `nickname` varchar(100) NOT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('redpacket_user',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `sex` varchar(10) NOT NULL COMMENT '性别';");
}
if(!pdo_fieldexists('redpacket_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `headimgurl` varchar(500) NOT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('redpacket_user',  'income')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `income` float(10,2) unsigned NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('redpacket_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '日期';");
}
if(!pdo_fieldexists('redpacket_user',  'helpcount')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `helpcount` int(11) DEFAULT '0' COMMENT '助力次数';");
}
if(!pdo_fieldexists('redpacket_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `status` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket_user',  'awardid')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `awardid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket_user',  'awardtime')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `awardtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('redpacket_user',  'virtual')) {
	pdo_query("ALTER TABLE ".tablename('redpacket_user')." ADD `virtual` int(1) DEFAULT '0';");
}

?>