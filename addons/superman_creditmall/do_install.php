<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_ad` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`title` varchar(255) NOT NULL,
`content` text,
`isshow` tinyint(4) NOT NULL,
`start_time` int(10) unsigned NOT NULL,
`end_time` int(10) unsigned NOT NULL,
`displayorder` int(11) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`,`isshow`,`displayorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_ad_position` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`ad_id` int(11) NOT NULL,
`position_id` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `indx_id` (`ad_id`,`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_checkout_code` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`code` varchar(50) NOT NULL,
`remark` varchar(500) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `ind_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_checkout_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`orderid` int(10) unsigned NOT NULL,
`ordersn` varchar(50) NOT NULL,
`checkout` varchar(50) NOT NULL,
`type` tinyint(4) NOT NULL,
`remark` varchar(500) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `uniq_uo` (`uniacid`,`ordersn`),
KEY `ind_orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_checkout_user` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
`remark` varchar(500) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
`displayorder` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `uniq_uu` (`uniacid`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_dispatch` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`fee` decimal(10,2) NOT NULL,
`isshow` tinyint(4) NOT NULL,
`need_address` tinyint(4) NOT NULL,
`extend` text,
`displayorder` int(11) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_ip_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`ip` varchar(20) NOT NULL,
`location` varchar(100) NOT NULL,
`orderid` int(10) unsigned NOT NULL,
`ordersn` varchar(50) NOT NULL,
`product_id` int(10) unsigned NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `ind_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_kv` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`skey` varchar(255) NOT NULL,
`svalue` mediumtext,
PRIMARY KEY (`id`),
UNIQUE KEY `uniq_us` (`uniacid`,`skey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_mytask` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`task_id` int(11) NOT NULL,
`status` tinyint(4) NOT NULL,
`credit` decimal(10,2) NOT NULL,
`credit_type` varchar(10) NOT NULL,
`progress` varchar(255) NOT NULL,
`extend` text,
`applytime` int(10) unsigned NOT NULL,
`completetime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`),
KEY `indx_uid` (`uid`,`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_navigation` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`icon` varchar(500) NOT NULL,
`title` varchar(100) NOT NULL,
`url` varchar(500) NOT NULL,
`displayorder` int(11) NOT NULL,
`isshow` tinyint(4) NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`ordersn` varchar(50) NOT NULL,
`uid` int(10) unsigned NOT NULL,
`product_id` int(10) unsigned NOT NULL,
`total` int(10) unsigned NOT NULL,
`price` decimal(10,2) NOT NULL,
`credit_type` varchar(10) NOT NULL,
`credit` decimal(10,2) NOT NULL,
`remark` varchar(500) NOT NULL,
`username` varchar(100) NOT NULL,
`mobile` varchar(20) NOT NULL,
`zipcode` varchar(10) NOT NULL,
`address` varchar(1000) NOT NULL,
`express_title` varchar(100) NOT NULL,
`express_no` varchar(50) NOT NULL,
`express_fee` decimal(10,2) NOT NULL,
`pickup_info` text,
`pay_type` tinyint(4) NOT NULL,
`payment_no` varchar(100) NOT NULL,
`pay_credit` tinyint(4) NOT NULL,
`pay_price` tinyint(4) NOT NULL,
`status` tinyint(4) NOT NULL,
`pay_time` int(10) unsigned NOT NULL,
`isread` tinyint(4) NOT NULL,
`extend` text,
`updatetime` int(10) unsigned NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `indx_ordersn` (`ordersn`),
KEY `indx_uniacid` (`uniacid`,`status`,`dateline`),
KEY `indx_uid` (`uid`,`status`,`dateline`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_product` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`title` varchar(255) NOT NULL,
`type` tinyint(4) NOT NULL,
`dispatch_id` text,
`market_price` decimal(10,2) NOT NULL,
`price` decimal(10,2) NOT NULL,
`credit_type` varchar(10) NOT NULL,
`credit` decimal(10,2) NOT NULL,
`total` int(10) unsigned NOT NULL,
`sales` int(10) unsigned NOT NULL,
`month_sales` int(10) unsigned NOT NULL,
`week_sales` int(10) unsigned NOT NULL,
`start_time` int(10) unsigned NOT NULL,
`end_time` int(10) unsigned NOT NULL,
`cover` varchar(255) NOT NULL,
`album` mediumtext,
`view_count` int(10) unsigned NOT NULL,
`share_count` int(10) unsigned NOT NULL,
`comment_count` int(10) unsigned NOT NULL,
`description` text,
`joined_total` int(10) unsigned NOT NULL,
`displayorder` int(11) NOT NULL,
`minus_total` tinyint(4) NOT NULL,
`ishome` tinyint(4) NOT NULL,
`isnew` tinyint(4) NOT NULL,
`ishot` tinyint(4) NOT NULL,
`isshow` tinyint(4) NOT NULL,
`isvirtual` tinyint(4) NOT NULL,
`order_buy_num` tinyint(4) NOT NULL,
`max_buy_num` tinyint(4) NOT NULL,
`today_limit` int(11) NOT NULL,
`extend` text,
`district` varchar(30) NOT NULL,
`city` varchar(30) NOT NULL,
`province` varchar(30) NOT NULL,
`share_credit_type` varchar(10) NOT NULL,
`share_credit` decimal(10,2) NOT NULL,
`groupid` int(11) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`,`type`,`displayorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_product_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`product_id` int(10) unsigned NOT NULL,
`credit_type` varchar(10) NOT NULL,
`credit` decimal(10,2) NOT NULL,
`status` tinyint(4) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
`pay_time` int(10) unsigned NOT NULL,
`pay_price` tinyint(4) NOT NULL,
`pay_credit` tinyint(4) NOT NULL,
`millisecond` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_id` (`uniacid`,`uid`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_product_share` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`product_id` int(10) unsigned NOT NULL,
`friend_uid` int(10) unsigned NOT NULL,
`credit_type` varchar(10) NOT NULL,
`credit` decimal(10,2) NOT NULL,
`ip` char(15) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_stat` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`daytime` int(10) unsigned NOT NULL,
`product_views` int(11) NOT NULL,
`product_shares` int(11) NOT NULL,
`product_comments` int(11) NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_task` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`relate_id` int(11) NOT NULL,
`type` tinyint(4) NOT NULL,
`title` varchar(100) NOT NULL,
`name` varchar(100) NOT NULL,
`url` varchar(255) NOT NULL,
`description` text,
`icon` varchar(100) NOT NULL,
`isshow` tinyint(4) NOT NULL,
`applied` int(11) NOT NULL,
`completed` int(11) NOT NULL,
`limits` int(11) NOT NULL,
`applyperm` text,
`credit_type` varchar(10) NOT NULL,
`credit_min` decimal(10,2) NOT NULL,
`credit_max` decimal(10,2) NOT NULL,
`builtin` tinyint(4) NOT NULL,
`issuperman` tinyint(4) NOT NULL,
`starttime` int(10) unsigned NOT NULL,
`endtime` int(10) unsigned NOT NULL,
`extend` text,
`displayorder` int(11) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
`showdata` tinyint(4) NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_uniacid` (`uniacid`),
KEY `indx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_superman_creditmall_virtual_stuff` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(10) unsigned NOT NULL,
`product_id` int(10) unsigned NOT NULL,
`key` varchar(255) NOT NULL,
`value` varchar(1000) NOT NULL,
`extend` text,
`status` tinyint(4) NOT NULL,
`get_time` int(10) unsigned NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `indx_id` (`uniacid`,`uid`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
