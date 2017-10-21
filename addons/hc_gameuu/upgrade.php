<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hc_gameuu_game` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `isok` tinyint(1) DEFAULT '1' COMMENT '0是不发布，1是发布',
  `num` int(10) DEFAULT '0' COMMENT '点击量',
  `img` varchar(125) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `desc` varchar(500) DEFAULT NULL COMMENT '简介',
  `category` tinyint(2) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ist` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hc_gameuu_game_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hc_gameuu_game_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL,
  `weid` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('hc_gameuu_game',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hc_gameuu_game',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game',  'isok')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `isok` tinyint(1) DEFAULT '1' COMMENT '0是不发布，1是发布';");
}
if(!pdo_fieldexists('hc_gameuu_game',  'num')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `num` int(10) DEFAULT '0' COMMENT '点击量';");
}
if(!pdo_fieldexists('hc_gameuu_game',  'img')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `img` varchar(125) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `desc` varchar(500) DEFAULT NULL COMMENT '简介';");
}
if(!pdo_fieldexists('hc_gameuu_game',  'category')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `category` tinyint(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game',  'url')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `url` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game',  'ist')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game')." ADD `ist` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hc_gameuu_game_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game_category')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game_category',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game_category')." ADD `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game_img',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game_img')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hc_gameuu_game_img',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game_img')." ADD `title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game_img',  'url')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game_img')." ADD `url` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game_img',  'img')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game_img')." ADD `img` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('hc_gameuu_game_img',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hc_gameuu_game_img')." ADD `weid` varchar(10) DEFAULT NULL;");
}

?>