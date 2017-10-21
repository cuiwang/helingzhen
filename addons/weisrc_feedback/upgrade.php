<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_weisrc_feedback_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT '0',
  `from_user` varchar(100) DEFAULT '',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0为第一级',
  `nickname` varchar(100) DEFAULT '',
  `username` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `content` varchar(200) DEFAULT '' COMMENT '回复内容',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_feedback_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT '0',
  `title` varchar(100) DEFAULT '' COMMENT '网站名称',
  `pagesize` int(10) unsigned DEFAULT '10' COMMENT '每页显示数量 默认为10',
  `topimgurl` varchar(500) DEFAULT '' COMMENT '顶部图片',
  `pagecolor` varchar(50) DEFAULT '' COMMENT '页面色调',
  `ischeck` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否需要审核',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('weisrc_feedback_feedback',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `weid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `from_user` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '0为第一级';");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `nickname` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `username` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `headimgurl` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `content` varchar(200) DEFAULT '' COMMENT '回复内容';");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_feedback_feedback',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_feedback')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_feedback_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_feedback_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `weid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_feedback_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `title` varchar(100) DEFAULT '' COMMENT '网站名称';");
}
if(!pdo_fieldexists('weisrc_feedback_setting',  'pagesize')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `pagesize` int(10) unsigned DEFAULT '10' COMMENT '每页显示数量 默认为10';");
}
if(!pdo_fieldexists('weisrc_feedback_setting',  'topimgurl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `topimgurl` varchar(500) DEFAULT '' COMMENT '顶部图片';");
}
if(!pdo_fieldexists('weisrc_feedback_setting',  'pagecolor')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `pagecolor` varchar(50) DEFAULT '' COMMENT '页面色调';");
}
if(!pdo_fieldexists('weisrc_feedback_setting',  'ischeck')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `ischeck` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weisrc_feedback_setting',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_feedback_setting')." ADD `dateline` int(10) DEFAULT '0';");
}

?>