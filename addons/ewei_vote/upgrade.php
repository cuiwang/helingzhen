<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_vote_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_user` varchar(50) DEFAULT '',
  `rid` int(11) DEFAULT '0',
  `votes` varchar(255) DEFAULT '',
  `votetime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_votetime` (`votetime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_vote_option` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `content` text,
  `vote_num` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_vote_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT '0',
  `weid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `votetype` tinyint(4) DEFAULT '0',
  `votetotal` int(10) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `votenum` int(10) DEFAULT '0',
  `votetimes` int(10) DEFAULT '0',
  `votelimit` int(10) DEFAULT '0',
  `viewnum` int(10) DEFAULT '0',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `isimg` int(10) DEFAULT '0',
  `isshow` int(10) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `share_txt` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('vote_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('vote_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('vote_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('vote_fans')." ADD `from_user` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('vote_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('vote_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_fans',  'votes')) {
	pdo_query("ALTER TABLE ".tablename('vote_fans')." ADD `votes` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('vote_fans',  'votetime')) {
	pdo_query("ALTER TABLE ".tablename('vote_fans')." ADD `votetime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('vote_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('vote_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('vote_fans',  'indx_votetime')) {
	pdo_query("ALTER TABLE ".tablename('vote_fans')." ADD KEY `indx_votetime` (`votetime`);");
}
if(!pdo_fieldexists('vote_option',  'id')) {
	pdo_query("ALTER TABLE ".tablename('vote_option')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('vote_option',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('vote_option')." ADD `rid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_option',  'title')) {
	pdo_query("ALTER TABLE ".tablename('vote_option')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('vote_option',  'description')) {
	pdo_query("ALTER TABLE ".tablename('vote_option')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('vote_option',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('vote_option')." ADD `thumb` varchar(60) DEFAULT '';");
}
if(!pdo_fieldexists('vote_option',  'content')) {
	pdo_query("ALTER TABLE ".tablename('vote_option')." ADD `content` text;");
}
if(!pdo_fieldexists('vote_option',  'vote_num')) {
	pdo_query("ALTER TABLE ".tablename('vote_option')." ADD `vote_num` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('vote_option',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('vote_option')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('vote_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('vote_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `rid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `weid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('vote_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('vote_reply',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `thumb` varchar(60) DEFAULT '';");
}
if(!pdo_fieldexists('vote_reply',  'votetype')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `votetype` tinyint(4) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'votetotal')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `votetotal` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `status` tinyint(1) DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('vote_reply',  'votenum')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `votenum` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'votetimes')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `votetimes` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'votelimit')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `votelimit` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `viewnum` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'isimg')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `isimg` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `isshow` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('vote_reply',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `share_title` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('vote_reply',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `share_desc` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('vote_reply',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `share_url` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('vote_reply',  'share_txt')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD `share_txt` varchar(500) DEFAULT '';");
}
if(!pdo_indexexists('vote_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('vote_reply',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('vote_reply')." ADD KEY `indx_weid` (`weid`);");
}

?>