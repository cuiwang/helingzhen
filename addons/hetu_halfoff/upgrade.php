<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_activation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` varchar(200) NOT NULL COMMENT 'uniacid',
  `pwd` varchar(200) NOT NULL COMMENT '卡密',
  `type` int(1) unsigned NOT NULL COMMENT '卡类型 标识月卡周卡等类型',
  `status` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '卡状态 标识是否已使用 0未使用 /1已使用',
  `openid` varchar(200) DEFAULT NULL COMMENT 'openid卡使用者',
  `time` varchar(100) DEFAULT NULL COMMENT '卡使用时间',
  `card` varchar(200) NOT NULL COMMENT '卡号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='激活卡表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_agent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '代理商表主键',
  `uniacid` int(11) unsigned NOT NULL COMMENT 'uniacid',
  `name` varchar(50) NOT NULL COMMENT '代理商名称',
  `phone` varchar(20) NOT NULL COMMENT '代理商联系方式',
  `address` varchar(255) NOT NULL COMMENT '代理商地址',
  `password` varchar(255) NOT NULL COMMENT '代理商密码',
  `province` varchar(255) NOT NULL COMMENT '代理商省',
  `city` varchar(100) NOT NULL COMMENT '代理商市',
  `district` varchar(100) NOT NULL COMMENT '代理商区/县',
  `time` int(20) unsigned NOT NULL COMMENT '添加时间',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '代理商状态 0不显示/1显示',
  `limit_condition` int(1) unsigned NOT NULL,
  `citylist` char(150) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `imgurl` varchar(255) NOT NULL,
  `left_title` char(150) NOT NULL,
  `left_word` char(100) NOT NULL,
  `left_tleson` char(100) NOT NULL,
  `left_url` varchar(255) NOT NULL,
  `left_img` varchar(255) NOT NULL,
  `top_title` char(150) NOT NULL,
  `top_tleson` char(150) NOT NULL,
  `top_url` varchar(255) NOT NULL,
  `top_img` varchar(255) NOT NULL,
  `bottom_title` char(150) NOT NULL,
  `bottom_url` varchar(255) NOT NULL,
  `bottom_img` varchar(255) NOT NULL,
  `bottom_tleson` char(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='代理商表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_business` (
  `bus_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商户表主键id',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `name` varchar(100) NOT NULL COMMENT '商户名称',
  `contactman` varchar(100) DEFAULT '后台测试' COMMENT '联系人',
  `businesshour` varchar(50) DEFAULT NULL COMMENT '商户营业时间',
  `img` varchar(200) DEFAULT NULL COMMENT '商户门户图片',
  `province` varchar(20) DEFAULT NULL COMMENT '省',
  `city` varchar(20) DEFAULT NULL COMMENT '市',
  `county` varchar(20) DEFAULT NULL COMMENT '县/区',
  `address` varchar(200) DEFAULT NULL COMMENT '详细地址',
  `phone` varchar(20) NOT NULL COMMENT '商户联系方式',
  `desc_text` text COMMENT '商户详情',
  `desc_dis` text COMMENT '商户优惠信息',
  `desc_img` varchar(50) DEFAULT NULL COMMENT '商户图片描述',
  `share_title` varchar(50) DEFAULT NULL COMMENT '分享标题',
  `share_img` varchar(200) DEFAULT NULL COMMENT '分享图片',
  `share_desc` varchar(255) DEFAULT NULL COMMENT '分享描述',
  `cir_id` int(11) unsigned DEFAULT NULL COMMENT '中心圈id 中心圈表外键',
  `category_id` int(11) unsigned DEFAULT NULL COMMENT '分类表id 分类表表外键',
  `lng` varchar(50) DEFAULT NULL COMMENT '商户经度',
  `lat` varchar(50) DEFAULT NULL COMMENT '商户纬度',
  `license` varchar(200) NOT NULL COMMENT '营业执照',
  `status` int(1) unsigned NOT NULL COMMENT '商户状态 0显示 1隐藏 2审核中 3拒绝',
  `top` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '置顶',
  `browse_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `discount_sign` int(1) unsigned NOT NULL COMMENT '优惠时间标识0天天享/1每周/2每月',
  `discount_time` text NOT NULL,
  `discount_num` int(10) unsigned NOT NULL,
  `openid` varchar(200) DEFAULT NULL COMMENT 'openid',
  `password` varchar(200) DEFAULT NULL COMMENT '商户密码',
  `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3',
  `agentid` int(11) unsigned NOT NULL,
  `other_discount` varchar(10) NOT NULL,
  `forward_num` int(11) unsigned NOT NULL,
  `other_desc_img` varchar(50) DEFAULT NULL COMMENT 'ǤԠʱݤԅܝĨ˶',
  PRIMARY KEY (`bus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商户信息表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_cardtype` (
  `cardtype_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '五折卡类型设置表主键id',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `type` varchar(50) NOT NULL COMMENT '五折卡类型',
  `type_desc` varchar(50) NOT NULL COMMENT '五折卡类型描述',
  `no_type` varchar(20) NOT NULL COMMENT '卡号编号前缀',
  `price` float(10,2) unsigned NOT NULL COMMENT '五折卡价格',
  `logo` varchar(200) NOT NULL COMMENT '卡标识',
  `desc` text NOT NULL,
  `days` int(11) unsigned NOT NULL COMMENT '有效期(天)',
  `status` int(1) unsigned NOT NULL COMMENT '卡状态 0显示/1隐藏',
  `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1',
  `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2',
  `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3',
  `limit_num` int(50) unsigned NOT NULL,
  PRIMARY KEY (`cardtype_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='五折卡类型设置表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `pid` int(11) unsigned NOT NULL COMMENT '副id',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(1) unsigned NOT NULL COMMENT '分类状态 0显示/1不显示',
  `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1',
  `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2',
  `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='分类表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_circle` (
  `cir_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '中心圈表id 主键',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `name` varchar(50) NOT NULL COMMENT '中心圈名称',
  `status` int(1) unsigned NOT NULL COMMENT '状态 0显示，1隐藏',
  `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `province` varchar(50) NOT NULL COMMENT '省',
  `city` varchar(50) NOT NULL COMMENT '市',
  `district` varchar(50) NOT NULL COMMENT '县',
  PRIMARY KEY (`cir_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='中心圈表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` int(1) unsigned NOT NULL,
  `pid` int(11) unsigned NOT NULL,
  `level` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_collection` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏表id 主键',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `bus_id` int(11) unsigned NOT NULL COMMENT '商户表主键id',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1',
  `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2',
  `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3',
  `status` int(1) unsigned NOT NULL,
  `time` int(20) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='收藏表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_confirm` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '核销表id 主键',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `bus_id` int(11) unsigned NOT NULL COMMENT '商户表主键id',
  `card_id` int(11) unsigned NOT NULL COMMENT '五折卡表主键id',
  `openid` varchar(200) NOT NULL COMMENT '核销人员openid',
  `time` int(20) unsigned NOT NULL COMMENT '核销时间',
  `ishalfoff` int(1) unsigned DEFAULT NULL,
  `agentid` int(11) unsigned NOT NULL,
  `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='核销表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uniacid` int(11) unsigned NOT NULL COMMENT 'uniacid',
  `bus_id` int(11) unsigned NOT NULL COMMENT '商户主键id',
  `title` varchar(100) NOT NULL COMMENT '优惠券名称',
  `desc` varchar(255) NOT NULL COMMENT '优惠券描述',
  `timezone` varchar(255) NOT NULL COMMENT '优惠券时段',
  `notice` varchar(255) NOT NULL COMMENT '优惠券使用说明',
  `starttime` int(20) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(20) unsigned NOT NULL COMMENT '结束时间',
  `num` int(11) unsigned NOT NULL COMMENT '优惠券个数',
  `limit` int(11) unsigned NOT NULL COMMENT '该优惠券领取限制 0为不限制',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0不显示/1显示',
  `sequence` int(11) unsigned NOT NULL COMMENT '排序',
  `discount` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠券表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_couponrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uniacid` int(11) unsigned NOT NULL COMMENT 'uniacid',
  `uid` int(11) unsigned NOT NULL COMMENT '领取人uid',
  `senduid` int(11) unsigned DEFAULT NULL COMMENT '赠送人uid 赠送使用',
  `openid` varchar(200) NOT NULL COMMENT '领取人openid',
  `sendopenid` varchar(200) DEFAULT NULL COMMENT '赠送人openid 赠送使用',
  `bus_id` int(11) unsigned NOT NULL COMMENT '商户主键bus_id',
  `coupon_id` int(11) unsigned NOT NULL COMMENT '优惠券主键id',
  `time` int(20) unsigned NOT NULL COMMENT '领取时间',
  `sendtime` int(20) unsigned DEFAULT NULL COMMENT '赠送时间 赠送使用',
  `code` varchar(100) NOT NULL COMMENT '串码 生成二维码使用',
  `status` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券使用状态 0未使用/1已使用/2赠送中',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠券领取纪录表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_getcard` (
  `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '五折卡表主键id',
  `payno` varchar(150) NOT NULL COMMENT '支付编号',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `cardtype_id` int(11) unsigned NOT NULL COMMENT '五折卡类型设置表主键id',
  `card_no` varchar(100) NOT NULL COMMENT '五折卡号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户表主键id',
  `start_time` int(20) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(20) unsigned NOT NULL COMMENT '结束时间',
  `status` int(1) unsigned NOT NULL COMMENT '标识0未支付/1已领取/2已禁用',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  `code` varchar(200) DEFAULT NULL COMMENT '串码 生成二维码使用',
  `gettype` int(1) unsigned DEFAULT NULL COMMENT '领取类型 支付1/激活2',
  `timeout` int(1) unsigned DEFAULT '0' COMMENT '是否发送消息 0未发送/1已发送',
  `agentid` int(11) unsigned NOT NULL,
  `activation_id` int(11) unsigned DEFAULT NULL COMMENT '激活码id',
  PRIMARY KEY (`card_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='领取五折卡表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_module` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '模块表主键id',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `name` varchar(50) NOT NULL COMMENT '模块名称',
  `url` varchar(200) NOT NULL COMMENT '模块链接URL',
  `img` varchar(200) NOT NULL COMMENT '模块图片',
  `status` int(1) unsigned NOT NULL COMMENT '模块状态',
  `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '模块顺序',
  `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1',
  `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2',
  `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='模块表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` varchar(200) NOT NULL COMMENT 'uniacid',
  `title` varchar(50) NOT NULL COMMENT '消息标题',
  `content` text NOT NULL COMMENT '消息内容',
  `creater` varchar(100) NOT NULL COMMENT '创建人',
  `createtime` int(11) unsigned NOT NULL COMMENT '创建时间',
  `sequence` int(11) unsigned NOT NULL COMMENT '排序',
  `starttime` int(11) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(11) unsigned NOT NULL COMMENT '结束时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='消息表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_newslog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` varchar(200) NOT NULL COMMENT 'uniacid',
  `newsid` int(11) unsigned NOT NULL COMMENT '消息表id',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='消息日志表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_staff` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '员工表id 主键',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `bus_id` int(11) unsigned NOT NULL COMMENT '商户表id',
  `openid` varchar(200) NOT NULL COMMENT '核销人员openid',
  `nickname` varchar(50) NOT NULL COMMENT '核销人员nickname',
  `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1',
  `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2',
  `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='员工表';
";
pdo_run($sql);
if(!pdo_fieldexists('hetu_halfoff_activation',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_activation')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('hetu_halfoff_activation',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_activation')." ADD `uniacid` varchar(200) NOT NULL COMMENT 'uniacid';");
}
if(!pdo_fieldexists('hetu_halfoff_activation',  'pwd')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_activation')." ADD `pwd` varchar(200) NOT NULL COMMENT '卡密';");
}
if(!pdo_fieldexists('hetu_halfoff_activation',  'type')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_activation')." ADD `type` int(1) unsigned NOT NULL COMMENT '卡类型 标识月卡周卡等类型';");
}
if(!pdo_fieldexists('hetu_halfoff_activation',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_activation')." ADD `status` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '卡状态 标识是否已使用 0未使用 /1已使用';");
}
if(!pdo_fieldexists('hetu_halfoff_activation',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_activation')." ADD `openid` varchar(200) DEFAULT NULL COMMENT 'openid卡使用者';");
}
if(!pdo_fieldexists('hetu_halfoff_activation',  'time')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_activation')." ADD `time` varchar(100) DEFAULT NULL COMMENT '卡使用时间';");
}
if(!pdo_fieldexists('hetu_halfoff_activation',  'card')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_activation')." ADD `card` varchar(200) NOT NULL COMMENT '卡号';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '代理商表主键';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT 'uniacid';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'name')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `name` varchar(50) NOT NULL COMMENT '代理商名称';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `phone` varchar(20) NOT NULL COMMENT '代理商联系方式';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'address')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `address` varchar(255) NOT NULL COMMENT '代理商地址';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'password')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `password` varchar(255) NOT NULL COMMENT '代理商密码';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'province')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `province` varchar(255) NOT NULL COMMENT '代理商省';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'city')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `city` varchar(100) NOT NULL COMMENT '代理商市';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'district')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `district` varchar(100) NOT NULL COMMENT '代理商区/县';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'time')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `time` int(20) unsigned NOT NULL COMMENT '添加时间';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '代理商状态 0不显示/1显示';");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'limit_condition')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `limit_condition` int(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'citylist')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `citylist` char(150) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'banner')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `banner` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'imgurl')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `imgurl` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'left_title')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `left_title` char(150) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'left_word')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `left_word` char(100) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'left_tleson')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `left_tleson` char(100) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'left_url')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `left_url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'left_img')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `left_img` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'top_title')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `top_title` char(150) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'top_tleson')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `top_tleson` char(150) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'top_url')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `top_url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'top_img')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `top_img` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'bottom_title')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `bottom_title` char(150) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'bottom_url')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `bottom_url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'bottom_img')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `bottom_img` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_agent',  'bottom_tleson')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_agent')." ADD `bottom_tleson` char(150) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'bus_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `bus_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商户表主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'name')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `name` varchar(100) NOT NULL COMMENT '商户名称';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'contactman')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `contactman` varchar(100) DEFAULT '后台测试' COMMENT '联系人';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'businesshour')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `businesshour` varchar(50) DEFAULT NULL COMMENT '商户营业时间';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'img')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `img` varchar(200) DEFAULT NULL COMMENT '商户门户图片';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'province')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `province` varchar(20) DEFAULT NULL COMMENT '省';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'city')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `city` varchar(20) DEFAULT NULL COMMENT '市';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'county')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `county` varchar(20) DEFAULT NULL COMMENT '县/区';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'address')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `address` varchar(200) DEFAULT NULL COMMENT '详细地址';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `phone` varchar(20) NOT NULL COMMENT '商户联系方式';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'desc_text')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `desc_text` text COMMENT '商户详情';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'desc_dis')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `desc_dis` text COMMENT '商户优惠信息';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'desc_img')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `desc_img` varchar(50) DEFAULT NULL COMMENT '商户图片描述';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `share_title` varchar(50) DEFAULT NULL COMMENT '分享标题';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'share_img')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `share_img` varchar(200) DEFAULT NULL COMMENT '分享图片';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `share_desc` varchar(255) DEFAULT NULL COMMENT '分享描述';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'cir_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `cir_id` int(11) unsigned DEFAULT NULL COMMENT '中心圈id 中心圈表外键';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'category_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `category_id` int(11) unsigned DEFAULT NULL COMMENT '分类表id 分类表表外键';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `lng` varchar(50) DEFAULT NULL COMMENT '商户经度';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `lat` varchar(50) DEFAULT NULL COMMENT '商户纬度';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'license')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `license` varchar(200) NOT NULL COMMENT '营业执照';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `status` int(1) unsigned NOT NULL COMMENT '商户状态 0显示 1隐藏 2审核中 3拒绝';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'top')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `top` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '置顶';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'browse_num')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `browse_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'sequence')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'discount_sign')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `discount_sign` int(1) unsigned NOT NULL COMMENT '优惠时间标识0天天享/1每周/2每月';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'discount_time')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `discount_time` text NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'discount_num')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `discount_num` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `openid` varchar(200) DEFAULT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'password')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `password` varchar(200) DEFAULT NULL COMMENT '商户密码';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'retain3')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3';");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'agentid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `agentid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'other_discount')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `other_discount` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'forward_num')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `forward_num` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_business',  'other_desc_img')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_business')." ADD `other_desc_img` varchar(50) DEFAULT NULL COMMENT 'ǤԠʱݤԅܝĨ˶';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'cardtype_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `cardtype_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '五折卡类型设置表主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'type')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `type` varchar(50) NOT NULL COMMENT '五折卡类型';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'type_desc')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `type_desc` varchar(50) NOT NULL COMMENT '五折卡类型描述';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'no_type')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `no_type` varchar(20) NOT NULL COMMENT '卡号编号前缀';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'price')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `price` float(10,2) unsigned NOT NULL COMMENT '五折卡价格';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `logo` varchar(200) NOT NULL COMMENT '卡标识';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `desc` text NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'days')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `days` int(11) unsigned NOT NULL COMMENT '有效期(天)';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `status` int(1) unsigned NOT NULL COMMENT '卡状态 0显示/1隐藏';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'sequence')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'retain1')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'retain2')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'retain3')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3';");
}
if(!pdo_fieldexists('hetu_halfoff_cardtype',  'limit_num')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_cardtype')." ADD `limit_num` int(50) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_category')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_category')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id';");
}
if(!pdo_fieldexists('hetu_halfoff_category',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_category')." ADD `pid` int(11) unsigned NOT NULL COMMENT '副id';");
}
if(!pdo_fieldexists('hetu_halfoff_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_category')." ADD `name` varchar(100) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('hetu_halfoff_category',  'sequence')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_category')." ADD `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('hetu_halfoff_category',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_category')." ADD `status` int(1) unsigned NOT NULL COMMENT '分类状态 0显示/1不显示';");
}
if(!pdo_fieldexists('hetu_halfoff_category',  'retain1')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_category')." ADD `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1';");
}
if(!pdo_fieldexists('hetu_halfoff_category',  'retain2')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_category')." ADD `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2';");
}
if(!pdo_fieldexists('hetu_halfoff_category',  'retain3')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_category')." ADD `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3';");
}
if(!pdo_fieldexists('hetu_halfoff_circle',  'cir_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_circle')." ADD `cir_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '中心圈表id 主键';");
}
if(!pdo_fieldexists('hetu_halfoff_circle',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_circle')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id';");
}
if(!pdo_fieldexists('hetu_halfoff_circle',  'name')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_circle')." ADD `name` varchar(50) NOT NULL COMMENT '中心圈名称';");
}
if(!pdo_fieldexists('hetu_halfoff_circle',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_circle')." ADD `status` int(1) unsigned NOT NULL COMMENT '状态 0显示，1隐藏';");
}
if(!pdo_fieldexists('hetu_halfoff_circle',  'sequence')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_circle')." ADD `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('hetu_halfoff_circle',  'province')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_circle')." ADD `province` varchar(50) NOT NULL COMMENT '省';");
}
if(!pdo_fieldexists('hetu_halfoff_circle',  'city')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_circle')." ADD `city` varchar(50) NOT NULL COMMENT '市';");
}
if(!pdo_fieldexists('hetu_halfoff_circle',  'district')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_circle')." ADD `district` varchar(50) NOT NULL COMMENT '县';");
}
if(!pdo_fieldexists('hetu_halfoff_city',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_city')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hetu_halfoff_city',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_city')." ADD `uniacid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_city',  'name')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_city')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_city',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_city')." ADD `status` int(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_city',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_city')." ADD `pid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_city',  'level')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_city')." ADD `level` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_collection',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_collection')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏表id 主键';");
}
if(!pdo_fieldexists('hetu_halfoff_collection',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_collection')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id';");
}
if(!pdo_fieldexists('hetu_halfoff_collection',  'bus_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_collection')." ADD `bus_id` int(11) unsigned NOT NULL COMMENT '商户表主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_collection',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_collection')." ADD `openid` varchar(200) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('hetu_halfoff_collection',  'retain1')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_collection')." ADD `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1';");
}
if(!pdo_fieldexists('hetu_halfoff_collection',  'retain2')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_collection')." ADD `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2';");
}
if(!pdo_fieldexists('hetu_halfoff_collection',  'retain3')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_collection')." ADD `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3';");
}
if(!pdo_fieldexists('hetu_halfoff_collection',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_collection')." ADD `status` int(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_collection',  'time')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_collection')." ADD `time` int(20) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_confirm',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_confirm')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '核销表id 主键';");
}
if(!pdo_fieldexists('hetu_halfoff_confirm',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_confirm')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id';");
}
if(!pdo_fieldexists('hetu_halfoff_confirm',  'bus_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_confirm')." ADD `bus_id` int(11) unsigned NOT NULL COMMENT '商户表主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_confirm',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_confirm')." ADD `card_id` int(11) unsigned NOT NULL COMMENT '五折卡表主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_confirm',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_confirm')." ADD `openid` varchar(200) NOT NULL COMMENT '核销人员openid';");
}
if(!pdo_fieldexists('hetu_halfoff_confirm',  'time')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_confirm')." ADD `time` int(20) unsigned NOT NULL COMMENT '核销时间';");
}
if(!pdo_fieldexists('hetu_halfoff_confirm',  'ishalfoff')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_confirm')." ADD `ishalfoff` int(1) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_confirm',  'agentid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_confirm')." ADD `agentid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_confirm',  'retain3')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_confirm')." ADD `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT 'uniacid';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'bus_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `bus_id` int(11) unsigned NOT NULL COMMENT '商户主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `title` varchar(100) NOT NULL COMMENT '优惠券名称';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `desc` varchar(255) NOT NULL COMMENT '优惠券描述';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'timezone')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `timezone` varchar(255) NOT NULL COMMENT '优惠券时段';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `notice` varchar(255) NOT NULL COMMENT '优惠券使用说明';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `starttime` int(20) unsigned NOT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `endtime` int(20) unsigned NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'num')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `num` int(11) unsigned NOT NULL COMMENT '优惠券个数';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'limit')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `limit` int(11) unsigned NOT NULL COMMENT '该优惠券领取限制 0为不限制';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0不显示/1显示';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'sequence')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `sequence` int(11) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('hetu_halfoff_coupon',  'discount')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_coupon')." ADD `discount` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT 'uniacid';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `uid` int(11) unsigned NOT NULL COMMENT '领取人uid';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'senduid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `senduid` int(11) unsigned DEFAULT NULL COMMENT '赠送人uid 赠送使用';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `openid` varchar(200) NOT NULL COMMENT '领取人openid';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'sendopenid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `sendopenid` varchar(200) DEFAULT NULL COMMENT '赠送人openid 赠送使用';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'bus_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `bus_id` int(11) unsigned NOT NULL COMMENT '商户主键bus_id';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'coupon_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `coupon_id` int(11) unsigned NOT NULL COMMENT '优惠券主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'time')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `time` int(20) unsigned NOT NULL COMMENT '领取时间';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'sendtime')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `sendtime` int(20) unsigned DEFAULT NULL COMMENT '赠送时间 赠送使用';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'code')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `code` varchar(100) NOT NULL COMMENT '串码 生成二维码使用';");
}
if(!pdo_fieldexists('hetu_halfoff_couponrecord',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD `status` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券使用状态 0未使用/1已使用/2赠送中';");
}
if(!pdo_indexexists('hetu_halfoff_couponrecord',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_couponrecord')." ADD KEY `uid` (`uid`);");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '五折卡表主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'payno')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `payno` varchar(150) NOT NULL COMMENT '支付编号';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'cardtype_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `cardtype_id` int(11) unsigned NOT NULL COMMENT '五折卡类型设置表主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'card_no')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `card_no` varchar(100) NOT NULL COMMENT '五折卡号';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'user_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `user_id` int(11) unsigned NOT NULL COMMENT '用户表主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'start_time')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `start_time` int(20) unsigned NOT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'end_time')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `end_time` int(20) unsigned NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `status` int(1) unsigned NOT NULL COMMENT '标识0未支付/1已领取/2已禁用';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `openid` varchar(200) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'code')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `code` varchar(200) DEFAULT NULL COMMENT '串码 生成二维码使用';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'gettype')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `gettype` int(1) unsigned DEFAULT NULL COMMENT '领取类型 支付1/激活2';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'timeout')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `timeout` int(1) unsigned DEFAULT '0' COMMENT '是否发送消息 0未发送/1已发送';");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'agentid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `agentid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hetu_halfoff_getcard',  'activation_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_getcard')." ADD `activation_id` int(11) unsigned DEFAULT NULL COMMENT '激活码id';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '模块表主键id';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'name')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `name` varchar(50) NOT NULL COMMENT '模块名称';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'url')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `url` varchar(200) NOT NULL COMMENT '模块链接URL';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'img')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `img` varchar(200) NOT NULL COMMENT '模块图片';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `status` int(1) unsigned NOT NULL COMMENT '模块状态';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'sequence')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `sequence` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '模块顺序';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'retain1')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'retain2')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2';");
}
if(!pdo_fieldexists('hetu_halfoff_module',  'retain3')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_module')." ADD `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3';");
}
if(!pdo_fieldexists('hetu_halfoff_news',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_news')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('hetu_halfoff_news',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_news')." ADD `uniacid` varchar(200) NOT NULL COMMENT 'uniacid';");
}
if(!pdo_fieldexists('hetu_halfoff_news',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_news')." ADD `title` varchar(50) NOT NULL COMMENT '消息标题';");
}
if(!pdo_fieldexists('hetu_halfoff_news',  'content')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_news')." ADD `content` text NOT NULL COMMENT '消息内容';");
}
if(!pdo_fieldexists('hetu_halfoff_news',  'creater')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_news')." ADD `creater` varchar(100) NOT NULL COMMENT '创建人';");
}
if(!pdo_fieldexists('hetu_halfoff_news',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_news')." ADD `createtime` int(11) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('hetu_halfoff_news',  'sequence')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_news')." ADD `sequence` int(11) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('hetu_halfoff_news',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_news')." ADD `starttime` int(11) unsigned NOT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('hetu_halfoff_news',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_news')." ADD `endtime` int(11) unsigned NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('hetu_halfoff_newslog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_newslog')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('hetu_halfoff_newslog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_newslog')." ADD `uniacid` varchar(200) NOT NULL COMMENT 'uniacid';");
}
if(!pdo_fieldexists('hetu_halfoff_newslog',  'newsid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_newslog')." ADD `newsid` int(11) unsigned NOT NULL COMMENT '消息表id';");
}
if(!pdo_fieldexists('hetu_halfoff_newslog',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_newslog')." ADD `openid` varchar(200) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('hetu_halfoff_staff',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_staff')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '员工表id 主键';");
}
if(!pdo_fieldexists('hetu_halfoff_staff',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_staff')." ADD `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id';");
}
if(!pdo_fieldexists('hetu_halfoff_staff',  'bus_id')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_staff')." ADD `bus_id` int(11) unsigned NOT NULL COMMENT '商户表id';");
}
if(!pdo_fieldexists('hetu_halfoff_staff',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_staff')." ADD `openid` varchar(200) NOT NULL COMMENT '核销人员openid';");
}
if(!pdo_fieldexists('hetu_halfoff_staff',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_staff')." ADD `nickname` varchar(50) NOT NULL COMMENT '核销人员nickname';");
}
if(!pdo_fieldexists('hetu_halfoff_staff',  'retain1')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_staff')." ADD `retain1` varchar(50) DEFAULT NULL COMMENT '保留字段1';");
}
if(!pdo_fieldexists('hetu_halfoff_staff',  'retain2')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_staff')." ADD `retain2` varchar(50) DEFAULT NULL COMMENT '保留字段2';");
}
if(!pdo_fieldexists('hetu_halfoff_staff',  'retain3')) {
	pdo_query("ALTER TABLE ".tablename('hetu_halfoff_staff')." ADD `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3';");
}

?>