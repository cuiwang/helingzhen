<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_weishare` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `title` varchar(100) NOT NULL COMMENT '活动标题',
  `thumb` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(100) NOT NULL COMMENT '活动描述',
  `image` varchar(100) NOT NULL COMMENT '背景图片',
  `max` int(11) NOT NULL COMMENT '得分极限',
  `start` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分值',
  `step` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力积分',
  `steprandom` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '助力随机积分',
  `steptype` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力随机积分方式',
  `rule` text NOT NULL COMMENT '规则',
  `url` varchar(250) NOT NULL COMMENT '引导关注素材',
  `count` int(11) NOT NULL COMMENT '领卡数量限制',
  `background` varchar(100) NOT NULL COMMENT '背景颜色',
  `tip` varchar(100) NOT NULL COMMENT '提示语',
  `unit` varchar(100) NOT NULL COMMENT '单位',
  `cardname` varchar(100) NOT NULL COMMENT '卡片名称',
  `helplimit` int(11) NOT NULL COMMENT '每天助力限制次数',
  `totallimit` int(11) NOT NULL COMMENT '总得助力次数',
  `limittype` int(1) NOT NULL COMMENT '限制类型',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  `endtime` int(11) unsigned NOT NULL COMMENT '日期',
  `shareIcon` varchar(200) NOT NULL COMMENT '分享图标',
  `shareTitle` varchar(200) NOT NULL,
  `shareContent` varchar(200) NOT NULL,
  `copyright` varchar(100) NOT NULL COMMENT '版权',
  `showu` varchar(1) NOT NULL DEFAULT '0',
  `sortcount` varchar(100) NOT NULL DEFAULT '10',
  `zl_rule` int(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weishare_firend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '分享用户的id',
  `sid` int(10) NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weishare_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `sid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `new_title` varchar(100) NOT NULL COMMENT '图文标题',
  `new_pic` varchar(100) NOT NULL COMMENT '图文图片',
  `new_desc` varchar(100) NOT NULL COMMENT '图文描述',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weishare_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `appid` varchar(200) NOT NULL COMMENT 'appid',
  `secret` varchar(200) NOT NULL COMMENT 'secret',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weishare_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID',
  `tel` varchar(50) NOT NULL,
  `income` float(10,2) unsigned NOT NULL DEFAULT '0.00',
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  `helpcount` int(11) DEFAULT '0' COMMENT '助力次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('weishare',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weishare',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `weid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('weishare',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `title` varchar(100) NOT NULL COMMENT '活动标题';");
}
if(!pdo_fieldexists('weishare',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `thumb` varchar(100) NOT NULL COMMENT '活动图片';");
}
if(!pdo_fieldexists('weishare',  'description')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `description` varchar(100) NOT NULL COMMENT '活动描述';");
}
if(!pdo_fieldexists('weishare',  'image')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `image` varchar(100) NOT NULL COMMENT '背景图片';");
}
if(!pdo_fieldexists('weishare',  'max')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `max` int(11) NOT NULL COMMENT '得分极限';");
}
if(!pdo_fieldexists('weishare',  'start')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `start` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分值';");
}
if(!pdo_fieldexists('weishare',  'step')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `step` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力积分';");
}
if(!pdo_fieldexists('weishare',  'steprandom')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `steprandom` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '助力随机积分';");
}
if(!pdo_fieldexists('weishare',  'steptype')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `steptype` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力随机积分方式';");
}
if(!pdo_fieldexists('weishare',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `rule` text NOT NULL COMMENT '规则';");
}
if(!pdo_fieldexists('weishare',  'url')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `url` varchar(250) NOT NULL COMMENT '引导关注素材';");
}
if(!pdo_fieldexists('weishare',  'count')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `count` int(11) NOT NULL COMMENT '领卡数量限制';");
}
if(!pdo_fieldexists('weishare',  'background')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `background` varchar(100) NOT NULL COMMENT '背景颜色';");
}
if(!pdo_fieldexists('weishare',  'tip')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `tip` varchar(100) NOT NULL COMMENT '提示语';");
}
if(!pdo_fieldexists('weishare',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `unit` varchar(100) NOT NULL COMMENT '单位';");
}
if(!pdo_fieldexists('weishare',  'cardname')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `cardname` varchar(100) NOT NULL COMMENT '卡片名称';");
}
if(!pdo_fieldexists('weishare',  'helplimit')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `helplimit` int(11) NOT NULL COMMENT '每天助力限制次数';");
}
if(!pdo_fieldexists('weishare',  'totallimit')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `totallimit` int(11) NOT NULL COMMENT '总得助力次数';");
}
if(!pdo_fieldexists('weishare',  'limittype')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `limittype` int(1) NOT NULL COMMENT '限制类型';");
}
if(!pdo_fieldexists('weishare',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '日期';");
}
if(!pdo_fieldexists('weishare',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `endtime` int(11) unsigned NOT NULL COMMENT '日期';");
}
if(!pdo_fieldexists('weishare',  'shareIcon')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `shareIcon` varchar(200) NOT NULL COMMENT '分享图标';");
}
if(!pdo_fieldexists('weishare',  'shareTitle')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `shareTitle` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weishare',  'shareContent')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `shareContent` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weishare',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `copyright` varchar(100) NOT NULL COMMENT '版权';");
}
if(!pdo_fieldexists('weishare',  'showu')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `showu` varchar(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weishare',  'sortcount')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `sortcount` varchar(100) NOT NULL DEFAULT '10';");
}
if(!pdo_fieldexists('weishare',  'zl_rule')) {
	pdo_query("ALTER TABLE ".tablename('weishare')." ADD `zl_rule` int(20) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weishare_firend',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weishare_firend')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weishare_firend',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('weishare_firend')." ADD `uid` int(10) NOT NULL DEFAULT '0' COMMENT '分享用户的id';");
}
if(!pdo_fieldexists('weishare_firend',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('weishare_firend')." ADD `sid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weishare_firend',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('weishare_firend')." ADD `openid` varchar(50) NOT NULL COMMENT '用户唯一身份ID';");
}
if(!pdo_fieldexists('weishare_firend',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weishare_firend')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '日期';");
}
if(!pdo_fieldexists('weishare_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weishare_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weishare_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('weishare_reply')." ADD `rid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('weishare_reply',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('weishare_reply')." ADD `sid` int(10) unsigned NOT NULL COMMENT '规则ID';");
}
if(!pdo_fieldexists('weishare_reply',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('weishare_reply')." ADD `new_title` varchar(100) NOT NULL COMMENT '图文标题';");
}
if(!pdo_fieldexists('weishare_reply',  'new_pic')) {
	pdo_query("ALTER TABLE ".tablename('weishare_reply')." ADD `new_pic` varchar(100) NOT NULL COMMENT '图文图片';");
}
if(!pdo_fieldexists('weishare_reply',  'new_desc')) {
	pdo_query("ALTER TABLE ".tablename('weishare_reply')." ADD `new_desc` varchar(100) NOT NULL COMMENT '图文描述';");
}
if(!pdo_indexexists('weishare_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('weishare_reply')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_fieldexists('weishare_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weishare_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weishare_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('weishare_setting')." ADD `weid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('weishare_setting',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('weishare_setting')." ADD `appid` varchar(200) NOT NULL COMMENT 'appid';");
}
if(!pdo_fieldexists('weishare_setting',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('weishare_setting')." ADD `secret` varchar(200) NOT NULL COMMENT 'secret';");
}
if(!pdo_fieldexists('weishare_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weishare_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weishare_user',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('weishare_user')." ADD `sid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weishare_user',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('weishare_user')." ADD `from_user` varchar(50) NOT NULL COMMENT '用户唯一身份ID';");
}
if(!pdo_fieldexists('weishare_user',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('weishare_user')." ADD `tel` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weishare_user',  'income')) {
	pdo_query("ALTER TABLE ".tablename('weishare_user')." ADD `income` float(10,2) unsigned NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('weishare_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weishare_user')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '日期';");
}
if(!pdo_fieldexists('weishare_user',  'helpcount')) {
	pdo_query("ALTER TABLE ".tablename('weishare_user')." ADD `helpcount` int(11) DEFAULT '0' COMMENT '助力次数';");
}

?>