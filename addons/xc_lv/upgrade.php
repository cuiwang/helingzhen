<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_xc_lv_adv` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`advname` varchar(50),
`link` varchar(255),
`thumb` varchar(255),
`displayorder` int(11),
`enabled` int(11),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_enabled` (`enabled`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_cart` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`goodsid` int(11) NOT NULL,
`goodstype` tinyint(1) NOT NULL,
`from_user` varchar(50) NOT NULL,
`total` int(10) unsigned NOT NULL,
`optionid` int(10),
`marketprice` decimal(10,2),
PRIMARY KEY (`id`),
KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(255) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`isrecommand` int(10),
`description` varchar(500) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`catetitle` varchar(255),
`enabled` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_dispatch` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`dispatchname` varchar(50),
`dispatchtype` int(11),
`displayorder` int(11),
`firstprice` decimal(10,2),
`secondprice` decimal(10,2),
`firstweight` int(11),
`secondweight` int(11),
`express` int(11),
`description` text,
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_express` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`express_name` varchar(50),
`displayorder` int(11),
`express_price` varchar(10),
`express_area` varchar(100),
`express_url` varchar(255),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_feedback` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
`type` tinyint(1) unsigned NOT NULL,
`status` tinyint(1) NOT NULL,
`feedbackid` varchar(30) NOT NULL,
`transid` varchar(30) NOT NULL,
`reason` varchar(1000) NOT NULL,
`solution` varchar(1000) NOT NULL,
`remark` varchar(1000) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_weid` (`weid`),
KEY `idx_feedbackid` (`feedbackid`),
KEY `idx_createtime` (`createtime`),
KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_firm` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`city` varchar(255),
`city2` varchar(255),
`stime` datetime,
`xq` varchar(255),
`mark` text,
`xingming` varchar(255),
`mobile` varchar(20),
`haoma` varchar(255),
`status` int(1),
`openid` varchar(200),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_goods` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`type` tinyint(1) unsigned NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`thumb` varchar(100) NOT NULL,
`unit` varchar(5) NOT NULL,
`description` varchar(1000) NOT NULL,
`content` text NOT NULL,
`goodssn` varchar(50) NOT NULL,
`productsn` varchar(50) NOT NULL,
`marketprice` decimal(10,2) NOT NULL,
`productprice` decimal(10,2) NOT NULL,
`costprice` decimal(10,2) NOT NULL,
`originalprice` decimal(10,2) NOT NULL,
`total` int(10) unsigned NOT NULL,
`totalcnf` int(11),
`sales` int(10) unsigned NOT NULL,
`spec` varchar(5000) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
`weight` decimal(10,2) NOT NULL,
`credit` decimal(10,2) NOT NULL,
`maxbuy` int(11),
`usermaxbuy` int(10) unsigned NOT NULL,
`hasoption` int(11),
`dispatch` int(11),
`thumb_url` text,
`isnew` int(11),
`ishot` int(11),
`isdiscount` int(11),
`isrecommand` int(11),
`istime` int(11),
`timestart` int(11),
`timeend` int(11),
`viewcount` int(11),
`deleted` tinyint(3) unsigned NOT NULL,
`starttime` int(11),
`endtime` int(11),
`xingcheng` text,
`time2` varchar(500),
`xingji` int(10),
`detime` varchar(5000) NOT NULL,
`zhi` text NOT NULL,
`location` varchar(255),
`han` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_goods_option` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goodsid` int(10),
`title` varchar(50),
`thumb` varchar(60),
`productprice` decimal(10,2),
`marketprice` decimal(10,2),
`costprice` decimal(10,2),
`stock` int(11),
`weight` decimal(10,2),
`displayorder` int(11),
`specs` text,
PRIMARY KEY (`id`),
KEY `indx_goodsid` (`goodsid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_goods_param` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goodsid` int(10),
`title` varchar(50),
`value` text,
`displayorder` int(11),
PRIMARY KEY (`id`),
KEY `indx_goodsid` (`goodsid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_member_address` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`uid` int(50) unsigned NOT NULL,
`username` varchar(20) NOT NULL,
`mobile` varchar(11) NOT NULL,
`zipcode` varchar(6) NOT NULL,
`province` varchar(32) NOT NULL,
`city` varchar(32) NOT NULL,
`district` varchar(32) NOT NULL,
`address` varchar(512) NOT NULL,
`isdefault` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_uinacid` (`uniacid`),
KEY `idx_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(50) NOT NULL,
`ordersn` varchar(20) NOT NULL,
`price` varchar(10) NOT NULL,
`status` tinyint(4) NOT NULL,
`sendtype` tinyint(1) unsigned NOT NULL,
`paytype` tinyint(1) unsigned NOT NULL,
`transid` varchar(30) NOT NULL,
`goodstype` tinyint(1) unsigned NOT NULL,
`remark` varchar(1000) NOT NULL,
`address` varchar(1024) NOT NULL,
`expresscom` varchar(30) NOT NULL,
`expresssn` varchar(50) NOT NULL,
`express` varchar(200) NOT NULL,
`goodsprice` decimal(10,2),
`dispatchprice` decimal(10,2),
`dispatch` int(10),
`paydetail` varchar(255) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
`retime` datetime,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_order_goods` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`orderid` int(10) unsigned NOT NULL,
`goodsid` int(10) unsigned NOT NULL,
`price` decimal(10,2),
`total` int(10) unsigned NOT NULL,
`optionid` int(10),
`createtime` int(10) unsigned NOT NULL,
`optionname` text,
`ettotal` int(10),
`productprice` decimal(10,2),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_product` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`goodsid` int(11) NOT NULL,
`productsn` varchar(50) NOT NULL,
`title` varchar(1000) NOT NULL,
`marketprice` decimal(10,0) unsigned NOT NULL,
`productprice` decimal(10,0) unsigned NOT NULL,
`total` int(11) NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`spec` varchar(5000) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_spec` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`description` varchar(1000) NOT NULL,
`displaytype` tinyint(3) unsigned NOT NULL,
`content` text NOT NULL,
`goodsid` int(11),
`displayorder` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xc_lv_spec_item` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`specid` int(11),
`title` varchar(255),
`thumb` varchar(255),
`show` int(11),
`displayorder` int(11),
PRIMARY KEY (`id`),
KEY `indx_weid` (`weid`),
KEY `indx_specid` (`specid`),
KEY `indx_show` (`show`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('xc_lv_adv')) {
	if(!pdo_fieldexists('xc_lv_adv',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_adv')) {
	if(!pdo_fieldexists('xc_lv_adv',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_adv')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_adv')) {
	if(!pdo_fieldexists('xc_lv_adv',  'advname')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_adv')." ADD `advname` varchar(50);");
	}	
}
if(pdo_tableexists('xc_lv_adv')) {
	if(!pdo_fieldexists('xc_lv_adv',  'link')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_adv')." ADD `link` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_adv')) {
	if(!pdo_fieldexists('xc_lv_adv',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_adv')." ADD `thumb` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_adv')) {
	if(!pdo_fieldexists('xc_lv_adv',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_adv')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_adv')) {
	if(!pdo_fieldexists('xc_lv_adv',  'enabled')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_adv')." ADD `enabled` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_cart')) {
	if(!pdo_fieldexists('xc_lv_cart',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_cart')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_cart')) {
	if(!pdo_fieldexists('xc_lv_cart',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_cart')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_cart')) {
	if(!pdo_fieldexists('xc_lv_cart',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_cart')." ADD `goodsid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_cart')) {
	if(!pdo_fieldexists('xc_lv_cart',  'goodstype')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_cart')." ADD `goodstype` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_cart')) {
	if(!pdo_fieldexists('xc_lv_cart',  'from_user')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_cart')." ADD `from_user` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_cart')) {
	if(!pdo_fieldexists('xc_lv_cart',  'total')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_cart')." ADD `total` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_cart')) {
	if(!pdo_fieldexists('xc_lv_cart',  'optionid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_cart')." ADD `optionid` int(10);");
	}	
}
if(pdo_tableexists('xc_lv_cart')) {
	if(!pdo_fieldexists('xc_lv_cart',  'marketprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_cart')." ADD `marketprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'name')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `name` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `thumb` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'parentid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `parentid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'isrecommand')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `isrecommand` int(10);");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'description')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `description` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'catetitle')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `catetitle` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_category')) {
	if(!pdo_fieldexists('xc_lv_category',  'enabled')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_category')." ADD `enabled` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'dispatchname')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `dispatchname` varchar(50);");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'dispatchtype')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `dispatchtype` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'firstprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `firstprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'secondprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `secondprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'firstweight')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `firstweight` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'secondweight')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `secondweight` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'express')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `express` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_dispatch')) {
	if(!pdo_fieldexists('xc_lv_dispatch',  'description')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_dispatch')." ADD `description` text;");
	}	
}
if(pdo_tableexists('xc_lv_express')) {
	if(!pdo_fieldexists('xc_lv_express',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_express')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_express')) {
	if(!pdo_fieldexists('xc_lv_express',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_express')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_express')) {
	if(!pdo_fieldexists('xc_lv_express',  'express_name')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_express')." ADD `express_name` varchar(50);");
	}	
}
if(pdo_tableexists('xc_lv_express')) {
	if(!pdo_fieldexists('xc_lv_express',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_express')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_express')) {
	if(!pdo_fieldexists('xc_lv_express',  'express_price')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_express')." ADD `express_price` varchar(10);");
	}	
}
if(pdo_tableexists('xc_lv_express')) {
	if(!pdo_fieldexists('xc_lv_express',  'express_area')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_express')." ADD `express_area` varchar(100);");
	}	
}
if(pdo_tableexists('xc_lv_express')) {
	if(!pdo_fieldexists('xc_lv_express',  'express_url')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_express')." ADD `express_url` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `openid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'type')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `type` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'status')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `status` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'feedbackid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `feedbackid` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'transid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `transid` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'reason')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `reason` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'solution')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `solution` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'remark')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `remark` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_feedback')) {
	if(!pdo_fieldexists('xc_lv_feedback',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_feedback')." ADD `createtime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'city')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `city` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'city2')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `city2` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'stime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `stime` datetime;");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'xq')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `xq` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'mark')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `mark` text;");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'xingming')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `xingming` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `mobile` varchar(20);");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'haoma')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `haoma` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'status')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `status` int(1);");
	}	
}
if(pdo_tableexists('xc_lv_firm')) {
	if(!pdo_fieldexists('xc_lv_firm',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_firm')." ADD `openid` varchar(200);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'pcate')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `pcate` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'ccate')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `ccate` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'type')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `type` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'status')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `status` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `displayorder` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'title')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `thumb` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'unit')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `unit` varchar(5) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'description')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `description` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'content')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `content` text NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'goodssn')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `goodssn` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'productsn')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `productsn` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'marketprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `marketprice` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'productprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `productprice` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'costprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `costprice` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'originalprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `originalprice` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'total')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `total` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'totalcnf')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `totalcnf` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'sales')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `sales` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'spec')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `spec` varchar(5000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'weight')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `weight` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'credit')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `credit` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'maxbuy')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `maxbuy` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'usermaxbuy')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `usermaxbuy` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'hasoption')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `hasoption` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'dispatch')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `dispatch` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'thumb_url')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `thumb_url` text;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'isnew')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `isnew` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'ishot')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `ishot` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'isdiscount')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `isdiscount` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'isrecommand')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `isrecommand` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'istime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `istime` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'timestart')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `timestart` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'timeend')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `timeend` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'viewcount')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `viewcount` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'deleted')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `deleted` tinyint(3) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'starttime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `starttime` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'endtime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `endtime` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'xingcheng')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `xingcheng` text;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'time2')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `time2` varchar(500);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'xingji')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `xingji` int(10);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'detime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `detime` varchar(5000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'zhi')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `zhi` text NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'location')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `location` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_goods')) {
	if(!pdo_fieldexists('xc_lv_goods',  'han')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods')." ADD `han` text NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `goodsid` int(10);");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'title')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `title` varchar(50);");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `thumb` varchar(60);");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'productprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `productprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'marketprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `marketprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'costprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `costprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'stock')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `stock` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'weight')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `weight` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_goods_option')) {
	if(!pdo_fieldexists('xc_lv_goods_option',  'specs')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_option')." ADD `specs` text;");
	}	
}
if(pdo_tableexists('xc_lv_goods_param')) {
	if(!pdo_fieldexists('xc_lv_goods_param',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_param')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_goods_param')) {
	if(!pdo_fieldexists('xc_lv_goods_param',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_param')." ADD `goodsid` int(10);");
	}	
}
if(pdo_tableexists('xc_lv_goods_param')) {
	if(!pdo_fieldexists('xc_lv_goods_param',  'title')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_param')." ADD `title` varchar(50);");
	}	
}
if(pdo_tableexists('xc_lv_goods_param')) {
	if(!pdo_fieldexists('xc_lv_goods_param',  'value')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_param')." ADD `value` text;");
	}	
}
if(pdo_tableexists('xc_lv_goods_param')) {
	if(!pdo_fieldexists('xc_lv_goods_param',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_goods_param')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `uid` int(50) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'username')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `username` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `mobile` varchar(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'zipcode')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `zipcode` varchar(6) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'province')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `province` varchar(32) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'city')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `city` varchar(32) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'district')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `district` varchar(32) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'address')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `address` varchar(512) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_member_address')) {
	if(!pdo_fieldexists('xc_lv_member_address',  'isdefault')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_member_address')." ADD `isdefault` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'from_user')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `from_user` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'ordersn')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `ordersn` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'price')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `price` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'status')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `status` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'sendtype')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `sendtype` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'paytype')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `paytype` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'transid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `transid` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'goodstype')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `goodstype` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'remark')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `remark` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'address')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `address` varchar(1024) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'expresscom')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `expresscom` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'expresssn')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `expresssn` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'express')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `express` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'goodsprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `goodsprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'dispatchprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `dispatchprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'dispatch')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `dispatch` int(10);");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'paydetail')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `paydetail` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `createtime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order')) {
	if(!pdo_fieldexists('xc_lv_order',  'retime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order')." ADD `retime` datetime;");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'orderid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `orderid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `goodsid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'price')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `price` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'total')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `total` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'optionid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `optionid` int(10);");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'optionname')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `optionname` text;");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'ettotal')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `ettotal` int(10);");
	}	
}
if(pdo_tableexists('xc_lv_order_goods')) {
	if(!pdo_fieldexists('xc_lv_order_goods',  'productprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_order_goods')." ADD `productprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('xc_lv_product')) {
	if(!pdo_fieldexists('xc_lv_product',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_product')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_product')) {
	if(!pdo_fieldexists('xc_lv_product',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_product')." ADD `goodsid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_product')) {
	if(!pdo_fieldexists('xc_lv_product',  'productsn')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_product')." ADD `productsn` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_product')) {
	if(!pdo_fieldexists('xc_lv_product',  'title')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_product')." ADD `title` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_product')) {
	if(!pdo_fieldexists('xc_lv_product',  'marketprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_product')." ADD `marketprice` decimal(10,0) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_product')) {
	if(!pdo_fieldexists('xc_lv_product',  'productprice')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_product')." ADD `productprice` decimal(10,0) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_product')) {
	if(!pdo_fieldexists('xc_lv_product',  'total')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_product')." ADD `total` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_product')) {
	if(!pdo_fieldexists('xc_lv_product',  'status')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_product')." ADD `status` tinyint(3) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_product')) {
	if(!pdo_fieldexists('xc_lv_product',  'spec')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_product')." ADD `spec` varchar(5000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_spec')) {
	if(!pdo_fieldexists('xc_lv_spec',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_spec')) {
	if(!pdo_fieldexists('xc_lv_spec',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_spec')) {
	if(!pdo_fieldexists('xc_lv_spec',  'title')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec')." ADD `title` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_spec')) {
	if(!pdo_fieldexists('xc_lv_spec',  'description')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec')." ADD `description` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_spec')) {
	if(!pdo_fieldexists('xc_lv_spec',  'displaytype')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec')." ADD `displaytype` tinyint(3) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_spec')) {
	if(!pdo_fieldexists('xc_lv_spec',  'content')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec')." ADD `content` text NOT NULL;");
	}	
}
if(pdo_tableexists('xc_lv_spec')) {
	if(!pdo_fieldexists('xc_lv_spec',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec')." ADD `goodsid` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_spec')) {
	if(!pdo_fieldexists('xc_lv_spec',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_spec_item')) {
	if(!pdo_fieldexists('xc_lv_spec_item',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec_item')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xc_lv_spec_item')) {
	if(!pdo_fieldexists('xc_lv_spec_item',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec_item')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_spec_item')) {
	if(!pdo_fieldexists('xc_lv_spec_item',  'specid')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec_item')." ADD `specid` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_spec_item')) {
	if(!pdo_fieldexists('xc_lv_spec_item',  'title')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec_item')." ADD `title` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_spec_item')) {
	if(!pdo_fieldexists('xc_lv_spec_item',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec_item')." ADD `thumb` varchar(255);");
	}	
}
if(pdo_tableexists('xc_lv_spec_item')) {
	if(!pdo_fieldexists('xc_lv_spec_item',  'show')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec_item')." ADD `show` int(11);");
	}	
}
if(pdo_tableexists('xc_lv_spec_item')) {
	if(!pdo_fieldexists('xc_lv_spec_item',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('xc_lv_spec_item')." ADD `displayorder` int(11);");
	}	
}
