<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_chavin_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `typeid` int(11) DEFAULT NULL,
  `time` int(40) DEFAULT NULL,
  `lookcount` int(250) DEFAULT NULL,
  `datacount` int(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_button` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `orderlist` int(11) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `font` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_buy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `data` varchar(100) DEFAULT NULL,
  `orderlist` int(11) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `data` text CHARACTER SET utf8,
  `productid` int(11) DEFAULT NULL,
  `pic` text CHARACTER SET utf8,
  `openid` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `time` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `orderlist` int(11) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_diytype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `productid` int(11) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `orderl` varchar(255) DEFAULT '0',
  `ischeck` varchar(255) DEFAULT '1',
  `issend` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_look` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  `time` varchar(55) DEFAULT NULL,
  `nickname` varchar(150) DEFAULT NULL,
  `avatar` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `src` varchar(255) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `issend` int(11) DEFAULT '0',
  `sendmethod` varchar(30) DEFAULT NULL,
  `smsnum` varchar(255) DEFAULT NULL,
  `smsuser` varchar(255) DEFAULT NULL,
  `smspass` varchar(255) DEFAULT NULL,
  `sendopenid` varchar(255) DEFAULT NULL,
  `sendid` varchar(255) DEFAULT NULL,
  `isfollow` int(11) DEFAULT NULL,
  `postnum` int(30) DEFAULT NULL,
  `lat` varchar(100) DEFAULT NULL,
  `lng` varchar(100) DEFAULT NULL,
  `shareimg` varchar(60) DEFAULT NULL,
  `sharetitle` varchar(150) DEFAULT NULL,
  `sharedescription` varchar(200) DEFAULT NULL,
  `isviewcount` int(11) DEFAULT NULL,
  `isbutton` int(11) DEFAULT NULL,
  `isbuy` int(11) DEFAULT NULL,
  `buyspeed` int(60) DEFAULT NULL,
  `buynum` int(60) DEFAULT NULL,
  `isform` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_sendopenid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  `time` int(30) DEFAULT NULL,
  `productid` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_sonoption` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `productid` int(11) DEFAULT NULL,
  `typeid` varchar(255) NOT NULL,
  `name` varchar(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_chavin_product_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `topid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('chavin_product',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product',  'name')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product')." ADD `name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product',  'typeid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product')." ADD `typeid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product',  'time')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product')." ADD `time` int(40) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product',  'lookcount')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product')." ADD `lookcount` int(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product',  'datacount')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product')." ADD `datacount` int(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_button',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_button')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_button',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_button')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_button',  'name')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_button')." ADD `name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_button',  'orderlist')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_button')." ADD `orderlist` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_button',  'url')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_button')." ADD `url` varchar(250) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_button',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_button')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_button',  'color')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_button')." ADD `color` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_button',  'font')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_button')." ADD `font` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_buy',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_buy')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_buy',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_buy')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_buy',  'data')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_buy')." ADD `data` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_buy',  'orderlist')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_buy')." ADD `orderlist` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_buy',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_buy')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_data')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_data')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_data',  'data')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_data')." ADD `data` text CHARACTER SET utf8;");
}
if(!pdo_fieldexists('chavin_product_data',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_data')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_data',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_data')." ADD `pic` text CHARACTER SET utf8;");
}
if(!pdo_fieldexists('chavin_product_data',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_data')." ADD `openid` varchar(255) CHARACTER SET utf8 DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_data',  'time')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_data')." ADD `time` varchar(50) CHARACTER SET utf8 DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_detail',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_detail')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_detail',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_detail')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_detail',  'img')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_detail')." ADD `img` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_detail',  'orderlist')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_detail')." ADD `orderlist` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_detail',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_detail')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_diytype',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_diytype')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_diytype',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_diytype')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('chavin_product_diytype',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_diytype')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_diytype',  'type')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_diytype')." ADD `type` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('chavin_product_diytype',  'name')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_diytype')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('chavin_product_diytype',  'orderl')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_diytype')." ADD `orderl` varchar(255) DEFAULT '0';");
}
if(!pdo_fieldexists('chavin_product_diytype',  'ischeck')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_diytype')." ADD `ischeck` varchar(255) DEFAULT '1';");
}
if(!pdo_fieldexists('chavin_product_diytype',  'issend')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_diytype')." ADD `issend` varchar(255) DEFAULT '0';");
}
if(!pdo_fieldexists('chavin_product_look',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_look')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_look',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_look')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_look',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_look')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_look',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_look')." ADD `openid` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_look',  'time')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_look')." ADD `time` varchar(55) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_look',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_look')." ADD `nickname` varchar(150) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_look',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_look')." ADD `avatar` varchar(150) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_nav',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_nav')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_nav',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_nav')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('chavin_product_nav',  'image')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_nav')." ADD `image` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('chavin_product_nav',  'src')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_nav')." ADD `src` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_nav',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_nav')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'address')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `address` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `phone` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'issend')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `issend` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('chavin_product_reply',  'sendmethod')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `sendmethod` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'smsnum')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `smsnum` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'smsuser')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `smsuser` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'smspass')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `smspass` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'sendopenid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `sendopenid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'sendid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `sendid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'isfollow')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `isfollow` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'postnum')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `postnum` int(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `lat` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `lng` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'shareimg')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `shareimg` varchar(60) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `sharetitle` varchar(150) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'sharedescription')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `sharedescription` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'isviewcount')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `isviewcount` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'isbutton')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `isbutton` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'isbuy')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `isbuy` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'buyspeed')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `buyspeed` int(60) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'buynum')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `buynum` int(60) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_reply',  'isform')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_reply')." ADD `isform` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_sendopenid',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sendopenid')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_sendopenid',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sendopenid')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_sendopenid',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sendopenid')." ADD `openid` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_sendopenid',  'time')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sendopenid')." ADD `time` int(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_sendopenid',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sendopenid')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_sendopenid',  'name')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sendopenid')." ADD `name` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_sendopenid',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sendopenid')." ADD `mobile` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_sonoption',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sonoption')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_sonoption',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sonoption')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('chavin_product_sonoption',  'productid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sonoption')." ADD `productid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_sonoption',  'typeid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sonoption')." ADD `typeid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('chavin_product_sonoption',  'name')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sonoption')." ADD `name` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('chavin_product_sonoption',  'image')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_sonoption')." ADD `image` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('chavin_product_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_type')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('chavin_product_type',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_type')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_type',  'name')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_type')." ADD `name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('chavin_product_type',  'topid')) {
	pdo_query("ALTER TABLE ".tablename('chavin_product_type')." ADD `topid` int(11) DEFAULT NULL;");
}

?>