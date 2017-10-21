<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_lxy_marry_info` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `weid` bigint(20) unsigned DEFAULT NULL,
  `fromuser` varchar(32) DEFAULT NULL,
  `sid` bigint(20) unsigned DEFAULT NULL COMMENT 'micro_xitie_set id',
  `name` varchar(25) DEFAULT NULL,
  `tel` varchar(25) DEFAULT NULL,
  `rs` smallint(1) DEFAULT NULL COMMENT '赴宴人数',
  `zhufu` varchar(255) DEFAULT NULL COMMENT '收到祝福',
  `ctime` datetime DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '1:赴宴 2：祝福',
  PRIMARY KEY (`id`),
  KEY `idx_sid_openid` (`sid`,`fromuser`),
  KEY `idx_sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微喜帖信息列表';
CREATE TABLE IF NOT EXISTS `ims_lxy_marry_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `art_pic` varchar(255) DEFAULT NULL,
  `bg_pic` varchar(255) NOT NULL COMMENT '背景图片',
  `donghua_pic` varchar(255) DEFAULT NULL,
  `suolue_pic` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `xl_name` varchar(255) DEFAULT NULL,
  `xn_name` varchar(255) DEFAULT NULL,
  `is_front` varchar(255) DEFAULT '1' COMMENT '1:新郎名字在前 2:新娘名字在前',
  `tel` varchar(25) DEFAULT NULL,
  `hy_time` datetime DEFAULT NULL COMMENT '婚宴日期',
  `dist` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `hy_addr` varchar(255) DEFAULT NULL COMMENT '婚宴地址',
  `jw_addr` varchar(255) DEFAULT NULL COMMENT '经纬地址',
  `lng` varchar(12) DEFAULT '116.403694',
  `lat` varchar(12) DEFAULT '39.916042',
  `video` varchar(255) DEFAULT '/res/weiXiTie/mp4.mp4',
  `music` varchar(255) DEFAULT '/res/weiXiTie/youGotMe.mp3',
  `hs_pic` text COMMENT '婚纱图片',
  `pwd` varchar(255) DEFAULT NULL,
  `word` varchar(500) DEFAULT NULL,
  `erweima_pic` varchar(255) DEFAULT NULL COMMENT '二维码图片',
  `copyright` varchar(512) DEFAULT NULL COMMENT '版权',
  `createtime` int(11) unsigned DEFAULT NULL,
  `sendtitle` varchar(255) NOT NULL DEFAULT '',
  `senddescription` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微喜帖设置';
CREATE TABLE IF NOT EXISTS `ims_lxy_marry_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `marryid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('lxy_marry_info',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_marry_info',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `weid` bigint(20) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_info',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `fromuser` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_info',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `sid` bigint(20) unsigned DEFAULT NULL COMMENT 'micro_xitie_set id';");
}
if(!pdo_fieldexists('lxy_marry_info',  'name')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `name` varchar(25) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_info',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `tel` varchar(25) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_info',  'rs')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `rs` smallint(1) DEFAULT NULL COMMENT '赴宴人数';");
}
if(!pdo_fieldexists('lxy_marry_info',  'zhufu')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `zhufu` varchar(255) DEFAULT NULL COMMENT '收到祝福';");
}
if(!pdo_fieldexists('lxy_marry_info',  'ctime')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `ctime` datetime DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_info',  'type')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD `type` tinyint(1) DEFAULT '1' COMMENT '1:赴宴 2：祝福';");
}
if(!pdo_indexexists('lxy_marry_info',  'idx_sid_openid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD KEY `idx_sid_openid` (`sid`,`fromuser`);");
}
if(!pdo_indexexists('lxy_marry_info',  'idx_sid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_info')." ADD KEY `idx_sid` (`sid`);");
}
if(!pdo_fieldexists('lxy_marry_list',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_marry_list',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `weid` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `title` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'art_pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `art_pic` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'bg_pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `bg_pic` varchar(255) NOT NULL COMMENT '背景图片';");
}
if(!pdo_fieldexists('lxy_marry_list',  'donghua_pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `donghua_pic` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'suolue_pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `suolue_pic` varchar(255) DEFAULT NULL COMMENT '缩略图';");
}
if(!pdo_fieldexists('lxy_marry_list',  'xl_name')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `xl_name` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'xn_name')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `xn_name` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'is_front')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `is_front` varchar(255) DEFAULT '1' COMMENT '1:新郎名字在前 2:新娘名字在前';");
}
if(!pdo_fieldexists('lxy_marry_list',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `tel` varchar(25) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'hy_time')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `hy_time` datetime DEFAULT NULL COMMENT '婚宴日期';");
}
if(!pdo_fieldexists('lxy_marry_list',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `dist` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'city')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `city` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'province')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `province` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'hy_addr')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `hy_addr` varchar(255) DEFAULT NULL COMMENT '婚宴地址';");
}
if(!pdo_fieldexists('lxy_marry_list',  'jw_addr')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `jw_addr` varchar(255) DEFAULT NULL COMMENT '经纬地址';");
}
if(!pdo_fieldexists('lxy_marry_list',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `lng` varchar(12) DEFAULT '116.403694';");
}
if(!pdo_fieldexists('lxy_marry_list',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `lat` varchar(12) DEFAULT '39.916042';");
}
if(!pdo_fieldexists('lxy_marry_list',  'video')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `video` varchar(255) DEFAULT '/res/weiXiTie/mp4.mp4';");
}
if(!pdo_fieldexists('lxy_marry_list',  'music')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `music` varchar(255) DEFAULT '/res/weiXiTie/youGotMe.mp3';");
}
if(!pdo_fieldexists('lxy_marry_list',  'hs_pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `hs_pic` text COMMENT '婚纱图片';");
}
if(!pdo_fieldexists('lxy_marry_list',  'pwd')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `pwd` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'word')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `word` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'erweima_pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `erweima_pic` varchar(255) DEFAULT NULL COMMENT '二维码图片';");
}
if(!pdo_fieldexists('lxy_marry_list',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `copyright` varchar(512) DEFAULT NULL COMMENT '版权';");
}
if(!pdo_fieldexists('lxy_marry_list',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `createtime` int(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_marry_list',  'sendtitle')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `sendtitle` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('lxy_marry_list',  'senddescription')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_list')." ADD `senddescription` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('lxy_marry_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_marry_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('lxy_marry_reply',  'marryid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_reply')." ADD `marryid` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('lxy_marry_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_marry_reply')." ADD KEY `idx_rid` (`rid`);");
}

?>