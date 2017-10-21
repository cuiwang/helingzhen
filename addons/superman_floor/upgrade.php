<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_superman_floor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `awardprompt` text NOT NULL,
  `currentprompt` text NOT NULL,
  `floorprompt` text NOT NULL,
  `setting` text NOT NULL,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_superman_floor_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `floors` varchar(1000) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_superman_floor_winner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `floor` int(4) unsigned NOT NULL DEFAULT '0',
  `uid` int(4) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL DEFAULT '0',
  `award_id` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `realname` varchar(128) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `qq` varchar(20) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rid_floor_UNIQUE` (`rid`,`floor`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('superman_floor',  'id')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('superman_floor',  'awardprompt')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor')." ADD `awardprompt` text NOT NULL;");
}
if(!pdo_fieldexists('superman_floor',  'currentprompt')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor')." ADD `currentprompt` text NOT NULL;");
}
if(!pdo_fieldexists('superman_floor',  'floorprompt')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor')." ADD `floorprompt` text NOT NULL;");
}
if(!pdo_fieldexists('superman_floor',  'setting')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor')." ADD `setting` text NOT NULL;");
}
if(!pdo_fieldexists('superman_floor',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('superman_floor',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor')." ADD KEY `rid` (`rid`);");
}
if(!pdo_fieldexists('superman_floor_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_award')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('superman_floor_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_award')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_award',  'floors')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_award')." ADD `floors` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superman_floor_award',  'title')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_award')." ADD `title` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superman_floor_award',  'description')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_award')." ADD `description` text NOT NULL;");
}
if(!pdo_fieldexists('superman_floor_award',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_award')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('superman_floor_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_award')." ADD KEY `rid` (`rid`);");
}
if(!pdo_fieldexists('superman_floor_winner',  'id')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('superman_floor_winner',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_winner',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_winner',  'floor')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `floor` int(4) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_winner',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `uid` int(4) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_winner',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `openid` varchar(50) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_winner',  'award_id')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `award_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_winner',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `ip` char(15) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superman_floor_winner',  'status')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `status` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('superman_floor_winner',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `realname` varchar(128) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superman_floor_winner',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `mobile` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superman_floor_winner',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `qq` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('superman_floor_winner',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD `dateline` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('superman_floor_winner',  'rid_floor_UNIQUE')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD UNIQUE KEY `rid_floor_UNIQUE` (`rid`,`floor`);");
}
if(!pdo_indexexists('superman_floor_winner',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('superman_floor_winner',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('superman_floor_winner')." ADD KEY `rid` (`rid`);");
}

?>