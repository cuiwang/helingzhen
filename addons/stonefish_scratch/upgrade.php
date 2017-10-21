<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `awardingtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '单独兑奖1统一兑奖2',
  `beihuo` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启备货1开启0关闭',
  `beihuo_tips` varchar(20) DEFAULT '' COMMENT '备货提示词',
  `awardingpas` varchar(10) DEFAULT '' COMMENT '兑奖密码',
  `inventory` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖后库存1中奖减少2为兑奖后减少',
  `awardingstarttime` int(10) DEFAULT '0' COMMENT '兑奖开始时间',
  `awardingendtime` int(10) DEFAULT '0' COMMENT '兑奖结束时间',
  `awarding_tips` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `awardingaddress` varchar(50) DEFAULT '' COMMENT '兑奖地点',
  `awardingtel` varchar(50) DEFAULT '' COMMENT '兑奖电话',
  `baidumaplng` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `baidumaplat` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `before` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖资料活动前还是中奖后1前2为后',
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
  `tmplmsg_participate` int(11) DEFAULT '0' COMMENT '参与消息模板',
  `tmplmsg_winning` int(11) DEFAULT '0' COMMENT '中奖消息模板',
  `tmplmsg_exchange` int(11) DEFAULT '0' COMMENT '兑奖消息模板',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
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
  `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始数',
  `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换数',
  `sharepoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分享助力',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `createtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `lasttime` int(10) unsigned NOT NULL COMMENT '最后参与时间',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  `zhongjiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `todaynum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '今日参与次数',
  `totalnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总参与次数',
  `tosharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享使用次数',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获奖次数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_fansaward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID',
  `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码',
  `createtime` int(10) DEFAULT '0' COMMENT '领取时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '使用时间',
  `openstatus` tinyint(1) DEFAULT '0' COMMENT '是否拆开',
  `zhongjiangtime` int(10) DEFAULT '0' COMMENT '中奖时间',
  `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`prizeid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_fanstmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `tmplmsgid` int(11) DEFAULT '0' COMMENT '消息模板ID',
  `tmplmsg` text NOT NULL COMMENT '发送内容',
  `createtime` int(10) DEFAULT '0' COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`tmplmsgid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `prizetype` varchar(20) NOT NULL COMMENT '奖品类型真实虚拟积分等',
  `prizevalue` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `prizerating` varchar(50) NOT NULL COMMENT '奖品等级',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `prizepic` varchar(255) NOT NULL COMMENT '奖品图片',
  `prizetotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `prizeren` int(10) NOT NULL COMMENT '每人最多中奖',
  `prizeday` int(10) NOT NULL COMMENT '每天最多发奖',
  `probalilty` varchar(5) NOT NULL COMMENT '中奖概率%',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要帮助人数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_prizemika` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖品ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `mikacodesn` varchar(100) NOT NULL COMMENT '密卡字符串',
  `virtual_value` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `actionurl` varchar(200) NOT NULL COMMENT '激活地址',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `templateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动模板ID',
  `title` varchar(50) DEFAULT '' COMMENT '活动标题',
  `description` varchar(255) DEFAULT '' COMMENT '活动简介',
  `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片',
  `end_title` varchar(50) DEFAULT '' COMMENT '结束标题',
  `end_description` varchar(200) DEFAULT '' COMMENT '活动结束简介',
  `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '活动是否停止0为暂停1为活动中',
  `starttime` int(10) DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '结束时间',
  `music` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打开背景音乐',
  `musicurl` varchar(255) NOT NULL DEFAULT '' COMMENT '背景音乐地址',
  `mauto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '音乐是否自动播放',
  `mloop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否循环播放',
  `issubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与类型0为任意1为关注粉丝2为会员',
  `visubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型',
  `fansnum` int(10) DEFAULT '0' COMMENT '参与人数',
  `viewnum` int(10) DEFAULT '0' COMMENT '访问次数',
  `prize_num` int(10) DEFAULT '0' COMMENT '奖品总数',
  `award_num` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `award_num_tips` varchar(100) DEFAULT '' COMMENT '超过中奖数量提示',
  `number_times` int(11) DEFAULT '0' COMMENT '每人最多参与次数',
  `number_times_tips` varchar(100) DEFAULT '' COMMENT '超过总次数提示',
  `day_number_times` int(11) DEFAULT '0' COMMENT '每人每天最多参与次数',
  `day_number_times_tips` varchar(100) DEFAULT '' COMMENT '超过每天次数提示',
  `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数',
  `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数',
  `showprize` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品',
  `prizeinfo` text NOT NULL COMMENT '奖品详细介绍',
  `awardtext` varchar(1000) DEFAULT '' COMMENT '中奖提示文字',
  `notawardtext` varchar(1000) DEFAULT '' COMMENT '没有中奖提示文字',
  `notprizetext` varchar(1000) DEFAULT '' COMMENT '没有奖品提示文字',
  `tips` varchar(200) DEFAULT '' COMMENT '活动次数提示',
  `copyright` varchar(20) DEFAULT '' COMMENT '版权',
  `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1',
  `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称',
  `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小',
  `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力',
  `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数',
  `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数',
  `addp` tinyint(1) DEFAULT '100' COMMENT '好友助力机率%',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `adpic` varchar(255) DEFAULT '' COMMENT '活动页顶部广告图',
  `adpicurl` varchar(255) DEFAULT '' COMMENT '活动页顶部广告链接',
  `homepictime` tinyint(1) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepictype` tinyint(1) unsigned NOT NULL COMMENT '首页广告类型1为每次2为每天3为每周4为仅1次',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与次数选项0活动设置1商户赠送2为积分购买',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送/积分购买说明',
  `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型',
  `credit_value` int(11) DEFAULT '0' COMMENT '积分购买多少积分',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `acid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '子公众号ID',
  `help_url` varchar(255) DEFAULT '' COMMENT '帮助关注引导页',
  `share_url` varchar(255) DEFAULT '' COMMENT '参与关注引导页',
  `share_open_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启作用',
  `share_title` varchar(50) DEFAULT '' COMMENT '分享标题',
  `share_desc` varchar(100) DEFAULT '' COMMENT '分享简介',
  `share_txt` text NOT NULL COMMENT '参与活动规则',
  `share_img` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图',
  `share_anniu` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字',
  `share_firend` varchar(255) NOT NULL COMMENT '助力按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_confirmurl` varchar(255) DEFAULT '' COMMENT '分享成功跳转URL',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  `sharetimes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为每天次数2为总次数',
  `sharetype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分享赠送类型0分享立即赠送1分享成功赠送',
  `sharenumtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分享赠送机会类型0单独赠送机会1每人赠送机会2分享共计赠送',
  `sharenum` varchar(5) DEFAULT '0' COMMENT '分享赠送礼盒基数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_sharedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '访问人openid',
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
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(20) DEFAULT '' COMMENT '模板名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '模板缩略图',
  `fontsize` varchar(2) DEFAULT '12' COMMENT '文字大小',
  `bgimg` varchar(255) DEFAULT '' COMMENT '背景图',
  `bgcolor` varchar(7) DEFAULT '' COMMENT '背景色',
  `textcolor` varchar(7) DEFAULT '' COMMENT '文字色',
  `textcolorlink` varchar(7) DEFAULT '' COMMENT '链接文字色',
  `buttoncolor` varchar(7) DEFAULT '' COMMENT '按钮色',
  `buttontextcolor` varchar(7) DEFAULT '' COMMENT '按钮文字色',
  `rulecolor` varchar(7) DEFAULT '' COMMENT '规则框背景色',
  `ruletextcolor` varchar(7) DEFAULT '' COMMENT '规则框文字色',
  `navcolor` varchar(7) DEFAULT '' COMMENT '导航色',
  `navtextcolor` varchar(7) DEFAULT '' COMMENT '导航文字色',
  `navactioncolor` varchar(7) DEFAULT '' COMMENT '导航选中文字色',
  `watchcolor` varchar(7) DEFAULT '' COMMENT '弹出框背景色',
  `watchtextcolor` varchar(7) DEFAULT '' COMMENT '弹出框文字色',
  `awardcolor` varchar(7) DEFAULT '' COMMENT '兑奖框背景色',
  `awardtextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框文字色',
  `awardscolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功背景色',
  `awardstextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功文字色',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_scratch_tmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `template_id` varchar(50) DEFAULT '' COMMENT '模板ID',
  `template_name` varchar(20) DEFAULT '' COMMENT '模板名称',
  `topcolor` varchar(7) DEFAULT '' COMMENT '通知文字色',
  `first` varchar(100) DEFAULT '' COMMENT '标题',
  `firstcolor` varchar(7) DEFAULT '' COMMENT '标题文字色',
  `keyword1` varchar(100) DEFAULT '' COMMENT '参数1',
  `keyword1code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword1color` varchar(7) DEFAULT '' COMMENT '参数1文字色',
  `keyword2` varchar(100) DEFAULT '' COMMENT '参数2',
  `keyword2code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword2color` varchar(7) DEFAULT '' COMMENT '参数2文字色',
  `keyword3` varchar(100) DEFAULT '' COMMENT '参数3',
  `keyword3code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword3color` varchar(7) DEFAULT '' COMMENT '参数3文字色',
  `keyword4` varchar(100) DEFAULT '' COMMENT '参数4',
  `keyword4code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword4color` varchar(7) DEFAULT '' COMMENT '参数4文字色',
  `keyword5` varchar(100) DEFAULT '' COMMENT '参数5',
  `keyword5code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword5color` varchar(7) DEFAULT '' COMMENT '参数5文字色',
  `keyword6` varchar(100) DEFAULT '' COMMENT '参数6',
  `keyword6code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword6color` varchar(7) DEFAULT '' COMMENT '参数6文字色',
  `keyword7` varchar(100) DEFAULT '' COMMENT '参数7',
  `keyword7code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword7color` varchar(7) DEFAULT '' COMMENT '参数7文字色',
  `keyword8` varchar(100) DEFAULT '' COMMENT '参数8',
  `keyword8code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword8color` varchar(7) DEFAULT '' COMMENT '参数8文字色',
  `keyword9` varchar(100) DEFAULT '' COMMENT '参数9',
  `keyword9code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword9color` varchar(7) DEFAULT '' COMMENT '参数9文字色',
  `keyword10` varchar(100) DEFAULT '' COMMENT '参数10',
  `keyword10code` varchar(20) DEFAULT '' COMMENT '参数1字段',
  `keyword10color` varchar(7) DEFAULT '' COMMENT '参数10文字色',
  `remark` varchar(100) DEFAULT '' COMMENT '备注',
  `remarkcolor` varchar(7) DEFAULT '' COMMENT '备注文字色',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('stonefish_scratch_exchange',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'tickettype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'awardingtype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `awardingtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '单独兑奖1统一兑奖2';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'beihuo')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `beihuo` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启备货1开启0关闭';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'beihuo_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `beihuo_tips` varchar(20) DEFAULT '' COMMENT '备货提示词';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'awardingpas')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `awardingpas` varchar(10) DEFAULT '' COMMENT '兑奖密码';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'inventory')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `inventory` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖后库存1中奖减少2为兑奖后减少';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'awardingstarttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `awardingstarttime` int(10) DEFAULT '0' COMMENT '兑奖开始时间';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'awardingendtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `awardingendtime` int(10) DEFAULT '0' COMMENT '兑奖结束时间';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'awarding_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `awarding_tips` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'awardingaddress')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `awardingaddress` varchar(50) DEFAULT '' COMMENT '兑奖地点';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'awardingtel')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `awardingtel` varchar(50) DEFAULT '' COMMENT '兑奖电话';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'baidumaplng')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `baidumaplng` varchar(10) DEFAULT '' COMMENT '兑奖导航';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'baidumaplat')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `baidumaplat` varchar(10) DEFAULT '' COMMENT '兑奖导航';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'before')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `before` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖资料活动前还是中奖后1前2为后';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isrealname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'ismobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isqq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isemail')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isaddress')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isgender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'istelephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isidcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'iscompany')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isoccupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isposition')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isfans')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'isfansname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'tmplmsg_participate')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `tmplmsg_participate` int(11) DEFAULT '0' COMMENT '参与消息模板';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'tmplmsg_winning')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `tmplmsg_winning` int(11) DEFAULT '0' COMMENT '中奖消息模板';");
}
if(!pdo_fieldexists('stonefish_scratch_exchange',  'tmplmsg_exchange')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD `tmplmsg_exchange` int(11) DEFAULT '0' COMMENT '兑奖消息模板';");
}
if(!pdo_indexexists('stonefish_scratch_exchange',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_scratch_exchange',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_exchange')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'email')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'idcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'company')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'occupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'position')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'inpoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始数';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'outpoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换数';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'sharepoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `sharepoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分享助力';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'share_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'sharetime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '注册时间';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `lasttime` int(10) unsigned NOT NULL COMMENT '最后参与时间';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'tickettype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'ticketid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'ticketname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'zhongjiang')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `zhongjiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'xuni')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'todaynum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `todaynum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '今日参与次数';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'totalnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `totalnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总参与次数';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'tosharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `tosharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享使用次数';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'awardnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `awardnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获奖次数';");
}
if(!pdo_fieldexists('stonefish_scratch_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止';");
}
if(!pdo_indexexists('stonefish_scratch_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_scratch_fans',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fans')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'prizeid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'codesn')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `createtime` int(10) DEFAULT '0' COMMENT '领取时间';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `consumetime` int(10) DEFAULT '0' COMMENT '使用时间';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'openstatus')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `openstatus` tinyint(1) DEFAULT '0' COMMENT '是否拆开';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'zhongjiangtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `zhongjiangtime` int(10) DEFAULT '0' COMMENT '中奖时间';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'zhongjiang')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'xuni')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'tickettype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'ticketid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID';");
}
if(!pdo_fieldexists('stonefish_scratch_fansaward',  'ticketname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称';");
}
if(!pdo_indexexists('stonefish_scratch_fansaward',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_scratch_fansaward',  'indx_prizeid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD KEY `indx_prizeid` (`prizeid`);");
}
if(!pdo_indexexists('stonefish_scratch_fansaward',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fansaward')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_fanstmplmsg',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_fanstmplmsg',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_scratch_fanstmplmsg',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_fanstmplmsg',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_scratch_fanstmplmsg',  'tmplmsgid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD `tmplmsgid` int(11) DEFAULT '0' COMMENT '消息模板ID';");
}
if(!pdo_fieldexists('stonefish_scratch_fanstmplmsg',  'tmplmsg')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD `tmplmsg` text NOT NULL COMMENT '发送内容';");
}
if(!pdo_fieldexists('stonefish_scratch_fanstmplmsg',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD `createtime` int(10) DEFAULT '0' COMMENT '发送时间';");
}
if(!pdo_indexexists('stonefish_scratch_fanstmplmsg',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_scratch_fanstmplmsg',  'indx_prizeid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD KEY `indx_prizeid` (`tmplmsgid`);");
}
if(!pdo_indexexists('stonefish_scratch_fanstmplmsg',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_fanstmplmsg')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `prizetype` varchar(20) NOT NULL COMMENT '奖品类型真实虚拟积分等';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'prizevalue')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `prizevalue` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'prizerating')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `prizerating` varchar(50) NOT NULL COMMENT '奖品等级';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'prizename')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `prizename` varchar(50) NOT NULL COMMENT '奖品名称';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'prizepic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `prizepic` varchar(255) NOT NULL COMMENT '奖品图片';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'prizetotal')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `prizetotal` int(10) NOT NULL COMMENT '奖品数量';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'prizedraw')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `prizedraw` int(10) NOT NULL COMMENT '中奖数量';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'prizeren')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `prizeren` int(10) NOT NULL COMMENT '每人最多中奖';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'prizeday')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `prizeday` int(10) NOT NULL COMMENT '每天最多发奖';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'probalilty')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `probalilty` varchar(5) NOT NULL COMMENT '中奖概率%';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('stonefish_scratch_prize',  'break')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要帮助人数';");
}
if(!pdo_indexexists('stonefish_scratch_prize',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_scratch_prize',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prize')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'prizeid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖品ID';");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'mikacodesn')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `mikacodesn` varchar(100) NOT NULL COMMENT '密卡字符串';");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'virtual_value')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `virtual_value` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值';");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'actionurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `actionurl` varchar(200) NOT NULL COMMENT '激活地址';");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('stonefish_scratch_prizemika',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过';");
}
if(!pdo_indexexists('stonefish_scratch_prizemika',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_scratch_prizemika',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_prizemika')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'templateid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `templateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动模板ID';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `title` varchar(50) DEFAULT '' COMMENT '活动标题';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `description` varchar(255) DEFAULT '' COMMENT '活动简介';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'start_picurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'end_title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `end_title` varchar(50) DEFAULT '' COMMENT '结束标题';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'end_description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `end_description` varchar(200) DEFAULT '' COMMENT '活动结束简介';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'end_picurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `isshow` tinyint(1) DEFAULT '1' COMMENT '活动是否停止0为暂停1为活动中';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `starttime` int(10) DEFAULT '0' COMMENT '开始时间';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `endtime` int(10) DEFAULT '0' COMMENT '结束时间';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'music')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `music` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打开背景音乐';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'musicurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `musicurl` varchar(255) NOT NULL DEFAULT '' COMMENT '背景音乐地址';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'mauto')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `mauto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '音乐是否自动播放';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'mloop')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `mloop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否循环播放';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'issubscribe')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `issubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与类型0为任意1为关注粉丝2为会员';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'visubscribe')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `visubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `fansnum` int(10) DEFAULT '0' COMMENT '参与人数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `viewnum` int(10) DEFAULT '0' COMMENT '访问次数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'prize_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `prize_num` int(10) DEFAULT '0' COMMENT '奖品总数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'award_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `award_num` int(11) DEFAULT '0' COMMENT '每人最多获奖次数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'award_num_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `award_num_tips` varchar(100) DEFAULT '' COMMENT '超过中奖数量提示';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'number_times')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `number_times` int(11) DEFAULT '0' COMMENT '每人最多参与次数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'number_times_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `number_times_tips` varchar(100) DEFAULT '' COMMENT '超过总次数提示';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'day_number_times')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `day_number_times` int(11) DEFAULT '0' COMMENT '每人每天最多参与次数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'day_number_times_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `day_number_times_tips` varchar(100) DEFAULT '' COMMENT '超过每天次数提示';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'viewawardnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'viewranknum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'showprize')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `showprize` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'prizeinfo')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `prizeinfo` text NOT NULL COMMENT '奖品详细介绍';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'awardtext')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `awardtext` varchar(1000) DEFAULT '' COMMENT '中奖提示文字';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'notawardtext')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `notawardtext` varchar(1000) DEFAULT '' COMMENT '没有中奖提示文字';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'notprizetext')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `notprizetext` varchar(1000) DEFAULT '' COMMENT '没有奖品提示文字';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `tips` varchar(200) DEFAULT '' COMMENT '活动次数提示';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `copyright` varchar(20) DEFAULT '' COMMENT '版权';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'inpointstart')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'inpointend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'power')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'poweravatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'powertype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'randompointstart')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'randompointend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'addp')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `addp` tinyint(1) DEFAULT '100' COMMENT '好友助力机率%';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'limittype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'totallimit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'helptype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'xuninum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'xuninumtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'xuninuminitial')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'xuninumending')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'xuninum_time')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'adpic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `adpic` varchar(255) DEFAULT '' COMMENT '活动页顶部广告图';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'adpicurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `adpicurl` varchar(255) DEFAULT '' COMMENT '活动页顶部广告链接';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'homepictime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `homepictime` tinyint(1) unsigned NOT NULL COMMENT '首页秒显图片显示时间';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'homepictype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `homepictype` tinyint(1) unsigned NOT NULL COMMENT '首页广告类型1为每次2为每天3为每周4为仅1次';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'homepic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'opportunity')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与次数选项0活动设置1商户赠送2为积分购买';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'opportunity_txt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `opportunity_txt` text NOT NULL COMMENT '商户赠送/积分购买说明';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'credit_type')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'credit_value')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `credit_value` int(11) DEFAULT '0' COMMENT '积分购买多少积分';");
}
if(!pdo_fieldexists('stonefish_scratch_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD `createtime` int(10) DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_indexexists('stonefish_scratch_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_scratch_reply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_reply')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `acid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '子公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'help_url')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `help_url` varchar(255) DEFAULT '' COMMENT '帮助关注引导页';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_url` varchar(255) DEFAULT '' COMMENT '参与关注引导页';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_open_close')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_open_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启作用';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_title` varchar(50) DEFAULT '' COMMENT '分享标题';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_desc` varchar(100) DEFAULT '' COMMENT '分享简介';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_txt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_txt` text NOT NULL COMMENT '参与活动规则';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_img')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_img` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_anniu')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_anniu` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_firend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_firend` varchar(255) NOT NULL COMMENT '助力按钮';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_pic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_confirm')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_confirmurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_confirmurl` varchar(255) DEFAULT '' COMMENT '分享成功跳转URL';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_fail')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'share_cancel')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'sharetimes')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `sharetimes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为每天次数2为总次数';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'sharetype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `sharetype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分享赠送类型0分享立即赠送1分享成功赠送';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'sharenumtype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `sharenumtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分享赠送机会类型0单独赠送机会1每人赠送机会2分享共计赠送';");
}
if(!pdo_fieldexists('stonefish_scratch_share',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD `sharenum` varchar(5) DEFAULT '0' COMMENT '分享赠送礼盒基数';");
}
if(!pdo_indexexists('stonefish_scratch_share',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_scratch_share',  'indx_acid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD KEY `indx_acid` (`acid`);");
}
if(!pdo_indexexists('stonefish_scratch_share',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_share')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid';");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '访问人openid';");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP';");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'visitorstime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'point')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `point` decimal(10,2) DEFAULT '0.00' COMMENT '助力金额';");
}
if(!pdo_fieldexists('stonefish_scratch_sharedata',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数';");
}
if(!pdo_indexexists('stonefish_scratch_sharedata',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_scratch_sharedata',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_sharedata')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `title` varchar(20) DEFAULT '' COMMENT '模板名称';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `thumb` varchar(255) DEFAULT '' COMMENT '模板缩略图';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'fontsize')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `fontsize` varchar(2) DEFAULT '12' COMMENT '文字大小';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'bgimg')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `bgimg` varchar(255) DEFAULT '' COMMENT '背景图';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `bgcolor` varchar(7) DEFAULT '' COMMENT '背景色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'textcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `textcolor` varchar(7) DEFAULT '' COMMENT '文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'textcolorlink')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `textcolorlink` varchar(7) DEFAULT '' COMMENT '链接文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'buttoncolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `buttoncolor` varchar(7) DEFAULT '' COMMENT '按钮色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'buttontextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `buttontextcolor` varchar(7) DEFAULT '' COMMENT '按钮文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'rulecolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `rulecolor` varchar(7) DEFAULT '' COMMENT '规则框背景色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'ruletextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `ruletextcolor` varchar(7) DEFAULT '' COMMENT '规则框文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'navcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `navcolor` varchar(7) DEFAULT '' COMMENT '导航色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'navtextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `navtextcolor` varchar(7) DEFAULT '' COMMENT '导航文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'navactioncolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `navactioncolor` varchar(7) DEFAULT '' COMMENT '导航选中文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'watchcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `watchcolor` varchar(7) DEFAULT '' COMMENT '弹出框背景色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'watchtextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `watchtextcolor` varchar(7) DEFAULT '' COMMENT '弹出框文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'awardcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `awardcolor` varchar(7) DEFAULT '' COMMENT '兑奖框背景色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'awardtextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `awardtextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'awardscolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `awardscolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功背景色';");
}
if(!pdo_fieldexists('stonefish_scratch_template',  'awardstextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD `awardstextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功文字色';");
}
if(!pdo_indexexists('stonefish_scratch_template',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_template')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'template_id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `template_id` varchar(50) DEFAULT '' COMMENT '模板ID';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'template_name')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `template_name` varchar(20) DEFAULT '' COMMENT '模板名称';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'topcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `topcolor` varchar(7) DEFAULT '' COMMENT '通知文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'first')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `first` varchar(100) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'firstcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `firstcolor` varchar(7) DEFAULT '' COMMENT '标题文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword1')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword1` varchar(100) DEFAULT '' COMMENT '参数1';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword1code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword1code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword1color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword1color` varchar(7) DEFAULT '' COMMENT '参数1文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword2')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword2` varchar(100) DEFAULT '' COMMENT '参数2';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword2code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword2code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword2color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword2color` varchar(7) DEFAULT '' COMMENT '参数2文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword3')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword3` varchar(100) DEFAULT '' COMMENT '参数3';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword3code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword3code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword3color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword3color` varchar(7) DEFAULT '' COMMENT '参数3文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword4')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword4` varchar(100) DEFAULT '' COMMENT '参数4';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword4code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword4code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword4color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword4color` varchar(7) DEFAULT '' COMMENT '参数4文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword5')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword5` varchar(100) DEFAULT '' COMMENT '参数5';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword5code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword5code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword5color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword5color` varchar(7) DEFAULT '' COMMENT '参数5文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword6')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword6` varchar(100) DEFAULT '' COMMENT '参数6';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword6code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword6code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword6color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword6color` varchar(7) DEFAULT '' COMMENT '参数6文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword7')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword7` varchar(100) DEFAULT '' COMMENT '参数7';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword7code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword7code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword7color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword7color` varchar(7) DEFAULT '' COMMENT '参数7文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword8')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword8` varchar(100) DEFAULT '' COMMENT '参数8';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword8code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword8code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword8color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword8color` varchar(7) DEFAULT '' COMMENT '参数8文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword9')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword9` varchar(100) DEFAULT '' COMMENT '参数9';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword9code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword9code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword9color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword9color` varchar(7) DEFAULT '' COMMENT '参数9文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword10')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword10` varchar(100) DEFAULT '' COMMENT '参数10';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword10code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword10code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'keyword10color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `keyword10color` varchar(7) DEFAULT '' COMMENT '参数10文字色';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `remark` varchar(100) DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists('stonefish_scratch_tmplmsg',  'remarkcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD `remarkcolor` varchar(7) DEFAULT '' COMMENT '备注文字色';");
}
if(!pdo_indexexists('stonefish_scratch_tmplmsg',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_scratch_tmplmsg')." ADD KEY `indx_uniacid` (`uniacid`);");
}

?>