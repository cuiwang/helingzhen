<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_lee_xiangaitou` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `rule` varchar(1000) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `day_play_count` int(3) DEFAULT '0',
  `award_count` int(11) DEFAULT '0',
  `copyright` varchar(100) NOT NULL,
  `prize_level_0` varchar(100) DEFAULT '没有中奖',
  `prize_name_0` varchar(100) NOT NULL,
  `prize_p_0` int(3) NOT NULL,
  `prize_level_1` varchar(100) DEFAULT '一等奖',
  `prize_name_1` varchar(100) NOT NULL,
  `prize_p_1` int(3) NOT NULL DEFAULT '0',
  `prize_num_1` int(10) NOT NULL,
  `prize_level_2` varchar(100) DEFAULT '二等奖',
  `prize_name_2` varchar(100) NOT NULL,
  `prize_img_2` varchar(200) NOT NULL,
  `prize_p_2` int(3) NOT NULL,
  `prize_num_2` int(10) NOT NULL DEFAULT '0',
  `prize_level_3` varchar(100) DEFAULT '三等奖',
  `prize_name_3` varchar(100) NOT NULL,
  `prize_p_3` int(3) NOT NULL,
  `prize_num_3` int(10) NOT NULL DEFAULT '0',
  `prize_level_4` varchar(100) DEFAULT '四等奖',
  `prize_name_4` varchar(100) NOT NULL,
  `prize_p_4` int(3) NOT NULL,
  `prize_num_4` int(10) NOT NULL DEFAULT '0',
  `prize_level_5` varchar(100) DEFAULT '五等奖',
  `prize_name_5` varchar(100) NOT NULL,
  `prize_p_5` int(3) NOT NULL,
  `prize_num_5` int(10) NOT NULL DEFAULT '0',
  `new_title` varchar(200) DEFAULT NULL,
  `new_pic` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lee_xiangaitou_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `xgtid` int(10) NOT NULL,
  `from_user` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  `uid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lee_xiangaitou_user_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `xgtid` int(10) NOT NULL,
  `uid` varchar(200) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `award_name` varchar(200) NOT NULL,
  `award_level` varchar(200) NOT NULL,
  `level` int(3) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `remark` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lee_xiangaitou_user_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `xgtid` int(10) NOT NULL,
  `uid` varchar(200) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `award_name` varchar(200) NOT NULL,
  `award_level` varchar(200) NOT NULL,
  `level` int(3) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('lee_xiangaitou',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `rid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `starttime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `endtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `rule` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `follow_url` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'day_play_count')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `day_play_count` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'award_count')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `award_count` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `copyright` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_level_0')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_level_0` varchar(100) DEFAULT '没有中奖';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_name_0')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_name_0` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_p_0')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_p_0` int(3) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_level_1')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_level_1` varchar(100) DEFAULT '一等奖';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_name_1')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_name_1` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_p_1')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_p_1` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_num_1')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_num_1` int(10) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_level_2')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_level_2` varchar(100) DEFAULT '二等奖';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_name_2')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_name_2` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_img_2')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_img_2` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_p_2')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_p_2` int(3) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_num_2')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_num_2` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_level_3')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_level_3` varchar(100) DEFAULT '三等奖';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_name_3')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_name_3` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_p_3')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_p_3` int(3) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_num_3')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_num_3` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_level_4')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_level_4` varchar(100) DEFAULT '四等奖';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_name_4')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_name_4` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_p_4')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_p_4` int(3) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_num_4')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_num_4` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_level_5')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_level_5` varchar(100) DEFAULT '五等奖';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_name_5')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_name_5` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_p_5')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_p_5` int(3) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'prize_num_5')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `prize_num_5` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `new_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'new_pic')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `new_pic` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'new_content')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `new_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `share_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `share_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `share_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'xgtid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `xgtid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `from_user` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'address')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `address` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `mobile` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `realname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user')." ADD `uid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'xgtid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `xgtid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `uid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'award_name')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `award_name` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'award_level')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `award_level` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'level')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `level` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'status')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `remark` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_award',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_award')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou_user_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_record',  'xgtid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_record')." ADD `xgtid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_record')." ADD `uid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_record')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_record',  'award_name')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_record')." ADD `award_name` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_record',  'award_level')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_record')." ADD `award_level` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('lee_xiangaitou_user_record',  'level')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_record')." ADD `level` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('lee_xiangaitou_user_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lee_xiangaitou_user_record')." ADD `createtime` int(10) DEFAULT '0';");
}

?>