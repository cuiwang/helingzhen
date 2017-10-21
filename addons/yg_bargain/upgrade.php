<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_yg_bargain_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `behelped` varchar(255) DEFAULT NULL,
  `shopid` int(11) DEFAULT NULL,
  `helpopenid` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `price` float(10,2) DEFAULT '0.00',
  `cutprice` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yg_bargain_info` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `nickname` varchar(1000) DEFAULT NULL,
  `headimgurl` varchar(1000) DEFAULT NULL,
  `logoopenid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yg_bargain_oauth` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `appid` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `secret` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `ims_yg_bargain_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `sharepic` varchar(500) NOT NULL,
  `sharedesc` varchar(255) NOT NULL,
  `sharetitle` varchar(20) NOT NULL,
  `createtime` int(11) DEFAULT NULL,
  `status` int(3) DEFAULT NULL,
  `toppic` varchar(500) DEFAULT NULL,
  `advertising` varchar(1000) DEFAULT NULL,
  `bgmsg` varchar(500) DEFAULT NULL,
  `bglink` varchar(255) DEFAULT NULL,
  `versions` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yg_bargain_shop` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `sname` varchar(255) DEFAULT NULL,
  `spic` varchar(500) DEFAULT NULL,
  `listpic` varchar(500) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `setime` int(11) DEFAULT NULL,
  `sstime` int(11) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `max` int(11) DEFAULT NULL,
  `smin` int(11) DEFAULT NULL,
  `smax` int(11) DEFAULT NULL,
  `cutdi` int(11) DEFAULT NULL,
  `stime` int(11) DEFAULT NULL,
  `shoptype` int(11) DEFAULT NULL,
  `rule` varchar(5000) DEFAULT NULL,
  `shopdetail` mediumtext,
  `bgmsg` varchar(500) DEFAULT NULL,
  `bglink` varchar(1000) DEFAULT NULL,
  `pwd` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yg_bargain_shoptype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `typename` varchar(255) DEFAULT NULL,
  `ttime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yg_bargain_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `usertel` varchar(255) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yg_bargain_usershop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `shopid` int(11) DEFAULT NULL,
  `bgprie` float(255,2) DEFAULT NULL,
  `addtime` int(11) DEFAULT NULL,
  `uptime` int(15) DEFAULT NULL,
  `isduihuan` int(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('yg_bargain_help',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_help')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_bargain_help',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_help')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_help',  'behelped')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_help')." ADD `behelped` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_help',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_help')." ADD `shopid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_help',  'helpopenid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_help')." ADD `helpopenid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_help',  'time')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_help')." ADD `time` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_help',  'price')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_help')." ADD `price` float(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('yg_bargain_help',  'cutprice')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_help')." ADD `cutprice` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_info',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_info')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_bargain_info',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_info')." ADD `uniacid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_info',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_info')." ADD `openid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_info',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_info')." ADD `nickname` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_info',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_info')." ADD `headimgurl` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_info',  'logoopenid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_info')." ADD `logoopenid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_oauth',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_oauth')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_bargain_oauth',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_oauth')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_oauth',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_oauth')." ADD `appid` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_oauth',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_oauth')." ADD `secret` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `starttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `endtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'sharepic')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `sharepic` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `sharedesc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `sharetitle` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `createtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `status` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'toppic')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `toppic` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'advertising')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `advertising` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'bgmsg')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `bgmsg` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'bglink')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `bglink` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_reply',  'versions')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_reply')." ADD `versions` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'sname')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `sname` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'spic')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `spic` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'listpic')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `listpic` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'price')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'setime')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `setime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'sstime')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `sstime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'min')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `min` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'max')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `max` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'smin')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `smin` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'smax')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `smax` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'cutdi')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `cutdi` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'stime')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `stime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'shoptype')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `shoptype` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `rule` varchar(5000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'shopdetail')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `shopdetail` mediumtext;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'bgmsg')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `bgmsg` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'bglink')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `bglink` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shop',  'pwd')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shop')." ADD `pwd` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shoptype',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shoptype')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_bargain_shoptype',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shoptype')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shoptype',  'typename')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shoptype')." ADD `typename` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_shoptype',  'ttime')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_shoptype')." ADD `ttime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_user')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_bargain_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_user')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_user',  'username')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_user')." ADD `username` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_user',  'usertel')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_user')." ADD `usertel` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_user')." ADD `openid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_user')." ADD `nickname` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_user')." ADD `headimgurl` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_user',  'time')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_user')." ADD `time` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_usershop',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_usershop')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yg_bargain_usershop',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_usershop')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_usershop',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_usershop')." ADD `openid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_usershop',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_usershop')." ADD `userid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_usershop',  'shopid')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_usershop')." ADD `shopid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_usershop',  'bgprie')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_usershop')." ADD `bgprie` float(255,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_usershop',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_usershop')." ADD `addtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_usershop',  'uptime')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_usershop')." ADD `uptime` int(15) DEFAULT NULL;");
}
if(!pdo_fieldexists('yg_bargain_usershop',  'isduihuan')) {
	pdo_query("ALTER TABLE ".tablename('yg_bargain_usershop')." ADD `isduihuan` int(5) DEFAULT '0';");
}

?>