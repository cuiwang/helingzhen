<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hx_stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `phone` varchar(15) NOT NULL DEFAULT '',
  `qq` varchar(15) NOT NULL DEFAULT '',
  `province` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `dist` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(500) NOT NULL DEFAULT '',
  `lng` varchar(10) NOT NULL DEFAULT '',
  `lat` varchar(10) NOT NULL DEFAULT '',
  `icon` varchar(200) NOT NULL,
  `industry1` varchar(10) NOT NULL DEFAULT '',
  `industry2` varchar(10) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_lat_lng` (`lng`,`lat`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('hx_stores',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_stores',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_stores',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `title` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `thumb` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'content')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `content` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `phone` varchar(15) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `qq` varchar(15) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'province')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `province` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'city')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `city` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `dist` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'address')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `address` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `lng` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `lat` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `icon` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_stores',  'industry1')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `industry1` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'industry2')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `industry2` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_stores',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD `createtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('hx_stores',  'idx_lat_lng')) {
	pdo_query("ALTER TABLE ".tablename('hx_stores')." ADD KEY `idx_lat_lng` (`lng`,`lat`);");
}

?>