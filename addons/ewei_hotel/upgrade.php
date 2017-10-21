<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hotel2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `lng` decimal(10,2) DEFAULT '0.00',
  `lat` decimal(10,2) DEFAULT '0.00',
  `ordermax` int(11) DEFAULT '0',
  `numsmax` int(11) DEFAULT '0',
  `daymax` int(11) DEFAULT '0',
  `address` varchar(255) DEFAULT '',
  `location_p` varchar(50) DEFAULT '',
  `location_c` varchar(50) DEFAULT '',
  `location_a` varchar(50) DEFAULT '',
  `roomcount` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `phone` varchar(255) DEFAULT '',
  `mail` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `thumborder` varchar(255) DEFAULT '',
  `description` text,
  `content` text,
  `traffic` text,
  `thumbs` text,
  `sales` text,
  `displayorder` int(11) DEFAULT '0',
  `level` int(11) DEFAULT '0',
  `device` text,
  `brandid` int(11) DEFAULT '0',
  `businessid` int(11) DEFAULT '0',
  `integral_rate` int(11) NOT NULL DEFAULT '0' COMMENT '在该酒店消费返积分的比例',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hotel2_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hotel2_business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `location_p` varchar(255) DEFAULT '',
  `location_c` varchar(255) DEFAULT '',
  `location_a` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hotel2_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `comment` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hotel2_comment_clerk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `orderid` int(25) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `comment` varchar(255) DEFAULT '',
  `clerkid` int(11) DEFAULT '0',
  `realname` varchar(20) DEFAULT NULL,
  `grade` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_hotel2_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `userid` varchar(50) DEFAULT '',
  `from_user` varchar(50) DEFAULT '',
  `realname` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `score` int(11) DEFAULT '0' COMMENT '积分',
  `createtime` int(11) DEFAULT '0',
  `userbind` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `clerk` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(30) DEFAULT '' COMMENT '用户名',
  `password` varchar(200) DEFAULT '' COMMENT '密码',
  `salt` varchar(8) NOT NULL DEFAULT '' COMMENT '加密盐',
  `islogin` tinyint(3) NOT NULL DEFAULT '0',
  `isauto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '自动添加，0否，1是',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hotel2_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `roomid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `btime` int(11) DEFAULT '0',
  `etime` int(11) DEFAULT '0',
  `style` varchar(255) DEFAULT '',
  `nums` int(11) DEFAULT '0',
  `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价',
  `info` text,
  `time` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `paytype` int(11) DEFAULT '0',
  `paystatus` int(11) DEFAULT '0',
  `msg` text,
  `mngtime` int(11) DEFAULT '0',
  `contact_name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人',
  `day` tinyint(2) NOT NULL DEFAULT '0' COMMENT '住几晚',
  `sum_price` decimal(10,2) DEFAULT '0.00' COMMENT '总价',
  `ordersn` varchar(30) DEFAULT '',
  `comment` int(3) NOT NULL DEFAULT '0',
  `clerkcomment` int(11) DEFAULT '0' COMMENT '店员评分',
  PRIMARY KEY (`id`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_weid` (`weid`),
  KEY `indx_roomid` (`roomid`),
  KEY `indx_memberid` (`memberid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hotel2_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `hotelid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hotel2_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotelid` int(11) DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `mprice` varchar(255) NOT NULL DEFAULT '',
  `thumbs` text,
  `device` text,
  `area` varchar(255) DEFAULT '',
  `floor` varchar(255) DEFAULT '',
  `smoke` varchar(255) DEFAULT '',
  `bed` varchar(255) DEFAULT '',
  `persons` int(11) DEFAULT '0',
  `bedadd` varchar(30) DEFAULT '',
  `status` int(11) DEFAULT '0',
  `isshow` int(11) DEFAULT '0',
  `sales` text,
  `displayorder` int(11) DEFAULT '0',
  `area_show` int(11) DEFAULT '0',
  `floor_show` int(11) DEFAULT '0',
  `smoke_show` int(11) DEFAULT '0',
  `bed_show` int(11) DEFAULT '0',
  `persons_show` int(11) DEFAULT '0',
  `bedadd_show` int(11) DEFAULT '0',
  `score` int(11) DEFAULT '0' COMMENT '订房积分',
  `breakfast` tinyint(3) DEFAULT '0' COMMENT '0无早 1单早 2双早',
  `sortid` int(11) NOT NULL DEFAULT '0' COMMENT '房间id，排序时使用',
  `service` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hotel2_room_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `roomid` int(11) DEFAULT '0',
  `roomdate` int(11) DEFAULT '0',
  `thisdate` varchar(255) NOT NULL DEFAULT '' COMMENT '当天日期',
  `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价',
  `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价',
  `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价',
  `num` varchar(255) DEFAULT '-1',
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_roomid` (`roomid`),
  KEY `indx_roomdate` (`roomdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hotel2_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `user` tinyint(1) DEFAULT '0' COMMENT '用户类型0微信用户1独立用户',
  `reg` tinyint(1) DEFAULT '0' COMMENT '是否允许注册0禁止注册1允许注册',
  `bind` tinyint(1) DEFAULT '0' COMMENT '是否绑定',
  `regcontent` text COMMENT '注册提示',
  `ordertype` tinyint(1) DEFAULT '0' COMMENT '预定类型0电话预定1电话和网络预订',
  `is_unify` tinyint(1) DEFAULT '0' COMMENT '0使用各分店电话,1使用统一电话',
  `tel` varchar(20) DEFAULT '' COMMENT '统一电话',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '提醒接受手机',
  `template` varchar(32) NOT NULL DEFAULT '' COMMENT '发送模板消息',
  `templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '模板ID',
  `paytype1` tinyint(1) DEFAULT '0',
  `paytype2` tinyint(1) DEFAULT '0',
  `paytype3` tinyint(1) DEFAULT '0',
  `version` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0单酒店版1多酒店版',
  `location_p` varchar(50) DEFAULT '',
  `location_c` varchar(50) DEFAULT '',
  `location_a` varchar(50) DEFAULT '',
  `smscode` int(3) NOT NULL DEFAULT '0',
  `refund` int(3) NOT NULL DEFAULT '0',
  `is_sms` int(10) NOT NULL DEFAULT '0' COMMENT '是否开启短信',
  `sms_id` varchar(20) NOT NULL COMMENT '短信模板ID',
  `refuse_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱',
  `confirm_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱',
  `print` varchar(10) NOT NULL COMMENT '打印机ID',
  `check_in_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '酒店已入住通知模板id',
  `finish_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '酒店订单完成通知模板id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('hotel2',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `lng` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('hotel2',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `lat` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('hotel2',  'ordermax')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `ordermax` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'numsmax')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `numsmax` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'daymax')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `daymax` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'address')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `address` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `location_p` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `location_c` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `location_a` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2',  'roomcount')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `roomcount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `phone` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2',  'mail')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `mail` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2',  'thumborder')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `thumborder` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2',  'description')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `description` text;");
}
if(!pdo_fieldexists('hotel2',  'content')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `content` text;");
}
if(!pdo_fieldexists('hotel2',  'traffic')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `traffic` text;");
}
if(!pdo_fieldexists('hotel2',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `thumbs` text;");
}
if(!pdo_fieldexists('hotel2',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `sales` text;");
}
if(!pdo_fieldexists('hotel2',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'level')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `level` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'device')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `device` text;");
}
if(!pdo_fieldexists('hotel2',  'brandid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `brandid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'businessid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `businessid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2',  'integral_rate')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD `integral_rate` int(11) NOT NULL DEFAULT '0' COMMENT '在该酒店消费返积分的比例';");
}
if(!pdo_indexexists('hotel2',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('hotel2_brand',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_brand')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_brand',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_brand')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_brand',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_brand')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_brand',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_brand')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_brand',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_brand')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('hotel2_brand',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_brand')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('hotel2_brand',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_brand')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('hotel2_business',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_business')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_business',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_business')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_business',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_business')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_business',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_business')." ADD `location_p` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_business',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_business')." ADD `location_c` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_business',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_business')." ADD `location_a` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_business',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_business')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_business',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_business')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('hotel2_business',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_business')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('hotel2_comment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_comment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_comment',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_comment',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment')." ADD `uid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_comment',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_comment',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment')." ADD `comment` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_comment_clerk',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment_clerk')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_comment_clerk',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment_clerk')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_comment_clerk',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment_clerk')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_comment_clerk',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment_clerk')." ADD `orderid` int(25) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_comment_clerk',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment_clerk')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_comment_clerk',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment_clerk')." ADD `comment` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_comment_clerk',  'clerkid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment_clerk')." ADD `clerkid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_comment_clerk',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment_clerk')." ADD `realname` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('hotel2_comment_clerk',  'grade')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_comment_clerk')." ADD `grade` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('hotel2_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_member',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_member',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `userid` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_member',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `from_user` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_member',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `realname` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `mobile` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_member',  'score')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `score` int(11) DEFAULT '0' COMMENT '积分';");
}
if(!pdo_fieldexists('hotel2_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_member',  'userbind')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `userbind` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_member',  'clerk')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `clerk` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_member',  'username')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `username` varchar(30) DEFAULT '' COMMENT '用户名';");
}
if(!pdo_fieldexists('hotel2_member',  'password')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `password` varchar(200) DEFAULT '' COMMENT '密码';");
}
if(!pdo_fieldexists('hotel2_member',  'salt')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `salt` varchar(8) NOT NULL DEFAULT '' COMMENT '加密盐';");
}
if(!pdo_fieldexists('hotel2_member',  'islogin')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `islogin` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_member',  'isauto')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `isauto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '自动添加，0否，1是';");
}
if(!pdo_fieldexists('hotel2_member',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD `nickname` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('hotel2_member',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_member')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('hotel2_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'roomid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `roomid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'memberid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `memberid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_order',  'name')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `name` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `mobile` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注';");
}
if(!pdo_fieldexists('hotel2_order',  'btime')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `btime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'etime')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `etime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'style')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `style` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_order',  'nums')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `nums` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'oprice')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价';");
}
if(!pdo_fieldexists('hotel2_order',  'cprice')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价';");
}
if(!pdo_fieldexists('hotel2_order',  'mprice')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价';");
}
if(!pdo_fieldexists('hotel2_order',  'info')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `info` text;");
}
if(!pdo_fieldexists('hotel2_order',  'time')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `time` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `paytype` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'paystatus')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `paystatus` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'msg')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `msg` text;");
}
if(!pdo_fieldexists('hotel2_order',  'mngtime')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `mngtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'contact_name')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `contact_name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人';");
}
if(!pdo_fieldexists('hotel2_order',  'day')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `day` tinyint(2) NOT NULL DEFAULT '0' COMMENT '住几晚';");
}
if(!pdo_fieldexists('hotel2_order',  'sum_price')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `sum_price` decimal(10,2) DEFAULT '0.00' COMMENT '总价';");
}
if(!pdo_fieldexists('hotel2_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `ordersn` varchar(30) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_order',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `comment` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_order',  'clerkcomment')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD `clerkcomment` int(11) DEFAULT '0' COMMENT '店员评分';");
}
if(!pdo_indexexists('hotel2_order',  'indx_hotelid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD KEY `indx_hotelid` (`hotelid`);");
}
if(!pdo_indexexists('hotel2_order',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('hotel2_order',  'indx_roomid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD KEY `indx_roomid` (`roomid`);");
}
if(!pdo_indexexists('hotel2_order',  'indx_memberid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_order')." ADD KEY `indx_memberid` (`memberid`);");
}
if(!pdo_fieldexists('hotel2_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_reply')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hotel2_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_reply')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hotel2_reply',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_reply')." ADD `hotelid` int(11) NOT NULL;");
}
if(!pdo_indexexists('hotel2_reply',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_reply')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('hotel2_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('hotel2_room',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_room',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_room',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_room',  'oprice')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价';");
}
if(!pdo_fieldexists('hotel2_room',  'cprice')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价';");
}
if(!pdo_fieldexists('hotel2_room',  'mprice')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `mprice` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_room',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `thumbs` text;");
}
if(!pdo_fieldexists('hotel2_room',  'device')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `device` text;");
}
if(!pdo_fieldexists('hotel2_room',  'area')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `area` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_room',  'floor')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `floor` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_room',  'smoke')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `smoke` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_room',  'bed')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `bed` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_room',  'persons')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `persons` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'bedadd')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `bedadd` varchar(30) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_room',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `isshow` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `sales` text;");
}
if(!pdo_fieldexists('hotel2_room',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'area_show')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `area_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'floor_show')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `floor_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'smoke_show')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `smoke_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'bed_show')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `bed_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'persons_show')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `persons_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'bedadd_show')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `bedadd_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room',  'score')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `score` int(11) DEFAULT '0' COMMENT '订房积分';");
}
if(!pdo_fieldexists('hotel2_room',  'breakfast')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `breakfast` tinyint(3) DEFAULT '0' COMMENT '0无早 1单早 2双早';");
}
if(!pdo_fieldexists('hotel2_room',  'sortid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `sortid` int(11) NOT NULL DEFAULT '0' COMMENT '房间id，排序时使用';");
}
if(!pdo_fieldexists('hotel2_room',  'service')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD `service` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('hotel2_room',  'indx_hotelid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD KEY `indx_hotelid` (`hotelid`);");
}
if(!pdo_indexexists('hotel2_room',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('hotel2_room_price',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_room_price',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room_price',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room_price',  'roomid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `roomid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room_price',  'roomdate')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `roomdate` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_room_price',  'thisdate')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `thisdate` varchar(255) NOT NULL DEFAULT '' COMMENT '当天日期';");
}
if(!pdo_fieldexists('hotel2_room_price',  'oprice')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `oprice` decimal(10,2) DEFAULT '0.00' COMMENT '原价';");
}
if(!pdo_fieldexists('hotel2_room_price',  'cprice')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `cprice` decimal(10,2) DEFAULT '0.00' COMMENT '现价';");
}
if(!pdo_fieldexists('hotel2_room_price',  'mprice')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `mprice` decimal(10,2) DEFAULT '0.00' COMMENT '会员价';");
}
if(!pdo_fieldexists('hotel2_room_price',  'num')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `num` varchar(255) DEFAULT '-1';");
}
if(!pdo_fieldexists('hotel2_room_price',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD `status` int(11) DEFAULT '1';");
}
if(!pdo_indexexists('hotel2_room_price',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('hotel2_room_price',  'indx_hotelid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD KEY `indx_hotelid` (`hotelid`);");
}
if(!pdo_indexexists('hotel2_room_price',  'indx_roomid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD KEY `indx_roomid` (`roomid`);");
}
if(!pdo_indexexists('hotel2_room_price',  'indx_roomdate')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_room_price')." ADD KEY `indx_roomdate` (`roomdate`);");
}
if(!pdo_fieldexists('hotel2_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hotel2_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_set',  'user')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `user` tinyint(1) DEFAULT '0' COMMENT '用户类型0微信用户1独立用户';");
}
if(!pdo_fieldexists('hotel2_set',  'reg')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `reg` tinyint(1) DEFAULT '0' COMMENT '是否允许注册0禁止注册1允许注册';");
}
if(!pdo_fieldexists('hotel2_set',  'bind')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `bind` tinyint(1) DEFAULT '0' COMMENT '是否绑定';");
}
if(!pdo_fieldexists('hotel2_set',  'regcontent')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `regcontent` text COMMENT '注册提示';");
}
if(!pdo_fieldexists('hotel2_set',  'ordertype')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `ordertype` tinyint(1) DEFAULT '0' COMMENT '预定类型0电话预定1电话和网络预订';");
}
if(!pdo_fieldexists('hotel2_set',  'is_unify')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `is_unify` tinyint(1) DEFAULT '0' COMMENT '0使用各分店电话,1使用统一电话';");
}
if(!pdo_fieldexists('hotel2_set',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `tel` varchar(20) DEFAULT '' COMMENT '统一电话';");
}
if(!pdo_fieldexists('hotel2_set',  'email')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `email` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱';");
}
if(!pdo_fieldexists('hotel2_set',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '提醒接受手机';");
}
if(!pdo_fieldexists('hotel2_set',  'template')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `template` varchar(32) NOT NULL DEFAULT '' COMMENT '发送模板消息';");
}
if(!pdo_fieldexists('hotel2_set',  'templateid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '模板ID';");
}
if(!pdo_fieldexists('hotel2_set',  'paytype1')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `paytype1` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_set',  'paytype2')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `paytype2` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_set',  'paytype3')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `paytype3` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_set',  'version')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `version` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0单酒店版1多酒店版';");
}
if(!pdo_fieldexists('hotel2_set',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `location_p` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_set',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `location_c` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_set',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `location_a` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('hotel2_set',  'smscode')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `smscode` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_set',  'refund')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `refund` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hotel2_set',  'is_sms')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `is_sms` int(10) NOT NULL DEFAULT '0' COMMENT '是否开启短信';");
}
if(!pdo_fieldexists('hotel2_set',  'sms_id')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `sms_id` varchar(20) NOT NULL COMMENT '短信模板ID';");
}
if(!pdo_fieldexists('hotel2_set',  'refuse_templateid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `refuse_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱';");
}
if(!pdo_fieldexists('hotel2_set',  'confirm_templateid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `confirm_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱';");
}
if(!pdo_fieldexists('hotel2_set',  'print')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `print` varchar(10) NOT NULL COMMENT '打印机ID';");
}
if(!pdo_fieldexists('hotel2_set',  'check_in_templateid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `check_in_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '酒店已入住通知模板id';");
}
if(!pdo_fieldexists('hotel2_set',  'finish_templateid')) {
	pdo_query("ALTER TABLE ".tablename('hotel2_set')." ADD `finish_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '酒店订单完成通知模板id';");
}

?>