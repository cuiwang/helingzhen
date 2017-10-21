<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_recharge_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('recharge_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('recharge_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('recharge_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('recharge_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `ordersn` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('recharge_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `price` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('recharge_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功';");
}
if(!pdo_fieldexists('recharge_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线';");
}
if(!pdo_fieldexists('recharge_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
if(!pdo_fieldexists('recharge_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `remark` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('recharge_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('recharge_order')." ADD `createtime` int(10) unsigned NOT NULL;");
}

?>