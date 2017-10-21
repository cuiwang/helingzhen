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
if(pdo_tableexists('ims_superman_creditmall_ad')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad',  'title')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad` ADD `title` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad',  'content')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad` ADD `content` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad',  'isshow')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad` ADD `isshow` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad',  'start_time')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad` ADD `start_time` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad',  'end_time')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad` ADD `end_time` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad',  'displayorder')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad` ADD `displayorder` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad_position')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad_position',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad_position` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad_position')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad_position',  'ad_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad_position` ADD `ad_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ad_position')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ad_position',  'position_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ad_position` ADD `position_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_code')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_code',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_code` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_code')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_code',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_code` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_code')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_code',  'title')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_code` ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_code')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_code',  'code')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_code` ADD `code` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_code')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_code',  'remark')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_code` ADD `remark` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_code')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_code',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_code` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_log',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_log` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_log',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_log` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_log',  'uid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_log` ADD `uid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_log',  'orderid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_log` ADD `orderid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_log',  'ordersn')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_log` ADD `ordersn` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_log',  'checkout')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_log` ADD `checkout` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_log',  'type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_log` ADD `type` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_log',  'remark')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_log` ADD `remark` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_log',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_log` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_user')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_user',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_user` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_user')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_user',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_user` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_user')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_user',  'uid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_user` ADD `uid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_user')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_user',  'openid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_user` ADD `openid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_user')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_user',  'remark')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_user` ADD `remark` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_user')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_user',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_user` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_checkout_user')) {
	if(!pdo_fieldexists('ims_superman_creditmall_checkout_user',  'displayorder')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_checkout_user` ADD `displayorder` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_dispatch')) {
	if(!pdo_fieldexists('ims_superman_creditmall_dispatch',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_dispatch` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_dispatch')) {
	if(!pdo_fieldexists('ims_superman_creditmall_dispatch',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_dispatch` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_dispatch')) {
	if(!pdo_fieldexists('ims_superman_creditmall_dispatch',  'title')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_dispatch` ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_dispatch')) {
	if(!pdo_fieldexists('ims_superman_creditmall_dispatch',  'fee')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_dispatch` ADD `fee` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_dispatch')) {
	if(!pdo_fieldexists('ims_superman_creditmall_dispatch',  'isshow')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_dispatch` ADD `isshow` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_dispatch')) {
	if(!pdo_fieldexists('ims_superman_creditmall_dispatch',  'need_address')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_dispatch` ADD `need_address` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_dispatch')) {
	if(!pdo_fieldexists('ims_superman_creditmall_dispatch',  'extend')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_dispatch` ADD `extend` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_dispatch')) {
	if(!pdo_fieldexists('ims_superman_creditmall_dispatch',  'displayorder')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_dispatch` ADD `displayorder` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_dispatch')) {
	if(!pdo_fieldexists('ims_superman_creditmall_dispatch',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_dispatch` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ip_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ip_log',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ip_log` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ip_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ip_log',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ip_log` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ip_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ip_log',  'ip')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ip_log` ADD `ip` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ip_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ip_log',  'location')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ip_log` ADD `location` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ip_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ip_log',  'orderid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ip_log` ADD `orderid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ip_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ip_log',  'ordersn')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ip_log` ADD `ordersn` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ip_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ip_log',  'product_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ip_log` ADD `product_id` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_ip_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_ip_log',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_ip_log` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_kv')) {
	if(!pdo_fieldexists('ims_superman_creditmall_kv',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_kv` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_kv')) {
	if(!pdo_fieldexists('ims_superman_creditmall_kv',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_kv` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_kv')) {
	if(!pdo_fieldexists('ims_superman_creditmall_kv',  'skey')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_kv` ADD `skey` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_kv')) {
	if(!pdo_fieldexists('ims_superman_creditmall_kv',  'svalue')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_kv` ADD `svalue` mediumtext;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'uid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `uid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'task_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `task_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'status')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `status` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'credit')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `credit` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'credit_type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `credit_type` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'progress')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `progress` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'extend')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `extend` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'applytime')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `applytime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_mytask')) {
	if(!pdo_fieldexists('ims_superman_creditmall_mytask',  'completetime')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_mytask` ADD `completetime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_navigation')) {
	if(!pdo_fieldexists('ims_superman_creditmall_navigation',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_navigation` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_navigation')) {
	if(!pdo_fieldexists('ims_superman_creditmall_navigation',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_navigation` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_navigation')) {
	if(!pdo_fieldexists('ims_superman_creditmall_navigation',  'icon')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_navigation` ADD `icon` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_navigation')) {
	if(!pdo_fieldexists('ims_superman_creditmall_navigation',  'title')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_navigation` ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_navigation')) {
	if(!pdo_fieldexists('ims_superman_creditmall_navigation',  'url')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_navigation` ADD `url` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_navigation')) {
	if(!pdo_fieldexists('ims_superman_creditmall_navigation',  'displayorder')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_navigation` ADD `displayorder` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_navigation')) {
	if(!pdo_fieldexists('ims_superman_creditmall_navigation',  'isshow')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_navigation` ADD `isshow` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'ordersn')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `ordersn` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'uid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `uid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'product_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `product_id` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'total')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `total` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'price')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `price` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'credit_type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `credit_type` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'credit')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `credit` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'remark')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `remark` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'username')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `username` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'mobile')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `mobile` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'zipcode')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `zipcode` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'address')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `address` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'express_title')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `express_title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'express_no')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `express_no` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'express_fee')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `express_fee` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'pickup_info')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `pickup_info` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'pay_type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `pay_type` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'payment_no')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `payment_no` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'pay_credit')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `pay_credit` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'pay_price')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `pay_price` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'status')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `status` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'pay_time')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `pay_time` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'isread')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `isread` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'extend')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `extend` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'updatetime')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `updatetime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_order')) {
	if(!pdo_fieldexists('ims_superman_creditmall_order',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_order` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'title')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `title` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `type` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'dispatch_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `dispatch_id` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'market_price')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `market_price` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'price')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `price` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'credit_type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `credit_type` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'credit')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `credit` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'total')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `total` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'sales')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `sales` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'month_sales')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `month_sales` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'week_sales')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `week_sales` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'start_time')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `start_time` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'end_time')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `end_time` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'cover')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `cover` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'album')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `album` mediumtext;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'view_count')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `view_count` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'share_count')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `share_count` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'comment_count')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `comment_count` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'description')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `description` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'joined_total')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `joined_total` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'displayorder')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `displayorder` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'minus_total')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `minus_total` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'ishome')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `ishome` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'isnew')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `isnew` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'ishot')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `ishot` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'isshow')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `isshow` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'isvirtual')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `isvirtual` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'order_buy_num')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `order_buy_num` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'max_buy_num')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `max_buy_num` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'today_limit')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `today_limit` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'extend')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `extend` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'district')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `district` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'city')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `city` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'province')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `province` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'share_credit_type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `share_credit_type` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'share_credit')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `share_credit` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'groupid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `groupid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'uid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `uid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'product_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `product_id` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'credit_type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `credit_type` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'credit')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `credit` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'status')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `status` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'pay_time')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `pay_time` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'pay_price')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `pay_price` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'pay_credit')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `pay_credit` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_log')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_log',  'millisecond')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_log` ADD `millisecond` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_share')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_share',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_share` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_share')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_share',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_share` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_share')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_share',  'uid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_share` ADD `uid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_share')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_share',  'product_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_share` ADD `product_id` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_share')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_share',  'friend_uid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_share` ADD `friend_uid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_share')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_share',  'credit_type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_share` ADD `credit_type` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_share')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_share',  'credit')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_share` ADD `credit` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_share')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_share',  'ip')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_share` ADD `ip` char(15) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_product_share')) {
	if(!pdo_fieldexists('ims_superman_creditmall_product_share',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_product_share` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_stat')) {
	if(!pdo_fieldexists('ims_superman_creditmall_stat',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_stat` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_stat')) {
	if(!pdo_fieldexists('ims_superman_creditmall_stat',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_stat` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_stat')) {
	if(!pdo_fieldexists('ims_superman_creditmall_stat',  'daytime')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_stat` ADD `daytime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_stat')) {
	if(!pdo_fieldexists('ims_superman_creditmall_stat',  'product_views')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_stat` ADD `product_views` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_stat')) {
	if(!pdo_fieldexists('ims_superman_creditmall_stat',  'product_shares')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_stat` ADD `product_shares` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_stat')) {
	if(!pdo_fieldexists('ims_superman_creditmall_stat',  'product_comments')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_stat` ADD `product_comments` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'relate_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `relate_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `type` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'title')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'name')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `name` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'url')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `url` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'description')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `description` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'icon')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `icon` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'isshow')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `isshow` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'applied')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `applied` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'completed')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `completed` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'limits')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `limits` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'applyperm')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `applyperm` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'credit_type')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `credit_type` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'credit_min')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `credit_min` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'credit_max')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `credit_max` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'builtin')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `builtin` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'issuperman')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `issuperman` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'starttime')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `starttime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'endtime')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `endtime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'extend')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `extend` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'displayorder')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `displayorder` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_task')) {
	if(!pdo_fieldexists('ims_superman_creditmall_task',  'showdata')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_task` ADD `showdata` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'uid')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `uid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'product_id')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `product_id` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'key')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `key` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'value')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `value` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'extend')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `extend` text;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'status')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `status` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'get_time')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `get_time` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_superman_creditmall_virtual_stuff')) {
	if(!pdo_fieldexists('ims_superman_creditmall_virtual_stuff',  'dateline')) {
		pdo_query("ALTER TABLE `ims_superman_creditmall_virtual_stuff` ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
