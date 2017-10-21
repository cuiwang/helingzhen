<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_dayu_zhinv_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_dayu_zhinv_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `copyright` varchar(300) NOT NULL,
  `share_txt` varchar(500) DEFAULT '',
  `follow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '关注',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('dayu_zhinv_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('dayu_zhinv_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_reply')." ADD `rid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('dayu_zhinv_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_reply')." ADD `title` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('dayu_zhinv_reply',  'cover')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_reply')." ADD `cover` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('dayu_zhinv_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_reply')." ADD `description` text;");
}
if(!pdo_fieldexists('dayu_zhinv_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_set')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('dayu_zhinv_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_set')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('dayu_zhinv_set',  'title')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_set')." ADD `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('dayu_zhinv_set',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_set')." ADD `share_title` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('dayu_zhinv_set',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_set')." ADD `share_desc` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('dayu_zhinv_set',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_set')." ADD `share_url` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('dayu_zhinv_set',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_set')." ADD `copyright` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('dayu_zhinv_set',  'share_txt')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_set')." ADD `share_txt` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('dayu_zhinv_set',  'follow')) {
	pdo_query("ALTER TABLE ".tablename('dayu_zhinv_set')." ADD `follow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '关注';");
}

?>