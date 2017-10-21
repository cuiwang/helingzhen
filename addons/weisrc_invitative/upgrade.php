<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_weisrc_invitative_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(10) unsigned DEFAULT '0',
  `reply_title` varchar(100) DEFAULT '图文标题',
  `description` varchar(255) DEFAULT '' COMMENT '描述',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '封面',
  `title` varchar(100) DEFAULT '' COMMENT '活动标题',
  `content` text NOT NULL COMMENT '活动介绍',
  `organizers` varchar(100) DEFAULT '' COMMENT '举办者',
  `bg` varchar(500) DEFAULT '' COMMENT '背景',
  `cardtype` tinyint(1) DEFAULT '1' COMMENT '卡片类型',
  `cardbg` varchar(500) DEFAULT '' COMMENT '卡片背景',
  `thumbs` varchar(1000) DEFAULT '' COMMENT '活动图片',
  `musicurl` varchar(500) DEFAULT '' COMMENT '音乐链接',
  `tel` varchar(20) NOT NULL COMMENT '联系电话',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `place` varchar(200) NOT NULL DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `displayorder` int(11) DEFAULT '0',
  `starttime` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_invitative_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `activityid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weisrc_invitative_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT '0',
  `from_user` varchar(100) DEFAULT '',
  `activityid` int(10) unsigned NOT NULL DEFAULT '0',
  `nickname` varchar(100) DEFAULT '',
  `headimgurl` varchar(500) DEFAULT '',
  `username` varchar(100) DEFAULT '' COMMENT '用户名称',
  `tel` varchar(50) DEFAULT '' COMMENT '联系电话',
  `company` varchar(200) DEFAULT '' COMMENT '公司',
  `position` varchar(200) DEFAULT '' COMMENT '职位',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `dateline` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('weisrc_invitative_activity',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `weid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'reply_title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `reply_title` varchar(100) DEFAULT '图文标题';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'description')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `description` varchar(255) DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '封面';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `title` varchar(100) DEFAULT '' COMMENT '活动标题';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `content` text NOT NULL COMMENT '活动介绍';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'organizers')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `organizers` varchar(100) DEFAULT '' COMMENT '举办者';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'bg')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `bg` varchar(500) DEFAULT '' COMMENT '背景';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'cardtype')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `cardtype` tinyint(1) DEFAULT '1' COMMENT '卡片类型';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'cardbg')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `cardbg` varchar(500) DEFAULT '' COMMENT '卡片背景';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `thumbs` varchar(1000) DEFAULT '' COMMENT '活动图片';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'musicurl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `musicurl` varchar(500) DEFAULT '' COMMENT '音乐链接';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `tel` varchar(20) NOT NULL COMMENT '联系电话';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `address` varchar(200) NOT NULL COMMENT '地址';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'place')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `place` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `starttime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `endtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_invitative_activity',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD `dateline` int(10) DEFAULT '0';");
}
if(!pdo_indexexists('weisrc_invitative_activity',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('weisrc_invitative_activity',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_activity')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('weisrc_invitative_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_invitative_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_invitative_reply',  'activityid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_reply')." ADD `activityid` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('weisrc_invitative_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_reply')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `weid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `from_user` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'activityid')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `activityid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `nickname` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `headimgurl` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'username')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `username` varchar(100) DEFAULT '' COMMENT '用户名称';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `tel` varchar(50) DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'company')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `company` varchar(200) DEFAULT '' COMMENT '公司';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'position')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `position` varchar(200) DEFAULT '' COMMENT '职位';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('weisrc_invitative_user',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('weisrc_invitative_user')." ADD `dateline` int(10) DEFAULT '0';");
}

?>