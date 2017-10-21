<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_wz_tuan_address` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`openid` varchar(300) NOT NULL,
`cname` varchar(30) NOT NULL,
`tel` varchar(20) NOT NULL,
`province` varchar(20) NOT NULL,
`city` varchar(20) NOT NULL,
`county` varchar(20) NOT NULL,
`detailed_address` varchar(225) NOT NULL,
`uniacid` int(10) NOT NULL,
`addtime` varchar(45) NOT NULL,
`status` int(2) NOT NULL,
`type` int(2) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_admin` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`username` varchar(30) NOT NULL,
`password` varchar(20) NOT NULL,
`email` varchar(60) NOT NULL,
`tel` varchar(20) NOT NULL,
`uniacid` int(10),
`openid` varchar(100),
PRIMARY KEY (`id`),
UNIQUE KEY `username` (`username`),
UNIQUE KEY `openid` (`openid`),
UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_adv` (
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

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_arealimit` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`enabled` int(11) NOT NULL,
`arealimitname` varchar(56) NOT NULL,
`areas` text NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`thumb` varchar(255) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`isrecommand` int(10),
`description` varchar(500) NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`enabled` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_collect` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`openid` varchar(200) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_dispatch` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`dispatchname` varchar(50),
`dispatchtype` int(11),
`displayorder` int(11),
`firstprice` decimal(10,2),
`secondprice` decimal(10,2),
`firstweight` int(11),
`secondweight` int(11),
`express` varchar(250),
`areas` text,
`carriers` text,
`enabled` int(11),
PRIMARY KEY (`id`),
KEY `idx_uniacid` (`uniacid`),
KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`gname` varchar(225) NOT NULL,
`fk_typeid` int(10) unsigned NOT NULL,
`gsn` varchar(50) NOT NULL,
`gnum` int(10) unsigned NOT NULL,
`groupnum` int(10) unsigned NOT NULL,
`mprice` decimal(10,2) NOT NULL,
`gprice` decimal(10,2) NOT NULL,
`oprice` decimal(10,2) NOT NULL,
`freight` decimal(10,2) NOT NULL,
`gdesc` longtext NOT NULL,
`gimg` varchar(225),
`isshow` tinyint(4) NOT NULL,
`salenum` int(10) unsigned NOT NULL,
`ishot` tinyint(4) NOT NULL,
`displayorder` int(11) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
`uniacid` int(10) NOT NULL,
`endtime` int(11) NOT NULL,
`yunfei_id` int(11) NOT NULL,
`is_discount` int(11) NOT NULL,
`credits` int(11) NOT NULL,
`is_hexiao` int(2) NOT NULL,
`hexiao_id` varchar(225) NOT NULL,
`is_share` int(2) NOT NULL,
`gdetaile` longtext NOT NULL,
`isnew` int(11) NOT NULL,
`isrecommand` int(11) NOT NULL,
`isdiscount` int(11) NOT NULL,
`hasoption` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_atlas` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`g_id` int(11) NOT NULL,
`thumb` varchar(145) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_imgs` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`fk_gid` int(10) NOT NULL,
`albumpath` varchar(225) NOT NULL,
`uniacid` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `fk_gid` (`fk_gid`),
UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_option` (
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

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_param` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`goodsid` int(10),
`title` varchar(50),
`value` text,
`displayorder` int(11),
PRIMARY KEY (`id`),
KEY `indx_goodsid` (`goodsid`),
KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_goods_type` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`cname` varchar(30) NOT NULL,
`pid` int(10),
`uniacid` int(10),
PRIMARY KEY (`id`),
UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_group` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`groupnumber` varchar(115) NOT NULL,
`goodsid` int(11) NOT NULL,
`goodsname` varchar(1024) NOT NULL,
`groupstatus` int(11) NOT NULL,
`neednum` int(11) NOT NULL,
`lacknum` int(11) NOT NULL,
`starttime` varchar(225) NOT NULL,
`endtime` varchar(225) NOT NULL,
`uniacid` int(11) NOT NULL,
`grouptype` int(11) NOT NULL,
`isshare` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_member` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`openid` varchar(100) NOT NULL,
`nickname` varchar(50) NOT NULL,
`avatar` varchar(255) NOT NULL,
`tag` varchar(1000) NOT NULL,
`mobile` varchar(20) NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_order` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` varchar(45) NOT NULL,
`gnum` int(11) NOT NULL,
`openid` varchar(45) NOT NULL,
`ptime` varchar(45) NOT NULL,
`orderno` varchar(45) NOT NULL,
`price` varchar(45) NOT NULL,
`status` int(9) NOT NULL,
`addressid` int(11) NOT NULL,
`g_id` int(11) NOT NULL,
`tuan_id` int(11) NOT NULL,
`is_tuan` int(2) NOT NULL,
`createtime` varchar(45) NOT NULL,
`pay_type` int(4) NOT NULL,
`starttime` varchar(45) NOT NULL,
`endtime` int(45) NOT NULL,
`tuan_first` int(11) NOT NULL,
`express` varchar(50),
`expresssn` varchar(50),
`transid` varchar(50) NOT NULL,
`remark` varchar(100) NOT NULL,
`success` int(11) NOT NULL,
`addname` varchar(50) NOT NULL,
`mobile` varchar(50) NOT NULL,
`address` varchar(300) NOT NULL,
`goodsprice` varchar(45) NOT NULL,
`pay_price` varchar(45) NOT NULL,
`freight` varchar(45) NOT NULL,
`credits` int(11) NOT NULL,
`is_usecard` int(11) NOT NULL,
`is_hexiao` int(2) NOT NULL,
`hexiaoma` varchar(50) NOT NULL,
`veropenid` varchar(200) NOT NULL,
`sendtime` varchar(115) NOT NULL,
`gettime` varchar(115) NOT NULL,
`addresstype` int(11) NOT NULL,
`optionid` int(11) NOT NULL,
`checkpay` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_order_goods` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`fk_orderid` int(10) NOT NULL,
`fk_goodid` int(10) NOT NULL,
`uniacid` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `fk_orderid` (`fk_orderid`),
UNIQUE KEY `fk_goodid` (`fk_goodid`),
UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_order_print` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`sid` int(10) NOT NULL,
`pid` int(3) NOT NULL,
`oid` int(10) NOT NULL,
`foid` varchar(50) NOT NULL,
`status` int(3) NOT NULL,
`addtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_print` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`sid` int(10) NOT NULL,
`name` varchar(45) NOT NULL,
`print_no` varchar(50) NOT NULL,
`key` varchar(50) NOT NULL,
`member_code` varchar(50) NOT NULL,
`print_nums` int(3) NOT NULL,
`qrcode_link` varchar(100) NOT NULL,
`status` int(3) NOT NULL,
`mode` int(3) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_refund_record` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`type` int(11) NOT NULL,
`goodsid` int(11) NOT NULL,
`payfee` varchar(100) NOT NULL,
`refundfee` varchar(100) NOT NULL,
`transid` varchar(115) NOT NULL,
`refund_id` varchar(115) NOT NULL,
`refundername` varchar(100) NOT NULL,
`refundermobile` varchar(100) NOT NULL,
`goodsname` varchar(100) NOT NULL,
`createtime` varchar(45) NOT NULL,
`status` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`orderid` varchar(45) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_rules` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`rulesname` varchar(40) NOT NULL,
`rulesdetail` varchar(4000),
`uniacid` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `rulesname` (`rulesname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_saler` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`storeid` varchar(225),
`uniacid` int(11),
`openid` varchar(255),
`nickname` varchar(145) NOT NULL,
`avatar` varchar(225) NOT NULL,
`status` tinyint(3),
PRIMARY KEY (`id`),
KEY `idx_storeid` (`storeid`),
KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_spec` (
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

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_spec_item` (
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

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_store` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11),
`storename` varchar(255),
`address` varchar(255),
`tel` varchar(255),
`lat` varchar(255),
`lng` varchar(255),
`status` tinyint(3),
`createtime` varchar(45) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_uniacid` (`uniacid`),
KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_user` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uid` int(11) NOT NULL,
`uniacid` int(11) NOT NULL,
`title` varchar(45) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_wz_tuan_users` (
`id` int(10) NOT NULL,
`username` varchar(30) NOT NULL,
`password` varchar(20) NOT NULL,
`email` varchar(60) NOT NULL,
`tel` varchar(20) NOT NULL,
`uniacid` int(10) NOT NULL,
`openid` varchar(100) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `username` (`username`),
UNIQUE KEY `uniacid` (`uniacid`),
UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `openid` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'cname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `cname` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `tel` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'province')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `province` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'city')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `city` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'county')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `county` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'detailed_address')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `detailed_address` varchar(225) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'addtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `addtime` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'status')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `status` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_address')) {
	if(!pdo_fieldexists('wz_tuan_address',  'type')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_address')." ADD `type` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_admin')) {
	if(!pdo_fieldexists('wz_tuan_admin',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_admin')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_admin')) {
	if(!pdo_fieldexists('wz_tuan_admin',  'username')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_admin')." ADD `username` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_admin')) {
	if(!pdo_fieldexists('wz_tuan_admin',  'password')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_admin')." ADD `password` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_admin')) {
	if(!pdo_fieldexists('wz_tuan_admin',  'email')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_admin')." ADD `email` varchar(60) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_admin')) {
	if(!pdo_fieldexists('wz_tuan_admin',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_admin')." ADD `tel` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_admin')) {
	if(!pdo_fieldexists('wz_tuan_admin',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_admin')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('wz_tuan_admin')) {
	if(!pdo_fieldexists('wz_tuan_admin',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_admin')." ADD `openid` varchar(100);");
	}	
}
if(pdo_tableexists('wz_tuan_adv')) {
	if(!pdo_fieldexists('wz_tuan_adv',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_adv')) {
	if(!pdo_fieldexists('wz_tuan_adv',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_adv')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_adv')) {
	if(!pdo_fieldexists('wz_tuan_adv',  'advname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_adv')." ADD `advname` varchar(50);");
	}	
}
if(pdo_tableexists('wz_tuan_adv')) {
	if(!pdo_fieldexists('wz_tuan_adv',  'link')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_adv')." ADD `link` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_adv')) {
	if(!pdo_fieldexists('wz_tuan_adv',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_adv')." ADD `thumb` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_adv')) {
	if(!pdo_fieldexists('wz_tuan_adv',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_adv')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_adv')) {
	if(!pdo_fieldexists('wz_tuan_adv',  'enabled')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_adv')." ADD `enabled` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_arealimit')) {
	if(!pdo_fieldexists('wz_tuan_arealimit',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_arealimit')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_arealimit')) {
	if(!pdo_fieldexists('wz_tuan_arealimit',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_arealimit')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_arealimit')) {
	if(!pdo_fieldexists('wz_tuan_arealimit',  'enabled')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_arealimit')." ADD `enabled` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_arealimit')) {
	if(!pdo_fieldexists('wz_tuan_arealimit',  'arealimitname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_arealimit')." ADD `arealimitname` varchar(56) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_arealimit')) {
	if(!pdo_fieldexists('wz_tuan_arealimit',  'areas')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_arealimit')." ADD `areas` text NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_category')) {
	if(!pdo_fieldexists('wz_tuan_category',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_category')) {
	if(!pdo_fieldexists('wz_tuan_category',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_category')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_category')) {
	if(!pdo_fieldexists('wz_tuan_category',  'name')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_category')." ADD `name` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_category')) {
	if(!pdo_fieldexists('wz_tuan_category',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_category')." ADD `thumb` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_category')) {
	if(!pdo_fieldexists('wz_tuan_category',  'parentid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_category')." ADD `parentid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_category')) {
	if(!pdo_fieldexists('wz_tuan_category',  'isrecommand')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_category')." ADD `isrecommand` int(10);");
	}	
}
if(pdo_tableexists('wz_tuan_category')) {
	if(!pdo_fieldexists('wz_tuan_category',  'description')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_category')." ADD `description` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_category')) {
	if(!pdo_fieldexists('wz_tuan_category',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_category')) {
	if(!pdo_fieldexists('wz_tuan_category',  'enabled')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_category')." ADD `enabled` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_collect')) {
	if(!pdo_fieldexists('wz_tuan_collect',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_collect')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_collect')) {
	if(!pdo_fieldexists('wz_tuan_collect',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_collect')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_collect')) {
	if(!pdo_fieldexists('wz_tuan_collect',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_collect')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_collect')) {
	if(!pdo_fieldexists('wz_tuan_collect',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_collect')." ADD `openid` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `uniacid` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'dispatchname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `dispatchname` varchar(50);");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'dispatchtype')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `dispatchtype` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'firstprice')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `firstprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'secondprice')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `secondprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'firstweight')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `firstweight` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'secondweight')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `secondweight` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'express')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `express` varchar(250);");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'areas')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `areas` text;");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'carriers')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `carriers` text;");
	}	
}
if(pdo_tableexists('wz_tuan_dispatch')) {
	if(!pdo_fieldexists('wz_tuan_dispatch',  'enabled')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_dispatch')." ADD `enabled` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'gname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `gname` varchar(225) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'fk_typeid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `fk_typeid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'gsn')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `gsn` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'gnum')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `gnum` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'groupnum')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `groupnum` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'mprice')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `mprice` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'gprice')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `gprice` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'oprice')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `oprice` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'freight')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `freight` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'gdesc')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `gdesc` longtext NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'gimg')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `gimg` varchar(225);");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'isshow')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `isshow` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'salenum')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `salenum` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'ishot')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `ishot` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `displayorder` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'endtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `endtime` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'yunfei_id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `yunfei_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'is_discount')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `is_discount` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'credits')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `credits` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'is_hexiao')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `is_hexiao` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'hexiao_id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `hexiao_id` varchar(225) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'is_share')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `is_share` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'gdetaile')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `gdetaile` longtext NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'isnew')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `isnew` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'isrecommand')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `isrecommand` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'isdiscount')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `isdiscount` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods')) {
	if(!pdo_fieldexists('wz_tuan_goods',  'hasoption')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods')." ADD `hasoption` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_atlas')) {
	if(!pdo_fieldexists('wz_tuan_goods_atlas',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_atlas')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_atlas')) {
	if(!pdo_fieldexists('wz_tuan_goods_atlas',  'g_id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_atlas')." ADD `g_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_atlas')) {
	if(!pdo_fieldexists('wz_tuan_goods_atlas',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_atlas')." ADD `thumb` varchar(145) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_imgs')) {
	if(!pdo_fieldexists('wz_tuan_goods_imgs',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_imgs')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_imgs')) {
	if(!pdo_fieldexists('wz_tuan_goods_imgs',  'fk_gid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_imgs')." ADD `fk_gid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_imgs')) {
	if(!pdo_fieldexists('wz_tuan_goods_imgs',  'albumpath')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_imgs')." ADD `albumpath` varchar(225) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_imgs')) {
	if(!pdo_fieldexists('wz_tuan_goods_imgs',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_imgs')." ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `goodsid` int(10);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'title')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `title` varchar(50);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `thumb` varchar(60);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'productprice')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `productprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'marketprice')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `marketprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'costprice')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `costprice` decimal(10,2);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'stock')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `stock` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'weight')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `weight` decimal(10,2);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_option')) {
	if(!pdo_fieldexists('wz_tuan_goods_option',  'specs')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_option')." ADD `specs` text;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_param')) {
	if(!pdo_fieldexists('wz_tuan_goods_param',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_param')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_param')) {
	if(!pdo_fieldexists('wz_tuan_goods_param',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_param')." ADD `goodsid` int(10);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_param')) {
	if(!pdo_fieldexists('wz_tuan_goods_param',  'title')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_param')." ADD `title` varchar(50);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_param')) {
	if(!pdo_fieldexists('wz_tuan_goods_param',  'value')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_param')." ADD `value` text;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_param')) {
	if(!pdo_fieldexists('wz_tuan_goods_param',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_param')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_type')) {
	if(!pdo_fieldexists('wz_tuan_goods_type',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_type')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_type')) {
	if(!pdo_fieldexists('wz_tuan_goods_type',  'cname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_type')." ADD `cname` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_goods_type')) {
	if(!pdo_fieldexists('wz_tuan_goods_type',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_type')." ADD `pid` int(10);");
	}	
}
if(pdo_tableexists('wz_tuan_goods_type')) {
	if(!pdo_fieldexists('wz_tuan_goods_type',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_goods_type')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'groupnumber')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `groupnumber` varchar(115) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `goodsid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'goodsname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `goodsname` varchar(1024) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'groupstatus')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `groupstatus` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'neednum')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `neednum` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'lacknum')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `lacknum` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'starttime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `starttime` varchar(225) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'endtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `endtime` varchar(225) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'grouptype')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `grouptype` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_group')) {
	if(!pdo_fieldexists('wz_tuan_group',  'isshare')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_group')." ADD `isshare` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_member')) {
	if(!pdo_fieldexists('wz_tuan_member',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_member')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_member')) {
	if(!pdo_fieldexists('wz_tuan_member',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_member')." ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_member')) {
	if(!pdo_fieldexists('wz_tuan_member',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_member')." ADD `openid` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_member')) {
	if(!pdo_fieldexists('wz_tuan_member',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_member')." ADD `nickname` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_member')) {
	if(!pdo_fieldexists('wz_tuan_member',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_member')." ADD `avatar` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_member')) {
	if(!pdo_fieldexists('wz_tuan_member',  'tag')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_member')." ADD `tag` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_member')) {
	if(!pdo_fieldexists('wz_tuan_member',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_member')." ADD `mobile` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `uniacid` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'gnum')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `gnum` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `openid` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'ptime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `ptime` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'orderno')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `orderno` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'price')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `price` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'status')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `status` int(9) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'addressid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `addressid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'g_id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `g_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'tuan_id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `tuan_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'is_tuan')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `is_tuan` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `createtime` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'pay_type')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `pay_type` int(4) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'starttime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `starttime` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'endtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `endtime` int(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'tuan_first')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `tuan_first` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'express')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `express` varchar(50);");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'expresssn')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `expresssn` varchar(50);");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'transid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `transid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'remark')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `remark` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'success')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `success` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'addname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `addname` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `mobile` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'address')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `address` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'goodsprice')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `goodsprice` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'pay_price')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `pay_price` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'freight')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `freight` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'credits')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `credits` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'is_usecard')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `is_usecard` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'is_hexiao')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `is_hexiao` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'hexiaoma')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `hexiaoma` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'veropenid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `veropenid` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'sendtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `sendtime` varchar(115) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'gettime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `gettime` varchar(115) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'addresstype')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `addresstype` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'optionid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `optionid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order')) {
	if(!pdo_fieldexists('wz_tuan_order',  'checkpay')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order')." ADD `checkpay` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_goods')) {
	if(!pdo_fieldexists('wz_tuan_order_goods',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_goods')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_order_goods')) {
	if(!pdo_fieldexists('wz_tuan_order_goods',  'fk_orderid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_goods')." ADD `fk_orderid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_goods')) {
	if(!pdo_fieldexists('wz_tuan_order_goods',  'fk_goodid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_goods')." ADD `fk_goodid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_goods')) {
	if(!pdo_fieldexists('wz_tuan_order_goods',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_goods')." ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_print')) {
	if(!pdo_fieldexists('wz_tuan_order_print',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_print')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_order_print')) {
	if(!pdo_fieldexists('wz_tuan_order_print',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_print')." ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_print')) {
	if(!pdo_fieldexists('wz_tuan_order_print',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_print')." ADD `sid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_print')) {
	if(!pdo_fieldexists('wz_tuan_order_print',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_print')." ADD `pid` int(3) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_print')) {
	if(!pdo_fieldexists('wz_tuan_order_print',  'oid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_print')." ADD `oid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_print')) {
	if(!pdo_fieldexists('wz_tuan_order_print',  'foid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_print')." ADD `foid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_print')) {
	if(!pdo_fieldexists('wz_tuan_order_print',  'status')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_print')." ADD `status` int(3) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_order_print')) {
	if(!pdo_fieldexists('wz_tuan_order_print',  'addtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_order_print')." ADD `addtime` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `sid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'name')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `name` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'print_no')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `print_no` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'key')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `key` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'member_code')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `member_code` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'print_nums')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `print_nums` int(3) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'qrcode_link')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `qrcode_link` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'status')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `status` int(3) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_print')) {
	if(!pdo_fieldexists('wz_tuan_print',  'mode')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_print')." ADD `mode` int(3) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'type')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `type` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `goodsid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'payfee')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `payfee` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'refundfee')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `refundfee` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'transid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `transid` varchar(115) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'refund_id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `refund_id` varchar(115) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'refundername')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `refundername` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'refundermobile')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `refundermobile` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'goodsname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `goodsname` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `createtime` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'status')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `status` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_refund_record')) {
	if(!pdo_fieldexists('wz_tuan_refund_record',  'orderid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_refund_record')." ADD `orderid` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_rules')) {
	if(!pdo_fieldexists('wz_tuan_rules',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_rules')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_rules')) {
	if(!pdo_fieldexists('wz_tuan_rules',  'rulesname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_rules')." ADD `rulesname` varchar(40) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_rules')) {
	if(!pdo_fieldexists('wz_tuan_rules',  'rulesdetail')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_rules')." ADD `rulesdetail` varchar(4000);");
	}	
}
if(pdo_tableexists('wz_tuan_rules')) {
	if(!pdo_fieldexists('wz_tuan_rules',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_rules')." ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_saler')) {
	if(!pdo_fieldexists('wz_tuan_saler',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_saler')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_saler')) {
	if(!pdo_fieldexists('wz_tuan_saler',  'storeid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_saler')." ADD `storeid` varchar(225);");
	}	
}
if(pdo_tableexists('wz_tuan_saler')) {
	if(!pdo_fieldexists('wz_tuan_saler',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_saler')." ADD `uniacid` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_saler')) {
	if(!pdo_fieldexists('wz_tuan_saler',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_saler')." ADD `openid` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_saler')) {
	if(!pdo_fieldexists('wz_tuan_saler',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_saler')." ADD `nickname` varchar(145) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_saler')) {
	if(!pdo_fieldexists('wz_tuan_saler',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_saler')." ADD `avatar` varchar(225) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_saler')) {
	if(!pdo_fieldexists('wz_tuan_saler',  'status')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_saler')." ADD `status` tinyint(3);");
	}	
}
if(pdo_tableexists('wz_tuan_spec')) {
	if(!pdo_fieldexists('wz_tuan_spec',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_spec')) {
	if(!pdo_fieldexists('wz_tuan_spec',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_spec')) {
	if(!pdo_fieldexists('wz_tuan_spec',  'title')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec')." ADD `title` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_spec')) {
	if(!pdo_fieldexists('wz_tuan_spec',  'description')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec')." ADD `description` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_spec')) {
	if(!pdo_fieldexists('wz_tuan_spec',  'displaytype')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec')." ADD `displaytype` tinyint(3) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_spec')) {
	if(!pdo_fieldexists('wz_tuan_spec',  'content')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec')." ADD `content` text NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_spec')) {
	if(!pdo_fieldexists('wz_tuan_spec',  'goodsid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec')." ADD `goodsid` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_spec')) {
	if(!pdo_fieldexists('wz_tuan_spec',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_spec_item')) {
	if(!pdo_fieldexists('wz_tuan_spec_item',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec_item')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_spec_item')) {
	if(!pdo_fieldexists('wz_tuan_spec_item',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec_item')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_spec_item')) {
	if(!pdo_fieldexists('wz_tuan_spec_item',  'specid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec_item')." ADD `specid` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_spec_item')) {
	if(!pdo_fieldexists('wz_tuan_spec_item',  'title')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec_item')." ADD `title` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_spec_item')) {
	if(!pdo_fieldexists('wz_tuan_spec_item',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec_item')." ADD `thumb` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_spec_item')) {
	if(!pdo_fieldexists('wz_tuan_spec_item',  'show')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec_item')." ADD `show` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_spec_item')) {
	if(!pdo_fieldexists('wz_tuan_spec_item',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_spec_item')." ADD `displayorder` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_store')) {
	if(!pdo_fieldexists('wz_tuan_store',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_store')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_store')) {
	if(!pdo_fieldexists('wz_tuan_store',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_store')." ADD `uniacid` int(11);");
	}	
}
if(pdo_tableexists('wz_tuan_store')) {
	if(!pdo_fieldexists('wz_tuan_store',  'storename')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_store')." ADD `storename` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_store')) {
	if(!pdo_fieldexists('wz_tuan_store',  'address')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_store')." ADD `address` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_store')) {
	if(!pdo_fieldexists('wz_tuan_store',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_store')." ADD `tel` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_store')) {
	if(!pdo_fieldexists('wz_tuan_store',  'lat')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_store')." ADD `lat` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_store')) {
	if(!pdo_fieldexists('wz_tuan_store',  'lng')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_store')." ADD `lng` varchar(255);");
	}	
}
if(pdo_tableexists('wz_tuan_store')) {
	if(!pdo_fieldexists('wz_tuan_store',  'status')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_store')." ADD `status` tinyint(3);");
	}	
}
if(pdo_tableexists('wz_tuan_store')) {
	if(!pdo_fieldexists('wz_tuan_store',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_store')." ADD `createtime` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_user')) {
	if(!pdo_fieldexists('wz_tuan_user',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_user')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('wz_tuan_user')) {
	if(!pdo_fieldexists('wz_tuan_user',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_user')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_user')) {
	if(!pdo_fieldexists('wz_tuan_user',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_user')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_user')) {
	if(!pdo_fieldexists('wz_tuan_user',  'title')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_user')." ADD `title` varchar(45) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_users')) {
	if(!pdo_fieldexists('wz_tuan_users',  'id')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_users')." ADD `id` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_users')) {
	if(!pdo_fieldexists('wz_tuan_users',  'username')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_users')." ADD `username` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_users')) {
	if(!pdo_fieldexists('wz_tuan_users',  'password')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_users')." ADD `password` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_users')) {
	if(!pdo_fieldexists('wz_tuan_users',  'email')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_users')." ADD `email` varchar(60) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_users')) {
	if(!pdo_fieldexists('wz_tuan_users',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_users')." ADD `tel` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_users')) {
	if(!pdo_fieldexists('wz_tuan_users',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_users')." ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('wz_tuan_users')) {
	if(!pdo_fieldexists('wz_tuan_users',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('wz_tuan_users')." ADD `openid` varchar(100) NOT NULL;");
	}	
}
