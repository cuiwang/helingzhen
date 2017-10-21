<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_jy_reads_bonus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT '0',
  `bonusname` varchar(50) DEFAULT NULL COMMENT '名称',
  `bonusthumb` varchar(255) DEFAULT NULL COMMENT '图片',
  `bonuscount` int(10) DEFAULT '0' COMMENT '总数量',
  `bonusneed` int(10) DEFAULT '0' COMMENT '需要数量',
  `bonusrest` int(10) DEFAULT '0' COMMENT '剩余数量',
  `bonusvalue` float(10,2) DEFAULT '0.00' COMMENT '价值',
  `islimit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要活动0不需要，1需要',
  `limit` text COMMENT '活动信息',
  `bonusmsg` text COMMENT '红包页信息',
  `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要限制地区0不需要，1需要',
  `area` text COMMENT '地理位置信息',
  `wishing` varchar(120) DEFAULT NULL COMMENT '红包祝福语最大长度128',
  `remark` varchar(255) DEFAULT NULL COMMENT '红包备注信息最大长度256',
  `actname` varchar(255) DEFAULT '集阅读' COMMENT '活动名称',
  `sendname` varchar(255) DEFAULT '集阅读' COMMENT '活动名称',
  `isrange` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否金额区间',
  `bonusvaluerange` varchar(255) DEFAULT '' COMMENT '金额区间',
  `displayorder` int(10) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已删除1为存在',
  `share` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为已不显示1为显示',
  `info` text COMMENT '获取的信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_reads_bonus_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `userid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已失败1为成功',
  `log` text COMMENT '日志',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_reads_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT '0',
  `couponname` varchar(50) DEFAULT NULL COMMENT '名称',
  `couponthumb` varchar(255) DEFAULT NULL COMMENT '图片',
  `couponcount` int(10) DEFAULT '0' COMMENT '总数量',
  `couponneed` int(10) DEFAULT '0' COMMENT '需要数量',
  `couponrest` int(10) DEFAULT '0' COMMENT '剩余数量',
  `couponcode` varchar(255) DEFAULT NULL COMMENT '微信上生成的',
  `couponmsg` varchar(255) DEFAULT NULL COMMENT '卡券页面信息',
  `islimit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要活动0不需要，1需要',
  `limit` text COMMENT '活动信息',
  `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要限制地区0不需要，1需要',
  `area` text COMMENT '地理位置信息',
  `displayorder` int(10) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已删除1为存在',
  `share` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为已不显示1为显示',
  `info` text COMMENT '获取的信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_reads_coupon_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `userid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已失败1为成功',
  `log` text COMMENT '日志',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_reads_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `replyid` int(10) unsigned NOT NULL COMMENT '回复ID',
  `popenid` varchar(30) NOT NULL COMMENT '父ID',
  `sopenid` varchar(30) NOT NULL COMMENT '子ID',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_reads_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT '0',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `replyid` int(10) unsigned NOT NULL COMMENT '回复ID',
  `prizename` varchar(50) DEFAULT NULL COMMENT '奖品名称',
  `prizeurl` varchar(50) DEFAULT NULL COMMENT '奖品链接',
  `prizethumb` varchar(255) DEFAULT NULL COMMENT '奖品图片',
  `prizecount` int(10) DEFAULT '0' COMMENT '奖品总数量',
  `prizeneed` int(10) DEFAULT '0' COMMENT '兑奖需要数量',
  `prizerest` int(10) DEFAULT '0' COMMENT '奖品剩余数量',
  `displayorder` int(10) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已删除1为存在',
  `share` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为已不显示1为显示',
  `info` text COMMENT '获取的信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='奖项从属于活动';
CREATE TABLE IF NOT EXISTS `ims_jy_reads_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态0结束，1正常，2暂停',
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `name` varchar(255) NOT NULL DEFAULT '集阅读' COMMENT '活动名称',
  `share_title` varchar(255) NOT NULL COMMENT '分享标题',
  `share_thumb` varchar(255) NOT NULL COMMENT '分享图片',
  `share_description` varchar(255) NOT NULL COMMENT '分享描述',
  `link` varchar(255) NOT NULL COMMENT '当前关注链接',
  `loading` varchar(255) NOT NULL COMMENT '加载时图片',
  `arrow` varchar(255) NOT NULL COMMENT '加载时图片下方箭头',
  `top` varchar(255) NOT NULL COMMENT '内容顶部图片',
  `bottom` varchar(255) NOT NULL COMMENT '内容顶部图片',
  `telephone` varchar(50) NOT NULL COMMENT '点击bottom拨打电话',
  `bgcolor` varchar(10) NOT NULL DEFAULT '#FFFFFF' COMMENT '背景颜色',
  `content` text COMMENT '显示内容',
  `rule` text COMMENT '显示兑奖规则或活动地址',
  `tips` varchar(255) NOT NULL COMMENT '备注',
  `ad` varchar(255) NOT NULL COMMENT '核销页广告',
  `ad_url` varchar(255) NOT NULL COMMENT '核销页URL',
  `prizes` longtext NOT NULL COMMENT '当前回复的文章奖项',
  `follow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要强制关注0不需要，1需要',
  `mutual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁止相互采集0-3',
  `bonus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁止红包0禁止，1开放',
  `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要限制地区0不需要，1需要',
  `area` text COMMENT '地理位置信息',
  `area_title` text COMMENT '地理位置信息名称',
  `copyright` varchar(20) NOT NULL,
  `starttime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `share` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为已不显示1为显示',
  `alias` varchar(255) NOT NULL DEFAULT '阅读' COMMENT '阅读别名',
  `start` varchar(255) NOT NULL DEFAULT '' COMMENT '底部图片',
  `zdy_url` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义链接',
  `locationtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 ip定位，1 gps定位',
  `bggcolor` varchar(10) NOT NULL DEFAULT '#FFFFFF' COMMENT '背景颜色',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='回复信息表，包含所有回复资料以及活动资料';
CREATE TABLE IF NOT EXISTS `ims_jy_reads_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `replyid` int(10) unsigned NOT NULL COMMENT '回复ID',
  `uid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID',
  `openid` varchar(30) NOT NULL COMMENT 'openid',
  `userinfo` text COMMENT '用户信息',
  `status` tinyint(1) unsigned NOT NULL COMMENT '用户状态',
  `sn` varchar(20) NOT NULL COMMENT 'SN奖品唯一码、如果为bonus-sn则为获取红包',
  `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖项ID，未兑奖时为0，prize的ID或者Bonus的ID',
  `prize` text COMMENT '序列化信息',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='点开文章的用户';
CREATE TABLE IF NOT EXISTS `ims_jy_reads_user_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `propertykey` varchar(255) NOT NULL COMMENT '检索键名',
  `propertyvalue` varchar(255) NOT NULL COMMENT '检索值名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户属性';
CREATE TABLE IF NOT EXISTS `ims_jy_reads_verifier` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL COMMENT '公众号ID',
  `rid` int(10) NOT NULL COMMENT '规则ID',
  `replyid` int(10) NOT NULL COMMENT '回复ID',
  `uid` int(10) NOT NULL COMMENT '微赞用户表memberID',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `prizeid` int(10) NOT NULL COMMENT '奖项ID',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常，0禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动核销人员';
";
pdo_run($sql);
if(!pdo_fieldexists('jy_reads_bonus',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_bonus',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `uniacid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'bonusname')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `bonusname` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'bonusthumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `bonusthumb` varchar(255) DEFAULT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'bonuscount')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `bonuscount` int(10) DEFAULT '0' COMMENT '总数量';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'bonusneed')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `bonusneed` int(10) DEFAULT '0' COMMENT '需要数量';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'bonusrest')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `bonusrest` int(10) DEFAULT '0' COMMENT '剩余数量';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'bonusvalue')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `bonusvalue` float(10,2) DEFAULT '0.00' COMMENT '价值';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'islimit')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `islimit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要活动0不需要，1需要';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'limit')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `limit` text COMMENT '活动信息';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'bonusmsg')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `bonusmsg` text COMMENT '红包页信息';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'location')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要限制地区0不需要，1需要';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'area')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `area` text COMMENT '地理位置信息';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'wishing')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `wishing` varchar(120) DEFAULT NULL COMMENT '红包祝福语最大长度128';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `remark` varchar(255) DEFAULT NULL COMMENT '红包备注信息最大长度256';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'actname')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `actname` varchar(255) DEFAULT '集阅读' COMMENT '活动名称';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'sendname')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `sendname` varchar(255) DEFAULT '集阅读' COMMENT '活动名称';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'isrange')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `isrange` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否金额区间';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'bonusvaluerange')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `bonusvaluerange` varchar(255) DEFAULT '' COMMENT '金额区间';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `displayorder` int(10) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已删除1为存在';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'share')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `share` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为已不显示1为显示';");
}
if(!pdo_fieldexists('jy_reads_bonus',  'info')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus')." ADD `info` text COMMENT '获取的信息';");
}
if(!pdo_fieldexists('jy_reads_bonus_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_bonus_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus_log')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('jy_reads_bonus_log',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus_log')." ADD `userid` int(10) unsigned NOT NULL COMMENT '用户ID';");
}
if(!pdo_fieldexists('jy_reads_bonus_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus_log')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已失败1为成功';");
}
if(!pdo_fieldexists('jy_reads_bonus_log',  'log')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus_log')." ADD `log` text COMMENT '日志';");
}
if(!pdo_fieldexists('jy_reads_bonus_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_bonus_log')." ADD `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `uniacid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'couponname')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `couponname` varchar(50) DEFAULT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'couponthumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `couponthumb` varchar(255) DEFAULT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'couponcount')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `couponcount` int(10) DEFAULT '0' COMMENT '总数量';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'couponneed')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `couponneed` int(10) DEFAULT '0' COMMENT '需要数量';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'couponrest')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `couponrest` int(10) DEFAULT '0' COMMENT '剩余数量';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'couponcode')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `couponcode` varchar(255) DEFAULT NULL COMMENT '微信上生成的';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'couponmsg')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `couponmsg` varchar(255) DEFAULT NULL COMMENT '卡券页面信息';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'islimit')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `islimit` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要活动0不需要，1需要';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'limit')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `limit` text COMMENT '活动信息';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'location')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要限制地区0不需要，1需要';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'area')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `area` text COMMENT '地理位置信息';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `displayorder` int(10) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已删除1为存在';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'share')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `share` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为已不显示1为显示';");
}
if(!pdo_fieldexists('jy_reads_coupon',  'info')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon')." ADD `info` text COMMENT '获取的信息';");
}
if(!pdo_fieldexists('jy_reads_coupon_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_coupon_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon_log')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('jy_reads_coupon_log',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon_log')." ADD `userid` int(10) unsigned NOT NULL COMMENT '用户ID';");
}
if(!pdo_fieldexists('jy_reads_coupon_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon_log')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已失败1为成功';");
}
if(!pdo_fieldexists('jy_reads_coupon_log',  'log')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon_log')." ADD `log` text COMMENT '日志';");
}
if(!pdo_fieldexists('jy_reads_coupon_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_coupon_log')." ADD `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间';");
}
if(!pdo_fieldexists('jy_reads_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_log')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('jy_reads_log',  'replyid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_log')." ADD `replyid` int(10) unsigned NOT NULL COMMENT '回复ID';");
}
if(!pdo_fieldexists('jy_reads_log',  'popenid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_log')." ADD `popenid` varchar(30) NOT NULL COMMENT '父ID';");
}
if(!pdo_fieldexists('jy_reads_log',  'sopenid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_log')." ADD `sopenid` varchar(30) NOT NULL COMMENT '子ID';");
}
if(!pdo_fieldexists('jy_reads_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_log')." ADD `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间';");
}
if(!pdo_fieldexists('jy_reads_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_prize',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `uniacid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_reads_prize',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('jy_reads_prize',  'replyid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `replyid` int(10) unsigned NOT NULL COMMENT '回复ID';");
}
if(!pdo_fieldexists('jy_reads_prize',  'prizename')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `prizename` varchar(50) DEFAULT NULL COMMENT '奖品名称';");
}
if(!pdo_fieldexists('jy_reads_prize',  'prizeurl')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `prizeurl` varchar(50) DEFAULT NULL COMMENT '奖品链接';");
}
if(!pdo_fieldexists('jy_reads_prize',  'prizethumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `prizethumb` varchar(255) DEFAULT NULL COMMENT '奖品图片';");
}
if(!pdo_fieldexists('jy_reads_prize',  'prizecount')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `prizecount` int(10) DEFAULT '0' COMMENT '奖品总数量';");
}
if(!pdo_fieldexists('jy_reads_prize',  'prizeneed')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `prizeneed` int(10) DEFAULT '0' COMMENT '兑奖需要数量';");
}
if(!pdo_fieldexists('jy_reads_prize',  'prizerest')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `prizerest` int(10) DEFAULT '0' COMMENT '奖品剩余数量';");
}
if(!pdo_fieldexists('jy_reads_prize',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `displayorder` int(10) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('jy_reads_prize',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0为已删除1为存在';");
}
if(!pdo_fieldexists('jy_reads_prize',  'share')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `share` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为已不显示1为显示';");
}
if(!pdo_fieldexists('jy_reads_prize',  'info')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_prize')." ADD `info` text COMMENT '获取的信息';");
}
if(!pdo_fieldexists('jy_reads_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `status` tinyint(1) unsigned NOT NULL COMMENT '状态0结束，1正常，2暂停';");
}
if(!pdo_fieldexists('jy_reads_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('jy_reads_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('jy_reads_reply',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `name` varchar(255) NOT NULL DEFAULT '集阅读' COMMENT '活动名称';");
}
if(!pdo_fieldexists('jy_reads_reply',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `share_title` varchar(255) NOT NULL COMMENT '分享标题';");
}
if(!pdo_fieldexists('jy_reads_reply',  'share_thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `share_thumb` varchar(255) NOT NULL COMMENT '分享图片';");
}
if(!pdo_fieldexists('jy_reads_reply',  'share_description')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `share_description` varchar(255) NOT NULL COMMENT '分享描述';");
}
if(!pdo_fieldexists('jy_reads_reply',  'link')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `link` varchar(255) NOT NULL COMMENT '当前关注链接';");
}
if(!pdo_fieldexists('jy_reads_reply',  'loading')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `loading` varchar(255) NOT NULL COMMENT '加载时图片';");
}
if(!pdo_fieldexists('jy_reads_reply',  'arrow')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `arrow` varchar(255) NOT NULL COMMENT '加载时图片下方箭头';");
}
if(!pdo_fieldexists('jy_reads_reply',  'top')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `top` varchar(255) NOT NULL COMMENT '内容顶部图片';");
}
if(!pdo_fieldexists('jy_reads_reply',  'bottom')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `bottom` varchar(255) NOT NULL COMMENT '内容顶部图片';");
}
if(!pdo_fieldexists('jy_reads_reply',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `telephone` varchar(50) NOT NULL COMMENT '点击bottom拨打电话';");
}
if(!pdo_fieldexists('jy_reads_reply',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `bgcolor` varchar(10) NOT NULL DEFAULT '#FFFFFF' COMMENT '背景颜色';");
}
if(!pdo_fieldexists('jy_reads_reply',  'content')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `content` text COMMENT '显示内容';");
}
if(!pdo_fieldexists('jy_reads_reply',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `rule` text COMMENT '显示兑奖规则或活动地址';");
}
if(!pdo_fieldexists('jy_reads_reply',  'tips')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `tips` varchar(255) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('jy_reads_reply',  'ad')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `ad` varchar(255) NOT NULL COMMENT '核销页广告';");
}
if(!pdo_fieldexists('jy_reads_reply',  'ad_url')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `ad_url` varchar(255) NOT NULL COMMENT '核销页URL';");
}
if(!pdo_fieldexists('jy_reads_reply',  'prizes')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `prizes` longtext NOT NULL COMMENT '当前回复的文章奖项';");
}
if(!pdo_fieldexists('jy_reads_reply',  'follow')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `follow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要强制关注0不需要，1需要';");
}
if(!pdo_fieldexists('jy_reads_reply',  'mutual')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `mutual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁止相互采集0-3';");
}
if(!pdo_fieldexists('jy_reads_reply',  'bonus')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `bonus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁止红包0禁止，1开放';");
}
if(!pdo_fieldexists('jy_reads_reply',  'location')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `location` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要限制地区0不需要，1需要';");
}
if(!pdo_fieldexists('jy_reads_reply',  'area')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `area` text COMMENT '地理位置信息';");
}
if(!pdo_fieldexists('jy_reads_reply',  'area_title')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `area_title` text COMMENT '地理位置信息名称';");
}
if(!pdo_fieldexists('jy_reads_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `copyright` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('jy_reads_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `starttime` int(10) unsigned NOT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('jy_reads_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `endtime` int(10) unsigned NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('jy_reads_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间';");
}
if(!pdo_fieldexists('jy_reads_reply',  'share')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `share` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为已不显示1为显示';");
}
if(!pdo_fieldexists('jy_reads_reply',  'alias')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `alias` varchar(255) NOT NULL DEFAULT '阅读' COMMENT '阅读别名';");
}
if(!pdo_fieldexists('jy_reads_reply',  'start')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `start` varchar(255) NOT NULL DEFAULT '' COMMENT '底部图片';");
}
if(!pdo_fieldexists('jy_reads_reply',  'zdy_url')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `zdy_url` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义链接';");
}
if(!pdo_fieldexists('jy_reads_reply',  'locationtype')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `locationtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0 ip定位，1 gps定位';");
}
if(!pdo_fieldexists('jy_reads_reply',  'bggcolor')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_reply')." ADD `bggcolor` varchar(10) NOT NULL DEFAULT '#FFFFFF' COMMENT '背景颜色';");
}
if(!pdo_fieldexists('jy_reads_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('jy_reads_user',  'replyid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `replyid` int(10) unsigned NOT NULL COMMENT '回复ID';");
}
if(!pdo_fieldexists('jy_reads_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `uid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID';");
}
if(!pdo_fieldexists('jy_reads_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `openid` varchar(30) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('jy_reads_user',  'userinfo')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `userinfo` text COMMENT '用户信息';");
}
if(!pdo_fieldexists('jy_reads_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `status` tinyint(1) unsigned NOT NULL COMMENT '用户状态';");
}
if(!pdo_fieldexists('jy_reads_user',  'sn')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `sn` varchar(20) NOT NULL COMMENT 'SN奖品唯一码、如果为bonus-sn则为获取红包';");
}
if(!pdo_fieldexists('jy_reads_user',  'prizeid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖项ID，未兑奖时为0，prize的ID或者Bonus的ID';");
}
if(!pdo_fieldexists('jy_reads_user',  'prize')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `prize` text COMMENT '序列化信息';");
}
if(!pdo_fieldexists('jy_reads_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间';");
}
if(!pdo_fieldexists('jy_reads_user',  'hits')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user')." ADD `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击次数';");
}
if(!pdo_fieldexists('jy_reads_user_property',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user_property')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_user_property',  'propertykey')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user_property')." ADD `propertykey` varchar(255) NOT NULL COMMENT '检索键名';");
}
if(!pdo_fieldexists('jy_reads_user_property',  'propertyvalue')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_user_property')." ADD `propertyvalue` varchar(255) NOT NULL COMMENT '检索值名';");
}
if(!pdo_fieldexists('jy_reads_verifier',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_verifier')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_reads_verifier',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_verifier')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('jy_reads_verifier',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_verifier')." ADD `rid` int(10) NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('jy_reads_verifier',  'replyid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_verifier')." ADD `replyid` int(10) NOT NULL COMMENT '回复ID';");
}
if(!pdo_fieldexists('jy_reads_verifier',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_verifier')." ADD `uid` int(10) NOT NULL COMMENT '微赞用户表memberID';");
}
if(!pdo_fieldexists('jy_reads_verifier',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_verifier')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('jy_reads_verifier',  'prizeid')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_verifier')." ADD `prizeid` int(10) NOT NULL COMMENT '奖项ID';");
}
if(!pdo_fieldexists('jy_reads_verifier',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_verifier')." ADD `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间';");
}
if(!pdo_fieldexists('jy_reads_verifier',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_reads_verifier')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1正常，0禁用';");
}

?>