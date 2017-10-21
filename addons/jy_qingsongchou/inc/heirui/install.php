<?php

$sql =<<<EOF
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_adv` (
  `adv_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `adv_title` varchar(100) DEFAULT NULL,
  `adv_pic` varchar(200) DEFAULT NULL,
  `adv_link` varchar(200) DEFAULT NULL,
  `position` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_class` (
  `class_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `class_name` varchar(40) NOT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_fund` (
  `fund_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(10) DEFAULT NULL,
  `pro_id` int(10) DEFAULT NULL,
  `fund_value` double DEFAULT NULL,
  `fund_time` int(10) DEFAULT NULL,
  `fund_true` tinyint(2) NOT NULL DEFAULT '0',
  `order_num` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`fund_id`),
  KEY `mem_id` (`mem_id`),
  KEY `pro_id` (`pro_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_good` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `goods_class` varchar(20) DEFAULT NULL,
  `goods_name` varchar(100) NOT NULL,
  `goods_pic` varchar(200) DEFAULT NULL,
  `goods_intro` text,
  `goods_param` text,
  `goods_say` text,
  `goods_price` float(10,2) DEFAULT NULL,
  `goods_status` int(2) DEFAULT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_mall` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `copyright` varchar(200) DEFAULT NULL,
  `code_pic` varchar(200) DEFAULT NULL,
  `banner` varchar(200) DEFAULT NULL,
  `banner_link` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_member` (
  `mem_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mem_name` varchar(100) DEFAULT NULL,
  `mem_photo` varchar(200) DEFAULT NULL,
  `mem_pass` varchar(200) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  PRIMARY KEY (`mem_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_order` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_num` varchar(100) DEFAULT NULL,
  `mem_id` int(10) NOT NULL,
  `goods_id` int(10) NOT NULL,
  `pro_id` int(10) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `goods_num` int(2) DEFAULT NULL,
  `total_price` float(10,2) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`order_id`),
  KEY `mem_id` (`mem_id`),
  KEY `goods_id` (`goods_id`),
  KEY `pro_id` (`pro_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_project` (
  `pro_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `class_id` int(10) DEFAULT NULL,
  `pro_name` varchar(40) NOT NULL,
  `pro_short` varchar(100) DEFAULT NULL,
  `pro_gress` text,
  `pro_feature` varchar(200) DEFAULT NULL,
  `pro_content` text,
  `pro_fund` double DEFAULT NULL,
  `pro_goal` float DEFAULT NULL,
  `pro_stick` tinyint(2) NOT NULL DEFAULT '0',
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`pro_id`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `gy_name` varchar(40) DEFAULT NULL,
  `gy_logo` varchar(100) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `gy_agree_name` varchar(100) DEFAULT NULL,
  `gy_agree` text,
  `appid` varchar(200) NOT NULL,
  `appsecret` varchar(200) NOT NULL,
  `mchid` varchar(100) NOT NULL,
  `shkey` varchar(100) NOT NULL,
  `is_follow` tinyint(2) NOT NULL DEFAULT '0',
  `is_sort` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_slide` (
  `slide_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `slide_pic` varchar(200) DEFAULT NULL,
  `slide_title` varchar(100) DEFAULT NULL,
  `slide_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_welfare_uadd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mem_id` int(10) DEFAULT NULL,
  `class_id` int(10) DEFAULT NULL,
  `utel` varchar(20) DEFAULT NULL,
  `pro_name` varchar(40) NOT NULL,
  `pro_short` varchar(30) DEFAULT NULL,
  `pro_gress` text,
  `pro_feature` varchar(200) DEFAULT NULL,
  `pro_content` text,
  `pro_goal` float DEFAULT NULL,
  `add_time` int(10) DEFAULT NULL,
  `verify` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mem_id` (`mem_id`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF;
pdo_run($sql);
