<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_brand` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `bname` varchar(50) NOT NULL,
  `intro` varchar(1000) NOT NULL,
  `intro2` varchar(1000) NOT NULL,
  `video_name` varchar(100) DEFAULT NULL,
  `video_url` varchar(100) DEFAULT NULL,
  `createtime` int(11) unsigned DEFAULT NULL,
  `pptname` varchar(100) DEFAULT NULL,
  `ppt1` varchar(100) DEFAULT NULL,
  `ppt2` varchar(100) DEFAULT NULL,
  `ppt3` varchar(100) DEFAULT NULL,
  `pic` varchar(100) NOT NULL,
  `visitsCount` int(11) DEFAULT '0',
  `btnName` varchar(20) DEFAULT NULL,
  `btnUrl` varchar(100) DEFAULT NULL,
  `btnName2` varchar(20) DEFAULT NULL,
  `btnUrl2` varchar(100) DEFAULT NULL,
  `btnName3` varchar(20) DEFAULT NULL,
  `btnUrl3` varchar(100) DEFAULT NULL,
  `showMsg` int(1) DEFAULT '0',
  `tel` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_brand_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_brand_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `createtime` int(11) unsigned DEFAULT NULL,
  `bid` int(11) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `address` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_brand_note` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `note` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_brand_product` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned DEFAULT NULL,
  `pname` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `summary` varchar(200) NOT NULL,
  `intro` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_brand_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `bid` int(10) unsigned NOT NULL,
  `new_pic` varchar(200) NOT NULL,
  `news_content` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('brand',  'id')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('brand',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `weid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'bname')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `bname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('brand',  'intro')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `intro` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('brand',  'intro2')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `intro2` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('brand',  'video_name')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `video_name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'video_url')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `video_url` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `createtime` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'pptname')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `pptname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'ppt1')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `ppt1` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'ppt2')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `ppt2` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'ppt3')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `ppt3` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `pic` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('brand',  'visitsCount')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `visitsCount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('brand',  'btnName')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `btnName` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'btnUrl')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `btnUrl` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'btnName2')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `btnName2` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'btnUrl2')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `btnUrl2` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'btnName3')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `btnName3` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'btnUrl3')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `btnUrl3` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand',  'showMsg')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `showMsg` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('brand',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('brand')." ADD `tel` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand_image',  'id')) {
	pdo_query("ALTER TABLE ".tablename('brand_image')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('brand_image',  'bid')) {
	pdo_query("ALTER TABLE ".tablename('brand_image')." ADD `bid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('brand_image',  'title')) {
	pdo_query("ALTER TABLE ".tablename('brand_image')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('brand_image',  'url')) {
	pdo_query("ALTER TABLE ".tablename('brand_image')." ADD `url` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('brand_message',  'id')) {
	pdo_query("ALTER TABLE ".tablename('brand_message')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('brand_message',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('brand_message')." ADD `createtime` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('brand_message',  'bid')) {
	pdo_query("ALTER TABLE ".tablename('brand_message')." ADD `bid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('brand_message',  'name')) {
	pdo_query("ALTER TABLE ".tablename('brand_message')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('brand_message',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('brand_message')." ADD `tel` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('brand_message',  'content')) {
	pdo_query("ALTER TABLE ".tablename('brand_message')." ADD `content` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('brand_message',  'address')) {
	pdo_query("ALTER TABLE ".tablename('brand_message')." ADD `address` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('brand_note',  'id')) {
	pdo_query("ALTER TABLE ".tablename('brand_note')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('brand_note',  'bid')) {
	pdo_query("ALTER TABLE ".tablename('brand_note')." ADD `bid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('brand_note',  'title')) {
	pdo_query("ALTER TABLE ".tablename('brand_note')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('brand_note',  'note')) {
	pdo_query("ALTER TABLE ".tablename('brand_note')." ADD `note` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand_product',  'id')) {
	pdo_query("ALTER TABLE ".tablename('brand_product')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('brand_product',  'bid')) {
	pdo_query("ALTER TABLE ".tablename('brand_product')." ADD `bid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('brand_product',  'pname')) {
	pdo_query("ALTER TABLE ".tablename('brand_product')." ADD `pname` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('brand_product',  'image')) {
	pdo_query("ALTER TABLE ".tablename('brand_product')." ADD `image` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('brand_product',  'summary')) {
	pdo_query("ALTER TABLE ".tablename('brand_product')." ADD `summary` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('brand_product',  'intro')) {
	pdo_query("ALTER TABLE ".tablename('brand_product')." ADD `intro` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('brand_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('brand_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('brand_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('brand_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('brand_reply',  'bid')) {
	pdo_query("ALTER TABLE ".tablename('brand_reply')." ADD `bid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('brand_reply',  'new_pic')) {
	pdo_query("ALTER TABLE ".tablename('brand_reply')." ADD `new_pic` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('brand_reply',  'news_content')) {
	pdo_query("ALTER TABLE ".tablename('brand_reply')." ADD `news_content` varchar(500) NOT NULL;");
}
if(!pdo_indexexists('brand_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('brand_reply')." ADD KEY `idx_rid` (`rid`);");
}

?>