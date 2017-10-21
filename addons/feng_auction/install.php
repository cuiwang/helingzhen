<?php
global $_W;

$sql = "
CREATE TABLE IF NOT EXISTS `ims_auction_adv` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_auction_goodslist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `title` varchar(100) DEFAULT NULL COMMENT '商品标题',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

";
pdo_query($sql);