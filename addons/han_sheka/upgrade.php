<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_han_sheka_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `classid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT NULL,
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `music` varchar(200) NOT NULL DEFAULT '',
  `cardbg` varchar(200) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `tempid` int(11) NOT NULL DEFAULT '0',
  `cardid` varchar(100) NOT NULL DEFAULT '',
  `is_index` tinyint(2) NOT NULL DEFAULT '0',
  `lang` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_han_sheka_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `weid` int(11) DEFAULT '0',
  `cid` int(11) NOT NULL,
  `is_show` tinyint(2) NOT NULL DEFAULT '0',
  `follow_switch` tinyint(2) NOT NULL DEFAULT '1',
  `zdyurl` varchar(255) DEFAULT NULL,
  `f_logo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_han_sheka_zhufu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `cardfrom` varchar(200) NOT NULL,
  `cardto` varchar(200) NOT NULL,
  `cardbody` varchar(1000) NOT NULL,
  `cid` int(11) NOT NULL,
  `cardto_left` varchar(100) NOT NULL,
  `cardto_top` varchar(100) NOT NULL,
  `cardbody_width` varchar(100) NOT NULL,
  `cardbody_left` varchar(100) NOT NULL,
  `cardbody_top` varchar(100) NOT NULL,
  `cardfrom_left` varchar(100) NOT NULL,
  `cardfrom_top` varchar(100) NOT NULL,
  `panel_top` varchar(100) NOT NULL,
  `panel_left` varchar(100) NOT NULL,
  `panel_width` varchar(100) NOT NULL,
  `panel_height` varchar(100) NOT NULL,
  `panel_color` varchar(100) NOT NULL,
  `panel_bg` varchar(100) NOT NULL,
  `panel_alpha` varchar(100) NOT NULL,
  `lang` tinyint(3) NOT NULL DEFAULT '1',
  `font_size` int(3) NOT NULL DEFAULT '12',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('han_sheka_list',  'id')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('han_sheka_list',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('han_sheka_list',  'classid')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `classid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('han_sheka_list',  'title')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('han_sheka_list',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `thumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('han_sheka_list',  'description')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `description` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('han_sheka_list',  'music')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `music` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('han_sheka_list',  'cardbg')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `cardbg` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('han_sheka_list',  'params')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `params` text NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_list',  'tempid')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `tempid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('han_sheka_list',  'cardid')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `cardid` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('han_sheka_list',  'is_index')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `is_index` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('han_sheka_list',  'lang')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_list')." ADD `lang` tinyint(3) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('han_sheka_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('han_sheka_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_reply')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_reply')." ADD `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('han_sheka_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_reply')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('han_sheka_reply',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_reply')." ADD `cid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_reply',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_reply')." ADD `is_show` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('han_sheka_reply',  'follow_switch')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_reply')." ADD `follow_switch` tinyint(2) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('han_sheka_reply',  'zdyurl')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_reply')." ADD `zdyurl` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('han_sheka_reply',  'f_logo')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_reply')." ADD `f_logo` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardfrom')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardfrom` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardto')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardto` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardbody')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardbody` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardto_left')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardto_left` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardto_top')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardto_top` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardbody_width')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardbody_width` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardbody_left')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardbody_left` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardbody_top')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardbody_top` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardfrom_left')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardfrom_left` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'cardfrom_top')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `cardfrom_top` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'panel_top')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `panel_top` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'panel_left')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `panel_left` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'panel_width')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `panel_width` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'panel_height')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `panel_height` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'panel_color')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `panel_color` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'panel_bg')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `panel_bg` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'panel_alpha')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `panel_alpha` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'lang')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `lang` tinyint(3) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('han_sheka_zhufu',  'font_size')) {
	pdo_query("ALTER TABLE ".tablename('han_sheka_zhufu')." ADD `font_size` int(3) NOT NULL DEFAULT '12';");
}

?>