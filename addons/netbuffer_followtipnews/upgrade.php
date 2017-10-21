<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_abc_replace` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `replace` varchar(32) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`) USING BTREE,
  KEY `id` (`id`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('abc_replace',  'id')) {
	pdo_query("ALTER TABLE ".tablename('abc_replace')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('abc_replace',  'replace')) {
	pdo_query("ALTER TABLE ".tablename('abc_replace')." ADD `replace` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('abc_replace',  'name')) {
	pdo_query("ALTER TABLE ".tablename('abc_replace')." ADD `name` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('abc_replace',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('abc_replace')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('abc_replace',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('abc_replace')." ADD KEY `uniacid` (`uniacid`) USING BTREE;");
}
if(!pdo_indexexists('abc_replace',  'id')) {
	pdo_query("ALTER TABLE ".tablename('abc_replace')." ADD KEY `id` (`id`) USING BTREE;");
}

?>