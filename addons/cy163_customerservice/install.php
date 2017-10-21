<?php
pdo_query("
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_messikefu_cservice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ctype` tinyint(1) NOT NULL,
  `content` varchar(100) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_messikefu_tplmessage_sendlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tpl_id` int(11) DEFAULT NULL,
  `tpl_title` varchar(50) DEFAULT NULL,
  `message` text COMMENT '消息内容',
  `success` int(11) DEFAULT '0' COMMENT '成功人数',
  `fail` int(11) DEFAULT '0' COMMENT '失败人数',
  `time` int(11) DEFAULT NULL COMMENT '发送时间',
  `uniacid` int(5) DEFAULT NULL,
  `type` int(5) DEFAULT '0' COMMENT '消息类型 0为群发 1为个人',
  `target` varchar(80) DEFAULT '' COMMENT '发送对象 type 为0时 是粉丝组 type 为1时是openid',
  `status` int(2) DEFAULT '0' COMMENT '状态 0为发送中 1为完成 2为失败',
  `error` text COMMENT '错误记录',
  `mid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_messikefu_tplmessage_tpllist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tplbh` varchar(50) NOT NULL,
  `tpl_id` varchar(80) DEFAULT NULL,
  `tpl_title` varchar(20) DEFAULT NULL,
  `tpl_key` varchar(500) DEFAULT NULL COMMENT '模板内容key',
  `tpl_example` varchar(500) DEFAULT NULL,
  `uniacid` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
");
