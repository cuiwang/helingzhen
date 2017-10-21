<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_fineness_admire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `author` varchar(255) NOT NULL COMMENT '昵称',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `ordersn` varchar(255) NOT NULL COMMENT '订单号',
  `thumb` varchar(500) NOT NULL COMMENT '头像',
  `status` varchar(2) NOT NULL COMMENT '是否显示',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '赞赏价格',
  `createtime` int(10) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL,
  `transid` varchar(100) NOT NULL,
  `tid` varchar(100) NOT NULL,
  `plid` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fineness_admire_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `price` decimal(10,1) DEFAULT NULL,
  `createtime` int(10) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fineness_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `pcateid` int(11) DEFAULT '0',
  `link` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `pid` int(10) unsigned DEFAULT '0' COMMENT '父ID',
  `zanNum` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幻灯片';
CREATE TABLE IF NOT EXISTS `ims_fineness_adv_er` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '广告标题',
  `thumb` varchar(500) NOT NULL COMMENT '广告图片',
  `link` varchar(500) NOT NULL COMMENT '广告外链',
  `type` tinyint(1) unsigned NOT NULL COMMENT '0商品推广1推荐公众',
  `description` varchar(500) NOT NULL COMMENT '广告外链',
  `status` varchar(2) NOT NULL COMMENT '是否显示',
  `createtime` int(10) NOT NULL,
  `weid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='随机广告';
CREATE TABLE IF NOT EXISTS `ims_fineness_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `musicurl` varchar(100) NOT NULL DEFAULT '' COMMENT '上传音乐',
  `content` mediumtext,
  `credit` varchar(255) DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板目录',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板名称',
  `author` varchar(300) NOT NULL DEFAULT '' COMMENT '作者',
  `bg_music_switch` varchar(1) NOT NULL DEFAULT '1',
  `clickNum` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '缩略图',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '简介',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `outLink` varchar(500) DEFAULT '' COMMENT '外链',
  `type` varchar(10) NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `tel` varchar(15) NOT NULL,
  `zanNum` int(10) unsigned NOT NULL DEFAULT '0',
  `iscomment` tinyint(1) DEFAULT '0',
  `isadmire` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否赞赏',
  `admiretxt` varchar(30) NOT NULL DEFAULT '' COMMENT '内容模板目录',
  `openid` varchar(50) DEFAULT '' COMMENT 'ip',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fineness_article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分类图片',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述',
  `template` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板目录',
  `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板名称',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fineness_article_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL,
  `read` int(11) NOT NULL DEFAULT '0',
  `like` int(11) NOT NULL DEFAULT '0',
  `comment` int(11) NOT NULL DEFAULT '0',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_aid` (`aid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fineness_article_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL DEFAULT '',
  `aid` int(11) DEFAULT '0',
  `cate` varchar(255) NOT NULL DEFAULT '',
  `cons` varchar(255) NOT NULL DEFAULT '',
  `uniacid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fineness_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `author` varchar(255) NOT NULL COMMENT '昵称',
  `openid` varchar(255) NOT NULL COMMENT '昵称',
  `thumb` varchar(500) NOT NULL COMMENT '头像',
  `js_cmt_input` varchar(500) NOT NULL COMMENT '留言内容',
  `js_cmt_reply` varchar(500) NOT NULL COMMENT '回复内容',
  `status` varchar(2) NOT NULL COMMENT '是否显示',
  `praise_num` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章评价';
CREATE TABLE IF NOT EXISTS `ims_fineness_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `guanzhuUrl` varchar(255) DEFAULT '' COMMENT '引导关注',
  `guanzhutitle` varchar(255) DEFAULT '' COMMENT '引导关注名称',
  `historyUrl` varchar(255) DEFAULT '' COMMENT '历史记录外链',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `cnzz` varchar(800) DEFAULT NULL,
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  `logo` varchar(255) DEFAULT '' COMMENT 'logo',
  `tjgzh` varchar(255) DEFAULT '1' COMMENT '推荐公众号图片',
  `tjgzhUrl` varchar(255) DEFAULT '1' COMMENT '推荐公众号引导关注',
  `isopen` varchar(1) DEFAULT '1',
  `title` varchar(255) DEFAULT '',
  `footlogo` varchar(255) DEFAULT '',
  `iscomment` varchar(1) DEFAULT '1',
  `isget` varchar(1) DEFAULT '0',
  `mchid` varchar(255) DEFAULT '',
  `shkey` varchar(500) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('fineness_admire',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_admire',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_admire',  'aid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID';");
}
if(!pdo_fieldexists('fineness_admire',  'author')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `author` varchar(255) NOT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('fineness_admire',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('fineness_admire',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `ordersn` varchar(255) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('fineness_admire',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `thumb` varchar(500) NOT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('fineness_admire',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `status` varchar(2) NOT NULL COMMENT '是否显示';");
}
if(!pdo_fieldexists('fineness_admire',  'price')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '赞赏价格';");
}
if(!pdo_fieldexists('fineness_admire',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fineness_admire',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `paytype` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_admire',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `transid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fineness_admire',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `tid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fineness_admire',  'plid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD `plid` varchar(100) NOT NULL;");
}
if(!pdo_indexexists('fineness_admire',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('fineness_admire_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire_set')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_admire_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire_set')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_admire_set',  'aid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire_set')." ADD `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID';");
}
if(!pdo_fieldexists('fineness_admire_set',  'price')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire_set')." ADD `price` decimal(10,1) DEFAULT NULL;");
}
if(!pdo_fieldexists('fineness_admire_set',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire_set')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fineness_admire_set',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire_set')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_indexexists('fineness_admire_set',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_admire_set')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('fineness_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_adv',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_adv',  'pcateid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD `pcateid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_adv',  'link')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD `link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_adv',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_adv',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD `pid` int(10) unsigned DEFAULT '0' COMMENT '父ID';");
}
if(!pdo_fieldexists('fineness_adv',  'zanNum')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD `zanNum` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('fineness_adv',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('fineness_adv_er',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv_er')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_adv_er',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv_er')." ADD `title` varchar(255) NOT NULL COMMENT '广告标题';");
}
if(!pdo_fieldexists('fineness_adv_er',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv_er')." ADD `thumb` varchar(500) NOT NULL COMMENT '广告图片';");
}
if(!pdo_fieldexists('fineness_adv_er',  'link')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv_er')." ADD `link` varchar(500) NOT NULL COMMENT '广告外链';");
}
if(!pdo_fieldexists('fineness_adv_er',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv_er')." ADD `type` tinyint(1) unsigned NOT NULL COMMENT '0商品推广1推荐公众';");
}
if(!pdo_fieldexists('fineness_adv_er',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv_er')." ADD `description` varchar(500) NOT NULL COMMENT '广告外链';");
}
if(!pdo_fieldexists('fineness_adv_er',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv_er')." ADD `status` varchar(2) NOT NULL COMMENT '是否显示';");
}
if(!pdo_fieldexists('fineness_adv_er',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv_er')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fineness_adv_er',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_adv_er')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_article',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_article',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fineness_article',  'musicurl')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `musicurl` varchar(100) NOT NULL DEFAULT '' COMMENT '上传音乐';");
}
if(!pdo_fieldexists('fineness_article',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `content` mediumtext;");
}
if(!pdo_fieldexists('fineness_article',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `credit` varchar(255) DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类';");
}
if(!pdo_fieldexists('fineness_article',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类';");
}
if(!pdo_fieldexists('fineness_article',  'template')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `template` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板目录';");
}
if(!pdo_fieldexists('fineness_article',  'templatefile')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '内容模板名称';");
}
if(!pdo_fieldexists('fineness_article',  'author')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `author` varchar(300) NOT NULL DEFAULT '' COMMENT '作者';");
}
if(!pdo_fieldexists('fineness_article',  'bg_music_switch')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `bg_music_switch` varchar(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('fineness_article',  'clickNum')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `clickNum` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `thumb` varchar(500) NOT NULL DEFAULT '' COMMENT '缩略图';");
}
if(!pdo_fieldexists('fineness_article',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `description` varchar(500) NOT NULL DEFAULT '' COMMENT '简介';");
}
if(!pdo_fieldexists('fineness_article',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('fineness_article',  'outLink')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `outLink` varchar(500) DEFAULT '' COMMENT '外链';");
}
if(!pdo_fieldexists('fineness_article',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `type` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('fineness_article',  'kid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `kid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_article',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_article',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `tel` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('fineness_article',  'zanNum')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `zanNum` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article',  'iscomment')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `iscomment` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article',  'isadmire')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `isadmire` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '是否赞赏';");
}
if(!pdo_fieldexists('fineness_article',  'admiretxt')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `admiretxt` varchar(30) NOT NULL DEFAULT '' COMMENT '内容模板目录';");
}
if(!pdo_fieldexists('fineness_article',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article')." ADD `openid` varchar(50) DEFAULT '' COMMENT 'ip';");
}
if(!pdo_fieldexists('fineness_article_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_article_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('fineness_article_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('fineness_article_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('fineness_article_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('fineness_article_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `thumb` varchar(1024) NOT NULL DEFAULT '' COMMENT '分类图片';");
}
if(!pdo_fieldexists('fineness_article_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `description` varchar(100) NOT NULL DEFAULT '' COMMENT '分类描述';");
}
if(!pdo_fieldexists('fineness_article_category',  'template')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `template` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板目录';");
}
if(!pdo_fieldexists('fineness_article_category',  'templatefile')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `templatefile` varchar(300) NOT NULL DEFAULT '' COMMENT '分类模板名称';");
}
if(!pdo_fieldexists('fineness_article_category',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article_category',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `type` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('fineness_article_category',  'kid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `kid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_article_category',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_category')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_article_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_article_log',  'aid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD `aid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_article_log',  'read')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD `read` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article_log',  'like')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD `like` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article_log',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD `comment` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('fineness_article_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('fineness_article_log',  'idx_aid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD KEY `idx_aid` (`aid`);");
}
if(!pdo_indexexists('fineness_article_log',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fineness_article_log',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_log')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('fineness_article_report',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_report')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_article_report',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_report')." ADD `openid` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fineness_article_report',  'aid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_report')." ADD `aid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_article_report',  'cate')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_report')." ADD `cate` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fineness_article_report',  'cons')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_report')." ADD `cons` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fineness_article_report',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_article_report')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_comment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_comment',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fineness_comment',  'aid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `aid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID';");
}
if(!pdo_fieldexists('fineness_comment',  'author')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `author` varchar(255) NOT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('fineness_comment',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `openid` varchar(255) NOT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('fineness_comment',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `thumb` varchar(500) NOT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('fineness_comment',  'js_cmt_input')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `js_cmt_input` varchar(500) NOT NULL COMMENT '留言内容';");
}
if(!pdo_fieldexists('fineness_comment',  'js_cmt_reply')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `js_cmt_reply` varchar(500) NOT NULL COMMENT '回复内容';");
}
if(!pdo_fieldexists('fineness_comment',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `status` varchar(2) NOT NULL COMMENT '是否显示';");
}
if(!pdo_fieldexists('fineness_comment',  'praise_num')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `praise_num` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_comment',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fineness_comment',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('fineness_comment')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('fineness_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fineness_sysset',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_sysset',  'guanzhuUrl')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `guanzhuUrl` varchar(255) DEFAULT '' COMMENT '引导关注';");
}
if(!pdo_fieldexists('fineness_sysset',  'guanzhutitle')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `guanzhutitle` varchar(255) DEFAULT '' COMMENT '引导关注名称';");
}
if(!pdo_fieldexists('fineness_sysset',  'historyUrl')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `historyUrl` varchar(255) DEFAULT '' COMMENT '历史记录外链';");
}
if(!pdo_fieldexists('fineness_sysset',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `copyright` varchar(255) DEFAULT '' COMMENT '版权';");
}
if(!pdo_fieldexists('fineness_sysset',  'cnzz')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `cnzz` varchar(800) DEFAULT NULL;");
}
if(!pdo_fieldexists('fineness_sysset',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `appid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_sysset',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `appsecret` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_sysset',  'appid_share')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `appid_share` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_sysset',  'appsecret_share')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `appsecret_share` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_sysset',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `logo` varchar(255) DEFAULT '' COMMENT 'logo';");
}
if(!pdo_fieldexists('fineness_sysset',  'tjgzh')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `tjgzh` varchar(255) DEFAULT '1' COMMENT '推荐公众号图片';");
}
if(!pdo_fieldexists('fineness_sysset',  'tjgzhUrl')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `tjgzhUrl` varchar(255) DEFAULT '1' COMMENT '推荐公众号引导关注';");
}
if(!pdo_fieldexists('fineness_sysset',  'isopen')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `isopen` varchar(1) DEFAULT '1';");
}
if(!pdo_fieldexists('fineness_sysset',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_sysset',  'footlogo')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `footlogo` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_sysset',  'iscomment')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `iscomment` varchar(1) DEFAULT '1';");
}
if(!pdo_fieldexists('fineness_sysset',  'isget')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `isget` varchar(1) DEFAULT '0';");
}
if(!pdo_fieldexists('fineness_sysset',  'mchid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `mchid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fineness_sysset',  'shkey')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD `shkey` varchar(500) DEFAULT '';");
}
if(!pdo_indexexists('fineness_sysset',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('fineness_sysset')." ADD KEY `indx_weid` (`weid`);");
}

?>