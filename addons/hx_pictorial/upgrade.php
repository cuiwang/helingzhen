<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hx_pictorial` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `icon` varchar(100) NOT NULL DEFAULT '',
  `share` varchar(250) NOT NULL DEFAULT '',
  `open` varchar(100) NOT NULL DEFAULT '',
  `ostyle` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `music` varchar(100) NOT NULL DEFAULT '',
  `mauto` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `mloop` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `loading` varchar(100) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `isloop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isview` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `moban` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hx_pictorial_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pictorialid` int(10) unsigned NOT NULL,
  `photoid` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `item` varchar(1000) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `x` int(3) NOT NULL DEFAULT '0',
  `y` int(3) NOT NULL DEFAULT '0',
  `bigimg` varchar(1000) NOT NULL DEFAULT '',
  `animation` varchar(20) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_photoid` (`photoid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hx_pictorial_photo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pictorialid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(250) NOT NULL DEFAULT '',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  `ispreview` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_huabaoid` (`pictorialid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hx_pictorial_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `huabaoid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('hx_pictorial',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_pictorial',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_pictorial',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `title` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `icon` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial',  'share')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `share` varchar(250) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial',  'open')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `open` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial',  'ostyle')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `ostyle` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_pictorial',  'music')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `music` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial',  'mauto')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `mauto` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('hx_pictorial',  'mloop')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `mloop` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('hx_pictorial',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `thumb` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial',  'content')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `content` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial',  'loading')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `loading` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_pictorial',  'isloop')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `isloop` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_pictorial',  'isview')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `isview` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('hx_pictorial',  'type')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_pictorial',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_pictorial',  'moban')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial')." ADD `moban` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial_item',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_pictorial_item',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_pictorial_item',  'pictorialid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `pictorialid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_pictorial_item',  'photoid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `photoid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_pictorial_item',  'type')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_pictorial_item',  'item')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `item` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial_item',  'url')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `url` varchar(250) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial_item',  'x')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `x` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_pictorial_item',  'y')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `y` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_pictorial_item',  'bigimg')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `bigimg` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial_item',  'animation')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `animation` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial_item',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('hx_pictorial_item',  'idx_photoid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_item')." ADD KEY `idx_photoid` (`photoid`);");
}
if(!pdo_fieldexists('hx_pictorial_photo',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_pictorial_photo',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_pictorial_photo',  'pictorialid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD `pictorialid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_pictorial_photo',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD `title` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial_photo',  'url')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD `url` varchar(250) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial_photo',  'attachment')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD `attachment` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_pictorial_photo',  'ispreview')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD `ispreview` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_pictorial_photo',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_pictorial_photo',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('hx_pictorial_photo',  'idx_huabaoid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_photo')." ADD KEY `idx_huabaoid` (`pictorialid`);");
}
if(!pdo_fieldexists('hx_pictorial_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_pictorial_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_reply')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hx_pictorial_reply',  'huabaoid')) {
	pdo_query("ALTER TABLE ".tablename('hx_pictorial_reply')." ADD `huabaoid` int(11) NOT NULL;");
}

?>