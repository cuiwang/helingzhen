<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_weisrc_diandeng_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `prizetype` varchar(50) DEFAULT '',
  `prizename` varchar(50) DEFAULT '',
  `prizetotal` int(11) DEFAULT '0',
  `prizepic` varchar(500) DEFAULT '',
  `dateline` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_diandeng_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `headimgurl` varchar(500) NOT NULL DEFAULT '' COMMENT '头像',
  `nickname` varchar(50) DEFAULT '',
  `username` varchar(50) DEFAULT '',
  `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `address` varchar(200) DEFAULT '',
  `todaynum` int(11) DEFAULT '0',
  `totalnum` int(11) DEFAULT '0',
  `awardnum` int(11) DEFAULT '0',
  `last_time` int(10) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  `success_time` int(10) DEFAULT '0',
  `issuccess` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `issend` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_diandeng_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `help_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `prizetype` varchar(50) DEFAULT '' COMMENT '字类型',
  `total` int(10) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  `status` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_diandeng_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `content` varchar(200) DEFAULT '',
  `rule` text,
  `rule2` text,
  `start_picurl` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `ticket_information` varchar(200) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `tips` varchar(500) DEFAULT '',
  `repeat_lottery_reply` varchar(50) DEFAULT '',
  `end_theme` varchar(50) DEFAULT '',
  `end_instruction` varchar(200) DEFAULT '',
  `end_picurl` varchar(200) DEFAULT '',
  `c_type_one` varchar(20) DEFAULT '',
  `c_img1_one` varchar(200) DEFAULT '',
  `c_img2_one` varchar(200) DEFAULT '',
  `c_name_one` varchar(50) DEFAULT '',
  `c_num_one` int(11) DEFAULT '0',
  `c_draw_one` int(11) DEFAULT '0',
  `c_rate_one` double DEFAULT '0',
  `c_type_two` varchar(20) DEFAULT '',
  `c_img1_two` varchar(200) DEFAULT '',
  `c_img2_two` varchar(200) DEFAULT '',
  `c_name_two` varchar(50) DEFAULT '',
  `c_num_two` int(11) DEFAULT '0',
  `c_draw_two` int(11) DEFAULT '0',
  `c_rate_two` double DEFAULT '0',
  `c_type_three` varchar(20) DEFAULT '',
  `c_img1_three` varchar(200) DEFAULT '',
  `c_img2_three` varchar(200) DEFAULT '',
  `c_name_three` varchar(50) DEFAULT '',
  `c_num_three` int(11) DEFAULT '0',
  `c_draw_three` int(11) DEFAULT '0',
  `c_rate_three` double DEFAULT '0',
  `c_type_four` varchar(20) DEFAULT '',
  `c_img1_four` varchar(200) DEFAULT '',
  `c_img2_four` varchar(200) DEFAULT '',
  `c_name_four` varchar(50) DEFAULT '',
  `c_num_four` int(11) DEFAULT '0',
  `c_draw_four` int(11) DEFAULT '0',
  `c_rate_four` double DEFAULT '0',
  `c_type_five` varchar(20) DEFAULT '',
  `c_img1_five` varchar(200) DEFAULT '',
  `c_img2_five` varchar(200) DEFAULT '',
  `c_name_five` varchar(50) DEFAULT '',
  `c_num_five` int(11) DEFAULT '0',
  `c_draw_five` int(11) DEFAULT '0',
  `c_rate_five` double DEFAULT '0',
  `c_type_six` varchar(20) DEFAULT '',
  `c_img1_six` varchar(200) DEFAULT '',
  `c_img2_six` varchar(200) DEFAULT '',
  `c_name_six` varchar(50) DEFAULT '',
  `c_num_six` int(11) DEFAULT '0',
  `c_draw_six` int(10) DEFAULT '0',
  `c_rate_six` double DEFAULT '0',
  `c_type_seven` varchar(20) DEFAULT '',
  `c_img1_seven` varchar(200) DEFAULT '',
  `c_img2_seven` varchar(200) DEFAULT '',
  `c_name_seven` varchar(50) DEFAULT '',
  `c_num_seven` int(11) DEFAULT '0',
  `c_draw_seven` int(10) DEFAULT '0',
  `c_rate_seven` double DEFAULT '0',
  `c_type_eight` varchar(20) DEFAULT '',
  `c_img1_eight` varchar(200) DEFAULT '',
  `c_img2_eight` varchar(200) DEFAULT '',
  `c_name_eight` varchar(50) DEFAULT '',
  `c_num_eight` int(11) DEFAULT '0',
  `c_draw_eight` int(10) DEFAULT '0',
  `c_rate_eight` double DEFAULT '0',
  `c_unit_one` varchar(20) DEFAULT '',
  `c_unit_two` varchar(20) DEFAULT '',
  `c_unit_three` varchar(20) DEFAULT '',
  `c_unit_four` varchar(20) DEFAULT '',
  `c_unit_five` varchar(20) DEFAULT '',
  `logo` varchar(500) DEFAULT '',
  `bg` varchar(500) DEFAULT '',
  `light` varchar(500) DEFAULT '',
  `light2` varchar(500) DEFAULT '',
  `paper` varchar(500) DEFAULT '',
  `qrcode` varchar(500) DEFAULT '',
  `music_url` varchar(500) DEFAULT '',
  `total_num` int(11) DEFAULT '0' COMMENT '总获奖人数(自动加)',
  `probability` double DEFAULT '0',
  `total_award` int(11) DEFAULT '0' COMMENT '总奖品数量',
  `award_times` int(11) DEFAULT '0',
  `number_times` int(11) DEFAULT '0',
  `most_num_times` int(11) DEFAULT '0',
  `copyright` varchar(50) DEFAULT '',
  `copyrighturl` varchar(200) DEFAULT '',
  `address` varchar(200) DEFAULT '',
  `show_num` tinyint(2) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `fansnum` int(11) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  `isage` tinyint(1) DEFAULT '0',
  `isweixin` tinyint(1) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_image` varchar(500) DEFAULT '',
  `share_url` varchar(500) DEFAULT '',
  `follow_url` varchar(500) DEFAULT '',
  `isneedfollow` tinyint(1) DEFAULT '1',
  `share_txt` varchar(500) DEFAULT '',
  `award_url` varchar(500) DEFAULT '',
  `award_tip` varchar(200) DEFAULT '',
  `mode` tinyint(1) DEFAULT '1',
  `isusername` tinyint(1) DEFAULT '1',
  `istel` tinyint(1) DEFAULT '1',
  `isaddress` tinyint(1) DEFAULT '1',
  `day_times` int(11) DEFAULT '5',
  `total_times` int(11) DEFAULT '10',
  `ishelpfollow` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('weisrc_diandeng_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_diandeng_award',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_award',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD `prizetype` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_award',  'prizename')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD `prizename` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_award',  'prizetotal')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD `prizetotal` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_award',  'prizepic')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD `prizepic` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_award',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_award',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD `status` tinyint(4) DEFAULT '0';");
}
if(!pdo_indexexists('weisrc_diandeng_award',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('weisrc_diandeng_award',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_award')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `from_user` varchar(50) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `headimgurl` varchar(500) NOT NULL DEFAULT '' COMMENT '头像';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `nickname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `username` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `address` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'todaynum')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `todaynum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'totalnum')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `totalnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'awardnum')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `awardnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'last_time')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `last_time` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'success_time')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `success_time` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'issuccess')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `issuccess` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `status` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_diandeng_fans',  'issend')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD `issend` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('weisrc_diandeng_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('weisrc_diandeng_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_diandeng_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_record',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_record',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD `from_user` varchar(100) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('weisrc_diandeng_record',  'help_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD `help_user` varchar(100) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('weisrc_diandeng_record',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD `prizetype` varchar(50) DEFAULT '' COMMENT '字类型';");
}
if(!pdo_fieldexists('weisrc_diandeng_record',  'total')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD `total` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_record',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_indexexists('weisrc_diandeng_record',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('weisrc_diandeng_record',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_record')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `content` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `rule` text;");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'rule2')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `rule2` text;");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'start_picurl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `start_picurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `isshow` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'ticket_information')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `ticket_information` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'tips')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `tips` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'repeat_lottery_reply')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `repeat_lottery_reply` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'end_theme')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `end_theme` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'end_instruction')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `end_instruction` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'end_picurl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `end_picurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_type_one')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_type_one` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img1_one')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img1_one` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img2_one')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img2_one` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_name_one')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_name_one` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_num_one')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_num_one` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_draw_one')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_draw_one` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_rate_one')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_rate_one` double DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_type_two')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_type_two` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img1_two')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img1_two` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img2_two')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img2_two` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_name_two')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_name_two` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_num_two')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_num_two` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_draw_two')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_draw_two` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_rate_two')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_rate_two` double DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_type_three')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_type_three` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img1_three')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img1_three` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img2_three')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img2_three` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_name_three')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_name_three` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_num_three')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_num_three` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_draw_three')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_draw_three` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_rate_three')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_rate_three` double DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_type_four')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_type_four` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img1_four')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img1_four` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img2_four')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img2_four` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_name_four')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_name_four` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_num_four')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_num_four` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_draw_four')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_draw_four` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_rate_four')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_rate_four` double DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_type_five')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_type_five` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img1_five')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img1_five` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img2_five')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img2_five` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_name_five')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_name_five` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_num_five')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_num_five` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_draw_five')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_draw_five` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_rate_five')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_rate_five` double DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_type_six')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_type_six` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img1_six')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img1_six` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img2_six')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img2_six` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_name_six')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_name_six` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_num_six')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_num_six` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_draw_six')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_draw_six` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_rate_six')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_rate_six` double DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_type_seven')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_type_seven` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img1_seven')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img1_seven` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img2_seven')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img2_seven` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_name_seven')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_name_seven` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_num_seven')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_num_seven` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_draw_seven')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_draw_seven` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_rate_seven')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_rate_seven` double DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_type_eight')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_type_eight` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img1_eight')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img1_eight` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_img2_eight')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_img2_eight` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_name_eight')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_name_eight` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_num_eight')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_num_eight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_draw_eight')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_draw_eight` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_rate_eight')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_rate_eight` double DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_unit_one')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_unit_one` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_unit_two')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_unit_two` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_unit_three')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_unit_three` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_unit_four')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_unit_four` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'c_unit_five')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `c_unit_five` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `logo` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'bg')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `bg` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'light')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `light` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'light2')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `light2` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'paper')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `paper` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `qrcode` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'music_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `music_url` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'total_num')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `total_num` int(11) DEFAULT '0' COMMENT '总获奖人数(自动加)';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'probability')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `probability` double DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'total_award')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `total_award` int(11) DEFAULT '0' COMMENT '总奖品数量';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'award_times')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `award_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'number_times')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `number_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'most_num_times')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `most_num_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `copyright` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'copyrighturl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `copyrighturl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `address` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'show_num')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `show_num` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `viewnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `fansnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'isage')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `isage` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'isweixin')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `isweixin` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `share_title` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `share_desc` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'share_image')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `share_image` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `share_url` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `follow_url` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'isneedfollow')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `isneedfollow` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'share_txt')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `share_txt` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'award_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `award_url` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'award_tip')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `award_tip` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'mode')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `mode` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'isusername')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `isusername` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'istel')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `istel` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'isaddress')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `isaddress` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'day_times')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `day_times` int(11) DEFAULT '5';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'total_times')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `total_times` int(11) DEFAULT '10';");
}
if(!pdo_fieldexists('weisrc_diandeng_reply',  'ishelpfollow')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD `ishelpfollow` tinyint(1) DEFAULT '1';");
}
if(!pdo_indexexists('weisrc_diandeng_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('weisrc_diandeng_reply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_diandeng_reply')." ADD KEY `indx_uniacid` (`uniacid`);");
}

?>