<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_tb_service_fast` (
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
`uditing` int(11) NOT NULL,
`identify` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
