<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_mon_qmshake` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `shake_follow_enable` int(1) DEFAULT NULL,
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
  `rule` varchar(2000) DEFAULT NULL,
  `top_banner` varchar(500) DEFAULT NULL,
  `top_banner_title` varchar(100) DEFAULT NULL,
  `top_banner_show` int(1) DEFAULT '0',
  `top_banner_url` varchar(500) DEFAULT NULL,
  `follow_dlg_tip` varchar(500) DEFAULT NULL,
  `follow_btn_name` varchar(20) DEFAULT NULL,
  `shake_day_limit` int(3) DEFAULT '0',
  `prize_limit` int(3) DEFAULT '0',
  `total_limit` int(3) DEFAULT '0',
  `dpassword` varchar(20) DEFAULT NULL,
  `title_bg` varchar(300) DEFAULT NULL,
  `shake_bg` varchar(300) DEFAULT NULL,
  `index_bg` varchar(300) DEFAULT NULL,
  `share_bg` varchar(300) DEFAULT NULL,
  `share_enable` int(1) DEFAULT '0',
  `share_times` int(3) DEFAULT '0',
  `share_award_count` int(3) DEFAULT '0',
  `share_url` varchar(1000) DEFAULT NULL,
  `unstarttip` text,
  `tmpId` varchar(2000) DEFAULT NULL,
  `tmpenable` int(1) DEFAULT NULL,
  `udefine` varchar(200) DEFAULT NULL,
  `lj_tip` varchar(2000) DEFAULT NULL,
  `jfye_auto_dh` int(1) DEFAULT NULL,
  `day_prize_limit` int(1) DEFAULT NULL,
  `sid` int(10) DEFAULT NULL,
  `pname` varchar(50) DEFAULT NULL,
  `p_summary` varchar(500) DEFAULT NULL,
  `pimg` varchar(250) DEFAULT NULL,
  `p_url` varchar(250) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `pcount` int(10) DEFAULT NULL,
  `left_count` int(10) DEFAULT NULL,
  `pb` int(10) DEFAULT '0',
  `display_order` int(3) DEFAULT NULL,
  `tgs` varchar(250) DEFAULT NULL,
  `tgs_url` varchar(1000) DEFAULT NULL,
  `virtual_count` int(10) DEFAULT NULL,
  `ptype` int(1) DEFAULT NULL,
  `jfye` float(10,2) DEFAULT NULL,
  `pid` int(10) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `status` int(1) DEFAULT NULL,
  `djtime` int(10) DEFAULT NULL,
  `award_count` int(10) DEFAULT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `uname` varchar(200) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_qmshake_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) DEFAULT NULL,
  `pname` varchar(50) DEFAULT NULL,
  `p_summary` varchar(500) DEFAULT NULL,
  `pimg` varchar(250) DEFAULT NULL,
  `p_url` varchar(250) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `pcount` int(10) DEFAULT NULL,
  `left_count` int(10) DEFAULT NULL,
  `pb` int(10) DEFAULT '0',
  `display_order` int(3) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `tgs` varchar(250) DEFAULT NULL,
  `tgs_url` varchar(1000) DEFAULT NULL,
  `virtual_count` int(10) DEFAULT NULL,
  `ptype` int(1) DEFAULT NULL,
  `jfye` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_qmshake_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `pname` varchar(200) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `status` int(1) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `djtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_qmshake_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL,
  `award_count` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_qmshake_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `uname` varchar(200) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `udefine` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('mon_qmshake',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_qmshake',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `rid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `starttime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `endtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'shake_follow_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `shake_follow_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `copyright` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'randking_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `randking_count` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `follow_url` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `new_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'new_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `new_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'new_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `new_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `share_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `share_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `share_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `updatetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `rule` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'top_banner')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `top_banner` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'top_banner_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `top_banner_title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'top_banner_show')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `top_banner_show` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake',  'top_banner_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `top_banner_url` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'follow_dlg_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `follow_dlg_tip` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'follow_btn_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `follow_btn_name` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'shake_day_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `shake_day_limit` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake',  'prize_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `prize_limit` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake',  'total_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `total_limit` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake',  'dpassword')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `dpassword` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'title_bg')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `title_bg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'shake_bg')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `shake_bg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'index_bg')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `index_bg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'share_bg')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `share_bg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'share_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `share_enable` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake',  'share_times')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `share_times` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake',  'share_award_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `share_award_count` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `share_url` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'unstarttip')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `unstarttip` text;");
}
if(!pdo_fieldexists('mon_qmshake',  'tmpId')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `tmpId` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'tmpenable')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `tmpenable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'udefine')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `udefine` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'lj_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `lj_tip` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'jfye_auto_dh')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `jfye_auto_dh` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'day_prize_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `day_prize_limit` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `sid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'pname')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `pname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'p_summary')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `p_summary` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'pimg')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `pimg` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'p_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `p_url` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'price')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'pcount')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `pcount` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'left_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `left_count` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'pb')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `pb` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake',  'display_order')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `display_order` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'tgs')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `tgs` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'tgs_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `tgs_url` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'virtual_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `virtual_count` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'ptype')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `ptype` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'jfye')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `jfye` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `pid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `uid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'status')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'djtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `djtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'award_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `award_count` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `uname` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake')." ADD `tel` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `sid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'pname')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `pname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'p_summary')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `p_summary` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'pimg')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `pimg` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'p_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `p_url` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'price')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'pcount')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `pcount` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'left_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `left_count` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'pb')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `pb` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'display_order')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `display_order` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'tgs')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `tgs` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'tgs_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `tgs_url` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'virtual_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `virtual_count` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'ptype')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `ptype` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_prize',  'jfye')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_prize')." ADD `jfye` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_qmshake_record',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_record')." ADD `sid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_record',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_record')." ADD `pid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_record',  'pname')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_record')." ADD `pname` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_record')." ADD `uid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_record')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_record')." ADD `status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_record')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake_record',  'djtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_record')." ADD `djtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_share')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_qmshake_share',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_share')." ADD `sid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_share',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_share')." ADD `uid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_share',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_share')." ADD `openid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_share',  'award_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_share')." ADD `award_count` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_share',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_share')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_qmshake_user',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_user')." ADD `sid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_user')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_user')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_user')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_user',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_user')." ADD `uname` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_user',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_user')." ADD `tel` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_qmshake_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_user')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_qmshake_user',  'udefine')) {
	pdo_query("ALTER TABLE ".tablename('mon_qmshake_user')." ADD `udefine` varchar(200) DEFAULT NULL;");
}

?>