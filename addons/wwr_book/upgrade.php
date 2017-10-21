<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_wwr_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT '',
  `truename` varchar(200) NOT NULL DEFAULT '',
  `mobile` varchar(200) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wwr_vote_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `ads` varchar(255) NOT NULL,
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `guanzhu` tinyint(1) unsigned NOT NULL,
  `tips` varchar(255) NOT NULL COMMENT '未关注提示信息',
  `ischeck` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需审核',
  `maxnum` tinyint(3) unsigned NOT NULL DEFAULT '20' COMMENT '排行榜数量',
  `voteone` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一用户投票数',
  `votes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总数',
  `replytitle` varchar(255) NOT NULL,
  `replyimg` varchar(255) NOT NULL,
  `replydesc` varchar(255) NOT NULL,
  `intro` text,
  `introrule` text,
  `award` text,
  `introoff` text,
  `views` int(10) unsigned DEFAULT '0',
  `rid` int(10) unsigned DEFAULT NULL COMMENT 'rid是规则id',
  `uniacid` int(10) unsigned DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wwr_vote_activity_records` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `truename` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `sex` tinyint(1) unsigned DEFAULT '0',
  `wxnicheng` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `wxhao` varchar(255) DEFAULT NULL,
  `jianjie` varchar(255) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `tthumbs` text,
  `addtime` int(10) unsigned DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT '0',
  `votes` int(10) unsigned DEFAULT '0' COMMENT '投票数',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量',
  `ip` char(15) DEFAULT NULL,
  `activityid` int(10) unsigned DEFAULT NULL,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`,`views`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wwr_vote_activity_votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nicheng` varchar(255) DEFAULT NULL,
  `sex` tinyint(1) unsigned DEFAULT '0',
  `avatar` varchar(255) DEFAULT NULL,
  `rid` int(10) unsigned DEFAULT NULL COMMENT '参与者id',
  `activityid` int(10) unsigned DEFAULT NULL,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `addtime` int(10) unsigned DEFAULT NULL,
  `ip` char(15) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('wwr_book',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wwr_book')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wwr_book',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_book')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wwr_book',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wwr_book')." ADD `title` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wwr_book',  'truename')) {
	pdo_query("ALTER TABLE ".tablename('wwr_book')." ADD `truename` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wwr_book',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('wwr_book')." ADD `mobile` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wwr_book',  'content')) {
	pdo_query("ALTER TABLE ".tablename('wwr_book')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('wwr_book',  'listorder')) {
	pdo_query("ALTER TABLE ".tablename('wwr_book')." ADD `listorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wwr_book',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wwr_book')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示';");
}
if(!pdo_fieldexists('wwr_book',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wwr_book')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'ads')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `ads` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `starttime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `endtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'guanzhu')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `guanzhu` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'tips')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `tips` varchar(255) NOT NULL COMMENT '未关注提示信息';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'ischeck')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `ischeck` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需审核';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'maxnum')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `maxnum` tinyint(3) unsigned NOT NULL DEFAULT '20' COMMENT '排行榜数量';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'voteone')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `voteone` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一用户投票数';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'votes')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `votes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总数';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'replytitle')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `replytitle` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'replyimg')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `replyimg` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'replydesc')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `replydesc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'intro')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `intro` text;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'introrule')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `introrule` text;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'award')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `award` text;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'introoff')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `introoff` text;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'views')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `views` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `rid` int(10) unsigned DEFAULT NULL COMMENT 'rid是规则id';");
}
if(!pdo_fieldexists('wwr_vote_activity',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity')." ADD `addtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'truename')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `truename` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `mobile` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `sex` tinyint(1) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'wxnicheng')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `wxnicheng` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `avatar` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'wxhao')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `wxhao` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'jianjie')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `jianjie` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'cover')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `cover` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'tthumbs')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `tthumbs` text;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `addtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `status` tinyint(3) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'votes')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `votes` int(10) unsigned DEFAULT '0' COMMENT '投票数';");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'views')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '浏览量';");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `ip` char(15) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'activityid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `activityid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_records',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_records')." ADD `openid` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'nicheng')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `nicheng` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `sex` tinyint(1) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `avatar` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `rid` int(10) unsigned DEFAULT NULL COMMENT '参与者id';");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'activityid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `activityid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `addtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `ip` char(15) DEFAULT NULL;");
}
if(!pdo_fieldexists('wwr_vote_activity_votes',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wwr_vote_activity_votes')." ADD `openid` varchar(255) DEFAULT NULL;");
}

?>