<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_yg_parkour_info` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `nickname` varchar(1000) DEFAULT NULL,
  `headimgurl` varchar(1000) DEFAULT NULL,
  `logoopenid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yg_parkour_oauth` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `appid` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secret` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `ims_yg_parkour_recomd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `rid` int(11) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `asnum` float(11,1) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yg_parkour_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `sharepic` varchar(500) NOT NULL,
  `sharedesc` varchar(255) NOT NULL,
  `sharetitle` varchar(10) NOT NULL,
  `indexbg` varchar(255) NOT NULL,
  `rule` varchar(500) DEFAULT NULL COMMENT '按完',
  `pnum` int(10) DEFAULT '0',
  `addnum` int(10) DEFAULT NULL COMMENT '转发增加次数',
  `turng` int(10) DEFAULT NULL COMMENT '是否转发加次数',
  `createtime` int(11) DEFAULT NULL,
  `status` int(3) DEFAULT NULL,
  `follelink` varchar(1000) DEFAULT NULL,
  `forward` int(12) DEFAULT NULL,
  `phbbg` varchar(500) DEFAULT NULL,
  `gamebg` varchar(500) DEFAULT NULL,
  `gametime` int(20) DEFAULT NULL,
  `isfolle` int(20) DEFAULT NULL,
  `resultbg` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yg_parkour_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `rid` int(11) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(1000) DEFAULT NULL,
  `asnum` float(11,1) DEFAULT '0.0' COMMENT '分数',
  `time` int(15) DEFAULT NULL COMMENT '添加时间',
  `count` int(11) DEFAULT '0' COMMENT '次数',
  `comparetime` int(11) DEFAULT NULL,
  `realname` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `forward` int(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('yg_parkour_info',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_info')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_parkour_info',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_info')." ADD `uniacid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_info',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_info')." ADD `openid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_info',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_info')." ADD `nickname` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_info',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_info')." ADD `headimgurl` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_info',  'logoopenid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_info')." ADD `logoopenid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_oauth',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_oauth')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_parkour_oauth',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_oauth')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_oauth',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_oauth')." ADD `appid` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_oauth',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_oauth')." ADD `secret` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_recomd',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_recomd')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_parkour_recomd',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_recomd')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_recomd',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_recomd')." ADD `rid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_recomd',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_recomd')." ADD `openid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_recomd',  'asnum')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_recomd')." ADD `asnum` float(11,1) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_recomd',  'time')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_recomd')." ADD `time` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_recomd',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_recomd')." ADD `nickname` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_recomd',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_recomd')." ADD `headimgurl` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `starttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `endtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'sharepic')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `sharepic` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `sharedesc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `sharetitle` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'indexbg')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `indexbg` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `rule` varchar(500) DEFAULT NULL COMMENT '按完';");
}
if(!pdo_fieldexists('yg_parkour_reply',  'pnum')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `pnum` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('yg_parkour_reply',  'addnum')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `addnum` int(10) DEFAULT NULL COMMENT '转发增加次数';");
}
if(!pdo_fieldexists('yg_parkour_reply',  'turng')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `turng` int(10) DEFAULT NULL COMMENT '是否转发加次数';");
}
if(!pdo_fieldexists('yg_parkour_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `createtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `status` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'follelink')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `follelink` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'forward')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `forward` int(12) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'phbbg')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `phbbg` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'gamebg')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `gamebg` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'gametime')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `gametime` int(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'isfolle')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `isfolle` int(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_reply',  'resultbg')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_reply')." ADD `resultbg` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_parkour_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_user',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `rid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `openid` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `nickname` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `headimgurl` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_user',  'asnum')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `asnum` float(11,1) DEFAULT '0.0' COMMENT '分数';");
}
if(!pdo_fieldexists('yg_parkour_user',  'time')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `time` int(15) DEFAULT NULL COMMENT '添加时间';");
}
if(!pdo_fieldexists('yg_parkour_user',  'count')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `count` int(11) DEFAULT '0' COMMENT '次数';");
}
if(!pdo_fieldexists('yg_parkour_user',  'comparetime')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `comparetime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_user',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `realname` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_user',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `tel` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_parkour_user',  'forward')) {
	pdo_query("ALTER TABLE ".tablename('yg_parkour_user')." ADD `forward` int(255) DEFAULT '0';");
}

?>