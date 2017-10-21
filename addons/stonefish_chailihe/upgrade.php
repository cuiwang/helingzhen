<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_apiconfig` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `apitype` varchar(50) DEFAULT NULL,
  `key` varchar(50) DEFAULT NULL,
  `tpl_id` varchar(50) DEFAULT NULL,
  `sign` varchar(50) DEFAULT NULL,
  `aging` int(10) DEFAULT NULL,
  `agingrepeat` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_banner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `bannerpic` varchar(255) NOT NULL COMMENT '幻灯图片',
  `bannerurl` varchar(255) NOT NULL COMMENT '幻灯链接',
  `bannertitle` varchar(50) NOT NULL,
  `displayorder` int(10) DEFAULT NULL,
  `isshow` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_exchange` (
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
  `isexchange` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0领取礼盒时输入1中奖后输入',
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
  `limittype` tinyint(1) unsigned NOT NULL,
  `limitgender` tinyint(1) unsigned NOT NULL,
  `limitcity` varchar(1000) DEFAULT NULL,
  `tmplmsg_help` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_fans` (
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
  `mobile_province` varchar(20) NOT NULL,
  `mobile_city` varchar(20) NOT NULL,
  `limit` tinyint(1) unsigned NOT NULL,
  `welfare` tinyint(1) unsigned NOT NULL,
  `mobile_company` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_fansaward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID',
  `liheid` int(11) DEFAULT '0' COMMENT '礼盒样式ID',
  `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码',
  `createtime` int(10) DEFAULT '0' COMMENT '领取时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '使用时间',
  `sharenum` int(10) DEFAULT '0' COMMENT '拆开人数',
  `openstatus` tinyint(1) DEFAULT '0' COMMENT '是否拆开',
  `zhongjiangtime` int(10) DEFAULT '0' COMMENT '中奖时间',
  `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  `log` varchar(100) DEFAULT NULL,
  `errno` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`prizeid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_fanstmplmsg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `tmplmsgid` int(11) DEFAULT '0' COMMENT '消息模板ID',
  `tmplmsg` text NOT NULL COMMENT '发送内容',
  `createtime` int(10) DEFAULT '0' COMMENT '发送时间',
  `seednum` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`tmplmsgid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_lihestyle` (
  `liheid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(20) DEFAULT '' COMMENT '样式名称',
  `thumb1` varchar(255) DEFAULT '' COMMENT '礼盒展示图',
  `thumb2` varchar(255) DEFAULT '' COMMENT '礼盒拆开图',
  `thumb3` varchar(255) DEFAULT '' COMMENT '礼盒显示图',
  `shangjialogo` varchar(255) DEFAULT '' COMMENT '商家LOGO',
  `music` varchar(2) DEFAULT '' COMMENT '礼盒声音',
  PRIMARY KEY (`liheid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_mobileverify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `realname` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `verifytime` int(10) unsigned NOT NULL,
  `welfare` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `liheid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼盒样式ID',
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
  `password` varchar(10) DEFAULT NULL,
  `awardingaddress` varchar(50) DEFAULT NULL,
  `awardingtel` varchar(50) DEFAULT NULL,
  `baidumaplng` varchar(10) DEFAULT NULL,
  `baidumaplat` varchar(10) DEFAULT NULL,
  `share_title` varchar(50) DEFAULT NULL,
  `share_desc` varchar(100) DEFAULT NULL,
  `share_img` varchar(255) NOT NULL,
  `xuninum` int(10) unsigned NOT NULL,
  `xuninumtime` int(10) unsigned NOT NULL,
  `xuninuminitial` int(10) unsigned NOT NULL,
  `xuninumending` int(10) unsigned NOT NULL,
  `xuninum_time` int(10) unsigned NOT NULL,
  `viewnum` int(10) unsigned NOT NULL,
  `share_num` int(10) unsigned NOT NULL,
  `sharenum` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_prizemika` (
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
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_redpack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `appid` varchar(100) DEFAULT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `mchid` varchar(20) DEFAULT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `signkey` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `templateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动模板ID',
  `slidevertical` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '多个礼盒切换效果2左右1上下',
  `tips` varchar(300) DEFAULT '' COMMENT '活动提示',
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
  `number_times` int(11) DEFAULT '0' COMMENT '每人最多参与次数',
  `day_number_times` int(11) DEFAULT '0' COMMENT '每人每天最多参与次数',
  `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数',
  `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数',
  `showprize` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品',
  `prizeinfo` text NOT NULL COMMENT '奖品详细介绍',
  `awardtext` varchar(1000) DEFAULT '' COMMENT '中奖提示文字',
  `notawardtext` varchar(1000) DEFAULT '' COMMENT '没有中奖提示文字',
  `noprizepic` varchar(1000) DEFAULT '' COMMENT '没有中奖提示图',
  `notprizetext` varchar(1000) DEFAULT '' COMMENT '没有奖品提示文字',
  `copyright` varchar(20) DEFAULT '' COMMENT '版权',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称',
  `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小',
  `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止',
  `helpfans` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0全部用户1只能助力1人',
  `helplihe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0所有礼盒1单独礼盒',
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
  `sys_users` varchar(500) NOT NULL,
  `sys_users_tips` varchar(300) DEFAULT NULL,
  `redpack_tips` varchar(100) DEFAULT NULL,
  `msgadpic` varchar(1000) DEFAULT NULL,
  `msgadpictime` tinyint(1) unsigned NOT NULL,
  `mobileverify` tinyint(1) unsigned NOT NULL,
  `surprises` tinyint(1) unsigned NOT NULL,
  `smsverify` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_share` (
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
  `sharenum` int(11) DEFAULT '0' COMMENT '分享赠送礼盒基数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_acid` (`acid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_sharedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `fid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼盒记录ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '访问人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  `share_type` int(10) unsigned NOT NULL,
  `virtual` tinyint(1) unsigned NOT NULL,
  `who` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(20) DEFAULT '' COMMENT '模板名称',
  `thumb` varchar(255) DEFAULT '' COMMENT '模板缩略图',
  `fontsize` varchar(2) DEFAULT '12' COMMENT '文字大小',
  `bgimg` varchar(255) DEFAULT '' COMMENT '背景图',
  `bgimglihe` varchar(255) DEFAULT '' COMMENT '领取礼盒背景图',
  `bgimgprize` varchar(255) DEFAULT '' COMMENT '中奖背景图',
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
CREATE TABLE IF NOT EXISTS `ims_stonefish_chailihe_tmplmsg` (
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
if(!pdo_fieldexists('stonefish_chailihe_apiconfig',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_apiconfig')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_apiconfig',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_apiconfig')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_apiconfig',  'apitype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_apiconfig')." ADD `apitype` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_apiconfig',  'key')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_apiconfig')." ADD `key` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_apiconfig',  'tpl_id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_apiconfig')." ADD `tpl_id` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_apiconfig',  'sign')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_apiconfig')." ADD `sign` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_apiconfig',  'aging')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_apiconfig')." ADD `aging` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_apiconfig',  'agingrepeat')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_apiconfig')." ADD `agingrepeat` tinyint(1) NOT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_apiconfig',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_apiconfig')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_banner',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_banner',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_banner',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_banner',  'bannerpic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD `bannerpic` varchar(255) NOT NULL COMMENT '幻灯图片';");
}
if(!pdo_fieldexists('stonefish_chailihe_banner',  'bannerurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD `bannerurl` varchar(255) NOT NULL COMMENT '幻灯链接';");
}
if(!pdo_fieldexists('stonefish_chailihe_banner',  'bannertitle')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD `bannertitle` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_banner',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD `displayorder` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_banner',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD `isshow` tinyint(1) DEFAULT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_banner',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_banner',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_banner')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'tickettype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'awardingtype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `awardingtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '单独兑奖1统一兑奖2';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'beihuo')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `beihuo` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启备货1开启0关闭';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'beihuo_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `beihuo_tips` varchar(20) DEFAULT '' COMMENT '备货提示词';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'awardingpas')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `awardingpas` varchar(10) DEFAULT '' COMMENT '兑奖密码';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'inventory')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `inventory` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖后库存1中奖减少2为兑奖后减少';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'awardingstarttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `awardingstarttime` int(10) DEFAULT '0' COMMENT '兑奖开始时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'awardingendtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `awardingendtime` int(10) DEFAULT '0' COMMENT '兑奖结束时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'awarding_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `awarding_tips` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'awardingaddress')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `awardingaddress` varchar(50) DEFAULT '' COMMENT '兑奖地点';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'awardingtel')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `awardingtel` varchar(50) DEFAULT '' COMMENT '兑奖电话';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'baidumaplng')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `baidumaplng` varchar(10) DEFAULT '' COMMENT '兑奖导航';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'baidumaplat')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `baidumaplat` varchar(10) DEFAULT '' COMMENT '兑奖导航';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isexchange')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isexchange` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0领取礼盒时输入1中奖后输入';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isrealname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'ismobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isqq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isemail')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isaddress')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isgender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'istelephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isidcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'iscompany')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isoccupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isposition')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isfans')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'isfansname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'tmplmsg_participate')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `tmplmsg_participate` int(11) DEFAULT '0' COMMENT '参与消息模板';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'tmplmsg_winning')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `tmplmsg_winning` int(11) DEFAULT '0' COMMENT '中奖消息模板';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'tmplmsg_exchange')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `tmplmsg_exchange` int(11) DEFAULT '0' COMMENT '兑奖消息模板';");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'limittype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `limittype` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'limitgender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `limitgender` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'limitcity')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `limitcity` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_exchange',  'tmplmsg_help')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD `tmplmsg_help` int(11) DEFAULT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_exchange',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_exchange',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_exchange')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'email')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'idcard')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'company')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'occupation')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'position')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'share_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'sharetime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '注册时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `lasttime` int(10) unsigned NOT NULL COMMENT '最后参与时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'tickettype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'ticketid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'ticketname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'zhongjiang')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `zhongjiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'xuni')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'todaynum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `todaynum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '今日参与次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'totalnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `totalnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总参与次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'tosharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `tosharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享使用次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'awardnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `awardnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获奖次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止';");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'mobile_province')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `mobile_province` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'mobile_city')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `mobile_city` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'limit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `limit` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'welfare')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `welfare` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_fans',  'mobile_company')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD `mobile_company` varchar(20) NOT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_fans',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fans')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'prizeid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'liheid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `liheid` int(11) DEFAULT '0' COMMENT '礼盒样式ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'codesn')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `createtime` int(10) DEFAULT '0' COMMENT '领取时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `consumetime` int(10) DEFAULT '0' COMMENT '使用时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `sharenum` int(10) DEFAULT '0' COMMENT '拆开人数';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'openstatus')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `openstatus` tinyint(1) DEFAULT '0' COMMENT '是否拆开';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'zhongjiangtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `zhongjiangtime` int(10) DEFAULT '0' COMMENT '中奖时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'zhongjiang')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'xuni')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'tickettype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'ticketid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'ticketname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称';");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'log')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `log` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_fansaward',  'errno')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD `errno` varchar(100) DEFAULT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_fansaward',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_fansaward',  'indx_prizeid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD KEY `indx_prizeid` (`prizeid`);");
}
if(!pdo_indexexists('stonefish_chailihe_fansaward',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fansaward')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_fanstmplmsg',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_fanstmplmsg',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_fanstmplmsg',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_fanstmplmsg',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_chailihe_fanstmplmsg',  'tmplmsgid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD `tmplmsgid` int(11) DEFAULT '0' COMMENT '消息模板ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_fanstmplmsg',  'tmplmsg')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD `tmplmsg` text NOT NULL COMMENT '发送内容';");
}
if(!pdo_fieldexists('stonefish_chailihe_fanstmplmsg',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD `createtime` int(10) DEFAULT '0' COMMENT '发送时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_fanstmplmsg',  'seednum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD `seednum` int(10) DEFAULT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_fanstmplmsg',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_fanstmplmsg',  'indx_prizeid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD KEY `indx_prizeid` (`tmplmsgid`);");
}
if(!pdo_indexexists('stonefish_chailihe_fanstmplmsg',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_fanstmplmsg')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_lihestyle',  'liheid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_lihestyle')." ADD `liheid` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_lihestyle',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_lihestyle')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_lihestyle',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_lihestyle')." ADD `title` varchar(20) DEFAULT '' COMMENT '样式名称';");
}
if(!pdo_fieldexists('stonefish_chailihe_lihestyle',  'thumb1')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_lihestyle')." ADD `thumb1` varchar(255) DEFAULT '' COMMENT '礼盒展示图';");
}
if(!pdo_fieldexists('stonefish_chailihe_lihestyle',  'thumb2')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_lihestyle')." ADD `thumb2` varchar(255) DEFAULT '' COMMENT '礼盒拆开图';");
}
if(!pdo_fieldexists('stonefish_chailihe_lihestyle',  'thumb3')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_lihestyle')." ADD `thumb3` varchar(255) DEFAULT '' COMMENT '礼盒显示图';");
}
if(!pdo_fieldexists('stonefish_chailihe_lihestyle',  'shangjialogo')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_lihestyle')." ADD `shangjialogo` varchar(255) DEFAULT '' COMMENT '商家LOGO';");
}
if(!pdo_fieldexists('stonefish_chailihe_lihestyle',  'music')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_lihestyle')." ADD `music` varchar(2) DEFAULT '' COMMENT '礼盒声音';");
}
if(!pdo_indexexists('stonefish_chailihe_lihestyle',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_lihestyle')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_mobileverify',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_mobileverify',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_mobileverify',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_mobileverify',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD `realname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_mobileverify',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD `mobile` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_mobileverify',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_mobileverify',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_mobileverify',  'verifytime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD `verifytime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_mobileverify',  'welfare')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD `welfare` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_mobileverify',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_mobileverify',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_mobileverify')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'liheid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `liheid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼盒样式ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `prizetype` varchar(20) NOT NULL COMMENT '奖品类型真实虚拟积分等';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'prizevalue')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `prizevalue` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'prizerating')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `prizerating` varchar(50) NOT NULL COMMENT '奖品等级';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'prizename')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `prizename` varchar(50) NOT NULL COMMENT '奖品名称';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'prizepic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `prizepic` varchar(255) NOT NULL COMMENT '奖品图片';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'prizetotal')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `prizetotal` int(10) NOT NULL COMMENT '奖品数量';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'prizedraw')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `prizedraw` int(10) NOT NULL COMMENT '中奖数量';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'prizeren')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `prizeren` int(10) NOT NULL COMMENT '每人最多中奖';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'prizeday')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `prizeday` int(10) NOT NULL COMMENT '每天最多发奖';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'probalilty')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `probalilty` varchar(5) NOT NULL COMMENT '中奖概率%';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'break')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要帮助人数';");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'password')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `password` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'awardingaddress')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `awardingaddress` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'awardingtel')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `awardingtel` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'baidumaplng')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `baidumaplng` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'baidumaplat')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `baidumaplat` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `share_title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `share_desc` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'share_img')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `share_img` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'xuninum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `xuninum` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'xuninumtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `xuninumtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'xuninuminitial')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `xuninuminitial` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'xuninumending')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `xuninumending` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'xuninum_time')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `xuninum_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `viewnum` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'share_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `share_num` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_prize',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD `sharenum` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_prize',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_prize',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prize')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'prizeid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖品ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'mikacodesn')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `mikacodesn` varchar(100) NOT NULL COMMENT '密卡字符串';");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'virtual_value')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `virtual_value` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值';");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'actionurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `actionurl` varchar(200) NOT NULL COMMENT '激活地址';");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('stonefish_chailihe_prizemika',  'status')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过';");
}
if(!pdo_indexexists('stonefish_chailihe_prizemika',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_prizemika',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_prizemika')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_redpack',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_redpack')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_redpack',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_redpack')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_redpack',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_redpack')." ADD `appid` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_redpack',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_redpack')." ADD `secret` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_redpack',  'mchid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_redpack')." ADD `mchid` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_redpack',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_redpack')." ADD `ip` varchar(25) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_redpack',  'signkey')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_redpack')." ADD `signkey` varchar(32) DEFAULT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_redpack',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_redpack')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'templateid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `templateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动模板ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'slidevertical')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `slidevertical` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '多个礼盒切换效果2左右1上下';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `tips` varchar(300) DEFAULT '' COMMENT '活动提示';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `title` varchar(50) DEFAULT '' COMMENT '活动标题';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `description` varchar(255) DEFAULT '' COMMENT '活动简介';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'start_picurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'end_title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `end_title` varchar(50) DEFAULT '' COMMENT '结束标题';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'end_description')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `end_description` varchar(200) DEFAULT '' COMMENT '活动结束简介';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'end_picurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `isshow` tinyint(1) DEFAULT '1' COMMENT '活动是否停止0为暂停1为活动中';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `starttime` int(10) DEFAULT '0' COMMENT '开始时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `endtime` int(10) DEFAULT '0' COMMENT '结束时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'music')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `music` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打开背景音乐';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'musicurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `musicurl` varchar(255) NOT NULL DEFAULT '' COMMENT '背景音乐地址';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'mauto')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `mauto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '音乐是否自动播放';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'mloop')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `mloop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否循环播放';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'issubscribe')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `issubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与类型0为任意1为关注粉丝2为会员';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'visubscribe')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `visubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `fansnum` int(10) DEFAULT '0' COMMENT '参与人数';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `viewnum` int(10) DEFAULT '0' COMMENT '访问次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'prize_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `prize_num` int(10) DEFAULT '0' COMMENT '奖品总数';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'award_num')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `award_num` int(11) DEFAULT '0' COMMENT '每人最多获奖次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'number_times')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `number_times` int(11) DEFAULT '0' COMMENT '每人最多参与次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'day_number_times')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `day_number_times` int(11) DEFAULT '0' COMMENT '每人每天最多参与次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'viewawardnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'viewranknum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'showprize')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `showprize` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'prizeinfo')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `prizeinfo` text NOT NULL COMMENT '奖品详细介绍';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'awardtext')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `awardtext` varchar(1000) DEFAULT '' COMMENT '中奖提示文字';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'notawardtext')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `notawardtext` varchar(1000) DEFAULT '' COMMENT '没有中奖提示文字';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'noprizepic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `noprizepic` varchar(1000) DEFAULT '' COMMENT '没有中奖提示图';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'notprizetext')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `notprizetext` varchar(1000) DEFAULT '' COMMENT '没有奖品提示文字';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `copyright` varchar(20) DEFAULT '' COMMENT '版权';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'power')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'poweravatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'powertype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'limittype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'totallimit')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'helptype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'helpfans')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `helpfans` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0全部用户1只能助力1人';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'helplihe')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `helplihe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0所有礼盒1单独礼盒';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'xuninum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'xuninumtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'xuninuminitial')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'xuninumending')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'xuninum_time')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'adpic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `adpic` varchar(255) DEFAULT '' COMMENT '活动页顶部广告图';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'adpicurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `adpicurl` varchar(255) DEFAULT '' COMMENT '活动页顶部广告链接';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'homepictime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `homepictime` tinyint(1) unsigned NOT NULL COMMENT '首页秒显图片显示时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'homepictype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `homepictype` tinyint(1) unsigned NOT NULL COMMENT '首页广告类型1为每次2为每天3为每周4为仅1次';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'homepic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'opportunity')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与次数选项0活动设置1商户赠送2为积分购买';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'opportunity_txt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `opportunity_txt` text NOT NULL COMMENT '商户赠送/积分购买说明';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'credit_type')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'credit_value')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `credit_value` int(11) DEFAULT '0' COMMENT '积分购买多少积分';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `createtime` int(10) DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'sys_users')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `sys_users` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'sys_users_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `sys_users_tips` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'redpack_tips')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `redpack_tips` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'msgadpic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `msgadpic` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'msgadpictime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `msgadpictime` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'mobileverify')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `mobileverify` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'surprises')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `surprises` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_reply',  'smsverify')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD `smsverify` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_reply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_reply')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `acid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '子公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'help_url')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `help_url` varchar(255) DEFAULT '' COMMENT '帮助关注引导页';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_url` varchar(255) DEFAULT '' COMMENT '参与关注引导页';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_open_close')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_open_close` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启作用';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_title` varchar(50) DEFAULT '' COMMENT '分享标题';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_desc` varchar(100) DEFAULT '' COMMENT '分享简介';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_txt')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_txt` text NOT NULL COMMENT '参与活动规则';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_img')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_img` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈图';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_anniu')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_anniu` varchar(255) NOT NULL COMMENT '分享朋友或朋友圈按钮或文字';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_firend')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_firend` varchar(255) NOT NULL COMMENT '助力按钮';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_pic')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_pic` varchar(255) NOT NULL COMMENT '分享弹出图片';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_confirm')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_confirm` varchar(200) DEFAULT '' COMMENT '分享成功提示语';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_confirmurl')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_confirmurl` varchar(255) DEFAULT '' COMMENT '分享成功跳转URL';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_fail')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_fail` varchar(200) DEFAULT '' COMMENT '分享失败提示语';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'share_cancel')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `share_cancel` varchar(200) DEFAULT '' COMMENT '分享中途取消提示语';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'sharetimes')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `sharetimes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为每天次数2为总次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'sharetype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `sharetype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '分享赠送类型0分享立即赠送1分享成功赠送';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'sharenumtype')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `sharenumtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '分享赠送机会类型0单独赠送机会1每人赠送机会2分享共计赠送';");
}
if(!pdo_fieldexists('stonefish_chailihe_share',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD `sharenum` int(11) DEFAULT '0' COMMENT '分享赠送礼盒基数';");
}
if(!pdo_indexexists('stonefish_chailihe_share',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_share',  'indx_acid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD KEY `indx_acid` (`acid`);");
}
if(!pdo_indexexists('stonefish_chailihe_share',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_share')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `fid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼盒记录ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '访问人openid';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'visitorstime')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数';");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'share_type')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `share_type` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'virtual')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `virtual` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('stonefish_chailihe_sharedata',  'who')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD `who` varchar(10) NOT NULL;");
}
if(!pdo_indexexists('stonefish_chailihe_sharedata',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('stonefish_chailihe_sharedata',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_sharedata')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'title')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `title` varchar(20) DEFAULT '' COMMENT '模板名称';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `thumb` varchar(255) DEFAULT '' COMMENT '模板缩略图';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'fontsize')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `fontsize` varchar(2) DEFAULT '12' COMMENT '文字大小';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'bgimg')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `bgimg` varchar(255) DEFAULT '' COMMENT '背景图';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'bgimglihe')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `bgimglihe` varchar(255) DEFAULT '' COMMENT '领取礼盒背景图';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'bgimgprize')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `bgimgprize` varchar(255) DEFAULT '' COMMENT '中奖背景图';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `bgcolor` varchar(7) DEFAULT '' COMMENT '背景色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'textcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `textcolor` varchar(7) DEFAULT '' COMMENT '文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'textcolorlink')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `textcolorlink` varchar(7) DEFAULT '' COMMENT '链接文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'buttoncolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `buttoncolor` varchar(7) DEFAULT '' COMMENT '按钮色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'buttontextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `buttontextcolor` varchar(7) DEFAULT '' COMMENT '按钮文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'rulecolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `rulecolor` varchar(7) DEFAULT '' COMMENT '规则框背景色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'ruletextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `ruletextcolor` varchar(7) DEFAULT '' COMMENT '规则框文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'navcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `navcolor` varchar(7) DEFAULT '' COMMENT '导航色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'navtextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `navtextcolor` varchar(7) DEFAULT '' COMMENT '导航文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'navactioncolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `navactioncolor` varchar(7) DEFAULT '' COMMENT '导航选中文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'watchcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `watchcolor` varchar(7) DEFAULT '' COMMENT '弹出框背景色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'watchtextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `watchtextcolor` varchar(7) DEFAULT '' COMMENT '弹出框文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'awardcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `awardcolor` varchar(7) DEFAULT '' COMMENT '兑奖框背景色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'awardtextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `awardtextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'awardscolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `awardscolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功背景色';");
}
if(!pdo_fieldexists('stonefish_chailihe_template',  'awardstextcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD `awardstextcolor` varchar(7) DEFAULT '' COMMENT '兑奖框成功文字色';");
}
if(!pdo_indexexists('stonefish_chailihe_template',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_template')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'template_id')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `template_id` varchar(50) DEFAULT '' COMMENT '模板ID';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'template_name')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `template_name` varchar(20) DEFAULT '' COMMENT '模板名称';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'topcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `topcolor` varchar(7) DEFAULT '' COMMENT '通知文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'first')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `first` varchar(100) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'firstcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `firstcolor` varchar(7) DEFAULT '' COMMENT '标题文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword1')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword1` varchar(100) DEFAULT '' COMMENT '参数1';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword1code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword1code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword1color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword1color` varchar(7) DEFAULT '' COMMENT '参数1文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword2')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword2` varchar(100) DEFAULT '' COMMENT '参数2';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword2code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword2code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword2color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword2color` varchar(7) DEFAULT '' COMMENT '参数2文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword3')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword3` varchar(100) DEFAULT '' COMMENT '参数3';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword3code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword3code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword3color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword3color` varchar(7) DEFAULT '' COMMENT '参数3文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword4')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword4` varchar(100) DEFAULT '' COMMENT '参数4';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword4code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword4code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword4color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword4color` varchar(7) DEFAULT '' COMMENT '参数4文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword5')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword5` varchar(100) DEFAULT '' COMMENT '参数5';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword5code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword5code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword5color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword5color` varchar(7) DEFAULT '' COMMENT '参数5文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword6')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword6` varchar(100) DEFAULT '' COMMENT '参数6';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword6code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword6code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword6color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword6color` varchar(7) DEFAULT '' COMMENT '参数6文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword7')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword7` varchar(100) DEFAULT '' COMMENT '参数7';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword7code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword7code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword7color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword7color` varchar(7) DEFAULT '' COMMENT '参数7文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword8')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword8` varchar(100) DEFAULT '' COMMENT '参数8';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword8code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword8code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword8color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword8color` varchar(7) DEFAULT '' COMMENT '参数8文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword9')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword9` varchar(100) DEFAULT '' COMMENT '参数9';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword9code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword9code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword9color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword9color` varchar(7) DEFAULT '' COMMENT '参数9文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword10')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword10` varchar(100) DEFAULT '' COMMENT '参数10';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword10code')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword10code` varchar(20) DEFAULT '' COMMENT '参数1字段';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'keyword10color')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `keyword10color` varchar(7) DEFAULT '' COMMENT '参数10文字色';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `remark` varchar(100) DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists('stonefish_chailihe_tmplmsg',  'remarkcolor')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD `remarkcolor` varchar(7) DEFAULT '' COMMENT '备注文字色';");
}
if(!pdo_indexexists('stonefish_chailihe_tmplmsg',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('stonefish_chailihe_tmplmsg')." ADD KEY `indx_uniacid` (`uniacid`);");
}

?>