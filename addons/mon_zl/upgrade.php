<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_mon_zl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `awardstime` int(10) DEFAULT NULL,
  `awardetime` int(10) DEFAULT NULL,
  `awardaddress` varchar(2000) DEFAULT NULL,
  `rule` text,
  `award` text,
  `content` text,
  `title_bg` varchar(300) DEFAULT NULL,
  `share_bg` varchar(300) DEFAULT NULL,
  `copyright` varchar(50) DEFAULT NULL,
  `randking_count` int(10) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `top_banner` varchar(500) DEFAULT NULL,
  `top_banner_title` varchar(100) DEFAULT NULL,
  `top_banner_show` int(1) DEFAULT '0',
  `top_banner_url` varchar(500) DEFAULT NULL,
  `zl_follow_enable` int(1) DEFAULT NULL,
  `join_follow_enable` int(1) DEFAULT NULL,
  `follow_dlg_tip` varchar(500) DEFAULT NULL,
  `follow_btn_name` varchar(20) DEFAULT NULL,
  `udetail_eable` int(1) DEFAULT NULL,
  `telname` varchar(30) DEFAULT '手机',
  `contact_tel` varchar(20) DEFAULT NULL,
  `contact_name` varchar(20) DEFAULT '联系小编',
  `startp` int(10) DEFAULT NULL,
  `maxp` int(10) DEFAULT NULL,
  `zl_rule` varchar(2000) DEFAULT NULL,
  `join_btn_name` varchar(100) DEFAULT '我要报名',
  `uzl_btn_name` varchar(100) DEFAULT '发送给好友助力',
  `fzl_btn_name` varchar(100) DEFAULT '发送给好友帮他助力',
  `top_tag` int(3) DEFAULT NULL,
  `view_count` int(3) DEFAULT NULL,
  `share_count` int(3) DEFAULT NULL,
  `f_zl_limit` int(3) DEFAULT NULL,
  `zlunit` varchar(10) DEFAULT NULL,
  `syncredit` int(1) DEFAULT NULL,
  `f_zl_limit_tip` varchar(2000) DEFAULT NULL,
  `f_day_limit` int(10) DEFAULT NULL,
  `f_day_limit_tip` varchar(2000) DEFAULT NULL,
  `f_diff_limt` int(10) DEFAULT NULL,
  `f_diff_tip` varchar(2000) DEFAULT NULL,
  `ip_limit` int(10) DEFAULT NULL,
  `ip_limit_tip` varchar(2000) DEFAULT NULL,
  `tmp_enable` int(1) DEFAULT NULL,
  `tmpId` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_zl_friend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(300) DEFAULT NULL,
  `headimgurl` varchar(300) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `point` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_zl_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `appid` varchar(300) DEFAULT NULL,
  `apps` varchar(300) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_zl_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(300) NOT NULL,
  `uname` varchar(200) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `point` int(10) DEFAULT NULL,
  `ptime` int(10) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `moid` varchar(500) DEFAULT NULL,
  `isblack` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('mon_zl',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_zl',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `rid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `starttime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `endtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'awardstime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `awardstime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'awardetime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `awardetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'awardaddress')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `awardaddress` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `rule` text;");
}
if(!pdo_fieldexists('mon_zl',  'award')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `award` text;");
}
if(!pdo_fieldexists('mon_zl',  'content')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `content` text;");
}
if(!pdo_fieldexists('mon_zl',  'title_bg')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `title_bg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'share_bg')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `share_bg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `copyright` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'randking_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `randking_count` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `follow_url` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `new_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'new_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `new_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'new_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `new_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `share_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `share_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `share_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `updatetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'top_banner')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `top_banner` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'top_banner_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `top_banner_title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'top_banner_show')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `top_banner_show` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zl',  'top_banner_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `top_banner_url` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'zl_follow_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `zl_follow_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'join_follow_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `join_follow_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'follow_dlg_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `follow_dlg_tip` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'follow_btn_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `follow_btn_name` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'udetail_eable')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `udetail_eable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'telname')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `telname` varchar(30) DEFAULT '手机';");
}
if(!pdo_fieldexists('mon_zl',  'contact_tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `contact_tel` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'contact_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `contact_name` varchar(20) DEFAULT '联系小编';");
}
if(!pdo_fieldexists('mon_zl',  'startp')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `startp` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'maxp')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `maxp` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'zl_rule')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `zl_rule` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'join_btn_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `join_btn_name` varchar(100) DEFAULT '我要报名';");
}
if(!pdo_fieldexists('mon_zl',  'uzl_btn_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `uzl_btn_name` varchar(100) DEFAULT '发送给好友助力';");
}
if(!pdo_fieldexists('mon_zl',  'fzl_btn_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `fzl_btn_name` varchar(100) DEFAULT '发送给好友帮他助力';");
}
if(!pdo_fieldexists('mon_zl',  'top_tag')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `top_tag` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'view_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `view_count` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'share_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `share_count` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'f_zl_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `f_zl_limit` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'zlunit')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `zlunit` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'syncredit')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `syncredit` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'f_zl_limit_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `f_zl_limit_tip` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'f_day_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `f_day_limit` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'f_day_limit_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `f_day_limit_tip` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'f_diff_limt')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `f_diff_limt` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'f_diff_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `f_diff_tip` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'ip_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `ip_limit` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'ip_limit_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `ip_limit_tip` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'tmp_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `tmp_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl',  'tmpId')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl')." ADD `tmpId` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_friend',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_friend')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_zl_friend',  'zid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_friend')." ADD `zid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_friend',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_friend')." ADD `uid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_friend',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_friend')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zl_friend',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_friend')." ADD `nickname` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_friend',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_friend')." ADD `headimgurl` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_friend',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_friend')." ADD `ip` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_friend',  'point')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_friend')." ADD `point` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_friend',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_friend')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zl_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_zl_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_setting')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_zl_setting',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_setting')." ADD `appid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_setting',  'apps')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_setting')." ADD `apps` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_setting',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_setting')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zl_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_zl_user',  'zid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `zid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `headimgurl` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `uname` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `tel` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'point')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `point` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'ptime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `ptime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `ip` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zl_user',  'moid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `moid` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zl_user',  'isblack')) {
	pdo_query("ALTER TABLE ".tablename('mon_zl_user')." ADD `isblack` int(1) DEFAULT NULL;");
}

?>