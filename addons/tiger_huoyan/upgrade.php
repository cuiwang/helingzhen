<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_tiger_huoyan_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) DEFAULT NULL,
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(200) NOT NULL COMMENT '活动描述',
  `gzurl` varchar(255) NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('tiger_huoyan_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tiger_huoyan_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tiger_huoyan_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tiger_huoyan_reply')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('tiger_huoyan_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tiger_huoyan_reply')." ADD `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('tiger_huoyan_reply',  'picture')) {
	pdo_query("ALTER TABLE ".tablename('tiger_huoyan_reply')." ADD `picture` varchar(100) NOT NULL COMMENT '活动图片';");
}
if(!pdo_fieldexists('tiger_huoyan_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('tiger_huoyan_reply')." ADD `description` varchar(200) NOT NULL COMMENT '活动描述';");
}
if(!pdo_fieldexists('tiger_huoyan_reply',  'gzurl')) {
	pdo_query("ALTER TABLE ".tablename('tiger_huoyan_reply')." ADD `gzurl` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tiger_huoyan_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tiger_huoyan_reply')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_indexexists('tiger_huoyan_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('tiger_huoyan_reply')." ADD KEY `idx_rid` (`rid`);");
}

?>