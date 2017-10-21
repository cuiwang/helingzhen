<?php

$sql =<<<EOF
CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_goods` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`cardtype_id` int(11) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`unit` varchar(10) NOT NULL,
`thumb` varchar(100) NOT NULL,
`sn` int(11) unsigned NOT NULL,
`barcode` varchar(20) NOT NULL,
`marketprice` float(10,2) NOT NULL,
`seksillprice` float(10,2) NOT NULL,
`total` int(11) unsigned NOT NULL,
`totalcnf` int(1) unsigned NOT NULL,
`maxbuy` int(11) unsigned NOT NULL,
`usermaxbuy` int(11) unsigned NOT NULL,
`sales` int(11) unsigned NOT NULL,
`credit` int(11) unsigned,
`content` text NOT NULL,
`specifications` text,
`status` int(1) unsigned NOT NULL,
`miaosha_type` varchar(10),
`displayorder` int(11) unsigned NOT NULL,
`supplier` varchar(100),
`supplier_pass` varchar(100),
`since_address` varchar(150),
`since_phone` int(15) unsigned,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_order` (
`order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`order_no` varchar(150) NOT NULL,
`member` int(11) unsigned NOT NULL,
`goods_id` int(11) unsigned NOT NULL,
`stage_id` int(11) unsigned NOT NULL,
`goods_nature` varchar(150) NOT NULL,
`goods_number` int(11) unsigned NOT NULL,
`goods_seksillprice` float(10,2) unsigned NOT NULL,
`order_yunfei` float(10,2) unsigned DEFAULT '0.00',
`order_totalprice` float(10,2) unsigned NOT NULL,
`address` varchar(255),
`peis` varchar(255),
`order_time` int(20) unsigned NOT NULL,
`kd_no` varchar(255),
`delivery_time` int(20) unsigned,
`sign_ttime` int(20) unsigned,
`qx_ttime` int(20) unsigned,
`status` int(1) unsigned NOT NULL DEFAULT '1',
PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_peis` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`name` varchar(150) NOT NULL,
`status` int(1) unsigned NOT NULL DEFAULT '1',
`delivery_fee` float(10,2) NOT NULL DEFAULT '0.00',
`compositor` int(11) unsigned NOT NULL DEFAULT '0',
`peis_type` int(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_remind` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`userid` int(11) unsigned NOT NULL,
`stageid` int(11) unsigned NOT NULL,
`goodsid` int(11) unsigned NOT NULL,
`createtime` int(20) unsigned NOT NULL,
`status` int(1) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_stage` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`datetime` varchar(20) NOT NULL,
`timestart` varchar(20) NOT NULL,
`timeend` varchar(20) NOT NULL,
`goods` varchar(100) NOT NULL,
`status` int(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hetu_seckill_user` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`openid` varchar(100) NOT NULL,
`avatar` varchar(150) NOT NULL,
`phone` varchar(11) NOT NULL,
`nickname` varchar(50) NOT NULL,
`realname` varchar(50),
`sex` int(1) unsigned,
`address` text,
`status` int(1) unsigned NOT NULL DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF;
pdo_run($sql);
