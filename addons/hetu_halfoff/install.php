<?php

$sql =<<<EOF
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_activation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` varchar(200) NOT NULL COMMENT 'uniacid',
  `pwd` varchar(200) NOT NULL COMMENT '卡密',
  `type` int(1) unsigned NOT NULL COMMENT '卡类型 标识月卡周卡等类型',
  `status` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '卡状态 标识是否已使用 0未使用 /1已使用',
  `openid` varchar(200) DEFAULT NULL COMMENT 'openid卡使用者',
  `time` varchar(100) DEFAULT NULL COMMENT '卡使用时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='激活卡表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_agent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '代理商表主键',
  `uniacid` int(11) unsigned NOT NULL COMMENT 'uniacid',
  `name` varchar(50) NOT NULL COMMENT '代理商名称',
  `phone` varchar(20) NOT NULL COMMENT '代理商联系方式',
  `address` varchar(255) NOT NULL COMMENT '代理商地址',
  `password` varchar(255) NOT NULL COMMENT '代理商密码',
  `limit_condition` int(1) unsigned NOT NULL COMMENT '代理商代理等级 1省 2市 3区县',
  `citylist` char(150) NOT NULL COMMENT '代理商城市列表',
  `time` int(20) unsigned NOT NULL COMMENT '添加时间',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '代理商状态 0不显示/1显示',
  `banner` varchar(255) NOT NULL COMMENT '首页幻灯片',
  `imgurl` varchar(255) NOT NULL COMMENT '幻灯片链接',
  `left_title` char(150) NOT NULL COMMENT '首页广告一标题',
  `left_word` char(100) NOT NULL COMMENT '左侧广告关键字',
  `left_tleson` char(100) NOT NULL COMMENT '广告一副标题',
  `left_url` varchar(255) NOT NULL COMMENT '广告一链接URL',
  `left_img` varchar(255) NOT NULL COMMENT '广告一图片url',
  `top_title` char(150) NOT NULL COMMENT '首页广告二标题',
  `top_tleson` char(150) NOT NULL COMMENT '广告二副标题',
  `top_url` varchar(255) NOT NULL COMMENT '广告二链接URL',
  `top_img` varchar(255) NOT NULL COMMENT '广告二图片url',
  `bottom_title` char(150) NOT NULL COMMENT '首页广告三标题',
  `bottom_url` varchar(255) NOT NULL COMMENT '广告三链接',
  `bottom_img` varchar(255) NOT NULL COMMENT '广告三图片链接',
  `bottom_tleson` char(150) NOT NULL COMMENT '广告三副标题',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='代理商表';
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
  `discount_time` text NOT NULL COMMENT '优惠时间',
  `other_discount` varchar(10) NOT NULL COMMENT '其余优惠 几折 0为无优惠',
  `discount_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每次的优惠数量0为不限量',
  `openid` varchar(200) DEFAULT NULL COMMENT 'openid',
  `password` varchar(200) DEFAULT NULL COMMENT '商户密码',
  `agentid` int(11) unsigned NOT NULL COMMENT '代理人id',
  `forward_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '转发量',
  PRIMARY KEY (`bus_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商户信息表';
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
  PRIMARY KEY (`cardtype_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='五折卡类型设置表';
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类表';
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='中心圈表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_city` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uniacid` int(11) unsigned NOT NULL COMMENT 'uniacid',
  `name` varchar(100) NOT NULL COMMENT '名称',
  `status` int(1) unsigned NOT NULL COMMENT '状态 0不显示 1显示',
  `pid` int(11) unsigned NOT NULL COMMENT 'pid',
  `level` int(11) unsigned NOT NULL COMMENT '类型 0省 1市 2区县',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='五折卡-城市表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_collection` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏表id 主键',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `bus_id` int(11) unsigned NOT NULL COMMENT '商户表主键id',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  `status` int(1) unsigned NOT NULL COMMENT '收藏状态 1已收藏 0未收藏',
  `time` int(20) unsigned NOT NULL COMMENT '收藏时间',
  `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_confirm` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '核销表id 主键',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `bus_id` int(11) unsigned NOT NULL COMMENT '商户表主键id',
  `card_id` int(11) unsigned NOT NULL COMMENT '五折卡表主键id',
  `openid` varchar(200) NOT NULL COMMENT '核销人员openid',
  `time` int(20) unsigned NOT NULL COMMENT '核销时间',
  `ishalfoff` int(1) unsigned DEFAULT '1' COMMENT '1五折卡/0优惠券',
  `agentid` int(11) unsigned NOT NULL COMMENT '代理商id',
  `retain3` varchar(50) DEFAULT NULL COMMENT '保留字段3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='核销表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `uniacid` int(11) unsigned NOT NULL COMMENT 'uniacid',
  `bus_id` int(11) unsigned NOT NULL COMMENT '商户主键id',
  `title` varchar(100) NOT NULL COMMENT '优惠券名称',
  `desc` varchar(255) NOT NULL COMMENT '优惠券描述',
  `timezone` varchar(255) NOT NULL COMMENT '优惠券时段',
  `notice` varchar(255) NOT NULL COMMENT '优惠券使用说明',
  `discount` varchar(20) NOT NULL COMMENT '几折',
  `starttime` int(20) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(20) unsigned NOT NULL COMMENT '结束时间',
  `num` int(11) unsigned NOT NULL COMMENT '优惠券个数',
  `limit` int(11) unsigned NOT NULL COMMENT '该优惠券领取限制 0为不限制',
  `status` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 0不显示/1显示',
  `sequence` int(11) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券表';
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠券领取纪录表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_getcard` (
  `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '五折卡表主键id',
  `payno` varchar(150) NOT NULL COMMENT '支付编号',
  `uniacid` int(11) unsigned NOT NULL COMMENT '当前公众号id',
  `cardtype_id` int(11) unsigned NOT NULL COMMENT '五折卡类型设置表主键id',
  `card_no` varchar(100) NOT NULL COMMENT '五折卡号',
  `start_time` int(20) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(20) unsigned NOT NULL COMMENT '结束时间',
  `status` int(1) unsigned NOT NULL COMMENT '标识0未支付/1已领取/2已禁用',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  `code` varchar(200) DEFAULT NULL COMMENT '串码 生成二维码使用',
  `gettype` int(1) unsigned DEFAULT NULL COMMENT '领取类型 支付1/激活2',
  `timeout` int(1) unsigned DEFAULT '0' COMMENT '是否发送消息 0未发送/1已发送',
  `agentid` int(11) unsigned NOT NULL COMMENT '代理商id',
  PRIMARY KEY (`card_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='领取五折卡表';
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块表';
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息表';
CREATE TABLE IF NOT EXISTS `ims_hetu_halfoff_newslog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` varchar(200) NOT NULL COMMENT 'uniacid',
  `newsid` int(11) unsigned NOT NULL COMMENT '消息表id',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息日志表';
EOF;
pdo_run($sql);
