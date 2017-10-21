<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_amouse_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(25) NOT NULL,
  `price` varchar(100) NOT NULL,
  `square_price` varchar(100) NOT NULL,
  `area` varchar(100) NOT NULL,
  `house_type` varchar(100) NOT NULL,
  `floor` varchar(100) NOT NULL,
  `orientation` varchar(100) NOT NULL,
  `type` varchar(2) NOT NULL,
  `status` varchar(2) NOT NULL,
  `recommed` int(1) NOT NULL,
  `contacts` varchar(100) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `introduction` text NOT NULL,
  `openid` varchar(25) NOT NULL,
  `createtime` int(10) NOT NULL,
  `thumb3` varchar(1000) NOT NULL,
  `thumb4` varchar(1000) NOT NULL,
  `thumb1` varchar(1000) NOT NULL,
  `thumb2` varchar(1000) NOT NULL,
  `place` varchar(1000) NOT NULL DEFAULT '',
  `lat` varchar(1000) NOT NULL DEFAULT '0.0000000000',
  `lng` varchar(1000) NOT NULL DEFAULT '0.0000000000',
  `location_p` varchar(1000) NOT NULL,
  `location_c` varchar(1000) NOT NULL,
  `location_a` varchar(1000) NOT NULL,
  `brokerage` varchar(1000) NOT NULL,
  `jjrmobile` varchar(13) DEFAULT '0',
  `broker` varchar(200) DEFAULT '',
  `isshow` int(10) DEFAULT '1' COMMENT '是否只显示经纪人信息',
  `defcity` varchar(1000) DEFAULT '中国',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_house_slide` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `url` varchar(200) NOT NULL,
  `slide` varchar(200) NOT NULL,
  `listorder` int(10) unsigned NOT NULL,
  `isshow` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('amouse_house',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_house',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'title')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `title` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'price')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `price` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'square_price')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `square_price` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'area')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `area` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'house_type')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `house_type` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'floor')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `floor` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'orientation')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `orientation` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'type')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `type` varchar(2) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `status` varchar(2) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'recommed')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `recommed` int(1) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'contacts')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `contacts` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `phone` varchar(13) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'introduction')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `introduction` text NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `openid` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'thumb3')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `thumb3` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'thumb4')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `thumb4` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'thumb1')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `thumb1` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'thumb2')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `thumb2` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'place')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `place` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_house',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `lat` varchar(1000) NOT NULL DEFAULT '0.0000000000';");
}
if(!pdo_fieldexists('amouse_house',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `lng` varchar(1000) NOT NULL DEFAULT '0.0000000000';");
}
if(!pdo_fieldexists('amouse_house',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `location_p` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `location_c` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `location_a` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'brokerage')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `brokerage` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house',  'jjrmobile')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `jjrmobile` varchar(13) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_house',  'broker')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `broker` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_house',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `isshow` int(10) DEFAULT '1' COMMENT '是否只显示经纪人信息';");
}
if(!pdo_fieldexists('amouse_house',  'defcity')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD `defcity` varchar(1000) DEFAULT '中国';");
}
if(!pdo_indexexists('amouse_house',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('amouse_house_slide',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house_slide')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_house_slide',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house_slide')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_house_slide',  'url')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house_slide')." ADD `url` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house_slide',  'slide')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house_slide')." ADD `slide` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('amouse_house_slide',  'listorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house_slide')." ADD `listorder` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_house_slide',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house_slide')." ADD `isshow` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_house_slide',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_house_slide')." ADD `createtime` int(10) unsigned NOT NULL;");
}

?>