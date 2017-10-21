<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_messikefu_adv` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`advname` varchar(50),
`link` varchar(255),
`thumb` varchar(255),
`displayorder` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_biaoqian` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`kefuopenid` varchar(200) NOT NULL,
`fensiopenid` varchar(200) NOT NULL,
`name` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_chat` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`openid` varchar(100) NOT NULL,
`toopenid` varchar(100) NOT NULL,
`content` varchar(255) NOT NULL,
`time` int(11) NOT NULL,
`nickname` varchar(50) NOT NULL,
`avatar` varchar(200) NOT NULL,
`type` tinyint(1) NOT NULL,
`hasread` tinyint(1) NOT NULL,
`fkid` int(11) NOT NULL,
`yuyintime` smallint(6) NOT NULL,
`hasyuyindu` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_cservice` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`ctype` tinyint(1) NOT NULL,
`content` varchar(100) NOT NULL,
`thumb` varchar(255) NOT NULL,
`displayorder` int(11) NOT NULL,
`starthour` smallint(6) NOT NULL,
`endhour` smallint(6) NOT NULL,
`autoreply` varchar(200) NOT NULL,
`isonline` tinyint(1) NOT NULL,
`groupid` int(11) NOT NULL,
`fansauto` text NOT NULL,
`kefuauto` text NOT NULL,
`isautosub` tinyint(1) NOT NULL,
`qrtext` varchar(50) NOT NULL,
`qrcolor` varchar(20) NOT NULL,
`qrbg` varchar(20) NOT NULL,
`iskefuqrcode` tinyint(1) NOT NULL,
`kefuqrcode` varchar(200) NOT NULL,
`ishow` tinyint(1) NOT NULL,
`notonline` varchar(255) NOT NULL,
`lingjie` tinyint(1) NOT NULL,
`typename` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_cservicegroup` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(255) NOT NULL,
`displayorder` int(11) NOT NULL,
`qrtext` varchar(50) NOT NULL,
`qrbg` varchar(20) NOT NULL,
`qrcolor` varchar(20) NOT NULL,
`cangroup` tinyint(1) NOT NULL,
`typename` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_fanskefu` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`fansopenid` varchar(100) NOT NULL,
`kefuopenid` varchar(100) NOT NULL,
`fansavatar` varchar(200) NOT NULL,
`kefuavatar` varchar(200) NOT NULL,
`fansnickname` varchar(100) NOT NULL,
`kefunickname` varchar(100) NOT NULL,
`lasttime` int(11) NOT NULL,
`notread` int(11) NOT NULL,
`lastcon` varchar(255) NOT NULL,
`kefulasttime` int(11) NOT NULL,
`kefulastcon` varchar(255) NOT NULL,
`kefunotread` int(11) NOT NULL,
`msgtype` smallint(6) NOT NULL,
`kefumsgtype` smallint(6) NOT NULL,
`kefuseetime` int(11) NOT NULL,
`fansseetime` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_group` (
`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`groupname` varchar(100) NOT NULL,
`thumb` varchar(200) NOT NULL,
`admin` varchar(100) NOT NULL,
`time` int(11) NOT NULL,
`autoreply` varchar(200) NOT NULL,
`quickcon` text NOT NULL,
`isautosub` tinyint(1) NOT NULL,
`cservicegroupid` int(11) NOT NULL,
`lasttime` int(11) NOT NULL,
`maxnum` int(11) NOT NULL,
`isguanzhu` tinyint(1) NOT NULL,
`jinyan` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_groupchat` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`nickname` varchar(100) NOT NULL,
`avatar` varchar(255) NOT NULL,
`weid` int(11) NOT NULL,
`groupid` int(11) NOT NULL,
`openid` varchar(100) NOT NULL,
`content` varchar(255) NOT NULL,
`time` int(11) NOT NULL,
`type` tinyint(1) NOT NULL,
`yuyintime` smallint(6) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_groupmember` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`groupid` int(11) NOT NULL,
`openid` varchar(100) NOT NULL,
`nickname` varchar(50) NOT NULL,
`avatar` varchar(255) NOT NULL,
`type` tinyint(1) NOT NULL,
`status` tinyint(1) NOT NULL,
`intime` int(11) NOT NULL,
`lasttime` int(11) NOT NULL,
`notread` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_jqr` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`title` varchar(100) NOT NULL,
`huifu` text NOT NULL,
`kefuid` int(11) NOT NULL,
`type` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_sanchat` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`sanfkid` int(11) NOT NULL,
`content` varchar(255) NOT NULL,
`time` int(11) NOT NULL,
`type` tinyint(1) NOT NULL,
`openid` varchar(100) NOT NULL,
`yuyintime` smallint(6) NOT NULL,
`hasyuyindu` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_sanfanskefu` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`fansopenid` varchar(100) NOT NULL,
`kefuopenid` varchar(100) NOT NULL,
`fansavatar` varchar(200) NOT NULL,
`kefuavatar` varchar(200) NOT NULL,
`fansnickname` varchar(100) NOT NULL,
`kefunickname` varchar(100) NOT NULL,
`lasttime` int(11) NOT NULL,
`notread` int(11) NOT NULL,
`lastcon` varchar(255) NOT NULL,
`msgtype` smallint(6) NOT NULL,
`seetime` int(11) NOT NULL,
`qudao` varchar(50) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_set` (
`id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`title` varchar(30) NOT NULL,
`istplon` tinyint(1) NOT NULL,
`unfollowtext` text NOT NULL,
`followqrcode` varchar(100) NOT NULL,
`sharetitle` varchar(100) NOT NULL,
`sharedes` varchar(255) NOT NULL,
`sharethumb` varchar(155) NOT NULL,
`kefutplminute` int(11) NOT NULL,
`bgcolor` varchar(10) NOT NULL,
`defaultavatar` varchar(100) NOT NULL,
`fansauto` text NOT NULL,
`kefuauto` text NOT NULL,
`issharemsg` tinyint(1) NOT NULL,
`isautosub` tinyint(1) NOT NULL,
`isshowwgz` tinyint(1) NOT NULL,
`sharetype` tinyint(1) NOT NULL,
`mingan` text NOT NULL,
`temcolor` varchar(50) NOT NULL,
`candel` tinyint(1) NOT NULL,
`copyright` varchar(255) NOT NULL,
`canservicequn` tinyint(1) NOT NULL,
`canfansqun` tinyint(1) NOT NULL,
`isgrouptplon` tinyint(1) NOT NULL,
`grouptplminute` int(11) NOT NULL,
`isgroupon` tinyint(1) NOT NULL,
`footertext1` varchar(50) NOT NULL,
`footertext2` varchar(50) NOT NULL,
`footertext3` varchar(50) NOT NULL,
`footertext4` varchar(50) NOT NULL,
`isqiniu` tinyint(1) NOT NULL,
`qiniuaccesskey` varchar(255) NOT NULL,
`qiniusecretkey` varchar(255) NOT NULL,
`qiniubucket` varchar(255) NOT NULL,
`qiniuurl` varchar(255) NOT NULL,
`httptype` tinyint(1) NOT NULL,
`istxfon` tinyint(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_tplmessage_sendlog` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`tpl_id` int(11),
`tpl_title` varchar(50),
`message` text,
`success` int(11),
`fail` int(11),
`time` int(11),
`uniacid` int(5),
`type` int(5),
`target` varchar(80),
`status` int(2),
`error` text,
`mid` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_messikefu_tplmessage_tpllist` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`tplbh` varchar(50) NOT NULL,
`tpl_id` varchar(80),
`tpl_title` varchar(20),
`tpl_key` varchar(500),
`tpl_example` varchar(500),
`uniacid` int(5),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
