<?php
$sql = "
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

CREATE TABLE IF NOT EXISTS  `ims_hx_zhongchou_news_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `displayorder` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hx_zhongchou_order_ws` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
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
";
pdo_query($sql);
if(!pdo_fieldexists('hx_zhongchou_dispatch', 'enabled')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_dispatch')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('hx_zhongchou_project', 'from_user')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD   `from_user` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project', 'starttime')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD   `starttime` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project', 'reason')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD   `reason` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project', 'subsurl')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD   `subsurl` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project', 'show_type')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD   `show_type` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project', 'type')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD    `type` tinyint(10) DEFAULT '0';");
}
if(!pdo_fieldexists('hx_zhongchou_project', 'lianxiren')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD    `lianxiren` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_project', 'qq')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project')." ADD    `qq` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item', 'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD    `displayorder` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_zhongchou_project_item', 'delivery_fee')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_project_item')." ADD    `delivery_fee` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws', 'nickname')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD    `nickname` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hx_zhongchou_order_ws', 'avatar')) {
	pdo_query("ALTER TABLE ".tablename('hx_zhongchou_order_ws')." ADD     `avatar` varchar(255) NOT NULL DEFAULT '';");
}
