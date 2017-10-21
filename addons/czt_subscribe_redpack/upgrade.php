<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_czt_subscribe_redpack_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `fee` varchar(10) NOT NULL,
  `log` varchar(500) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `create_t` int(10) unsigned NOT NULL,
  `success_t` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL,
  `success_time` int(10) unsigned NOT NULL,
  `oauth_openid` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`),
  KEY `log` (`log`(333)),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'id')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `openid` varchar(40) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `fee` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'log')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `log` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'status')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'create_t')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `create_t` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'success_t')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `success_t` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `create_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'success_time')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `success_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_subscribe_redpack_records',  'oauth_openid')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD `oauth_openid` varchar(40) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('czt_subscribe_redpack_records',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD KEY `openid` (`openid`);");
}
if(!pdo_indexexists('czt_subscribe_redpack_records',  'log')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD KEY `log` (`log`(333));");
}
if(!pdo_indexexists('czt_subscribe_redpack_records',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('czt_subscribe_redpack_records',  'status')) {
	pdo_query("ALTER TABLE ".tablename('czt_subscribe_redpack_records')." ADD KEY `status` (`status`);");
}

?>