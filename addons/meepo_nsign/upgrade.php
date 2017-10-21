<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_nsign_add` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `shop` text NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `thumb` text NOT NULL,
  `content` text NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_nsign_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `award` text NOT NULL,
  `time` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_nsign_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` text NOT NULL,
  `today_rank` int(11) NOT NULL,
  `sign_time` int(11) NOT NULL,
  `last_sign_time` int(11) NOT NULL,
  `continue_sign_days` int(11) NOT NULL,
  `maxcontinue_sign_days` int(11) NOT NULL,
  `total_sign_num` int(11) NOT NULL,
  `maxtotal_sign_num` int(11) NOT NULL,
  `first_sign_days` int(11) NOT NULL,
  `maxfirst_sign_days` int(11) NOT NULL,
  `credit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_nsign_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `title` text NOT NULL,
  `picture` text NOT NULL,
  `description` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('nsign_add',  'id')) {
	pdo_query("ALTER TABLE ".tablename('nsign_add')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('nsign_add',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('nsign_add')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_add',  'shop')) {
	pdo_query("ALTER TABLE ".tablename('nsign_add')." ADD `shop` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_add',  'title')) {
	pdo_query("ALTER TABLE ".tablename('nsign_add')." ADD `title` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_add',  'description')) {
	pdo_query("ALTER TABLE ".tablename('nsign_add')." ADD `description` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_add',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('nsign_add')." ADD `thumb` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_add',  'content')) {
	pdo_query("ALTER TABLE ".tablename('nsign_add')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_add',  'type')) {
	pdo_query("ALTER TABLE ".tablename('nsign_add')." ADD `type` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('nsign_prize')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('nsign_prize',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('nsign_prize')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_prize',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('nsign_prize')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_prize',  'name')) {
	pdo_query("ALTER TABLE ".tablename('nsign_prize')." ADD `name` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_prize',  'type')) {
	pdo_query("ALTER TABLE ".tablename('nsign_prize')." ADD `type` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_prize',  'award')) {
	pdo_query("ALTER TABLE ".tablename('nsign_prize')." ADD `award` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_prize',  'time')) {
	pdo_query("ALTER TABLE ".tablename('nsign_prize')." ADD `time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_prize',  'num')) {
	pdo_query("ALTER TABLE ".tablename('nsign_prize')." ADD `num` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_prize',  'status')) {
	pdo_query("ALTER TABLE ".tablename('nsign_prize')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('nsign_record',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'username')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `username` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'today_rank')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `today_rank` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'sign_time')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `sign_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'last_sign_time')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `last_sign_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'continue_sign_days')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `continue_sign_days` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'maxcontinue_sign_days')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `maxcontinue_sign_days` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'total_sign_num')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `total_sign_num` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'maxtotal_sign_num')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `maxtotal_sign_num` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'first_sign_days')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `first_sign_days` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'maxfirst_sign_days')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `maxfirst_sign_days` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_record',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('nsign_record')." ADD `credit` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('nsign_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('nsign_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('nsign_reply')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('nsign_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('nsign_reply')." ADD `title` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_reply',  'picture')) {
	pdo_query("ALTER TABLE ".tablename('nsign_reply')." ADD `picture` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('nsign_reply')." ADD `description` text NOT NULL;");
}
if(!pdo_fieldexists('nsign_reply',  'content')) {
	pdo_query("ALTER TABLE ".tablename('nsign_reply')." ADD `content` text NOT NULL;");
}

?>