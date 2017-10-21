<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_messikefu_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_biaoqian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `kefuopenid` varchar(200) NOT NULL,
  `fensiopenid` varchar(200) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `toopenid` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `hasread` tinyint(1) NOT NULL,
  `fkid` int(11) NOT NULL,
  `yuyintime` smallint(6) NOT NULL,
  `hasyuyindu` tinyint(1) NOT NULL,
  `isjqr` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_cservice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ctype` tinyint(1) NOT NULL,
  `content` varchar(100) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `starthour` smallint(6) NOT NULL,
  `endhour` smallint(6) NOT NULL,
  `autoreply` varchar(200) NOT NULL,
  `isonline` tinyint(1) NOT NULL,
  `groupid` int(11) NOT NULL,
  `fansauto` text NOT NULL,
  `kefuauto` text NOT NULL,
  `isautosub` tinyint(1) NOT NULL,
  `qrtext` varchar(50) NOT NULL,
  `qrbg` varchar(20) NOT NULL,
  `qrcolor` varchar(20) NOT NULL,
  `iskefuqrcode` tinyint(1) NOT NULL,
  `kefuqrcode` varchar(200) NOT NULL,
  `ishow` tinyint(1) NOT NULL,
  `notonline` varchar(255) NOT NULL,
  `lingjie` tinyint(1) NOT NULL,
  `typename` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_cservicegroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `qrtext` varchar(50) NOT NULL,
  `qrbg` varchar(20) NOT NULL,
  `qrcolor` varchar(20) NOT NULL,
  `cangroup` tinyint(1) NOT NULL,
  `typename` varchar(50) NOT NULL,
  `ishow` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_fanskefu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `fansopenid` varchar(100) NOT NULL,
  `kefuopenid` varchar(100) NOT NULL,
  `fansavatar` varchar(200) NOT NULL,
  `kefuavatar` varchar(200) NOT NULL,
  `fansnickname` varchar(100) NOT NULL,
  `kefunickname` varchar(100) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `notread` int(11) NOT NULL,
  `lastcon` varchar(255) NOT NULL,
  `kefulasttime` int(11) NOT NULL,
  `kefulastcon` varchar(255) NOT NULL,
  `kefunotread` int(11) NOT NULL,
  `msgtype` smallint(6) NOT NULL,
  `kefumsgtype` smallint(6) NOT NULL,
  `kefuseetime` int(11) NOT NULL,
  `fansseetime` int(11) NOT NULL,
  `guanlinum` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `groupname` varchar(100) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `admin` varchar(100) NOT NULL,
  `time` int(11) NOT NULL,
  `autoreply` varchar(200) NOT NULL,
  `quickcon` text NOT NULL,
  `isautosub` tinyint(1) NOT NULL,
  `cservicegroupid` int(11) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `maxnum` int(11) NOT NULL,
  `isguanzhu` tinyint(1) NOT NULL,
  `jinyan` tinyint(1) NOT NULL,
  `isshenhe` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_groupchat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(100) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `weid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `content` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `yuyintime` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_groupmember` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `intime` int(11) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `notread` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_jqr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `huifu` text NOT NULL,
  `kefuid` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_kefuandgroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `kefuid` int(11) NOT NULL,
  `groupid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_pingjia` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `kefuopenid` varchar(200) NOT NULL,
  `fensiopenid` varchar(200) NOT NULL,
  `pingtype` tinyint(1) NOT NULL,
  `content` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_sanchat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `sanfkid` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `yuyintime` smallint(6) NOT NULL,
  `hasyuyindu` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_sanfanskefu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `fansopenid` varchar(100) NOT NULL,
  `kefuopenid` varchar(100) NOT NULL,
  `fansavatar` varchar(200) NOT NULL,
  `kefuavatar` varchar(200) NOT NULL,
  `fansnickname` varchar(100) NOT NULL,
  `kefunickname` varchar(100) NOT NULL,
  `lasttime` int(11) NOT NULL,
  `notread` int(11) NOT NULL,
  `lastcon` varchar(255) NOT NULL,
  `msgtype` smallint(6) NOT NULL,
  `seetime` int(11) NOT NULL,
  `qudao` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_set` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `istplon` tinyint(1) NOT NULL,
  `unfollowtext` text NOT NULL,
  `followqrcode` varchar(100) NOT NULL,
  `sharetitle` varchar(100) NOT NULL,
  `sharedes` varchar(255) NOT NULL,
  `sharethumb` varchar(155) NOT NULL,
  `kefutplminute` int(11) NOT NULL,
  `bgcolor` varchar(10) NOT NULL,
  `defaultavatar` varchar(100) NOT NULL,
  `fansauto` text NOT NULL,
  `kefuauto` text NOT NULL,
  `issharemsg` tinyint(1) NOT NULL,
  `isautosub` tinyint(1) NOT NULL,
  `isshowwgz` tinyint(1) NOT NULL,
  `sharetype` tinyint(1) NOT NULL,
  `mingan` text NOT NULL,
  `temcolor` varchar(50) NOT NULL,
  `candel` tinyint(1) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `canservicequn` tinyint(1) NOT NULL,
  `canfansqun` tinyint(1) NOT NULL,
  `isgrouptplon` tinyint(1) NOT NULL,
  `grouptplminute` int(11) NOT NULL,
  `isgroupon` tinyint(1) NOT NULL,
  `footertext1` varchar(50) NOT NULL,
  `footertext2` varchar(50) NOT NULL,
  `footertext3` varchar(50) NOT NULL,
  `footertext4` varchar(50) NOT NULL,
  `isqiniu` tinyint(1) NOT NULL,
  `qiniuaccesskey` varchar(255) NOT NULL,
  `qiniusecretkey` varchar(255) NOT NULL,
  `qiniubucket` varchar(255) NOT NULL,
  `qiniuurl` varchar(255) NOT NULL,
  `httptype` tinyint(1) NOT NULL,
  `istxfon` tinyint(1) NOT NULL,
  `ishowgroupnum` tinyint(1) NOT NULL,
  `chosekefutem` tinyint(1) NOT NULL,
  `tulingkey` varchar(100) NOT NULL,
  `istulingon` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_tplmessage_sendlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tpl_id` int(11) DEFAULT NULL,
  `tpl_title` varchar(50) DEFAULT NULL,
  `message` text COMMENT '消息内容',
  `success` int(11) DEFAULT '0' COMMENT '成功人数',
  `fail` int(11) DEFAULT '0' COMMENT '失败人数',
  `time` int(11) DEFAULT NULL COMMENT '发送时间',
  `uniacid` int(5) DEFAULT NULL,
  `type` int(5) DEFAULT '0' COMMENT '消息类型 0为群发 1为个人',
  `target` varchar(80) DEFAULT '' COMMENT '发送对象 type 为0时 是粉丝组 type 为1时是openid',
  `status` int(2) DEFAULT '0' COMMENT '状态 0为发送中 1为完成 2为失败',
  `error` text COMMENT '错误记录',
  `mid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_messikefu_tplmessage_tpllist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tplbh` varchar(50) NOT NULL,
  `tpl_id` varchar(80) DEFAULT NULL,
  `tpl_title` varchar(20) DEFAULT NULL,
  `tpl_key` varchar(500) DEFAULT NULL COMMENT '模板内容key',
  `tpl_example` varchar(500) DEFAULT NULL,
  `uniacid` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('messikefu_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_adv',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('messikefu_adv',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('messikefu_adv',  'link')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD `link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('messikefu_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('messikefu_adv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_adv')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('messikefu_biaoqian',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_biaoqian',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_biaoqian',  'kefuopenid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD `kefuopenid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_biaoqian',  'fensiopenid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD `fensiopenid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_biaoqian',  'name')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_biaoqian')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_chat',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'toopenid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `toopenid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'content')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `content` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'time')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `avatar` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'type')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'hasread')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `hasread` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'fkid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `fkid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'yuyintime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `yuyintime` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'hasyuyindu')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `hasyuyindu` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_chat',  'isjqr')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `isjqr` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_cservice',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'name')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'ctype')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `ctype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'content')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `content` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `displayorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'starthour')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `starthour` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'endhour')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `endhour` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'autoreply')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `autoreply` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'isonline')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `isonline` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `groupid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'fansauto')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `fansauto` text NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'kefuauto')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `kefuauto` text NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'isautosub')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `isautosub` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'qrtext')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `qrtext` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'qrbg')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `qrbg` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'qrcolor')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `qrcolor` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'iskefuqrcode')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `iskefuqrcode` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'kefuqrcode')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `kefuqrcode` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'ishow')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `ishow` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'notonline')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `notonline` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'lingjie')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `lingjie` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservice',  'typename')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `typename` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'name')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `displayorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'qrtext')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `qrtext` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'qrbg')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `qrbg` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'qrcolor')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `qrcolor` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'cangroup')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `cangroup` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'typename')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `typename` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_cservicegroup',  'ishow')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `ishow` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'fansopenid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `fansopenid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'kefuopenid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefuopenid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'fansavatar')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `fansavatar` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'kefuavatar')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefuavatar` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'fansnickname')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `fansnickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'kefunickname')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefunickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `lasttime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'notread')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `notread` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'lastcon')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `lastcon` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'kefulasttime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefulasttime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'kefulastcon')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefulastcon` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'kefunotread')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefunotread` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'msgtype')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `msgtype` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'kefumsgtype')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefumsgtype` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'kefuseetime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefuseetime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'fansseetime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `fansseetime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_fanskefu',  'guanlinum')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `guanlinum` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_group',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'groupname')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `groupname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `thumb` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'admin')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `admin` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'time')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'autoreply')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `autoreply` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'quickcon')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `quickcon` text NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'isautosub')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `isautosub` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'cservicegroupid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `cservicegroupid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `lasttime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'maxnum')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `maxnum` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'isguanzhu')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `isguanzhu` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'jinyan')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `jinyan` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_group',  'isshenhe')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `isshenhe` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `groupid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'content')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `content` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'time')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'type')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupchat',  'yuyintime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `yuyintime` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `groupid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'type')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'status')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'intime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `intime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `lasttime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_groupmember',  'notread')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `notread` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_jqr',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_jqr')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_jqr',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_jqr')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_jqr',  'title')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_jqr')." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_jqr',  'huifu')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_jqr')." ADD `huifu` text NOT NULL;");
}
if(!pdo_fieldexists('messikefu_jqr',  'kefuid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_jqr')." ADD `kefuid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_jqr',  'type')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_jqr')." ADD `type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_kefuandgroup',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_kefuandgroup')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_kefuandgroup',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_kefuandgroup')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_kefuandgroup',  'kefuid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_kefuandgroup')." ADD `kefuid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_kefuandgroup',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_kefuandgroup')." ADD `groupid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_pingjia',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_pingjia',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_pingjia',  'kefuopenid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD `kefuopenid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_pingjia',  'fensiopenid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD `fensiopenid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_pingjia',  'pingtype')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD `pingtype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_pingjia',  'content')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD `content` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_pingjia',  'time')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_pingjia')." ADD `time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanchat',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_sanchat',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanchat',  'sanfkid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `sanfkid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanchat',  'content')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `content` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanchat',  'time')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanchat',  'type')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `type` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanchat',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanchat',  'yuyintime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `yuyintime` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanchat',  'hasyuyindu')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `hasyuyindu` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'fansopenid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `fansopenid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'kefuopenid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `kefuopenid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'fansavatar')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `fansavatar` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'kefuavatar')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `kefuavatar` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'fansnickname')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `fansnickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'kefunickname')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `kefunickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `lasttime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'notread')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `notread` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'lastcon')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `lastcon` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'msgtype')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `msgtype` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'seetime')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `seetime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_sanfanskefu',  'qudao')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_sanfanskefu')." ADD `qudao` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'title')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'istplon')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `istplon` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'unfollowtext')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `unfollowtext` text NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'followqrcode')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `followqrcode` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `sharetitle` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'sharedes')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `sharedes` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'sharethumb')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `sharethumb` varchar(155) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'kefutplminute')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `kefutplminute` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `bgcolor` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'defaultavatar')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `defaultavatar` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'fansauto')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `fansauto` text NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'kefuauto')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `kefuauto` text NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'issharemsg')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `issharemsg` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'isautosub')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isautosub` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'isshowwgz')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isshowwgz` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'sharetype')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `sharetype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'mingan')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `mingan` text NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'temcolor')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `temcolor` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'candel')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `candel` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `copyright` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'canservicequn')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `canservicequn` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'canfansqun')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `canfansqun` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'isgrouptplon')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isgrouptplon` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'grouptplminute')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `grouptplminute` int(11) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'isgroupon')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isgroupon` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'footertext1')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `footertext1` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'footertext2')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `footertext2` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'footertext3')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `footertext3` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'footertext4')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `footertext4` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'isqiniu')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isqiniu` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'qiniuaccesskey')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `qiniuaccesskey` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'qiniusecretkey')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `qiniusecretkey` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'qiniubucket')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `qiniubucket` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'qiniuurl')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `qiniuurl` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'httptype')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `httptype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'istxfon')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `istxfon` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'ishowgroupnum')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `ishowgroupnum` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'chosekefutem')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `chosekefutem` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'tulingkey')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `tulingkey` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_set',  'istulingon')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `istulingon` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'tpl_id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `tpl_id` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'tpl_title')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `tpl_title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'message')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `message` text COMMENT '消息内容';");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'success')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `success` int(11) DEFAULT '0' COMMENT '成功人数';");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'fail')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `fail` int(11) DEFAULT '0' COMMENT '失败人数';");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'time')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `time` int(11) DEFAULT NULL COMMENT '发送时间';");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `uniacid` int(5) DEFAULT NULL;");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'type')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `type` int(5) DEFAULT '0' COMMENT '消息类型 0为群发 1为个人';");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'target')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `target` varchar(80) DEFAULT '' COMMENT '发送对象 type 为0时 是粉丝组 type 为1时是openid';");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'status')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `status` int(2) DEFAULT '0' COMMENT '状态 0为发送中 1为完成 2为失败';");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'error')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `error` text COMMENT '错误记录';");
}
if(!pdo_fieldexists('messikefu_tplmessage_sendlog',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_sendlog')." ADD `mid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('messikefu_tplmessage_tpllist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_tpllist')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('messikefu_tplmessage_tpllist',  'tplbh')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_tpllist')." ADD `tplbh` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('messikefu_tplmessage_tpllist',  'tpl_id')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_tpllist')." ADD `tpl_id` varchar(80) DEFAULT NULL;");
}
if(!pdo_fieldexists('messikefu_tplmessage_tpllist',  'tpl_title')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_tpllist')." ADD `tpl_title` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('messikefu_tplmessage_tpllist',  'tpl_key')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_tpllist')." ADD `tpl_key` varchar(500) DEFAULT NULL COMMENT '模板内容key';");
}
if(!pdo_fieldexists('messikefu_tplmessage_tpllist',  'tpl_example')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_tpllist')." ADD `tpl_example` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('messikefu_tplmessage_tpllist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('messikefu_tplmessage_tpllist')." ADD `uniacid` int(5) DEFAULT NULL;");
}

?>