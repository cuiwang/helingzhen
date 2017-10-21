<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_mbwish_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL COMMENT '祝福创建者',
  `wish` varchar(500) NOT NULL COMMENT '祝福语录音',
  `views` int(10) unsigned NOT NULL COMMENT '浏览次数',
  `timecreated` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('mbwish_records',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mbwish_records')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mbwish_records',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mbwish_records')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('mbwish_records',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mbwish_records')." ADD `openid` varchar(50) NOT NULL COMMENT '祝福创建者';");
}
if(!pdo_fieldexists('mbwish_records',  'wish')) {
	pdo_query("ALTER TABLE ".tablename('mbwish_records')." ADD `wish` varchar(500) NOT NULL COMMENT '祝福语录音';");
}
if(!pdo_fieldexists('mbwish_records',  'views')) {
	pdo_query("ALTER TABLE ".tablename('mbwish_records')." ADD `views` int(10) unsigned NOT NULL COMMENT '浏览次数';");
}
if(!pdo_fieldexists('mbwish_records',  'timecreated')) {
	pdo_query("ALTER TABLE ".tablename('mbwish_records')." ADD `timecreated` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_indexexists('mbwish_records',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('mbwish_records')." ADD KEY `uniacid` (`uniacid`);");
}

?>