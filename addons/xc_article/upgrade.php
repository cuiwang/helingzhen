<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_xc_article_adv_cache` (
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `adv_on_off` varchar(10) NOT NULL DEFAULT 'off',
  `adv_top` text NOT NULL,
  `adv_status` text NOT NULL,
  `adv_bottom` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xc_article_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `iscommend` tinyint(1) NOT NULL DEFAULT '0',
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板目录',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板文件',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '内容配图',
  `sharethumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分享缩率图',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `recommendation` text NOT NULL COMMENT '推荐ID列表',
  `recommendation_source` varchar(20) NOT NULL COMMENT '推荐来源user自定义rand随机none没有',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `linkurl` varchar(500) NOT NULL DEFAULT '',
  `redirect_url` varchar(500) NOT NULL DEFAULT '',
  `share_credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享积分奖励',
  `click_credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击积分奖励',
  `max_credit` int(10) NOT NULL DEFAULT '0' COMMENT '积分奖励上限',
  `per_user_credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '单个用户送积分上限，0表示不限制',
  `praise_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `read_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `adv_on_off` varchar(10) NOT NULL DEFAULT 'off',
  `adv_top` text NOT NULL,
  `adv_status` text NOT NULL,
  `adv_bottom` text NOT NULL,
  `follow` tinyint(20) unsigned NOT NULL,
  `follow_url` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_iscommend` (`iscommend`),
  KEY `idx_ishot` (`ishot`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xc_article_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `nid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联导航id',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '分类图标',
  `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分类图片',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板目录',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板文件',
  `linkurl` varchar(500) NOT NULL DEFAULT '',
  `ishomepage` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xc_article_article_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `articleid` int(11) NOT NULL,
  `isfill` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xc_article_share_track` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `clicker_id` varchar(100) NOT NULL DEFAULT '',
  `shareby` varchar(100) NOT NULL DEFAULT '',
  `track_type` varchar(100) NOT NULL DEFAULT '',
  `track_sub_type` varchar(100) NOT NULL DEFAULT '',
  `track_msg` varchar(100) NOT NULL DEFAULT '',
  `detail_id` varchar(50) NOT NULL DEFAULT '' COMMENT '具体来源',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '文章标题',
  `extra` varchar(50) NOT NULL COMMENT '附加信息',
  `access_time` int(10) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('xc_article_adv_cache',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_adv_cache')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xc_article_adv_cache',  'adv_on_off')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_adv_cache')." ADD `adv_on_off` varchar(10) NOT NULL DEFAULT 'off';");
}
if(!pdo_fieldexists('xc_article_adv_cache',  'adv_top')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_adv_cache')." ADD `adv_top` text NOT NULL;");
}
if(!pdo_fieldexists('xc_article_adv_cache',  'adv_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_adv_cache')." ADD `adv_status` text NOT NULL;");
}
if(!pdo_fieldexists('xc_article_adv_cache',  'adv_bottom')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_adv_cache')." ADD `adv_bottom` text NOT NULL;");
}
if(!pdo_fieldexists('xc_article_article',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_article_article',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xc_article_article',  'iscommend')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `iscommend` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xc_article_article',  'ishot')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xc_article_article',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类';");
}
if(!pdo_fieldexists('xc_article_article',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类';");
}
if(!pdo_fieldexists('xc_article_article',  'template')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `template` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板目录';");
}
if(!pdo_fieldexists('xc_article_article',  'templatefile')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板文件';");
}
if(!pdo_fieldexists('xc_article_article',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_article',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `description` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_article',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('xc_article_article',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '内容配图';");
}
if(!pdo_fieldexists('xc_article_article',  'sharethumb')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `sharethumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分享缩率图';");
}
if(!pdo_fieldexists('xc_article_article',  'source')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `source` varchar(50) NOT NULL DEFAULT '' COMMENT '来源';");
}
if(!pdo_fieldexists('xc_article_article',  'author')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `author` varchar(50) NOT NULL COMMENT '作者';");
}
if(!pdo_fieldexists('xc_article_article',  'recommendation')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `recommendation` text NOT NULL COMMENT '推荐ID列表';");
}
if(!pdo_fieldexists('xc_article_article',  'recommendation_source')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `recommendation_source` varchar(20) NOT NULL COMMENT '推荐来源user自定义rand随机none没有';");
}
if(!pdo_fieldexists('xc_article_article',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xc_article_article',  'linkurl')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `linkurl` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_article',  'redirect_url')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `redirect_url` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_article',  'share_credit')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `share_credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享积分奖励';");
}
if(!pdo_fieldexists('xc_article_article',  'click_credit')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `click_credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击积分奖励';");
}
if(!pdo_fieldexists('xc_article_article',  'max_credit')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `max_credit` int(10) NOT NULL DEFAULT '0' COMMENT '积分奖励上限';");
}
if(!pdo_fieldexists('xc_article_article',  'per_user_credit')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `per_user_credit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '单个用户送积分上限，0表示不限制';");
}
if(!pdo_fieldexists('xc_article_article',  'praise_count')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `praise_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数';");
}
if(!pdo_fieldexists('xc_article_article',  'read_count')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `read_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '阅读数';");
}
if(!pdo_fieldexists('xc_article_article',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xc_article_article',  'adv_on_off')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `adv_on_off` varchar(10) NOT NULL DEFAULT 'off';");
}
if(!pdo_fieldexists('xc_article_article',  'adv_top')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `adv_top` text NOT NULL;");
}
if(!pdo_fieldexists('xc_article_article',  'adv_status')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `adv_status` text NOT NULL;");
}
if(!pdo_fieldexists('xc_article_article',  'adv_bottom')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `adv_bottom` text NOT NULL;");
}
if(!pdo_fieldexists('xc_article_article',  'follow')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `follow` tinyint(20) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xc_article_article',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD `follow_url` varchar(200) NOT NULL;");
}
if(!pdo_indexexists('xc_article_article',  'idx_iscommend')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD KEY `idx_iscommend` (`iscommend`);");
}
if(!pdo_indexexists('xc_article_article',  'idx_ishot')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article')." ADD KEY `idx_ishot` (`ishot`);");
}
if(!pdo_fieldexists('xc_article_article_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_article_article_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xc_article_article_category',  'nid')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `nid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联导航id';");
}
if(!pdo_fieldexists('xc_article_article_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('xc_article_article_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('xc_article_article_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xc_article_article_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('xc_article_article_category',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `icon` varchar(100) NOT NULL DEFAULT '' COMMENT '分类图标';");
}
if(!pdo_fieldexists('xc_article_article_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分类图片';");
}
if(!pdo_fieldexists('xc_article_article_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述';");
}
if(!pdo_fieldexists('xc_article_article_category',  'template')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `template` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板目录';");
}
if(!pdo_fieldexists('xc_article_article_category',  'templatefile')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板文件';");
}
if(!pdo_fieldexists('xc_article_article_category',  'linkurl')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `linkurl` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_article_category',  'ishomepage')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_category')." ADD `ishomepage` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xc_article_article_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_article_article_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_reply')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xc_article_article_reply',  'articleid')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_reply')." ADD `articleid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xc_article_article_reply',  'isfill')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_article_reply')." ADD `isfill` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xc_article_share_track',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xc_article_share_track',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xc_article_share_track',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `credit` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xc_article_share_track',  'clicker_id')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `clicker_id` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_share_track',  'shareby')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `shareby` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_share_track',  'track_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `track_type` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_share_track',  'track_sub_type')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `track_sub_type` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_share_track',  'track_msg')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `track_msg` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xc_article_share_track',  'detail_id')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `detail_id` varchar(50) NOT NULL DEFAULT '' COMMENT '具体来源';");
}
if(!pdo_fieldexists('xc_article_share_track',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `title` varchar(50) NOT NULL DEFAULT '' COMMENT '文章标题';");
}
if(!pdo_fieldexists('xc_article_share_track',  'extra')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `extra` varchar(50) NOT NULL COMMENT '附加信息';");
}
if(!pdo_fieldexists('xc_article_share_track',  'access_time')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `access_time` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xc_article_share_track',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('xc_article_share_track')." ADD `ip` varchar(64) NOT NULL;");
}

?>