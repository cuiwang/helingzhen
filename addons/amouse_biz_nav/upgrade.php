<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_card_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `cardid` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0',
  `from_openid` varchar(255) DEFAULT '',
  `subcredit` int(11) DEFAULT '0',
  `submoney` decimal(10,2) DEFAULT '0.00',
  `reccredit` int(11) DEFAULT '0',
  `recmoney` decimal(10,2) DEFAULT '0.00',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_cardid` (`cardid`),
  FULLTEXT KEY `idx_from_openid` (`from_openid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '分类',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_fun` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `displayorder` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `link` varchar(200) NOT NULL COMMENT '链接',
  `icon` varchar(250) DEFAULT '' COMMENT '图标',
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `weid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `type` varchar(200) NOT NULL COMMENT '1个人微信，2群，3公众号',
  `openid` varchar(200) DEFAULT '' COMMENT '被推广人的openid',
  `fopenid` varchar(200) DEFAULT '' COMMENT '推广人的openid',
  `credit` int(11) DEFAULT '0',
  `pk` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`weid`),
  KEY `idx_openid` (`openid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='添加好友日志';
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_meal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '套餐名称',
  `img` varchar(250) DEFAULT '' COMMENT '二维码',
  `day` decimal(10,1) DEFAULT NULL,
  `auto` tinyint(1) DEFAULT '0' COMMENT '是否自动爆机',
  `price` decimal(10,1) DEFAULT NULL,
  `desc` varchar(40) DEFAULT '' COMMENT '描述',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) DEFAULT '1' COMMENT '套餐类型',
  `acid` int(11) DEFAULT '2',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='套餐价格';
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_money_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `fansid` int(11) NOT NULL,
  `level_first_id` int(11) DEFAULT '0',
  `level_second_id` int(11) DEFAULT '0',
  `level_three_id` int(11) DEFAULT '0',
  `credit` decimal(10,2) unsigned NOT NULL,
  `ipcilent` varchar(20) DEFAULT '',
  `module` varchar(100) NOT NULL DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `remark` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_fansid` (`fansid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `displayorder` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `link` varchar(200) NOT NULL COMMENT '链接',
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `weid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `openid` varchar(50) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称',
  `username` varchar(20) NOT NULL COMMENT '用户真实姓名',
  `mobile` varchar(20) NOT NULL COMMENT '手机',
  `address` varchar(50) NOT NULL COMMENT '地址',
  `province` varchar(50) NOT NULL COMMENT '省份',
  `city` varchar(50) NOT NULL COMMENT '城市',
  `dist` varchar(50) NOT NULL COMMENT '区',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `mealid` int(10) unsigned NOT NULL COMMENT '套餐ID',
  `num` int(10) unsigned NOT NULL COMMENT '数量',
  `mgid` int(10) unsigned DEFAULT '0' COMMENT '群ID',
  `memberid` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(255) DEFAULT '',
  `remark` varchar(255) DEFAULT '',
  `sendtime` int(11) DEFAULT '0',
  `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0已提交,1为已付款,2为已发货，3为成功',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额支付,2为支付宝,3为微信支付,4为定价返还',
  `transid` varchar(100) NOT NULL COMMENT '微信订单号',
  `price` decimal(10,2) DEFAULT NULL,
  `wxnotify` varchar(200) DEFAULT NULL,
  `notifytime` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL COMMENT '充值时间',
  `tid` varchar(128) NOT NULL,
  `plid` bigint(11) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_show_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `sets` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统产品设置';
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(100) DEFAULT '0' COMMENT '分类',
  `displayorder` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `link` varchar(200) NOT NULL COMMENT '链接',
  `img` varchar(250) DEFAULT '' COMMENT '图标',
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `weid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_amouse_biz_nav_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `sets` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统设置';
";
pdo_run($sql);
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'cardid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `cardid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `mid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'from_openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `from_openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'subcredit')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `subcredit` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'submoney')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `submoney` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'reccredit')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `reccredit` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'recmoney')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `recmoney` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('amouse_biz_nav_card_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('amouse_biz_nav_card_log',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('amouse_biz_nav_card_log',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('amouse_biz_nav_card_log',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_indexexists('amouse_biz_nav_card_log',  'idx_cardid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD KEY `idx_cardid` (`cardid`);");
}
if(!pdo_indexexists('amouse_biz_nav_card_log',  'idx_from_openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_card_log')." ADD FULLTEXT KEY `idx_from_openid` (`from_openid`);");
}
if(!pdo_fieldexists('amouse_biz_nav_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_category')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('amouse_biz_nav_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('amouse_biz_nav_category',  'type')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_category')." ADD `type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '分类';");
}
if(!pdo_fieldexists('amouse_biz_nav_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('amouse_biz_nav_category',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_category')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_fun',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_fun')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_fun',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_fun')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_fun',  'title')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_fun')." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_fun',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_fun')." ADD `displayorder` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('amouse_biz_nav_fun',  'link')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_fun')." ADD `link` varchar(200) NOT NULL COMMENT '链接';");
}
if(!pdo_fieldexists('amouse_biz_nav_fun',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_fun')." ADD `icon` varchar(250) DEFAULT '' COMMENT '图标';");
}
if(!pdo_fieldexists('amouse_biz_nav_fun',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_fun')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_indexexists('amouse_biz_nav_fun',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_fun')." ADD KEY `weid` (`uniacid`);");
}
if(!pdo_indexexists('amouse_biz_nav_fun',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_fun')." ADD KEY `status` (`status`);");
}
if(!pdo_fieldexists('amouse_biz_nav_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_log',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_log',  'type')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD `type` varchar(200) NOT NULL COMMENT '1个人微信，2群，3公众号';");
}
if(!pdo_fieldexists('amouse_biz_nav_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD `openid` varchar(200) DEFAULT '' COMMENT '被推广人的openid';");
}
if(!pdo_fieldexists('amouse_biz_nav_log',  'fopenid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD `fopenid` varchar(200) DEFAULT '' COMMENT '推广人的openid';");
}
if(!pdo_fieldexists('amouse_biz_nav_log',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD `credit` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_log',  'pk')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD `pk` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('amouse_biz_nav_log',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD KEY `idx_uniacid` (`weid`);");
}
if(!pdo_indexexists('amouse_biz_nav_log',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_log')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'title')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `title` varchar(100) NOT NULL COMMENT '套餐名称';");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'img')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `img` varchar(250) DEFAULT '' COMMENT '二维码';");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'day')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `day` decimal(10,1) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'auto')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `auto` tinyint(1) DEFAULT '0' COMMENT '是否自动爆机';");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'price')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `price` decimal(10,1) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `desc` varchar(40) DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'type')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `type` tinyint(1) DEFAULT '1' COMMENT '套餐类型';");
}
if(!pdo_fieldexists('amouse_biz_nav_meal',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD `acid` int(11) DEFAULT '2';");
}
if(!pdo_indexexists('amouse_biz_nav_meal',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_meal')." ADD KEY `idx_uniacid` (`weid`);");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `fansid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'level_first_id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `level_first_id` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'level_second_id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `level_second_id` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'level_three_id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `level_three_id` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `credit` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'ipcilent')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `ipcilent` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'module')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `module` varchar(100) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_money_record',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD `remark` varchar(100) NOT NULL;");
}
if(!pdo_indexexists('amouse_biz_nav_money_record',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('amouse_biz_nav_money_record',  'idx_fansid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_money_record')." ADD KEY `idx_fansid` (`fansid`);");
}
if(!pdo_fieldexists('amouse_biz_nav_notice',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_notice')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_notice',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_notice')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_notice',  'title')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_notice')." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_notice',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_notice')." ADD `displayorder` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('amouse_biz_nav_notice',  'link')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_notice')." ADD `link` varchar(200) NOT NULL COMMENT '链接';");
}
if(!pdo_fieldexists('amouse_biz_nav_notice',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_notice')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_notice',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_notice')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('amouse_biz_nav_notice',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_notice')." ADD KEY `weid` (`uniacid`);");
}
if(!pdo_indexexists('amouse_biz_nav_notice',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_notice')." ADD KEY `status` (`status`);");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `openid` varchar(50) NOT NULL COMMENT '微信会员openID';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `nickname` varchar(20) NOT NULL COMMENT '用户昵称';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'username')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `username` varchar(20) NOT NULL COMMENT '用户真实姓名';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `mobile` varchar(20) NOT NULL COMMENT '手机';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `address` varchar(50) NOT NULL COMMENT '地址';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'province')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `province` varchar(50) NOT NULL COMMENT '省份';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'city')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `city` varchar(50) NOT NULL COMMENT '城市';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `dist` varchar(50) NOT NULL COMMENT '区';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'mealid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `mealid` int(10) unsigned NOT NULL COMMENT '套餐ID';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'num')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `num` int(10) unsigned NOT NULL COMMENT '数量';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'mgid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `mgid` int(10) unsigned DEFAULT '0' COMMENT '群ID';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'memberid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `memberid` int(10) unsigned DEFAULT '0' COMMENT '会员ID';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `ordersn` varchar(20) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'expresscom')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `expresscom` varchar(30) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'expresssn')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `expresssn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'express')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `express` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `remark` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'sendtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `sendtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0已提交,1为已付款,2为已发货，3为成功';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额支付,2为支付宝,3为微信支付,4为定价返还';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `transid` varchar(100) NOT NULL COMMENT '微信订单号';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `price` decimal(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'wxnotify')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `wxnotify` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'notifytime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `notifytime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '充值时间';");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `tid` varchar(128) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'plid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `plid` bigint(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD `from_user` varchar(50) NOT NULL COMMENT '微信会员openID';");
}
if(!pdo_indexexists('amouse_biz_nav_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('amouse_biz_nav_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD KEY `openid` (`openid`);");
}
if(!pdo_indexexists('amouse_biz_nav_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_order')." ADD KEY `status` (`status`);");
}
if(!pdo_fieldexists('amouse_biz_nav_show_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_show_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_show_sysset',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_show_sysset')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_show_sysset',  'sets')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_show_sysset')." ADD `sets` text;");
}
if(!pdo_indexexists('amouse_biz_nav_show_sysset',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_show_sysset')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('amouse_biz_nav_slide',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_slide',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_slide',  'title')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD `title` varchar(100) DEFAULT '0' COMMENT '分类';");
}
if(!pdo_fieldexists('amouse_biz_nav_slide',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD `displayorder` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('amouse_biz_nav_slide',  'link')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD `link` varchar(200) NOT NULL COMMENT '链接';");
}
if(!pdo_fieldexists('amouse_biz_nav_slide',  'img')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD `img` varchar(250) DEFAULT '' COMMENT '图标';");
}
if(!pdo_fieldexists('amouse_biz_nav_slide',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_slide',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('amouse_biz_nav_slide',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD KEY `weid` (`uniacid`);");
}
if(!pdo_indexexists('amouse_biz_nav_slide',  'status')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_slide')." ADD KEY `status` (`status`);");
}
if(!pdo_fieldexists('amouse_biz_nav_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('amouse_biz_nav_sysset',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_sysset')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('amouse_biz_nav_sysset',  'sets')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_sysset')." ADD `sets` text;");
}
if(!pdo_indexexists('amouse_biz_nav_sysset',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('amouse_biz_nav_sysset')." ADD KEY `idx_uniacid` (`uniacid`);");
}

?>