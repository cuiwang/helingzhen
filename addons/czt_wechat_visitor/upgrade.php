<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_czt_wechat_visitor_fans` (
  `fanid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `headimgurl` varchar(256) NOT NULL DEFAULT '',
  `sex` tinyint(1) DEFAULT NULL,
  `city` varchar(20) NOT NULL DEFAULT '',
  `country` varchar(20) NOT NULL DEFAULT '',
  `province` varchar(20) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`fanid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_czt_wechat_visitor_lists` (
  `listid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `fanid` int(11) unsigned NOT NULL,
  `topic` varchar(256) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL,
  `zan` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`listid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_czt_wechat_visitor_visitors` (
  `listid` int(11) unsigned NOT NULL,
  `fanid` int(11) unsigned NOT NULL,
  `create_time` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'fanid')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `fanid` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `openid` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `nickname` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `headimgurl` varchar(256) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `sex` tinyint(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'city')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `city` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'country')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `country` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'province')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `province` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('czt_wechat_visitor_fans',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_fans')." ADD `create_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_wechat_visitor_lists',  'listid')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_lists')." ADD `listid` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('czt_wechat_visitor_lists',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_lists')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_wechat_visitor_lists',  'fanid')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_lists')." ADD `fanid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_wechat_visitor_lists',  'topic')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_lists')." ADD `topic` varchar(256) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('czt_wechat_visitor_lists',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_lists')." ADD `create_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_wechat_visitor_lists',  'zan')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_lists')." ADD `zan` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('czt_wechat_visitor_visitors',  'listid')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_visitors')." ADD `listid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_wechat_visitor_visitors',  'fanid')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_visitors')." ADD `fanid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('czt_wechat_visitor_visitors',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('czt_wechat_visitor_visitors')." ADD `create_time` int(10) unsigned NOT NULL;");
}

?>