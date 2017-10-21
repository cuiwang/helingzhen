<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_zmcn_fw_batch` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL DEFAULT '0',
  `m1` varchar(2) NOT NULL,
  `batch` varchar(20) NOT NULL DEFAULT '0' COMMENT '批号',
  `num` int(11) NOT NULL DEFAULT '0',
  `rcon` int(10) NOT NULL DEFAULT '0',
  `factory` varchar(40) NOT NULL COMMENT '生产企业',
  `product` varchar(50) NOT NULL COMMENT '品名',
  `brand` varchar(40) NOT NULL COMMENT '品牌',
  `yuan` varchar(30) NOT NULL,
  `jianjie` varchar(255) NOT NULL,
  `ischuanhuo` int(11) NOT NULL DEFAULT '0',
  `province` varchar(30) NOT NULL,
  `city` varchar(40) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `inttype` int(2) NOT NULL DEFAULT '1',
  `integral` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `lasttime` int(10) NOT NULL DEFAULT '0',
  `validity` int(10) NOT NULL DEFAULT '0',
  `buylink` varchar(255) NOT NULL,
  `wailink` varchar(6000) NOT NULL,
  `logo` varchar(150) NOT NULL,
  `banner` varchar(150) NOT NULL,
  `video` varchar(150) NOT NULL,
  `videoid` varchar(100) NOT NULL,
  `param` text NOT NULL,
  `toshop` varchar(500) NOT NULL,
  `sint` int(7) NOT NULL DEFAULT '0',
  `leint` varchar(300) NOT NULL,
  `caid` varchar(6000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `batch` (`batch`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_zmcn_fw_chai` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL DEFAULT '0',
  `batchid` int(11) NOT NULL DEFAULT '0',
  `code` varchar(40) NOT NULL,
  `type` int(10) NOT NULL DEFAULT '0',
  `num` int(10) NOT NULL DEFAULT '0',
  `isvalid` int(2) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `userna` varchar(50) NOT NULL,
  `userid` int(10) NOT NULL DEFAULT '0',
  `ip` varchar(30) NOT NULL,
  `gender` int(2) NOT NULL DEFAULT '0',
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `os` varchar(15) NOT NULL,
  `container` varchar(15) NOT NULL,
  `iserr` int(2) NOT NULL DEFAULT '0' COMMENT '1跨区',
  `isgz` int(10) NOT NULL,
  `hdtype` int(3) NOT NULL,
  `credittype` varchar(20) NOT NULL,
  `fnum` decimal(10,2) NOT NULL,
  `isy` int(2) NOT NULL,
  `tx` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `province` (`province`),
  KEY `city` (`city`),
  KEY `gender` (`gender`),
  KEY `type` (`type`),
  KEY `uniacid` (`uniacid`),
  KEY `batchid` (`batchid`),
  KEY `hdtype` (`hdtype`),
  KEY `credittype` (`credittype`),
  KEY `hdtype_2` (`hdtype`),
  KEY `credittype_2` (`credittype`),
  KEY `hdtype_3` (`hdtype`),
  KEY `credittype_3` (`credittype`),
  KEY `hdtype_4` (`hdtype`),
  KEY `credittype_4` (`credittype`),
  KEY `hdtype_5` (`hdtype`),
  KEY `credittype_5` (`credittype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_zmcn_fw_clerks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(200) NOT NULL DEFAULT '',
  `storeid` int(10) unsigned NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `password` (`password`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_zmcn_fw_codeset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL DEFAULT '0',
  `rid` int(10) NOT NULL DEFAULT '0',
  `m1` varchar(2) NOT NULL,
  `k` varchar(300) NOT NULL,
  `act` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_zmcn_fw_exchange` (
  `tid` int(11) NOT NULL DEFAULT '0',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(11) NOT NULL DEFAULT '0',
  `act` varchar(50) NOT NULL,
  `intd` varchar(50) NOT NULL,
  `zhtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `tid` (`tid`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_zmcn_fw_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `type` int(11) NOT NULL,
  `summary` varchar(50) NOT NULL,
  `uid` int(10) NOT NULL DEFAULT '0',
  `addtime` int(10) NOT NULL DEFAULT '0',
  `remark` varchar(50) NOT NULL,
  `ip` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `summary` (`summary`),
  KEY `uniacid` (`uniacid`),
  KEY `summary_2` (`summary`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_zmcn_fw_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL DEFAULT '0',
  `luck` varchar(4000) NOT NULL,
  `settings` text NOT NULL,
  `red` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('zmcn_fw_batch',  'id')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `uniacid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'm1')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `m1` varchar(2) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'batch')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `batch` varchar(20) NOT NULL DEFAULT '0' COMMENT '批号';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'num')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `num` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'rcon')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `rcon` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'factory')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `factory` varchar(40) NOT NULL COMMENT '生产企业';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'product')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `product` varchar(50) NOT NULL COMMENT '品名';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'brand')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `brand` varchar(40) NOT NULL COMMENT '品牌';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'yuan')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `yuan` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'jianjie')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `jianjie` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'ischuanhuo')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `ischuanhuo` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'province')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `province` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'city')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `city` varchar(40) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `remark` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'inttype')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `inttype` int(2) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'integral')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `integral` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `lasttime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'validity')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `validity` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'buylink')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `buylink` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'wailink')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `wailink` varchar(6000) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `logo` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'banner')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `banner` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'video')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `video` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'videoid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `videoid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'param')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `param` text NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'toshop')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `toshop` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'sint')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `sint` int(7) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'leint')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `leint` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_batch',  'caid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD `caid` varchar(6000) NOT NULL;");
}
if(!pdo_indexexists('zmcn_fw_batch',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('zmcn_fw_batch',  'batch')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_batch')." ADD KEY `batch` (`batch`);");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'id')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `uniacid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'batchid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `batchid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'code')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `code` varchar(40) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'type')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `type` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'num')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `num` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'isvalid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `isvalid` int(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'userna')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `userna` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `userid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `ip` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `gender` int(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'province')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'city')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'os')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `os` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'container')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `container` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'iserr')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `iserr` int(2) NOT NULL DEFAULT '0' COMMENT '1跨区';");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'isgz')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `isgz` int(10) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'hdtype')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `hdtype` int(3) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'credittype')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `credittype` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'fnum')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `fnum` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'isy')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `isy` int(2) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_chai',  'tx')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD `tx` varchar(100) NOT NULL;");
}
if(!pdo_indexexists('zmcn_fw_chai',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `userid` (`userid`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'province')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `province` (`province`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'city')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `city` (`city`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `gender` (`gender`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'type')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `type` (`type`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'batchid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `batchid` (`batchid`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'hdtype')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `hdtype` (`hdtype`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'credittype')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `credittype` (`credittype`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'hdtype_2')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `hdtype_2` (`hdtype`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'credittype_2')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `credittype_2` (`credittype`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'hdtype_3')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `hdtype_3` (`hdtype`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'credittype_3')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `credittype_3` (`credittype`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'hdtype_4')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `hdtype_4` (`hdtype`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'credittype_4')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `credittype_4` (`credittype`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'hdtype_5')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `hdtype_5` (`hdtype`);");
}
if(!pdo_indexexists('zmcn_fw_chai',  'credittype_5')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_chai')." ADD KEY `credittype_5` (`credittype`);");
}
if(!pdo_fieldexists('zmcn_fw_clerks',  'id')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('zmcn_fw_clerks',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_clerks',  'name')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD `name` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('zmcn_fw_clerks',  'password')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD `password` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('zmcn_fw_clerks',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD `storeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_clerks',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD `mobile` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_clerks',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_clerks',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD `nickname` varchar(30) NOT NULL;");
}
if(!pdo_indexexists('zmcn_fw_clerks',  'password')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD KEY `password` (`password`);");
}
if(!pdo_indexexists('zmcn_fw_clerks',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_clerks')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('zmcn_fw_codeset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_codeset')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('zmcn_fw_codeset',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_codeset')." ADD `uniacid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_codeset',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_codeset')." ADD `rid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_codeset',  'm1')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_codeset')." ADD `m1` varchar(2) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_codeset',  'k')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_codeset')." ADD `k` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_codeset',  'act')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_codeset')." ADD `act` int(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_exchange',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_exchange')." ADD `tid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_exchange',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_exchange')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_exchange',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_exchange')." ADD `uid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_exchange',  'act')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_exchange')." ADD `act` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_exchange',  'intd')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_exchange')." ADD `intd` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_exchange',  'zhtime')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_exchange')." ADD `zhtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('zmcn_fw_exchange',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_exchange')." ADD KEY `tid` (`tid`);");
}
if(!pdo_indexexists('zmcn_fw_exchange',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_exchange')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('zmcn_fw_exchange',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_exchange')." ADD KEY `uid` (`uid`);");
}
if(!pdo_fieldexists('zmcn_fw_history',  'id')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('zmcn_fw_history',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_history',  'type')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD `type` int(11) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_history',  'summary')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD `summary` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_history',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD `uid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_history',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD `addtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_history',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD `remark` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_history',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD `ip` varchar(20) NOT NULL;");
}
if(!pdo_indexexists('zmcn_fw_history',  'summary')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD KEY `summary` (`summary`);");
}
if(!pdo_indexexists('zmcn_fw_history',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('zmcn_fw_history',  'summary_2')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_history')." ADD KEY `summary_2` (`summary`);");
}
if(!pdo_fieldexists('zmcn_fw_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('zmcn_fw_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_set')." ADD `uniacid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('zmcn_fw_set',  'luck')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_set')." ADD `luck` varchar(4000) NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_set',  'settings')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_set')." ADD `settings` text NOT NULL;");
}
if(!pdo_fieldexists('zmcn_fw_set',  'red')) {
	pdo_query("ALTER TABLE ".tablename('zmcn_fw_set')." ADD `red` text NOT NULL;");
}

?>