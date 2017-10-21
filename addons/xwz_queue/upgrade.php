<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_xwz_queue_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `typeid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `number` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `giveuptime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xwz_queue_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `headimgurl` varchar(255) DEFAULT '' COMMENT '头像',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 -1 黑名单 0 正常',
  `suc` int(11) DEFAULT '0' COMMENT '取号次数',
  `past` int(11) DEFAULT '0' COMMENT '过号次数',
  `cancel` int(11) DEFAULT '0' COMMENT '取消次数',
  `createtime` int(11) DEFAULT '0' COMMENT '提交时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xwz_queue_manager` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `username` varchar(100) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xwz_queue_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(200) DEFAULT '',
  `heading` varchar(255) DEFAULT '',
  `smallheading` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `followurl` varchar(255) DEFAULT '',
  `intro` text,
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `num` int(11) DEFAULT '0',
  `beforenum` int(11) DEFAULT '0',
  `screenbg` varchar(255) DEFAULT '',
  `qrcode` varchar(1000) DEFAULT '',
  `qrcodetype` tinyint(3) DEFAULT '0',
  `templateid` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xwz_queue_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `tag` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `num` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('xwz_queue_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xwz_queue_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_data',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_data',  'typeid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD `typeid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_data',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD `openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_data',  'number')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD `number` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_data',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_data',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_data',  'giveuptime')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD `giveuptime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('xwz_queue_data',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('xwz_queue_data',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_data')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_fieldexists('xwz_queue_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xwz_queue_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_fans',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `nickname` varchar(255) DEFAULT '' COMMENT '昵称';");
}
if(!pdo_fieldexists('xwz_queue_fans',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `headimgurl` varchar(255) DEFAULT '' COMMENT '头像';");
}
if(!pdo_fieldexists('xwz_queue_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '状态 -1 黑名单 0 正常';");
}
if(!pdo_fieldexists('xwz_queue_fans',  'suc')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `suc` int(11) DEFAULT '0' COMMENT '取号次数';");
}
if(!pdo_fieldexists('xwz_queue_fans',  'past')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `past` int(11) DEFAULT '0' COMMENT '过号次数';");
}
if(!pdo_fieldexists('xwz_queue_fans',  'cancel')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `cancel` int(11) DEFAULT '0' COMMENT '取消次数';");
}
if(!pdo_fieldexists('xwz_queue_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD `createtime` int(11) DEFAULT '0' COMMENT '提交时间';");
}
if(!pdo_indexexists('xwz_queue_fans',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('xwz_queue_fans',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('xwz_queue_fans',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('xwz_queue_fans',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_fans')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('xwz_queue_manager',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_manager')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xwz_queue_manager',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_manager')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xwz_queue_manager',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_manager')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xwz_queue_manager',  'username')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_manager')." ADD `username` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xwz_queue_manager',  'pwd')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_manager')." ADD `pwd` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('xwz_queue_manager',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_manager')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('xwz_queue_manager',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_manager')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('xwz_queue_manager',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_manager')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_fieldexists('xwz_queue_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xwz_queue_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `thumb` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'heading')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `heading` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'smallheading')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `smallheading` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `tel` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'followurl')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `followurl` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'intro')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `intro` text;");
}
if(!pdo_fieldexists('xwz_queue_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'num')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `num` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'beforenum')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `beforenum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'screenbg')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `screenbg` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `qrcode` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'qrcodetype')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `qrcodetype` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_reply',  'templateid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD `templateid` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('xwz_queue_reply',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('xwz_queue_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('xwz_queue_reply',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_reply')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_fieldexists('xwz_queue_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xwz_queue_type',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_type',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_type',  'tag')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD `tag` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_type',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xwz_queue_type',  'num')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD `num` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xwz_queue_type',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('xwz_queue_type',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('xwz_queue_type',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('xwz_queue_type',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('xwz_queue_type')." ADD KEY `idx_status` (`status`);");
}

?>