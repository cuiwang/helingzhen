<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_wx_xinmeiti_pubcla` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `xmt_fl` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `xmt_fltime` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='分类';
CREATE TABLE IF NOT EXISTS `ims_wx_xinmeiti_pubnum` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '主体公众号',
  `xmt_fl` varchar(64) NOT NULL DEFAULT '' COMMENT '分类',
  `xmt_name` varchar(64) NOT NULL DEFAULT '' COMMENT '公众号名称',
  `xmt_biz` varchar(128) NOT NULL DEFAULT '' COMMENT '身份证biz',
  `xmt_img` varchar(255) NOT NULL DEFAULT '' COMMENT '公众号头像',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='公众号';
";
pdo_run($sql);
if(!pdo_fieldexists('wx_xinmeiti_pubcla',  'Id')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubcla')." ADD `Id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_xinmeiti_pubcla',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubcla')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_xinmeiti_pubcla',  'xmt_fl')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubcla')." ADD `xmt_fl` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称';");
}
if(!pdo_fieldexists('wx_xinmeiti_pubcla',  'xmt_fltime')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubcla')." ADD `xmt_fltime` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_xinmeiti_pubnum',  'Id')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubnum')." ADD `Id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_xinmeiti_pubnum',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubnum')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '主体公众号';");
}
if(!pdo_fieldexists('wx_xinmeiti_pubnum',  'xmt_fl')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubnum')." ADD `xmt_fl` varchar(64) NOT NULL DEFAULT '' COMMENT '分类';");
}
if(!pdo_fieldexists('wx_xinmeiti_pubnum',  'xmt_name')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubnum')." ADD `xmt_name` varchar(64) NOT NULL DEFAULT '' COMMENT '公众号名称';");
}
if(!pdo_fieldexists('wx_xinmeiti_pubnum',  'xmt_biz')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubnum')." ADD `xmt_biz` varchar(128) NOT NULL DEFAULT '' COMMENT '身份证biz';");
}
if(!pdo_fieldexists('wx_xinmeiti_pubnum',  'xmt_img')) {
	pdo_query("ALTER TABLE ".tablename('wx_xinmeiti_pubnum')." ADD `xmt_img` varchar(255) NOT NULL DEFAULT '' COMMENT '公众号头像';");
}

?>