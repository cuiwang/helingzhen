<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_apiconfig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `apitype` varchar(50) DEFAULT '' COMMENT '接口类型',
  `key` varchar(50) DEFAULT '' COMMENT '验证KEY',
  `tpl_id` varchar(50) DEFAULT '' COMMENT '验证模板',
  `sign` varchar(50) DEFAULT '' COMMENT '验证签名',
  `aging` int(10) DEFAULT '180' COMMENT '验证时效',
  `agingrepeat` tinyint(1) NOT NULL DEFAULT '1' COMMENT '验证时效1只能使用一次0为时效内有限',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `awardingstarttime` int(10) DEFAULT '0' COMMENT '兑奖开始时间',
  `awardingendtime` int(10) DEFAULT '0' COMMENT '兑奖结束时间',
  `awarding_tips` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `warning_tips` varchar(200) DEFAULT '' COMMENT '安全警告提示词',
  `yidong_tips` varchar(200) DEFAULT '' COMMENT '移动提示词',
  `liantong_tips` varchar(200) DEFAULT '' COMMENT '联通提示词',
  `dianxin_tips` varchar(200) DEFAULT '' COMMENT '电信提示词',
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
  `tmplmsg_help` int(11) DEFAULT '0' COMMENT '助力消息模板',
  `tmplmsg_exchange` int(11) DEFAULT '0' COMMENT '兑奖消息模板',
  `limittype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '限制参与类型',
  `limitgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '限制参与性别',
  `limitcity` varchar(1000) DEFAULT '' COMMENT '限制参与地区',
  `limitwelfare` int(10) DEFAULT '100' COMMENT '无效用户助力倍数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_fans` (
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
  `mobile_province` varchar(20) NOT NULL DEFAULT '' COMMENT '手机省份',
  `mobile_city` varchar(20) NOT NULL DEFAULT '' COMMENT '手机城市',
  `mobile_company` varchar(20) NOT NULL DEFAULT '' COMMENT '手机运营商',
  `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始数',
  `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换数',
  `sharepoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分享助力',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `createtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `zhongjiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止',
  `limit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否限制',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_fansaward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码',
  `createtime` int(10) DEFAULT '0' COMMENT '领取时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '使用时间',
  `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换数',
  `log` varchar(500) NOT NULL,
  `completed` int(11) NOT NULL DEFAULT '0',
  `ticketname` varchar(30) NOT NULL,
  `error_num` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '出错次数',
  `error_seed` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '出错消息',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_fanstmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `tmplmsgid` int(11) DEFAULT '0' COMMENT '消息模板ID',
  `tmplmsg` text NOT NULL COMMENT '发送内容',
  `seednum` int(10) DEFAULT '1' COMMENT '发送次数',
  `createtime` int(10) DEFAULT '0' COMMENT '发送时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`tmplmsgid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_mobileverify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL COMMENT '时间',
  `verifytime` int(10) unsigned NOT NULL COMMENT '验证时间',
  `welfare` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '中奖倍数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_redpack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `appid` varchar(100) DEFAULT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `mchid` varchar(20) DEFAULT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `signkey` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_reply` (
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
  `sys_users` varchar(500) NOT NULL DEFAULT '' COMMENT '系统会员组ID',
  `sys_users_tips` varchar(300) DEFAULT '' COMMENT '会员组提示',
  `fansnum` int(10) DEFAULT '0' COMMENT '参与人数',
  `viewnum` int(10) DEFAULT '0' COMMENT '访问次数',
  `prize_num` int(10) DEFAULT '0' COMMENT '奖品总数',
  `award_num` int(10) DEFAULT '1' COMMENT '每人最多获奖次数',
  `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数',
  `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数',
  `msgadpic` varchar(1000) DEFAULT '' COMMENT '消息提示广告图',
  `msgadpictime` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '消息提示时效',
  `intips` varchar(200) DEFAULT '' COMMENT '活动提示',
  `copyright` varchar(20) DEFAULT '' COMMENT '版权',
  `helptel` varchar(20) DEFAULT '' COMMENT '咨询电话',
  `homeanniu` varchar(50) DEFAULT '' COMMENT '抢红包等按钮',
  `lingquanniu` varchar(50) DEFAULT '' COMMENT '领取按钮',
  `lingquanniutips` varchar(50) DEFAULT '' COMMENT '领取提示词',
  `helptips` varchar(50) DEFAULT '' COMMENT '帮助提示词',
  `helpanniu` varchar(50) DEFAULT '' COMMENT '帮助按钮',
  `seedredpack` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '发奖类型0为人工1为自动',
  `redpacktype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '红包类型',
  `redpackv` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否小数',
  `danwei` varchar(10) DEFAULT '' COMMENT '单位如元',
  `redpack` varchar(10) DEFAULT '' COMMENT '红包重命名',
  `acthelp` varchar(10) DEFAULT '' COMMENT '动作重命名',
  `redpack_meun` varchar(300) DEFAULT '' COMMENT '兑换菜单',
  `sharepoint` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '提现基本值',
  `maxsharepoint` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '提现最大值',
  `redpack_tips` varchar(100) DEFAULT '' COMMENT '提现提示词',
  `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1',
  `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2',
  `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数',
  `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数',
  `addp` tinyint(1) DEFAULT '100' COMMENT '好友助力机率%',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称',
  `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小',
  `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止',
  `helpfans` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0全部用户1只能助力1人',
  `helplihe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0所有分享1单独分享',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `adpic` varchar(255) DEFAULT '' COMMENT '未参与图',
  `toppic` varchar(255) DEFAULT '' COMMENT '已参与图',
  `helppic` varchar(255) DEFAULT '' COMMENT '帮助广告图',
  `duihuanpic` varchar(255) DEFAULT '' COMMENT '兑换广告图',
  `myfanspic` varchar(255) DEFAULT '' COMMENT '我的背景图',
  `homepictime` tinyint(1) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepictype` tinyint(1) unsigned NOT NULL COMMENT '首页广告类型1为每次2为每天3为每周4为仅1次',
  `homepic` varchar(1000) NOT NULL COMMENT '首页秒显图片',
  `mobileverify` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否验证手机号',
  `smsverify` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启短信验证',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_share` (
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
  `share_anniufirend` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字',
  `share_firend` varchar(255) NOT NULL COMMENT '助力按钮',
  `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片',
  `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语',
  `share_confirmurl` varchar(255) DEFAULT '' COMMENT '分享成功跳转URL',
  `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语',
  `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_sharedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `share_type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0为分享1为访问',
  `virtual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人',
  `sharepoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力值',
  `welfare` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '中奖倍数',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '访问人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_template` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_redfunshare_tmplmsg` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('stonefish_redfunshare_apiconfig',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_apiconfig')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_apiconfig',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_apiconfig')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_redfunshare_apiconfig',  'apitype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_apiconfig')." ADD `apitype` varchar(50) DEFAULT '' COMMENT '接口类型';");
}
if(!pdo_fieldexists('stonefish_redfunshare_apiconfig',  'key')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_apiconfig')." ADD `key` varchar(50) DEFAULT '' COMMENT '验证KEY';");
}
if(!pdo_fieldexists('stonefish_redfunshare_apiconfig',  'tpl_id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_apiconfig')." ADD `tpl_id` varchar(50) DEFAULT '' COMMENT '验证模板';");
}
if(!pdo_fieldexists('stonefish_redfunshare_apiconfig',  'sign')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_apiconfig')." ADD `sign` varchar(50) DEFAULT '' COMMENT '验证签名';");
}
if(!pdo_fieldexists('stonefish_redfunshare_apiconfig',  'aging')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_apiconfig')." ADD `aging` int(10) DEFAULT '180' COMMENT '验证时效';");
}
if(!pdo_fieldexists('stonefish_redfunshare_apiconfig',  'agingrepeat')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_apiconfig')." ADD `agingrepeat` tinyint(1) NOT NULL DEFAULT '1' COMMENT '验证时效1只能使用一次0为时效内有限';");
}
if(!pdo_indexexists('stonefish_redfunshare_apiconfig',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_apiconfig')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'awardingstarttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `awardingstarttime` int(10) DEFAULT '0' COMMENT '兑奖开始时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'awardingendtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `awardingendtime` int(10) DEFAULT '0' COMMENT '兑奖结束时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'awarding_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `awarding_tips` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'warning_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `warning_tips` varchar(200) DEFAULT '' COMMENT '安全警告提示词';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'yidong_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `yidong_tips` varchar(200) DEFAULT '' COMMENT '移动提示词';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'liantong_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `liantong_tips` varchar(200) DEFAULT '' COMMENT '联通提示词';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'dianxin_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `dianxin_tips` varchar(200) DEFAULT '' COMMENT '电信提示词';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isrealname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'ismobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isqq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isemail')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isaddress')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isgender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'istelephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isidcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'iscompany')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isoccupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isposition')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isfans')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'isfansname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'tmplmsg_participate')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `tmplmsg_participate` int(11) DEFAULT '0' COMMENT '参与消息模板';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'tmplmsg_help')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `tmplmsg_help` int(11) DEFAULT '0' COMMENT '助力消息模板';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'tmplmsg_exchange')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `tmplmsg_exchange` int(11) DEFAULT '0' COMMENT '兑奖消息模板';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'limittype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `limittype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '限制参与类型';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'limitgender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `limitgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '限制参与性别';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'limitcity')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `limitcity` varchar(1000) DEFAULT '' COMMENT '限制参与地区';");
}
if(!pdo_fieldexists('stonefish_redfunshare_exchange',  'limitwelfare')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD `limitwelfare` int(10) DEFAULT '100' COMMENT '无效用户助力倍数';");
}
if(!pdo_indexexists('stonefish_redfunshare_exchange',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_exchange',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_exchange')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'email')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'idcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'company')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'occupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'position')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'mobile_province')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `mobile_province` varchar(20) NOT NULL DEFAULT '' COMMENT '手机省份';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'mobile_city')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `mobile_city` varchar(20) NOT NULL DEFAULT '' COMMENT '手机城市';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'mobile_company')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `mobile_company` varchar(20) NOT NULL DEFAULT '' COMMENT '手机运营商';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'inpoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'outpoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'sharepoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `sharepoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分享助力';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'share_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'sharetime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '注册时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'zhongjiang')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `zhongjiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'xuni')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fans',  'limit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD `limit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否限制';");
}
if(!pdo_indexexists('stonefish_redfunshare_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_fans',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fans')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'codesn')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `createtime` int(10) DEFAULT '0' COMMENT '领取时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `consumetime` int(10) DEFAULT '0' COMMENT '使用时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'zhongjiang')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'xuni')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'outpoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'log')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `log` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'completed')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `completed` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'ticketname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `ticketname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'error_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `error_num` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '出错次数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fansaward',  'error_seed')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD `error_seed` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '出错消息';");
}
if(!pdo_indexexists('stonefish_redfunshare_fansaward',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_fansaward',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fansaward')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_fanstmplmsg',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_fanstmplmsg',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fanstmplmsg',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fanstmplmsg',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fanstmplmsg',  'tmplmsgid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD `tmplmsgid` int(11) DEFAULT '0' COMMENT '消息模板ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fanstmplmsg',  'tmplmsg')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD `tmplmsg` text NOT NULL COMMENT '发送内容';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fanstmplmsg',  'seednum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD `seednum` int(10) DEFAULT '1' COMMENT '发送次数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_fanstmplmsg',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD `createtime` int(10) DEFAULT '0' COMMENT '发送时间';");
}
if(!pdo_indexexists('stonefish_redfunshare_fanstmplmsg',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_fanstmplmsg',  'indx_prizeid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD KEY `indx_prizeid` (`tmplmsgid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_fanstmplmsg',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_fanstmplmsg')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_mobileverify',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_mobileverify',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_redfunshare_mobileverify',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_mobileverify',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名';");
}
if(!pdo_fieldexists('stonefish_redfunshare_mobileverify',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '电话';");
}
if(!pdo_fieldexists('stonefish_redfunshare_mobileverify',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('stonefish_redfunshare_mobileverify',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_mobileverify',  'verifytime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD `verifytime` int(10) unsigned NOT NULL COMMENT '验证时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_mobileverify',  'welfare')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD `welfare` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '中奖倍数';");
}
if(!pdo_indexexists('stonefish_redfunshare_mobileverify',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_mobileverify',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_mobileverify')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_redpack',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_redpack')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_redpack',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_redpack')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_redpack',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_redpack')." ADD `appid` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_redfunshare_redpack',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_redpack')." ADD `secret` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_redfunshare_redpack',  'mchid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_redpack')." ADD `mchid` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_redfunshare_redpack',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_redpack')." ADD `ip` varchar(25) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_redfunshare_redpack',  'signkey')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_redpack')." ADD `signkey` varchar(32) DEFAULT NULL;");
}
if(!pdo_indexexists('stonefish_redfunshare_redpack',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_redpack')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'templateid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `templateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动模板ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `title` varchar(50) DEFAULT '' COMMENT '活动标题';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `description` varchar(255) DEFAULT '' COMMENT '活动简介';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'start_picurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'end_title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `end_title` varchar(50) DEFAULT '' COMMENT '结束标题';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'end_description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `end_description` varchar(200) DEFAULT '' COMMENT '活动结束简介';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'end_picurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `isshow` tinyint(1) DEFAULT '1' COMMENT '活动是否停止0为暂停1为活动中';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `starttime` int(10) DEFAULT '0' COMMENT '开始时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `endtime` int(10) DEFAULT '0' COMMENT '结束时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'music')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `music` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打开背景音乐';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'musicurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `musicurl` varchar(255) NOT NULL DEFAULT '' COMMENT '背景音乐地址';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'mauto')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `mauto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '音乐是否自动播放';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'mloop')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `mloop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否循环播放';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'issubscribe')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `issubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与类型0为任意1为关注粉丝2为会员';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'visubscribe')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `visubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'sys_users')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `sys_users` varchar(500) NOT NULL DEFAULT '' COMMENT '系统会员组ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'sys_users_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `sys_users_tips` varchar(300) DEFAULT '' COMMENT '会员组提示';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `fansnum` int(10) DEFAULT '0' COMMENT '参与人数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `viewnum` int(10) DEFAULT '0' COMMENT '访问次数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'prize_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `prize_num` int(10) DEFAULT '0' COMMENT '奖品总数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'award_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `award_num` int(10) DEFAULT '1' COMMENT '每人最多获奖次数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'viewawardnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'viewranknum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'msgadpic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `msgadpic` varchar(1000) DEFAULT '' COMMENT '消息提示广告图';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'msgadpictime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `msgadpictime` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '消息提示时效';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'intips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `intips` varchar(200) DEFAULT '' COMMENT '活动提示';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `copyright` varchar(20) DEFAULT '' COMMENT '版权';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'helptel')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `helptel` varchar(20) DEFAULT '' COMMENT '咨询电话';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'homeanniu')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `homeanniu` varchar(50) DEFAULT '' COMMENT '抢红包等按钮';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'lingquanniu')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `lingquanniu` varchar(50) DEFAULT '' COMMENT '领取按钮';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'lingquanniutips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `lingquanniutips` varchar(50) DEFAULT '' COMMENT '领取提示词';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'helptips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `helptips` varchar(50) DEFAULT '' COMMENT '帮助提示词';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'helpanniu')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `helpanniu` varchar(50) DEFAULT '' COMMENT '帮助按钮';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'seedredpack')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `seedredpack` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '发奖类型0为人工1为自动';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'redpacktype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `redpacktype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '红包类型';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'redpackv')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `redpackv` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否小数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'danwei')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `danwei` varchar(10) DEFAULT '' COMMENT '单位如元';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'redpack')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `redpack` varchar(10) DEFAULT '' COMMENT '红包重命名';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'acthelp')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `acthelp` varchar(10) DEFAULT '' COMMENT '动作重命名';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'redpack_meun')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `redpack_meun` varchar(300) DEFAULT '' COMMENT '兑换菜单';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'sharepoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `sharepoint` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '提现基本值';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'maxsharepoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `maxsharepoint` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '提现最大值';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'redpack_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `redpack_tips` varchar(100) DEFAULT '' COMMENT '提现提示词';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'inpointstart')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'inpointend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'randompointstart')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'randompointend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'addp')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `addp` tinyint(1) DEFAULT '100' COMMENT '好友助力机率%';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'power')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'poweravatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'powertype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'limittype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'totallimit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'helptype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'helpfans')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `helpfans` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0全部用户1只能助力1人';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'helplihe')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `helplihe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0所有分享1单独分享';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'xuninum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'xuninumtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'xuninuminitial')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'xuninumending')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'xuninum_time')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'adpic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `adpic` varchar(255) DEFAULT '' COMMENT '未参与图';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'toppic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `toppic` varchar(255) DEFAULT '' COMMENT '已参与图';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'helppic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `helppic` varchar(255) DEFAULT '' COMMENT '帮助广告图';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'duihuanpic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `duihuanpic` varchar(255) DEFAULT '' COMMENT '兑换广告图';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'myfanspic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `myfanspic` varchar(255) DEFAULT '' COMMENT '我的背景图';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'homepictime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `homepictime` tinyint(1) unsigned NOT NULL COMMENT '首页秒显图片显示时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'homepictype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `homepictype` tinyint(1) unsigned NOT NULL COMMENT '首页广告类型1为每次2为每天3为每周4为仅1次';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'homepic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `homepic` varchar(1000) NOT NULL COMMENT '首页秒显图片';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'mobileverify')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `mobileverify` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否验证手机号';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'smsverify')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `smsverify` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启短信验证';");
}
if(!pdo_fieldexists('stonefish_redfunshare_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD `createtime` int(10) DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_indexexists('stonefish_redfunshare_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_reply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_reply')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `acid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '子公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'help_url')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `help_url` varchar(255) DEFAULT '' COMMENT '帮助关注引导页';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_url` varchar(255) DEFAULT '' COMMENT '参与关注引导页';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_open_close')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_open_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启作用';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_title` varchar(50) DEFAULT '' COMMENT '分享标题';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_desc` varchar(100) DEFAULT '' COMMENT '分享简介';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_txt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_txt` text NOT NULL COMMENT '参与活动规则';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_img')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_img` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_anniu')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_anniu` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_anniufirend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_anniufirend` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_firend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_firend` varchar(255) NOT NULL COMMENT '助力按钮';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_pic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_confirm')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_confirmurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_confirmurl` varchar(255) DEFAULT '' COMMENT '分享成功跳转URL';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_fail')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语';");
}
if(!pdo_fieldexists('stonefish_redfunshare_share',  'share_cancel')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语';");
}
if(!pdo_indexexists('stonefish_redfunshare_share',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_share',  'indx_acid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD KEY `indx_acid` (`acid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_share',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_share')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'share_type')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `share_type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0为分享1为访问';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'virtual')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `virtual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'sharepoint')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `sharepoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力值';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'welfare')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `welfare` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '中奖倍数';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '访问人openid';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'visitorstime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('stonefish_redfunshare_sharedata',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数';");
}
if(!pdo_indexexists('stonefish_redfunshare_sharedata',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_redfunshare_sharedata',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_sharedata')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `title` varchar(20) DEFAULT '' COMMENT '模板名称';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `thumb` varchar(255) DEFAULT '' COMMENT '模板缩略图';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'fontsize')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `fontsize` varchar(2) DEFAULT '12' COMMENT '文字大小';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'bgimg')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `bgimg` varchar(255) DEFAULT '' COMMENT '背景图';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `bgcolor` varchar(7) DEFAULT '' COMMENT '背景色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'textcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `textcolor` varchar(7) DEFAULT '' COMMENT '文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'textcolorlink')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `textcolorlink` varchar(7) DEFAULT '' COMMENT '链接文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'buttoncolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `buttoncolor` varchar(7) DEFAULT '' COMMENT '按钮色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'buttontextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `buttontextcolor` varchar(7) DEFAULT '' COMMENT '按钮文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'rulecolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `rulecolor` varchar(7) DEFAULT '' COMMENT '规则框背景色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'ruletextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `ruletextcolor` varchar(7) DEFAULT '' COMMENT '规则框文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'navcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `navcolor` varchar(7) DEFAULT '' COMMENT '导航色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'navtextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `navtextcolor` varchar(7) DEFAULT '' COMMENT '导航文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'navactioncolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `navactioncolor` varchar(7) DEFAULT '' COMMENT '导航选中文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'watchcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `watchcolor` varchar(7) DEFAULT '' COMMENT '弹出框背景色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'watchtextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `watchtextcolor` varchar(7) DEFAULT '' COMMENT '弹出框文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'awardcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `awardcolor` varchar(7) DEFAULT '' COMMENT '兑奖框背景色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'awardtextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `awardtextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'awardscolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `awardscolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功背景色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_template',  'awardstextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD `awardstextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功文字色';");
}
if(!pdo_indexexists('stonefish_redfunshare_template',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_template')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'template_id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `template_id` varchar(50) DEFAULT '' COMMENT '模板ID';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'template_name')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `template_name` varchar(20) DEFAULT '' COMMENT '模板名称';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'topcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `topcolor` varchar(7) DEFAULT '' COMMENT '通知文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'first')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `first` varchar(100) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'firstcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `firstcolor` varchar(7) DEFAULT '' COMMENT '标题文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword1')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword1` varchar(100) DEFAULT '' COMMENT '参数1';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword1code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword1code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword1color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword1color` varchar(7) DEFAULT '' COMMENT '参数1文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword2')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword2` varchar(100) DEFAULT '' COMMENT '参数2';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword2code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword2code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword2color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword2color` varchar(7) DEFAULT '' COMMENT '参数2文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword3')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword3` varchar(100) DEFAULT '' COMMENT '参数3';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword3code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword3code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword3color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword3color` varchar(7) DEFAULT '' COMMENT '参数3文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword4')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword4` varchar(100) DEFAULT '' COMMENT '参数4';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword4code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword4code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword4color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword4color` varchar(7) DEFAULT '' COMMENT '参数4文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword5')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword5` varchar(100) DEFAULT '' COMMENT '参数5';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword5code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword5code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword5color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword5color` varchar(7) DEFAULT '' COMMENT '参数5文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword6')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword6` varchar(100) DEFAULT '' COMMENT '参数6';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword6code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword6code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword6color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword6color` varchar(7) DEFAULT '' COMMENT '参数6文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword7')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword7` varchar(100) DEFAULT '' COMMENT '参数7';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword7code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword7code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword7color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword7color` varchar(7) DEFAULT '' COMMENT '参数7文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword8')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword8` varchar(100) DEFAULT '' COMMENT '参数8';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword8code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword8code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword8color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword8color` varchar(7) DEFAULT '' COMMENT '参数8文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword9')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword9` varchar(100) DEFAULT '' COMMENT '参数9';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword9code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword9code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword9color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword9color` varchar(7) DEFAULT '' COMMENT '参数9文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword10')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword10` varchar(100) DEFAULT '' COMMENT '参数10';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword10code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword10code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'keyword10color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `keyword10color` varchar(7) DEFAULT '' COMMENT '参数10文字色';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `remark` varchar(100) DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists('stonefish_redfunshare_tmplmsg',  'remarkcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD `remarkcolor` varchar(7) DEFAULT '' COMMENT '备注文字色';");
}
if(!pdo_indexexists('stonefish_redfunshare_tmplmsg',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_redfunshare_tmplmsg')." ADD KEY `indx_uniacid` (`uniacid`);");
}

?>