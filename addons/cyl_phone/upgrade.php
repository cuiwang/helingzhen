<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_cyl_phone_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL,
  `followurl` varchar(1000) DEFAULT '' COMMENT '连接',
  `thumb` varchar(1000) DEFAULT '' COMMENT '底部图片',
  `title` varchar(1000) DEFAULT '' COMMENT '导航名称',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_cyl_phone_business` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `categoryid` int(11) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `title` varchar(30) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `industry_1` varchar(20) NOT NULL,
  `industry_2` varchar(20) NOT NULL,
  `weixin` varchar(25) NOT NULL,
  `net` varchar(255) NOT NULL,
  `lng` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `ewm` varchar(255) NOT NULL,
  `zy` text NOT NULL,
  `desc` text NOT NULL,
  `time` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '2',
  `click` int(25) NOT NULL,
  `recommended` int(2) NOT NULL,
  `categoryid_2` int(11) NOT NULL,
  `dpimg` varchar(255) NOT NULL,
  `yjh` varchar(100) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  `tx` varchar(255) NOT NULL,
  `dnimg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_cyl_phone_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '分类名称',
  `orderno` int(10) unsigned NOT NULL COMMENT '分类排序',
  `thumb` varchar(255) NOT NULL COMMENT '分类图标',
  `uniacid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_cyl_phone_individual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `wxh` varchar(255) NOT NULL,
  `ewm` varchar(255) NOT NULL,
  `status` int(10) NOT NULL DEFAULT '0',
  `avatar` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_cyl_phone_message` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `contentid` int(20) NOT NULL,
  `uniacid` int(20) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_cyl_phone_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `tid` varchar(255) NOT NULL,
  `uid` varchar(25) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `time` varchar(25) NOT NULL,
  `fee` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_cyl_phone_paihang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `click` int(11) NOT NULL,
  `time` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_cyl_phone_push` (
  `uniacid` int(25) NOT NULL,
  `first` varchar(255) NOT NULL,
  `keyword1` varchar(255) NOT NULL,
  `keyword2` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `push` int(2) NOT NULL DEFAULT '0',
  `kfid` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_cyl_phone_weixin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(2) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `weixin` varchar(255) NOT NULL,
  `ewm` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `yjh` varchar(255) NOT NULL,
  `recommended` int(2) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `time` varchar(25) NOT NULL,
  `sex` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('cyl_phone_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cyl_phone_adv',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_adv')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('cyl_phone_adv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_adv')." ADD `displayorder` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_adv',  'followurl')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_adv')." ADD `followurl` varchar(1000) DEFAULT '' COMMENT '连接';");
}
if(!pdo_fieldexists('cyl_phone_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_adv')." ADD `thumb` varchar(1000) DEFAULT '' COMMENT '底部图片';");
}
if(!pdo_fieldexists('cyl_phone_adv',  'title')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_adv')." ADD `title` varchar(1000) DEFAULT '' COMMENT '导航名称';");
}
if(!pdo_indexexists('cyl_phone_adv',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_adv')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('cyl_phone_business',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cyl_phone_business',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `openid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'categoryid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `categoryid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `logo` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'title')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `phone` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'industry_1')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `industry_1` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'industry_2')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `industry_2` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `weixin` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'net')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `net` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `lng` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `lat` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'address')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `address` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'ewm')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `ewm` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'zy')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `zy` text NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `desc` text NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'time')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `time` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'status')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `status` int(11) NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('cyl_phone_business',  'click')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `click` int(25) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'recommended')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `recommended` int(2) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'categoryid_2')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `categoryid_2` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'dpimg')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `dpimg` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'yjh')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `yjh` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `nickname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'tx')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `tx` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_business',  'dnimg')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_business')." ADD `dnimg` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_category')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cyl_phone_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_category')." ADD `name` varchar(30) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('cyl_phone_category',  'orderno')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_category')." ADD `orderno` int(10) unsigned NOT NULL COMMENT '分类排序';");
}
if(!pdo_fieldexists('cyl_phone_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_category')." ADD `thumb` varchar(255) NOT NULL COMMENT '分类图标';");
}
if(!pdo_fieldexists('cyl_phone_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_category')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_category',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_category')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_individual',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cyl_phone_individual',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `openid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_individual',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_individual',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `nickname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_individual',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `phone` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_individual',  'wxh')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `wxh` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_individual',  'ewm')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `ewm` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_individual',  'status')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `status` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('cyl_phone_individual',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_individual',  'address')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD `address` varchar(255) NOT NULL;");
}
if(!pdo_indexexists('cyl_phone_individual',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_individual')." ADD UNIQUE KEY `id` (`id`);");
}
if(!pdo_fieldexists('cyl_phone_message',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_message')." ADD `id` int(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cyl_phone_message',  'contentid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_message')." ADD `contentid` int(20) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_message',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_message')." ADD `uniacid` int(20) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_message',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_message')." ADD `openid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_message',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_message')." ADD `nickname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_message',  'content')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_message')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_message',  'time')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_message')." ADD `time` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_message',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_message')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cyl_phone_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_order',  'business_id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD `business_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_order',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD `tid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD `uid` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD `openid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_order',  'time')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD `time` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_order',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD `fee` int(11) NOT NULL;");
}
if(!pdo_indexexists('cyl_phone_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_order')." ADD UNIQUE KEY `id` (`id`);");
}
if(!pdo_fieldexists('cyl_phone_paihang',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_paihang')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cyl_phone_paihang',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_paihang')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_paihang',  'business_id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_paihang')." ADD `business_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_paihang',  'click')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_paihang')." ADD `click` int(11) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_paihang',  'time')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_paihang')." ADD `time` varchar(25) NOT NULL;");
}
if(!pdo_indexexists('cyl_phone_paihang',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_paihang')." ADD UNIQUE KEY `id` (`id`);");
}
if(!pdo_fieldexists('cyl_phone_push',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_push')." ADD `uniacid` int(25) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_push',  'first')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_push')." ADD `first` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_push',  'keyword1')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_push')." ADD `keyword1` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_push',  'keyword2')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_push')." ADD `keyword2` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_push',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_push')." ADD `remark` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_push',  'link')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_push')." ADD `link` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_push',  'push')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_push')." ADD `push` int(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('cyl_phone_push',  'kfid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_push')." ADD `kfid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `uniacid` int(2) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `openid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `weixin` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'ewm')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `ewm` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `nickname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'yjh')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `yjh` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'recommended')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `recommended` int(2) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'time')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `time` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('cyl_phone_weixin',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD `sex` int(2) NOT NULL;");
}
if(!pdo_indexexists('cyl_phone_weixin',  'id')) {
	pdo_query("ALTER TABLE ".tablename('cyl_phone_weixin')." ADD UNIQUE KEY `id` (`id`);");
}

?>