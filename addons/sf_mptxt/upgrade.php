<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_sf_mptxt_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `account` varchar(200) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `uploadtime` int(11) DEFAULT NULL,
  `edittime` int(11) DEFAULT NULL,
  `operator` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('sf_mptxt_list',  'id')) {
	pdo_query("ALTER TABLE ".tablename('sf_mptxt_list')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('sf_mptxt_list',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('sf_mptxt_list')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('sf_mptxt_list',  'name')) {
	pdo_query("ALTER TABLE ".tablename('sf_mptxt_list')." ADD `name` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('sf_mptxt_list',  'account')) {
	pdo_query("ALTER TABLE ".tablename('sf_mptxt_list')." ADD `account` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('sf_mptxt_list',  'title')) {
	pdo_query("ALTER TABLE ".tablename('sf_mptxt_list')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('sf_mptxt_list',  'uploadtime')) {
	pdo_query("ALTER TABLE ".tablename('sf_mptxt_list')." ADD `uploadtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('sf_mptxt_list',  'edittime')) {
	pdo_query("ALTER TABLE ".tablename('sf_mptxt_list')." ADD `edittime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('sf_mptxt_list',  'operator')) {
	pdo_query("ALTER TABLE ".tablename('sf_mptxt_list')." ADD `operator` varchar(50) DEFAULT NULL;");
}

?>