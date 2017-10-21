<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '相册名称',
  `subtitle` varchar(255) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id ims_lxy_buildpro_set table id',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `jianjie` text,
  `pic` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='楼盘相册';
CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `pic1` varchar(255) DEFAULT NULL,
  `pic2` varchar(255) DEFAULT NULL,
  `pic3` varchar(255) DEFAULT NULL,
  `pic4` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='楼盘海报';
CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_expert_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `expert_name` varchar(20) DEFAULT NULL,
  `zhiwei` varchar(255) DEFAULT NULL COMMENT '专家职位',
  `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序',
  `jianjie` text,
  `content` text COMMENT '点评内容',
  `thumb` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='楼盘-专家点评';
CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_fell` (
  `yid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `yinxiang_number` int(11) unsigned DEFAULT '0' COMMENT '印象数',
  `isshow` tinyint(1) DEFAULT '1',
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`yid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='房友印象';
CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_fell_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `fromuser` varchar(255) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='房友印象';
CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_full_view` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hsid` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `quanjinglink` varchar(500) DEFAULT NULL COMMENT '全景外链',
  `pic_qian` varchar(1023) DEFAULT NULL,
  `pic_hou` varchar(1023) DEFAULT NULL,
  `pic_zuo` varchar(1023) DEFAULT NULL,
  `pic_you` varchar(1023) DEFAULT NULL,
  `pic_shang` varchar(1023) DEFAULT NULL,
  `pic_xia` varchar(1023) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='楼盘户型全景';
CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_head` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `xcname` varchar(255) DEFAULT NULL,
  `headpic` varchar(255) DEFAULT NULL,
  `apartpic` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `dist` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `jw_addr` varchar(255) DEFAULT NULL,
  `lng` varchar(12) DEFAULT '116.403694',
  `lat` varchar(12) DEFAULT '39.916042',
  `jianjie` text,
  `xiangmu` text,
  `jiaotong` text,
  `addr` varchar(255) DEFAULT NULL,
  `yyurl` varchar(500) DEFAULT NULL,
  `xwurl` varchar(500) DEFAULT NULL,
  `hyurl` varchar(500) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `lxname` varchar(50) DEFAULT NULL,
  `hyname` varchar(50) DEFAULT NULL,
  `yyname` varchar(50) DEFAULT NULL,
  `xwname` varchar(50) DEFAULT NULL,
  `yxname` varchar(50) DEFAULT NULL,
  `hxname` varchar(50) DEFAULT NULL,
  `jjname` varchar(50) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='楼盘简介';
CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_house` (
  `hsid` int(11) NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '户型名称',
  `sid` int(11) DEFAULT NULL COMMENT '子楼盘 ims_lxy_buildpro_set id',
  `louceng` smallint(1) DEFAULT NULL COMMENT '楼层',
  `mianji` varchar(255) DEFAULT NULL COMMENT '建筑面积',
  `fang` tinyint(4) DEFAULT NULL,
  `ting` tinyint(4) DEFAULT NULL,
  `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序',
  `jianjie` text,
  `pic` text,
  `picjson` text,
  `createtime` int(11) DEFAULT NULL,
  PRIMARY KEY (`hsid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='楼盘户型';
CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `hid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lxy_buildpro_sub` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `hid` int(11) DEFAULT NULL COMMENT '楼盘id',
  `title` varchar(255) DEFAULT NULL COMMENT '子楼盘名称',
  `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序',
  `jianjie` text,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='子楼盘';
";
pdo_run($sql);
if(!pdo_fieldexists('lxy_buildpro_album',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_album')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_album',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_album')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_album',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_album')." ADD `title` varchar(255) DEFAULT NULL COMMENT '相册名称';");
}
if(!pdo_fieldexists('lxy_buildpro_album',  'subtitle')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_album')." ADD `subtitle` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_album',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_album')." ADD `hid` int(11) DEFAULT NULL COMMENT '楼盘id ims_lxy_buildpro_set table id';");
}
if(!pdo_fieldexists('lxy_buildpro_album',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_album')." ADD `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('lxy_buildpro_album',  'jianjie')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_album')." ADD `jianjie` text;");
}
if(!pdo_fieldexists('lxy_buildpro_album',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_album')." ADD `pic` text;");
}
if(!pdo_fieldexists('lxy_buildpro_bill',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_bill')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_bill',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_bill')." ADD `hid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_bill',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_bill')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_bill',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_bill')." ADD `title` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_bill',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_bill')." ADD `pic` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_bill',  'pic1')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_bill')." ADD `pic1` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_bill',  'pic2')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_bill')." ADD `pic2` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_bill',  'pic3')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_bill')." ADD `pic3` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_bill',  'pic4')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_bill')." ADD `pic4` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `hid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `title` varchar(255) DEFAULT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'expert_name')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `expert_name` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'zhiwei')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `zhiwei` varchar(255) DEFAULT NULL COMMENT '专家职位';");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'jianjie')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `jianjie` text;");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'content')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `content` text COMMENT '点评内容';");
}
if(!pdo_fieldexists('lxy_buildpro_expert_comment',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_expert_comment')." ADD `thumb` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_fell',  'yid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell')." ADD `yid` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_fell',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_fell',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell')." ADD `hid` int(11) DEFAULT NULL COMMENT '楼盘id';");
}
if(!pdo_fieldexists('lxy_buildpro_fell',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell')." ADD `title` varchar(255) DEFAULT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('lxy_buildpro_fell',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell')." ADD `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('lxy_buildpro_fell',  'yinxiang_number')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell')." ADD `yinxiang_number` int(11) unsigned DEFAULT '0' COMMENT '印象数';");
}
if(!pdo_fieldexists('lxy_buildpro_fell',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell')." ADD `isshow` tinyint(1) DEFAULT '1';");
}
if(!pdo_fieldexists('lxy_buildpro_fell',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell')." ADD `createtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_fell_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_fell_record',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell_record')." ADD `hid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_fell_record',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell_record')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_fell_record',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell_record')." ADD `fromuser` varchar(255) DEFAULT NULL COMMENT '楼盘id';");
}
if(!pdo_fieldexists('lxy_buildpro_fell_record',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_fell_record')." ADD `title` varchar(255) DEFAULT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'hsid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `hsid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `title` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'quanjinglink')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `quanjinglink` varchar(500) DEFAULT NULL COMMENT '全景外链';");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'pic_qian')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `pic_qian` varchar(1023) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'pic_hou')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `pic_hou` varchar(1023) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'pic_zuo')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `pic_zuo` varchar(1023) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'pic_you')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `pic_you` varchar(1023) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'pic_shang')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `pic_shang` varchar(1023) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'pic_xia')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `pic_xia` varchar(1023) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `sort` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_full_view',  'status')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_full_view')." ADD `status` tinyint(4) DEFAULT '1';");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `hid` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `title` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `pic` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `desc` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'xcname')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `xcname` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'headpic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `headpic` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'apartpic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `apartpic` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'video')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `video` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `dist` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'city')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `city` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'province')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `province` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'jw_addr')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `jw_addr` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `lng` varchar(12) DEFAULT '116.403694';");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `lat` varchar(12) DEFAULT '39.916042';");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'jianjie')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `jianjie` text;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'xiangmu')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `xiangmu` text;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'jiaotong')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `jiaotong` text;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'addr')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `addr` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'yyurl')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `yyurl` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'xwurl')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `xwurl` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'hyurl')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `hyurl` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `tel` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'lxname')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `lxname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'hyname')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `hyname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'yyname')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `yyname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'xwname')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `xwname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'yxname')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `yxname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'hxname')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `hxname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'jjname')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `jjname` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_head',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_head')." ADD `createtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'hsid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `hsid` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `hid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `title` varchar(255) DEFAULT NULL COMMENT '户型名称';");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `sid` int(11) DEFAULT NULL COMMENT '子楼盘 ims_lxy_buildpro_set id';");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'louceng')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `louceng` smallint(1) DEFAULT NULL COMMENT '楼层';");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'mianji')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `mianji` varchar(255) DEFAULT NULL COMMENT '建筑面积';");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'fang')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `fang` tinyint(4) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'ting')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `ting` tinyint(4) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `sort` tinyint(4) unsigned DEFAULT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'jianjie')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `jianjie` text;");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `pic` text;");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'picjson')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `picjson` text;");
}
if(!pdo_fieldexists('lxy_buildpro_house',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_house')." ADD `createtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('lxy_buildpro_reply',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_reply')." ADD `hid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_sub',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_sub')." ADD `sid` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lxy_buildpro_sub',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_sub')." ADD `weid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lxy_buildpro_sub',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_sub')." ADD `hid` int(11) DEFAULT NULL COMMENT '楼盘id';");
}
if(!pdo_fieldexists('lxy_buildpro_sub',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_sub')." ADD `title` varchar(255) DEFAULT NULL COMMENT '子楼盘名称';");
}
if(!pdo_fieldexists('lxy_buildpro_sub',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_sub')." ADD `sort` tinyint(4) unsigned DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('lxy_buildpro_sub',  'jianjie')) {
	pdo_query("ALTER TABLE ".tablename('lxy_buildpro_sub')." ADD `jianjie` text;");
}

?>