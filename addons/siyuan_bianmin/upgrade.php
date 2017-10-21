<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_fenlei` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `thumb` varchar(200) DEFAULT NULL,
  `displayorder` int(10) NOT NULL DEFAULT '0',
  `parentid` int(10) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `nid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_flash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `attachment` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `tel` varchar(100) NOT NULL DEFAULT '',
  `weixin` varchar(100) NOT NULL DEFAULT '',
  `fenleiid` int(10) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `ding` int(1) NOT NULL DEFAULT '0',
  `slei` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '内容配图',
  `name` varchar(100) DEFAULT NULL,
  `gao` int(20) NOT NULL DEFAULT '120',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_siyuan_bianmin_tougao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `tel` varchar(100) NOT NULL DEFAULT '',
  `address` varchar(200) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `weixin` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('siyuan_bianmin_fenlei',  'id')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_fenlei')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('siyuan_bianmin_fenlei',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_fenlei')." ADD `weid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('siyuan_bianmin_fenlei',  'name')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_fenlei')." ADD `name` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_fenlei',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_fenlei')." ADD `thumb` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_fenlei',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_fenlei')." ADD `displayorder` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('siyuan_bianmin_fenlei',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_fenlei')." ADD `parentid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('siyuan_bianmin_fenlei',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_fenlei')." ADD `enabled` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('siyuan_bianmin_fenlei',  'nid')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_fenlei')." ADD `nid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('siyuan_bianmin_fenlei',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_fenlei')." ADD KEY `indx_uniacid` (`weid`);");
}
if(!pdo_fieldexists('siyuan_bianmin_flash',  'id')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_flash')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('siyuan_bianmin_flash',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_flash')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_flash',  'title')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_flash')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_flash',  'url')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_flash')." ADD `url` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_flash',  'attachment')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_flash')." ADD `attachment` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'id')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'title')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `tel` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `weixin` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'fenleiid')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `fenleiid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'address')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `address` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'status')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `status` int(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'ding')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `ding` int(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('siyuan_bianmin_news',  'slei')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_news')." ADD `slei` int(10) NOT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('siyuan_bianmin_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_setting')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_setting')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_setting',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_setting')." ADD `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '内容配图';");
}
if(!pdo_fieldexists('siyuan_bianmin_setting',  'name')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_setting')." ADD `name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_setting',  'gao')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_setting')." ADD `gao` int(20) NOT NULL DEFAULT '120';");
}
if(!pdo_fieldexists('siyuan_bianmin_tougao',  'id')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_tougao')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('siyuan_bianmin_tougao',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_tougao')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_tougao',  'title')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_tougao')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_tougao',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_tougao')." ADD `tel` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('siyuan_bianmin_tougao',  'address')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_tougao')." ADD `address` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('siyuan_bianmin_tougao',  'status')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_tougao')." ADD `status` int(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('siyuan_bianmin_tougao',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('siyuan_bianmin_tougao')." ADD `weixin` varchar(100) DEFAULT NULL;");
}

?>