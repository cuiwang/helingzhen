<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_auction_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_auction_category` (
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
CREATE TABLE IF NOT EXISTS `ims_auction_goodslist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `title` varchar(100) DEFAULT NULL COMMENT '商品标题',
  `categoryid` int(10) DEFAULT '0' COMMENT '拍品分类',
  `sh_price` int(10) DEFAULT '0' COMMENT '起拍金额',
  `add_price` int(10) DEFAULT '0' COMMENT '默认加价金额',
  `st_price` int(10) DEFAULT '0' COMMENT '成交金额',
  `bond` int(10) DEFAULT '0' COMMENT '保证金',
  `picarr` text COMMENT '商品图片',
  `content` mediumtext COMMENT '商品详情',
  `start_time` int(10) unsigned DEFAULT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned DEFAULT NULL COMMENT '结束时间',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `pos` tinyint(4) unsigned DEFAULT '0' COMMENT '出价次数',
  `status` int(11) NOT NULL COMMENT '1:已付余款',
  `g_status` int(11) NOT NULL COMMENT '2:上架；1：下架',
  `q_uid` varchar(10) DEFAULT NULL COMMENT '成交人昵称',
  `q_user` varchar(50) DEFAULT NULL COMMENT '成交人from_user',
  `send_state` int(4) unsigned NOT NULL COMMENT '1为已发货',
  `send` int(4) unsigned NOT NULL COMMENT '是否需要快递1为需要',
  `express` varchar(20) DEFAULT NULL COMMENT '快递公司',
  `expressn` char(20) DEFAULT NULL COMMENT '快递单',
  `send_time` char(20) DEFAULT NULL COMMENT '发货时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `send_state` (`send_state`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_auction_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id',
  `balance` int(10) unsigned NOT NULL COMMENT '会员余额',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `realname` varchar(10) NOT NULL COMMENT '真实姓名',
  `nickname` varchar(20) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `address` varchar(255) NOT NULL COMMENT '邮寄地址',
  `bankcard` varchar(20) NOT NULL,
  `bankname` varchar(10) NOT NULL,
  `alipay` varchar(30) NOT NULL,
  `aliname` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_auction_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0未支付,1为已付款',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额支付,2为支付宝,3为微信支付,4为定价返还',
  `transid` varchar(30) NOT NULL COMMENT '微信订单号',
  `price` int(10) unsigned NOT NULL COMMENT '充值金额',
  `createtime` int(10) unsigned NOT NULL COMMENT '充值时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `from_user` (`from_user`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_auction_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `sid` int(10) unsigned NOT NULL COMMENT '商品编号',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `price` int(10) unsigned NOT NULL COMMENT '交易价格',
  `bond` int(10) unsigned NOT NULL COMMENT '保证金',
  `createtime` int(10) unsigned NOT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_auction_withdrawals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `status` smallint(4) NOT NULL COMMENT '0为提现中,1为提现成功，2提现失败',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为银行卡,2为支付宝',
  `price` int(10) unsigned NOT NULL COMMENT '提现金额',
  `createtime` int(10) unsigned NOT NULL COMMENT '申请时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('auction_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('auction_adv',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('auction_adv',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('auction_adv',  'link')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD `link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('auction_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('auction_adv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('auction_adv',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('auction_adv',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('auction_adv',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('auction_adv',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('auction_adv')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('auction_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('auction_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('auction_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('auction_category')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('auction_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('auction_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('auction_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('auction_category')." ADD `thumb` varchar(255) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists('auction_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('auction_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('auction_category',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('auction_category')." ADD `isrecommand` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('auction_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('auction_category')." ADD `description` varchar(500) NOT NULL COMMENT '分类介绍';");
}
if(!pdo_fieldexists('auction_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('auction_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('auction_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('auction_category')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('auction_goodslist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('auction_goodslist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号';");
}
if(!pdo_fieldexists('auction_goodslist',  'title')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `title` varchar(100) DEFAULT NULL COMMENT '商品标题';");
}
if(!pdo_fieldexists('auction_goodslist',  'categoryid')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `categoryid` int(10) DEFAULT '0' COMMENT '拍品分类';");
}
if(!pdo_fieldexists('auction_goodslist',  'sh_price')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `sh_price` int(10) DEFAULT '0' COMMENT '起拍金额';");
}
if(!pdo_fieldexists('auction_goodslist',  'add_price')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `add_price` int(10) DEFAULT '0' COMMENT '默认加价金额';");
}
if(!pdo_fieldexists('auction_goodslist',  'st_price')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `st_price` int(10) DEFAULT '0' COMMENT '成交金额';");
}
if(!pdo_fieldexists('auction_goodslist',  'bond')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `bond` int(10) DEFAULT '0' COMMENT '保证金';");
}
if(!pdo_fieldexists('auction_goodslist',  'picarr')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `picarr` text COMMENT '商品图片';");
}
if(!pdo_fieldexists('auction_goodslist',  'content')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `content` mediumtext COMMENT '商品详情';");
}
if(!pdo_fieldexists('auction_goodslist',  'start_time')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `start_time` int(10) unsigned DEFAULT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('auction_goodslist',  'end_time')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `end_time` int(10) unsigned DEFAULT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('auction_goodslist',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('auction_goodslist',  'pos')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `pos` tinyint(4) unsigned DEFAULT '0' COMMENT '出价次数';");
}
if(!pdo_fieldexists('auction_goodslist',  'status')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `status` int(11) NOT NULL COMMENT '1:已付余款';");
}
if(!pdo_fieldexists('auction_goodslist',  'g_status')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `g_status` int(11) NOT NULL COMMENT '2:上架；1：下架';");
}
if(!pdo_fieldexists('auction_goodslist',  'q_uid')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `q_uid` varchar(10) DEFAULT NULL COMMENT '成交人昵称';");
}
if(!pdo_fieldexists('auction_goodslist',  'q_user')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `q_user` varchar(50) DEFAULT NULL COMMENT '成交人from_user';");
}
if(!pdo_fieldexists('auction_goodslist',  'send_state')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `send_state` int(4) unsigned NOT NULL COMMENT '1为已发货';");
}
if(!pdo_fieldexists('auction_goodslist',  'send')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `send` int(4) unsigned NOT NULL COMMENT '是否需要快递1为需要';");
}
if(!pdo_fieldexists('auction_goodslist',  'express')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `express` varchar(20) DEFAULT NULL COMMENT '快递公司';");
}
if(!pdo_fieldexists('auction_goodslist',  'expressn')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `expressn` char(20) DEFAULT NULL COMMENT '快递单';");
}
if(!pdo_fieldexists('auction_goodslist',  'send_time')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD `send_time` char(20) DEFAULT NULL COMMENT '发货时间';");
}
if(!pdo_indexexists('auction_goodslist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('auction_goodslist',  'send_state')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD KEY `send_state` (`send_state`);");
}
if(!pdo_indexexists('auction_goodslist',  'status')) {
	pdo_query("ALTER TABLE ".tablename('auction_goodslist')." ADD KEY `status` (`status`);");
}
if(!pdo_fieldexists('auction_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('auction_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id';");
}
if(!pdo_fieldexists('auction_member',  'balance')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `balance` int(10) unsigned NOT NULL COMMENT '会员余额';");
}
if(!pdo_fieldexists('auction_member',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `from_user` varchar(50) NOT NULL COMMENT '微信会员openID';");
}
if(!pdo_fieldexists('auction_member',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `realname` varchar(10) NOT NULL COMMENT '真实姓名';");
}
if(!pdo_fieldexists('auction_member',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `nickname` varchar(20) NOT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('auction_member',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `avatar` varchar(255) NOT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('auction_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `mobile` varchar(11) NOT NULL COMMENT '手机号码';");
}
if(!pdo_fieldexists('auction_member',  'address')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `address` varchar(255) NOT NULL COMMENT '邮寄地址';");
}
if(!pdo_fieldexists('auction_member',  'bankcard')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `bankcard` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('auction_member',  'bankname')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `bankname` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('auction_member',  'alipay')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `alipay` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('auction_member',  'aliname')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD `aliname` varchar(20) NOT NULL;");
}
if(!pdo_indexexists('auction_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_member')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('auction_recharge',  'id')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('auction_recharge',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号';");
}
if(!pdo_fieldexists('auction_recharge',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `from_user` varchar(50) NOT NULL COMMENT '微信会员openID';");
}
if(!pdo_fieldexists('auction_recharge',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `nickname` varchar(20) NOT NULL COMMENT '用户昵称';");
}
if(!pdo_fieldexists('auction_recharge',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `uid` int(10) unsigned NOT NULL COMMENT '用户ID';");
}
if(!pdo_fieldexists('auction_recharge',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `ordersn` varchar(20) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('auction_recharge',  'status')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0未支付,1为已付款';");
}
if(!pdo_fieldexists('auction_recharge',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额支付,2为支付宝,3为微信支付,4为定价返还';");
}
if(!pdo_fieldexists('auction_recharge',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `transid` varchar(30) NOT NULL COMMENT '微信订单号';");
}
if(!pdo_fieldexists('auction_recharge',  'price')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `price` int(10) unsigned NOT NULL COMMENT '充值金额';");
}
if(!pdo_fieldexists('auction_recharge',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '充值时间';");
}
if(!pdo_indexexists('auction_recharge',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('auction_recharge',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD KEY `from_user` (`from_user`);");
}
if(!pdo_indexexists('auction_recharge',  'status')) {
	pdo_query("ALTER TABLE ".tablename('auction_recharge')." ADD KEY `status` (`status`);");
}
if(!pdo_fieldexists('auction_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('auction_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号';");
}
if(!pdo_fieldexists('auction_record',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `from_user` varchar(50) NOT NULL COMMENT '微信会员openID';");
}
if(!pdo_fieldexists('auction_record',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `nickname` varchar(20) NOT NULL COMMENT '用户昵称';");
}
if(!pdo_fieldexists('auction_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `uid` int(10) unsigned NOT NULL COMMENT '用户ID';");
}
if(!pdo_fieldexists('auction_record',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `sid` int(10) unsigned NOT NULL COMMENT '商品编号';");
}
if(!pdo_fieldexists('auction_record',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `ordersn` varchar(20) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('auction_record',  'price')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `price` int(10) unsigned NOT NULL COMMENT '交易价格';");
}
if(!pdo_fieldexists('auction_record',  'bond')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `bond` int(10) unsigned NOT NULL COMMENT '保证金';");
}
if(!pdo_fieldexists('auction_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '购买时间';");
}
if(!pdo_indexexists('auction_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('auction_record',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('auction_record')." ADD KEY `sid` (`sid`);");
}
if(!pdo_fieldexists('auction_withdrawals',  'id')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('auction_withdrawals',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号';");
}
if(!pdo_fieldexists('auction_withdrawals',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD `uid` int(10) unsigned NOT NULL COMMENT '用户ID';");
}
if(!pdo_fieldexists('auction_withdrawals',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD `ordersn` varchar(20) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('auction_withdrawals',  'status')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD `status` smallint(4) NOT NULL COMMENT '0为提现中,1为提现成功，2提现失败';");
}
if(!pdo_fieldexists('auction_withdrawals',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为银行卡,2为支付宝';");
}
if(!pdo_fieldexists('auction_withdrawals',  'price')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD `price` int(10) unsigned NOT NULL COMMENT '提现金额';");
}
if(!pdo_fieldexists('auction_withdrawals',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '申请时间';");
}
if(!pdo_indexexists('auction_withdrawals',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('auction_withdrawals',  'status')) {
	pdo_query("ALTER TABLE ".tablename('auction_withdrawals')." ADD KEY `status` (`status`);");
}

?>