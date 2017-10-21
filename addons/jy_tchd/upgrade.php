<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_jy_tchd_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '批次ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `description` varchar(250) NOT NULL COMMENT '描述',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1多选，2填空，3下拉菜单，4市区街道 , 5三级联动 , 6手机 , 7姓名 , 8头像 ,9图片',
  `mobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示于手机端',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `content` text,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) DEFAULT '0' COMMENT '0为纯图片,1为描述，2为链接跳转',
  `url` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `createtime` int(10) NOT NULL,
  `type` tinyint(1) DEFAULT '0' COMMENT '0为默认页，其他为自定义位置',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage',
  `description` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
  `updatetime` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage',
  `description` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `weid` int(10) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `code` varchar(200) NOT NULL,
  `createtime` int(10) NOT NULL,
  `mid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_dianyuan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `uid` int(10) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
  `username` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(20) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  `QQ` varchar(200) DEFAULT NULL,
  `wechat` varchar(200) DEFAULT NULL,
  `mendianid` int(10) DEFAULT '0',
  `type` int(10) DEFAULT '0' COMMENT '0代表店员，1为店长，2为待审核',
  `password` varchar(200) DEFAULT NULL,
  `description` text,
  `updatetime` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_feedback` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `mid` int(10) NOT NULL,
  `feedback` varchar(255) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_hd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `hdname` varchar(200) NOT NULL DEFAULT '',
  `hdcateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动类别id',
  `thumb` text,
  `time` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) DEFAULT NULL,
  `province` varchar(200) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `dist` varchar(200) DEFAULT NULL,
  `town` varchar(200) DEFAULT NULL,
  `street` varchar(200) DEFAULT NULL,
  `lng` varchar(10) DEFAULT NULL,
  `lat` varchar(10) DEFAULT NULL,
  `description` text,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `num` int(10) NOT NULL,
  `pv` int(10) NOT NULL COMMENT '浏览量',
  `sc` int(10) NOT NULL COMMENT '人气',
  `isindex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要显示首页',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为显示，2为不显示，3为待审核，4为审核不通过',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除',
  `createtime` int(10) unsigned NOT NULL,
  `deadline` int(10) unsigned NOT NULL,
  `tk` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启退款',
  `tk_day` int(10) unsigned NOT NULL,
  `lx_mobile` varchar(20) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `renshu` varchar(200) NOT NULL DEFAULT '',
  `qx` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '选中可否取消',
  `shenhe` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要审核',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为报名,2为文案',
  `is_tj` int(1) DEFAULT '0',
  `realnum` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_hd_cy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `priceid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `status` int(2) NOT NULL COMMENT '0为已参加,1为未参加',
  `plid` bigint(11) unsigned DEFAULT NULL COMMENT 'core_paylog表的id',
  `price` decimal(10,2) DEFAULT '0.00',
  `jifen` decimal(10,2) DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_hd_cy_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `hdid` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_hd_gz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `gzid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0商家，1用户',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_hd_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `pricename` varchar(255) DEFAULT '',
  `price` decimal(10,2) DEFAULT '0.00',
  `num` int(10) unsigned NOT NULL DEFAULT '0',
  `renshu` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '0为未有人报名,1为有人报名',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_hd_pv` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_hd_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `hdid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `report` text,
  `status` int(1) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_hd_sc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `content` text,
  `cateid` tinyint(3) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `displayorder` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_help_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `content` varchar(255) DEFAULT '',
  `deleted` tinyint(3) DEFAULT '0',
  `displayorder` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_help_read` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0',
  `helpid` int(11) DEFAULT '0',
  `cateid` int(11) DEFAULT '0',
  `createtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_jiedao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '批次ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `description` text COMMENT '描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除',
  `thumb` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `user` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `op` text,
  `createtime` int(11) DEFAULT '0',
  `ip` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_weid` (`weid`),
  FULLTEXT KEY `idx_name` (`name`),
  FULLTEXT KEY `idx_user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_md_brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage',
  `description` varchar(255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_md_fenlei` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage',
  `description` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `enabled` int(2) NOT NULL COMMENT '封号与否',
  `type` int(2) NOT NULL COMMENT '2为预留账户版用户,1为微信',
  `updatetime` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  `beizhu` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_member_attr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `qq` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `birthyear` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `constellation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `zodiac` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `idcard` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `studentid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `grade` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `address` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `zipcode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `nationality` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `resideprovince` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `graduateschool` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `company` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `education` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `occupation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `position` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `revenue` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `affectivestatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `lookingfor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `bloodtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `height` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `weight` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `alipay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `msn` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `taobao` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `site` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `bio` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  `interest` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_member_geren` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `mid` int(10) NOT NULL,
  `qq` varchar(15) NOT NULL,
  `gender` tinyint(1) NOT NULL,
  `birthyear` smallint(6) unsigned NOT NULL,
  `birthmonth` tinyint(3) unsigned NOT NULL,
  `birthday` tinyint(3) unsigned NOT NULL,
  `constellation` varchar(10) NOT NULL,
  `zodiac` varchar(5) NOT NULL,
  `idcard` varchar(30) NOT NULL,
  `studentid` varchar(50) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(10) NOT NULL,
  `nationality` varchar(30) NOT NULL,
  `resideprovince` varchar(30) NOT NULL,
  `residecity` varchar(30) NOT NULL,
  `residedist` varchar(30) NOT NULL,
  `graduateschool` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `education` varchar(10) NOT NULL,
  `occupation` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `revenue` varchar(10) NOT NULL,
  `affectivestatus` varchar(30) NOT NULL,
  `lookingfor` varchar(255) NOT NULL,
  `bloodtype` varchar(5) NOT NULL,
  `height` varchar(5) NOT NULL,
  `weight` varchar(5) NOT NULL,
  `alipay` varchar(30) NOT NULL,
  `msn` varchar(30) NOT NULL,
  `taobao` varchar(30) NOT NULL,
  `site` varchar(30) NOT NULL,
  `bio` text NOT NULL,
  `interest` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_mendian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `brand_id` int(10) NOT NULL,
  `mdcateid` int(10) NOT NULL,
  `mendianname` varchar(200) NOT NULL DEFAULT '',
  `thumb` varchar(200) DEFAULT NULL,
  `tuce` text,
  `tel` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  `jw_addr` varchar(255) DEFAULT NULL,
  `lng` varchar(10) DEFAULT NULL,
  `lat` varchar(10) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  `description` text,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
  `updatetime` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `town` varchar(200) DEFAULT NULL,
  `street` varchar(200) DEFAULT NULL,
  `manager` varchar(200) DEFAULT NULL,
  `manager_tel` varchar(200) DEFAULT NULL,
  `beizhu` varchar(250) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_mendian_hd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `mendianid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `hdid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForRedirectUrl',
  `description` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_muban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `cateid` tinyint(3) DEFAULT '0',
  `deleted` tinyint(3) DEFAULT '0',
  `displayorder` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_muban_cate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `content` varchar(255) DEFAULT '',
  `deleted` tinyint(3) DEFAULT '0',
  `displayorder` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_perm_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rolename` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `perms` text,
  `deleted` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_status` (`status`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_perm_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `username` varchar(255) DEFAULT '',
  `password` varchar(255) DEFAULT '',
  `roleid` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `perms` text,
  `deleted` tinyint(3) DEFAULT '0',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_roleid` (`roleid`),
  KEY `idx_status` (`status`),
  KEY `idx_deleted` (`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_plugin_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `status` int(10) NOT NULL,
  `marketprice` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  `hidden` int(10) DEFAULT '0',
  `plugin` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `aname` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sharetitle` varchar(255) NOT NULL,
  `sharedesc` varchar(255) NOT NULL,
  `sharelogo` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `notice` varchar(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `copyrighturl` varchar(255) NOT NULL,
  `color` varchar(10) NOT NULL,
  `follow_logo` varchar(255) NOT NULL COMMENT '引导关注logo',
  `follow_url` text COMMENT '关注地址',
  `sms_type` int(10) NOT NULL DEFAULT '0' COMMENT '0,1为互亿无线,2为微赞',
  `sms_sign` varchar(255) NOT NULL,
  `sms_product` varchar(255) NOT NULL,
  `sms_username` varchar(255) NOT NULL,
  `sms_pwd` varchar(255) NOT NULL,
  `bd_ak` varchar(255) NOT NULL,
  `sync` int(2) NOT NULL COMMENT '同步手机名称与否',
  `sync2` int(2) NOT NULL COMMENT '同步个人信息名称与否',
  `updatetime` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  `tx_ak` varchar(255) NOT NULL,
  `geren_bg` varchar(255) NOT NULL,
  `jy_rule` text,
  `payrule` text,
  `geren_system` int(2) NOT NULL COMMENT '个人中心是否显示积分余额',
  `shenhe` int(2) NOT NULL DEFAULT '0' COMMENT '个人发布活动是否需要审核',
  `isxuni` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_synav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForRedirectUrl',
  `description` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
  `type` tinyint(1) DEFAULT '1' COMMENT '1为首页，2为活动列表页，3为用户活动列表页，4为官方活动列表页',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `sets` longtext,
  `plugins` longtext,
  `sec` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jy_tchd_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForRedirectUrl',
  `description` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('jy_tchd_attr',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_attr')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_attr',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_attr')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('jy_tchd_attr',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_attr')." ADD `name` varchar(50) NOT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('jy_tchd_attr',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_attr')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '批次ID,0为第一级';");
}
if(!pdo_fieldexists('jy_tchd_attr',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_attr')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('jy_tchd_attr',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_attr')." ADD `description` varchar(250) NOT NULL COMMENT '描述';");
}
if(!pdo_fieldexists('jy_tchd_attr',  'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_attr')." ADD `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1多选，2填空，3下拉菜单，4市区街道 , 5三级联动 , 6手机 , 7姓名 , 8头像 ,9图片';");
}
if(!pdo_fieldexists('jy_tchd_attr',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_attr')." ADD `mobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示于手机端';");
}
if(!pdo_fieldexists('jy_tchd_attr',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_attr')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('jy_tchd_banner',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_banner',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_banner',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_banner',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_banner',  'content')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `content` text;");
}
if(!pdo_fieldexists('jy_tchd_banner',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('jy_tchd_banner',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `enabled` tinyint(1) DEFAULT '0' COMMENT '0为纯图片,1为描述，2为链接跳转';");
}
if(!pdo_fieldexists('jy_tchd_banner',  'url')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_banner',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `status` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('jy_tchd_banner',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_banner',  'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_banner')." ADD `type` tinyint(1) DEFAULT '0' COMMENT '0为默认页，其他为自定义位置';");
}
if(!pdo_fieldexists('jy_tchd_brands',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_brands')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_brands',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_brands')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_brands',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_brands')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder';");
}
if(!pdo_fieldexists('jy_tchd_brands',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_brands')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_brands',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_brands')." ADD `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage';");
}
if(!pdo_fieldexists('jy_tchd_brands',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_brands')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_brands',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_brands')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists';");
}
if(!pdo_fieldexists('jy_tchd_brands',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_brands')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_brands',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_brands')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_cate',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_cate')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_cate',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_cate')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_cate',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_cate')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder';");
}
if(!pdo_fieldexists('jy_tchd_cate',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_cate')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_cate',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_cate')." ADD `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage';");
}
if(!pdo_fieldexists('jy_tchd_cate',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_cate')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_cate',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_cate')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists';");
}
if(!pdo_fieldexists('jy_tchd_code',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_code')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_code',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_code')." ADD `uid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_code',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_code')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_code',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_code')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_code',  'code')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_code')." ADD `code` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_code',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_code')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_code',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_code')." ADD `mid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `from_user` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists';");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'username')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `username` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `mobile` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'mail')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `mail` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'QQ')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `QQ` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'wechat')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `wechat` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'mendianid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `mendianid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `type` int(10) DEFAULT '0' COMMENT '0代表店员，1为店长，2为待审核';");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'password')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `password` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `description` text;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('jy_tchd_dianyuan',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_dianyuan')." ADD `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_feedback',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_feedback')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_feedback',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_feedback')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_feedback',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_feedback')." ADD `mid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_feedback',  'feedback')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_feedback')." ADD `feedback` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_feedback',  'contact')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_feedback')." ADD `contact` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_feedback',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_feedback')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_feedback',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_feedback')." ADD `enabled` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('jy_tchd_feedback',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_feedback')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `mid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'hdname')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `hdname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'hdcateid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `hdcateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动类别id';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `thumb` text;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'time')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `time` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'address')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `address` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'province')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `province` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'city')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `city` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `dist` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'town')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `town` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'street')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `street` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `lng` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `lat` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `description` text;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `starttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `endtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'num')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `num` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'pv')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `pv` int(10) NOT NULL COMMENT '浏览量';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'sc')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `sc` int(10) NOT NULL COMMENT '人气';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'isindex')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `isindex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要显示首页';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `isshow` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为显示，2为不显示，3为待审核，4为审核不通过';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'deadline')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `deadline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'tk')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `tk` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启退款';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'tk_day')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `tk_day` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'lx_mobile')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `lx_mobile` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'price')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `price` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd',  'renshu')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `renshu` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'qx')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `qx` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '选中可否取消';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'shenhe')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `shenhe` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为报名,2为文案';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'is_tj')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `is_tj` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_hd',  'realnum')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd')." ADD `realnum` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'priceid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `priceid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `status` int(2) NOT NULL COMMENT '0为已参加,1为未参加';");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'plid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `plid` bigint(11) unsigned DEFAULT NULL COMMENT 'core_paylog表的id';");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'price')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'jifen')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `jifen` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('jy_tchd_hd_cy',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy_log',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy_log')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy_log',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy_log')." ADD `hdid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy_log',  'num')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy_log')." ADD `num` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_cy_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_cy_log')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_gz',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_gz')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_hd_gz',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_gz')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_gz',  'gzid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_gz')." ADD `gzid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_gz',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_gz')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_gz',  'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_gz')." ADD `type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0商家，1用户';");
}
if(!pdo_fieldexists('jy_tchd_hd_gz',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_gz')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_price',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_price')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_hd_price',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_price')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_price',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_price')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_price',  'pricename')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_price')." ADD `pricename` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_hd_price',  'price')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_price')." ADD `price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('jy_tchd_hd_price',  'num')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_price')." ADD `num` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_hd_price',  'renshu')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_price')." ADD `renshu` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_hd_price',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_price')." ADD `status` int(2) NOT NULL DEFAULT '0' COMMENT '0为未有人报名,1为有人报名';");
}
if(!pdo_fieldexists('jy_tchd_hd_price',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_price')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_pv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_pv')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_hd_pv',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_pv')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_pv',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_pv')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_pv',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_pv')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_pv',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_pv')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_report',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_report')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_hd_report',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_report')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_report',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_report')." ADD `hdid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_report',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_report')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_report',  'report')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_report')." ADD `report` text;");
}
if(!pdo_fieldexists('jy_tchd_hd_report',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_report')." ADD `status` int(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_hd_report',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_report')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_sc',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_sc')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_hd_sc',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_sc')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_sc',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_sc')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_sc',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_sc')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_hd_sc',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_hd_sc')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_help',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_help',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_help',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help',  'content')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `content` text;");
}
if(!pdo_fieldexists('jy_tchd_help',  'cateid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `cateid` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `deleted` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `displayorder` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_help',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_help',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help')." ADD `updatetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_help_cate',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_cate')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_help_cate',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_cate')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help_cate',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_cate')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_help_cate',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_cate')." ADD `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help_cate',  'content')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_cate')." ADD `content` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_help_cate',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_cate')." ADD `deleted` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help_cate',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_cate')." ADD `displayorder` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_help_cate',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_cate')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_help_cate',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_cate')." ADD `updatetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_help_read',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_read')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_help_read',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_read')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help_read',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_read')." ADD `mid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help_read',  'helpid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_read')." ADD `helpid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help_read',  'cateid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_read')." ADD `cateid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_help_read',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_help_read')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_jiedao',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_jiedao')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_jiedao',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_jiedao')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('jy_tchd_jiedao',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_jiedao')." ADD `name` varchar(50) NOT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('jy_tchd_jiedao',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_jiedao')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '批次ID,0为第一级';");
}
if(!pdo_fieldexists('jy_tchd_jiedao',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_jiedao')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('jy_tchd_jiedao',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_jiedao')." ADD `description` text COMMENT '描述';");
}
if(!pdo_fieldexists('jy_tchd_jiedao',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_jiedao')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('jy_tchd_jiedao',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_jiedao')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否删除';");
}
if(!pdo_fieldexists('jy_tchd_jiedao',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_jiedao')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_log',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD `uid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_log',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_log',  'user')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD `user` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_log',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD `name` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_log',  'op')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD `op` text;");
}
if(!pdo_fieldexists('jy_tchd_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_log',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD `ip` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('jy_tchd_log',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_indexexists('jy_tchd_log',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('jy_tchd_log',  'idx_name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD FULLTEXT KEY `idx_name` (`name`);");
}
if(!pdo_indexexists('jy_tchd_log',  'idx_user')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_log')." ADD FULLTEXT KEY `idx_user` (`user`);");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage';");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_md_brands',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_brands')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_md_fenlei',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_fenlei')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_md_fenlei',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_fenlei')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_md_fenlei',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_fenlei')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder';");
}
if(!pdo_fieldexists('jy_tchd_md_fenlei',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_fenlei')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_md_fenlei',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_fenlei')." ADD `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage';");
}
if(!pdo_fieldexists('jy_tchd_md_fenlei',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_fenlei')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_md_fenlei',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_fenlei')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists';");
}
if(!pdo_fieldexists('jy_tchd_md_fenlei',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_fenlei')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('jy_tchd_md_fenlei',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_md_fenlei')." ADD `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_member',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `realname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `enabled` int(2) NOT NULL COMMENT '封号与否';");
}
if(!pdo_fieldexists('jy_tchd_member',  'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `type` int(2) NOT NULL COMMENT '2为预留账户版用户,1为微信';");
}
if(!pdo_fieldexists('jy_tchd_member',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member',  'beizhu')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member')." ADD `beizhu` varchar(250) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `qq` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'birthyear')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `birthyear` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'constellation')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `constellation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'zodiac')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `zodiac` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'idcard')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `idcard` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'studentid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `studentid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'grade')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `grade` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'address')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `address` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'zipcode')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `zipcode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'nationality')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `nationality` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'resideprovince')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `resideprovince` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'graduateschool')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `graduateschool` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'company')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `company` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'education')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `education` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'occupation')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `occupation` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'position')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `position` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'revenue')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `revenue` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'affectivestatus')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `affectivestatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'lookingfor')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `lookingfor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'bloodtype')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `bloodtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'height')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `height` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'weight')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `weight` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'alipay')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `alipay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'msn')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `msn` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'taobao')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `taobao` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'site')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `site` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'bio')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `bio` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_attr',  'interest')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_attr')." ADD `interest` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1为开启';");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `mid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `qq` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `gender` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'birthyear')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `birthyear` smallint(6) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'birthmonth')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `birthmonth` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'birthday')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `birthday` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'constellation')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `constellation` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'zodiac')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `zodiac` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'idcard')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `idcard` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'studentid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `studentid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'grade')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `grade` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'address')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `address` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'zipcode')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `zipcode` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'nationality')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `nationality` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'resideprovince')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `resideprovince` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'residecity')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `residecity` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'residedist')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `residedist` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'graduateschool')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `graduateschool` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'company')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `company` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'education')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `education` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'occupation')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `occupation` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'position')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `position` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'revenue')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `revenue` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'affectivestatus')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `affectivestatus` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'lookingfor')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `lookingfor` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'bloodtype')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `bloodtype` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'height')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `height` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'weight')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `weight` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'alipay')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `alipay` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'msn')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `msn` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'taobao')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `taobao` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'site')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `site` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'bio')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `bio` text NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_member_geren',  'interest')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD `interest` text NOT NULL;");
}
if(!pdo_indexexists('jy_tchd_member_geren',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD KEY `mid` (`mid`);");
}
if(!pdo_indexexists('jy_tchd_member_geren',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_member_geren')." ADD KEY `weid` (`weid`);");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'brand_id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `brand_id` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'mdcateid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `mdcateid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'mendianname')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `mendianname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `thumb` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'tuce')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `tuce` text;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `tel` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'address')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `address` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'mail')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `mail` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'jw_addr')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `jw_addr` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `lng` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `lat` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'province')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'city')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `description` text;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists';");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'town')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `town` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'street')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `street` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'manager')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `manager` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'manager_tel')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `manager_tel` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'beizhu')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `beizhu` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('jy_tchd_mendian',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian')." ADD `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_mendian_hd',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian_hd')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_mendian_hd',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian_hd')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('jy_tchd_mendian_hd',  'mendianid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian_hd')." ADD `mendianid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id';");
}
if(!pdo_fieldexists('jy_tchd_mendian_hd',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian_hd')." ADD `hdid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品id';");
}
if(!pdo_fieldexists('jy_tchd_mendian_hd',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_mendian_hd')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_menu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_menu')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_menu',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_menu')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_menu',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_menu')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder';");
}
if(!pdo_fieldexists('jy_tchd_menu',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_menu')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_menu',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_menu')." ADD `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage';");
}
if(!pdo_fieldexists('jy_tchd_menu',  'url')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_menu')." ADD `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForRedirectUrl';");
}
if(!pdo_fieldexists('jy_tchd_menu',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_menu')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_menu',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_menu')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists';");
}
if(!pdo_fieldexists('jy_tchd_muban',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_muban',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_muban',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_muban',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_muban',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_muban',  'cateid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `cateid` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_muban',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `deleted` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_muban',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `displayorder` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_muban',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_muban',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban')." ADD `updatetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_muban_cate',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban_cate')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_muban_cate',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban_cate')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_muban_cate',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban_cate')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_muban_cate',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban_cate')." ADD `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_muban_cate',  'content')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban_cate')." ADD `content` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_muban_cate',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban_cate')." ADD `deleted` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_muban_cate',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban_cate')." ADD `displayorder` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_muban_cate',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban_cate')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_muban_cate',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_muban_cate')." ADD `updatetime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_tchd_perm_role',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_role')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_perm_role',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_role')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_perm_role',  'rolename')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_role')." ADD `rolename` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_perm_role',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_role')." ADD `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_perm_role',  'perms')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_role')." ADD `perms` text;");
}
if(!pdo_fieldexists('jy_tchd_perm_role',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_role')." ADD `deleted` tinyint(3) DEFAULT '0';");
}
if(!pdo_indexexists('jy_tchd_perm_role',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_role')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('jy_tchd_perm_role',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_role')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('jy_tchd_perm_role',  'idx_deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_role')." ADD KEY `idx_deleted` (`deleted`);");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `uid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'username')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `username` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'password')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `password` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'roleid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `roleid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'perms')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `perms` text;");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `deleted` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `realname` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jy_tchd_perm_user',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD `mobile` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('jy_tchd_perm_user',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('jy_tchd_perm_user',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('jy_tchd_perm_user',  'idx_roleid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD KEY `idx_roleid` (`roleid`);");
}
if(!pdo_indexexists('jy_tchd_perm_user',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('jy_tchd_perm_user',  'idx_deleted')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_perm_user')." ADD KEY `idx_deleted` (`deleted`);");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `status` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `marketprice` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'price')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `price` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'hidden')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `hidden` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'plugin')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `plugin` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `desc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'version')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `version` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_plugin_config',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_plugin_config')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'aname')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `aname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sharetitle` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sharedesc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sharelogo')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sharelogo` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `notice` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `copyright` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'copyrighturl')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `copyrighturl` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'color')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `color` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'follow_logo')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `follow_logo` varchar(255) NOT NULL COMMENT '引导关注logo';");
}
if(!pdo_fieldexists('jy_tchd_setting',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `follow_url` text COMMENT '关注地址';");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sms_type')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sms_type` int(10) NOT NULL DEFAULT '0' COMMENT '0,1为互亿无线,2为微赞';");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sms_sign')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sms_sign` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sms_product')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sms_product` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sms_username')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sms_username` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sms_pwd')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sms_pwd` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'bd_ak')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `bd_ak` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sync')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sync` int(2) NOT NULL COMMENT '同步手机名称与否';");
}
if(!pdo_fieldexists('jy_tchd_setting',  'sync2')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `sync2` int(2) NOT NULL COMMENT '同步个人信息名称与否';");
}
if(!pdo_fieldexists('jy_tchd_setting',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'tx_ak')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `tx_ak` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'geren_bg')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `geren_bg` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'jy_rule')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `jy_rule` text;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'payrule')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `payrule` text;");
}
if(!pdo_fieldexists('jy_tchd_setting',  'geren_system')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `geren_system` int(2) NOT NULL COMMENT '个人中心是否显示积分余额';");
}
if(!pdo_fieldexists('jy_tchd_setting',  'shenhe')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `shenhe` int(2) NOT NULL DEFAULT '0' COMMENT '个人发布活动是否需要审核';");
}
if(!pdo_fieldexists('jy_tchd_setting',  'isxuni')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_setting')." ADD `isxuni` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_tchd_synav',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_synav')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_synav',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_synav')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_synav',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_synav')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder';");
}
if(!pdo_fieldexists('jy_tchd_synav',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_synav')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_synav',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_synav')." ADD `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage';");
}
if(!pdo_fieldexists('jy_tchd_synav',  'url')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_synav')." ADD `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForRedirectUrl';");
}
if(!pdo_fieldexists('jy_tchd_synav',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_synav')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_synav',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_synav')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists';");
}
if(!pdo_fieldexists('jy_tchd_synav',  'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_synav')." ADD `type` tinyint(1) DEFAULT '1' COMMENT '1为首页，2为活动列表页，3为用户活动列表页，4为官方活动列表页';");
}
if(!pdo_fieldexists('jy_tchd_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_sysset',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_sysset')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_sysset',  'sets')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_sysset')." ADD `sets` longtext;");
}
if(!pdo_fieldexists('jy_tchd_sysset',  'plugins')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_sysset')." ADD `plugins` longtext;");
}
if(!pdo_fieldexists('jy_tchd_sysset',  'sec')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_sysset')." ADD `sec` text;");
}
if(!pdo_fieldexists('jy_tchd_url',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_url')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_tchd_url',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_url')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_url',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_url')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT 'ForOrder';");
}
if(!pdo_fieldexists('jy_tchd_url',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_url')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_url',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_url')." ADD `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForLogoImage';");
}
if(!pdo_fieldexists('jy_tchd_url',  'url')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_url')." ADD `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'ForRedirectUrl';");
}
if(!pdo_fieldexists('jy_tchd_url',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_url')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_tchd_url',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_tchd_url')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0ForDeleted1ForExists';");
}

?>