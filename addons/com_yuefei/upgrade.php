<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_com_yuefei_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `thumb` varchar(500) NOT NULL DEFAULT '',
  `position` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:首页,2:商家页',
  `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_com_yuefei_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fansid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(2000) NOT NULL DEFAULT '',
  `sharecount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享',
  `readcount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `default_read` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `default_praise` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_com_yuefei_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `nickname` varchar(50) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `username` varchar(50) DEFAULT '',
  `mobile` varchar(50) DEFAULT '',
  `address` varchar(200) DEFAULT '',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别',
  `country` varchar(50) DEFAULT '',
  `province` varchar(50) DEFAULT '',
  `city` varchar(50) DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `qrcode` varchar(500) DEFAULT '',
  `ad` varchar(500) DEFAULT '',
  `title1` varchar(500) DEFAULT '',
  `title2` varchar(500) DEFAULT '',
  `title3` varchar(500) DEFAULT '',
  `ad_url` varchar(500) DEFAULT '',
  `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间',
  `is_vip` tinyint(1) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_com_yuefei_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号id',
  `fansid` int(10) unsigned NOT NULL COMMENT '门店id',
  `from_user` varchar(50) NOT NULL,
  `transid` varchar(50) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `ordersn` varchar(30) NOT NULL COMMENT '订单号',
  `totalprice` varchar(10) NOT NULL COMMENT '总金额',
  `ispay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1,2',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1余额，2微信支付，3到付',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `address` varchar(250) NOT NULL DEFAULT '' COMMENT '地址',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为确认付款方式，2为成功',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
  `isfinish` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `vipcount` int(10) unsigned DEFAULT '0' COMMENT 'vip',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_com_yuefei_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(20) DEFAULT '' COMMENT '网站名称',
  `is_show_ad` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `is_pay` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `price` varchar(20) DEFAULT '1980',
  `help` text COMMENT '帮助教程',
  `btn_index` varchar(500) DEFAULT '月费年费' COMMENT '首页',
  `btn1` varchar(500) DEFAULT '',
  `btn_url1` varchar(500) DEFAULT '',
  `btn2` varchar(500) DEFAULT '',
  `btn_url2` varchar(500) DEFAULT '',
  `btn3` varchar(500) DEFAULT '',
  `btn_url3` varchar(500) DEFAULT '',
  `btn4` varchar(500) DEFAULT '',
  `btn_url4` varchar(500) DEFAULT '',
  `btn5` varchar(500) DEFAULT '',
  `btn_url5` varchar(500) DEFAULT '',
  `ad2_text` varchar(100) DEFAULT '',
  `ad2` varchar(500) DEFAULT '',
  `ad_url2` varchar(500) DEFAULT '',
  `mobile` varchar(500) DEFAULT '',
  `title1` varchar(500) DEFAULT '',
  `title2` varchar(500) DEFAULT '',
  `title3` varchar(500) DEFAULT '',
  `qrcode` varchar(500) DEFAULT '',
  `ad` varchar(500) DEFAULT '',
  `ad_url` varchar(500) DEFAULT '',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_image` varchar(500) DEFAULT '',
  `share_url` varchar(500) DEFAULT '',
  `copyright` varchar(1000) DEFAULT '',
  `dateline` int(10) DEFAULT '0',
  `allowweixin` tinyint(1) NOT NULL DEFAULT '0',
  `viptype` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'vip类型,1:年费,2:月费',
  `paytype` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pay类型,1:微信支付,2:转账支付',
  `weixin` varchar(50) DEFAULT '' COMMENT 'pay类型',
  `read_min` int(10) NOT NULL DEFAULT '10000' COMMENT '最小',
  `read_max` int(10) NOT NULL DEFAULT '30000' COMMENT '最大',
  `praise_min` int(10) NOT NULL DEFAULT '100' COMMENT '最小',
  `praise_max` int(10) NOT NULL DEFAULT '1000' COMMENT '最大',
  `show_qrcode` tinyint(1) NOT NULL DEFAULT '1',
  `show_mobile` tinyint(1) NOT NULL DEFAULT '1',
  `taste_vip` int(10) NOT NULL DEFAULT '0',
  `price1` varchar(20) DEFAULT '100',
  `price2` varchar(20) DEFAULT '280',
  `price3` varchar(20) DEFAULT '500',
  `price4` varchar(20) DEFAULT '800',
  `is_secondary_show` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_com_yuefei_sn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `sncode` varchar(100) DEFAULT '',
  `fansid` int(11) DEFAULT '0',
  `from_user` varchar(100) DEFAULT '' COMMENT '微信ID',
  `status` tinyint(1) DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('com_yuefei_ad',  'id')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('com_yuefei_ad',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('com_yuefei_ad',  'title')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_ad',  'url')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `url` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_ad',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `thumb` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_ad',  'position')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `position` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:首页,2:商家页';");
}
if(!pdo_fieldexists('com_yuefei_ad',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间';");
}
if(!pdo_fieldexists('com_yuefei_ad',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间';");
}
if(!pdo_fieldexists('com_yuefei_ad',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_ad',  'status')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示';");
}
if(!pdo_fieldexists('com_yuefei_ad',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_ad')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('com_yuefei_article',  'id')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('com_yuefei_article',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `fansid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('com_yuefei_article',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `from_user` varchar(50) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('com_yuefei_article',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('com_yuefei_article',  'title')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_article',  'url')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `url` varchar(2000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_article',  'sharecount')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `sharecount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享';");
}
if(!pdo_fieldexists('com_yuefei_article',  'readcount')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `readcount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读';");
}
if(!pdo_fieldexists('com_yuefei_article',  'recommend')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐';");
}
if(!pdo_fieldexists('com_yuefei_article',  'default_read')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `default_read` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量';");
}
if(!pdo_fieldexists('com_yuefei_article',  'default_praise')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `default_praise` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量';");
}
if(!pdo_fieldexists('com_yuefei_article',  'status')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('com_yuefei_article',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_article',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_article')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('com_yuefei_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('com_yuefei_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `from_user` varchar(50) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `nickname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `headimgurl` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'username')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `username` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `mobile` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `address` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `sex` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '性别';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'country')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `country` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'province')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `province` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'city')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `city` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `qrcode` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'ad')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `ad` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'title1')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `title1` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'title2')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `title2` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'title3')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `title3` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'ad_url')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `ad_url` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `starttime` int(10) NOT NULL DEFAULT '0' COMMENT '开始时间';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '结束时间';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'is_vip')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `is_vip` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `status` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('com_yuefei_fans',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_indexexists('com_yuefei_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_fans')." ADD KEY `indx_rid` (`id`);");
}
if(!pdo_fieldexists('com_yuefei_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('com_yuefei_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('com_yuefei_order',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `fansid` int(10) unsigned NOT NULL COMMENT '门店id';");
}
if(!pdo_fieldexists('com_yuefei_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('com_yuefei_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `transid` varchar(50) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
if(!pdo_fieldexists('com_yuefei_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `ordersn` varchar(30) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('com_yuefei_order',  'totalprice')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `totalprice` varchar(10) NOT NULL COMMENT '总金额';");
}
if(!pdo_fieldexists('com_yuefei_order',  'ispay')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `ispay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0,1,2';");
}
if(!pdo_fieldexists('com_yuefei_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1余额，2微信支付，3到付';");
}
if(!pdo_fieldexists('com_yuefei_order',  'username')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名';");
}
if(!pdo_fieldexists('com_yuefei_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `address` varchar(250) NOT NULL DEFAULT '' COMMENT '地址';");
}
if(!pdo_fieldexists('com_yuefei_order',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('com_yuefei_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为确认付款方式，2为成功';");
}
if(!pdo_fieldexists('com_yuefei_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists('com_yuefei_order',  'isfinish')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `isfinish` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_order',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_order',  'vipcount')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_order')." ADD `vipcount` int(10) unsigned DEFAULT '0' COMMENT 'vip';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('com_yuefei_setting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('com_yuefei_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `title` varchar(20) DEFAULT '' COMMENT '网站名称';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'is_show_ad')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `is_show_ad` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'is_pay')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `is_pay` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'price')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `price` varchar(20) DEFAULT '1980';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'help')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `help` text COMMENT '帮助教程';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn_index')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn_index` varchar(500) DEFAULT '月费年费' COMMENT '首页';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn1')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn1` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn_url1')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn_url1` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn2')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn2` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn_url2')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn_url2` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn3')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn3` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn_url3')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn_url3` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn4')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn4` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn_url4')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn_url4` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn5')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn5` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'btn_url5')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `btn_url5` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'ad2_text')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `ad2_text` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'ad2')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `ad2` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'ad_url2')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `ad_url2` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `mobile` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'title1')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `title1` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'title2')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `title2` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'title3')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `title3` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `qrcode` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'ad')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `ad` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'ad_url')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `ad_url` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `share_title` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `share_desc` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'share_image')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `share_image` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `share_url` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `copyright` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'allowweixin')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `allowweixin` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'viptype')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `viptype` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'vip类型,1:年费,2:月费';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `paytype` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'pay类型,1:微信支付,2:转账支付';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `weixin` varchar(50) DEFAULT '' COMMENT 'pay类型';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'read_min')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `read_min` int(10) NOT NULL DEFAULT '10000' COMMENT '最小';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'read_max')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `read_max` int(10) NOT NULL DEFAULT '30000' COMMENT '最大';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'praise_min')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `praise_min` int(10) NOT NULL DEFAULT '100' COMMENT '最小';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'praise_max')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `praise_max` int(10) NOT NULL DEFAULT '1000' COMMENT '最大';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'show_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `show_qrcode` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'show_mobile')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `show_mobile` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'taste_vip')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `taste_vip` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'price1')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `price1` varchar(20) DEFAULT '100';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'price2')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `price2` varchar(20) DEFAULT '280';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'price3')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `price3` varchar(20) DEFAULT '500';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'price4')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `price4` varchar(20) DEFAULT '800';");
}
if(!pdo_fieldexists('com_yuefei_setting',  'is_secondary_show')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_setting')." ADD `is_secondary_show` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('com_yuefei_sn',  'id')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_sn')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('com_yuefei_sn',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_sn')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_sn',  'sncode')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_sn')." ADD `sncode` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('com_yuefei_sn',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_sn')." ADD `fansid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_sn',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_sn')." ADD `from_user` varchar(100) DEFAULT '' COMMENT '微信ID';");
}
if(!pdo_fieldexists('com_yuefei_sn',  'status')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_sn')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('com_yuefei_sn',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_sn')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_indexexists('com_yuefei_sn',  'indx_id')) {
	pdo_query("ALTER TABLE ".tablename('com_yuefei_sn')." ADD KEY `indx_id` (`id`);");
}

?>