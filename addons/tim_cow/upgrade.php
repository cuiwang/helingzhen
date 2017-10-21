<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_tim_cowsetting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `index_intro` varchar(200) DEFAULT NULL,
  `counttime` int(5) DEFAULT NULL,
  `goods` varchar(10) DEFAULT NULL,
  `tips` varchar(200) NOT NULL,
  `rules` text,
  `share_title` varchar(100) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_cowuser` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `user_name` varchar(20) NOT NULL,
  `user_photo` varchar(200) NOT NULL,
  `user_score` int(10) NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('tim_cowsetting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_cowsetting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tim_cowsetting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_cowsetting',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `logo` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_cowsetting',  'index_intro')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `index_intro` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_cowsetting',  'counttime')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `counttime` int(5) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_cowsetting',  'goods')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `goods` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_cowsetting',  'tips')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `tips` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tim_cowsetting',  'rules')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `rules` text;");
}
if(!pdo_fieldexists('tim_cowsetting',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `share_title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_cowsetting',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `share_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_cowsetting',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowsetting')." ADD `share_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_cowuser',  'user_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowuser')." ADD `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_cowuser',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowuser')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tim_cowuser',  'user_name')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowuser')." ADD `user_name` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tim_cowuser',  'user_photo')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowuser')." ADD `user_photo` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tim_cowuser',  'user_score')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowuser')." ADD `user_score` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tim_cowuser',  'user_phone')) {
	pdo_query("ALTER TABLE ".tablename('tim_cowuser')." ADD `user_phone` varchar(20) NOT NULL;");
}

?>