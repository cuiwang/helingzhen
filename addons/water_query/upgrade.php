<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_water_query_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `info` varchar(500) NOT NULL,
  `infophoto` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('water_query_info',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_query_info')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_query_info',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_query_info')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('water_query_info',  'keyword')) {
	pdo_query("ALTER TABLE ".tablename('water_query_info')." ADD `keyword` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('water_query_info',  'info')) {
	pdo_query("ALTER TABLE ".tablename('water_query_info')." ADD `info` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('water_query_info',  'infophoto')) {
	pdo_query("ALTER TABLE ".tablename('water_query_info')." ADD `infophoto` varchar(300) NOT NULL;");
}

?>