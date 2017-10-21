<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_aaidybnt_testapi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `content` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('aaidybnt_testapi',  'id')) {
	pdo_query("ALTER TABLE ".tablename('aaidybnt_testapi')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('aaidybnt_testapi',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('aaidybnt_testapi')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('aaidybnt_testapi',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('aaidybnt_testapi')." ADD `acid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('aaidybnt_testapi',  'num')) {
	pdo_query("ALTER TABLE ".tablename('aaidybnt_testapi')." ADD `num` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('aaidybnt_testapi',  'content')) {
	pdo_query("ALTER TABLE ".tablename('aaidybnt_testapi')." ADD `content` varchar(1000) NOT NULL;");
}
if(!pdo_indexexists('aaidybnt_testapi',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('aaidybnt_testapi')." ADD KEY `rid` (`rid`);");
}

?>