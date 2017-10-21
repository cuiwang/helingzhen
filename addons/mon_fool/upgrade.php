<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_mon_fool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `follow_url` varchar(200) NOT NULL,
  `new_title` varchar(200) NOT NULL,
  `new_icon` varchar(200) NOT NULL,
  `new_content` varchar(200) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_icon` varchar(200) NOT NULL,
  `share_content` varchar(200) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('mon_fool',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_fool',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `follow_url` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `new_title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'new_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `new_icon` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'new_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `new_content` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `share_title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `share_icon` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `share_content` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_fool',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_fool')." ADD `createtime` int(10) DEFAULT '0';");
}

?>