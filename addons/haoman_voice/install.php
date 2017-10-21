<?php
pdo_query("
DROP TABLE IF EXISTS `ims_haoman_voice_award`;
CREATE TABLE `ims_haoman_voice_award` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `ims_haoman_voice_cardticket`;
CREATE TABLE `ims_haoman_voice_cardticket` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `createtime` varchar(20) DEFAULT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `ims_haoman_voice_cash`;
CREATE TABLE `ims_haoman_voice_cash` (
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


DROP TABLE IF EXISTS `ims_haoman_voice_code`;
CREATE TABLE `ims_haoman_voice_code` (
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


DROP TABLE IF EXISTS `ims_haoman_voice_data`;
CREATE TABLE `ims_haoman_voice_data` (
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


DROP TABLE IF EXISTS `ims_haoman_voice_fans`;
CREATE TABLE `ims_haoman_voice_fans` (
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `ims_haoman_voice_hb`;
CREATE TABLE `ims_haoman_voice_hb` (
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


DROP TABLE IF EXISTS `ims_haoman_voice_jiequan`;
CREATE TABLE `ims_haoman_voice_jiequan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `ims_haoman_voice_password`;
CREATE TABLE `ims_haoman_voice_password` (
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


DROP TABLE IF EXISTS `ims_haoman_voice_pici`;
CREATE TABLE `ims_haoman_voice_pici` (
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


DROP TABLE IF EXISTS `ims_haoman_voice_prize`;
CREATE TABLE `ims_haoman_voice_prize` (
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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `ims_haoman_voice_pw`;
CREATE TABLE `ims_haoman_voice_pw` (
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


DROP TABLE IF EXISTS `ims_haoman_voice_qrcode`;
CREATE TABLE `ims_haoman_voice_qrcode` (
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


DROP TABLE IF EXISTS `ims_haoman_voice_reply`;
CREATE TABLE `ims_haoman_voice_reply` (
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
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


");