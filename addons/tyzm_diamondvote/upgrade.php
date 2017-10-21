<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_blacklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `type` varchar(1) DEFAULT '0',
  `value` varchar(50) DEFAULT NULL COMMENT '值',
  `content` varchar(50) DEFAULT NULL COMMENT '昵称，IP地区',
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `content` (`content`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `pv_total` int(1) NOT NULL,
  `share_total` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_domainlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0' COMMENT '1，主域名，0备选域名',
  `domain` varchar(50) DEFAULT NULL COMMENT '域名',
  `extensive` tinyint(1) DEFAULT '0' COMMENT '是否泛域名',
  `description` varchar(255) NOT NULL COMMENT '备注',
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `content` (`domain`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_fansdata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(50) NOT NULL COMMENT '用户openid',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `indx_openid` (`openid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_friendship` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(100) DEFAULT '',
  `eventrule` mediumtext COMMENT '活动规则',
  `config` mediumtext COMMENT '相关配置',
  `packata` mediumtext COMMENT '套餐配置',
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_gift` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `ptid` varchar(128) NOT NULL COMMENT '订单号',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `uniontid` varchar(30) NOT NULL COMMENT '商户单号',
  `paytype` varchar(10) NOT NULL COMMENT '支付方式',
  `oauth_openid` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户openid',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `user_ip` varchar(15) NOT NULL COMMENT '客户端ip',
  `gifttitle` varchar(8) DEFAULT '0' COMMENT '礼物名称',
  `giftcount` int(10) NOT NULL DEFAULT '1' COMMENT '礼物数量',
  `gifticon` varchar(255) NOT NULL COMMENT '礼物图标',
  `fee` decimal(10,2) NOT NULL COMMENT '支付金额',
  `giftvote` varchar(50) NOT NULL COMMENT '抵票数',
  `ispay` int(1) NOT NULL COMMENT '支付状态',
  `isdeal` tinyint(1) NOT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `indx_tid` (`tid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_ptid` (`ptid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_looklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `oauth_openid` varchar(50) NOT NULL,
  `user_ip` varchar(15) NOT NULL COMMENT '客户端ip',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_redpack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `unionid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户unionid',
  `openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户openid',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `user_ip` varchar(15) NOT NULL COMMENT '客户端ip',
  `mch_billno` varchar(28) DEFAULT '',
  `total_amount` int(10) DEFAULT '0',
  `total_num` int(3) NOT NULL,
  `client_ip` varchar(15) NOT NULL,
  `send_time` varchar(14) DEFAULT '0',
  `send_listid` varchar(32) DEFAULT '0',
  `return_data` text,
  `return_code` varchar(16) NOT NULL,
  `return_msg` varchar(256) NOT NULL,
  `result_code` varchar(16) NOT NULL,
  `err_code` varchar(32) NOT NULL,
  `err_code_des` varchar(128) NOT NULL,
  `rewards` varchar(20) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态-012wz-com',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `title` varchar(100) DEFAULT '',
  `thumb` varchar(255) DEFAULT '' COMMENT '封面',
  `description` varchar(255) DEFAULT '',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `apstarttime` int(10) DEFAULT '0' COMMENT '报名时间start',
  `apendtime` int(10) DEFAULT '0' COMMENT '报名时间end',
  `votestarttime` int(10) DEFAULT '0' COMMENT '投票时间start',
  `voteendtime` int(10) DEFAULT '0' COMMENT '投票时间end',
  `topimg` varchar(255) DEFAULT '' COMMENT '背景图片',
  `bgcolor` varchar(255) DEFAULT '' COMMENT '背景颜色',
  `style` varchar(255) DEFAULT '' COMMENT '风格',
  `infomsg` mediumtext COMMENT '活动介绍',
  `eventrule` mediumtext COMMENT '活动规则',
  `awardmsg` mediumtext COMMENT '奖品介绍',
  `prizemsg` mediumtext COMMENT '奖品简介',
  `endintro` mediumtext COMMENT '活动结束说明',
  `config` mediumtext COMMENT '相关配置',
  `addata` mediumtext COMMENT '广告配置',
  `usepwd` varchar(10) NOT NULL COMMENT '核销密码',
  `area` varchar(100) DEFAULT '0' COMMENT '地区限制',
  `followguide` varchar(255) DEFAULT '' COMMENT '未关注引导提示',
  `shareimg` varchar(255) DEFAULT '' COMMENT '分享图标',
  `sharetitle` varchar(100) DEFAULT '' COMMENT '分享标题',
  `sharedesc` varchar(300) DEFAULT '' COMMENT '分享简介',
  `status` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  `giftdata` mediumtext NOT NULL COMMENT '礼物配置数据',
  `bill_data` mediumtext NOT NULL COMMENT '海报数据',
  `applydata` mediumtext NOT NULL COMMENT '报名信息配置',
  `notice` varchar(100) DEFAULT '',
  `verifycode` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_viporder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `ptid` varchar(128) NOT NULL COMMENT '订单号',
  `uniacid` int(11) DEFAULT '0',
  `uniontid` varchar(30) NOT NULL COMMENT '商户单号',
  `paytype` varchar(10) NOT NULL COMMENT '支付方式',
  `oauth_openid` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `user_ip` varchar(15) NOT NULL COMMENT '客户端ip',
  `packname` varchar(8) DEFAULT '0' COMMENT '套餐类型',
  `packicon` varchar(255) NOT NULL COMMENT '套餐图标',
  `packtime` varchar(255) NOT NULL COMMENT '套餐有效期',
  `fee` decimal(10,2) NOT NULL COMMENT '支付金额',
  `packnum` varchar(50) NOT NULL COMMENT '套餐数量',
  `ispay` int(1) NOT NULL COMMENT '支付状态',
  `isdeal` tinyint(1) NOT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `indx_tid` (`tid`),
  KEY `indx_ptid` (`ptid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_votedata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `from_user` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户openid',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `user_ip` varchar(15) NOT NULL COMMENT '客户端ip',
  `votetype` tinyint(1) DEFAULT '0' COMMENT '投票类型，0投票，1钻石',
  `fee` decimal(10,2) NOT NULL COMMENT '支付金额',
  `paynum` int(10) NOT NULL,
  `votenum` varchar(50) NOT NULL,
  `ptid` varchar(50) NOT NULL COMMENT '订单号',
  `plid` varchar(50) NOT NULL COMMENT '支付id',
  `mch_billno` varchar(28) NOT NULL,
  `send_time` varchar(14) NOT NULL,
  `send_listid` varchar(32) NOT NULL,
  `return_data` mediumtext NOT NULL,
  `return_code` varchar(16) NOT NULL,
  `return_msg` varchar(256) NOT NULL,
  `result_code` varchar(16) NOT NULL,
  `err_code` varchar(32) NOT NULL,
  `err_code_des` varchar(128) NOT NULL COMMENT '真实用户openid',
  `ispay` int(1) NOT NULL COMMENT '支付状态',
  `isdeal` tinyint(1) NOT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  `oauth_openid` varchar(50) NOT NULL,
  `reward` tinyint(1) NOT NULL COMMENT '抽奖状态',
  PRIMARY KEY (`id`),
  KEY `indx_tid` (`tid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tyzm_diamondvote_voteuser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `noid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '0' COMMENT '真实用户openid',
  `openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户openid',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `user_ip` varchar(15) NOT NULL COMMENT '客户端ip',
  `sex` tinyint(1) DEFAULT '0' COMMENT '性别',
  `name` varchar(30) NOT NULL COMMENT '姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `introduction` varchar(255) DEFAULT NULL COMMENT '个人介绍',
  `img1` varchar(255) DEFAULT '' COMMENT '图1',
  `img2` varchar(255) DEFAULT '' COMMENT '图2',
  `img3` varchar(255) DEFAULT '' COMMENT '图3',
  `img4` varchar(255) DEFAULT '' COMMENT '图4',
  `img5` varchar(255) DEFAULT '' COMMENT '图5',
  `details` mediumtext COMMENT '投票详情',
  `formatdata` mediumtext COMMENT '上传图片mediaid',
  `votenum` int(255) DEFAULT '0' COMMENT '投票数量',
  `diamondnum` int(255) DEFAULT '0' COMMENT '钻石数量',
  `eggnum` int(255) DEFAULT '0' COMMENT '鸡蛋数量',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `locktime` int(10) DEFAULT '0' COMMENT '锁定时间',
  `attestation` tinyint(1) DEFAULT '0' COMMENT '认证状态',
  `atmsg` varchar(255) NOT NULL DEFAULT '' COMMENT '认证简介',
  `createtime` int(10) DEFAULT '0' COMMENT '时间',
  `oauth_openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '真实用户openid',
  `joindata` mediumtext NOT NULL COMMENT '报名信息',
  `giftcount` decimal(10,2) NOT NULL COMMENT '礼物数量',
  `vheat` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟热度',
  `lastvotetime` int(10) DEFAULT '0' COMMENT '最新投票时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('tyzm_diamondvote_blacklist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_blacklist')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_blacklist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_blacklist')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_blacklist',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_blacklist')." ADD `type` varchar(1) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_blacklist',  'value')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_blacklist')." ADD `value` varchar(50) DEFAULT NULL COMMENT '值';");
}
if(!pdo_fieldexists('tyzm_diamondvote_blacklist',  'content')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_blacklist')." ADD `content` varchar(50) DEFAULT NULL COMMENT '昵称，IP地区';");
}
if(!pdo_fieldexists('tyzm_diamondvote_blacklist',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_blacklist')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_blacklist',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_blacklist')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_indexexists('tyzm_diamondvote_blacklist',  'content')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_blacklist')." ADD UNIQUE KEY `content` (`content`);");
}
if(!pdo_indexexists('tyzm_diamondvote_blacklist',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_blacklist')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tyzm_diamondvote_count',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_count')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_count',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_count')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_count',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_count')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_count',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_count')." ADD `tid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_count',  'pv_total')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_count')." ADD `pv_total` int(1) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_count',  'share_total')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_count')." ADD `share_total` int(1) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_domainlist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_domainlist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_domainlist',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_domainlist',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD `type` tinyint(1) DEFAULT '0' COMMENT '1，主域名，0备选域名';");
}
if(!pdo_fieldexists('tyzm_diamondvote_domainlist',  'domain')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD `domain` varchar(50) DEFAULT NULL COMMENT '域名';");
}
if(!pdo_fieldexists('tyzm_diamondvote_domainlist',  'extensive')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD `extensive` tinyint(1) DEFAULT '0' COMMENT '是否泛域名';");
}
if(!pdo_fieldexists('tyzm_diamondvote_domainlist',  'description')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD `description` varchar(255) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('tyzm_diamondvote_domainlist',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_domainlist',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_indexexists('tyzm_diamondvote_domainlist',  'content')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD KEY `content` (`domain`);");
}
if(!pdo_indexexists('tyzm_diamondvote_domainlist',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_domainlist')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tyzm_diamondvote_fansdata',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_fansdata')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_fansdata',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_fansdata')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_fansdata',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_fansdata')." ADD `openid` varchar(50) NOT NULL COMMENT '用户openid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_fansdata',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_fansdata')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_indexexists('tyzm_diamondvote_fansdata',  'indx_openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_fansdata')." ADD KEY `indx_openid` (`openid`);");
}
if(!pdo_indexexists('tyzm_diamondvote_fansdata',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_fansdata')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tyzm_diamondvote_friendship',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_friendship')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_friendship',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_friendship')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_friendship',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_friendship')." ADD `title` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('tyzm_diamondvote_friendship',  'eventrule')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_friendship')." ADD `eventrule` mediumtext COMMENT '活动规则';");
}
if(!pdo_fieldexists('tyzm_diamondvote_friendship',  'config')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_friendship')." ADD `config` mediumtext COMMENT '相关配置';");
}
if(!pdo_fieldexists('tyzm_diamondvote_friendship',  'packata')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_friendship')." ADD `packata` mediumtext COMMENT '套餐配置';");
}
if(!pdo_fieldexists('tyzm_diamondvote_friendship',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_friendship')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_friendship',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_friendship')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_indexexists('tyzm_diamondvote_friendship',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_friendship')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `tid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'ptid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `ptid` varchar(128) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'uniontid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `uniontid` varchar(30) NOT NULL COMMENT '商户单号';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `paytype` varchar(10) NOT NULL COMMENT '支付方式';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'oauth_openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `oauth_openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'user_ip')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `user_ip` varchar(15) NOT NULL COMMENT '客户端ip';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'gifttitle')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `gifttitle` varchar(8) DEFAULT '0' COMMENT '礼物名称';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'giftcount')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `giftcount` int(10) NOT NULL DEFAULT '1' COMMENT '礼物数量';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'gifticon')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `gifticon` varchar(255) NOT NULL COMMENT '礼物图标';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `fee` decimal(10,2) NOT NULL COMMENT '支付金额';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'giftvote')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `giftvote` varchar(50) NOT NULL COMMENT '抵票数';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'ispay')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `ispay` int(1) NOT NULL COMMENT '支付状态';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'isdeal')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `isdeal` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('tyzm_diamondvote_gift',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_indexexists('tyzm_diamondvote_gift',  'indx_tid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD KEY `indx_tid` (`tid`);");
}
if(!pdo_indexexists('tyzm_diamondvote_gift',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('tyzm_diamondvote_gift',  'indx_ptid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_gift')." ADD KEY `indx_ptid` (`ptid`);");
}
if(!pdo_fieldexists('tyzm_diamondvote_looklist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_looklist')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_looklist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_looklist')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_looklist',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_looklist')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_looklist',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_looklist')." ADD `tid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_looklist',  'oauth_openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_looklist')." ADD `oauth_openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_looklist',  'user_ip')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_looklist')." ADD `user_ip` varchar(15) NOT NULL COMMENT '客户端ip';");
}
if(!pdo_fieldexists('tyzm_diamondvote_looklist',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_looklist')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `tid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'unionid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `unionid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户unionid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'user_ip')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `user_ip` varchar(15) NOT NULL COMMENT '客户端ip';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'mch_billno')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `mch_billno` varchar(28) DEFAULT '';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'total_amount')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `total_amount` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'total_num')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `total_num` int(3) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'client_ip')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `client_ip` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'send_time')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `send_time` varchar(14) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'send_listid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `send_listid` varchar(32) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'return_data')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `return_data` text;");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'return_code')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `return_code` varchar(16) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'return_msg')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `return_msg` varchar(256) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'result_code')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `result_code` varchar(16) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'err_code')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `err_code` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'err_code_des')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `err_code_des` varchar(128) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'rewards')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `rewards` varchar(20) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '状态-012wz-com';");
}
if(!pdo_fieldexists('tyzm_diamondvote_redpack',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_indexexists('tyzm_diamondvote_redpack',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_redpack')." ADD UNIQUE KEY `id` (`id`);");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `title` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `thumb` varchar(255) DEFAULT '' COMMENT '封面';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'apstarttime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `apstarttime` int(10) DEFAULT '0' COMMENT '报名时间start';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'apendtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `apendtime` int(10) DEFAULT '0' COMMENT '报名时间end';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'votestarttime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `votestarttime` int(10) DEFAULT '0' COMMENT '投票时间start';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'voteendtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `voteendtime` int(10) DEFAULT '0' COMMENT '投票时间end';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'topimg')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `topimg` varchar(255) DEFAULT '' COMMENT '背景图片';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `bgcolor` varchar(255) DEFAULT '' COMMENT '背景颜色';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'style')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `style` varchar(255) DEFAULT '' COMMENT '风格';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'infomsg')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `infomsg` mediumtext COMMENT '活动介绍';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'eventrule')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `eventrule` mediumtext COMMENT '活动规则';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'awardmsg')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `awardmsg` mediumtext COMMENT '奖品介绍';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'prizemsg')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `prizemsg` mediumtext COMMENT '奖品简介';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'endintro')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `endintro` mediumtext COMMENT '活动结束说明';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'config')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `config` mediumtext COMMENT '相关配置';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'addata')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `addata` mediumtext COMMENT '广告配置';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'usepwd')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `usepwd` varchar(10) NOT NULL COMMENT '核销密码';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'area')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `area` varchar(100) DEFAULT '0' COMMENT '地区限制';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'followguide')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `followguide` varchar(255) DEFAULT '' COMMENT '未关注引导提示';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'shareimg')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `shareimg` varchar(255) DEFAULT '' COMMENT '分享图标';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `sharetitle` varchar(100) DEFAULT '' COMMENT '分享标题';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `sharedesc` varchar(300) DEFAULT '' COMMENT '分享简介';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'giftdata')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `giftdata` mediumtext NOT NULL COMMENT '礼物配置数据';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'bill_data')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `bill_data` mediumtext NOT NULL COMMENT '海报数据';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'applydata')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `applydata` mediumtext NOT NULL COMMENT '报名信息配置';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `notice` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('tyzm_diamondvote_reply',  'verifycode')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD `verifycode` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('tyzm_diamondvote_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('tyzm_diamondvote_reply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_reply')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `tid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'ptid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `ptid` varchar(128) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'uniontid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `uniontid` varchar(30) NOT NULL COMMENT '商户单号';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `paytype` varchar(10) NOT NULL COMMENT '支付方式';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'oauth_openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `oauth_openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'user_ip')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `user_ip` varchar(15) NOT NULL COMMENT '客户端ip';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'packname')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `packname` varchar(8) DEFAULT '0' COMMENT '套餐类型';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'packicon')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `packicon` varchar(255) NOT NULL COMMENT '套餐图标';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'packtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `packtime` varchar(255) NOT NULL COMMENT '套餐有效期';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `fee` decimal(10,2) NOT NULL COMMENT '支付金额';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'packnum')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `packnum` varchar(50) NOT NULL COMMENT '套餐数量';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'ispay')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `ispay` int(1) NOT NULL COMMENT '支付状态';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'isdeal')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `isdeal` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('tyzm_diamondvote_viporder',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_indexexists('tyzm_diamondvote_viporder',  'indx_tid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD KEY `indx_tid` (`tid`);");
}
if(!pdo_indexexists('tyzm_diamondvote_viporder',  'indx_ptid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_viporder')." ADD KEY `indx_ptid` (`ptid`);");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `tid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'user_ip')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `user_ip` varchar(15) NOT NULL COMMENT '客户端ip';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'votetype')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `votetype` tinyint(1) DEFAULT '0' COMMENT '投票类型，0投票，1钻石';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `fee` decimal(10,2) NOT NULL COMMENT '支付金额';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'paynum')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `paynum` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'votenum')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `votenum` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'ptid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `ptid` varchar(50) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'plid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `plid` varchar(50) NOT NULL COMMENT '支付id';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'mch_billno')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `mch_billno` varchar(28) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'send_time')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `send_time` varchar(14) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'send_listid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `send_listid` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'return_data')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `return_data` mediumtext NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'return_code')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `return_code` varchar(16) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'return_msg')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `return_msg` varchar(256) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'result_code')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `result_code` varchar(16) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'err_code')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `err_code` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'err_code_des')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `err_code_des` varchar(128) NOT NULL COMMENT '真实用户openid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'ispay')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `ispay` int(1) NOT NULL COMMENT '支付状态';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'isdeal')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `isdeal` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'oauth_openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `oauth_openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tyzm_diamondvote_votedata',  'reward')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD `reward` tinyint(1) NOT NULL COMMENT '抽奖状态';");
}
if(!pdo_indexexists('tyzm_diamondvote_votedata',  'indx_tid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD KEY `indx_tid` (`tid`);");
}
if(!pdo_indexexists('tyzm_diamondvote_votedata',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('tyzm_diamondvote_votedata',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_votedata')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'noid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `noid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `from_user` varchar(50) NOT NULL DEFAULT '0' COMMENT '真实用户openid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '用户openid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'user_ip')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `user_ip` varchar(15) NOT NULL COMMENT '客户端ip';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `sex` tinyint(1) DEFAULT '0' COMMENT '性别';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `name` varchar(30) NOT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `mobile` varchar(11) DEFAULT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'introduction')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `introduction` varchar(255) DEFAULT NULL COMMENT '个人介绍';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'img1')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `img1` varchar(255) DEFAULT '' COMMENT '图1';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'img2')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `img2` varchar(255) DEFAULT '' COMMENT '图2';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'img3')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `img3` varchar(255) DEFAULT '' COMMENT '图3';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'img4')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `img4` varchar(255) DEFAULT '' COMMENT '图4';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'img5')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `img5` varchar(255) DEFAULT '' COMMENT '图5';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'details')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `details` mediumtext COMMENT '投票详情';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'formatdata')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `formatdata` mediumtext COMMENT '上传图片mediaid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'votenum')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `votenum` int(255) DEFAULT '0' COMMENT '投票数量';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'diamondnum')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `diamondnum` int(255) DEFAULT '0' COMMENT '钻石数量';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'eggnum')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `eggnum` int(255) DEFAULT '0' COMMENT '鸡蛋数量';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'locktime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `locktime` int(10) DEFAULT '0' COMMENT '锁定时间';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'attestation')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `attestation` tinyint(1) DEFAULT '0' COMMENT '认证状态';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'atmsg')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `atmsg` varchar(255) NOT NULL DEFAULT '' COMMENT '认证简介';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `createtime` int(10) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'oauth_openid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `oauth_openid` varchar(50) NOT NULL DEFAULT '0' COMMENT '真实用户openid';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'joindata')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `joindata` mediumtext NOT NULL COMMENT '报名信息';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'giftcount')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `giftcount` decimal(10,2) NOT NULL COMMENT '礼物数量';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'vheat')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `vheat` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟热度';");
}
if(!pdo_fieldexists('tyzm_diamondvote_voteuser',  'lastvotetime')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD `lastvotetime` int(10) DEFAULT '0' COMMENT '最新投票时间';");
}
if(!pdo_indexexists('tyzm_diamondvote_voteuser',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('tyzm_diamondvote_voteuser',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tyzm_diamondvote_voteuser')." ADD KEY `indx_uniacid` (`uniacid`);");
}

?>