<?php

$sql =<<<EOF
DROP TABLE IF EXISTS `ims_enjoy_city_ad`;
CREATE TABLE `ims_enjoy_city_ad` (
  `id` int(200) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) unsigned DEFAULT NULL,
  `hot` int(5) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `url` longtext,
  `pic` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_contact`;
CREATE TABLE `ims_enjoy_city_contact` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `hot` int(50) DEFAULT NULL,
  `cgender` int(2) DEFAULT NULL,
  `cavatar` longtext,
  `cewm` longtext,
  `cnickname` varchar(50) DEFAULT NULL,
  `cweixin` varchar(50) DEFAULT NULL,
  `descript` varchar(1000) DEFAULT NULL,
  `addtime` varchar(50) DEFAULT NULL,
  `ischeck` int(2) DEFAULT NULL,
  `uid` int(255) DEFAULT NULL,
  `kind` int(2) DEFAULT NULL,
  `checktime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_custom`;
CREATE TABLE `ims_enjoy_city_custom` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `hot` int(50) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL,
  `thumb` varchar(1000) DEFAULT NULL,
  `wurl` varchar(1000) DEFAULT NULL,
  `enabled` int(2) NOT NULL DEFAULT '0' COMMENT '0显示1不显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_fans`;
CREATE TABLE `ims_enjoy_city_fans` (
  `uid` int(255) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `huid` int(255) NOT NULL DEFAULT '0',
  `openid` varchar(40) NOT NULL DEFAULT '',
  `proxy` varchar(40) NOT NULL DEFAULT '',
  `unionid` varchar(40) NOT NULL DEFAULT '',
  `nickname` varchar(20) NOT NULL DEFAULT '',
  `gender` varchar(2) DEFAULT '',
  `state` varchar(20) NOT NULL DEFAULT '',
  `city` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `avatar` varchar(500) NOT NULL DEFAULT '',
  `subscribe` int(2) DEFAULT NULL,
  `subscribe_time` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `ewm` varchar(500) DEFAULT NULL,
  `weixin` varchar(100) DEFAULT NULL,
  `descript` varchar(1000) DEFAULT NULL,
  `addtime` varchar(50) DEFAULT NULL,
  `type` int(2) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `ischeck` int(2) NOT NULL DEFAULT '0',
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`),
  KEY `proxy` (`proxy`),
  KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_fansxx`;
CREATE TABLE `ims_enjoy_city_fansxx` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(255) DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `intro` longtext,
  `createtime` varchar(50) DEFAULT NULL,
  `overtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_fimg`;
CREATE TABLE `ims_enjoy_city_fimg` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(200) DEFAULT NULL,
  `imgurl` varchar(1000) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_firm`;
CREATE TABLE `ims_enjoy_city_firm` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `hot` int(50) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `parentid` int(50) DEFAULT NULL,
  `childid` int(50) DEFAULT NULL,
  `tel` varchar(30) DEFAULT NULL,
  `intro` longtext,
  `ischeck` int(2) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `address` varchar(1000) DEFAULT NULL,
  `location_x` varchar(50) DEFAULT NULL,
  `location_y` varchar(50) DEFAULT NULL,
  `img` varchar(500) DEFAULT NULL,
  `icon` varchar(500) DEFAULT NULL,
  `uid` varchar(255) NOT NULL DEFAULT '0',
  `isrank` int(2) NOT NULL DEFAULT '0',
  `ismoney` int(2) NOT NULL DEFAULT '0',
  `stime` varchar(50) DEFAULT NULL,
  `etime` varchar(50) DEFAULT NULL,
  `browse` int(255) NOT NULL DEFAULT '0',
  `forward` int(255) NOT NULL DEFAULT '0',
  `wei_num` varchar(200) DEFAULT NULL,
  `wei_name` varchar(200) DEFAULT NULL,
  `wei_sex` int(2) DEFAULT NULL,
  `wei_intro` varchar(1000) DEFAULT NULL,
  `wei_avatar` varchar(2000) DEFAULT NULL,
  `wei_ewm` varchar(2000) DEFAULT NULL,
  `s_name` varchar(200) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  `sid` int(50) DEFAULT NULL,
  `ispay` int(2) NOT NULL DEFAULT '0',
  `paymoney` float(50,2) DEFAULT NULL,
  `breaks` longtext,
  `custom` varchar(200) NOT NULL,
  `firmurl` varchar(5000) DEFAULT NULL,
  `rid` int(255) DEFAULT NULL,
  `starnums` int(50) NOT NULL DEFAULT '0',
  `star` float(10,2) DEFAULT NULL,
  `starscores` int(50) NOT NULL DEFAULT '0',
  `video1` text,
  `video2` text,
  `fax` text,
  `cflag` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_firmfans`;
CREATE TABLE `ims_enjoy_city_firmfans` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(255) DEFAULT NULL,
  `rid` int(255) DEFAULT NULL,
  `openid` varchar(500) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  `favatar` longtext,
  `fnickname` varchar(500) DEFAULT NULL,
  `flag` int(2) DEFAULT '0',
  `starscore` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_firmlabel`;
CREATE TABLE `ims_enjoy_city_firmlabel` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `openid` varchar(500) DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL,
  `checked` int(2) NOT NULL DEFAULT '0',
  `times` int(50) unsigned DEFAULT NULL,
  `checktime` varchar(30) DEFAULT NULL,
  `fid` int(50) DEFAULT NULL,
  `createtime` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_forward`;
CREATE TABLE `ims_enjoy_city_forward` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(200) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_job`;
CREATE TABLE `ims_enjoy_city_job` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(255) DEFAULT NULL,
  `ptitle` varchar(500) DEFAULT NULL,
  `wages` varchar(500) DEFAULT NULL,
  `pnum` int(50) DEFAULT NULL,
  `pmobile` varchar(50) DEFAULT NULL,
  `isend` int(2) NOT NULL DEFAULT '0',
  `isfull` int(2) NOT NULL DEFAULT '0',
  `paddress` varchar(5000) DEFAULT NULL,
  `pdetail` longtext,
  `ischeck` int(11) NOT NULL DEFAULT '0',
  `updatetime` varchar(50) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_kind`;
CREATE TABLE `ims_enjoy_city_kind` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `hot` int(50) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL,
  `thumb` varchar(1000) DEFAULT NULL,
  `parentid` int(50) DEFAULT NULL,
  `wurl` varchar(1000) DEFAULT NULL,
  `enabled` int(2) NOT NULL DEFAULT '0' COMMENT '0显示1不显示',
  `headimg` varchar(5000) DEFAULT NULL,
  `headurl` varchar(5000) DEFAULT NULL,
  `footimg` varchar(5000) DEFAULT NULL,
  `footurl` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_pics`;
CREATE TABLE `ims_enjoy_city_pics` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `fid` int(100) DEFAULT NULL,
  `picurl` varchar(1000) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_reply`;
CREATE TABLE `ims_enjoy_city_reply` (
  `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `icon` longtext,
  `ewm` longtext,
  `slogo` longtext,
  `sucai` longtext,
  `share_title` varchar(500) DEFAULT NULL,
  `share_icon` varchar(500) DEFAULT NULL,
  `share_content` varchar(1000) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `copyright` varchar(500) DEFAULT NULL,
  `agreement` longtext,
  `weixin` varchar(200) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  `fee` varchar(200) NOT NULL DEFAULT '200',
  `issq` int(2) NOT NULL DEFAULT '0',
  `mshare_title` varchar(500) DEFAULT NULL,
  `mshare_icon` varchar(500) DEFAULT NULL,
  `mshare_content` varchar(1000) DEFAULT NULL,
  `jointel` varchar(1000) DEFAULT NULL,
  `banner` longtext,
  `onlinepay` int(2) NOT NULL DEFAULT '0',
  `bonus` longtext,
  `kfewm` longtext,
  `isright` int(2) DEFAULT '0',
  `issmple` int(2) NOT NULL DEFAULT '0',
  `wtt` longtext,
  `custurl` text,
  `custimg` longtext,
  `custurl1` longtext,
  `custimg1` varchar(2000) DEFAULT NULL,
  `custurl2` longtext,
  `custimg2` varchar(2000) DEFAULT NULL,
  `isjob` int(2) NOT NULL DEFAULT '0',
  `wstyle` int(2) NOT NULL DEFAULT '0',
  `weurl` text,
  `ispayfirst` int(2) DEFAULT '0',
  `ftitle` varchar(500) DEFAULT NULL,
  `fivebg` varchar(300) DEFAULT NULL,
  `fknock` int(2) DEFAULT '0',
  `ftimes` int(2) DEFAULT '0',
  `discount` int(2) DEFAULT '0',
  `system` int(2) DEFAULT '0',
  `fcredit` int(2) DEFAULT '0',
  `fsdcredit` int(2) DEFAULT '0',
  `fright` int(2) DEFAULT '0',
  `fmoney` int(2) DEFAULT '0',
  `fshare_icon` varchar(300) DEFAULT NULL,
  `fshare_title` varchar(300) DEFAULT NULL,
  `fshare_content` varchar(1000) DEFAULT NULL,
  `fmshare_icon` varchar(300) DEFAULT NULL,
  `fmshare_title` varchar(300) DEFAULT NULL,
  `fmshare_content` varchar(1000) DEFAULT NULL,
  `funit` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ims_enjoy_city_seller`;
CREATE TABLE `ims_enjoy_city_seller` (
  `id` int(50) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `realname` varchar(500) DEFAULT NULL,
  `openid` varchar(1000) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ims_enjoy_city_taglap`;
CREATE TABLE `ims_enjoy_city_taglap` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(50) DEFAULT NULL,
  `tagid` int(255) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `fid` int(255) DEFAULT NULL,
  `flag` int(2) NOT NULL DEFAULT '1',
  `createtime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


EOF;
pdo_run($sql);
