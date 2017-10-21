<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_mon_baton` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `copyright` varchar(100) NOT NULL,
  `copyright_url` varchar(500) DEFAULT NULL,
  `index_banner` varchar(500) DEFAULT NULL,
  `my_banner` varchar(500) DEFAULT NULL,
  `ry_banner` varchar(500) DEFAULT NULL,
  `default_logo` varchar(500) DEFAULT NULL,
  `default_name` varchar(20) DEFAULT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `end_dialog_tip` varchar(500) DEFAULT NULL,
  `follow_dialog_tip` varchar(500) DEFAULT NULL,
  `hd_intro` varchar(2000) DEFAULT NULL,
  `rule_intro` varchar(2000) DEFAULT NULL,
  `prize_intro` varchar(2000) DEFAULT NULL,
  `add_intro` varchar(2000) DEFAULT NULL,
  `join_fans_enable` int(1) DEFAULT NULL,
  `sucess_banner` varchar(500) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `speak` varchar(1000) DEFAULT NULL,
  `follow_btn` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_baton_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `appid` varchar(200) DEFAULT NULL,
  `appsecret` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_baton_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `puid` int(10) DEFAULT NULL,
  `baton_num` int(10) DEFAULT '0',
  `baton` int(10) DEFAULT '0',
  `uname` varchar(20) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `speak` varchar(1000) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('mon_baton',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_baton',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `rid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `starttime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `endtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `follow_url` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `copyright` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'copyright_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `copyright_url` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'index_banner')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `index_banner` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'my_banner')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `my_banner` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'ry_banner')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `ry_banner` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'default_logo')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `default_logo` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'default_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `default_name` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `logo` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'end_dialog_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `end_dialog_tip` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'follow_dialog_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `follow_dialog_tip` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'hd_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `hd_intro` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'rule_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `rule_intro` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'prize_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `prize_intro` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'add_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `add_intro` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'join_fans_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `join_fans_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'sucess_banner')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `sucess_banner` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `new_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'new_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `new_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'new_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `new_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `share_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `share_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `share_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `updatetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'speak')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `speak` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton',  'follow_btn')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton')." ADD `follow_btn` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_baton_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_setting')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_baton_setting',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_setting')." ADD `appid` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton_setting',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_setting')." ADD `appsecret` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton_setting',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_setting')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_baton_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_baton_user',  'bid')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `bid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_baton_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_baton_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_baton_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_baton_user',  'puid')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `puid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton_user',  'baton_num')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `baton_num` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_baton_user',  'baton')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `baton` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_baton_user',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `uname` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton_user',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `tel` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton_user',  'speak')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `speak` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_baton_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_baton_user')." ADD `createtime` int(10) DEFAULT '0';");
}

?>