<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_addcard` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `pcate` int(10) NOT NULL COMMENT '店铺ID',
  `uniacid` int(11) DEFAULT '0',
  `buynum` varchar(255) NOT NULL COMMENT '店铺名称',
  `cardname` varchar(255) NOT NULL COMMENT '卡券名称',
  `cardnum` int(10) NOT NULL COMMENT '卡券数量',
  `cardid` varchar(255) NOT NULL COMMENT '微信卡券ID',
  `cardprize` decimal(10,2) NOT NULL DEFAULT '0.00',
  `most_num_times` int(11) DEFAULT '0',
  `today_most_times` int(11) DEFAULT '0',
  `getendtime` int(10) DEFAULT '0',
  `getstarttime` int(10) DEFAULT '0',
  `isstartusing` int(10) unsigned NOT NULL COMMENT '是否启用',
  `createtime` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_cardticket` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `createtime` varchar(20) DEFAULT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `bianhao` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_feedbackid` (`feedbackid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `sales_num` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `goodssn` varchar(100) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `total` int(10) unsigned NOT NULL DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `spec` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `maxbuy` int(11) DEFAULT '0',
  `usermaxbuy` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最多购买数量',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` int(11) DEFAULT '0',
  `ishot` int(11) DEFAULT '0',
  `isdiscount` int(11) DEFAULT '0',
  `isrecommand` int(11) DEFAULT '0',
  `istime` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_hexiaoaward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `category` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `pwid` varchar(50) NOT NULL DEFAULT '' COMMENT '卡密标示符',
  `pwsn` varchar(100) NOT NULL DEFAULT '' COMMENT '商品编码',
  `title` varchar(35) DEFAULT NULL COMMENT '卡密',
  `used_cardid` varchar(50) DEFAULT NULL COMMENT '卡号',
  `createtime` int(10) DEFAULT '0',
  `orderid` varchar(50) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_hexiaopeople` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `category` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `tandid` varchar(50) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `statuss` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` varchar(300) NOT NULL,
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `paydetail` varchar(255) NOT NULL COMMENT '支付详情',
  `createtime` int(10) unsigned NOT NULL,
  `paytime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` varchar(50) NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `pici` varchar(10) NOT NULL COMMENT '批次',
  `pwid` varchar(100) NOT NULL DEFAULT '' COMMENT '卡密标示符',
  `title` varchar(35) DEFAULT NULL COMMENT '卡密',
  `used_cardid` varchar(50) DEFAULT NULL COMMENT '卡号',
  `cardid` varchar(50) DEFAULT NULL COMMENT '卡ID',
  `category` varchar(50) DEFAULT NULL COMMENT '分类ID',
  `optionname` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_pici` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `pici` int(10) DEFAULT '0' COMMENT '批次',
  `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称',
  `codenum` int(11) DEFAULT '0' COMMENT '口令数量',
  `is_qrcode` varchar(100) DEFAULT '0' COMMENT '是否生成二维码',
  `is_qrcode2` int(11) DEFAULT '0' COMMENT '是否生成永久二维码',
  `createtime` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_pici` (`pici`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_pw` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `pici` int(10) NOT NULL COMMENT '批次',
  `pwid` varchar(50) NOT NULL DEFAULT '' COMMENT '卡密标示符',
  `pwsn` varchar(100) NOT NULL DEFAULT '' COMMENT '商品编码',
  `title` varchar(35) DEFAULT NULL COMMENT '卡密',
  `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称',
  `iscqr` int(11) DEFAULT '0' COMMENT '是否生成二维码',
  `isused` int(11) DEFAULT '0' COMMENT '是否被使用',
  `used_cardid` varchar(50) DEFAULT NULL COMMENT '卡号',
  `endtime` int(10) DEFAULT '0',
  `used_times` int(10) DEFAULT '0',
  `activation_time` int(10) DEFAULT '0' COMMENT '激活时间',
  `num` int(10) DEFAULT '0',
  `category` varchar(50) DEFAULT NULL COMMENT '分类ID',
  `status` tinyint(1) DEFAULT '0',
  `ishexiao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为不需要，1为需要',
  `bianhao` int(10) DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_pwid` (`pwid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_haoman_virtuamall_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `rid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `pcate` int(10) NOT NULL COMMENT '店铺ID';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'buynum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `buynum` varchar(255) NOT NULL COMMENT '店铺名称';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'cardname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `cardname` varchar(255) NOT NULL COMMENT '卡券名称';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'cardnum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `cardnum` int(10) NOT NULL COMMENT '卡券数量';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'cardid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `cardid` varchar(255) NOT NULL COMMENT '微信卡券ID';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'cardprize')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `cardprize` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'most_num_times')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `most_num_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'today_most_times')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `today_most_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'getendtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `getendtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'getstarttime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `getstarttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'isstartusing')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `isstartusing` int(10) unsigned NOT NULL COMMENT '是否启用';");
}
if(!pdo_fieldexists('haoman_virtuamall_addcard',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD `createtime` varchar(20) DEFAULT NULL;");
}
if(!pdo_indexexists('haoman_virtuamall_addcard',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_virtuamall_addcard',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_addcard')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `realname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'province')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `province` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'city')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `city` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'area')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `area` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'address')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `address` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'isdefault')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_address',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_address')." ADD `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_adv',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_adv',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_adv',  'link')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD `link` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_adv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_adv',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_virtuamall_adv',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('haoman_virtuamall_adv',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('haoman_virtuamall_adv',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_adv')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('haoman_virtuamall_cardticket',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cardticket')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_cardticket',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cardticket')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_cardticket',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cardticket')." ADD `createtime` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_cardticket',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cardticket')." ADD `ticket` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_cart',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cart')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_cart',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cart')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_cart',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cart')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_cart',  'goodstype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cart')." ADD `goodstype` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('haoman_virtuamall_cart',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cart')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_cart',  'total')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cart')." ADD `total` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_cart',  'optionid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cart')." ADD `optionid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_cart',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cart')." ADD `marketprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_indexexists('haoman_virtuamall_cart',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_cart')." ADD KEY `idx_openid` (`from_user`);");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `thumb` varchar(255) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `isrecommand` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'bianhao')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `bianhao` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `description` varchar(500) NOT NULL COMMENT '分类介绍';");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('haoman_virtuamall_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_category')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'dispatchname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `dispatchname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'dispatchtype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `dispatchtype` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'firstprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `firstprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'secondprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `secondprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'firstweight')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `firstweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'secondweight')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `secondweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'express')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `express` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_dispatch',  'description')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD `description` text;");
}
if(!pdo_indexexists('haoman_virtuamall_dispatch',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('haoman_virtuamall_dispatch',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_dispatch')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('haoman_virtuamall_express',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_express')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_express',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_express')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_express',  'express_name')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_express')." ADD `express_name` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_express',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_express')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_express',  'express_price')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_express')." ADD `express_price` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_express',  'express_area')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_express')." ADD `express_area` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_express',  'express_url')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_express')." ADD `express_url` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('haoman_virtuamall_express',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_express')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('haoman_virtuamall_express',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_express')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'type')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎';");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝';");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'feedbackid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号';");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `transid` varchar(30) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'reason')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `reason` varchar(1000) NOT NULL COMMENT '理由';");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'solution')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `solution` varchar(1000) NOT NULL COMMENT '期待解决方案';");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `remark` varchar(1000) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('haoman_virtuamall_feedback',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('haoman_virtuamall_feedback',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('haoman_virtuamall_feedback',  'idx_feedbackid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD KEY `idx_feedbackid` (`feedbackid`);");
}
if(!pdo_indexexists('haoman_virtuamall_feedback',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_indexexists('haoman_virtuamall_feedback',  'idx_transid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_feedback')." ADD KEY `idx_transid` (`transid`);");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'sales_num')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `sales_num` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'type')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `unit` varchar(5) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'description')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `description` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'content')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'goodssn')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `goodssn` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'productsn')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `productsn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `productprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'costprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `costprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'originalprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `total` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'totalcnf')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `sales` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'spec')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `spec` varchar(5000) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'weight')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `weight` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `credit` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'maxbuy')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `maxbuy` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'usermaxbuy')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `usermaxbuy` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最多购买数量';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'hasoption')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `hasoption` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `dispatch` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `thumb_url` text;");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'isnew')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `isnew` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'ishot')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `ishot` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'isdiscount')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `isdiscount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `isrecommand` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'istime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `istime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'timestart')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `timestart` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'timeend')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `timeend` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'viewcount')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `viewcount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods')." ADD `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `goodsid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `thumb` varchar(60) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `productprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `marketprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'costprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `costprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'stock')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `stock` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'weight')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `weight` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_option',  'specs')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD `specs` text;");
}
if(!pdo_indexexists('haoman_virtuamall_goods_option',  'indx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD KEY `indx_goodsid` (`goodsid`);");
}
if(!pdo_indexexists('haoman_virtuamall_goods_option',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_option')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_param',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_param')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_param',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_param')." ADD `goodsid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_param',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_param')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_param',  'value')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_param')." ADD `value` text;");
}
if(!pdo_fieldexists('haoman_virtuamall_goods_param',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_param')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_virtuamall_goods_param',  'indx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_param')." ADD KEY `indx_goodsid` (`goodsid`);");
}
if(!pdo_indexexists('haoman_virtuamall_goods_param',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_goods_param')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `realname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'category')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `category` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'pwid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `pwid` varchar(50) NOT NULL DEFAULT '' COMMENT '卡密标示符';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'pwsn')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `pwsn` varchar(100) NOT NULL DEFAULT '' COMMENT '商品编码';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `title` varchar(35) DEFAULT NULL COMMENT '卡密';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'used_cardid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `used_cardid` varchar(50) DEFAULT NULL COMMENT '卡号';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaoaward',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaoaward')." ADD `orderid` varchar(50) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `avatar` varchar(255) DEFAULT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `realname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'category')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `category` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_hexiaopeople',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_hexiaopeople')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `ordersn` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'tandid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `tandid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `price` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'statuss')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `statuss` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0普通状态，1为已付款，2为已发货，3为成功';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'sendtype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'goodstype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `remark` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'addressid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `addressid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `realname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `address` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'expresscom')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `expresscom` varchar(30) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'expresssn')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `expresssn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'express')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `express` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'goodsprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `goodsprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `dispatchprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `dispatch` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'paydetail')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `paydetail` varchar(255) NOT NULL COMMENT '支付详情';");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order')." ADD `paytime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `orderid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `goodsid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'price')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `total` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'optionid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `optionid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'pici')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `pici` varchar(10) NOT NULL COMMENT '批次';");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'pwid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `pwid` varchar(100) NOT NULL DEFAULT '' COMMENT '卡密标示符';");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `title` varchar(35) DEFAULT NULL COMMENT '卡密';");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'used_cardid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `used_cardid` varchar(50) DEFAULT NULL COMMENT '卡号';");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'cardid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `cardid` varchar(50) DEFAULT NULL COMMENT '卡ID';");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'category')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `category` varchar(50) DEFAULT NULL COMMENT '分类ID';");
}
if(!pdo_fieldexists('haoman_virtuamall_order_goods',  'optionname')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_order_goods')." ADD `optionname` text;");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'pici')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `pici` int(10) DEFAULT '0' COMMENT '批次';");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'rulename')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称';");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'codenum')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `codenum` int(11) DEFAULT '0' COMMENT '口令数量';");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'is_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `is_qrcode` varchar(100) DEFAULT '0' COMMENT '是否生成二维码';");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'is_qrcode2')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `is_qrcode2` int(11) DEFAULT '0' COMMENT '是否生成永久二维码';");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_pici',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_virtuamall_pici',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_virtuamall_pici',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('haoman_virtuamall_pici',  'indx_pici')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pici')." ADD KEY `indx_pici` (`pici`);");
}
if(!pdo_fieldexists('haoman_virtuamall_product',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_product',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_product',  'productsn')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD `productsn` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_product',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD `title` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_product',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD `marketprice` decimal(10,0) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_product',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD `productprice` decimal(10,0) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_product',  'total')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD `total` int(11) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_product',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('haoman_virtuamall_product',  'spec')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD `spec` varchar(5000) NOT NULL;");
}
if(!pdo_indexexists('haoman_virtuamall_product',  'idx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_product')." ADD KEY `idx_goodsid` (`goodsid`);");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `uniacid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'pici')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `pici` int(10) NOT NULL COMMENT '批次';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'pwid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `pwid` varchar(50) NOT NULL DEFAULT '' COMMENT '卡密标示符';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'pwsn')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `pwsn` varchar(100) NOT NULL DEFAULT '' COMMENT '商品编码';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `title` varchar(35) DEFAULT NULL COMMENT '卡密';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'rulename')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `rulename` varchar(50) DEFAULT NULL COMMENT '适用规则名称';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'iscqr')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `iscqr` int(11) DEFAULT '0' COMMENT '是否生成二维码';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'isused')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `isused` int(11) DEFAULT '0' COMMENT '是否被使用';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'used_cardid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `used_cardid` varchar(50) DEFAULT NULL COMMENT '卡号';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'used_times')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `used_times` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'activation_time')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `activation_time` int(10) DEFAULT '0' COMMENT '激活时间';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'num')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `num` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'category')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `category` varchar(50) DEFAULT NULL COMMENT '分类ID';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'status')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'ishexiao')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `ishexiao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为不需要，1为需要';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'bianhao')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `bianhao` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_pw',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD `createtime` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('haoman_virtuamall_pw',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('haoman_virtuamall_pw',  'indx_pwid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_pw')." ADD KEY `indx_pwid` (`pwid`);");
}
if(!pdo_fieldexists('haoman_virtuamall_spec',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_spec',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_spec',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_spec',  'description')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_spec',  'displaytype')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec')." ADD `displaytype` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_spec',  'content')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('haoman_virtuamall_spec',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec')." ADD `goodsid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_spec',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_spec_item',  'id')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('haoman_virtuamall_spec_item',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_spec_item',  'specid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD `specid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_spec_item',  'title')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_spec_item',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('haoman_virtuamall_spec_item',  'show')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD `show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('haoman_virtuamall_spec_item',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('haoman_virtuamall_spec_item',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('haoman_virtuamall_spec_item',  'indx_specid')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD KEY `indx_specid` (`specid`);");
}
if(!pdo_indexexists('haoman_virtuamall_spec_item',  'indx_show')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD KEY `indx_show` (`show`);");
}
if(!pdo_indexexists('haoman_virtuamall_spec_item',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('haoman_virtuamall_spec_item')." ADD KEY `indx_displayorder` (`displayorder`);");
}

?>