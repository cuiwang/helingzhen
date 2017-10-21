<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_weisrc_audio_music` (
  `mid` mediumint(8) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `url` varchar(500) NOT NULL COMMENT '歌曲链接',
  `title` char(255) NOT NULL COMMENT '歌曲名称',
  `cover` varchar(500) NOT NULL COMMENT '唱片封面',
  `singer` char(255) NOT NULL COMMENT '歌手',
  `intro` char(255) NOT NULL COMMENT '解说',
  `collect` int(11) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `displayorder` int(11) DEFAULT '0',
  `dateline` int(11) DEFAULT '0',
  PRIMARY KEY (`mid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_audio_music_user` (
  `did` mediumint(8) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `openid` char(255) NOT NULL,
  `mid` mediumint(8) NOT NULL,
  `title` char(255) NOT NULL,
  `cover` char(255) NOT NULL,
  `singer` char(255) NOT NULL,
  `intro` char(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL,
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_audio_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) DEFAULT '' COMMENT '版权名称',
  `bg` varchar(500) DEFAULT '' COMMENT '背景图',
  `bg_rand` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '随机背景',
  `bg_setting` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '随机背景',
  `bg_url` varchar(500) DEFAULT '' COMMENT '自定义背景图',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('weisrc_audio_music',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `mid` mediumint(8) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_audio_music',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_audio_music',  'url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `url` varchar(500) NOT NULL COMMENT '歌曲链接';");
}
if(!pdo_fieldexists('weisrc_audio_music',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `title` char(255) NOT NULL COMMENT '歌曲名称';");
}
if(!pdo_fieldexists('weisrc_audio_music',  'cover')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `cover` varchar(500) NOT NULL COMMENT '唱片封面';");
}
if(!pdo_fieldexists('weisrc_audio_music',  'singer')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `singer` char(255) NOT NULL COMMENT '歌手';");
}
if(!pdo_fieldexists('weisrc_audio_music',  'intro')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `intro` char(255) NOT NULL COMMENT '解说';");
}
if(!pdo_fieldexists('weisrc_audio_music',  'collect')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `collect` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_audio_music',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_audio_music',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_audio_music',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD `dateline` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('weisrc_audio_music',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music')." ADD KEY `status` (`status`);");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'did')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `did` mediumint(8) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `openid` char(255) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `mid` mediumint(8) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `title` char(255) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'cover')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `cover` char(255) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'singer')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `singer` char(255) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'intro')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `intro` char(255) NOT NULL;");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `status` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weisrc_audio_music_user',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_music_user')." ADD `dateline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_audio_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_setting')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_audio_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_setting')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weisrc_audio_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_setting')." ADD `title` varchar(50) DEFAULT '' COMMENT '版权名称';");
}
if(!pdo_fieldexists('weisrc_audio_setting',  'bg')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_setting')." ADD `bg` varchar(500) DEFAULT '' COMMENT '背景图';");
}
if(!pdo_fieldexists('weisrc_audio_setting',  'bg_rand')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_setting')." ADD `bg_rand` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '随机背景';");
}
if(!pdo_fieldexists('weisrc_audio_setting',  'bg_setting')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_setting')." ADD `bg_setting` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '随机背景';");
}
if(!pdo_fieldexists('weisrc_audio_setting',  'bg_url')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_setting')." ADD `bg_url` varchar(500) DEFAULT '' COMMENT '自定义背景图';");
}
if(!pdo_fieldexists('weisrc_audio_setting',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_audio_setting')." ADD `dateline` int(10) DEFAULT '0';");
}

?>