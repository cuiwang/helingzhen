<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_site_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `goodnum` int(10) unsigned NOT NULL,
  `multipage` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `multiid` (`multiid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('site_page',  'id')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('site_page',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'multiid')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `multiid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'title')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'description')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'params')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `params` longtext NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'html')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `html` longtext NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'type')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `type` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'status')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'goodnum')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `goodnum` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('site_page',  'multipage')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD `multipage` longtext NOT NULL;");
}
if(!pdo_indexexists('site_page',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('site_page',  'multiid')) {
	pdo_query("ALTER TABLE ".tablename('site_page')." ADD KEY `multiid` (`multiid`);");
}

?>