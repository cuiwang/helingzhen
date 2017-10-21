<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_xk_housekeepassess` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `sername` varchar(100) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `content` varchar(500) DEFAULT NULL,
  `pjnum` tinyint(1) unsigned DEFAULT '1',
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepbarrage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `img` varchar(200) NOT NULL,
  `time` varchar(100) NOT NULL,
  `content` varchar(500) DEFAULT NULL,
  `addtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepbase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NOT NULL,
  `mopenid` varchar(100) DEFAULT NULL,
  `lead` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `share_title` varchar(100) DEFAULT NULL,
  `share_icon` varchar(100) DEFAULT NULL,
  `share_content` varchar(255) DEFAULT NULL,
  `share_link` varchar(100) DEFAULT NULL,
  `fwscontent` text NOT NULL,
  `tgycontent` text NOT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  `updatetime` int(10) unsigned DEFAULT NULL,
  `sername` varchar(100) NOT NULL,
  `sercontent` text NOT NULL,
  `quename` varchar(100) NOT NULL,
  `quecontent` text NOT NULL,
  `header` varchar(100) NOT NULL,
  `footer` varchar(100) NOT NULL,
  `tjztz` varchar(500) NOT NULL,
  `cfsm` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepfwstgy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL,
  `openid` varchar(200) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `shmoney` decimal(10,2) unsigned NOT NULL,
  `summoney` decimal(10,2) unsigned NOT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  `updatetime` int(10) unsigned NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `fwstgy` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepmember` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` varchar(255) NOT NULL DEFAULT '',
  `money` int(10) unsigned NOT NULL DEFAULT '0',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `agio` smallint(2) unsigned NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepmoneycz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL DEFAULT '0',
  `czmoney` int(10) unsigned NOT NULL,
  `zsmoney` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepmoneyrc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL,
  `tid` varchar(100) NOT NULL DEFAULT '',
  `money` int(10) unsigned NOT NULL,
  `zsmoney` int(10) unsigned NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepmuban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(10) unsigned NOT NULL,
  `messageid1` varchar(100) DEFAULT NULL,
  `prompt1` varchar(500) DEFAULT NULL,
  `remarks1` varchar(500) DEFAULT NULL,
  `messageid2` varchar(100) DEFAULT NULL,
  `prompt2` varchar(500) DEFAULT NULL,
  `remarks2` varchar(500) DEFAULT NULL,
  `messageid3` varchar(100) DEFAULT NULL,
  `prompt3` varchar(500) DEFAULT NULL,
  `remarks3` varchar(500) DEFAULT NULL,
  `messageid4` varchar(100) DEFAULT NULL,
  `prompt4` varchar(500) DEFAULT NULL,
  `remarks4` varchar(500) DEFAULT NULL,
  `messageid5` varchar(100) NOT NULL,
  `prompt5` varchar(500) NOT NULL,
  `remarks5` varchar(500) NOT NULL,
  `messageid6` varchar(200) NOT NULL,
  `prompt6` varchar(500) NOT NULL,
  `remarks6` varchar(500) NOT NULL,
  `messageid7` varchar(200) NOT NULL,
  `prompt7` varchar(500) NOT NULL,
  `remarks7` varchar(500) NOT NULL,
  `messageid8` varchar(200) NOT NULL,
  `prompt8` varchar(500) NOT NULL,
  `remarks8` varchar(500) NOT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeeporderg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `openid` varchar(80) DEFAULT NULL,
  `project_name` varchar(50) DEFAULT NULL,
  `addtime` varchar(100) DEFAULT NULL,
  `atime` int(10) unsigned NOT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL,
  `number` int(10) unsigned NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  `unit` varchar(100) NOT NULL,
  `summoney` decimal(10,2) NOT NULL,
  `content` varchar(200) NOT NULL,
  `state` tinyint(1) DEFAULT '0',
  `sername` varchar(500) DEFAULT NULL,
  `seropenid` varchar(80) NOT NULL,
  `tgyopenid` varchar(200) NOT NULL,
  `jwd` varchar(50) NOT NULL,
  `tid` varchar(30) NOT NULL,
  `fws` tinyint(1) unsigned NOT NULL,
  `tgy` tinyint(1) unsigned NOT NULL,
  `img` varchar(200) NOT NULL,
  `con` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepqdlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL,
  `openid` varchar(200) NOT NULL,
  `sername` varchar(100) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `summoney` decimal(10,2) unsigned NOT NULL,
  `time` int(10) unsigned DEFAULT NULL,
  `addtime` varchar(50) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepserverg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `content` varchar(500) NOT NULL,
  `top` int(11) DEFAULT '0',
  `addtime` int(10) unsigned DEFAULT NULL,
  `updatetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepservergproject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `serverg_id` int(11) NOT NULL,
  `content` varchar(500) NOT NULL,
  `summary` varchar(500) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `price` decimal(10,2) unsigned DEFAULT '0.00',
  `hint` varchar(800) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `addtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `top` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepsetting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NOT NULL,
  `apiclient_cert` varchar(255) DEFAULT NULL,
  `apiclient_key` varchar(255) DEFAULT NULL,
  `rootca` varchar(255) DEFAULT NULL,
  `mp3` varchar(200) NOT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepslide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `link` varchar(300) NOT NULL,
  `pic` varchar(100) NOT NULL,
  `top` int(11) unsigned NOT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepslidecc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NOT NULL,
  `title1` varchar(100) NOT NULL,
  `ftitle1` varchar(100) NOT NULL,
  `url1` varchar(200) NOT NULL,
  `pic1` varchar(100) NOT NULL,
  `title2` varchar(100) NOT NULL,
  `ftitle2` varchar(100) NOT NULL,
  `url2` varchar(200) NOT NULL,
  `pic2` varchar(100) NOT NULL,
  `title3` varchar(100) NOT NULL,
  `ftitle3` varchar(100) NOT NULL,
  `url3` varchar(200) NOT NULL,
  `pic3` varchar(100) NOT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepstaff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` tinyint(3) unsigned NOT NULL,
  `wechat` varchar(100) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `sex` tinyint(1) unsigned DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL,
  `qrcode` varchar(255) DEFAULT NULL,
  `booking` tinyint(1) unsigned DEFAULT '0',
  `seraddress` varchar(300) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `updatetime` int(10) unsigned NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `project` text NOT NULL,
  `tgy` tinyint(1) unsigned DEFAULT '0',
  `tgystate` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `front` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `backadmin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `tgynum` int(10) unsigned NOT NULL DEFAULT '0',
  `fwsmoney` decimal(10,2) unsigned DEFAULT '0.00',
  `tgymoney` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `rid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `member` varchar(100) NOT NULL DEFAULT '',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `tgytime` int(10) unsigned NOT NULL,
  `money` decimal(10,2) unsigned NOT NULL,
  `tgyopenid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xk_housekeepuseraddress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(200) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `road` varchar(1000) NOT NULL,
  `moren` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('xk_housekeepassess',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepassess')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepassess',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepassess')." ADD `wid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepassess',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepassess')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepassess',  'sername')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepassess')." ADD `sername` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepassess',  'project_name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepassess')." ADD `project_name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepassess',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepassess')." ADD `content` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepassess',  'pjnum')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepassess')." ADD `pjnum` tinyint(1) unsigned DEFAULT '1';");
}
if(!pdo_fieldexists('xk_housekeepassess',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepassess')." ADD `addtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbarrage',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbarrage')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepbarrage',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbarrage')." ADD `wid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbarrage',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbarrage')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbarrage',  'img')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbarrage')." ADD `img` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbarrage',  'time')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbarrage')." ADD `time` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbarrage',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbarrage')." ADD `content` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbarrage',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbarrage')." ADD `addtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `wid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'mopenid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `mopenid` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'lead')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `lead` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `mobile` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `comment` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `share_title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `share_icon` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `share_content` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'share_link')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `share_link` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'fwscontent')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `fwscontent` text NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'tgycontent')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `tgycontent` text NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `addtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `updatetime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'sername')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `sername` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'sercontent')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `sercontent` text NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'quename')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `quename` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'quecontent')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `quecontent` text NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'header')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `header` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'footer')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `footer` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'tjztz')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `tjztz` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepbase',  'cfsm')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepbase')." ADD `cfsm` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `wid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'money')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `money` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'shmoney')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `shmoney` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'summoney')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `summoney` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `addtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `updatetime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'state')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `state` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepfwstgy',  'fwstgy')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepfwstgy')." ADD `fwstgy` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmember',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepmember',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `wid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmember',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xk_housekeepmember',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `content` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xk_housekeepmember',  'money')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `money` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmember',  'number')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `number` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmember',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmember',  'agio')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `agio` smallint(2) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmember',  'level')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `level` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmember',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmember')." ADD `groupid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmoneycz',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneycz')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepmoneycz',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneycz')." ADD `wid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmoneycz',  'czmoney')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneycz')." ADD `czmoney` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmoneycz',  'zsmoney')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneycz')." ADD `zsmoney` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmoneycz',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneycz')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `wid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `nickname` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `tid` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'money')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `money` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'zsmoney')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `zsmoney` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepmoneyrc',  'state')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmoneyrc')." ADD `state` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `wid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'messageid1')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `messageid1` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'prompt1')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `prompt1` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'remarks1')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `remarks1` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'messageid2')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `messageid2` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'prompt2')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `prompt2` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'remarks2')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `remarks2` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'messageid3')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `messageid3` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'prompt3')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `prompt3` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'remarks3')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `remarks3` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'messageid4')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `messageid4` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'prompt4')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `prompt4` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'remarks4')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `remarks4` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'messageid5')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `messageid5` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'prompt5')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `prompt5` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'remarks5')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `remarks5` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'messageid6')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `messageid6` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'prompt6')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `prompt6` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'remarks6')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `remarks6` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'messageid7')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `messageid7` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'prompt7')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `prompt7` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'remarks7')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `remarks7` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'messageid8')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `messageid8` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'prompt8')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `prompt8` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'remarks8')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `remarks8` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepmuban',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepmuban')." ADD `addtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `wid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `openid` varchar(80) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'project_name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `project_name` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `addtime` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'atime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `atime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `mobile` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `address` varchar(120) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'number')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `number` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `price` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `unit` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'summoney')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `summoney` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `content` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'state')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `state` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'sername')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `sername` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'seropenid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `seropenid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'tgyopenid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `tgyopenid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'jwd')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `jwd` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `tid` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'fws')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `fws` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'tgy')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `tgy` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'img')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `img` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeeporderg',  'con')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeeporderg')." ADD `con` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `wid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'sername')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `sername` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'project_name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `project_name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `address` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'summoney')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `summoney` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'time')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `time` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `addtime` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'state')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `state` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepqdlist',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepqdlist')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepserverg',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepserverg')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepserverg',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepserverg')." ADD `wid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepserverg',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepserverg')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepserverg',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepserverg')." ADD `icon` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepserverg',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepserverg')." ADD `content` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepserverg',  'top')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepserverg')." ADD `top` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepserverg',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepserverg')." ADD `addtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepserverg',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepserverg')." ADD `updatetime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `wid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'serverg_id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `serverg_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `content` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'summary')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `summary` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `icon` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `price` decimal(10,2) unsigned DEFAULT '0.00';");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'hint')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `hint` varchar(800) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `unit` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepservergproject',  'top')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepservergproject')." ADD `top` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepsetting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepsetting')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepsetting',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepsetting')." ADD `wid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepsetting',  'apiclient_cert')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepsetting')." ADD `apiclient_cert` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepsetting',  'apiclient_key')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepsetting')." ADD `apiclient_key` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepsetting',  'rootca')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepsetting')." ADD `rootca` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepsetting',  'mp3')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepsetting')." ADD `mp3` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepsetting',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepsetting')." ADD `addtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslide',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslide')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepslide',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslide')." ADD `wid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslide',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslide')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslide',  'link')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslide')." ADD `link` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslide',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslide')." ADD `pic` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslide',  'top')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslide')." ADD `top` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslide',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslide')." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `wid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'title1')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `title1` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'ftitle1')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `ftitle1` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'url1')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `url1` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'pic1')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `pic1` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'title2')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `title2` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'ftitle2')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `ftitle2` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'url2')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `url2` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'pic2')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `pic2` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'title3')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `title3` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'ftitle3')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `ftitle3` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'url3')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `url3` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'pic3')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `pic3` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepslidecc',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepslidecc')." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `wid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'age')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `age` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'wechat')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `wechat` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `mobile` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `sex` tinyint(1) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `avatar` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `qrcode` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'booking')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `booking` tinyint(1) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'seraddress')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `seraddress` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `addtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `updatetime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'state')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `state` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'project')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `project` text NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'tgy')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `tgy` tinyint(1) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'tgystate')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `tgystate` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'front')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `front` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'backadmin')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `backadmin` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'tgynum')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `tgynum` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'fwsmoney')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `fwsmoney` decimal(10,2) unsigned DEFAULT '0.00';");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'tgymoney')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `tgymoney` decimal(10,2) unsigned NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('xk_housekeepstaff',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepstaff')." ADD `rid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuser',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepuser',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `wid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepuser',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuser',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `nickname` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xk_housekeepuser',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `mobile` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuser',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `avatar` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuser',  'member')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `member` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xk_housekeepuser',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepuser',  'tgytime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `tgytime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuser',  'money')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `money` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuser',  'tgyopenid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuser')." ADD `tgyopenid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuseraddress',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuseraddress')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xk_housekeepuseraddress',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuseraddress')." ADD `wid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepuseraddress',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuseraddress')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuseraddress',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuseraddress')." ADD `name` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xk_housekeepuseraddress',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuseraddress')." ADD `mobile` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuseraddress',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuseraddress')." ADD `address` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuseraddress',  'road')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuseraddress')." ADD `road` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xk_housekeepuseraddress',  'moren')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuseraddress')." ADD `moren` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xk_housekeepuseraddress',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('xk_housekeepuseraddress')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}

?>