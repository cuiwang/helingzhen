<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_jy_signup_cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `description` varchar(250) NOT NULL COMMENT '描述',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_dianyuan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `uid` int(10) NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `username` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(20) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  `QQ` varchar(200) DEFAULT NULL,
  `wechat` varchar(200) DEFAULT NULL,
  `mendianid` int(10) DEFAULT '0',
  `type` int(10) DEFAULT '0',
  `password` varchar(200) DEFAULT NULL,
  `description` text,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_hd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `mendianid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id',
  `hdname` varchar(200) NOT NULL DEFAULT '',
  `hdcateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动类别id',
  `thumb` text,
  `renshu` varchar(200) NOT NULL DEFAULT '',
  `time` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  `deadline` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `lng` varchar(10) DEFAULT NULL,
  `lat` varchar(10) DEFAULT NULL,
  `description` text,
  `num` int(10) NOT NULL,
  `pv` int(10) NOT NULL COMMENT '浏览量',
  `sc` int(10) NOT NULL COMMENT '人气',
  `pl` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_hd_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `status` int(2) NOT NULL COMMENT '0为已参加,1为未参加',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_hd_cy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `nicheng` varchar(255) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `mail` varchar(200) NOT NULL,
  `qq` varchar(200) NOT NULL,
  `wechat` varchar(200) NOT NULL,
  `weibo` varchar(200) NOT NULL,
  `company` varchar(200) NOT NULL,
  `education` varchar(200) NOT NULL,
  `sex` varchar(10) NOT NULL,
  `age` varchar(10) NOT NULL,
  `status` int(2) NOT NULL COMMENT '0为已参加,1为未参加',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_hd_pv` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_hd_sc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_hd_ziliao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `ziliao` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否必填',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_hdcomment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `description` text,
  `num` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_hdcomment_zan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `hdid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL,
  `hdcommentid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `wechatid` int(10) NOT NULL,
  `from_user` varchar(30) NOT NULL,
  `nicheng` varchar(255) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `pwd` varchar(200) NOT NULL,
  `status` int(2) NOT NULL,
  `type` int(2) NOT NULL COMMENT '1为微信,0为账户',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_mendian` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mendianname` varchar(200) NOT NULL DEFAULT '',
  `thumb` varchar(200) DEFAULT NULL,
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
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `aname` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sharetitle` varchar(255) NOT NULL,
  `sharedesc` varchar(255) NOT NULL,
  `sharelogo` varchar(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `copyrighturl` varchar(255) NOT NULL,
  `color` varchar(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_shua` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `pv_min` int(10) NOT NULL,
  `pv_max` int(10) NOT NULL,
  `sc_min` int(10) NOT NULL,
  `sc_max` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_jy_signup_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '分类图片',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '分类跳转链接',
  `description` varchar(255) NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('jy_signup_cate',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_cate')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_cate',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_cate')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('jy_signup_cate',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_cate')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('jy_signup_cate',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_cate')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('jy_signup_cate',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_cate')." ADD `description` varchar(250) NOT NULL COMMENT '描述';");
}
if(!pdo_fieldexists('jy_signup_cate',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_cate')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `from_user` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `status` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'username')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `username` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `mobile` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'mail')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `mail` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'QQ')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `QQ` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'wechat')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `wechat` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'mendianid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `mendianid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `type` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'password')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `password` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `description` text;");
}
if(!pdo_fieldexists('jy_signup_dianyuan',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_dianyuan')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_hd',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('jy_signup_hd',  'mendianid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `mendianid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店id';");
}
if(!pdo_fieldexists('jy_signup_hd',  'hdname')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `hdname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_signup_hd',  'hdcateid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `hdcateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动类别id';");
}
if(!pdo_fieldexists('jy_signup_hd',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `thumb` text;");
}
if(!pdo_fieldexists('jy_signup_hd',  'renshu')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `renshu` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_signup_hd',  'time')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `time` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_signup_hd',  'address')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `address` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'province')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'city')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'deadline')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `deadline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `starttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `endtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'price')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('jy_signup_hd',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `lng` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `lat` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `description` text;");
}
if(!pdo_fieldexists('jy_signup_hd',  'num')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `num` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'pv')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `pv` int(10) NOT NULL COMMENT '浏览量';");
}
if(!pdo_fieldexists('jy_signup_hd',  'sc')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `sc` int(10) NOT NULL COMMENT '人气';");
}
if(!pdo_fieldexists('jy_signup_hd',  'pl')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `pl` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('jy_signup_hd_card',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_card')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_hd_card',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_card')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_card',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_card')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_card',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_card')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_card',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_card')." ADD `status` int(2) NOT NULL COMMENT '0为已参加,1为未参加';");
}
if(!pdo_fieldexists('jy_signup_hd_card',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_card')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'nicheng')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `nicheng` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'mail')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `mail` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `qq` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'wechat')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `wechat` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'weibo')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `weibo` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'company')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `company` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'education')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `education` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `sex` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'age')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `age` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `status` int(2) NOT NULL COMMENT '0为已参加,1为未参加';");
}
if(!pdo_fieldexists('jy_signup_hd_cy',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_cy')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_pv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_pv')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_hd_pv',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_pv')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_pv',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_pv')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_pv',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_pv')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_pv',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_pv')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_sc',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_sc')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_hd_sc',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_sc')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_sc',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_sc')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_sc',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_sc')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_sc',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_sc')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_ziliao',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_ziliao')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_hd_ziliao',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_ziliao')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_ziliao',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_ziliao')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_ziliao',  'ziliao')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_ziliao')." ADD `ziliao` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hd_ziliao',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_ziliao')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否必填';");
}
if(!pdo_fieldexists('jy_signup_hd_ziliao',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hd_ziliao')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_hdcomment',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment')." ADD `description` text;");
}
if(!pdo_fieldexists('jy_signup_hdcomment',  'num')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment')." ADD `num` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment_zan',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment_zan')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_hdcomment_zan',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment_zan')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment_zan',  'hdid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment_zan')." ADD `hdid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment_zan',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment_zan')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment_zan',  'hdcommentid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment_zan')." ADD `hdcommentid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_hdcomment_zan',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_hdcomment_zan')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_member',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_member',  'wechatid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `wechatid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_member',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `from_user` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_member',  'nicheng')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `nicheng` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_member',  'pwd')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `pwd` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `status` int(2) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_member',  'type')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `type` int(2) NOT NULL COMMENT '1为微信,0为账户';");
}
if(!pdo_fieldexists('jy_signup_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_member')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'mendianname')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `mendianname` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jy_signup_mendian',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `thumb` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `tel` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'address')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `address` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'mail')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `mail` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'jw_addr')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `jw_addr` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `lng` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `lat` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'province')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'city')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `description` text;");
}
if(!pdo_fieldexists('jy_signup_mendian',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_mendian')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'aname')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `aname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `sharetitle` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `sharedesc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'sharelogo')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `sharelogo` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `copyright` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'copyrighturl')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `copyrighturl` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'color')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `color` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_setting',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_setting')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_shua',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_shua')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_shua',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_shua')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_shua',  'pv_min')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_shua')." ADD `pv_min` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_shua',  'pv_max')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_shua')." ADD `pv_max` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_shua',  'sc_min')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_shua')." ADD `sc_min` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_shua',  'sc_max')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_shua')." ADD `sc_max` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_shua',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_shua')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_url',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_url')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jy_signup_url',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_url')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_url',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_url')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('jy_signup_url',  'name')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_url')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_url',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_url')." ADD `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '分类图片';");
}
if(!pdo_fieldexists('jy_signup_url',  'url')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_url')." ADD `url` varchar(255) NOT NULL DEFAULT '' COMMENT '分类跳转链接';");
}
if(!pdo_fieldexists('jy_signup_url',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_url')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jy_signup_url',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jy_signup_url')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}

?>