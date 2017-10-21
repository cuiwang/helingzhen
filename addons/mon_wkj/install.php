<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_mon_wkj` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`rid` int(10) NOT NULL,
`weid` int(11) NOT NULL,
`title` varchar(200) NOT NULL,
`starttime` int(10),
`endtime` int(10),
`p_name` varchar(100),
`p_kc` int(10),
`p_y_price` float(10,2),
`p_low_price` float(10,2),
`yf_price` float(10,2),
`p_pic` varchar(200),
`p_preview_pic` varchar(200),
`follow_url` varchar(200),
`copyright` varchar(100) NOT NULL,
`new_title` varchar(200),
`new_icon` varchar(200),
`new_content` varchar(200),
`share_title` varchar(200),
`share_icon` varchar(200),
`share_content` varchar(200),
`p_url` varchar(500),
`copyright_url` varchar(500),
`hot_tel` varchar(50),
`p_intro` varchar(1000),
`createtime` int(10),
`kj_dialog_tip` varchar(1000),
`rank_tip` varchar(1000),
`u_fist_tip` varchar(1000),
`u_already_tip` varchar(1000),
`fk_fist_tip` varchar(1000),
`fk_already_tip` varchar(1000),
`kj_rule` varchar(1000),
`pay_type` int(2),
`p_model` varchar(1000),
`friend_help_limit` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_mon_wkj_firend` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`kid` int(10) NOT NULL,
`uid` int(10) NOT NULL,
`openid` varchar(200) NOT NULL,
`nickname` varchar(100) NOT NULL,
`headimgurl` varchar(200) NOT NULL,
`k_price` float(10,2),
`kh_price` float(10,2),
`createtime` int(10),
`ip` varchar(30),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_mon_wkj_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`kid` int(10) NOT NULL,
`uid` int(10) NOT NULL,
`uname` varchar(100),
`address` varchar(100),
`tel` varchar(50),
`openid` varchar(200),
`order_no` varchar(100),
`wxorder_no` varchar(100),
`y_price` float(10,2),
`kh_price` float(10,2),
`yf_price` float(10,2),
`total_price` float(10,2),
`pay_type` int(2),
`p_model` varchar(1000),
`status` int(1),
`wxnotify` varchar(200),
`notifytime` int(10),
`createtime` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_mon_wkj_setting` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`appid` varchar(200),
`appsecret` varchar(200),
`mchid` varchar(100),
`shkey` varchar(100),
`createtime` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_mon_wkj_user` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`kid` int(10) NOT NULL,
`openid` varchar(200) NOT NULL,
`nickname` varchar(100) NOT NULL,
`headimgurl` varchar(200) NOT NULL,
`price` float(10,2),
`ip` varchar(30),
`createtime` int(10),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
