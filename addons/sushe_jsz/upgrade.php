<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_sushe_jsz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `pic` varchar(120) DEFAULT '',
  `sort` tinyint(3) unsigned DEFAULT '0',
  `create_time` int(10) unsigned DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('sushe_jsz',  'id')) {
	pdo_query("ALTER TABLE ".tablename('sushe_jsz')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('sushe_jsz',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('sushe_jsz')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('sushe_jsz',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('sushe_jsz')." ADD `pic` varchar(120) DEFAULT '';");
}
if(!pdo_fieldexists('sushe_jsz',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('sushe_jsz')." ADD `sort` tinyint(3) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('sushe_jsz',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('sushe_jsz')." ADD `create_time` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('sushe_jsz',  'update_time')) {
	pdo_query("ALTER TABLE ".tablename('sushe_jsz')." ADD `update_time` int(10) unsigned DEFAULT '0';");
}

?>