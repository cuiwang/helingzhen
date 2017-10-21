<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_mon_zjp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `index_pic` varchar(200) DEFAULT NULL,
  `prize_intro` varchar(1000) DEFAULT NULL,
  `rule_intro` varchar(1000) DEFAULT NULL,
  `share_award_enable` int(1) DEFAULT '0',
  `share_award_count` int(3) DEFAULT '0',
  `share_award_time` int(3) DEFAULT '0',
  `u_award_count` int(3) DEFAULT '1',
  `play_count` int(3) DEFAULT '0',
  `follow_url` varchar(200) DEFAULT NULL,
  `copyright` varchar(100) NOT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `status` int(3) DEFAULT '0',
  `banner_ad_pic` varchar(200) DEFAULT NULL,
  `dialog_tips` varchar(1000) DEFAULT NULL,
  `success_award_tips` varchar(1000) DEFAULT NULL,
  `fail_award_tips` varchar(1000) DEFAULT NULL,
  `lock_tip` varchar(100) DEFAULT NULL,
  `day_play_count` int(3) DEFAULT NULL,
  `prize_sharebtn_name` varchar(50) DEFAULT NULL,
  `luck_sharebtn_name` varchar(50) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_zjp_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(10) NOT NULL,
  `pname` varchar(50) NOT NULL,
  `psummary` varchar(50) NOT NULL,
  `picon` varchar(200) NOT NULL,
  `unit` varchar(200) NOT NULL,
  `count` int(10) DEFAULT '1',
  `percent` int(3) DEFAULT '0',
  `sort` int(3) DEFAULT '0',
  `left_count` int(10) DEFAULT '1',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_zjp_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(10) NOT NULL,
  `uid` varchar(200) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `award_status` int(1) DEFAULT '0',
  `msg` varchar(500) DEFAULT NULL,
  `stauts` int(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_zjp_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `share_award_count` int(10) DEFAULT '0',
  `play_count` int(10) DEFAULT '0',
  `award_play_count` int(10) DEFAULT '0',
  `share_count` int(10) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('mon_zjp',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_zjp',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `rid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `starttime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `endtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'index_pic')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `index_pic` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'prize_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `prize_intro` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'rule_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `rule_intro` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'share_award_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `share_award_enable` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp',  'share_award_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `share_award_count` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp',  'share_award_time')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `share_award_time` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp',  'u_award_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `u_award_count` int(3) DEFAULT '1';");
}
if(!pdo_fieldexists('mon_zjp',  'play_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `play_count` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `follow_url` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `copyright` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `new_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'new_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `new_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'new_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `new_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `share_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `share_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `share_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'status')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `status` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp',  'banner_ad_pic')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `banner_ad_pic` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'dialog_tips')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `dialog_tips` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'success_award_tips')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `success_award_tips` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'fail_award_tips')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `fail_award_tips` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'lock_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `lock_tip` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'day_play_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `day_play_count` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'prize_sharebtn_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `prize_sharebtn_name` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'luck_sharebtn_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `luck_sharebtn_name` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_zjp_prize',  'zid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `zid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_prize',  'pname')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `pname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_prize',  'psummary')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `psummary` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_prize',  'picon')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `picon` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_prize',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `unit` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_prize',  'count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `count` int(10) DEFAULT '1';");
}
if(!pdo_fieldexists('mon_zjp_prize',  'percent')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `percent` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_prize',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `sort` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_prize',  'left_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `left_count` int(10) DEFAULT '1';");
}
if(!pdo_fieldexists('mon_zjp_prize',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_prize')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_zjp_record',  'zid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_record')." ADD `zid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_record')." ADD `uid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_record')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_record',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_record')." ADD `pid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp_record',  'award_status')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_record')." ADD `award_status` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_record',  'msg')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_record')." ADD `msg` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp_record',  'stauts')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_record')." ADD `stauts` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_record')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_zjp_user',  'zid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `zid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_user',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `tel` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_zjp_user',  'share_award_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `share_award_count` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_user',  'play_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `play_count` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_user',  'award_play_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `award_play_count` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_zjp_user',  'share_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `share_count` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_zjp_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_zjp_user')." ADD `createtime` int(10) DEFAULT '0';");
}

?>