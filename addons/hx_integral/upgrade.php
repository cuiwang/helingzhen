<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hx_integral_jf` (
  `telphone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `credit` int(8) NOT NULL,
  `is_show` tinyint(2) NOT NULL DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('hx_integral_jf',  'telphone')) {
	pdo_query("ALTER TABLE ".tablename('hx_integral_jf')." ADD `telphone` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('hx_integral_jf',  'password')) {
	pdo_query("ALTER TABLE ".tablename('hx_integral_jf')." ADD `password` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hx_integral_jf',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('hx_integral_jf')." ADD `credit` int(8) NOT NULL;");
}
if(!pdo_fieldexists('hx_integral_jf',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('hx_integral_jf')." ADD `is_show` tinyint(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_integral_jf',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hx_integral_jf')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_integral_jf',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_integral_jf')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}

?>