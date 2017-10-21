<?php
pdo_query('CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'所属帐号\',
  `cityid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'城市id\',
  `name` varchar(50) NOT NULL COMMENT \'分类名称\',
  `parentid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'上级分类ID,0为第一级\',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT \'0\' COMMENT \'排序\',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'是否开启\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'所属帐号\',
  `cityid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'城市id\',
  `name` varchar(50) NOT NULL COMMENT \'分类名称\',
  `logo` varchar(500) DEFAULT \'\' COMMENT \'商家logo\',
  `parentid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'上级分类ID,0为第一级\',
  `isfirst` tinyint(1) unsigned NOT NULL DEFAULT \'0\' COMMENT \'首页推荐\',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT \'0\' COMMENT \'排序\',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'是否开启\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_city` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'所属帐号\',
  `name` varchar(50) NOT NULL COMMENT \'城市名称\',
  `parentid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'上级分类ID,0为第一级\',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT \'0\' COMMENT \'排序\',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'是否开启\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT \'公众号ID\',
  `storeid` int(11) NOT NULL COMMENT \'商家ID\',
  `parentid` int(11) DEFAULT \'0\' COMMENT \'父级ID\',
  `from_user` varchar(100) DEFAULT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `content` varchar(600) DEFAULT NULL,
  `top` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'置顶\',
  `displayorder` int(10) unsigned NOT NULL DEFAULT \'0\',
  `status` tinyint(1) DEFAULT \'0\',
  `dateline` int(11) DEFAULT \'0\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'商家id\',
  `title` varchar(200) NOT NULL DEFAULT \'\',
  `thumb` varchar(500) NOT NULL DEFAULT \'\',
  `summary` varchar(1000) NOT NULL DEFAULT \'\',
  `description` text NOT NULL,
  `address` varchar(200) NOT NULL DEFAULT \'\',
  `start_time` int(10) NOT NULL DEFAULT \'0\' COMMENT \'开始时间\',
  `end_time` int(10) NOT NULL DEFAULT \'0\' COMMENT \'结束时间\',
  `url` varchar(200) NOT NULL DEFAULT \'\',
  `isfirst` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'是否在首页显示\',
  `top` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'置顶\',
  `mode` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'加入方式 0:后台 1:申请\',
  `checked` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'审核\',
  `displayorder` int(10) unsigned NOT NULL DEFAULT \'0\',
  `status` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'是否显示\',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `storeid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'商家id\',
  `title` varchar(200) NOT NULL DEFAULT \'\',
  `price` varchar(50) NOT NULL DEFAULT \'\',
  `thumb` varchar(500) NOT NULL DEFAULT \'\',
  `description` text NOT NULL,
  `url` varchar(200) NOT NULL DEFAULT \'\',
  `checked` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'审核\',
  `displayorder` int(10) unsigned NOT NULL DEFAULT \'0\',
  `status` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'是否显示\',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT \'所属帐号\',
  `title` varchar(100) NOT NULL DEFAULT \'\',
  `bg` varchar(500) NOT NULL DEFAULT \'\',
  `announcement` text NOT NULL COMMENT \'公告\',
  `address` varchar(200) NOT NULL DEFAULT \'\' COMMENT \'地址\',
  `tel` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'联系电话\',
  `place` varchar(200) NOT NULL DEFAULT \'\',
  `lat` decimal(18,10) NOT NULL DEFAULT \'0.0000000000\' COMMENT \'经度\',
  `lng` decimal(18,10) NOT NULL DEFAULT \'0.0000000000\' COMMENT \'纬度\',
  `location_p` varchar(100) NOT NULL DEFAULT \'\' COMMENT \'省\',
  `location_c` varchar(100) NOT NULL DEFAULT \'\' COMMENT \'市\',
  `location_a` varchar(100) NOT NULL DEFAULT \'\' COMMENT \'区\',
  `pagesize` int(10) unsigned NOT NULL DEFAULT \'5\' COMMENT \'每页显示数据量\',
  `topcolor` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'顶部字体颜色\',
  `topbgcolor` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'顶部字体颜色\',
  `announcebordercolor` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'公告边框颜色\',
  `announcebgcolor` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'公告背景颜色\',
  `announcecolor` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'公告字体颜色\',
  `storestitlecolor` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'商家名称颜色\',
  `storesstatuscolor` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'商家状态颜色\',
  `showcity` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'是否显示城市选择\',
  `settled` tinyint(1) unsigned NOT NULL DEFAULT \'0\' COMMENT \'是否开启入驻\',
  `feedback_show_enable` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'是否显示\',
  `feedback_check_enable` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'留言是否需要审核\',
  `scroll_announce` varchar(500) NOT NULL DEFAULT \'\' COMMENT \'公告\',
  `scroll_announce_speed` tinyint(2) unsigned NOT NULL DEFAULT \'6\' COMMENT \'公告滚动速度\',
  `scroll_announce_link` varchar(500) NOT NULL DEFAULT \'\' COMMENT \'公告链接\',
  `scroll_announce_enable` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'是否显示顶部公告\',
  `copyright` varchar(500) NOT NULL DEFAULT \'\' COMMENT \'底部版权\',
  `copyright_link` varchar(500) NOT NULL DEFAULT \'\' COMMENT \'底部版权链接\',
  `menuname1` varchar(50) NOT NULL DEFAULT \'\' COMMENT \'菜单1名称\',
  `menulink1` varchar(500) NOT NULL DEFAULT \'\' COMMENT \'菜单1链接\',
  `menuname2` varchar(50) NOT NULL DEFAULT \'\' COMMENT \'菜单2名称\',
  `menulink2` varchar(500) NOT NULL DEFAULT \'\' COMMENT \'菜单2链接\',
  `menuname3` varchar(50) NOT NULL DEFAULT \'\' COMMENT \'菜单3名称\',
  `menulink3` varchar(500) NOT NULL DEFAULT \'\' COMMENT \'菜单3链接\',
  `appid` varchar(300) NOT NULL DEFAULT \'\' COMMENT \'appid\',
  `secret` varchar(300) NOT NULL DEFAULT \'\' COMMENT \'secret\',
  `statistics` text NOT NULL,
  `share_title` varchar(100) NOT NULL DEFAULT \'\',
  `share_image` varchar(500) NOT NULL DEFAULT \'\',
  `share_desc` varchar(200) NOT NULL DEFAULT \'\',
  `share_cancel` varchar(200) NOT NULL DEFAULT \'\',
  `share_url` varchar(200) NOT NULL DEFAULT \'\',
  `share_num` int(10) NOT NULL DEFAULT \'0\',
  `follow_url` varchar(200) NOT NULL DEFAULT \'\',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `cityid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'城市id\',
  `title` varchar(100) NOT NULL DEFAULT \'\',
  `url` varchar(200) NOT NULL DEFAULT \'\',
  `storeid` int(10) unsigned NOT NULL DEFAULT \'0\',
  `description` varchar(1000) NOT NULL DEFAULT \'\',
  `attachment` varchar(500) NOT NULL DEFAULT \'\',
  `displayorder` int(10) unsigned NOT NULL DEFAULT \'0\',
  `isfirst` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'是否在首页显示\',
  `status` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'是否显示\',
  `dateline` int(10) unsigned NOT NULL,
  `position` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'1:首页,2:商家页\',
  `starttime` int(10) NOT NULL DEFAULT \'0\' COMMENT \'开始时间\',
  `endtime` int(10) NOT NULL DEFAULT \'0\' COMMENT \'结束时间\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_stores` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT \'0\' COMMENT \'公众号id\',
  `cityid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'城市id\',
  `pcate` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'类别id\',
  `ccate` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'类别id\',
  `title` varchar(50) NOT NULL DEFAULT \'\' COMMENT \'名称\',
  `description` text,
  `url` varchar(200) NOT NULL DEFAULT \'\' COMMENT \'微站网址\',
  `site_name` varchar(100) NOT NULL DEFAULT \'\' COMMENT \'微站按钮名称\',
  `site_url` varchar(200) NOT NULL DEFAULT \'\' COMMENT \'微站网址\',
  `shop_name` varchar(100) NOT NULL DEFAULT \'\' COMMENT \'折扣按钮名称\',
  `shop_url` varchar(400) NOT NULL DEFAULT \'\' COMMENT \'折扣链接\',
  `logo` varchar(200) NOT NULL DEFAULT \'\' COMMENT \'商家logo\',
  `qrcode` varchar(200) NOT NULL DEFAULT \'\' COMMENT \'商家logo\',
  `qrcode_url` varchar(400) NOT NULL DEFAULT \'\' COMMENT \'素材链接\',
  `qrcode_description` varchar(200) NOT NULL DEFAULT \'\' COMMENT \'二维码文字提示\',
  `services` varchar(200) NOT NULL DEFAULT \'\' COMMENT \'服务范围\',
  `username` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'联系人\',
  `tel` varchar(20) NOT NULL DEFAULT \'\' COMMENT \'联系电话\',
  `address` varchar(200) NOT NULL COMMENT \'地址\',
  `discounts` varchar(200) NOT NULL COMMENT \'会员折扣\',
  `consume` varchar(20) NOT NULL COMMENT \'人均消费\',
  `level` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'级别\',
  `place` varchar(200) NOT NULL DEFAULT \'\',
  `lat` decimal(18,10) NOT NULL DEFAULT \'0.0000000000\' COMMENT \'经度\',
  `lng` decimal(18,10) NOT NULL DEFAULT \'0.0000000000\' COMMENT \'纬度\',
  `hours` varchar(200) NOT NULL DEFAULT \'\' COMMENT \'营业时间\',
  `starttime` varchar(10) NOT NULL DEFAULT \'09:00\' COMMENT \'开始时间\',
  `endtime` varchar(10) NOT NULL DEFAULT \'18:00\' COMMENT \'结束时间\',
  `location_p` varchar(100) NOT NULL DEFAULT \'\' COMMENT \'省\',
  `location_c` varchar(100) NOT NULL DEFAULT \'\' COMMENT \'市\',
  `location_a` varchar(100) NOT NULL DEFAULT \'\' COMMENT \'区\',
  `isfirst` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'首页推荐\',
  `top` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'推荐商家，相当于置顶\',
  `from_user` varchar(50) NOT NULL DEFAULT \'\',
  `businesslicense` varchar(200) NOT NULL DEFAULT \'\' COMMENT \'营业执照\',
  `mode` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'加入方式 0:后台 1:申请入驻\',
  `checked` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'审核\',
  `status` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'是否在手机端显示\',
  `displayorder` tinyint(3) NOT NULL DEFAULT \'0\',
  `updatetime` int(10) NOT NULL DEFAULT \'0\',
  `dateline` int(10) NOT NULL DEFAULT \'0\',
  `time_enable1` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'启用营业时间1\',
  `time_enable2` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'启用营业时间2\',
  `time_enable3` tinyint(1) NOT NULL DEFAULT \'1\' COMMENT \'启用营业时间3\',
  `starttime2` varchar(10) NOT NULL DEFAULT \'09:00\' COMMENT \'开始时间\',
  `endtime2` varchar(10) NOT NULL DEFAULT \'18:00\' COMMENT \'结束时间\',
  `starttime3` varchar(10) NOT NULL DEFAULT \'09:00\' COMMENT \'开始时间\',
  `endtime3` varchar(10) NOT NULL DEFAULT \'18:00\' COMMENT \'结束时间\',
  `share_title` varchar(100) NOT NULL DEFAULT \'\',
  `share_desc` varchar(200) NOT NULL DEFAULT \'\',
  `share_cancel` varchar(200) NOT NULL DEFAULT \'\',
  `share_url` varchar(200) NOT NULL DEFAULT \'\',
  `share_num` int(10) NOT NULL DEFAULT \'0\',
  `follow_url` varchar(200) NOT NULL DEFAULT \'\',
  `isvip` tinyint(1) NOT NULL DEFAULT \'0\' COMMENT \'时间限制\',
  `vip_start` int(10) NOT NULL DEFAULT \'0\',
  `vip_end` int(10) NOT NULL DEFAULT \'0\',
  `discount` varchar(100) NOT NULL DEFAULT \'\' COMMENT \'会员折扣\',
  `aid` int(10) unsigned NOT NULL DEFAULT \'0\',
  `content` varchar(1000) NOT NULL DEFAULT \'\' COMMENT \'简介\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_businesscenter_template` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT \'0\',
  `template_name` varchar(50) NOT NULL DEFAULT \'style1\',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
');
if (pdo_tableexists('weisrc_businesscenter_area')) {
	if (!pdo_fieldexists('weisrc_businesscenter_area', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_area') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_area')) {
	if (!pdo_fieldexists('weisrc_businesscenter_area', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_area') . ' ADD `weid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'所属帐号\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_area')) {
	if (!pdo_fieldexists('weisrc_businesscenter_area', 'cityid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_area') . ' ADD `cityid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'城市id\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_area')) {
	if (!pdo_fieldexists('weisrc_businesscenter_area', 'name')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_area') . ' ADD `name` varchar(50) NOT NULL   COMMENT \'分类名称\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_area')) {
	if (!pdo_fieldexists('weisrc_businesscenter_area', 'parentid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_area') . ' ADD `parentid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'上级分类ID,0为第一级\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_area')) {
	if (!pdo_fieldexists('weisrc_businesscenter_area', 'displayorder')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_area') . ' ADD `displayorder` tinyint(3) unsigned NOT NULL  DEFAULT 0 COMMENT \'排序\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_area')) {
	if (!pdo_fieldexists('weisrc_businesscenter_area', 'enabled')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_area') . ' ADD `enabled` tinyint(1) unsigned NOT NULL  DEFAULT 1 COMMENT \'是否开启\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_category')) {
	if (!pdo_fieldexists('weisrc_businesscenter_category', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_category') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_category')) {
	if (!pdo_fieldexists('weisrc_businesscenter_category', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_category') . ' ADD `weid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'所属帐号\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_category')) {
	if (!pdo_fieldexists('weisrc_businesscenter_category', 'cityid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_category') . ' ADD `cityid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'城市id\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_category')) {
	if (!pdo_fieldexists('weisrc_businesscenter_category', 'name')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_category') . ' ADD `name` varchar(50) NOT NULL   COMMENT \'分类名称\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_category')) {
	if (!pdo_fieldexists('weisrc_businesscenter_category', 'logo')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_category') . ' ADD `logo` varchar(500)    COMMENT \'商家logo\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_category')) {
	if (!pdo_fieldexists('weisrc_businesscenter_category', 'parentid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_category') . ' ADD `parentid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'上级分类ID,0为第一级\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_category')) {
	if (!pdo_fieldexists('weisrc_businesscenter_category', 'isfirst')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_category') . ' ADD `isfirst` tinyint(1) unsigned NOT NULL  DEFAULT 0 COMMENT \'首页推荐\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_category')) {
	if (!pdo_fieldexists('weisrc_businesscenter_category', 'displayorder')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_category') . ' ADD `displayorder` tinyint(3) unsigned NOT NULL  DEFAULT 0 COMMENT \'排序\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_category')) {
	if (!pdo_fieldexists('weisrc_businesscenter_category', 'enabled')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_category') . ' ADD `enabled` tinyint(1) unsigned NOT NULL  DEFAULT 1 COMMENT \'是否开启\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_city')) {
	if (!pdo_fieldexists('weisrc_businesscenter_city', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_city') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_city')) {
	if (!pdo_fieldexists('weisrc_businesscenter_city', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_city') . ' ADD `weid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'所属帐号\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_city')) {
	if (!pdo_fieldexists('weisrc_businesscenter_city', 'name')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_city') . ' ADD `name` varchar(50) NOT NULL   COMMENT \'城市名称\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_city')) {
	if (!pdo_fieldexists('weisrc_businesscenter_city', 'parentid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_city') . ' ADD `parentid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'上级分类ID,0为第一级\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_city')) {
	if (!pdo_fieldexists('weisrc_businesscenter_city', 'displayorder')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_city') . ' ADD `displayorder` tinyint(3) unsigned NOT NULL  DEFAULT 0 COMMENT \'排序\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_city')) {
	if (!pdo_fieldexists('weisrc_businesscenter_city', 'enabled')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_city') . ' ADD `enabled` tinyint(1) unsigned NOT NULL  DEFAULT 1 COMMENT \'是否开启\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `weid` int(11) NOT NULL   COMMENT \'公众号ID\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'storeid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `storeid` int(11) NOT NULL   COMMENT \'商家ID\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'parentid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `parentid` int(11)   DEFAULT 0 COMMENT \'父级ID\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'from_user')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `from_user` varchar(100)    COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'nickname')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `nickname` varchar(30)    COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'content')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `content` varchar(600)    COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'top')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `top` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'置顶\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'displayorder')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `displayorder` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'status')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `status` tinyint(1)   DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_feedback')) {
	if (!pdo_fieldexists('weisrc_businesscenter_feedback', 'dateline')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_feedback') . ' ADD `dateline` int(11)   DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `weid` int(10) unsigned NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'storeid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `storeid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'商家id\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'title')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `title` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'thumb')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `thumb` varchar(500) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'summary')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `summary` varchar(1000) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'description')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `description` text NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'address')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `address` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'start_time')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `start_time` int(10) NOT NULL  DEFAULT 0 COMMENT \'开始时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'end_time')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `end_time` int(10) NOT NULL  DEFAULT 0 COMMENT \'结束时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `url` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'isfirst')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `isfirst` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'是否在首页显示\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'top')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `top` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'置顶\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'mode')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `mode` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'加入方式 0:后台 1:申请\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'checked')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `checked` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'审核\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'displayorder')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `displayorder` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'status')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `status` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'是否显示\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_news')) {
	if (!pdo_fieldexists('weisrc_businesscenter_news', 'dateline')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_news') . ' ADD `dateline` int(10) unsigned NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `weid` int(10) unsigned NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'storeid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `storeid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'商家id\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'title')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `title` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'price')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `price` varchar(50) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'thumb')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `thumb` varchar(500) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'description')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `description` text NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `url` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'checked')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `checked` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'审核\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'displayorder')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `displayorder` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'status')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `status` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'是否显示\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_product')) {
	if (!pdo_fieldexists('weisrc_businesscenter_product', 'dateline')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_product') . ' ADD `dateline` int(10) unsigned NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `weid` int(10) unsigned NOT NULL   COMMENT \'所属帐号\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'title')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `title` varchar(100) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'bg')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `bg` varchar(500) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'announcement')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `announcement` text NOT NULL   COMMENT \'公告\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'address')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `address` varchar(200) NOT NULL   COMMENT \'地址\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'tel')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `tel` varchar(20) NOT NULL   COMMENT \'联系电话\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'place')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `place` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'lat')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `lat` decimal(18,10) NOT NULL  DEFAULT 0.0000000000 COMMENT \'经度\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'lng')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `lng` decimal(18,10) NOT NULL  DEFAULT 0.0000000000 COMMENT \'纬度\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'location_p')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `location_p` varchar(100) NOT NULL   COMMENT \'省\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'location_c')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `location_c` varchar(100) NOT NULL   COMMENT \'市\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'location_a')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `location_a` varchar(100) NOT NULL   COMMENT \'区\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'pagesize')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `pagesize` int(10) unsigned NOT NULL  DEFAULT 5 COMMENT \'每页显示数据量\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'topcolor')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `topcolor` varchar(20) NOT NULL   COMMENT \'顶部字体颜色\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'topbgcolor')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `topbgcolor` varchar(20) NOT NULL   COMMENT \'顶部字体颜色\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'announcebordercolor')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `announcebordercolor` varchar(20) NOT NULL   COMMENT \'公告边框颜色\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'announcebgcolor')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `announcebgcolor` varchar(20) NOT NULL   COMMENT \'公告背景颜色\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'announcecolor')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `announcecolor` varchar(20) NOT NULL   COMMENT \'公告字体颜色\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'storestitlecolor')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `storestitlecolor` varchar(20) NOT NULL   COMMENT \'商家名称颜色\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'storesstatuscolor')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `storesstatuscolor` varchar(20) NOT NULL   COMMENT \'商家状态颜色\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'showcity')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `showcity` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'是否显示城市选择\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'settled')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `settled` tinyint(1) unsigned NOT NULL  DEFAULT 0 COMMENT \'是否开启入驻\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'feedback_show_enable')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `feedback_show_enable` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'是否显示\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'feedback_check_enable')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `feedback_check_enable` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'留言是否需要审核\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'scroll_announce')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `scroll_announce` varchar(500) NOT NULL   COMMENT \'公告\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'scroll_announce_speed')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `scroll_announce_speed` tinyint(2) unsigned NOT NULL  DEFAULT 6 COMMENT \'公告滚动速度\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'scroll_announce_link')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `scroll_announce_link` varchar(500) NOT NULL   COMMENT \'公告链接\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'scroll_announce_enable')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `scroll_announce_enable` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'是否显示顶部公告\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'copyright')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `copyright` varchar(500) NOT NULL   COMMENT \'底部版权\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'copyright_link')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `copyright_link` varchar(500) NOT NULL   COMMENT \'底部版权链接\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menuname1')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `menuname1` varchar(50) NOT NULL   COMMENT \'菜单1名称\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menulink1')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `menulink1` varchar(500) NOT NULL   COMMENT \'菜单1链接\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menuname2')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `menuname2` varchar(50) NOT NULL   COMMENT \'菜单2名称\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menulink2')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `menulink2` varchar(500) NOT NULL   COMMENT \'菜单2链接\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menuname3')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `menuname3` varchar(50) NOT NULL   COMMENT \'菜单3名称\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'menulink3')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `menulink3` varchar(500) NOT NULL   COMMENT \'菜单3链接\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'appid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `appid` varchar(300) NOT NULL   COMMENT \'appid\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'secret')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `secret` varchar(300) NOT NULL   COMMENT \'secret\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'statistics')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `statistics` text NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'share_title')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `share_title` varchar(100) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'share_image')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `share_image` varchar(500) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'share_desc')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `share_desc` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'share_cancel')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `share_cancel` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'share_url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `share_url` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'share_num')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `share_num` int(10) NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'follow_url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `follow_url` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_setting')) {
	if (!pdo_fieldexists('weisrc_businesscenter_setting', 'dateline')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_setting') . ' ADD `dateline` int(10) unsigned NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `weid` int(10) unsigned NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'cityid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `cityid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'城市id\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'title')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `title` varchar(100) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `url` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'storeid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `storeid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'description')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `description` varchar(1000) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'attachment')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `attachment` varchar(500) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'displayorder')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `displayorder` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'isfirst')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `isfirst` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'是否在首页显示\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'status')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `status` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'是否显示\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'dateline')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `dateline` int(10) unsigned NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'position')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `position` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'1:首页,2:商家页\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'starttime')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `starttime` int(10) NOT NULL  DEFAULT 0 COMMENT \'开始时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_slide')) {
	if (!pdo_fieldexists('weisrc_businesscenter_slide', 'endtime')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_slide') . ' ADD `endtime` int(10) NOT NULL  DEFAULT 0 COMMENT \'结束时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `id` int(10) NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `weid` int(10) NOT NULL  DEFAULT 0 COMMENT \'公众号id\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'cityid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `cityid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'城市id\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'pcate')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `pcate` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'类别id\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'ccate')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `ccate` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'类别id\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'title')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `title` varchar(50) NOT NULL   COMMENT \'名称\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'description')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `description` text    COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `url` varchar(200) NOT NULL   COMMENT \'微站网址\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'site_name')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `site_name` varchar(100) NOT NULL   COMMENT \'微站按钮名称\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'site_url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `site_url` varchar(200) NOT NULL   COMMENT \'微站网址\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'shop_name')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `shop_name` varchar(100) NOT NULL   COMMENT \'折扣按钮名称\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'shop_url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `shop_url` varchar(400) NOT NULL   COMMENT \'折扣链接\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'logo')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `logo` varchar(200) NOT NULL   COMMENT \'商家logo\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'qrcode')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `qrcode` varchar(200) NOT NULL   COMMENT \'商家logo\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'qrcode_url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `qrcode_url` varchar(400) NOT NULL   COMMENT \'素材链接\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'qrcode_description')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `qrcode_description` varchar(200) NOT NULL   COMMENT \'二维码文字提示\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'services')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `services` varchar(200) NOT NULL   COMMENT \'服务范围\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'username')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `username` varchar(20) NOT NULL   COMMENT \'联系人\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'tel')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `tel` varchar(20) NOT NULL   COMMENT \'联系电话\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'address')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `address` varchar(200) NOT NULL   COMMENT \'地址\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'discounts')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `discounts` varchar(200) NOT NULL   COMMENT \'会员折扣\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'consume')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `consume` varchar(20) NOT NULL   COMMENT \'人均消费\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'level')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `level` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'级别\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'place')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `place` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'lat')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `lat` decimal(18,10) NOT NULL  DEFAULT 0.0000000000 COMMENT \'经度\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'lng')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `lng` decimal(18,10) NOT NULL  DEFAULT 0.0000000000 COMMENT \'纬度\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'hours')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `hours` varchar(200) NOT NULL   COMMENT \'营业时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'starttime')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `starttime` varchar(10) NOT NULL  DEFAULT 09:00 COMMENT \'开始时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'endtime')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `endtime` varchar(10) NOT NULL  DEFAULT 18:00 COMMENT \'结束时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'location_p')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `location_p` varchar(100) NOT NULL   COMMENT \'省\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'location_c')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `location_c` varchar(100) NOT NULL   COMMENT \'市\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'location_a')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `location_a` varchar(100) NOT NULL   COMMENT \'区\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'isfirst')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `isfirst` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'首页推荐\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'top')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `top` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'推荐商家，相当于置顶\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'from_user')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `from_user` varchar(50) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'businesslicense')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `businesslicense` varchar(200) NOT NULL   COMMENT \'营业执照\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'mode')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `mode` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'加入方式 0:后台 1:申请入驻\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'checked')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `checked` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'审核\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'status')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `status` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'是否在手机端显示\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'displayorder')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `displayorder` tinyint(3) NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'updatetime')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `updatetime` int(10) NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'dateline')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `dateline` int(10) NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'time_enable1')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `time_enable1` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'启用营业时间1\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'time_enable2')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `time_enable2` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'启用营业时间2\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'time_enable3')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `time_enable3` tinyint(1) NOT NULL  DEFAULT 1 COMMENT \'启用营业时间3\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'starttime2')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `starttime2` varchar(10) NOT NULL  DEFAULT 09:00 COMMENT \'开始时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'endtime2')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `endtime2` varchar(10) NOT NULL  DEFAULT 18:00 COMMENT \'结束时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'starttime3')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `starttime3` varchar(10) NOT NULL  DEFAULT 09:00 COMMENT \'开始时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'endtime3')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `endtime3` varchar(10) NOT NULL  DEFAULT 18:00 COMMENT \'结束时间\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'share_title')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `share_title` varchar(100) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'share_desc')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `share_desc` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'share_cancel')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `share_cancel` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'share_url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `share_url` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'share_num')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `share_num` int(10) NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'follow_url')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `follow_url` varchar(200) NOT NULL   COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'isvip')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `isvip` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'时间限制\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'vip_start')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `vip_start` int(10) NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'vip_end')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `vip_end` int(10) NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'discount')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `discount` varchar(100) NOT NULL   COMMENT \'会员折扣\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'aid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `aid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_stores')) {
	if (!pdo_fieldexists('weisrc_businesscenter_stores', 'content')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_stores') . ' ADD `content` varchar(1000) NOT NULL   COMMENT \'简介\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_template')) {
	if (!pdo_fieldexists('weisrc_businesscenter_template', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_template') . ' ADD `id` int(10) NOT NULL auto_increment  COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_template')) {
	if (!pdo_fieldexists('weisrc_businesscenter_template', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_template') . ' ADD `weid` int(10) NOT NULL  DEFAULT 0 COMMENT \'\';');
	} 
} 
if (pdo_tableexists('weisrc_businesscenter_template')) {
	if (!pdo_fieldexists('weisrc_businesscenter_template', 'template_name')) {
		pdo_query('ALTER TABLE ' . tablename('weisrc_businesscenter_template') . ' ADD `template_name` varchar(50) NOT NULL  DEFAULT style1 COMMENT \'\';');
	} 
} 
