<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_jing_ns_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `loading` varchar(200) NOT NULL,
  `audio` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `link1` varchar(255) NOT NULL,
  `link2` varchar(255) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('jing_ns_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jing_ns_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'loading')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `loading` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'audio')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `audio` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `logo` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'link1')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `link1` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'link2')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `link2` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('jing_ns_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jing_ns_reply')." ADD `createtime` int(10) unsigned NOT NULL;");
}

?>