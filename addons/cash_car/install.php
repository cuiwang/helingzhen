<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_cash_car_blacklist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `dateline` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `ims_cash_car_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号id',
  `storeid` int(11) NOT NULL DEFAULT '0' COMMENT '服务点id',
  `goodsid` int(11) NOT NULL COMMENT '洗车项目id',
  `price` varchar(10) NOT NULL COMMENT '价格',
  `from_user` varchar(50) NOT NULL COMMENT '用户openid',
  `total` int(10) unsigned NOT NULL COMMENT '数量',
  `integral` int(11) NOT NULL COMMENT '赠送积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='洗车购物车表' AUTO_INCREMENT=2065 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='洗车分类表' AUTO_INCREMENT=3 ;


CREATE TABLE IF NOT EXISTS `ims_cash_car_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号',
  `storeid` int(11) NOT NULL DEFAULT '0' COMMENT '服务点id',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `unitname` varchar(5) NOT NULL DEFAULT '次',
  `content` text NOT NULL COMMENT '项目介绍',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `onlycard` varchar(255) DEFAULT NULL,
  `free_card` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL,
  `subcount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被点次数',
  `cid` int(11) NOT NULL COMMENT '分类id',
  `integral` int(11) NOT NULL COMMENT '赠送积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='洗车项目表' AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_goods_copy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号',
  `storeid` int(11) NOT NULL DEFAULT '0' COMMENT '服务点id',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `unitname` varchar(5) NOT NULL DEFAULT '次',
  `content` text NOT NULL COMMENT '项目介绍',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `onlycard` varchar(255) DEFAULT NULL,
  `free_card` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) unsigned NOT NULL,
  `subcount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被点次数',
  `cid` int(11) NOT NULL COMMENT '分类id',
  `integral` int(11) NOT NULL COMMENT '赠送积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='洗车项目表' AUTO_INCREMENT=18 ;


CREATE TABLE IF NOT EXISTS `ims_cash_car_goods_evaluate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT '公众号id',
  `orderid` int(11) NOT NULL COMMENT '订单id',
  `ordersn` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '订单编号',
  `goodsid` int(11) NOT NULL COMMENT '洗车项目id',
  `goods_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '洗车项目名称',
  `goods_price` decimal(10,2) NOT NULL COMMENT '洗车项目价格',
  `storeid` int(11) NOT NULL COMMENT '服务点id',
  `store_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '服务点名称',
  `grade` tinyint(1) NOT NULL COMMENT '评价 1.好评 2.中评 3.差评',
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '评价内容',
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `from_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '评价人',
  `images` text COLLATE utf8_unicode_ci NOT NULL COMMENT '洗车相片',
  `worker` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '洗车人',
  `add_time` int(10) NOT NULL COMMENT '评价时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='洗车项目评价表' AUTO_INCREMENT=52 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_member_onecard` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '会员编号',
  `weid` int(11) NOT NULL COMMENT '公众号id',
  `from_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '会员openid',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '洗车卡名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '单价',
  `number` int(4) unsigned NOT NULL COMMENT '洗车卡次数',
  `onlycard` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '洗车卡标识',
  `validity` int(10) NOT NULL COMMENT '有效期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='会员洗车卡次数' AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_member_onecard_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `giver_id` int(11) NOT NULL COMMENT '赠送人id',
  `give_mobile` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `giver_from_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '赠送人openid',
  `receiver_id` int(11) NOT NULL COMMENT '接收人id',
  `receiver_mobile` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `receiver_from_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '接收人openid',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '洗车卡名称',
  `number` int(11) NOT NULL COMMENT '数量',
  `add_time` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='洗车卡转赠日志表' AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_member_onecard_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `openid` varchar(255) DEFAULT NULL COMMENT '粉丝编号',
  `title` varchar(255) DEFAULT NULL COMMENT '洗车卡名称',
  `reduce` int(11) DEFAULT NULL COMMENT '变动次数',
  `total` int(11) DEFAULT NULL COMMENT '目前总次数',
  `remark` varchar(255) DEFAULT NULL COMMENT '变动理由',
  `add_time` int(10) DEFAULT NULL COMMENT '变动时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员洗车卡变动表' AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_nave` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `type` int(10) NOT NULL DEFAULT '-1' COMMENT '链接类型 -1:自定义 1:首页2:服务点3:洗车项目列表4:我的订单',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '导航名称',
  `link` varchar(200) NOT NULL DEFAULT '' COMMENT '导航链接',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='导航表' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_onecard` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '次卡套餐名称',
  `number` int(11) NOT NULL COMMENT '数量',
  `amount` int(10) NOT NULL COMMENT '总金额',
  `soft` int(3) NOT NULL DEFAULT '0' COMMENT '排序',
  `onlycard` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '该次卡唯一标识(需要与洗车项目关联)',
  `onlycard_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '洗车卡公共名称',
  `validity` int(10) DEFAULT NULL COMMENT '有效期，单位为(天)',
  `content` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '活动介绍',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0.下架 1.上架',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='洗车卡列表' AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_onecard_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `from_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户openid',
  `order_sn` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '订单号',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '洗车卡名称',
  `onlycard` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '洗车卡标识',
  `number` int(11) NOT NULL COMMENT '可洗车次数',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '洗车卡总额',
  `validity` int(3) DEFAULT NULL COMMENT '洗车卡有效期(单位：天)',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态 0.未支付  1.已支付',
  `paytype` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0' COMMENT '1余额，2微信支付，3支付宝',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='洗车卡订单表' AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号id',
  `storeid` int(10) unsigned NOT NULL COMMENT '门店id',
  `usecard` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否使用洗车卡 0.未使用  1.使用',
  `from_user` varchar(50) NOT NULL COMMENT '用户openid',
  `order_type` tinyint(1) NOT NULL COMMENT '订单类型 1.上门洗车  2.到店洗车',
  `ordersn` varchar(30) NOT NULL COMMENT '订单号',
  `totalnum` tinyint(4) DEFAULT NULL COMMENT '总数量',
  `totalprice` varchar(10) NOT NULL COMMENT '总金额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为确认付款方式，2为成功',
  `worker_openid` varchar(255) DEFAULT NULL COMMENT '工作人员openid',
  `images` text COMMENT '洗车图片',
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1余额，2在线，3到付',
  `transid` varchar(100) DEFAULT NULL COMMENT '微信支付单号',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `address` varchar(250) NOT NULL DEFAULT '' COMMENT '地址',
  `lng` decimal(18,10) NOT NULL COMMENT '洗车地址经度',
  `lat` decimal(18,10) NOT NULL COMMENT '洗车地址纬度',
  `map_type` tinyint(1) NOT NULL COMMENT '地图模式 1.腾讯地图 2.百度地图',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `mycard` varchar(50) NOT NULL COMMENT '车牌号码',
  `meal_date` int(11) NOT NULL COMMENT '洗车日期',
  `meal_time` varchar(50) NOT NULL COMMENT '洗车时间',
  `meal_timestamp` int(10) NOT NULL COMMENT '预约时间段开始时间戳',
  `remark` varchar(1000) DEFAULT '' COMMENT '备注',
  `cancel_time` int(10) DEFAULT NULL COMMENT '取消时间',
  `is_overtime` tinyint(1) unsigned DEFAULT '0' COMMENT '是否逾期取消订单 1.是  0.否',
  `isfinish` tinyint(1) NOT NULL DEFAULT '0',
  `finisher` varchar(255) DEFAULT NULL COMMENT '完成订单角色',
  `finish_time` int(10) NOT NULL COMMENT '完成订单时间',
  `is_evaluate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否评价 0.未评价 1.已评价',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `totalintegral` int(11) NOT NULL COMMENT '总共赠送积分',
  `yaoshi` varchar(150) NOT NULL DEFAULT '' COMMENT '钥匙地址',
  `paytime` int(10) NOT NULL DEFAULT '0' COMMENT '付款时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='洗车订单表' AUTO_INCREMENT=500001727 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号id',
  `storeid` int(10) unsigned NOT NULL COMMENT '服务点id',
  `orderid` int(10) unsigned NOT NULL COMMENT '订单id',
  `goodsid` int(10) unsigned NOT NULL COMMENT '洗车项目id',
  `title` varchar(255) NOT NULL COMMENT '洗车项目标题',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `total` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '数量',
  `onlycard` varchar(255) DEFAULT NULL COMMENT '洗车卡标识',
  `dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `integral` int(11) NOT NULL COMMENT '赠送积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='洗车订单项目表' AUTO_INCREMENT=1855 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `istplnotice` tinyint(1) NOT NULL COMMENT '是否开启模版消息 0.否 1.是',
  `wmessage` varchar(255) DEFAULT NULL COMMENT '洗车师傅模版消息id',
  `umessage` varchar(255) DEFAULT NULL COMMENT '用户下单通知模版消息',
  `cmessage` varchar(255) NOT NULL COMMENT '用户取消订单模版消息',
  `title` varchar(50) DEFAULT '' COMMENT '网站名称',
  `book_days` int(4) NOT NULL DEFAULT '2' COMMENT '可提前预约天数',
  `hours_time` int(4) DEFAULT '3' COMMENT '每个时间段最大订单量',
  `radius` int(11) DEFAULT '500' COMMENT '下单地点离服务点最大距离',
  `coords_time` int(4) NOT NULL DEFAULT '30' COMMENT '多少秒刷新位置',
  `is_give` tinyint(1) NOT NULL DEFAULT '1' COMMENT '转赠洗车卡 1.允许 0.禁止',
  `store_model` tinyint(1) NOT NULL DEFAULT '1' COMMENT '服务点模式 1.自营模式  2.多店模式',
  `banner` text COMMENT '首页banner',
  `evaluate_num` int(10) NOT NULL COMMENT '显示最新评价数量',
  `paytype` text NOT NULL COMMENT '支付方式',
  `dateline` int(10) DEFAULT '0' COMMENT '添加时间',
  `check_space` int(10) NOT NULL DEFAULT '3600' COMMENT '检查未支付订单间隔 默认3600秒',
  `lastcheck` int(10) NOT NULL DEFAULT '0' COMMENT '最后检查时间',
  `refuseorder` text COMMENT '系统自动拒绝订单时间',
  `smsurl` text COMMENT '短信请求地址',
  `sharelink` text NOT NULL COMMENT '分享信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='基本设置' AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_sms_template` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL COMMENT '公众号id',
  `title` varchar(255) DEFAULT NULL COMMENT '模版标题',
  `content` text COMMENT '短信内容',
  `status` tinyint(1) DEFAULT '0' COMMENT '模版状态 0.关闭 1.开启',
  `userscene` tinyint(3) DEFAULT NULL COMMENT '1、通知用户：用户付款 2、通知用户：洗车工已接单 3、通知用户：洗车工完成订单 4、通知用户：洗车工取消订单 5、通知用户：系统取消超时未接订单 11、通知洗车工：新预约订单',
  `addtime` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_stores` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '商家logo',
  `store_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '店铺类型 1.上门洗车 2.到店洗车',
  `map_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '地图类型 1.腾讯地图 2.百度地图',
  `commission` decimal(5,2) DEFAULT NULL COMMENT '服务点佣金比例',
  `info` text NOT NULL COMMENT '服务点介绍',
  `content` text NOT NULL COMMENT '简介',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `place` varchar(200) NOT NULL DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `txlat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '腾讯地图经度',
  `txlng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '腾讯地图纬度',
  `hours` text NOT NULL COMMENT '营业时间',
  `hours_time` int(4) NOT NULL COMMENT '每小时最大订单量',
  `radius` int(11) NOT NULL DEFAULT '200' COMMENT '距离范围',
  `thumb_url` varchar(1000) DEFAULT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示 0.隐藏 1.显示 2.审核中',
  `displayorder` int(4) NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  `accountid` int(11) NOT NULL DEFAULT '0' COMMENT '该字段对应服务点管理表(fy_car_account)的id字段',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `bookingtime` int(3) NOT NULL DEFAULT '0' COMMENT '提前(分钟)下单时间 0为每个时间段都可下单',
  `dayoff` varchar(255) NOT NULL DEFAULT '0' COMMENT '服务点休息日',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='服务点表' AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_stores_worker` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL COMMENT '公众号id',
  `name` varchar(20) CHARACTER SET gbk NOT NULL COMMENT '洗车师傅名称',
  `mobile` varchar(50) CHARACTER SET gbk NOT NULL COMMENT '手机号码',
  `openid` varchar(255) CHARACTER SET gbk NOT NULL COMMENT '洗车师傅openid',
  `wx_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '对应微信用户',
  `car_num` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '车辆唯一标识',
  `storeid` int(10) NOT NULL COMMENT '服务点id',
  `store_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '服务点名称',
  `isshow` int(1) NOT NULL DEFAULT '1' COMMENT '1,0 是否显示',
  `sort` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1.网站自营 2.代理加盟商',
  `workerid` int(11) NOT NULL DEFAULT '0' COMMENT '洗车工作人员id(与fy_car_worker的id对应)',
  `detail` varchar(255) CHARACTER SET gbk DEFAULT NULL COMMENT '介绍',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工作人员表' AUTO_INCREMENT=16 ;

CREATE TABLE IF NOT EXISTS `ims_cash_car_store_time` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `time` varchar(50) NOT NULL COMMENT '时间段',
  `soft` int(4) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='服务点工作时间表' AUTO_INCREMENT=25 ;"

INSERT INTO `ims_cash_car_store_time` (`id`, `time`, `soft`) VALUES
(1, '00:00~01:00', 1),
(2, '01:00~01:02', 2),
(3, '02:00~03:00', 3),
(4, '03:00~04:00', 4),
(5, '04:00~05:00', 5),
(6, '05:00~06:00', 6),
(7, '06:00~07:00', 7),
(8, '07:00~08:00', 8),
(9, '08:00~09:00', 9),
(10, '09:00~10:00', 10),
(11, '10:00~11:00', 11),
(12, '11:00~12:00', 12),
(13, '12:00~13:00', 13),
(14, '13:00~14:00', 14),
(15, '14:00~15:00', 15),
(16, '15:00~16:00', 16),
(17, '16:00~17:00', 17),
(18, '17:00~18:00', 18),
(19, '18:00~19:00', 19),
(20, '19:00~20:00', 20),
(21, '20:00~21:00', 21),
(22, '21:00~22:00', 22),
(23, '22:00~23:00', 23),
(24, '23:00~00:00', 24););
