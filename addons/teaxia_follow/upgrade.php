<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_teaxia_follow` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `credits` varchar(7) NOT NULL,
  `num` varchar(10) NOT NULL,
  `focus` varchar(255) NOT NULL,
  `uniacid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_teaxia_follow_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `uniacid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('teaxia_follow',  'id')) {
	pdo_query("ALTER TABLE ".tablename('teaxia_follow')." ADD `id` int(100) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('teaxia_follow',  'credits')) {
	pdo_query("ALTER TABLE ".tablename('teaxia_follow')." ADD `credits` varchar(7) NOT NULL;");
}
if(!pdo_fieldexists('teaxia_follow',  'num')) {
	pdo_query("ALTER TABLE ".tablename('teaxia_follow')." ADD `num` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('teaxia_follow',  'focus')) {
	pdo_query("ALTER TABLE ".tablename('teaxia_follow')." ADD `focus` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('teaxia_follow',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('teaxia_follow')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('teaxia_follow_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('teaxia_follow_log')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('teaxia_follow_log',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('teaxia_follow_log')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('teaxia_follow_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('teaxia_follow_log')." ADD `uniacid` int(10) NOT NULL;");
}

?>