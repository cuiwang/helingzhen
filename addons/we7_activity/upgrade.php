<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `ac_pic` varchar(100) NOT NULL,
  `begintime` int(11) unsigned DEFAULT NULL,
  `endtime` int(11) unsigned DEFAULT NULL,
  `createtime` int(11) unsigned DEFAULT NULL,
  `countlimit` int(5) NOT NULL,
  `countvirtual` int(5) DEFAULT '0',
  `visitsCount` int(11) DEFAULT '0',
  `ppt1` varchar(100) DEFAULT NULL,
  `ppt2` varchar(100) DEFAULT NULL,
  `ppt3` varchar(100) DEFAULT NULL,
  `acdes` varchar(500) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL,
  `location_p` varchar(100) NOT NULL COMMENT '所在地区_省',
  `location_c` varchar(100) NOT NULL COMMENT '所在地区_市',
  `location_a` varchar(100) NOT NULL COMMENT '所在地区_区',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(20) DEFAULT NULL,
  `zb` varchar(50) DEFAULT NULL,
  `cb` varchar(50) DEFAULT NULL,
  `xb` varchar(50) DEFAULT NULL,
  `cjdx` varchar(50) DEFAULT NULL,
  `hoteldesc` varchar(500) DEFAULT NULL,
  `costdes` varchar(500) DEFAULT NULL,
  `isrepeat` int(1) DEFAULT '0',
  `istip` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('activity',  'id')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('activity',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `weid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'name')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `name` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'ac_pic')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `ac_pic` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('activity',  'begintime')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `begintime` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `endtime` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `createtime` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'countlimit')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `countlimit` int(5) NOT NULL;");
}
if(!pdo_fieldexists('activity',  'countvirtual')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `countvirtual` int(5) DEFAULT '0';");
}
if(!pdo_fieldexists('activity',  'visitsCount')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `visitsCount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('activity',  'ppt1')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `ppt1` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'ppt2')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `ppt2` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'ppt3')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `ppt3` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'acdes')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `acdes` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('activity',  'address')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `address` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('activity',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `location_p` varchar(100) NOT NULL COMMENT '所在地区_省';");
}
if(!pdo_fieldexists('activity',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `location_c` varchar(100) NOT NULL COMMENT '所在地区_市';");
}
if(!pdo_fieldexists('activity',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `location_a` varchar(100) NOT NULL COMMENT '所在地区_区';");
}
if(!pdo_fieldexists('activity',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000';");
}
if(!pdo_fieldexists('activity',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000';");
}
if(!pdo_fieldexists('activity',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `tel` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'email')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `email` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'zb')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `zb` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'cb')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `cb` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'xb')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `xb` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'cjdx')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `cjdx` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'hoteldesc')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `hoteldesc` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'costdes')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `costdes` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('activity',  'isrepeat')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `isrepeat` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('activity',  'istip')) {
	pdo_query("ALTER TABLE ".tablename('activity')." ADD `istip` int(1) DEFAULT '0';");
}

?>