<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_supermanfc_cash_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `applypay` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remark` varchar(500) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `cashorderno` varchar(50) NOT NULL DEFAULT '',
  `paymentno` varchar(100) NOT NULL DEFAULT '',
  `reason` varchar(500) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_commission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00',
  `commission_get_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `commission_unget_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_commission_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `ordid` int(11) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `partnerid` int(11) NOT NULL DEFAULT '0' COMMENT '经纪人id',
  `recommendpid` int(11) NOT NULL DEFAULT '0',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
  `houseid` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘id',
  `laststatusid` int(11) NOT NULL DEFAULT '0',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态,-1关闭,0正常',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_mobile` (`mobile`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_mobile` (`mobile`),
  KEY `indx_houseid` (`houseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客户表';
CREATE TABLE IF NOT EXISTS `ims_supermanfc_customer_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='客户状态表';
CREATE TABLE IF NOT EXISTS `ims_supermanfc_customer_trace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `customerid` int(11) NOT NULL DEFAULT '0' COMMENT '客户id',
  `statusid` int(11) NOT NULL DEFAULT '0' COMMENT '客户状态id',
  `partnerid` int(11) NOT NULL DEFAULT '0' COMMENT '经纪人id',
  `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_cspid` (`customerid`,`statusid`) USING BTREE,
  KEY `indx_uniacid` (`uniacid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='客户状态跟踪表';
CREATE TABLE IF NOT EXISTS `ims_supermanfc_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `cid` int(11) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deposit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `phone` varchar(32) NOT NULL DEFAULT '',
  `selleraddress` varchar(512) NOT NULL DEFAULT '',
  `address` varchar(512) NOT NULL DEFAULT '',
  `province` varchar(50) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `district` varchar(50) NOT NULL DEFAULT '',
  `opentime` int(10) unsigned NOT NULL DEFAULT '0',
  `preferential` varchar(255) NOT NULL DEFAULT '',
  `hotmsg` varchar(255) NOT NULL DEFAULT '',
  `credit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit_type` varchar(10) NOT NULL DEFAULT '',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00',
  `new_commission` varchar(255) NOT NULL DEFAULT '',
  `longitude` varchar(255) NOT NULL DEFAULT '',
  `latitude` varchar(255) NOT NULL DEFAULT '',
  `geohash` varchar(45) NOT NULL DEFAULT '',
  `coverimg` varchar(255) NOT NULL DEFAULT '',
  `descimgs` mediumtext,
  `description` mediumtext,
  `dynamicdesc` mediumtext,
  `nearby` mediumtext,
  `pricetype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `specialtype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `housetype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `layouttype` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sharecount` int(10) unsigned NOT NULL DEFAULT '0',
  `commentcount` int(10) unsigned NOT NULL DEFAULT '0',
  `viewcount` int(10) unsigned NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `recommend` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_house_bespeak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `houseid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL DEFAULT '',
  `bespeaktime` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` mediumtext,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `phone` varchar(20) NOT NULL,
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_houseid` (`houseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_house_kv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `houseid` int(11) NOT NULL DEFAULT '0',
  `key` varchar(512) NOT NULL DEFAULT '',
  `value` varchar(512) NOT NULL DEFAULT '',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_houseid` (`houseid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_house_order` (
  `ordid` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `orderno` varchar(50) NOT NULL DEFAULT '',
  `houseid` int(11) NOT NULL DEFAULT '0',
  `paytype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:余额支付,2:在线支付,3:线下支付',
  `transid` varchar(30) NOT NULL DEFAULT '' COMMENT '微信支付',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `paydetail` varchar(500) NOT NULL DEFAULT '',
  `paytime` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ordid`),
  UNIQUE KEY `uniq_indx_orderno` (`orderno`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_house_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `house_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '楼盘id',
  `friend_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '好友会员id 0为游客',
  `credit_type` varchar(10) NOT NULL DEFAULT '' COMMENT '积分类型',
  `credit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '消费积分',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT 'ip',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_house_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:特色 2:类型',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `houseid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(256) NOT NULL DEFAULT '',
  `img` varchar(512) NOT NULL DEFAULT '',
  `area` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tag` varchar(256) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_looking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `slide` text,
  `viewtime` int(10) unsigned NOT NULL DEFAULT '0',
  `regdeadline` int(10) unsigned NOT NULL DEFAULT '0',
  `longitude` varchar(255) NOT NULL DEFAULT '',
  `latitude` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `contact` varchar(32) NOT NULL DEFAULT '',
  `gatheraddress` varchar(500) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `remark` mediumtext,
  `displayorder` int(11) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_looking_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `lookid` int(11) NOT NULL DEFAULT '0',
  `houseid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `indx_lookid` (`lookid`,`houseid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_looking_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `lookid` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `message` mediumtext,
  `fellows` tinyint(4) NOT NULL DEFAULT '0',
  `likehouse` mediumtext,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_lookid` (`lookid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_navigation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '图标',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(500) NOT NULL DEFAULT '' COMMENT '链接',
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示 1显示 0不显示',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_new_commission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remark` varchar(500) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT 'tidtype对应id值',
  `tidtype` varchar(20) NOT NULL DEFAULT '' COMMENT 'ordid/customerid',
  `paymentno` varchar(100) NOT NULL DEFAULT '',
  `reason` varchar(500) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  `payment_no` varchar(100) NOT NULL DEFAULT '',
  `order_no` varchar(50) NOT NULL DEFAULT '',
  `message` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_partner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `subuid` int(10) unsigned NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `customer_total` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `roleid` int(11) NOT NULL DEFAULT '0',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `realname` varchar(50) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `invite_qrcode` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `indx_uid` (`uid`,`subuid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_partner_house_ref` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `partnerid` int(11) NOT NULL DEFAULT '0' COMMENT '经纪人id',
  `houseid` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_phid` (`partnerid`,`houseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='楼盘项目经理关系表';
CREATE TABLE IF NOT EXISTS `ims_supermanfc_partner_rel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `partnerid` int(10) unsigned NOT NULL DEFAULT '0',
  `subpartnerid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `indx_partnerid` (`partnerid`,`subpartnerid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_supermanfc_partner_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `isadmin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '管理权限(0:否 1:是)',
  `issubadmin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '子管理权限(0:否 1:是)',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='经纪人身份类型表';
CREATE TABLE IF NOT EXISTS `ims_supermanfc_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id',
  `daytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '日期',
  `house_views` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘浏览数',
  `house_shares` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘分享数',
  `house_comments` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘评论数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `indx_uniacid` (`uniacid`,`daytime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('supermanfc_cash_apply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'user_id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `user_id` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `from_user` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'applypay')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `applypay` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `remark` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `status` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'cashorderno')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `cashorderno` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'paymentno')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `paymentno` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'reason')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `reason` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_cash_apply',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD `updatetime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_cash_apply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('supermanfc_cash_apply',  'indx_uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_cash_apply')." ADD KEY `indx_uid` (`uid`);");
}
if(!pdo_fieldexists('supermanfc_commission',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_commission',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_commission',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_commission',  'commission')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission')." ADD `commission` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_commission',  'commission_get_total')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission')." ADD `commission_get_total` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_commission',  'commission_unget_total')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission')." ADD `commission_unget_total` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_commission',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_commission',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('supermanfc_commission',  'indx_uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission')." ADD KEY `indx_uid` (`uid`);");
}
if(!pdo_fieldexists('supermanfc_commission_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_commission_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_commission_log',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_commission_log',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD `fee` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_commission_log',  'status')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD `status` tinyint(4) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('supermanfc_commission_log',  'ordid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD `ordid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_commission_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_commission_log',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD `updatetime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_commission_log',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('supermanfc_commission_log',  'indx_uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_commission_log')." ADD KEY `indx_uid` (`uid`);");
}
if(!pdo_fieldexists('supermanfc_customer',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_customer',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('supermanfc_customer',  'partnerid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `partnerid` int(11) NOT NULL DEFAULT '0' COMMENT '经纪人id';");
}
if(!pdo_fieldexists('supermanfc_customer',  'recommendpid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `recommendpid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_customer',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名';");
}
if(!pdo_fieldexists('supermanfc_customer',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机';");
}
if(!pdo_fieldexists('supermanfc_customer',  'houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `houseid` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘id';");
}
if(!pdo_fieldexists('supermanfc_customer',  'laststatusid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `laststatusid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_customer',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists('supermanfc_customer',  'status')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态,-1关闭,0正常';");
}
if(!pdo_fieldexists('supermanfc_customer',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳';");
}
if(!pdo_indexexists('supermanfc_customer',  'uniq_mobile')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD UNIQUE KEY `uniq_mobile` (`mobile`);");
}
if(!pdo_indexexists('supermanfc_customer',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('supermanfc_customer',  'indx_mobile')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD KEY `indx_mobile` (`mobile`);");
}
if(!pdo_indexexists('supermanfc_customer',  'indx_houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer')." ADD KEY `indx_houseid` (`houseid`);");
}
if(!pdo_fieldexists('supermanfc_customer_status',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_status')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_customer_status',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_status')." ADD `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('supermanfc_customer_status',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_status')." ADD `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_customer_status',  'title')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_status')." ADD `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称';");
}
if(!pdo_indexexists('supermanfc_customer_status',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_status')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_customer_trace',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_customer_trace',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_customer_trace',  'customerid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD `customerid` int(11) NOT NULL DEFAULT '0' COMMENT '客户id';");
}
if(!pdo_fieldexists('supermanfc_customer_trace',  'statusid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD `statusid` int(11) NOT NULL DEFAULT '0' COMMENT '客户状态id';");
}
if(!pdo_fieldexists('supermanfc_customer_trace',  'partnerid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD `partnerid` int(11) NOT NULL DEFAULT '0' COMMENT '经纪人id';");
}
if(!pdo_fieldexists('supermanfc_customer_trace',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD `remark` varchar(1000) NOT NULL DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists('supermanfc_customer_trace',  'money')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_customer_trace',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳';");
}
if(!pdo_indexexists('supermanfc_customer_trace',  'uniq_cspid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD UNIQUE KEY `uniq_cspid` (`customerid`,`statusid`) USING BTREE;");
}
if(!pdo_indexexists('supermanfc_customer_trace',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_customer_trace')." ADD KEY `indx_uniacid` (`uniacid`) USING BTREE;");
}
if(!pdo_fieldexists('supermanfc_house',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_house',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'name')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `name` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `cid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'price')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_house',  'deposit')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `deposit` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_house',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `phone` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'selleraddress')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `selleraddress` varchar(512) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'address')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `address` varchar(512) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'province')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `province` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'city')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `city` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'district')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `district` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'opentime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `opentime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'preferential')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `preferential` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'hotmsg')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `hotmsg` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `credit` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_house',  'credit_type')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `credit_type` varchar(10) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'commission')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `commission` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_house',  'new_commission')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `new_commission` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'longitude')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `longitude` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'latitude')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `latitude` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'geohash')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `geohash` varchar(45) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'coverimg')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `coverimg` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house',  'descimgs')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `descimgs` mediumtext;");
}
if(!pdo_fieldexists('supermanfc_house',  'description')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `description` mediumtext;");
}
if(!pdo_fieldexists('supermanfc_house',  'dynamicdesc')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `dynamicdesc` mediumtext;");
}
if(!pdo_fieldexists('supermanfc_house',  'nearby')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `nearby` mediumtext;");
}
if(!pdo_fieldexists('supermanfc_house',  'pricetype')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `pricetype` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'specialtype')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `specialtype` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'housetype')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `housetype` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'layouttype')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `layouttype` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'sharecount')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `sharecount` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'commentcount')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `commentcount` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'viewcount')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `viewcount` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house',  'recommend')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD `recommend` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_indexexists('supermanfc_house',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `houseid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'username')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `username` varchar(64) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'bespeaktime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `bespeaktime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `remark` mediumtext;");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'status')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `status` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `phone` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('supermanfc_house_bespeak',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD `updatetime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_house_bespeak',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('supermanfc_house_bespeak',  'indx_houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_bespeak')." ADD KEY `indx_houseid` (`houseid`);");
}
if(!pdo_fieldexists('supermanfc_house_kv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_kv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_house_kv',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_kv')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_kv',  'houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_kv')." ADD `houseid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_kv',  'key')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_kv')." ADD `key` varchar(512) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house_kv',  'value')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_kv')." ADD `value` varchar(512) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house_kv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_kv')." ADD `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_house_kv',  'indx_houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_kv')." ADD KEY `indx_houseid` (`houseid`);");
}
if(!pdo_fieldexists('supermanfc_house_order',  'ordid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `ordid` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_house_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'orderno')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `orderno` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `houseid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `paytype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:余额支付,2:在线支付,3:线下支付';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `transid` varchar(30) NOT NULL DEFAULT '' COMMENT '微信支付';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `amount` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `realname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `mobile` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `remark` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'paydetail')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `paydetail` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `paytime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_house_order',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_house_order',  'uniq_indx_orderno')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD UNIQUE KEY `uniq_indx_orderno` (`orderno`);");
}
if(!pdo_indexexists('supermanfc_house_order',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_order')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_house_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_house_share',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('supermanfc_house_share',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id';");
}
if(!pdo_fieldexists('supermanfc_house_share',  'house_id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD `house_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '楼盘id';");
}
if(!pdo_fieldexists('supermanfc_house_share',  'friend_uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD `friend_uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '好友会员id 0为游客';");
}
if(!pdo_fieldexists('supermanfc_house_share',  'credit_type')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD `credit_type` varchar(10) NOT NULL DEFAULT '' COMMENT '积分类型';");
}
if(!pdo_fieldexists('supermanfc_house_share',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD `credit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '消费积分';");
}
if(!pdo_fieldexists('supermanfc_house_share',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD `ip` char(15) NOT NULL DEFAULT '' COMMENT 'ip';");
}
if(!pdo_fieldexists('supermanfc_house_share',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间戳';");
}
if(!pdo_indexexists('supermanfc_house_share',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_share')." ADD KEY `indx_uniacid` (`uniacid`,`uid`);");
}
if(!pdo_fieldexists('supermanfc_house_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_type')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_house_type',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_type')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('supermanfc_house_type',  'type')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_type')." ADD `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1:特色 2:类型';");
}
if(!pdo_fieldexists('supermanfc_house_type',  'title')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_type')." ADD `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists('supermanfc_house_type',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_type')." ADD `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示';");
}
if(!pdo_fieldexists('supermanfc_house_type',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_type')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_indexexists('supermanfc_house_type',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_house_type')." ADD KEY `indx_uniacid` (`uniacid`,`type`);");
}
if(!pdo_fieldexists('supermanfc_layout',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_layout')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_layout',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_layout')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_layout',  'houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_layout')." ADD `houseid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_layout',  'name')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_layout')." ADD `name` varchar(256) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_layout',  'img')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_layout')." ADD `img` varchar(512) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_layout',  'area')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_layout')." ADD `area` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_layout',  'tag')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_layout')." ADD `tag` varchar(256) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_layout',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_layout')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_layout',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_layout')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_looking',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_looking',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking',  'name')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `name` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_looking',  'slide')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `slide` text;");
}
if(!pdo_fieldexists('supermanfc_looking',  'viewtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `viewtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking',  'regdeadline')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `regdeadline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking',  'longitude')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `longitude` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_looking',  'latitude')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `latitude` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_looking',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `phone` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_looking',  'contact')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `contact` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_looking',  'gatheraddress')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `gatheraddress` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_looking',  'status')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `status` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `remark` mediumtext;");
}
if(!pdo_fieldexists('supermanfc_looking',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `displayorder` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_looking',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_looking_house',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_house')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_looking_house',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_house')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking_house',  'lookid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_house')." ADD `lookid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking_house',  'houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_house')." ADD `houseid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_looking_house',  'indx_lookid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_house')." ADD UNIQUE KEY `indx_lookid` (`lookid`,`houseid`);");
}
if(!pdo_indexexists('supermanfc_looking_house',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_house')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'lookid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `lookid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'username')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `username` varchar(64) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `phone` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'message')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `message` mediumtext;");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'fellows')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `fellows` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'likehouse')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `likehouse` mediumtext;");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'status')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `status` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_looking_users',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD `updatetime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_looking_users',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('supermanfc_looking_users',  'indx_lookid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_looking_users')." ADD KEY `indx_lookid` (`lookid`);");
}
if(!pdo_fieldexists('supermanfc_navigation',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_navigation')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_navigation',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_navigation')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('supermanfc_navigation',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_navigation')." ADD `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '图标';");
}
if(!pdo_fieldexists('supermanfc_navigation',  'title')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_navigation')." ADD `title` varchar(100) NOT NULL DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists('supermanfc_navigation',  'url')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_navigation')." ADD `url` varchar(500) NOT NULL DEFAULT '' COMMENT '链接';");
}
if(!pdo_fieldexists('supermanfc_navigation',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_navigation')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('supermanfc_navigation',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_navigation')." ADD `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示 1显示 0不显示';");
}
if(!pdo_indexexists('supermanfc_navigation',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_navigation')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'user_id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `user_id` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'money')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `remark` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'status')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `status` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `tid` int(11) NOT NULL DEFAULT '0' COMMENT 'tidtype对应id值';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'tidtype')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `tidtype` varchar(20) NOT NULL DEFAULT '' COMMENT 'ordid/customerid';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'paymentno')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `paymentno` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'reason')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `reason` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `updatetime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'payment_no')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `payment_no` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'order_no')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `order_no` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_new_commission',  'message')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD `message` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('supermanfc_new_commission',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('supermanfc_new_commission',  'indx_uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_new_commission')." ADD KEY `indx_uid` (`uid`);");
}
if(!pdo_fieldexists('supermanfc_partner',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_partner',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner',  'subuid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `subuid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner',  'level')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `level` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner',  'customer_total')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `customer_total` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner',  'status')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `status` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner',  'roleid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `roleid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `phone` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_partner',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `realname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('supermanfc_partner',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner',  'invite_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD `invite_qrcode` varchar(64) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('supermanfc_partner',  'indx_uid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD UNIQUE KEY `indx_uid` (`uid`,`subuid`);");
}
if(!pdo_indexexists('supermanfc_partner',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_partner_house_ref',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_house_ref')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_partner_house_ref',  'partnerid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_house_ref')." ADD `partnerid` int(11) NOT NULL DEFAULT '0' COMMENT '经纪人id';");
}
if(!pdo_fieldexists('supermanfc_partner_house_ref',  'houseid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_house_ref')." ADD `houseid` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘id';");
}
if(!pdo_indexexists('supermanfc_partner_house_ref',  'uniq_phid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_house_ref')." ADD UNIQUE KEY `uniq_phid` (`partnerid`,`houseid`);");
}
if(!pdo_fieldexists('supermanfc_partner_rel',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_rel')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_partner_rel',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_rel')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner_rel',  'partnerid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_rel')." ADD `partnerid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner_rel',  'subpartnerid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_rel')." ADD `subpartnerid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('supermanfc_partner_rel',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_rel')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('supermanfc_partner_rel',  'indx_partnerid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_rel')." ADD UNIQUE KEY `indx_partnerid` (`partnerid`,`subpartnerid`);");
}
if(!pdo_indexexists('supermanfc_partner_rel',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_rel')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_partner_role',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_role')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_partner_role',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_role')." ADD `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('supermanfc_partner_role',  'title')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_role')." ADD `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists('supermanfc_partner_role',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_role')." ADD `displayorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('supermanfc_partner_role',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_role')." ADD `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示';");
}
if(!pdo_fieldexists('supermanfc_partner_role',  'isadmin')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_role')." ADD `isadmin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '管理权限(0:否 1:是)';");
}
if(!pdo_fieldexists('supermanfc_partner_role',  'issubadmin')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_role')." ADD `issubadmin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '子管理权限(0:否 1:是)';");
}
if(!pdo_indexexists('supermanfc_partner_role',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_partner_role')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('supermanfc_stat',  'id')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_stat')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('supermanfc_stat',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_stat')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('supermanfc_stat',  'daytime')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_stat')." ADD `daytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '日期';");
}
if(!pdo_fieldexists('supermanfc_stat',  'house_views')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_stat')." ADD `house_views` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘浏览数';");
}
if(!pdo_fieldexists('supermanfc_stat',  'house_shares')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_stat')." ADD `house_shares` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘分享数';");
}
if(!pdo_fieldexists('supermanfc_stat',  'house_comments')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_stat')." ADD `house_comments` int(11) NOT NULL DEFAULT '0' COMMENT '楼盘评论数';");
}
if(!pdo_indexexists('supermanfc_stat',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('supermanfc_stat')." ADD UNIQUE KEY `indx_uniacid` (`uniacid`,`daytime`);");
}

?>