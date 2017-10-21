<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_ewei_couplet_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '' COMMENT '用户ID',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `headurl` varchar(255) DEFAULT '' COMMENT '头像',
  `area` varchar(255) DEFAULT '' COMMENT '地区',
  `realname` varchar(255) DEFAULT '' COMMENT '姓名',
  `mobile` varchar(255) DEFAULT '' COMMENT '手机',
  `uptext` text COMMENT '上联',
  `downtext` text COMMENT '下联',
  `rule` text COMMENT '规则',
  `helps` int(11) DEFAULT '0' COMMENT '被帮助数',
  `status` tinyint(1) DEFAULT '0' COMMENT '0 未中奖 1 已中奖 2 已兑奖',
  `num` int(11) DEFAULT '0' COMMENT '抽中个数',
  `log` tinyint(1) DEFAULT '0',
  `sim` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0' COMMENT '参与时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '兑奖时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_couplet_fans_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `fansopenid` varchar(100) DEFAULT '',
  `openid` varchar(100) DEFAULT '',
  `nickname` varchar(255) DEFAULT '',
  `headurl` varchar(255) DEFAULT '',
  `desc` text,
  `status` tinyint(1) DEFAULT '0' COMMENT '0 错误 1 正确',
  `createtime` int(10) DEFAULT '0' COMMENT '帮助时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_couplet_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `start` decimal(10,2) DEFAULT '0.00',
  `end` decimal(10,2) DEFAULT '0.00',
  `detail` text,
  `rules` text,
  `couplets` text,
  `award_name` varchar(255) DEFAULT '0',
  `award_total` int(11) DEFAULT '0',
  `award_last` int(11) DEFAULT '0',
  `friendcount` int(11) DEFAULT '0',
  `copyright` varchar(200) DEFAULT '',
  `toptext` varchar(200) DEFAULT '',
  `followurl` varchar(1000) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `joincount` int(11) DEFAULT '0',
  `bgcolor` varchar(255) DEFAULT '',
  `res_img1` varchar(255) DEFAULT '',
  `res_img2` varchar(255) DEFAULT '',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_couplet_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('ewei_couplet_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `openid` varchar(100) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `nickname` varchar(255) DEFAULT '' COMMENT '昵称';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'headurl')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `headurl` varchar(255) DEFAULT '' COMMENT '头像';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'area')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `area` varchar(255) DEFAULT '' COMMENT '地区';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `realname` varchar(255) DEFAULT '' COMMENT '姓名';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `mobile` varchar(255) DEFAULT '' COMMENT '手机';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'uptext')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `uptext` text COMMENT '上联';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'downtext')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `downtext` text COMMENT '下联';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `rule` text COMMENT '规则';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'helps')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `helps` int(11) DEFAULT '0' COMMENT '被帮助数';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '0 未中奖 1 已中奖 2 已兑奖';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'num')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `num` int(11) DEFAULT '0' COMMENT '抽中个数';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'log')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `log` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'sim')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `sim` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `createtime` int(10) DEFAULT '0' COMMENT '参与时间';");
}
if(!pdo_fieldexists('ewei_couplet_fans',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD `consumetime` int(10) DEFAULT '0' COMMENT '兑奖时间';");
}
if(!pdo_indexexists('ewei_couplet_fans',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_fieldexists('ewei_couplet_fans_help',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_couplet_fans_help',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_fans_help',  'fansopenid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD `fansopenid` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_fans_help',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD `openid` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_fans_help',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD `nickname` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_fans_help',  'headurl')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD `headurl` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_fans_help',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD `desc` text;");
}
if(!pdo_fieldexists('ewei_couplet_fans_help',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '0 错误 1 正确';");
}
if(!pdo_fieldexists('ewei_couplet_fans_help',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD `createtime` int(10) DEFAULT '0' COMMENT '帮助时间';");
}
if(!pdo_indexexists('ewei_couplet_fans_help',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_fans_help')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `thumb` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `isshow` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `viewnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'start')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `start` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'end')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `end` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'detail')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `detail` text;");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'rules')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `rules` text;");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'couplets')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `couplets` text;");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'award_name')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `award_name` varchar(255) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'award_total')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `award_total` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'award_last')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `award_last` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'friendcount')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `friendcount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `copyright` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'toptext')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `toptext` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'followurl')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `followurl` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'joincount')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `joincount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `bgcolor` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'res_img1')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `res_img1` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'res_img2')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `res_img2` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_indexexists('ewei_couplet_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('ewei_couplet_reply',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_reply')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('ewei_couplet_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_couplet_sysset',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_sysset')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_couplet_sysset',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_sysset')." ADD `appid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_sysset',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_sysset')." ADD `appsecret` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_sysset',  'appid_share')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_sysset')." ADD `appid_share` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_couplet_sysset',  'appsecret_share')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_sysset')." ADD `appsecret_share` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('ewei_couplet_sysset',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_couplet_sysset')." ADD KEY `idx_uniacid` (`uniacid`);");
}

?>