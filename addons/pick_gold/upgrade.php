<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_pick_gold_rank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `score` int(10) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `score` (`score`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_pick_gold_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rank_show` smallint(5) unsigned NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `share_img` varchar(255) NOT NULL,
  `share_title` varchar(255) NOT NULL,
  `share_desc` varchar(255) NOT NULL,
  `help` text NOT NULL,
  `game_time` smallint(5) unsigned NOT NULL,
  `starttime` int(11) unsigned NOT NULL,
  `endtime` int(11) unsigned NOT NULL,
  `prop_value` varchar(200) NOT NULL,
  `award` varchar(500) NOT NULL,
  `game_title` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('pick_gold_rank',  'id')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_rank')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('pick_gold_rank',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_rank')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_rank',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_rank')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_rank',  'score')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_rank')." ADD `score` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_rank',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_rank')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('pick_gold_rank',  'score')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_rank')." ADD KEY `score` (`score`);");
}
if(!pdo_indexexists('pick_gold_rank',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_rank')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('pick_gold_rank',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_rank')." ADD KEY `uid` (`uid`);");
}
if(!pdo_indexexists('pick_gold_rank',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_rank')." ADD KEY `dateline` (`dateline`);");
}
if(!pdo_fieldexists('pick_gold_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('pick_gold_setting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'rank_show')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `rank_show` smallint(5) unsigned NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'share_img')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `share_img` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `share_title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `share_desc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'help')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `help` text NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'game_time')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `game_time` smallint(5) unsigned NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `starttime` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `endtime` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'prop_value')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `prop_value` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'award')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `award` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('pick_gold_setting',  'game_title')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD `game_title` varchar(200) NOT NULL;");
}
if(!pdo_indexexists('pick_gold_setting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('pick_gold_setting')." ADD KEY `uniacid` (`uniacid`);");
}

?>