<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_stonefish_redenvelope_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `description` varchar(200) DEFAULT '' COMMENT '描述',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `prize` int(11) DEFAULT '0' COMMENT '奖品ID',
  `award_sn` varchar(50) DEFAULT '' COMMENT 'SN',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `xuni` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redenvelope_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `point` decimal(10,2) DEFAULT '0.00' COMMENT '助力金额',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redenvelope_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始金额',
  `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换金额',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间',
  `awardingid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖地址ID',
  `last_time` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `zhongjiang` tinyint(1) DEFAULT '0',
  `xuni` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redenvelope_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `point` decimal(10,2) DEFAULT '0.00' COMMENT '奖品需要金额',
  `prizetype` varchar(50) NOT NULL COMMENT '奖品类别',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `prizepro` double DEFAULT '0' COMMENT '奖品概率',
  `prizetotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `prizepic` varchar(255) NOT NULL COMMENT '奖品图片',
  `prizetxt` text NOT NULL COMMENT '奖品说明',
  `credit` int(10) NOT NULL COMMENT '积分',
  `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redenvelope_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '' COMMENT '活动名称',
  `description` varchar(255) DEFAULT '' COMMENT '活动简介',
  `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片',
  `isshow` tinyint(1) DEFAULT '0',
  `envelope` tinyint(1) DEFAULT '0' COMMENT '红包类型0为实物奖品1为现金',
  `award_times` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `ticket_information` varchar(200) DEFAULT '' COMMENT '兑奖信息',
  `starttime` int(10) DEFAULT '0' COMMENT '活动开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '活动结束时间',
  `end_theme` varchar(50) DEFAULT '' COMMENT '结束标题',
  `end_instruction` varchar(200) DEFAULT '' COMMENT '活动结束简介',
  `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片',
  `adpic` varchar(200) DEFAULT '' COMMENT '活动页顶部广告图',
  `adpicurl` varchar(200) DEFAULT '' COMMENT '活动页顶部广告链接',
  `total_num` int(11) DEFAULT '0' COMMENT '奖品数量(自动加)',
  `sn_rename` varchar(20) DEFAULT '',
  `copyright` varchar(20) DEFAULT '' COMMENT '自定义版权',
  `show_num` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品数量',
  `viewnum` int(11) DEFAULT '0' COMMENT '浏览次数',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页滚动中奖人数显示',
  `fansnum` int(11) DEFAULT '0' COMMENT '参与人数',
  `cardbg` varchar(255) NOT NULL COMMENT '抽奖卡片背景图片',
  `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1',
  `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2',
  `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数',
  `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数',
  `addp` tinyint(1) DEFAULT '100' COMMENT '好友帮助攒钱机率%',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `incomelimit` float(10,2) unsigned NOT NULL DEFAULT '10000.00' COMMENT '最高金额限制',
  `tixianlimit` float(10,2) unsigned NOT NULL DEFAULT '100.00' COMMENT '提现金额限制',
  `countlimit` int(5) NOT NULL COMMENT '活动总人数限制',
  `createtime` int(10) DEFAULT '0' COMMENT '活动创建时间',
  `share_acid` int(10) DEFAULT '0' COMMENT '默认分享公众号ID',
  `sharetip` varchar(100) NOT NULL COMMENT '分享提示内容',
  `fanpaitip` varchar(100) NOT NULL COMMENT '好友翻牌小提示',
  `awardtip` varchar(200) NOT NULL COMMENT '中奖小提示说明',
  `sharebtn` varchar(10) NOT NULL COMMENT '邀请好友攒钱文字',
  `fsharebtn` varchar(10) NOT NULL COMMENT '好友帮助邀请攒钱文字',
  `bgcolor` varchar(10) DEFAULT '' COMMENT '背景颜色',
  `fontcolor` varchar(10) DEFAULT '' COMMENT '文字颜色',
  `btncolor` varchar(10) DEFAULT '' COMMENT '按钮颜色',
  `btnfontcolor` varchar(10) DEFAULT '' COMMENT '按钮文字颜色',
  `txcolor` varchar(10) DEFAULT '' COMMENT '提现按钮颜色',
  `txfontcolor` varchar(10) DEFAULT '' COMMENT '提现按钮文字颜色',
  `rulebgcolor` varchar(10) DEFAULT '' COMMENT '规则框背景颜色',
  `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `homepictime` int(3) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与选项 0任何人1关注粉丝2为商户赠送',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送参数说明',
  `award_info` text NOT NULL COMMENT '奖品详细介绍',
  `credit_times` tinyint(1) DEFAULT '0',
  `credit_type` varchar(20) DEFAULT '',
  `showparameters` varchar(1000) NOT NULL COMMENT '显示界面参数：背景色、背景图以及文字色等',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redenvelope_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `acid` int(11) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(255) DEFAULT '',
  `share_txt` text NOT NULL COMMENT '参与活动规则',
  `share_imgurl` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_picurl` varchar(255) NOT NULL COMMENT '分享图片按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redenvelope_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `access_token` varchar(1000) NOT NULL,
  `expires_in` int(11) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('stonefish_redenvelope_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'name')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `name` varchar(50) DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `description` varchar(200) DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `prizetype` varchar(10) DEFAULT '' COMMENT '类型';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'prize')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `prize` int(11) DEFAULT '0' COMMENT '奖品ID';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'award_sn')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `award_sn` varchar(50) DEFAULT '' COMMENT 'SN';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `consumetime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_award',  'xuni')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD `xuni` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('stonefish_redenvelope_award',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redenvelope_award',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_award')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid';");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP';");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'visitorstime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'point')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `point` decimal(10,2) DEFAULT '0.00' COMMENT '助力金额';");
}
if(!pdo_fieldexists('stonefish_redenvelope_data',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数';");
}
if(!pdo_indexexists('stonefish_redenvelope_data',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redenvelope_data',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_data')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `from_user` varchar(50) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'email')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'idcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'company')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'occupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'position')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'inpoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始金额';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'outpoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换金额';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'sharetime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'awardingid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `awardingid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖地址ID';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'last_time')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `last_time` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'zhongjiang')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `zhongjiang` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'xuni')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `xuni` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_indexexists('stonefish_redenvelope_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'point')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `point` decimal(10,2) DEFAULT '0.00' COMMENT '奖品需要金额';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `prizetype` varchar(50) NOT NULL COMMENT '奖品类别';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'prizename')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `prizename` varchar(50) NOT NULL COMMENT '奖品名称';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'prizepro')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `prizepro` double DEFAULT '0' COMMENT '奖品概率';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'prizetotal')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `prizetotal` int(10) NOT NULL COMMENT '奖品数量';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'prizedraw')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `prizedraw` int(10) NOT NULL COMMENT '中奖数量';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'prizepic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `prizepic` varchar(255) NOT NULL COMMENT '奖品图片';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'prizetxt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `prizetxt` text NOT NULL COMMENT '奖品说明';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `credit` int(10) NOT NULL COMMENT '积分';");
}
if(!pdo_fieldexists('stonefish_redenvelope_prize',  'credit_type')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型';");
}
if(!pdo_indexexists('stonefish_redenvelope_prize',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redenvelope_prize',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_prize')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `title` varchar(50) DEFAULT '' COMMENT '活动名称';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `description` varchar(255) DEFAULT '' COMMENT '活动简介';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'start_picurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isshow` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'envelope')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `envelope` tinyint(1) DEFAULT '0' COMMENT '红包类型0为实物奖品1为现金';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'award_times')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `award_times` int(11) DEFAULT '0' COMMENT '每人最多获奖次数';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'ticket_information')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `ticket_information` varchar(200) DEFAULT '' COMMENT '兑奖信息';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `starttime` int(10) DEFAULT '0' COMMENT '活动开始时间';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `endtime` int(10) DEFAULT '0' COMMENT '活动结束时间';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'end_theme')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `end_theme` varchar(50) DEFAULT '' COMMENT '结束标题';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'end_instruction')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `end_instruction` varchar(200) DEFAULT '' COMMENT '活动结束简介';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'end_picurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'adpic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `adpic` varchar(200) DEFAULT '' COMMENT '活动页顶部广告图';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'adpicurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `adpicurl` varchar(200) DEFAULT '' COMMENT '活动页顶部广告链接';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'total_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `total_num` int(11) DEFAULT '0' COMMENT '奖品数量(自动加)';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'sn_rename')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `sn_rename` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `copyright` varchar(20) DEFAULT '' COMMENT '自定义版权';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'show_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `show_num` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品数量';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `viewnum` int(11) DEFAULT '0' COMMENT '浏览次数';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'awardnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `awardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页滚动中奖人数显示';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `fansnum` int(11) DEFAULT '0' COMMENT '参与人数';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'cardbg')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `cardbg` varchar(255) NOT NULL COMMENT '抽奖卡片背景图片';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'inpointstart')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'inpointend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'randompointstart')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'randompointend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'addp')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `addp` tinyint(1) DEFAULT '100' COMMENT '好友帮助攒钱机率%';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'limittype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'totallimit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'incomelimit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `incomelimit` float(10,2) unsigned NOT NULL DEFAULT '10000.00' COMMENT '最高金额限制';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'tixianlimit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `tixianlimit` float(10,2) unsigned NOT NULL DEFAULT '100.00' COMMENT '提现金额限制';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'countlimit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `countlimit` int(5) NOT NULL COMMENT '活动总人数限制';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `createtime` int(10) DEFAULT '0' COMMENT '活动创建时间';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'share_acid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `share_acid` int(10) DEFAULT '0' COMMENT '默认分享公众号ID';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'sharetip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `sharetip` varchar(100) NOT NULL COMMENT '分享提示内容';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'fanpaitip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `fanpaitip` varchar(100) NOT NULL COMMENT '好友翻牌小提示';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'awardtip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `awardtip` varchar(200) NOT NULL COMMENT '中奖小提示说明';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'sharebtn')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `sharebtn` varchar(10) NOT NULL COMMENT '邀请好友攒钱文字';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'fsharebtn')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `fsharebtn` varchar(10) NOT NULL COMMENT '好友帮助邀请攒钱文字';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `bgcolor` varchar(10) DEFAULT '' COMMENT '背景颜色';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'fontcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `fontcolor` varchar(10) DEFAULT '' COMMENT '文字颜色';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'btncolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `btncolor` varchar(10) DEFAULT '' COMMENT '按钮颜色';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'btnfontcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `btnfontcolor` varchar(10) DEFAULT '' COMMENT '按钮文字颜色';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'txcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `txcolor` varchar(10) DEFAULT '' COMMENT '提现按钮颜色';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'txfontcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `txfontcolor` varchar(10) DEFAULT '' COMMENT '提现按钮文字颜色';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'rulebgcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `rulebgcolor` varchar(10) DEFAULT '' COMMENT '规则框背景颜色';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'ticketinfo')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isrealname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'ismobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isqq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isemail')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isaddress')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isgender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'istelephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isidcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'iscompany')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isoccupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isposition')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isfans')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'isfansname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'xuninum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'xuninumtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'xuninuminitial')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'xuninumending')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'xuninum_time')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'homepictime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `homepictime` int(3) unsigned NOT NULL COMMENT '首页秒显图片显示时间';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'homepic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'opportunity')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与选项 0任何人1关注粉丝2为商户赠送';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'opportunity_txt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `opportunity_txt` text NOT NULL COMMENT '商户赠送参数说明';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'award_info')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `award_info` text NOT NULL COMMENT '奖品详细介绍';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'credit_times')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `credit_times` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'credit_type')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `credit_type` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_redenvelope_reply',  'showparameters')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD `showparameters` varchar(1000) NOT NULL COMMENT '显示界面参数：背景色、背景图以及文字色等';");
}
if(!pdo_indexexists('stonefish_redenvelope_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redenvelope_reply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_reply')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `acid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_title` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_desc` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_url` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_txt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_txt` text NOT NULL COMMENT '参与活动规则';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_imgurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_imgurl` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_picurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_picurl` varchar(255) NOT NULL COMMENT '分享图片按钮';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_pic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_confirm')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_fail')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语';");
}
if(!pdo_fieldexists('stonefish_redenvelope_share',  'share_cancel')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语';");
}
if(!pdo_indexexists('stonefish_redenvelope_share',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redenvelope_share',  'indx_acid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD KEY `indx_acid` (`acid`);");
}
if(!pdo_indexexists('stonefish_redenvelope_share',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_share')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redenvelope_token',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_token')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redenvelope_token',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_token')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_redenvelope_token',  'access_token')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_token')." ADD `access_token` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_redenvelope_token',  'expires_in')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_token')." ADD `expires_in` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_redenvelope_token',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_token')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '日期';");
}
if(!pdo_indexexists('stonefish_redenvelope_token',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redenvelope_token')." ADD KEY `indx_uniacid` (`uniacid`);");
}

?>