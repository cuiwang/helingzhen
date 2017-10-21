<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_yike_delivery_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `sets` text,
  `plugins` text,
  `sec` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_delivery_vouchers` (
  `vouchersid` int(11) NOT NULL AUTO_INCREMENT COMMENT '提货券id',
  `templateid` int(11) DEFAULT NULL COMMENT '关联商品id',
  `cardnumber` varchar(255) DEFAULT NULL COMMENT '提货券卡号',
  `password` varchar(255) DEFAULT NULL COMMENT '提货券密码',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `buytime` int(11) DEFAULT NULL COMMENT '提货券够买时间',
  `starttime` int(11) DEFAULT NULL COMMENT '提货券开始时间',
  `endtime` int(11) DEFAULT NULL COMMENT '提货券到期时间',
  `isuse` int(11) DEFAULT '0' COMMENT '是否使用',
  `uid` int(11) DEFAULT NULL COMMENT '粉丝id',
  PRIMARY KEY (`vouchersid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_delivery_vouchers_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '快递id',
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号id',
  `express_name` varchar(50) DEFAULT '' COMMENT '快递名',
  `express_price` varchar(10) DEFAULT '' COMMENT '快递价格',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_delivery_vouchers_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `title` varchar(100) DEFAULT '' COMMENT '商品名',
  `thumb` varchar(255) DEFAULT '' COMMENT '商品封面图',
  `unit` varchar(5) DEFAULT '' COMMENT '单位',
  `content` text COMMENT '详情',
  `createtime` int(11) DEFAULT '0' COMMENT '时间',
  `thumb_url` text COMMENT 'banner图',
  `timestart` int(11) DEFAULT '0' COMMENT '开始时间',
  `timeend` int(11) DEFAULT '0' COMMENT '结束时间',
  `marketprice` decimal(10,2) DEFAULT '0.00' COMMENT '市场价格',
  `productprice` decimal(10,2) DEFAULT '0.00' COMMENT '产品价格',
  `sales` int(11) DEFAULT '0' COMMENT '销量',
  `abstract` text COMMENT '简介',
  `uniacid` varchar(255) DEFAULT NULL COMMENT '公众号id',
  `total` int(11) DEFAULT NULL COMMENT '库存',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_delivery_vouchers_order` (
  `orderid` varchar(255) NOT NULL COMMENT '订单号',
  `goodsid` varchar(255) DEFAULT NULL COMMENT '商品id',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `num` varchar(255) DEFAULT NULL COMMENT '数量',
  `type` int(11) DEFAULT NULL COMMENT '状态：1：待发货 2：已发货 3：已完成',
  `name` varchar(255) DEFAULT NULL COMMENT '收货人名',
  `region` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL COMMENT '收货地址',
  `phone` varchar(255) DEFAULT NULL COMMENT '联系电话',
  `delivery_time` varchar(255) DEFAULT NULL COMMENT '发货时间',
  `oktime` int(11) DEFAULT NULL COMMENT '确认时间',
  `uniacid` varchar(255) DEFAULT NULL COMMENT '公众号id',
  `express` varchar(255) DEFAULT NULL COMMENT '快递名',
  `expressid` varchar(255) DEFAULT NULL COMMENT '快递单号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_delivery_vouchers_template` (
  `templateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '模板id',
  `goodsid` varchar(255) DEFAULT NULL COMMENT '关联商品id',
  `createtime` int(11) DEFAULT NULL COMMENT '创建时间',
  `uniacid` int(11) DEFAULT NULL COMMENT '用户id',
  `templatename` varchar(255) DEFAULT NULL COMMENT '模板名',
  `usedata` varchar(255) DEFAULT '0' COMMENT '已使用的提货券数',
  `alldata` varchar(255) DEFAULT '0' COMMENT '提货券总数',
  PRIMARY KEY (`templateid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_delivery_vouchers_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '购买接口id',
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `url` varchar(255) DEFAULT NULL COMMENT 'url地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('yike_delivery_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yike_delivery_sysset',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_sysset')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('yike_delivery_sysset',  'sets')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_sysset')." ADD `sets` text;");
}
if(!pdo_fieldexists('yike_delivery_sysset',  'plugins')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_sysset')." ADD `plugins` text;");
}
if(!pdo_fieldexists('yike_delivery_sysset',  'sec')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_sysset')." ADD `sec` text;");
}
if(!pdo_indexexists('yike_delivery_sysset',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_sysset')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'vouchersid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `vouchersid` int(11) NOT NULL AUTO_INCREMENT COMMENT '提货券id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'templateid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `templateid` int(11) DEFAULT NULL COMMENT '关联商品id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'cardnumber')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `cardnumber` varchar(255) DEFAULT NULL COMMENT '提货券卡号';");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'password')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `password` varchar(255) DEFAULT NULL COMMENT '提货券密码';");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `createtime` int(11) DEFAULT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'buytime')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `buytime` int(11) DEFAULT NULL COMMENT '提货券够买时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `starttime` int(11) DEFAULT NULL COMMENT '提货券开始时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `endtime` int(11) DEFAULT NULL COMMENT '提货券到期时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'isuse')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `isuse` int(11) DEFAULT '0' COMMENT '是否使用';");
}
if(!pdo_fieldexists('yike_delivery_vouchers',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers')." ADD `uid` int(11) DEFAULT NULL COMMENT '粉丝id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_express',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_express')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '快递id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_express',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_express')." ADD `uniacid` int(11) DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_express',  'express_name')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_express')." ADD `express_name` varchar(50) DEFAULT '' COMMENT '快递名';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_express',  'express_price')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_express')." ADD `express_price` varchar(10) DEFAULT '' COMMENT '快递价格';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `title` varchar(100) DEFAULT '' COMMENT '商品名';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `thumb` varchar(255) DEFAULT '' COMMENT '商品封面图';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `unit` varchar(5) DEFAULT '' COMMENT '单位';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'content')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `content` text COMMENT '详情';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `createtime` int(11) DEFAULT '0' COMMENT '时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `thumb_url` text COMMENT 'banner图';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'timestart')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `timestart` int(11) DEFAULT '0' COMMENT '开始时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'timeend')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `timeend` int(11) DEFAULT '0' COMMENT '结束时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `marketprice` decimal(10,2) DEFAULT '0.00' COMMENT '市场价格';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `productprice` decimal(10,2) DEFAULT '0.00' COMMENT '产品价格';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `sales` int(11) DEFAULT '0' COMMENT '销量';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'abstract')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `abstract` text COMMENT '简介';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `uniacid` varchar(255) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_goods')." ADD `total` int(11) DEFAULT NULL COMMENT '库存';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `orderid` varchar(255) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `goodsid` varchar(255) DEFAULT NULL COMMENT '商品id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `uid` int(11) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'num')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `num` varchar(255) DEFAULT NULL COMMENT '数量';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'type')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `type` int(11) DEFAULT NULL COMMENT '状态：1：待发货 2：已发货 3：已完成';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'name')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `name` varchar(255) DEFAULT NULL COMMENT '收货人名';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'region')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `region` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `address` varchar(255) DEFAULT NULL COMMENT '收货地址';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `phone` varchar(255) DEFAULT NULL COMMENT '联系电话';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'delivery_time')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `delivery_time` varchar(255) DEFAULT NULL COMMENT '发货时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'oktime')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `oktime` int(11) DEFAULT NULL COMMENT '确认时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `uniacid` varchar(255) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'express')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `express` varchar(255) DEFAULT NULL COMMENT '快递名';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_order',  'expressid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_order')." ADD `expressid` varchar(255) DEFAULT NULL COMMENT '快递单号';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_template',  'templateid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_template')." ADD `templateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '模板id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_template',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_template')." ADD `goodsid` varchar(255) DEFAULT NULL COMMENT '关联商品id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_template',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_template')." ADD `createtime` int(11) DEFAULT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_template',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_template')." ADD `uniacid` int(11) DEFAULT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_template',  'templatename')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_template')." ADD `templatename` varchar(255) DEFAULT NULL COMMENT '模板名';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_template',  'usedata')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_template')." ADD `usedata` varchar(255) DEFAULT '0' COMMENT '已使用的提货券数';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_template',  'alldata')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_template')." ADD `alldata` varchar(255) DEFAULT '0' COMMENT '提货券总数';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_url',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_url')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '购买接口id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_url',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_url')." ADD `uniacid` int(11) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('yike_delivery_vouchers_url',  'url')) {
	pdo_query("ALTER TABLE ".tablename('yike_delivery_vouchers_url')." ADD `url` varchar(255) DEFAULT NULL COMMENT 'url地址';");
}

?>