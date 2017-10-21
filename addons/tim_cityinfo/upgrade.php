<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_tim_city_cate` (
  `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `model` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '默认模板',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_city_dislike` (
  `dislike_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `mem_id` int(10) NOT NULL,
  PRIMARY KEY (`dislike_id`),
  KEY `mem_id` (`mem_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_city_event` (
  `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `mem_id` int(10) NOT NULL,
  `cate_id` int(10) DEFAULT NULL,
  `event_title` varchar(100) DEFAULT NULL,
  `event_content` text,
  `job_name` varchar(20) DEFAULT NULL,
  `salary` varchar(20) DEFAULT NULL,
  `company` varchar(30) DEFAULT NULL,
  `company_scale` varchar(20) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL,
  `is_agree` int(2) DEFAULT '0' COMMENT '未审核',
  `job_require` varchar(400) DEFAULT NULL,
  `recruit_num` int(3) DEFAULT NULL,
  `house_address` varchar(200) DEFAULT NULL,
  `house_style` varchar(100) DEFAULT NULL,
  `house_dolar` int(6) DEFAULT NULL,
  `house_area` float DEFAULT NULL,
  `house_new` varchar(20) DEFAULT NULL,
  `house_orient` varchar(20) DEFAULT NULL,
  `house_floor` varchar(20) DEFAULT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `first_fee` double DEFAULT NULL,
  `is_first` tinyint(2) DEFAULT '0',
  `realname` varchar(100) DEFAULT NULL,
  `mem_tel` varchar(15) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `short_img` varchar(200) DEFAULT NULL,
  `fresh` int(2) DEFAULT NULL,
  `read_num` int(10) DEFAULT '0',
  `location` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`event_id`),
  KEY `cate_id` (`cate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_city_like` (
  `like_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `mem_id` int(10) NOT NULL,
  PRIMARY KEY (`like_id`),
  KEY `mem_id` (`mem_id`),
  KEY `event_id` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_city_member` (
  `mem_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(200) NOT NULL,
  `mem_name` varchar(100) DEFAULT NULL,
  `realname` varchar(100) DEFAULT NULL,
  `mem_photo` varchar(200) DEFAULT NULL,
  `mem_pass` varchar(200) DEFAULT NULL,
  `mem_tel` varchar(15) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `mem_dolar` double DEFAULT NULL,
  PRIMARY KEY (`mem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_city_recharge` (
  `recharge_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `recharge_no` varchar(30) DEFAULT NULL,
  `mem_id` int(10) NOT NULL,
  `recharge_dolar` double DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`recharge_id`),
  KEY `mem_id` (`mem_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_city_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `appid` varchar(200) DEFAULT NULL,
  `appsecret` varchar(200) DEFAULT NULL,
  `logo` varchar(200) DEFAULT NULL,
  `footer_info` varchar(200) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `first_perfee` double DEFAULT NULL,
  `default_img` varchar(200) DEFAULT NULL,
  `fresh_fee` double DEFAULT NULL,
  `is_agree` tinyint(2) DEFAULT NULL,
  `is_quyu` tinyint(2) DEFAULT '0' COMMENT '1未开启，0开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tim_city_slide` (
  `slide_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `slide_pic` varchar(200) DEFAULT NULL,
  `slide_title` varchar(100) DEFAULT NULL,
  `slide_url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`slide_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('tim_city_cate',  'cate_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_cate')." ADD `cate_id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_city_cate',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_cate')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('tim_city_cate',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_cate')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('tim_city_cate',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_cate')." ADD `thumb` varchar(255) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists('tim_city_cate',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_cate')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('tim_city_cate',  'description')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_cate')." ADD `description` varchar(500) NOT NULL COMMENT '分类介绍';");
}
if(!pdo_fieldexists('tim_city_cate',  'model')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_cate')." ADD `model` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '默认模板';");
}
if(!pdo_fieldexists('tim_city_cate',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_cate')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('tim_city_cate',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_cate')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示';");
}
if(!pdo_fieldexists('tim_city_dislike',  'dislike_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_dislike')." ADD `dislike_id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_city_dislike',  'event_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_dislike')." ADD `event_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tim_city_dislike',  'mem_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_dislike')." ADD `mem_id` int(10) NOT NULL;");
}
if(!pdo_indexexists('tim_city_dislike',  'mem_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_dislike')." ADD KEY `mem_id` (`mem_id`);");
}
if(!pdo_indexexists('tim_city_dislike',  'event_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_dislike')." ADD KEY `event_id` (`event_id`);");
}
if(!pdo_fieldexists('tim_city_event',  'event_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `event_id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_city_event',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'mem_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `mem_id` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'cate_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `cate_id` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'event_title')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `event_title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'event_content')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `event_content` text;");
}
if(!pdo_fieldexists('tim_city_event',  'job_name')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `job_name` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'salary')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `salary` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'company')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `company` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'company_scale')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `company_scale` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `create_time` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'is_agree')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `is_agree` int(2) DEFAULT '0' COMMENT '未审核';");
}
if(!pdo_fieldexists('tim_city_event',  'job_require')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `job_require` varchar(400) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'recruit_num')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `recruit_num` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'house_address')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `house_address` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'house_style')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `house_style` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'house_dolar')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `house_dolar` int(6) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'house_area')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `house_area` float DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'house_new')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `house_new` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'house_orient')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `house_orient` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'house_floor')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `house_floor` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `starttime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `endtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'first_fee')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `first_fee` double DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'is_first')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `is_first` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('tim_city_event',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `realname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'mem_tel')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `mem_tel` varchar(15) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'address')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `address` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'short_img')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `short_img` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'fresh')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `fresh` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_event',  'read_num')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `read_num` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('tim_city_event',  'location')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD `location` varchar(20) DEFAULT NULL;");
}
if(!pdo_indexexists('tim_city_event',  'cate_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_event')." ADD KEY `cate_id` (`cate_id`);");
}
if(!pdo_fieldexists('tim_city_like',  'like_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_like')." ADD `like_id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_city_like',  'event_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_like')." ADD `event_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tim_city_like',  'mem_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_like')." ADD `mem_id` int(10) NOT NULL;");
}
if(!pdo_indexexists('tim_city_like',  'mem_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_like')." ADD KEY `mem_id` (`mem_id`);");
}
if(!pdo_indexexists('tim_city_like',  'event_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_like')." ADD KEY `event_id` (`event_id`);");
}
if(!pdo_fieldexists('tim_city_member',  'mem_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_member')." ADD `mem_id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_city_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_member')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tim_city_member',  'mem_name')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_member')." ADD `mem_name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_member',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_member')." ADD `realname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_member',  'mem_photo')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_member')." ADD `mem_photo` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_member',  'mem_pass')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_member')." ADD `mem_pass` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_member',  'mem_tel')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_member')." ADD `mem_tel` varchar(15) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_member',  'address')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_member')." ADD `address` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_member',  'mem_dolar')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_member')." ADD `mem_dolar` double DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_recharge',  'recharge_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_recharge')." ADD `recharge_id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_city_recharge',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_recharge')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tim_city_recharge',  'recharge_no')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_recharge')." ADD `recharge_no` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_recharge',  'mem_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_recharge')." ADD `mem_id` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tim_city_recharge',  'recharge_dolar')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_recharge')." ADD `recharge_dolar` double DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_recharge',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_recharge')." ADD `create_time` int(10) DEFAULT NULL;");
}
if(!pdo_indexexists('tim_city_recharge',  'mem_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_recharge')." ADD KEY `mem_id` (`mem_id`);");
}
if(!pdo_fieldexists('tim_city_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_city_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `appid` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `appsecret` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `logo` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'footer_info')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `footer_info` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'first_perfee')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `first_perfee` double DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'default_img')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `default_img` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'fresh_fee')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `fresh_fee` double DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'is_agree')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `is_agree` tinyint(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_set',  'is_quyu')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_set')." ADD `is_quyu` tinyint(2) DEFAULT '0' COMMENT '1未开启，0开启';");
}
if(!pdo_fieldexists('tim_city_slide',  'slide_id')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_slide')." ADD `slide_id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tim_city_slide',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_slide')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tim_city_slide',  'slide_pic')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_slide')." ADD `slide_pic` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_slide',  'slide_title')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_slide')." ADD `slide_title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tim_city_slide',  'slide_url')) {
	pdo_query("ALTER TABLE ".tablename('tim_city_slide')." ADD `slide_url` varchar(200) DEFAULT NULL;");
}

?>