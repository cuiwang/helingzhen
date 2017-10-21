<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_gzredbag_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `total_count` int(10) NOT NULL DEFAULT '0' COMMENT '总次数',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='红包总次数';
CREATE TABLE IF NOT EXISTS `ims_gzredbag_hx` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL COMMENT '用户id',
  `status` int(1) NOT NULL COMMENT '状态',
  `send_status` int(1) NOT NULL COMMENT '红包发放状态',
  `hxcode` varchar(100) DEFAULT NULL COMMENT '核销编码',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_gzredbag_money` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `total_money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_gzredbag_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL COMMENT '用户id',
  `nickname` varchar(100) DEFAULT NULL COMMENT '用户昵称',
  `headimgurl` varchar(200) DEFAULT NULL COMMENT '用户头像',
  `status` varchar(1) DEFAULT NULL COMMENT '0未领取，1未领取',
  `send_status` int(1) NOT NULL COMMENT '红包发放状态',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_gzredbag_userinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL COMMENT '用户id',
  `nickname` varchar(100) DEFAULT NULL COMMENT '用户昵称',
  `headimgurl` varchar(200) DEFAULT NULL COMMENT '用户头像',
  `location_x` varchar(200) DEFAULT NULL COMMENT '经度',
  `location_y` varchar(1) DEFAULT NULL COMMENT '维度',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '粉丝领取总金额',
  `tx_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '粉丝已提现金额',
  `ktx_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '粉丝可提现金额',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='粉丝信息';
CREATE TABLE IF NOT EXISTS `ims_gzredbag_wxpay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) DEFAULT NULL COMMENT '支付项目名称',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `max_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包最大金额',
  `min_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包最小金额',
  `qr_url` varchar(500) DEFAULT NULL COMMENT '二维码字符串',
  `qr_img` varchar(300) NOT NULL COMMENT '二维码图片',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '扫码支付类型 1,2',
  `remark` text NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_gzredbag_wxpay_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(100) DEFAULT NULL COMMENT '用户id',
  `title` varchar(100) DEFAULT NULL COMMENT '支付项目名称',
  `wxpay_id` varchar(100) DEFAULT NULL COMMENT '支付项目id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `get_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '获得红包金额',
  `send_status` int(1) NOT NULL DEFAULT '0' COMMENT '红包发放状态',
  `out_trade_no` varchar(100) DEFAULT NULL COMMENT '内部交易单号',
  `transaction_id` varchar(100) DEFAULT NULL COMMENT '微信交易单号',
  `pay_status` int(1) NOT NULL DEFAULT '0' COMMENT '0未付款，1已付款',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('gzredbag_count',  'id')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_count')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('gzredbag_count',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_count')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('gzredbag_count',  'total_count')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_count')." ADD `total_count` int(10) NOT NULL DEFAULT '0' COMMENT '总次数';");
}
if(!pdo_fieldexists('gzredbag_count',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_count')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('gzredbag_hx',  'id')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_hx')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('gzredbag_hx',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_hx')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('gzredbag_hx',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_hx')." ADD `openid` varchar(100) NOT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('gzredbag_hx',  'status')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_hx')." ADD `status` int(1) NOT NULL COMMENT '状态';");
}
if(!pdo_fieldexists('gzredbag_hx',  'send_status')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_hx')." ADD `send_status` int(1) NOT NULL COMMENT '红包发放状态';");
}
if(!pdo_fieldexists('gzredbag_hx',  'hxcode')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_hx')." ADD `hxcode` varchar(100) DEFAULT NULL COMMENT '核销编码';");
}
if(!pdo_fieldexists('gzredbag_hx',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_hx')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('gzredbag_money',  'id')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_money')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('gzredbag_money',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_money')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('gzredbag_money',  'total_money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_money')." ADD `total_money` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('gzredbag_money',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_money')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('gzredbag_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('gzredbag_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_user')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('gzredbag_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_user')." ADD `openid` varchar(100) NOT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('gzredbag_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_user')." ADD `nickname` varchar(100) DEFAULT NULL COMMENT '用户昵称';");
}
if(!pdo_fieldexists('gzredbag_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_user')." ADD `headimgurl` varchar(200) DEFAULT NULL COMMENT '用户头像';");
}
if(!pdo_fieldexists('gzredbag_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_user')." ADD `status` varchar(1) DEFAULT NULL COMMENT '0未领取，1未领取';");
}
if(!pdo_fieldexists('gzredbag_user',  'send_status')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_user')." ADD `send_status` int(1) NOT NULL COMMENT '红包发放状态';");
}
if(!pdo_fieldexists('gzredbag_user',  'money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_user')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('gzredbag_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_user')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'id')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `openid` varchar(100) NOT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `nickname` varchar(100) DEFAULT NULL COMMENT '用户昵称';");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `headimgurl` varchar(200) DEFAULT NULL COMMENT '用户头像';");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'location_x')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `location_x` varchar(200) DEFAULT NULL COMMENT '经度';");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'location_y')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `location_y` varchar(1) DEFAULT NULL COMMENT '维度';");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '粉丝领取总金额';");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'tx_money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `tx_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '粉丝已提现金额';");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'ktx_money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `ktx_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '粉丝可提现金额';");
}
if(!pdo_fieldexists('gzredbag_userinfo',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_userinfo')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'id')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'title')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `title` varchar(100) DEFAULT NULL COMMENT '支付项目名称';");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额';");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'max_money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `max_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包最大金额';");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'min_money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `min_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '红包最小金额';");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'qr_url')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `qr_url` varchar(500) DEFAULT NULL COMMENT '二维码字符串';");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'qr_img')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `qr_img` varchar(300) NOT NULL COMMENT '二维码图片';");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'type')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `type` int(1) NOT NULL DEFAULT '1' COMMENT '扫码支付类型 1,2';");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `remark` text NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('gzredbag_wxpay',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `openid` varchar(100) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'title')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `title` varchar(100) DEFAULT NULL COMMENT '支付项目名称';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'wxpay_id')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `wxpay_id` varchar(100) DEFAULT NULL COMMENT '支付项目id';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'get_money')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `get_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '获得红包金额';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'send_status')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `send_status` int(1) NOT NULL DEFAULT '0' COMMENT '红包发放状态';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'out_trade_no')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `out_trade_no` varchar(100) DEFAULT NULL COMMENT '内部交易单号';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'transaction_id')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `transaction_id` varchar(100) DEFAULT NULL COMMENT '微信交易单号';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'pay_status')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `pay_status` int(1) NOT NULL DEFAULT '0' COMMENT '0未付款，1已付款';");
}
if(!pdo_fieldexists('gzredbag_wxpay_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('gzredbag_wxpay_order')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}

?>