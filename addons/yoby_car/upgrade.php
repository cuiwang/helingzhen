<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_yoby_car` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '行程表',
  `weid` int(10) DEFAULT NULL,
  `openid` varchar(65) DEFAULT NULL COMMENT '用户唯一',
  `address1` varchar(200) DEFAULT NULL COMMENT '出发地',
  `address2` varchar(200) DEFAULT NULL COMMENT '目的地',
  `createtime` int(10) DEFAULT NULL COMMENT '发布时间',
  `sendtime` varchar(20) DEFAULT NULL COMMENT '出发时间',
  `num` int(10) DEFAULT '1' COMMENT '人数',
  `rmb` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `isok` tinyint(1) DEFAULT '1' COMMENT '1进行中2结束,3关闭',
  `beizhu` varchar(1024) DEFAULT NULL COMMENT '备注附加',
  `type` tinyint(1) DEFAULT '1' COMMENT '1是乘客2是车主',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yoby_car_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告管理',
  `weid` int(10) DEFAULT NULL,
  `img` varchar(200) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `isok` tinyint(1) DEFAULT '0' COMMENT '1是下线',
  `orderby` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yoby_car_add` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '加入车主拼车',
  `weid` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `openid` varchar(65) DEFAULT NULL COMMENT '加入者openid',
  `cid` int(10) DEFAULT NULL COMMENT '拼车id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yoby_car_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '信息表',
  `weid` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL COMMENT '注册时间',
  `type` tinyint(1) DEFAULT '1' COMMENT '1是乘客2是车主',
  `title` varchar(50) DEFAULT NULL COMMENT '姓名',
  `sex` tinyint(1) DEFAULT '1' COMMENT '1男2女',
  `phone` varchar(15) DEFAULT NULL COMMENT '手机',
  `cid` varchar(200) DEFAULT NULL COMMENT '身份证',
  `sid` varchar(200) DEFAULT NULL COMMENT '车主需要驾照',
  `isok` tinyint(1) DEFAULT '0' COMMENT '0是未认证1是认证',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `headimgurl` varchar(200) DEFAULT NULL COMMENT '头像',
  `openid` varchar(65) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i` (`weid`,`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yoby_car_huodong` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动介绍',
  `weid` int(10) DEFAULT NULL,
  `title` varchar(40) DEFAULT NULL COMMENT '标题',
  `url` varchar(512) DEFAULT NULL COMMENT '连接',
  `createtime` int(10) DEFAULT NULL,
  `orderby` int(10) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yoby_car_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '积分记录',
  `weid` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `f` tinyint(1) DEFAULT '3' COMMENT '积分来源1发布文章2分享3阅读',
  `jifen` decimal(10,2) DEFAULT '0.00' COMMENT '积分数',
  `rectime` varchar(10) DEFAULT NULL COMMENT '时间年月日',
  `tid` int(10) DEFAULT NULL COMMENT '文章id',
  `openid` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yoby_car_say` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言表',
  `weid` int(10) DEFAULT NULL,
  `from_openid` varchar(500) DEFAULT NULL COMMENT '发布者openid',
  `to_openid` varchar(500) DEFAULT NULL COMMENT '接收者openid',
  `content` varchar(1000) DEFAULT NULL COMMENT '内容',
  `createtime` int(10) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yoby_car_zanzhu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '赞助商表',
  `weid` int(10) DEFAULT NULL,
  `openid` varchar(500) DEFAULT NULL,
  `logo` varchar(200) DEFAULT NULL COMMENT '图标地址',
  `title` varchar(500) DEFAULT NULL COMMENT '赞助商简介',
  `url` varchar(512) DEFAULT NULL COMMENT '赞助商网址',
  `createtime` int(10) DEFAULT NULL COMMENT '时间',
  `num` int(10) DEFAULT '0' COMMENT '点击量',
  `isok` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yoby_wz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `openid` varchar(512) DEFAULT NULL,
  `use_num` int(10) DEFAULT '0' COMMENT '使用次数',
  `max_num` int(10) DEFAULT '0' COMMENT '总次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('yoby_car',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '行程表';");
}
if(!pdo_fieldexists('yoby_car',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `openid` varchar(65) DEFAULT NULL COMMENT '用户唯一';");
}
if(!pdo_fieldexists('yoby_car',  'address1')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `address1` varchar(200) DEFAULT NULL COMMENT '出发地';");
}
if(!pdo_fieldexists('yoby_car',  'address2')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `address2` varchar(200) DEFAULT NULL COMMENT '目的地';");
}
if(!pdo_fieldexists('yoby_car',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `createtime` int(10) DEFAULT NULL COMMENT '发布时间';");
}
if(!pdo_fieldexists('yoby_car',  'sendtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `sendtime` varchar(20) DEFAULT NULL COMMENT '出发时间';");
}
if(!pdo_fieldexists('yoby_car',  'num')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `num` int(10) DEFAULT '1' COMMENT '人数';");
}
if(!pdo_fieldexists('yoby_car',  'rmb')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `rmb` decimal(10,2) DEFAULT '0.00' COMMENT '金额';");
}
if(!pdo_fieldexists('yoby_car',  'isok')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `isok` tinyint(1) DEFAULT '1' COMMENT '1进行中2结束,3关闭';");
}
if(!pdo_fieldexists('yoby_car',  'beizhu')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `beizhu` varchar(1024) DEFAULT NULL COMMENT '备注附加';");
}
if(!pdo_fieldexists('yoby_car',  'type')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car')." ADD `type` tinyint(1) DEFAULT '1' COMMENT '1是乘客2是车主';");
}
if(!pdo_fieldexists('yoby_car_ad',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_ad')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告管理';");
}
if(!pdo_fieldexists('yoby_car_ad',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_ad')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_ad',  'img')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_ad')." ADD `img` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_ad',  'url')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_ad')." ADD `url` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_ad',  'title')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_ad')." ADD `title` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_ad',  'isok')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_ad')." ADD `isok` tinyint(1) DEFAULT '0' COMMENT '1是下线';");
}
if(!pdo_fieldexists('yoby_car_ad',  'orderby')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_ad')." ADD `orderby` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('yoby_car_add',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_add')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '加入车主拼车';");
}
if(!pdo_fieldexists('yoby_car_add',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_add')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_add',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_add')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_add',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_add')." ADD `openid` varchar(65) DEFAULT NULL COMMENT '加入者openid';");
}
if(!pdo_fieldexists('yoby_car_add',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_add')." ADD `cid` int(10) DEFAULT NULL COMMENT '拼车id';");
}
if(!pdo_fieldexists('yoby_car_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '信息表';");
}
if(!pdo_fieldexists('yoby_car_fans',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `createtime` int(10) DEFAULT NULL COMMENT '注册时间';");
}
if(!pdo_fieldexists('yoby_car_fans',  'type')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `type` tinyint(1) DEFAULT '1' COMMENT '1是乘客2是车主';");
}
if(!pdo_fieldexists('yoby_car_fans',  'title')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `title` varchar(50) DEFAULT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('yoby_car_fans',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `sex` tinyint(1) DEFAULT '1' COMMENT '1男2女';");
}
if(!pdo_fieldexists('yoby_car_fans',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `phone` varchar(15) DEFAULT NULL COMMENT '手机';");
}
if(!pdo_fieldexists('yoby_car_fans',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `cid` varchar(200) DEFAULT NULL COMMENT '身份证';");
}
if(!pdo_fieldexists('yoby_car_fans',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `sid` varchar(200) DEFAULT NULL COMMENT '车主需要驾照';");
}
if(!pdo_fieldexists('yoby_car_fans',  'isok')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `isok` tinyint(1) DEFAULT '0' COMMENT '0是未认证1是认证';");
}
if(!pdo_fieldexists('yoby_car_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `nickname` varchar(100) DEFAULT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('yoby_car_fans',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `headimgurl` varchar(200) DEFAULT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('yoby_car_fans',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD `openid` varchar(65) DEFAULT NULL;");
}
if(!pdo_indexexists('yoby_car_fans',  'i')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_fans')." ADD KEY `i` (`weid`,`phone`);");
}
if(!pdo_fieldexists('yoby_car_huodong',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_huodong')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动介绍';");
}
if(!pdo_fieldexists('yoby_car_huodong',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_huodong')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_huodong',  'title')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_huodong')." ADD `title` varchar(40) DEFAULT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('yoby_car_huodong',  'url')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_huodong')." ADD `url` varchar(512) DEFAULT NULL COMMENT '连接';");
}
if(!pdo_fieldexists('yoby_car_huodong',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_huodong')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_huodong',  'orderby')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_huodong')." ADD `orderby` int(10) DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('yoby_car_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '积分记录';");
}
if(!pdo_fieldexists('yoby_car_log',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_log')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_log')." ADD `createtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_log',  'f')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_log')." ADD `f` tinyint(1) DEFAULT '3' COMMENT '积分来源1发布文章2分享3阅读';");
}
if(!pdo_fieldexists('yoby_car_log',  'jifen')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_log')." ADD `jifen` decimal(10,2) DEFAULT '0.00' COMMENT '积分数';");
}
if(!pdo_fieldexists('yoby_car_log',  'rectime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_log')." ADD `rectime` varchar(10) DEFAULT NULL COMMENT '时间年月日';");
}
if(!pdo_fieldexists('yoby_car_log',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_log')." ADD `tid` int(10) DEFAULT NULL COMMENT '文章id';");
}
if(!pdo_fieldexists('yoby_car_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_log')." ADD `openid` varchar(128) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_say',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_say')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '留言表';");
}
if(!pdo_fieldexists('yoby_car_say',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_say')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_say',  'from_openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_say')." ADD `from_openid` varchar(500) DEFAULT NULL COMMENT '发布者openid';");
}
if(!pdo_fieldexists('yoby_car_say',  'to_openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_say')." ADD `to_openid` varchar(500) DEFAULT NULL COMMENT '接收者openid';");
}
if(!pdo_fieldexists('yoby_car_say',  'content')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_say')." ADD `content` varchar(1000) DEFAULT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('yoby_car_say',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_say')." ADD `createtime` int(10) DEFAULT NULL COMMENT '时间';");
}
if(!pdo_fieldexists('yoby_car_zanzhu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_zanzhu')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '赞助商表';");
}
if(!pdo_fieldexists('yoby_car_zanzhu',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_zanzhu')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_zanzhu',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_zanzhu')." ADD `openid` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_car_zanzhu',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_zanzhu')." ADD `logo` varchar(200) DEFAULT NULL COMMENT '图标地址';");
}
if(!pdo_fieldexists('yoby_car_zanzhu',  'title')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_zanzhu')." ADD `title` varchar(500) DEFAULT NULL COMMENT '赞助商简介';");
}
if(!pdo_fieldexists('yoby_car_zanzhu',  'url')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_zanzhu')." ADD `url` varchar(512) DEFAULT NULL COMMENT '赞助商网址';");
}
if(!pdo_fieldexists('yoby_car_zanzhu',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_zanzhu')." ADD `createtime` int(10) DEFAULT NULL COMMENT '时间';");
}
if(!pdo_fieldexists('yoby_car_zanzhu',  'num')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_zanzhu')." ADD `num` int(10) DEFAULT '0' COMMENT '点击量';");
}
if(!pdo_fieldexists('yoby_car_zanzhu',  'isok')) {
	pdo_query("ALTER TABLE ".tablename('yoby_car_zanzhu')." ADD `isok` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('yoby_wz',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_wz')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yoby_wz',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_wz')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_wz',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_wz')." ADD `openid` varchar(512) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_wz',  'use_num')) {
	pdo_query("ALTER TABLE ".tablename('yoby_wz')." ADD `use_num` int(10) DEFAULT '0' COMMENT '使用次数';");
}
if(!pdo_fieldexists('yoby_wz',  'max_num')) {
	pdo_query("ALTER TABLE ".tablename('yoby_wz')." ADD `max_num` int(10) DEFAULT '0' COMMENT '总次数';");
}

?>