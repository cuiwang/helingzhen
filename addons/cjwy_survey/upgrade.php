<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_cjwy_survey` (
  `sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(10) unsigned DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `thumb` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `content` text,
  `stime` int(10) unsigned DEFAULT '0',
  `etime` int(10) unsigned DEFAULT '0',
  `success_info` varchar(100) DEFAULT NULL,
  `max_num` int(10) unsigned DEFAULT '0',
  `num` int(10) unsigned DEFAULT '0',
  `hdp` varchar(255) DEFAULT NULL,
  `bgpic` varchar(255) DEFAULT NULL,
  `copyright` varchar(20) DEFAULT NULL,
  `copyrighturl` varchar(255) DEFAULT NULL,
  `sharetitle` varchar(200) DEFAULT NULL,
  `sharepic` varchar(255) DEFAULT NULL,
  `sharedesc` varchar(255) DEFAULT NULL,
  `created` int(10) unsigned DEFAULT '0',
  `credit` int(10) unsigned DEFAULT '0',
  `coupon` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_cjwy_survey_answer` (
  `said` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `sid` int(10) unsigned DEFAULT '0',
  `uniacid` int(10) unsigned DEFAULT '0',
  `sqid` int(10) unsigned DEFAULT '0',
  `openid` int(10) unsigned DEFAULT '0',
  `uid` int(10) unsigned DEFAULT '0',
  `content` text,
  `created` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`said`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_cjwy_survey_question` (
  `sqid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(10) unsigned DEFAULT '0',
  `sid` int(10) unsigned DEFAULT '0',
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `extra` varchar(500) DEFAULT NULL,
  `is_must` tinyint(1) unsigned DEFAULT '1',
  `is_show` tinyint(1) unsigned DEFAULT '1',
  `rule` varchar(100) DEFAULT NULL,
  `defvalue` varchar(100) DEFAULT NULL,
  `sort` smallint(5) unsigned DEFAULT '0',
  `created` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`sqid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('cjwy_survey',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `sid` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cjwy_survey',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `uniacid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey',  'title')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `thumb` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'description')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `description` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'content')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `content` text;");
}
if(!pdo_fieldexists('cjwy_survey',  'stime')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `stime` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey',  'etime')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `etime` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey',  'success_info')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `success_info` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'max_num')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `max_num` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey',  'num')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `num` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey',  'hdp')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `hdp` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'bgpic')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `bgpic` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `copyright` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'copyrighturl')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `copyrighturl` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `sharetitle` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'sharepic')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `sharepic` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `sharedesc` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey',  'created')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `created` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `credit` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey',  'coupon')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey')." ADD `coupon` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_answer',  'said')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_answer')." ADD `said` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cjwy_survey_answer',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_answer')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_answer',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_answer')." ADD `sid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_answer',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_answer')." ADD `uniacid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_answer',  'sqid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_answer')." ADD `sqid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_answer',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_answer')." ADD `openid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_answer',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_answer')." ADD `uid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_answer',  'content')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_answer')." ADD `content` text;");
}
if(!pdo_fieldexists('cjwy_survey_answer',  'created')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_answer')." ADD `created` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey_question',  'sqid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `sqid` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('cjwy_survey_question',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_question',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `uniacid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_question',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `sid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_question',  'title')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey_question',  'description')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `description` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey_question',  'type')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `type` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey_question',  'extra')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `extra` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey_question',  'is_must')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `is_must` tinyint(1) unsigned DEFAULT '1';");
}
if(!pdo_fieldexists('cjwy_survey_question',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `is_show` tinyint(1) unsigned DEFAULT '1';");
}
if(!pdo_fieldexists('cjwy_survey_question',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `rule` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey_question',  'defvalue')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `defvalue` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('cjwy_survey_question',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `sort` smallint(5) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('cjwy_survey_question',  'created')) {
	pdo_query("ALTER TABLE ".tablename('cjwy_survey_question')." ADD `created` int(10) unsigned DEFAULT '0';");
}

?>