<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_bigwheel_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `description` varchar(200) DEFAULT '' COMMENT '描述',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `award_sn` varchar(50) DEFAULT '' COMMENT 'SN',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_bigwheel_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `todaynum` int(11) DEFAULT '0',
  `totalnum` int(11) DEFAULT '0',
  `awardnum` int(11) DEFAULT '0',
  `last_time` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_bigwheel_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `content` varchar(200) DEFAULT '',
  `start_picurl` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `ticket_information` varchar(200) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `repeat_lottery_reply` varchar(50) DEFAULT '',
  `end_theme` varchar(50) DEFAULT '',
  `end_instruction` varchar(200) DEFAULT '',
  `end_picurl` varchar(200) DEFAULT '',
  `c_type_one` varchar(20) DEFAULT '',
  `c_name_one` varchar(50) DEFAULT '',
  `c_num_one` int(11) DEFAULT '0',
  `c_draw_one` int(11) DEFAULT '0',
  `c_rate_one` double DEFAULT '0',
  `c_type_two` varchar(20) DEFAULT '',
  `c_name_two` varchar(50) DEFAULT '',
  `c_num_two` int(11) DEFAULT '0',
  `c_draw_two` int(11) DEFAULT '0',
  `c_rate_two` double DEFAULT '0',
  `c_type_three` varchar(20) DEFAULT '',
  `c_name_three` varchar(50) DEFAULT '',
  `c_num_three` int(11) DEFAULT '0',
  `c_draw_three` int(11) DEFAULT '0',
  `c_rate_three` double DEFAULT '0',
  `c_type_four` varchar(20) DEFAULT '',
  `c_name_four` varchar(50) DEFAULT '',
  `c_num_four` int(11) DEFAULT '0',
  `c_draw_four` int(11) DEFAULT '0',
  `c_rate_four` double DEFAULT '0',
  `c_type_five` varchar(20) DEFAULT '',
  `c_name_five` varchar(50) DEFAULT '',
  `c_num_five` int(11) DEFAULT '0',
  `c_draw_five` int(11) DEFAULT '0',
  `c_rate_five` double DEFAULT '0',
  `c_type_six` varchar(20) DEFAULT '',
  `c_name_six` varchar(50) DEFAULT '',
  `c_num_six` int(11) DEFAULT '0',
  `c_draw_six` int(10) DEFAULT '0',
  `c_rate_six` double DEFAULT '0',
  `total_num` int(11) DEFAULT '0' COMMENT '总获奖人数(自动加)',
  `probability` double DEFAULT '0',
  `award_times` int(11) DEFAULT '0',
  `number_times` int(11) DEFAULT '0',
  `most_num_times` int(11) DEFAULT '0',
  `sn_code` tinyint(4) DEFAULT '0',
  `sn_rename` varchar(20) DEFAULT '',
  `tel_rename` varchar(20) DEFAULT '',
  `copyright` varchar(20) DEFAULT '',
  `show_num` tinyint(2) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0',
  `fansnum` int(11) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `share_txt` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('bigwheel_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('bigwheel_award',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_award',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_award',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID';");
}
if(!pdo_fieldexists('bigwheel_award',  'name')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `name` varchar(50) DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists('bigwheel_award',  'description')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `description` varchar(200) DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('bigwheel_award',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `prizetype` varchar(10) DEFAULT '' COMMENT '类型';");
}
if(!pdo_fieldexists('bigwheel_award',  'award_sn')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `award_sn` varchar(50) DEFAULT '' COMMENT 'SN';");
}
if(!pdo_fieldexists('bigwheel_award',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_award',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `consumetime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_award',  'status')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD `status` tinyint(4) DEFAULT '0';");
}
if(!pdo_indexexists('bigwheel_award',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('bigwheel_award',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_award')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('bigwheel_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('bigwheel_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_fans',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `from_user` varchar(50) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('bigwheel_fans',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)';");
}
if(!pdo_fieldexists('bigwheel_fans',  'todaynum')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `todaynum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_fans',  'totalnum')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `totalnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_fans',  'awardnum')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `awardnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_fans',  'last_time')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `last_time` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_indexexists('bigwheel_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('bigwheel_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('bigwheel_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'content')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `content` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'start_picurl')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `start_picurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `isshow` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'ticket_information')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `ticket_information` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'repeat_lottery_reply')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `repeat_lottery_reply` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'end_theme')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `end_theme` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'end_instruction')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `end_instruction` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'end_picurl')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `end_picurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_type_one')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_type_one` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_name_one')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_name_one` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_num_one')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_num_one` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_draw_one')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_draw_one` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_rate_one')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_rate_one` double DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_type_two')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_type_two` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_name_two')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_name_two` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_num_two')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_num_two` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_draw_two')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_draw_two` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_rate_two')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_rate_two` double DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_type_three')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_type_three` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_name_three')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_name_three` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_num_three')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_num_three` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_draw_three')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_draw_three` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_rate_three')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_rate_three` double DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_type_four')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_type_four` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_name_four')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_name_four` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_num_four')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_num_four` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_draw_four')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_draw_four` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_rate_four')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_rate_four` double DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_type_five')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_type_five` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_name_five')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_name_five` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_num_five')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_num_five` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_draw_five')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_draw_five` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_rate_five')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_rate_five` double DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_type_six')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_type_six` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_name_six')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_name_six` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_num_six')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_num_six` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_draw_six')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_draw_six` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'c_rate_six')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `c_rate_six` double DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'total_num')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `total_num` int(11) DEFAULT '0' COMMENT '总获奖人数(自动加)';");
}
if(!pdo_fieldexists('bigwheel_reply',  'probability')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `probability` double DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'award_times')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `award_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'number_times')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `number_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'most_num_times')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `most_num_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'sn_code')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `sn_code` tinyint(4) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'sn_rename')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `sn_rename` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'tel_rename')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `tel_rename` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `copyright` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'show_num')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `show_num` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `viewnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `fansnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('bigwheel_reply',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `share_title` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `share_desc` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `share_url` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('bigwheel_reply',  'share_txt')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD `share_txt` text NOT NULL;");
}
if(!pdo_indexexists('bigwheel_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('bigwheel_reply',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('bigwheel_reply')." ADD KEY `indx_weid` (`weid`);");
}

?>