<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_ad` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`title` varchar(255),
`image` varchar(255),
`url` varchar(255),
`type` tinyint(2),
`delay` int(5),
`createtime` int(11),
`updatetime` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_gift` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`name` varchar(20) NOT NULL,
`price` int(11) NOT NULL,
`type` varchar(10) NOT NULL,
`num` int(11) NOT NULL,
`status` tinyint(1) NOT NULL,
`del` tinyint(1) NOT NULL,
`description` text,
`createtime` int(11) NOT NULL,
`updatetime` int(11) NOT NULL,
`pic` varchar(255) NOT NULL,
`mode` tinyint(1) NOT NULL,
`send_price` decimal(10,1),
`mobile_fee_money` int(10),
`hongbao_money` int(10),
`ziling_address` varchar(255),
`ziling_mobile` varchar(11),
`check_password` varchar(255),
`hide` tinyint(1),
`sold` int(11),
`limit_num` int(11),
`raffle` tinyint(1),
`hongbao_mode` tinyint(1),
`hongbao_min` int(11),
`hongbao_max` int(11),
`hongbao_send_num` varchar(255),
`raffle_min` int(11),
`raffle_max` int(11),
`raffle_send_num` varchar(255),
`auto_success` tinyint(1),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_gift_admin` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`openid` varchar(40) NOT NULL,
`gift_id` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_gift_order` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`openid` varchar(128) NOT NULL,
`gift` int(11) NOT NULL,
`status` int(2) NOT NULL,
`name` varchar(10) NOT NULL,
`mobile` varchar(11) NOT NULL,
`target` varchar(30) NOT NULL,
`createtime` int(10) NOT NULL,
`updatetime` int(10) NOT NULL,
`pay_method` tinyint(1),
`pay_status` tinyint(1),
`trans_num` varchar(100),
`order_num` varchar(255),
`send_price` decimal(10,1) NOT NULL,
`order_price` decimal(10,1) NOT NULL,
`raffle_status` tinyint(1),
`order_mode` tinyint(1),
`order_hongbao_money` int(11),
`remark` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_tpl_template_config` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`get_notice` varchar(255),
`check_status_access_notice` varchar(255),
`check_status_refuse_notice` varchar(255),
`send_notice` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
