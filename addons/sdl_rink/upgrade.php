<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_sdl_rink_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `nickname` varchar(50) DEFAULT '' COMMENT '用户昵称',
  `headimgurl` varchar(500) DEFAULT '' COMMENT '用户头像',
  `username` varchar(50) DEFAULT '' COMMENT '姓名',
  `tel` varchar(500) DEFAULT '' COMMENT '电话',
  `best_score` int(11) DEFAULT '0' COMMENT '单次最高分数',
  `lastplay_date` int(11) DEFAULT '0' COMMENT '参加时间',
  `lastshare_date` int(10) DEFAULT '0' COMMENT '最高分数参与时间',
  `isblack` tinyint(1) DEFAULT '0' COMMENT '是否列入黑名单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_sdl_rink_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT 'openid',
  `score` int(11) DEFAULT '0' COMMENT '分数',
  `playtime` int(10) DEFAULT '0' COMMENT '提交时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_sdl_rink_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '' COMMENT '标题',
  `isnow` tinyint(1) DEFAULT '0' COMMENT '是否收集用户信息',
  `picture` varchar(500) DEFAULT '' COMMENT 'logo',
  `ph_img` varchar(500) DEFAULT '' COMMENT '排行top',
  `mpp` varchar(500) DEFAULT '' COMMENT '背景音乐',
  `starttime` int(10) DEFAULT '0' COMMENT '游戏活动开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '游戏活动结束时间',
  `rule_img` varchar(500) DEFAULT '' COMMENT '规则top',
  `rule_txt` longtext COMMENT '规则内容',
  `share_title` varchar(200) DEFAULT '' COMMENT '分享标题',
  `share_desc` varchar(300) DEFAULT '' COMMENT '分享描述',
  `share_image` varchar(500) DEFAULT '' COMMENT '分享图标',
  `shared_title` varchar(200) DEFAULT '' COMMENT '分享标题',
  `shared_desc` varchar(300) DEFAULT '' COMMENT '分享描述',
  `shared_image` varchar(500) DEFAULT '' COMMENT '分享图标',
  `tw_title` varchar(200) DEFAULT '' COMMENT '图文标题',
  `tw_desc` varchar(300) DEFAULT '' COMMENT '图文描述',
  `tw_image` varchar(500) DEFAULT '' COMMENT '图文图标',
  `follow_url` varchar(100) DEFAULT '' COMMENT '引导关注链接',
  `isneedfollow` tinyint(1) DEFAULT '1' COMMENT '是否需要关注',
  `status` tinyint(1) DEFAULT '0' COMMENT '游戏状态',
  `vivt` int(10) DEFAULT '0' COMMENT '阅读量',
  `addvivt` int(10) DEFAULT '0' COMMENT '增加阅读量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('sdl_rink_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('sdl_rink_fans',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `from_user` varchar(50) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `nickname` varchar(50) DEFAULT '' COMMENT '用户昵称';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `headimgurl` varchar(500) DEFAULT '' COMMENT '用户头像';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'username')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `username` varchar(50) DEFAULT '' COMMENT '姓名';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `tel` varchar(500) DEFAULT '' COMMENT '电话';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'best_score')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `best_score` int(11) DEFAULT '0' COMMENT '单次最高分数';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'lastplay_date')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `lastplay_date` int(11) DEFAULT '0' COMMENT '参加时间';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'lastshare_date')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `lastshare_date` int(10) DEFAULT '0' COMMENT '最高分数参与时间';");
}
if(!pdo_fieldexists('sdl_rink_fans',  'isblack')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_fans')." ADD `isblack` tinyint(1) DEFAULT '0' COMMENT '是否列入黑名单';");
}
if(!pdo_fieldexists('sdl_rink_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('sdl_rink_record',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_record')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sdl_rink_record',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_record')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sdl_rink_record',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_record')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT 'openid';");
}
if(!pdo_fieldexists('sdl_rink_record',  'score')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_record')." ADD `score` int(11) DEFAULT '0' COMMENT '分数';");
}
if(!pdo_fieldexists('sdl_rink_record',  'playtime')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_record')." ADD `playtime` int(10) DEFAULT '0' COMMENT '提交时间';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('sdl_rink_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `title` varchar(50) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'isnow')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `isnow` tinyint(1) DEFAULT '0' COMMENT '是否收集用户信息';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'picture')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `picture` varchar(500) DEFAULT '' COMMENT 'logo';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'ph_img')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `ph_img` varchar(500) DEFAULT '' COMMENT '排行top';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'mpp')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `mpp` varchar(500) DEFAULT '' COMMENT '背景音乐';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `starttime` int(10) DEFAULT '0' COMMENT '游戏活动开始时间';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `endtime` int(10) DEFAULT '0' COMMENT '游戏活动结束时间';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'rule_img')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `rule_img` varchar(500) DEFAULT '' COMMENT '规则top';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'rule_txt')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `rule_txt` longtext COMMENT '规则内容';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `share_title` varchar(200) DEFAULT '' COMMENT '分享标题';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `share_desc` varchar(300) DEFAULT '' COMMENT '分享描述';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'share_image')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `share_image` varchar(500) DEFAULT '' COMMENT '分享图标';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'shared_title')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `shared_title` varchar(200) DEFAULT '' COMMENT '分享标题';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'shared_desc')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `shared_desc` varchar(300) DEFAULT '' COMMENT '分享描述';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'shared_image')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `shared_image` varchar(500) DEFAULT '' COMMENT '分享图标';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'tw_title')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `tw_title` varchar(200) DEFAULT '' COMMENT '图文标题';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'tw_desc')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `tw_desc` varchar(300) DEFAULT '' COMMENT '图文描述';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'tw_image')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `tw_image` varchar(500) DEFAULT '' COMMENT '图文图标';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `follow_url` varchar(100) DEFAULT '' COMMENT '引导关注链接';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'isneedfollow')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `isneedfollow` tinyint(1) DEFAULT '1' COMMENT '是否需要关注';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '游戏状态';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'vivt')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `vivt` int(10) DEFAULT '0' COMMENT '阅读量';");
}
if(!pdo_fieldexists('sdl_rink_reply',  'addvivt')) {
	pdo_query("ALTER TABLE ".tablename('sdl_rink_reply')." ADD `addvivt` int(10) DEFAULT '0' COMMENT '增加阅读量';");
}

?>