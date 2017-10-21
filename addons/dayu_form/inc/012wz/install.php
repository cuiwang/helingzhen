<?php

$sql =<<<EOF
CREATE TABLE IF NOT EXISTS `ims_dayu_form` (
  `reid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `titles` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `information` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `inhome` tinyint(4) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `member` varchar(20) NOT NULL DEFAULT '姓名',
  `phone` varchar(20) NOT NULL DEFAULT '手机',
  `noticeemail` varchar(50) NOT NULL DEFAULT '',
  `k_templateid` varchar(50) NOT NULL DEFAULT '',
  `kfid` varchar(50) NOT NULL DEFAULT '',
  `m_templateid` varchar(50) NOT NULL DEFAULT '',
  `kfirst` varchar(100) NOT NULL COMMENT '客服模板页头',
  `kfoot` varchar(100) NOT NULL COMMENT '客服模板页尾',
  `mfirst` varchar(100) NOT NULL COMMENT '客户模板页头',
  `mfoot` varchar(100) NOT NULL COMMENT '客户模板页尾',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `adds` varchar(20) NOT NULL DEFAULT '',
  `skins` varchar(20) NOT NULL DEFAULT 'weui',
  `custom_status` int(1) NOT NULL DEFAULT '0' COMMENT '客服消息状态',
  `mbgroup` int(10) unsigned NOT NULL,
  `outlink` varchar(250) NOT NULL,
  `isinfo` tinyint(1) NOT NULL DEFAULT '0',
  `isvoice` tinyint(1) NOT NULL DEFAULT '0',
  `isrevoice` tinyint(1) NOT NULL DEFAULT '0',
  `ivoice` tinyint(1) NOT NULL DEFAULT '0',
  `voice` varchar(50) NOT NULL DEFAULT '',
  `voicedec` varchar(50) NOT NULL DEFAULT '',
  `isloc` tinyint(1) NOT NULL DEFAULT '0',
  `isrethumb` tinyint(1) NOT NULL DEFAULT '0',
  `isrecord` tinyint(1) NOT NULL DEFAULT '0',
  `isget` tinyint(1) NOT NULL DEFAULT '0',
  `credit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `smsid` int(11) NOT NULL DEFAULT '0',
  `smsnotice` int(11) NOT NULL DEFAULT '0',
  `smstype` int(1) NOT NULL DEFAULT '0',
  `agreement` varchar(50) NOT NULL DEFAULT '',
  `paixu` int(1) NOT NULL DEFAULT '0',
  `field` tinyint(1) NOT NULL DEFAULT '0',
  `fields` text NOT NULL,
  `avatar` tinyint(1) NOT NULL DEFAULT '0',
  `bcolor` varchar(10) NOT NULL DEFAULT '',
  `pluraltit` varchar(50) NOT NULL DEFAULT '',
  `plural` tinyint(1) NOT NULL DEFAULT '0',
  `par` text NOT NULL,
  `linkage` text NOT NULL,
  `score_total` int(11) NOT NULL DEFAULT '0' COMMENT '总分',
  `score_vr` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟分',
  `score_num` int(11) NOT NULL DEFAULT '0' COMMENT '人数',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reid`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_form_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `raply` varchar(200) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_form_data` (
  `redid` bigint(20) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `rerid` int(11) NOT NULL,
  `refid` int(11) NOT NULL,
  `data` varchar(800) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`redid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_form_fields` (
  `refid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `essential` tinyint(1) NOT NULL DEFAULT '0',
  `only` tinyint(1) NOT NULL DEFAULT '0',
  `bind` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `loc` tinyint(1) NOT NULL DEFAULT '0',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`refid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_form_info` (
  `rerid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `member` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `thumb` text NOT NULL,
  `voice` varchar(250) NOT NULL,
  `revoice` varchar(250) NOT NULL,
  `rethumb` varchar(250) NOT NULL,
  `loc_x` varchar(20) NOT NULL DEFAULT '',
  `loc_y` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `yuyuetime` int(10) NOT NULL DEFAULT '0' COMMENT '客服确认时间',
  `kf` varchar(50) NOT NULL DEFAULT '',
  `kfinfo` varchar(100) NOT NULL COMMENT '客服信息',
  `var1` varchar(250) NOT NULL DEFAULT '',
  `var2` varchar(250) NOT NULL DEFAULT '',
  `var3` varchar(250) NOT NULL DEFAULT '',
  `file` text NOT NULL,
  `linkage` text NOT NULL,
  `kid` int(11) NOT NULL DEFAULT '0',
  `commentid` int(11) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rerid`),
  KEY `reid` (`reid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_form_linkage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reid` int(11) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `parentid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_form_loc` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `loc_x` varchar(20) NOT NULL,
  `loc_y` varchar(20) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_form_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_form_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_form_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `reid` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  `openid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `realname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `groupid` int(10) NOT NULL DEFAULT '0',
  `gender` tinyint(1) NOT NULL DEFAULT '1',
  `province` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `dist` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `realaddress` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `qq` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `alipay` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `idcard` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `frontid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `backid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fdriving` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bdriving` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `modules` int(2) NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `weid` (`weid`),
  KEY `openid` (`openid`),
  KEY `idcard` (`idcard`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='会员信息表';
CREATE TABLE IF NOT EXISTS `ims_dayu_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `appid` varchar(50) NOT NULL,
  `templateid` varchar(50) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  `num` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
EOF;
pdo_run($sql);
