<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_haoman_voice_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `awardname` varchar(50) DEFAULT '' COMMENT '描述',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `awardsimg` varchar(200) NOT NULL COMMENT '奖品图片',
  `prize` int(11) DEFAULT '0' COMMENT '奖品ID',
  `credit` int(11) DEFAULT '0' COMMENT '奖品金额',
  `translateResult` varchar(255) NOT NULL DEFAULT '' COMMENT '口令内容',
  `serverId` varchar(255) NOT NULL DEFAULT '',
  `localId` varchar(255) NOT NULL DEFAULT '',
  `match` varchar(255) NOT NULL DEFAULT '',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_fansID` (`fansID`),
  KEY `indx_from_user` (`from_user`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_cardticket` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `createtime` varchar(20) DEFAULT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_cash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `awardname` varchar(50) DEFAULT '' COMMENT '兑奖金额',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `awardsimg` varchar(200) NOT NULL COMMENT '奖品图片',
  `prize` int(11) DEFAULT '0' COMMENT '奖品ID',
  `credit` int(11) DEFAULT '0' COMMENT '奖品金额',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `pici` varchar(50) DEFAULT '0' COMMENT '批次',
  `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '口令',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `today_most_times` int(11) DEFAULT '0' COMMENT '每日最多次数',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间',
  `awardingid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖地址ID',
  `todaynum` int(11) DEFAULT '0',
  `totalnum` int(11) DEFAULT '0',
  `is_hbok` int(11) DEFAULT '0',
  `daynum` int(11) DEFAULT '0',
  `awardnum` int(11) DEFAULT '0',
  `last_time` int(10) DEFAULT '0',
  `zhongjiang` tinyint(1) DEFAULT '0',
  `isad` tinyint(1) DEFAULT '0',
  `isshare` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_fansID` (`fansID`),
  KEY `indx_from_user` (`from_user`),
  KEY `indx_nickname` (`nickname`),
  KEY `indx_createtime` (`createtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_hb` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `set` text,
  `mchid` varchar(100) NOT NULL DEFAULT '' COMMENT '商户号',
  `password` varchar(2550) NOT NULL DEFAULT '' COMMENT '商户密码',
  `appid` varchar(100) NOT NULL DEFAULT '' COMMENT '服务号ID',
  `secret` varchar(255) NOT NULL DEFAULT '' COMMENT '服务号secret',
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT '服务器IP',
  `sname` varchar(100) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `wishing` varchar(100) NOT NULL DEFAULT '' COMMENT '祝福语',
  `actname` varchar(100) NOT NULL DEFAULT '' COMMENT '红包活动名称',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo',
  `hbwishing` varchar(100) DEFAULT '' COMMENT '红包祝福语',
  `hbactname` varchar(100) DEFAULT '' COMMENT '红包活动',
  `hbremark` varchar(150) DEFAULT '' COMMENT '红包描述',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_jiequan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_password` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `pwid` varchar(50) NOT NULL DEFAULT '' COMMENT '口令ID',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_pici` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `pici` varchar(10) DEFAULT '' COMMENT '批次',
  `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称',
  `codenum` int(11) DEFAULT '0' COMMENT '口令数量',
  `is_qrcode` int(11) DEFAULT '0' COMMENT '是否生成二维码',
  `is_qrcode2` int(11) DEFAULT '0' COMMENT '是否生成永久二维码',
  `createtime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `turntable` int(10) unsigned NOT NULL COMMENT '转盘类型',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `couponid` varchar(255) NOT NULL COMMENT '微信卡券ID',
  `awardspro` double DEFAULT '0' COMMENT '奖品概率',
  `awardstotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `awardsimg` varchar(255) NOT NULL COMMENT '奖品图片',
  `pici` varchar(10) NOT NULL COMMENT '批次',
  `prizetxt` text NOT NULL COMMENT '奖品说明',
  `credit` int(10) NOT NULL COMMENT '积分',
  `credit2` int(10) NOT NULL COMMENT '积分2',
  `ppd` int(10) NOT NULL COMMENT '匹配度',
  `ppd2` int(10) NOT NULL COMMENT '匹配度2',
  `ptype` tinyint(1) DEFAULT '0' COMMENT '奖项类型',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_pw` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `pici` varchar(10) NOT NULL COMMENT '批次',
  `pwid` varchar(50) NOT NULL DEFAULT '' COMMENT '口令标示符',
  `title` varchar(35) DEFAULT NULL COMMENT '口令',
  `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称',
  `iscqr` int(11) DEFAULT '0' COMMENT '是否生成二维码',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `num` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_num` (`num`),
  KEY `indx_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_qrcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `pici` varchar(50) DEFAULT '0' COMMENT '批次',
  `scene_str` varchar(50) DEFAULT '0',
  `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称',
  `acid` varchar(50) DEFAULT '0' COMMENT 'acid',
  `qrcid` varchar(50) DEFAULT '0' COMMENT 'id',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '口令',
  `keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `model` varchar(255) NOT NULL DEFAULT '' COMMENT '模块',
  `ticket` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `expire` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(255) NOT NULL DEFAULT '',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `code_status` int(10) DEFAULT '0',
  `make` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_haoman_voice_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `rules` text NOT NULL COMMENT '参与活动规则',
  `start_picurl` varchar(200) DEFAULT '',
  `start_theme` tinyint(1) DEFAULT '0',
  `start_title` varchar(30) DEFAULT '',
  `backpicurl` varchar(200) DEFAULT '',
  `backcolor` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `ziliao` tinyint(1) DEFAULT '0',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `start_hour` int(10) DEFAULT '0',
  `end_hour` int(10) DEFAULT '0',
  `all_money` int(10) DEFAULT '0',
  `turntable` tinyint(1) DEFAULT '0',
  `is_error` tinyint(1) DEFAULT '0',
  `adpic` varchar(200) DEFAULT '',
  `noip_url` varchar(200) DEFAULT '',
  `adpicurl` varchar(200) DEFAULT '',
  `total_num` int(11) DEFAULT '0' COMMENT '总获奖人数(自动加)',
  `probability` double DEFAULT '0',
  `award_times` int(11) DEFAULT '0',
  `password` int(11) DEFAULT '0',
  `number_times` int(11) DEFAULT '0',
  `count_time` int(11) DEFAULT '0',
  `hb_today_times` int(11) DEFAULT '0',
  `open_p` int(11) DEFAULT '0',
  `most_num_times` int(11) DEFAULT '0',
  `is_show_prize` int(11) DEFAULT '0',
  `is_show_prize_num` int(11) DEFAULT '0',
  `today_most_times` int(11) DEFAULT '0',
  `key_money1` int(11) DEFAULT '0',
  `key_money2` int(11) DEFAULT '0',
  `match` int(11) DEFAULT '0',
  `ptype` varchar(20) DEFAULT '',
  `follow_url` varchar(255) DEFAULT '',
  `up_qrcode` varchar(255) DEFAULT '',
  `credit1` int(11) DEFAULT '0',
  `rec_show` int(11) DEFAULT '0',
  `rec_show2` int(11) DEFAULT '0',
  `copyright` varchar(20) DEFAULT '',
  `tiemadpic` varchar(200) DEFAULT '',
  `timead` int(10) DEFAULT '0',
  `timenum` int(10) DEFAULT '0',
  `time_style` int(10) DEFAULT '0',
  `share_gift` text NOT NULL COMMENT '赠送礼物',
  `timeadurl` varchar(255) DEFAULT '0',
  `viewnum` int(11) DEFAULT '0' COMMENT '浏览次数',
  `fansnum` int(11) DEFAULT '0' COMMENT '参与人数',
  `createtime` int(10) DEFAULT '0',
  `share_acid` int(10) DEFAULT '0',
  `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `qjbpic` varchar(225) NOT NULL COMMENT '活动页面顶部背景',
  `qjbimg` varchar(225) NOT NULL COMMENT '指针图',
  `gl_openid` varchar(300) NOT NULL COMMENT '管理员OPENID',
  `rankname` varchar(300) NOT NULL COMMENT '排行榜名称',
  `share_gift_img` varchar(225) NOT NULL COMMENT '分享奖品图',
  `share_imgurl` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_type` tinyint(1) DEFAULT '0',
  `share_gift_num` int(11) DEFAULT '0' COMMENT '分享赠送抽奖基数',
  `share_gift_num2` int(11) DEFAULT '0' COMMENT '分享赠送抽奖基数',
  `sharenum` int(11) DEFAULT '0' COMMENT '分享赠送抽奖基数',
  `sharenumtop` int(11) DEFAULT '0' COMMENT '分享赠送抽奖基数',
  `share_url` varchar(300) DEFAULT '',
  `getip` tinyint(1) DEFAULT '0',
  `getip_addr` text NOT NULL COMMENT '限制地区ip',
  `key` varchar(145) DEFAULT NULL,
  `allowip` text NOT NULL,
  `isallowip` tinyint(2) DEFAULT '0',
  `isappkey` tinyint(2) DEFAULT '0',
  `bd_key` varchar(50) DEFAULT '0',
  `address_sf` text NOT NULL,
  `address_sq` text NOT NULL,
  `address_qx` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('haoman_voice_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_award',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_award',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_award',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID';");
}
if(!pdo_fieldexists('haoman_voice_award',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('haoman_voice_award',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('haoman_voice_award',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('haoman_voice_award',  'awardname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `awardname` varchar(50) DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('haoman_voice_award',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `prizetype` varchar(10) DEFAULT '' COMMENT '类型';");
}
if(!pdo_fieldexists('haoman_voice_award',  'awardsimg')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `awardsimg` varchar(200) NOT NULL COMMENT '奖品图片';");
}
if(!pdo_fieldexists('haoman_voice_award',  'prize')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `prize` int(11) DEFAULT '0' COMMENT '奖品ID';");
}
if(!pdo_fieldexists('haoman_voice_award',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `credit` int(11) DEFAULT '0' COMMENT '奖品金额';");
}
if(!pdo_fieldexists('haoman_voice_award',  'translateResult')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `translateResult` varchar(255) NOT NULL DEFAULT '' COMMENT '口令内容';");
}
if(!pdo_fieldexists('haoman_voice_award',  'serverId')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `serverId` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_award',  'localId')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `localId` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_award',  'match')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `match` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_award',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_award',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `consumetime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_award',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_voice_award',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_award',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('haoman_voice_award',  'indx_fansID')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD KEY `indx_fansID` (`fansID`);");
}
if(!pdo_indexexists('haoman_voice_award',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_indexexists('haoman_voice_award',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_indexexists('haoman_voice_award',  'indx_status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_award')." ADD KEY `indx_status` (`status`);");
}
if(!pdo_fieldexists('haoman_voice_cardticket',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cardticket')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_cardticket',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cardticket')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('haoman_voice_cardticket',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cardticket')." ADD `createtime` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('haoman_voice_cardticket',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cardticket')." ADD `ticket` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('haoman_voice_cash',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_cash',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'awardname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `awardname` varchar(50) DEFAULT '' COMMENT '兑奖金额';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `prizetype` varchar(10) DEFAULT '' COMMENT '类型';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'awardsimg')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `awardsimg` varchar(200) NOT NULL COMMENT '奖品图片';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'prize')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `prize` int(11) DEFAULT '0' COMMENT '奖品ID';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `credit` int(11) DEFAULT '0' COMMENT '奖品金额';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `consumetime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_cash',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_voice_cash',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_cash',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_cash')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('haoman_voice_code',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_code',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_code',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_code',  'pici')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD `pici` varchar(50) DEFAULT '0' COMMENT '批次';");
}
if(!pdo_fieldexists('haoman_voice_code',  'rulename')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称';");
}
if(!pdo_fieldexists('haoman_voice_code',  'code')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD `code` varchar(255) NOT NULL DEFAULT '' COMMENT '口令';");
}
if(!pdo_fieldexists('haoman_voice_code',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_code',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD `consumetime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_code',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_voice_code',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_code',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_code')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('haoman_voice_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_data',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('haoman_voice_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('haoman_voice_data',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('haoman_voice_data',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid';");
}
if(!pdo_fieldexists('haoman_voice_data',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('haoman_voice_data',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('haoman_voice_data',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP';");
}
if(!pdo_fieldexists('haoman_voice_data',  'visitorstime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('haoman_voice_data',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数';");
}
if(!pdo_indexexists('haoman_voice_data',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_data',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_data')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('haoman_voice_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `from_user` varchar(50) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'today_most_times')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `today_most_times` int(11) DEFAULT '0' COMMENT '每日最多次数';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'sharetime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'awardingid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `awardingid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖地址ID';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'todaynum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `todaynum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'totalnum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `totalnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'is_hbok')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `is_hbok` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'daynum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `daynum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'awardnum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `awardnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'last_time')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `last_time` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'zhongjiang')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `zhongjiang` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'isad')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `isad` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'isshare')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `isshare` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_voice_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_fans',  'indx_fansID')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD KEY `indx_fansID` (`fansID`);");
}
if(!pdo_indexexists('haoman_voice_fans',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_indexexists('haoman_voice_fans',  'indx_nickname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD KEY `indx_nickname` (`nickname`);");
}
if(!pdo_indexexists('haoman_voice_fans',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_fans')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('haoman_voice_hb',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_hb',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'set')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `set` text;");
}
if(!pdo_fieldexists('haoman_voice_hb',  'mchid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `mchid` varchar(100) NOT NULL DEFAULT '' COMMENT '商户号';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'password')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `password` varchar(2550) NOT NULL DEFAULT '' COMMENT '商户密码';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `appid` varchar(100) NOT NULL DEFAULT '' COMMENT '服务号ID';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `secret` varchar(255) NOT NULL DEFAULT '' COMMENT '服务号secret';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `ip` varchar(100) NOT NULL DEFAULT '' COMMENT '服务器IP';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'sname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `sname` varchar(100) NOT NULL DEFAULT '' COMMENT '公众号名称';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'wishing')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `wishing` varchar(100) NOT NULL DEFAULT '' COMMENT '祝福语';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'actname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `actname` varchar(100) NOT NULL DEFAULT '' COMMENT '红包活动名称';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'hbwishing')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `hbwishing` varchar(100) DEFAULT '' COMMENT '红包祝福语';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'hbactname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `hbactname` varchar(100) DEFAULT '' COMMENT '红包活动';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'hbremark')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `hbremark` varchar(150) DEFAULT '' COMMENT '红包描述';");
}
if(!pdo_fieldexists('haoman_voice_hb',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_hb')." ADD `createtime` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_jiequan',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_jiequan')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_jiequan',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_jiequan')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_jiequan',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_jiequan')." ADD `appid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_jiequan',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_jiequan')." ADD `appsecret` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_jiequan',  'appid_share')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_jiequan')." ADD `appid_share` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_jiequan',  'appsecret_share')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_jiequan')." ADD `appsecret_share` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('haoman_voice_jiequan',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_jiequan')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('haoman_voice_password',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_password',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('haoman_voice_password',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('haoman_voice_password',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('haoman_voice_password',  'pwid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `pwid` varchar(50) NOT NULL DEFAULT '' COMMENT '口令ID';");
}
if(!pdo_fieldexists('haoman_voice_password',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('haoman_voice_password',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('haoman_voice_password',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP';");
}
if(!pdo_fieldexists('haoman_voice_password',  'visitorstime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('haoman_voice_password',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态';");
}
if(!pdo_indexexists('haoman_voice_password',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_password',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_password')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('haoman_voice_pici',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_pici',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_pici',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_pici',  'pici')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `pici` varchar(10) DEFAULT '' COMMENT '批次';");
}
if(!pdo_fieldexists('haoman_voice_pici',  'rulename')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称';");
}
if(!pdo_fieldexists('haoman_voice_pici',  'codenum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `codenum` int(11) DEFAULT '0' COMMENT '口令数量';");
}
if(!pdo_fieldexists('haoman_voice_pici',  'is_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `is_qrcode` int(11) DEFAULT '0' COMMENT '是否生成二维码';");
}
if(!pdo_fieldexists('haoman_voice_pici',  'is_qrcode2')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `is_qrcode2` int(11) DEFAULT '0' COMMENT '是否生成永久二维码';");
}
if(!pdo_fieldexists('haoman_voice_pici',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_pici',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_voice_pici',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_pici',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pici')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('haoman_voice_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_prize',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('haoman_voice_prize',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'turntable')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `turntable` int(10) unsigned NOT NULL COMMENT '转盘类型';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'prizename')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `prizename` varchar(50) NOT NULL COMMENT '奖品名称';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `couponid` varchar(255) NOT NULL COMMENT '微信卡券ID';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'awardspro')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `awardspro` double DEFAULT '0' COMMENT '奖品概率';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'awardstotal')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `awardstotal` int(10) NOT NULL COMMENT '奖品数量';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'prizedraw')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `prizedraw` int(10) NOT NULL COMMENT '中奖数量';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'awardsimg')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `awardsimg` varchar(255) NOT NULL COMMENT '奖品图片';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'pici')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `pici` varchar(10) NOT NULL COMMENT '批次';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'prizetxt')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `prizetxt` text NOT NULL COMMENT '奖品说明';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `credit` int(10) NOT NULL COMMENT '积分';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'credit2')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `credit2` int(10) NOT NULL COMMENT '积分2';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'ppd')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `ppd` int(10) NOT NULL COMMENT '匹配度';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'ppd2')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `ppd2` int(10) NOT NULL COMMENT '匹配度2';");
}
if(!pdo_fieldexists('haoman_voice_prize',  'ptype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD `ptype` tinyint(1) DEFAULT '0' COMMENT '奖项类型';");
}
if(!pdo_indexexists('haoman_voice_prize',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_prize',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_prize')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('haoman_voice_pw',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_pw',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `uniacid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('haoman_voice_pw',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'pici')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `pici` varchar(10) NOT NULL COMMENT '批次';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'pwid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `pwid` varchar(50) NOT NULL DEFAULT '' COMMENT '口令标示符';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `title` varchar(35) DEFAULT NULL COMMENT '口令';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'rulename')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'iscqr')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `iscqr` int(11) DEFAULT '0' COMMENT '是否生成二维码';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'num')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `num` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_pw',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD `createtime` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('haoman_voice_pw',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('haoman_voice_pw',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_pw',  'indx_num')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD KEY `indx_num` (`num`);");
}
if(!pdo_indexexists('haoman_voice_pw',  'indx_status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_pw')." ADD KEY `indx_status` (`status`);");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'pici')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `pici` varchar(50) DEFAULT '0' COMMENT '批次';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'scene_str')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `scene_str` varchar(50) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'rulename')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `acid` varchar(50) DEFAULT '0' COMMENT 'acid';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'qrcid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `qrcid` varchar(50) DEFAULT '0' COMMENT 'id';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'code')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `code` varchar(255) NOT NULL DEFAULT '' COMMENT '口令';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'keyword')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'name')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'model')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `model` varchar(255) NOT NULL DEFAULT '' COMMENT '模块';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `ticket` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'url')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `url` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'expire')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `expire` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'type')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `type` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `consumetime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'code_status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `code_status` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'make')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `make` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_qrcode',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_voice_qrcode',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_qrcode',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_qrcode')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('haoman_voice_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_voice_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'rules')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `rules` text NOT NULL COMMENT '参与活动规则';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'start_picurl')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `start_picurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'start_theme')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `start_theme` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'start_title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `start_title` varchar(30) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'backpicurl')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `backpicurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'backcolor')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `backcolor` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `isshow` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'ziliao')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `ziliao` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'start_hour')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `start_hour` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'end_hour')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `end_hour` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'all_money')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `all_money` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'turntable')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `turntable` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'is_error')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `is_error` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'adpic')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `adpic` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'noip_url')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `noip_url` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'adpicurl')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `adpicurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'total_num')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `total_num` int(11) DEFAULT '0' COMMENT '总获奖人数(自动加)';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'probability')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `probability` double DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'award_times')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `award_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'password')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `password` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'number_times')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `number_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'count_time')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `count_time` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'hb_today_times')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `hb_today_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'open_p')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `open_p` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'most_num_times')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `most_num_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'is_show_prize')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `is_show_prize` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'is_show_prize_num')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `is_show_prize_num` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'today_most_times')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `today_most_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'key_money1')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `key_money1` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'key_money2')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `key_money2` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'match')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `match` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'ptype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `ptype` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `follow_url` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'up_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `up_qrcode` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'credit1')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `credit1` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'rec_show')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `rec_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'rec_show2')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `rec_show2` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `copyright` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'tiemadpic')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `tiemadpic` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'timead')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `timead` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'timenum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `timenum` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'time_style')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `time_style` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_gift')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_gift` text NOT NULL COMMENT '赠送礼物';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'timeadurl')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `timeadurl` varchar(255) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `viewnum` int(11) DEFAULT '0' COMMENT '浏览次数';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `fansnum` int(11) DEFAULT '0' COMMENT '参与人数';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_acid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_acid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'ticketinfo')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'qjbpic')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `qjbpic` varchar(225) NOT NULL COMMENT '活动页面顶部背景';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'qjbimg')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `qjbimg` varchar(225) NOT NULL COMMENT '指针图';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'gl_openid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `gl_openid` varchar(300) NOT NULL COMMENT '管理员OPENID';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'rankname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `rankname` varchar(300) NOT NULL COMMENT '排行榜名称';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_gift_img')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_gift_img` varchar(225) NOT NULL COMMENT '分享奖品图';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_imgurl')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_imgurl` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_title` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_desc` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_type')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_type` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_gift_num')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_gift_num` int(11) DEFAULT '0' COMMENT '分享赠送抽奖基数';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_gift_num2')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_gift_num2` int(11) DEFAULT '0' COMMENT '分享赠送抽奖基数';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `sharenum` int(11) DEFAULT '0' COMMENT '分享赠送抽奖基数';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'sharenumtop')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `sharenumtop` int(11) DEFAULT '0' COMMENT '分享赠送抽奖基数';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `share_url` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'getip')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `getip` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'getip_addr')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `getip_addr` text NOT NULL COMMENT '限制地区ip';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'key')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `key` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists('haoman_voice_reply',  'allowip')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `allowip` text NOT NULL;");
}
if(!pdo_fieldexists('haoman_voice_reply',  'isallowip')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `isallowip` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'isappkey')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `isappkey` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'bd_key')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `bd_key` varchar(50) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_voice_reply',  'address_sf')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `address_sf` text NOT NULL;");
}
if(!pdo_fieldexists('haoman_voice_reply',  'address_sq')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `address_sq` text NOT NULL;");
}
if(!pdo_fieldexists('haoman_voice_reply',  'address_qx')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD `address_qx` text NOT NULL;");
}
if(!pdo_indexexists('haoman_voice_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_voice_reply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_voice_reply')." ADD KEY `indx_uniacid` (`uniacid`);");
}

?>