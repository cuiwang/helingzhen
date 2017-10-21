<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_water_fcards_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `headimg` varchar(300) DEFAULT NULL,
  `card1` int(11) DEFAULT '0',
  `card2` int(11) DEFAULT '0',
  `card3` int(11) DEFAULT '0',
  `card4` int(11) DEFAULT '0',
  `card5` int(11) DEFAULT '0',
  `today` date DEFAULT NULL,
  `left` int(2) DEFAULT '3',
  `finish` int(2) DEFAULT '0',
  `state` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_water_fcards_helper` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `fansid` int(11) DEFAULT '0',
  `openid` varchar(300) DEFAULT NULL,
  `helpid` int(11) DEFAULT NULL,
  `helpopenid` varchar(300) DEFAULT NULL,
  `helpheadimg` varchar(300) DEFAULT NULL,
  `cardid` int(2) DEFAULT NULL,
  `cardname` varchar(100) DEFAULT NULL,
  `state` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_water_fcards_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL,
  `fansid` int(11) DEFAULT '0',
  `fmoney` float DEFAULT NULL,
  `ftime` datetime DEFAULT NULL,
  `msg` varchar(1000) DEFAULT NULL,
  `state` int(2) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('water_fcards_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_fcards_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_fans',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `openid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `nickname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_fans',  'headimg')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `headimg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_fans',  'card1')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `card1` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_fans',  'card2')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `card2` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_fans',  'card3')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `card3` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_fans',  'card4')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `card4` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_fans',  'card5')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `card5` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_fans',  'today')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `today` date DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_fans',  'left')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `left` int(2) DEFAULT '3';");
}
if(!pdo_fieldexists('water_fcards_fans',  'finish')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `finish` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_fans',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_fans')." ADD `state` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_helper',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_fcards_helper',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_helper',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `fansid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_helper',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `openid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_helper',  'helpid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `helpid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_helper',  'helpopenid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `helpopenid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_helper',  'helpheadimg')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `helpheadimg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_helper',  'cardid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `cardid` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_helper',  'cardname')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `cardname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_helper',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_helper')." ADD `state` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_money',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_money')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_fcards_money',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_money')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_money',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_money')." ADD `openid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_money',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_money')." ADD `fansid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('water_fcards_money',  'fmoney')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_money')." ADD `fmoney` float DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_money',  'ftime')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_money')." ADD `ftime` datetime DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_money',  'msg')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_money')." ADD `msg` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_fcards_money',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_fcards_money')." ADD `state` int(2) DEFAULT '0';");
}

?>