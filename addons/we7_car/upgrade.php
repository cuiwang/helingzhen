<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_we7car_album` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_album_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `albumid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `ispreview` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_albumid_order` (`albumid`,`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `listorder` int(11) NOT NULL,
  `title` varchar(25) NOT NULL,
  `officialweb` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_weid_order` (`weid`,`listorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_care` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `brand_id` int(10) unsigned NOT NULL,
  `brand_cn` varchar(50) NOT NULL,
  `series_id` int(10) unsigned NOT NULL,
  `series_cn` varchar(50) NOT NULL,
  `type_id` int(10) unsigned NOT NULL,
  `type_cn` varchar(50) NOT NULL,
  `car_note` varchar(50) NOT NULL,
  `car_no` varchar(50) NOT NULL,
  `car_userName` varchar(50) NOT NULL,
  `car_mobile` varchar(15) NOT NULL,
  `car_startTime` int(10) unsigned NOT NULL,
  `car_photo` varchar(100) NOT NULL,
  `car_insurance_lastDate` int(10) unsigned NOT NULL,
  `car_insurance_lastCost` mediumint(10) unsigned NOT NULL,
  `car_care_mileage` int(11) NOT NULL,
  `car_care_lastDate` int(10) unsigned NOT NULL,
  `car_care_lastCost` mediumint(10) unsigned NOT NULL,
  `createtime` int(10) NOT NULL,
  `isshow` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_createtime` (`createtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_message_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `nickname` varchar(30) DEFAULT NULL,
  `info` varchar(200) DEFAULT NULL,
  `fid` int(11) DEFAULT '0',
  `isshow` tinyint(1) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_fid_time` (`fid`,`create_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_message_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `status` int(1) NOT NULL,
  `isshow` tinyint(1) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `iscommend` tinyint(1) NOT NULL DEFAULT '0',
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `template` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `thumb` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ims_category_id` (`category_id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_createtime` (`createtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_news_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `title` varchar(50) NOT NULL COMMENT '分类名称',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述',
  `thumb` varchar(60) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid_title` (`weid`,`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_order_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `srid` int(11) NOT NULL,
  `sfid` int(11) NOT NULL,
  `data` varchar(500) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ims_sid` (`sid`),
  KEY `ims_srid` (`srid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_order_fields` (
  `fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `value` varchar(300) NOT NULL DEFAULT '',
  PRIMARY KEY (`fid`),
  KEY `ims_sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_order_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) unsigned NOT NULL,
  `yytype` tinyint(11) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `brand` int(10) unsigned NOT NULL,
  `brand_cn` varchar(15) NOT NULL,
  `serie` int(10) unsigned NOT NULL,
  `serie_cn` varchar(15) NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `type_cn` varchar(15) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `createtime` int(10) NOT NULL,
  `note` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_sid` (`sid`),
  KEY `ims_createtime` (`createtime`),
  KEY `ims_dateline` (`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_order_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `yytype` tinyint(2) NOT NULL,
  `pertotal` tinyint(3) unsigned NOT NULL,
  `description` varchar(500) NOT NULL,
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `address` varchar(200) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `location_x` float NOT NULL,
  `location_y` float NOT NULL,
  `topbanner` varchar(150) DEFAULT NULL,
  `isshow` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `note` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`),
  KEY `ims_createtime` (`createtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_series` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `listorder` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `subtitle` varchar(20) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid_order` (`weid`,`listorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `listorder` int(11) NOT NULL,
  `kefuname` varchar(50) NOT NULL,
  `headthumb` varchar(200) NOT NULL,
  `kefutel` varchar(20) NOT NULL,
  `pre_sales` tinyint(2) NOT NULL,
  `aft_sales` tinyint(2) NOT NULL,
  `description` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `address` varchar(60) NOT NULL,
  `opentime` varchar(60) NOT NULL,
  `pre_consult` varchar(60) NOT NULL,
  `aft_consult` varchar(60) NOT NULL,
  `thumbArr` varchar(500) NOT NULL,
  `weicar_logo` varchar(200) NOT NULL,
  `shop_logo` varchar(200) NOT NULL,
  `guanhuai_thumb` varchar(200) NOT NULL,
  `typethumb` varchar(70) NOT NULL,
  `yuyue1thumb` varchar(70) NOT NULL,
  `yuyue2thumb` varchar(70) NOT NULL,
  `kefuthumb` varchar(70) NOT NULL,
  `messagethumb` varchar(70) NOT NULL,
  `carethumb` varchar(70) NOT NULL,
  `status` int(1) NOT NULL,
  `isshow` tinyint(1) NOT NULL,
  `tools` varchar(50) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_we7car_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `listorder` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `weid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `pyear` varchar(10) NOT NULL,
  `price1` varchar(50) NOT NULL,
  `price2` varchar(50) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `thumbArr` varchar(500) NOT NULL,
  `description` varchar(512) NOT NULL DEFAULT '' COMMENT '品牌描述',
  `output` varchar(10) NOT NULL,
  `gearnum` varchar(10) NOT NULL,
  `gear_box` varchar(30) NOT NULL,
  `xiangceid` int(11) NOT NULL,
  `createtime` int(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ims_weid_order` (`weid`,`listorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('we7car_album',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_album',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_album',  'type_id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `type_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_album',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `title` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_album',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `thumb` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_album',  'content')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `content` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_album',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_album',  'isview')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `isview` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('we7car_album',  'type')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_album',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('we7car_album',  'ims_weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album')." ADD KEY `ims_weid` (`weid`);");
}
if(!pdo_fieldexists('we7car_album_photo',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_album_photo',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_album_photo',  'albumid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD `albumid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_album_photo',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_album_photo',  'description')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD `description` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_album_photo',  'attachment')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD `attachment` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_album_photo',  'ispreview')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD `ispreview` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_album_photo',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_album_photo',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('we7car_album_photo',  'ims_weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD KEY `ims_weid` (`weid`);");
}
if(!pdo_indexexists('we7car_album_photo',  'ims_albumid_order')) {
	pdo_query("ALTER TABLE ".tablename('we7car_album_photo')." ADD KEY `ims_albumid_order` (`albumid`,`displayorder`);");
}
if(!pdo_fieldexists('we7car_brand',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_brand',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('we7car_brand',  'listorder')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD `listorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_brand',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD `title` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('we7car_brand',  'officialweb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD `officialweb` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('we7car_brand',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD `logo` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('we7car_brand',  'description')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD `description` text NOT NULL;");
}
if(!pdo_fieldexists('we7car_brand',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('we7car_brand',  'status')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD `status` tinyint(4) NOT NULL DEFAULT '1';");
}
if(!pdo_indexexists('we7car_brand',  'idx_weid_order')) {
	pdo_query("ALTER TABLE ".tablename('we7car_brand')." ADD KEY `idx_weid_order` (`weid`,`listorder`);");
}
if(!pdo_fieldexists('we7car_care',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_care',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'brand_id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `brand_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'brand_cn')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `brand_cn` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'series_id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `series_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'series_cn')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `series_cn` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'type_id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `type_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'type_cn')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `type_cn` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_note')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_note` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_no')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_no` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_userName')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_userName` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_mobile')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_mobile` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_startTime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_startTime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_photo')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_photo` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_insurance_lastDate')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_insurance_lastDate` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_insurance_lastCost')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_insurance_lastCost` mediumint(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_care_mileage')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_care_mileage` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_care_lastDate')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_care_lastDate` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'car_care_lastCost')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `car_care_lastCost` mediumint(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('we7car_care',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD `isshow` tinyint(1) NOT NULL;");
}
if(!pdo_indexexists('we7car_care',  'ims_weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD KEY `ims_weid` (`weid`);");
}
if(!pdo_indexexists('we7car_care',  'ims_createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_care')." ADD KEY `ims_createtime` (`createtime`);");
}
if(!pdo_fieldexists('we7car_message_list',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_message_list',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_message_list',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD `nickname` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('we7car_message_list',  'info')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD `info` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('we7car_message_list',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD `fid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_message_list',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD `isshow` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_message_list',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD `create_time` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('we7car_message_list',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD `from_user` varchar(50) DEFAULT NULL;");
}
if(!pdo_indexexists('we7car_message_list',  'ims_weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD KEY `ims_weid` (`weid`);");
}
if(!pdo_indexexists('we7car_message_list',  'ims_fid_time')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_list')." ADD KEY `ims_fid_time` (`fid`,`create_time`);");
}
if(!pdo_fieldexists('we7car_message_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_message_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_set')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_message_set',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_set')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_message_set',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_set')." ADD `thumb` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('we7car_message_set',  'status')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_set')." ADD `status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('we7car_message_set',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_set')." ADD `isshow` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('we7car_message_set',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_set')." ADD `create_time` int(10) NOT NULL;");
}
if(!pdo_indexexists('we7car_message_set',  'ims_weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_message_set')." ADD KEY `ims_weid` (`weid`);");
}
if(!pdo_fieldexists('we7car_news',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_news',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_news',  'iscommend')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `iscommend` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_news',  'ishot')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_news',  'category_id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `category_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_news',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_news',  'template')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `template` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_news',  'description')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `description` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_news',  'content')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('we7car_news',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `thumb` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图';");
}
if(!pdo_fieldexists('we7car_news',  'source')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源';");
}
if(!pdo_fieldexists('we7car_news',  'author')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `author` varchar(50) NOT NULL COMMENT '作者';");
}
if(!pdo_fieldexists('we7car_news',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('we7car_news',  'ims_category_id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD KEY `ims_category_id` (`category_id`);");
}
if(!pdo_indexexists('we7car_news',  'ims_weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD KEY `ims_weid` (`weid`);");
}
if(!pdo_indexexists('we7car_news',  'ims_createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news')." ADD KEY `ims_createtime` (`createtime`);");
}
if(!pdo_fieldexists('we7car_news_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_news_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news_category')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('we7car_news_category',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news_category')." ADD `title` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('we7car_news_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('we7car_news_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news_category')." ADD `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述';");
}
if(!pdo_fieldexists('we7car_news_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news_category')." ADD `thumb` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('we7car_news_category',  'status')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news_category')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_indexexists('we7car_news_category',  'ims_weid_title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_news_category')." ADD KEY `ims_weid_title` (`weid`,`title`);");
}
if(!pdo_fieldexists('we7car_order_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_data')." ADD `id` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_order_data',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_data')." ADD `sid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_data',  'srid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_data')." ADD `srid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_data',  'sfid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_data')." ADD `sfid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_data',  'data')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_data')." ADD `data` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_data',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_data')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('we7car_order_data',  'ims_sid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_data')." ADD KEY `ims_sid` (`sid`);");
}
if(!pdo_indexexists('we7car_order_data',  'ims_srid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_data')." ADD KEY `ims_srid` (`srid`);");
}
if(!pdo_fieldexists('we7car_order_fields',  'fid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_fields')." ADD `fid` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_order_fields',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_fields')." ADD `sid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_order_fields',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_fields')." ADD `title` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_order_fields',  'type')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_fields')." ADD `type` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('we7car_order_fields',  'value')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_fields')." ADD `value` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('we7car_order_fields',  'ims_sid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_fields')." ADD KEY `ims_sid` (`sid`);");
}
if(!pdo_fieldexists('we7car_order_list',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_order_list',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `sid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'yytype')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `yytype` tinyint(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'username')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `username` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `mobile` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'brand')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `brand` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'brand_cn')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `brand_cn` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'serie')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `serie` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'serie_cn')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `serie_cn` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'type')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `type` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'type_cn')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `type_cn` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'contact')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `contact` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'note')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `note` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_list',  'status')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD `status` tinyint(4) NOT NULL;");
}
if(!pdo_indexexists('we7car_order_list',  'ims_sid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD KEY `ims_sid` (`sid`);");
}
if(!pdo_indexexists('we7car_order_list',  'ims_createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD KEY `ims_createtime` (`createtime`);");
}
if(!pdo_indexexists('we7car_order_list',  'ims_dateline')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_list')." ADD KEY `ims_dateline` (`dateline`);");
}
if(!pdo_fieldexists('we7car_order_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_order_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('we7car_order_set',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'yytype')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `yytype` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'pertotal')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `pertotal` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'description')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `description` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'start_time')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `start_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'end_time')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `end_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'address')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `address` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `mobile` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'location_x')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `location_x` float NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'location_y')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `location_y` float NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'topbanner')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `topbanner` varchar(150) DEFAULT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `isshow` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('we7car_order_set',  'note')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `note` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_order_set',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('we7car_order_set',  'ims_weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD KEY `ims_weid` (`weid`);");
}
if(!pdo_indexexists('we7car_order_set',  'ims_createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_order_set')." ADD KEY `ims_createtime` (`createtime`);");
}
if(!pdo_fieldexists('we7car_series',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_series',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_series',  'bid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `bid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_series',  'listorder')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `listorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_series',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_series',  'subtitle')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `subtitle` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('we7car_series',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `thumb` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('we7car_series',  'description')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `description` text NOT NULL;");
}
if(!pdo_fieldexists('we7car_series',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('we7car_series',  'status')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD `status` tinyint(4) NOT NULL;");
}
if(!pdo_indexexists('we7car_series',  'ims_weid_order')) {
	pdo_query("ALTER TABLE ".tablename('we7car_series')." ADD KEY `ims_weid_order` (`weid`,`listorder`);");
}
if(!pdo_fieldexists('we7car_services',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_services',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_services',  'listorder')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `listorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_services',  'kefuname')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `kefuname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_services',  'headthumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `headthumb` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('we7car_services',  'kefutel')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `kefutel` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('we7car_services',  'pre_sales')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `pre_sales` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('we7car_services',  'aft_sales')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `aft_sales` tinyint(2) NOT NULL;");
}
if(!pdo_fieldexists('we7car_services',  'description')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `description` text NOT NULL;");
}
if(!pdo_fieldexists('we7car_services',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('we7car_services',  'status')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD `status` tinyint(4) NOT NULL;");
}
if(!pdo_indexexists('we7car_services',  'ims_weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_services')." ADD KEY `ims_weid` (`weid`);");
}
if(!pdo_fieldexists('we7car_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'description')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'address')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `address` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'opentime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `opentime` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'pre_consult')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `pre_consult` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'aft_consult')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `aft_consult` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'thumbArr')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `thumbArr` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'weicar_logo')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `weicar_logo` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'shop_logo')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `shop_logo` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'guanhuai_thumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `guanhuai_thumb` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'typethumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `typethumb` varchar(70) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'yuyue1thumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `yuyue1thumb` varchar(70) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'yuyue2thumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `yuyue2thumb` varchar(70) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'kefuthumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `kefuthumb` varchar(70) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'messagethumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `messagethumb` varchar(70) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'carethumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `carethumb` varchar(70) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'status')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `isshow` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'tools')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `tools` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_set',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD `create_time` int(10) NOT NULL;");
}
if(!pdo_indexexists('we7car_set',  'ims_weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_set')." ADD KEY `ims_weid` (`weid`);");
}
if(!pdo_fieldexists('we7car_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('we7car_type',  'listorder')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `listorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'title')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'bid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `bid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `sid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'pyear')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `pyear` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'price1')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `price1` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'price2')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `price2` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `thumb` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'thumbArr')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `thumbArr` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'description')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `description` varchar(512) NOT NULL DEFAULT '' COMMENT '品牌描述';");
}
if(!pdo_fieldexists('we7car_type',  'output')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `output` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'gearnum')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `gearnum` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'gear_box')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `gear_box` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'xiangceid')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `xiangceid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('we7car_type',  'status')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD `status` tinyint(4) NOT NULL;");
}
if(!pdo_indexexists('we7car_type',  'ims_weid_order')) {
	pdo_query("ALTER TABLE ".tablename('we7car_type')." ADD KEY `ims_weid_order` (`weid`,`listorder`);");
}

?>