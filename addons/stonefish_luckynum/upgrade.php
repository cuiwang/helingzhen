<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_stonefish_luckynum` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `isshow` tinyint(1) DEFAULT '0',
  `title` varchar(100) DEFAULT '',
  `shareimg` varchar(255) DEFAULT '',
  `sharetitle` varchar(100) DEFAULT '',
  `sharedesc` varchar(300) DEFAULT '',
  `show_instruction` varchar(100) DEFAULT '',
  `time_instruction` varchar(100) DEFAULT '',
  `limit_instruction` varchar(100) DEFAULT '',
  `end_instruction` varchar(100) DEFAULT '',
  `awardnum_instruction` varchar(100) DEFAULT '',
  `award_instruction` varchar(100) DEFAULT '',
  `luckynumstart` int(10) unsigned NOT NULL DEFAULT '0',
  `luckynumfilter` varchar(100) NOT NULL DEFAULT '',
  `awardprompt` varchar(200) NOT NULL DEFAULT '',
  `currentprompt` varchar(200) NOT NULL DEFAULT '',
  `limittype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '参与限制0为无限制1为只能一次',
  `awardnum` tinyint(1) NOT NULL DEFAULT '0' COMMENT '中奖限制次数',
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
  `sponsors1` varchar(50) DEFAULT '',
  `sponsors1link` varchar(255) DEFAULT '',
  `sponsors2` varchar(50) DEFAULT '',
  `sponsors2link` varchar(255) DEFAULT '',
  `sponsors3` varchar(50) DEFAULT '',
  `sponsors3link` varchar(255) DEFAULT '',
  `sponsors4` varchar(50) DEFAULT '',
  `sponsors4link` varchar(255) DEFAULT '',
  `sponsors5` varchar(50) DEFAULT '',
  `sponsors5link` varchar(255) DEFAULT '',
  `ruletext` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_luckynum_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `numbers` text NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_luckynum_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '0',
  `award_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
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
  `zhongjiang` tinyint(1) NOT NULL DEFAULT '0',
  `xuni` tinyint(1) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `awardingid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖地址ID',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid_number_UNIQUE` (`rid`,`number`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('stonefish_luckynum',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_luckynum',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isshow` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `title` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'shareimg')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `shareimg` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sharetitle` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sharedesc` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'show_instruction')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `show_instruction` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'time_instruction')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `time_instruction` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'limit_instruction')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `limit_instruction` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'end_instruction')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `end_instruction` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'awardnum_instruction')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `awardnum_instruction` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'award_instruction')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `award_instruction` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'luckynumstart')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `luckynumstart` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'luckynumfilter')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `luckynumfilter` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'awardprompt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `awardprompt` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'currentprompt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `currentprompt` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'limittype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `limittype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '参与限制0为无限制1为只能一次';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'awardnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `awardnum` tinyint(1) NOT NULL DEFAULT '0' COMMENT '中奖限制次数';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'ticketinfo')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isrealname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'ismobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isqq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isemail')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isaddress')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isgender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'istelephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isidcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'iscompany')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isoccupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isposition')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isfans')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'isfansname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors1')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors1` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors1link')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors1link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors2')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors2` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors2link')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors2link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors3')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors3` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors3link')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors3link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors4')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors4` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors4link')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors4link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors5')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors5` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'sponsors5link')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `sponsors5link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum',  'ruletext')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD `ruletext` text NOT NULL;");
}
if(!pdo_indexexists('stonefish_luckynum',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_luckynum',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_luckynum_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_award')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_luckynum_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_award')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_award',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_award')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_award',  'numbers')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_award')." ADD `numbers` text NOT NULL;");
}
if(!pdo_fieldexists('stonefish_luckynum_award',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_award')." ADD `title` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum_award',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_award')." ADD `description` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum_award',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_award')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('stonefish_luckynum_award',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_award')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_luckynum_award',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_award')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'number')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `number` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `from_user` varchar(50) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'award_id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `award_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `ip` char(15) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'email')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'idcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'company')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'occupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'position')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'zhongjiang')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `zhongjiang` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'xuni')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `xuni` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `consumetime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_luckynum_fans',  'awardingid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD `awardingid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖地址ID';");
}
if(!pdo_indexexists('stonefish_luckynum_fans',  'rid_number_UNIQUE')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD UNIQUE KEY `rid_number_UNIQUE` (`rid`,`number`);");
}
if(!pdo_indexexists('stonefish_luckynum_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_luckynum_fans',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_luckynum_fans')." ADD KEY `indx_uniacid` (`uniacid`);");
}

?>