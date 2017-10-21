<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_ss_wjgl_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('ss_wjgl_file',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ss_wjgl_file')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ss_wjgl_file',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ss_wjgl_file')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('ss_wjgl_file',  'name')) {
	pdo_query("ALTER TABLE ".tablename('ss_wjgl_file')." ADD `name` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('ss_wjgl_file',  'path')) {
	pdo_query("ALTER TABLE ".tablename('ss_wjgl_file')." ADD `path` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('ss_wjgl_file',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ss_wjgl_file')." ADD `createtime` int(11) DEFAULT NULL;");
}

?>