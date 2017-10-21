<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_fm_autogroup_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `iscommend` tinyint(1) NOT NULL DEFAULT '0',
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `thumb` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(500) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_iscommend` (`iscommend`),
  KEY `idx_ishot` (`ishot`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_autogroup_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_autogroup_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `gname` varchar(50) NOT NULL COMMENT '用户的唯一ID',
  `count` varchar(1000) NOT NULL DEFAULT '',
  `ydcount` varchar(10) NOT NULL,
  `gid` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `uniacid` int(10) NOT NULL,
  `fscount` int(10) unsigned NOT NULL COMMENT '发送次数',
  `wcount` int(10) unsigned NOT NULL COMMENT '发送次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_autogroup_grouplist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `nid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联导航id',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `daihao` varchar(50) NOT NULL COMMENT '分类代号',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `icontype` tinyint(1) unsigned NOT NULL,
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '分类图标',
  `css` varchar(500) NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板',
  `templatefile` varchar(100) NOT NULL DEFAULT '',
  `linkurl` varchar(500) NOT NULL DEFAULT '',
  `ishomepage` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_autogroup_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) NOT NULL,
  `from_user` varchar(50) NOT NULL COMMENT '用户的唯一身份ID',
  `gid` int(10) NOT NULL,
  `gname` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `content` varchar(255) NOT NULL,
  `fscount` int(10) unsigned NOT NULL COMMENT '发送次数',
  `createtime` int(10) unsigned NOT NULL,
  `lastupdate` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_autogroup_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user` varchar(50) NOT NULL COMMENT '用户的唯一身份ID',
  `rid` int(10) unsigned NOT NULL,
  `isjoin` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `isblacklist` tinyint(1) NOT NULL DEFAULT '0',
  `lastupdate` int(10) unsigned NOT NULL COMMENT '用户时间',
  `createtime` int(10) unsigned NOT NULL COMMENT '用户注册时间',
  `gid` int(10) NOT NULL,
  `gname` varchar(255) NOT NULL,
  `uniacid` int(10) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `fscount` int(10) unsigned NOT NULL COMMENT '发送次数',
  `wcount` int(10) unsigned NOT NULL COMMENT '发送次数',
  `follow` int(10) unsigned NOT NULL COMMENT '关注状态',
  `followtrue` int(10) unsigned NOT NULL COMMENT '是否取消过',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_autogroup_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `from_user` varchar(50) NOT NULL COMMENT '用户的唯一ID',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `type` varchar(10) NOT NULL,
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_autogroup_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(300) NOT NULL DEFAULT '',
  `content` varchar(300) NOT NULL DEFAULT '',
  `description` varchar(300) NOT NULL DEFAULT '',
  `uniacid` int(10) NOT NULL,
  `timeout` int(10) unsigned NOT NULL DEFAULT '0',
  `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `createtime` varchar(255) NOT NULL DEFAULT '',
  `viewnum` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('fm_autogroup_article',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_autogroup_article',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_article',  'iscommend')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `iscommend` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'ishot')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'template')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `template` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `description` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_article',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `thumb` varchar(100) NOT NULL DEFAULT '' COMMENT '缩略图';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'source')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'author')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `author` varchar(50) NOT NULL COMMENT '作者';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'linkurl')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `linkurl` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_article',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('fm_autogroup_article',  'idx_iscommend')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD KEY `idx_iscommend` (`iscommend`);");
}
if(!pdo_indexexists('fm_autogroup_article',  'idx_ishot')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_article')." ADD KEY `idx_ishot` (`ishot`);");
}
if(!pdo_fieldexists('fm_autogroup_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_award')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_autogroup_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_award')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_award',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_award')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_award',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_award')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_award',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_award')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_group',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_autogroup_group',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('fm_autogroup_group',  'gname')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `gname` varchar(50) NOT NULL COMMENT '用户的唯一ID';");
}
if(!pdo_fieldexists('fm_autogroup_group',  'count')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `count` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_group',  'ydcount')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `ydcount` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_group',  'gid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `gid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_autogroup_group',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_group',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_group',  'fscount')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `fscount` int(10) unsigned NOT NULL COMMENT '发送次数';");
}
if(!pdo_fieldexists('fm_autogroup_group',  'wcount')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_group')." ADD `wcount` int(10) unsigned NOT NULL COMMENT '发送次数';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'nid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `nid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联导航id';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'name')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'daihao')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `daihao` varchar(50) NOT NULL COMMENT '分类代号';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'icontype')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `icontype` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '分类图标';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'css')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `css` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'template')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `template` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'templatefile')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `templatefile` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'linkurl')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `linkurl` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_grouplist',  'ishomepage')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_grouplist')." ADD `ishomepage` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_autogroup_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_autogroup_log',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_log',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `from_user` varchar(50) NOT NULL COMMENT '用户的唯一身份ID';");
}
if(!pdo_fieldexists('fm_autogroup_log',  'gid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `gid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_log',  'gname')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `gname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_log',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `nickname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_log',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `content` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_log',  'fscount')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `fscount` int(10) unsigned NOT NULL COMMENT '发送次数';");
}
if(!pdo_fieldexists('fm_autogroup_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_log',  'lastupdate')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_log')." ADD `lastupdate` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_members',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_autogroup_members',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `from_user` varchar(50) NOT NULL COMMENT '用户的唯一身份ID';");
}
if(!pdo_fieldexists('fm_autogroup_members',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_members',  'isjoin')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `isjoin` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('fm_autogroup_members',  'isblacklist')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `isblacklist` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_autogroup_members',  'lastupdate')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `lastupdate` int(10) unsigned NOT NULL COMMENT '用户时间';");
}
if(!pdo_fieldexists('fm_autogroup_members',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '用户注册时间';");
}
if(!pdo_fieldexists('fm_autogroup_members',  'gid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `gid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_members',  'gname')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `gname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_members',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_members',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `nickname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_members',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `mobile` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_members',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_members',  'fscount')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `fscount` int(10) unsigned NOT NULL COMMENT '发送次数';");
}
if(!pdo_fieldexists('fm_autogroup_members',  'wcount')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `wcount` int(10) unsigned NOT NULL COMMENT '发送次数';");
}
if(!pdo_fieldexists('fm_autogroup_members',  'follow')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `follow` int(10) unsigned NOT NULL COMMENT '关注状态';");
}
if(!pdo_fieldexists('fm_autogroup_members',  'followtrue')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_members')." ADD `followtrue` int(10) unsigned NOT NULL COMMENT '是否取消过';");
}
if(!pdo_fieldexists('fm_autogroup_message',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_message')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_autogroup_message',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_message')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('fm_autogroup_message',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_message')." ADD `from_user` varchar(50) NOT NULL COMMENT '用户的唯一ID';");
}
if(!pdo_fieldexists('fm_autogroup_message',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_message')." ADD `content` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_message',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_message')." ADD `type` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_message',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_message')." ADD `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示';");
}
if(!pdo_fieldexists('fm_autogroup_message',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_message')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `title` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `content` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `description` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'timeout')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `timeout` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `logo` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `createtime` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_autogroup_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_autogroup_reply')." ADD `viewnum` int(10) NOT NULL;");
}

?>