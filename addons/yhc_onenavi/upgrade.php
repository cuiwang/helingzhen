<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_yhc_onenavi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `lat` varchar(20) DEFAULT NULL COMMENT '坐标经度',
  `lng` varchar(20) DEFAULT NULL COMMENT '坐标维度',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('yhc_onenavi',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yhc_onenavi')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yhc_onenavi',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yhc_onenavi')." ADD `rid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yhc_onenavi',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yhc_onenavi')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yhc_onenavi',  'title')) {
	pdo_query("ALTER TABLE ".tablename('yhc_onenavi')." ADD `title` varchar(100) DEFAULT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('yhc_onenavi',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('yhc_onenavi')." ADD `lat` varchar(20) DEFAULT NULL COMMENT '坐标经度';");
}
if(!pdo_fieldexists('yhc_onenavi',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('yhc_onenavi')." ADD `lng` varchar(20) DEFAULT NULL COMMENT '坐标维度';");
}
if(!pdo_indexexists('yhc_onenavi',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yhc_onenavi')." ADD KEY `rid` (`rid`);");
}

?>