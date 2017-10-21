<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_tc_singleproduct_clerk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `gid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tc_singleproduct_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `gname` varchar(255) NOT NULL,
  `shopname` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `desciption` text NOT NULL,
  `gstatus` tinyint(1) unsigned NOT NULL,
  `sharetitle` varchar(255) NOT NULL,
  `sharedesc` varchar(500) NOT NULL,
  `sharepic` varchar(255) NOT NULL,
  `createtime` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tc_singleproduct_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  `openid` varchar(32) NOT NULL,
  `amount` decimal(10,2) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `tid` varchar(64) NOT NULL,
  `transid` varchar(32) NOT NULL,
  `remark` varchar(500) NOT NULL,
  `qrcode` varchar(255) NOT NULL,
  `clerkopenid` varchar(32) NOT NULL,
  `createtime` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('tc_singleproduct_clerk',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_clerk')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tc_singleproduct_clerk',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_clerk')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_clerk',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_clerk')." ADD `openid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_clerk',  'gid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_clerk')." ADD `gid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'gname')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `gname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'shopname')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `shopname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `pic` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `type` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'price')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `price` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'count')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `count` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'desciption')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `desciption` text NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'gstatus')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `gstatus` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `sharetitle` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `sharedesc` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'sharepic')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `sharepic` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_goods')." ADD `createtime` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'gid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `gid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `openid` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `amount` decimal(10,2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `tid` varchar(64) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `transid` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `remark` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `qrcode` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'clerkopenid')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `clerkopenid` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('tc_singleproduct_orders',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tc_singleproduct_orders')." ADD `createtime` int(11) unsigned NOT NULL;");
}

?>