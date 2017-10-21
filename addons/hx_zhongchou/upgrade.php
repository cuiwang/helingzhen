<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_address` (
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
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_adv` (
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
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `projectid` int(11) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_dispatch` (
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
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_express` (
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
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_feedback` (
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
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `cateid` int(10) unsigned DEFAULT NULL,
  `content` text,
  `source` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `displayorder` int(10) unsigned DEFAULT NULL,
  `click` int(10) unsigned DEFAULT NULL,
  `is_display` tinyint(1) unsigned DEFAULT NULL,
  `createtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_news_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `displayorder` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `return_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `item_price` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_order_ws` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `nickname` varchar(20) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `ordersn` int(10) unsigned DEFAULT NULL,
  `price` decimal(10,2) unsigned DEFAULT NULL,
  `paytype` varchar(10) DEFAULT NULL,
  `transid` varchar(20) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `pid` int(10) unsigned DEFAULT NULL,
  `createtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `limit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `donenum` int(10) unsigned NOT NULL DEFAULT '0',
  `finish_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `starttime` int(10) unsigned DEFAULT '0',
  `deal_days` int(10) unsigned NOT NULL,
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isrecommand` tinyint(1) unsigned DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `video` varchar(255) NOT NULL DEFAULT '',
  `brief` varchar(1000) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `nosubuser` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0暂停1正常2停止',
  `reason` varchar(255) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `subsurl` varchar(500) DEFAULT NULL,
  `show_type` int(10) DEFAULT '0',
  `type` tinyint(10) DEFAULT '0',
  `lianxiren` varchar(20) DEFAULT '',
  `qq` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_project_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` varchar(2000) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `limit_num` int(10) unsigned NOT NULL,
  `donenum` int(10) unsigned NOT NULL DEFAULT '0',
  `repaid_day` int(10) unsigned NOT NULL,
  `return_type` tinyint(1) unsigned NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `dispatch` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('hx_zhongchou_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `realname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'province')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `province` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'city')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `city` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'area')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `area` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'address')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `address` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'isdefault')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_address',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_address')." ADD `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_adv',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_adv',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_adv',  'link')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD `link` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_adv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_adv',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('hx_zhongchou_adv',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('hx_zhongchou_adv',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('hx_zhongchou_adv',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_adv')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('hx_zhongchou_cart',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_cart')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_cart',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_cart')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_cart',  'projectid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_cart')." ADD `projectid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_cart',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_cart')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_indexexists('hx_zhongchou_cart',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_cart')." ADD KEY `idx_openid` (`from_user`);");
}
if(!pdo_fieldexists('hx_zhongchou_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_category')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('hx_zhongchou_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('hx_zhongchou_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_category')." ADD `thumb` varchar(255) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists('hx_zhongchou_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('hx_zhongchou_category',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_category')." ADD `isrecommand` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_category')." ADD `description` varchar(500) NOT NULL COMMENT '分类介绍';");
}
if(!pdo_fieldexists('hx_zhongchou_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('hx_zhongchou_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_category')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'dispatchname')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `dispatchname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'dispatchtype')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `dispatchtype` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'firstprice')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `firstprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'secondprice')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `secondprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'firstweight')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `firstweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'secondweight')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `secondweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'express')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `express` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'description')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `description` text;");
}
if(!pdo_fieldexists('hx_zhongchou_dispatch',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_indexexists('hx_zhongchou_dispatch',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('hx_zhongchou_dispatch',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('hx_zhongchou_express',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_express')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_express',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_express')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_express',  'express_name')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_express')." ADD `express_name` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_express',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_express')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_express',  'express_price')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_express')." ADD `express_price` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_express',  'express_area')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_express')." ADD `express_area` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_express',  'express_url')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_express')." ADD `express_url` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('hx_zhongchou_express',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_express')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('hx_zhongchou_express',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_express')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'type')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎';");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝';");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'feedbackid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号';");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `transid` varchar(30) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'reason')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `reason` varchar(1000) NOT NULL COMMENT '理由';");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'solution')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `solution` varchar(1000) NOT NULL COMMENT '期待解决方案';");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `remark` varchar(1000) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('hx_zhongchou_feedback',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('hx_zhongchou_feedback',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('hx_zhongchou_feedback',  'idx_feedbackid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD KEY `idx_feedbackid` (`feedbackid`);");
}
if(!pdo_indexexists('hx_zhongchou_feedback',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_indexexists('hx_zhongchou_feedback',  'idx_transid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_feedback')." ADD KEY `idx_transid` (`transid`);");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `title` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `thumb` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'cateid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `cateid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'content')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `content` text;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'source')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `source` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'author')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `author` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `displayorder` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'click')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `click` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'is_display')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `is_display` tinyint(1) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news')." ADD `createtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_news_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news_category')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news_category',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news_category')." ADD `title` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_news_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_news_category')." ADD `displayorder` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `ordersn` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `pid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'item_id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `item_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `price` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'sendtype')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'return_type')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `return_type` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `remark` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'addressid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `addressid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'expresscom')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `expresscom` varchar(30) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'expresssn')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `expresssn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'express')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `express` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'item_price')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `item_price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `dispatchprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `dispatch` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `weid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `from_user` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `nickname` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `avatar` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `ordersn` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'price')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `price` decimal(10,2) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `paytype` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `transid` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `status` tinyint(1) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `remark` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `pid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD `createtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `from_user` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'limit_price')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `limit_price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'donenum')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `donenum` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'finish_price')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `finish_price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `starttime` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'deal_days')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `deal_days` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'ishot')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `isrecommand` tinyint(1) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'video')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `video` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'brief')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `brief` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'url')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'content')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'nosubuser')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `nosubuser` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0暂停1正常2停止';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'reason')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `reason` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'subsurl')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `subsurl` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'show_type')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `show_type` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'type')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `type` tinyint(10) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'lianxiren')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `lianxiren` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_project',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD `qq` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `displayorder` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `pid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'price')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'description')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `description` varchar(2000) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'limit_num')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `limit_num` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'donenum')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `donenum` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'repaid_day')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `repaid_day` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'return_type')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `return_type` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'delivery_fee')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `delivery_fee` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `dispatch` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD `createtime` int(10) unsigned NOT NULL;");
}

?>