<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_weliam_shifcar_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自动排序',
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `name` varchar(32) NOT NULL COMMENT '名称',
  `picture` varchar(64) NOT NULL COMMENT '图标',
  `sort` int(11) NOT NULL COMMENT '排序',
  `is_show` int(2) NOT NULL COMMENT '是否显示',
  `createtime` varchar(32) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='问题分类表';
CREATE TABLE IF NOT EXISTS `ims_weliam_shifcar_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `title` varchar(64) NOT NULL COMMENT '名称，不能长于20个汉字',
  `answer` text NOT NULL COMMENT '解答内容',
  `categoryid` int(11) NOT NULL COMMENT '分类id',
  `createtime` varchar(32) NOT NULL COMMENT '创建时间',
  `is_show` int(2) NOT NULL COMMENT '是否展示',
  `scan` int(11) NOT NULL COMMENT '浏览量',
  `sort` int(11) NOT NULL COMMENT '排序',
  `is_importent` int(11) NOT NULL COMMENT '是否是重要问题',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='问题解答清单';
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL,
  `name` varchar(500) NOT NULL,
  `visible` tinyint(4) unsigned NOT NULL,
  `displayorder` tinyint(11) unsigned NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `isShow` (`visible`),
  KEY `parentId` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_advertisement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  `advtype` int(11) NOT NULL,
  `cardnumber` varchar(100) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `signtime` varchar(100) DEFAULT NULL,
  `issettime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_apirecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `sendmid` int(11) NOT NULL,
  `sendmobile` varchar(15) DEFAULT NULL,
  `takemid` int(11) NOT NULL,
  `takemobile` varchar(15) DEFAULT NULL,
  `type` smallint(2) NOT NULL,
  `remark` varchar(32) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `ordersn` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `area` varchar(32) NOT NULL,
  `address` varchar(100) NOT NULL,
  `status` smallint(2) NOT NULL,
  `express` varchar(32) DEFAULT NULL,
  `expresssn` varchar(32) DEFAULT NULL,
  `sendtime` int(11) DEFAULT NULL,
  `signtime` int(11) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  `postage` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(50) NOT NULL,
  `imgs` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brandid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `comment` varchar(500) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_error` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `data` text,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_hidenotice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `touser` text,
  `num` int(11) NOT NULL,
  `address` varchar(500) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_limitlinetpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(32) NOT NULL,
  `limitweek` varchar(300) NOT NULL,
  `limitday` varchar(300) NOT NULL,
  `data` text NOT NULL,
  `islimittime` smallint(2) NOT NULL,
  `limittime` varchar(300) NOT NULL,
  `status` smallint(2) NOT NULL,
  `createtime` int(11) NOT NULL,
  `reason` varchar(300) NOT NULL,
  `region` varchar(500) NOT NULL,
  `interval` varchar(300) NOT NULL,
  `isshare` int(11) NOT NULL,
  `shareid` int(11) NOT NULL,
  `isnumber` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_mcrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `model` int(11) NOT NULL,
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `times` int(11) NOT NULL,
  `remid` int(11) NOT NULL,
  `remark` varchar(300) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `invid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `unionid` varchar(100) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `credit1` decimal(10,2) NOT NULL,
  `credit2` decimal(10,2) NOT NULL,
  `gender` int(11) NOT NULL,
  `avatar` varchar(300) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `plate1` varchar(5) NOT NULL,
  `plate2` varchar(5) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `engine_number` varchar(50) NOT NULL,
  `frame_number` varchar(50) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `brandimg` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `mstatus` int(3) NOT NULL,
  `userstatus` int(2) NOT NULL,
  `ncnumber` varchar(30) NOT NULL,
  `message` varchar(200) NOT NULL,
  `harrystatus` int(11) NOT NULL,
  `harrytime1` int(11) NOT NULL,
  `harrytime2` int(11) NOT NULL,
  `acttime` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  `tasktime` int(11) NOT NULL,
  `limitlinetime` int(11) NOT NULL,
  `hidestatus` int(11) NOT NULL,
  `hidetime` int(11) NOT NULL,
  `hidelng` varchar(50) DEFAULT NULL,
  `hidelat` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_membercard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `credit1` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `times` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_nearby` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `data_img` text NOT NULL,
  `content` text NOT NULL,
  `contact` varchar(100) NOT NULL,
  `allimg` text NOT NULL,
  `lng` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附近商户';
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_oplog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `describe` varchar(225) DEFAULT NULL,
  `view_url` varchar(225) DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `data` varchar(1024) DEFAULT NULL,
  `createtime` varchar(32) DEFAULT NULL,
  `user` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_peccrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `hphm` varchar(32) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `acttime` varchar(50) DEFAULT NULL,
  `code` int(11) DEFAULT NULL,
  `status` smallint(2) DEFAULT NULL,
  `info` varchar(100) DEFAULT NULL,
  `fen` int(11) DEFAULT NULL,
  `money` varchar(32) DEFAULT NULL,
  `content` text,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_puv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `pv` int(11) NOT NULL,
  `uv` int(11) NOT NULL,
  `date` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_puvrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `pv` int(11) NOT NULL,
  `date` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_qrcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) DEFAULT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `qrid` int(10) unsigned NOT NULL,
  `model` tinyint(1) NOT NULL,
  `cardsn` varchar(64) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `remark` varchar(50) NOT NULL,
  `salt` varchar(32) DEFAULT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `qrid` (`qrid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `sendmid` int(11) NOT NULL,
  `takemid` int(11) NOT NULL,
  `longitude` varchar(10) DEFAULT NULL,
  `latitude` varchar(10) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  `comment` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sendmid` (`sendmid`),
  KEY `takemid` (`takemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_sclass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_smstpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(32) NOT NULL,
  `smstplid` varchar(32) NOT NULL,
  `data` text NOT NULL,
  `status` smallint(2) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_srecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_sug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `mid` int(11) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_waitmessage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `type` tinyint(3) unsigned NOT NULL,
  `str` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index` (`type`,`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_weliam_shiftcar_wechataddr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  `addressid` int(11) NOT NULL,
  `address` varchar(50) NOT NULL,
  `acid` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('weliam_shifcar_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_category')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自动排序';");
}
if(!pdo_fieldexists('weliam_shifcar_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_category')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('weliam_shifcar_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_category')." ADD `name` varchar(32) NOT NULL COMMENT '名称';");
}
if(!pdo_fieldexists('weliam_shifcar_category',  'picture')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_category')." ADD `picture` varchar(64) NOT NULL COMMENT '图标';");
}
if(!pdo_fieldexists('weliam_shifcar_category',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_category')." ADD `sort` int(11) NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('weliam_shifcar_category',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_category')." ADD `is_show` int(2) NOT NULL COMMENT '是否显示';");
}
if(!pdo_fieldexists('weliam_shifcar_category',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_category')." ADD `createtime` varchar(32) NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'title')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `title` varchar(64) NOT NULL COMMENT '名称，不能长于20个汉字';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'answer')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `answer` text NOT NULL COMMENT '解答内容';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'categoryid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `categoryid` int(11) NOT NULL COMMENT '分类id';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `createtime` varchar(32) NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `is_show` int(2) NOT NULL COMMENT '是否展示';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'scan')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `scan` int(11) NOT NULL COMMENT '浏览量';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `sort` int(11) NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('weliam_shifcar_question',  'is_importent')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shifcar_question')." ADD `is_importent` int(11) NOT NULL COMMENT '是否是重要问题';");
}
if(!pdo_fieldexists('weliam_shiftcar_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_address')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_address',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_address')." ADD `pid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_address',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_address')." ADD `name` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_address',  'visible')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_address')." ADD `visible` tinyint(4) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_address',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_address')." ADD `displayorder` tinyint(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_address',  'level')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_address')." ADD `level` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_indexexists('weliam_shiftcar_address',  'isShow')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_address')." ADD KEY `isShow` (`visible`);");
}
if(!pdo_indexexists('weliam_shiftcar_address',  'parentId')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_address')." ADD KEY `parentId` (`pid`);");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'position')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `position` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'advtype')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `advtype` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'cardnumber')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `cardnumber` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `remark` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'signtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `signtime` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_advertisement',  'issettime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_advertisement')." ADD `issettime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apirecord',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_apirecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apirecord',  'sendmid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD `sendmid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apirecord',  'sendmobile')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD `sendmobile` varchar(15) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apirecord',  'takemid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD `takemid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apirecord',  'takemobile')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD `takemobile` varchar(15) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apirecord',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD `type` smallint(2) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apirecord',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD `remark` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apirecord',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_indexexists('weliam_shiftcar_apirecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apirecord')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `ordersn` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `name` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `mobile` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'area')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `area` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `address` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `status` smallint(2) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'express')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `express` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'expresssn')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `expresssn` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'sendtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `sendtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'signtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `signtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_apply',  'postage')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD `postage` decimal(10,2) NOT NULL;");
}
if(!pdo_indexexists('weliam_shiftcar_apply',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_apply')." ADD KEY `mid` (`mid`);");
}
if(!pdo_fieldexists('weliam_shiftcar_brand',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_brand')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_brand',  'brand')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_brand')." ADD `brand` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_brand',  'imgs')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_brand')." ADD `imgs` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_class',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_class')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_class',  'brandid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_class')." ADD `brandid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_class',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_class')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_comment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_comment')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_comment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_comment')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_comment',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_comment')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_comment',  'reid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_comment')." ADD `reid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_comment',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_comment')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_comment',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_comment')." ADD `comment` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_comment',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_comment')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_error',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_error')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_error',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_error')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_error',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_error')." ADD `type` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_error',  'data')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_error')." ADD `data` text;");
}
if(!pdo_fieldexists('weliam_shiftcar_error',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_error')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_hidenotice',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_hidenotice')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_hidenotice',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_hidenotice')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_hidenotice',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_hidenotice')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_hidenotice',  'touser')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_hidenotice')." ADD `touser` text;");
}
if(!pdo_fieldexists('weliam_shiftcar_hidenotice',  'num')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_hidenotice')." ADD `num` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_hidenotice',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_hidenotice')." ADD `address` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_hidenotice',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_hidenotice')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `type` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'limitweek')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `limitweek` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'limitday')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `limitday` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'data')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `data` text NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'islimittime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `islimittime` smallint(2) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'limittime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `limittime` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `status` smallint(2) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'reason')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `reason` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'region')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `region` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'interval')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `interval` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'isshare')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `isshare` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'shareid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `shareid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_limitlinetpl',  'isnumber')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_limitlinetpl')." ADD `isnumber` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `sid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `type` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'model')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `model` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `fee` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'times')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `times` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'remid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `remid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `remark` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_mcrecord',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_mcrecord')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'invid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `invid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'unionid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `unionid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `realname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'credit1')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `credit1` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'credit2')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `credit2` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'gender')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `gender` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `avatar` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `mobile` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'city')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'province')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'plate1')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `plate1` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'plate2')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `plate2` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'plate_number')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `plate_number` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'engine_number')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `engine_number` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'frame_number')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `frame_number` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'brand')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `brand` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'brandimg')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `brandimg` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'mstatus')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `mstatus` int(3) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'userstatus')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `userstatus` int(2) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'ncnumber')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `ncnumber` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'message')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `message` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'harrystatus')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `harrystatus` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'harrytime1')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `harrytime1` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'harrytime2')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `harrytime2` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'acttime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `acttime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'tasktime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `tasktime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'limitlinetime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `limitlinetime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'hidestatus')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `hidestatus` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'hidetime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `hidetime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'hidelng')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `hidelng` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_member',  'hidelat')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_member')." ADD `hidelat` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_membercard',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_membercard')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_membercard',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_membercard')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_membercard',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_membercard')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_membercard',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_membercard')." ADD `sid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_membercard',  'credit1')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_membercard')." ADD `credit1` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('weliam_shiftcar_membercard',  'credit2')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_membercard')." ADD `credit2` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('weliam_shiftcar_membercard',  'times')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_membercard')." ADD `times` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_membercard',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_membercard')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `address` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `mobile` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'data_img')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `data_img` text NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'contact')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `contact` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'allimg')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `allimg` text NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `lng` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `lat` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_nearby',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_nearby')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_oplog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_oplog')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_oplog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_oplog')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_oplog',  'describe')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_oplog')." ADD `describe` varchar(225) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_oplog',  'view_url')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_oplog')." ADD `view_url` varchar(225) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_oplog',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_oplog')." ADD `ip` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_oplog',  'data')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_oplog')." ADD `data` varchar(1024) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_oplog',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_oplog')." ADD `createtime` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_oplog',  'user')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_oplog')." ADD `user` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'hphm')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `hphm` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `address` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'acttime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `acttime` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'code')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `code` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `status` smallint(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'info')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `info` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'fen')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `fen` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'money')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `money` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `content` text;");
}
if(!pdo_fieldexists('weliam_shiftcar_peccrecord',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD `createtime` int(11) DEFAULT NULL;");
}
if(!pdo_indexexists('weliam_shiftcar_peccrecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('weliam_shiftcar_peccrecord',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_peccrecord')." ADD KEY `mid` (`mid`);");
}
if(!pdo_fieldexists('weliam_shiftcar_puv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_puv',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puv')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_puv',  'pv')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puv')." ADD `pv` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_puv',  'uv')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puv')." ADD `uv` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_puv',  'date')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puv')." ADD `date` varchar(20) NOT NULL;");
}
if(!pdo_indexexists('weliam_shiftcar_puv',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puv')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('weliam_shiftcar_puvrecord',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puvrecord')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_puvrecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puvrecord')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_puvrecord',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puvrecord')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_puvrecord',  'pv')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puvrecord')." ADD `pv` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_puvrecord',  'date')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puvrecord')." ADD `date` varchar(20) NOT NULL;");
}
if(!pdo_indexexists('weliam_shiftcar_puvrecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puvrecord')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('weliam_shiftcar_puvrecord',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_puvrecord')." ADD KEY `mid` (`mid`);");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `mid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'qrid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `qrid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'model')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `model` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'cardsn')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `cardsn` varchar(64) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `remark` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'salt')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `salt` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_qrcode',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD `sid` int(11) NOT NULL;");
}
if(!pdo_indexexists('weliam_shiftcar_qrcode',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('weliam_shiftcar_qrcode',  'qrid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_qrcode')." ADD KEY `qrid` (`qrid`);");
}
if(!pdo_fieldexists('weliam_shiftcar_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_record',  'sendmid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD `sendmid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_record',  'takemid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD `takemid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_record',  'longitude')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD `longitude` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_record',  'latitude')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD `latitude` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_record',  'location')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD `location` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_record',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD `comment` int(11) NOT NULL;");
}
if(!pdo_indexexists('weliam_shiftcar_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('weliam_shiftcar_record',  'sendmid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD KEY `sendmid` (`sendmid`);");
}
if(!pdo_indexexists('weliam_shiftcar_record',  'takemid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_record')." ADD KEY `takemid` (`takemid`);");
}
if(!pdo_fieldexists('weliam_shiftcar_sclass',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_sclass')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_sclass',  'classid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_sclass')." ADD `classid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_sclass',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_sclass')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_setting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_setting')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_setting',  'key')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_setting')." ADD `key` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_setting',  'value')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_setting')." ADD `value` text NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_smstpl',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_smstpl')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_smstpl',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_smstpl')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_smstpl',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_smstpl')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_smstpl',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_smstpl')." ADD `type` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_smstpl',  'smstplid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_smstpl')." ADD `smstplid` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_smstpl',  'data')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_smstpl')." ADD `data` text NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_smstpl',  'status')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_smstpl')." ADD `status` smallint(2) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_smstpl',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_smstpl')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_srecord',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_srecord')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_srecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_srecord')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_srecord',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_srecord')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_srecord',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_srecord')." ADD `type` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_srecord',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_srecord')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_sug',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_sug')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_sug',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_sug')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_sug',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_sug')." ADD `mid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_sug',  'content')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_sug')." ADD `content` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_sug',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_sug')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_waitmessage',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_waitmessage')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_waitmessage',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_waitmessage')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_waitmessage',  'type')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_waitmessage')." ADD `type` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_waitmessage',  'str')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_waitmessage')." ADD `str` varchar(100) DEFAULT NULL;");
}
if(!pdo_indexexists('weliam_shiftcar_waitmessage',  'index')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_waitmessage')." ADD KEY `index` (`type`,`uniacid`);");
}
if(!pdo_fieldexists('weliam_shiftcar_wechataddr',  'id')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_wechataddr')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('weliam_shiftcar_wechataddr',  'name')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_wechataddr')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_wechataddr',  'key')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_wechataddr')." ADD `key` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_wechataddr',  'addressid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_wechataddr')." ADD `addressid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_wechataddr',  'address')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_wechataddr')." ADD `address` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_wechataddr',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_wechataddr')." ADD `acid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('weliam_shiftcar_wechataddr',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('weliam_shiftcar_wechataddr')." ADD `createtime` int(11) NOT NULL;");
}

?>