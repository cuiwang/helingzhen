<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_tb_service_fast` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `qq` varchar(255) NOT NULL,
  `weixin` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `shop` varchar(255) NOT NULL,
  `company` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tb_service_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `summery` text NOT NULL,
  `status` int(11) NOT NULL,
  `other_id` int(11) NOT NULL,
  `take_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phoneNumber` varchar(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `confirm` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tb_service_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `shareTitle` varchar(255) NOT NULL,
  `shareImage` text NOT NULL,
  `shareContent` varchar(255) NOT NULL,
  `shareLink` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tb_service_slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `slider1` varchar(255) NOT NULL,
  `slider2` varchar(255) NOT NULL,
  `slider3` varchar(255) NOT NULL,
  `slider4` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tb_service_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `phoneNumber` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `uditing` int(11) NOT NULL DEFAULT '0',
  `identify` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('tb_service_fast',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_fast')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tb_service_fast',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_fast')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_fast',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_fast')." ADD `qq` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_fast',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_fast')." ADD `weixin` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_fast',  'number')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_fast')." ADD `number` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_fast',  'shop')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_fast')." ADD `shop` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_fast',  'company')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_fast')." ADD `company` text NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tb_service_report',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'image1')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `image1` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'image2')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `image2` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'image3')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `image3` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `title` text NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'content')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'summery')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `summery` text NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'other_id')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `other_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'take_name')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `take_name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'username')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `username` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'phoneNumber')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `phoneNumber` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'address')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `address` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_report',  'confirm')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_report')." ADD `confirm` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_share')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tb_service_share',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_share')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_share',  'shareTitle')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_share')." ADD `shareTitle` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_share',  'shareImage')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_share')." ADD `shareImage` text NOT NULL;");
}
if(!pdo_fieldexists('tb_service_share',  'shareContent')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_share')." ADD `shareContent` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_share',  'shareLink')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_share')." ADD `shareLink` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_slider',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_slider')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tb_service_slider',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_slider')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_slider',  'slider1')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_slider')." ADD `slider1` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_slider',  'slider2')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_slider')." ADD `slider2` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_slider',  'slider3')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_slider')." ADD `slider3` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_slider',  'slider4')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_slider')." ADD `slider4` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_user')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tb_service_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_user')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_user',  'phoneNumber')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_user')." ADD `phoneNumber` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_user',  'username')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_user')." ADD `username` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_user',  'password')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_user')." ADD `password` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tb_service_user',  'uditing')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_user')." ADD `uditing` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('tb_service_user',  'identify')) {
	pdo_query("ALTER TABLE ".tablename('tb_service_user')." ADD `identify` int(11) NOT NULL;");
}

?>