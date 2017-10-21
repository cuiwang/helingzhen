<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_thinkidea_phonebook_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '行业名称',
  `parent_id` int(11) NOT NULL COMMENT '父id',
  `display` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `isshow` smallint(1) DEFAULT '1' COMMENT '是否显示',
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='分类表';
CREATE TABLE IF NOT EXISTS `ims_thinkidea_phonebook_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `zone` smallint(6) NOT NULL,
  `category` smallint(6) NOT NULL,
  `address` varchar(250) NOT NULL,
  `isauth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否认证',
  `coordinate` varchar(50) NOT NULL COMMENT '坐标',
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='电话本内容';
CREATE TABLE IF NOT EXISTS `ims_thinkidea_phonebook_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` smallint(6) NOT NULL,
  `weid` smallint(6) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `avatar` varchar(250) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `dateline` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_thinkidea_phonebook_zone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '名称',
  `parent_id` int(11) NOT NULL COMMENT '父id',
  `display` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `isshow` smallint(1) DEFAULT '1' COMMENT '是否显示',
  `dateline` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='区域表';
";
pdo_run($sql);
if(!pdo_fieldexists('thinkidea_phonebook_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_category')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('thinkidea_phonebook_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_category')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_category')." ADD `name` varchar(50) NOT NULL COMMENT '行业名称';");
}
if(!pdo_fieldexists('thinkidea_phonebook_category',  'parent_id')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_category')." ADD `parent_id` int(11) NOT NULL COMMENT '父id';");
}
if(!pdo_fieldexists('thinkidea_phonebook_category',  'display')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_category')." ADD `display` int(11) NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('thinkidea_phonebook_category',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_category')." ADD `isshow` smallint(1) DEFAULT '1' COMMENT '是否显示';");
}
if(!pdo_fieldexists('thinkidea_phonebook_category',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_category')." ADD `dateline` int(11) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'id')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `weid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'name')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `name` varchar(250) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `mobile` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'zone')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `zone` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'category')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `category` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'address')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `address` varchar(250) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'isauth')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `isauth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否认证';");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'coordinate')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `coordinate` varchar(50) NOT NULL COMMENT '坐标';");
}
if(!pdo_fieldexists('thinkidea_phonebook_info',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD `dateline` int(11) NOT NULL;");
}
if(!pdo_indexexists('thinkidea_phonebook_info',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_info')." ADD KEY `dateline` (`dateline`);");
}
if(!pdo_fieldexists('thinkidea_phonebook_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('thinkidea_phonebook_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_reply')." ADD `rid` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_reply')." ADD `weid` smallint(6) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_reply')." ADD `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_reply',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_reply')." ADD `avatar` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_reply')." ADD `description` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_reply',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_reply')." ADD `dateline` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_zone',  'id')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_zone')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('thinkidea_phonebook_zone',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_zone')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('thinkidea_phonebook_zone',  'name')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_zone')." ADD `name` varchar(50) NOT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('thinkidea_phonebook_zone',  'parent_id')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_zone')." ADD `parent_id` int(11) NOT NULL COMMENT '父id';");
}
if(!pdo_fieldexists('thinkidea_phonebook_zone',  'display')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_zone')." ADD `display` int(11) NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('thinkidea_phonebook_zone',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_zone')." ADD `isshow` smallint(1) DEFAULT '1' COMMENT '是否显示';");
}
if(!pdo_fieldexists('thinkidea_phonebook_zone',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('thinkidea_phonebook_zone')." ADD `dateline` int(11) NOT NULL;");
}

?>