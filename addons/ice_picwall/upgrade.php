<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_ice_picwall` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `tousername` varchar(32) NOT NULL DEFAULT '1',
  `timeID` int(10) unsigned NOT NULL DEFAULT '1',
  `timestart` int(10) unsigned NOT NULL DEFAULT '1',
  `timeend` varchar(16) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ice_picwalllikelist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `openid` varchar(64) NOT NULL DEFAULT '1',
  `tousername` varchar(32) NOT NULL DEFAULT '1',
  `timeID` int(10) unsigned NOT NULL DEFAULT '1',
  `picid` int(10) unsigned NOT NULL DEFAULT '1',
  `time` varchar(16) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ice_picwallmain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `openid` varchar(64) NOT NULL DEFAULT '1',
  `tousername` varchar(32) NOT NULL DEFAULT '1',
  `timeID` int(10) unsigned NOT NULL DEFAULT '1',
  `showID` int(10) unsigned NOT NULL DEFAULT '1',
  `imgurl` varchar(512) NOT NULL DEFAULT '1',
  `likeNum` int(10) unsigned NOT NULL DEFAULT '0',
  `time` varchar(16) NOT NULL DEFAULT '1',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ice_picwalluserinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `openid` varchar(64) NOT NULL DEFAULT '1',
  `uname` varchar(16) NOT NULL,
  `phone` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('ice_picwall',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwall')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_picwall',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwall')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwall',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwall')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwall',  'tousername')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwall')." ADD `tousername` varchar(32) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwall',  'timeID')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwall')." ADD `timeID` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwall',  'timestart')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwall')." ADD `timestart` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwall',  'timeend')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwall')." ADD `timeend` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalllikelist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalllikelist')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_picwalllikelist',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalllikelist')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalllikelist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalllikelist')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalllikelist',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalllikelist')." ADD `openid` varchar(64) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalllikelist',  'tousername')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalllikelist')." ADD `tousername` varchar(32) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalllikelist',  'timeID')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalllikelist')." ADD `timeID` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalllikelist',  'picid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalllikelist')." ADD `picid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalllikelist',  'time')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalllikelist')." ADD `time` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwallmain',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_picwallmain',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwallmain',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwallmain',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `openid` varchar(64) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwallmain',  'tousername')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `tousername` varchar(32) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwallmain',  'timeID')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `timeID` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwallmain',  'showID')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `showID` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwallmain',  'imgurl')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `imgurl` varchar(512) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwallmain',  'likeNum')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `likeNum` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('ice_picwallmain',  'time')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `time` varchar(16) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwallmain',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwallmain')." ADD `status` tinyint(4) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalluserinfo',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalluserinfo')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ice_picwalluserinfo',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalluserinfo')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalluserinfo',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalluserinfo')." ADD `openid` varchar(64) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('ice_picwalluserinfo',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalluserinfo')." ADD `uname` varchar(16) NOT NULL;");
}
if(!pdo_fieldexists('ice_picwalluserinfo',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('ice_picwalluserinfo')." ADD `phone` varchar(12) NOT NULL;");
}

?>