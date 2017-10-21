<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_imeepos_opensms_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT '0',
  `status` tinyint(2) DEFAULT '0',
  `openid` varchar(64) DEFAULT '',
  `nickname` varchar(32) DEFAULT '',
  `avatar` varchar(320) DEFAULT '',
  `content` varchar(1000) DEFAULT '',
  `type` tinyint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_imeepos_opensms_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codename` varchar(32) DEFAULT '',
  `value` text,
  `uniacid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('imeepos_opensms_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('imeepos_opensms_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_log')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('imeepos_opensms_log',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_log')." ADD `create_time` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('imeepos_opensms_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_log')." ADD `status` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('imeepos_opensms_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_log')." ADD `openid` varchar(64) DEFAULT '';");
}
if(!pdo_fieldexists('imeepos_opensms_log',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_log')." ADD `nickname` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('imeepos_opensms_log',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_log')." ADD `avatar` varchar(320) DEFAULT '';");
}
if(!pdo_fieldexists('imeepos_opensms_log',  'content')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_log')." ADD `content` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('imeepos_opensms_log',  'type')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_log')." ADD `type` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('imeepos_opensms_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_setting')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('imeepos_opensms_setting',  'codename')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_setting')." ADD `codename` varchar(32) DEFAULT '';");
}
if(!pdo_fieldexists('imeepos_opensms_setting',  'value')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_setting')." ADD `value` text;");
}
if(!pdo_fieldexists('imeepos_opensms_setting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('imeepos_opensms_setting')." ADD `uniacid` int(11) DEFAULT '0';");
}

?>