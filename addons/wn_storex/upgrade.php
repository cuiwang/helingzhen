<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_storex_activity_clerk_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `displayorder` int(4) NOT NULL,
  `pid` int(6) NOT NULL,
  `group_name` varchar(20) NOT NULL,
  `title` varchar(20) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  `type` varchar(20) NOT NULL,
  `permission` varchar(50) NOT NULL,
  `system` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_activity_clerks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联users表uid',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `password` (`password`),
  KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_activity_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '物品名称',
  `description` text NOT NULL COMMENT '描述信息',
  `thumb` varchar(500) NOT NULL COMMENT '缩略图',
  `type` tinyint(1) unsigned NOT NULL COMMENT '物品类型，1系统卡券，2微信呢卡券，3实物，4虚拟物品(未启用)，5营销模块操作次数',
  `extra` varchar(3000) NOT NULL DEFAULT '' COMMENT '兑换产品属性 卡券自增id',
  `credit` int(10) unsigned NOT NULL COMMENT '兑换积分数量',
  `credittype` varchar(10) NOT NULL COMMENT '兑换积分类型',
  `pretotal` int(11) NOT NULL COMMENT '每个人最大兑换次数',
  `num` int(11) NOT NULL COMMENT '已兑换礼品数量',
  `total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总量',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `extra` (`extra`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_activity_stores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `business_name` varchar(50) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `category` varchar(255) NOT NULL,
  `province` varchar(15) NOT NULL,
  `city` varchar(15) NOT NULL,
  `district` varchar(15) NOT NULL,
  `address` varchar(50) NOT NULL,
  `longitude` varchar(15) NOT NULL,
  `latitude` varchar(15) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `photo_list` varchar(10000) NOT NULL,
  `avg_price` int(10) unsigned NOT NULL,
  `recommend` varchar(255) NOT NULL,
  `special` varchar(255) NOT NULL,
  `introduction` varchar(255) NOT NULL,
  `open_time` varchar(50) NOT NULL,
  `location_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL COMMENT '1 审核通过 2 审核中 3审核未通过',
  `source` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1为系统门店，2为微信门店',
  `message` varchar(500) NOT NULL,
  `store_base_id` int(11) NOT NULL COMMENT '普通店铺添加为微信门店',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `location_id` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_bases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `lng` decimal(10,6) DEFAULT '0.000000',
  `lat` decimal(10,6) DEFAULT '0.000000',
  `address` varchar(255) DEFAULT '',
  `location_p` varchar(50) DEFAULT '',
  `location_c` varchar(50) DEFAULT '',
  `location_a` varchar(50) DEFAULT '',
  `status` int(11) DEFAULT '0',
  `phone` varchar(255) DEFAULT '',
  `mail` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `thumborder` varchar(255) DEFAULT '',
  `description` text,
  `content` text,
  `store_info` text COMMENT '关于我们',
  `traffic` text,
  `thumbs` text,
  `detail_thumbs` text COMMENT '详情页图片',
  `displayorder` int(11) DEFAULT '0',
  `integral_rate` int(11) NOT NULL DEFAULT '0' COMMENT '在该店铺消费返积分的比例',
  `store_type` int(8) NOT NULL DEFAULT '0' COMMENT '店铺类型',
  `extend_table` varchar(50) NOT NULL COMMENT '该店铺对应的扩张表',
  `timestart` varchar(50) NOT NULL DEFAULT '0' COMMENT '运营开始时间',
  `timeend` varchar(50) NOT NULL DEFAULT '0' COMMENT '运营结束时间',
  `distance` int(11) NOT NULL COMMENT '配送距离',
  `category_set` tinyint(4) NOT NULL DEFAULT '1' COMMENT '分类开启设置1开启，2关闭',
  `skin_style` varchar(48) NOT NULL DEFAULT 'display' COMMENT '皮肤选择',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_business` (
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
CREATE TABLE IF NOT EXISTS `ims_storex_categorys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `store_base_id` int(11) NOT NULL COMMENT '该分类属于哪个店铺的',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `category_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '分类类型 1 酒店，2,普通',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_clerk` (
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
  `username` varchar(30) DEFAULT '' COMMENT '用户名',
  `password` varchar(200) DEFAULT '' COMMENT '密码',
  `salt` varchar(8) NOT NULL DEFAULT '' COMMENT '加密盐',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `permission` text NOT NULL COMMENT '店员权限',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `code` varchar(6) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `total` tinyint(3) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `comment` varchar(255) DEFAULT '',
  `goodsid` int(11) NOT NULL COMMENT '评论商品的id',
  `comment_level` tinyint(11) NOT NULL COMMENT '评论商品的级别',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_comment_clerk` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `acid` int(10) unsigned NOT NULL DEFAULT '0',
  `card_id` varchar(50) NOT NULL,
  `type` varchar(15) NOT NULL COMMENT '卡券类型',
  `logo_url` varchar(150) NOT NULL,
  `code_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'code类型（二维码/条形码/code码）',
  `brand_name` varchar(15) NOT NULL COMMENT '商家名称',
  `title` varchar(15) NOT NULL,
  `sub_title` varchar(20) NOT NULL,
  `color` varchar(15) NOT NULL,
  `notice` varchar(15) NOT NULL COMMENT '使用说明',
  `description` varchar(1000) NOT NULL,
  `date_info` varchar(200) NOT NULL COMMENT '使用期限',
  `quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总库存',
  `use_custom_code` tinyint(3) NOT NULL DEFAULT '0',
  `bind_openid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `can_share` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否可分享',
  `can_give_friend` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否可转赠给朋友',
  `get_limit` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '每人领取限制',
  `service_phone` varchar(20) NOT NULL,
  `extra` varchar(1000) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:审核中,2:未通过,3:已通过,4:卡券被商户删除,5:未知',
  `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架',
  `is_selfconsume` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启自助核销',
  `promotion_url_name` varchar(10) NOT NULL,
  `promotion_url` varchar(100) NOT NULL,
  `promotion_url_sub_title` varchar(10) NOT NULL,
  `source` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '来源，1是系统，2是微信',
  `dosage` int(10) unsigned DEFAULT '0' COMMENT '已领取数量',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `card_id` (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_coupon_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `msg_id` int(10) NOT NULL DEFAULT '0',
  `status` int(10) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL DEFAULT '',
  `type` int(3) NOT NULL DEFAULT '0' COMMENT '1 发送系统卡券 2发送微信卡券',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `coupons` int(11) NOT NULL COMMENT '选择派发的卡券的id',
  `description` varchar(255) NOT NULL DEFAULT '‘’',
  `members` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_coupon_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `friend_openid` varchar(50) NOT NULL,
  `givebyfriend` tinyint(3) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `hash` varchar(32) NOT NULL,
  `addtime` int(10) unsigned NOT NULL,
  `usetime` int(10) unsigned NOT NULL,
  `status` tinyint(3) NOT NULL,
  `clerk_name` varchar(15) NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `store_id` int(10) unsigned NOT NULL,
  `clerk_type` tinyint(3) unsigned NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `grantmodule` varchar(255) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `granttype` tinyint(4) NOT NULL COMMENT '获取卡券的方式：1 兑换，2 扫码',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`acid`),
  KEY `card_id` (`card_id`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_coupon_store` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `couponid` varchar(255) NOT NULL DEFAULT '',
  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `couponid` (`couponid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_base_id` int(11) DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `oprice` decimal(10,2) DEFAULT '0.00',
  `cprice` decimal(10,2) DEFAULT '0.00',
  `mprice` varchar(255) NOT NULL DEFAULT '',
  `thumbs` text,
  `device` text,
  `reserve_device` text COMMENT '预定说明',
  `status` int(11) DEFAULT '0',
  `sales` text,
  `can_reserve` int(11) NOT NULL DEFAULT '1' COMMENT '预定设置',
  `can_buy` int(11) NOT NULL DEFAULT '1' COMMENT '购买设置',
  `isshow` int(11) DEFAULT '0',
  `score` int(11) DEFAULT '0' COMMENT '购买商品积分',
  `sortid` int(11) DEFAULT '0',
  `sold_num` int(11) NOT NULL DEFAULT '0' COMMENT '已售的数量',
  `store_type` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_hotel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_base_id` int(11) NOT NULL COMMENT '店铺基表对应的id',
  `weid` int(11) DEFAULT '0',
  `ordermax` int(11) DEFAULT '0',
  `numsmax` int(11) DEFAULT '0',
  `daymax` int(11) DEFAULT '0',
  `roomcount` int(11) DEFAULT '0',
  `sales` text,
  `level` int(11) DEFAULT '0',
  `device` text,
  `brandid` int(11) DEFAULT '0',
  `businessid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_mc_card` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '会员卡名称',
  `color` varchar(255) NOT NULL DEFAULT '' COMMENT '会员卡字颜色',
  `background` varchar(255) NOT NULL DEFAULT '' COMMENT '背景设置',
  `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo图片',
  `format_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否用手机号作为会员卡号',
  `format` varchar(50) NOT NULL DEFAULT '' COMMENT '会员卡卡号规则',
  `description` varchar(512) NOT NULL DEFAULT '' COMMENT '会员卡说明',
  `fields` varchar(1000) NOT NULL DEFAULT '' COMMENT '会员卡资料',
  `snpos` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用1:启用0:关闭',
  `business` text NOT NULL,
  `discount_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '折扣类型.1:满减,2:折扣',
  `discount` varchar(3000) NOT NULL DEFAULT '' COMMENT '各个会员组的优惠详情',
  `grant` varchar(3000) NOT NULL COMMENT '领卡赠送:积分,余额,优惠券',
  `grant_rate` varchar(20) NOT NULL DEFAULT '0' COMMENT '消费返积分比率',
  `offset_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分抵现比例',
  `offset_max` int(10) NOT NULL DEFAULT '0' COMMENT '每单最多可抵现金数量',
  `nums_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '计次是否开启，0为关闭，1为开启',
  `nums_text` varchar(15) NOT NULL COMMENT '计次名称',
  `nums` varchar(1000) NOT NULL DEFAULT '' COMMENT '计次规则',
  `times_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '计时是否开启，0为关闭，1为开启',
  `times_text` varchar(15) NOT NULL COMMENT '计时名称',
  `times` varchar(1000) NOT NULL DEFAULT '' COMMENT '计时规则',
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `recommend_status` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sign_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '签到功能是否开启，0为关闭，1为开启',
  `brand_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商户名字,',
  `notice` varchar(48) NOT NULL DEFAULT '' COMMENT '卡券使用提醒',
  `quantity` int(10) NOT NULL DEFAULT '0' COMMENT '会员卡库存',
  `max_increase_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '用户单次可获取的积分上限',
  `least_money_to_use_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '抵扣条件',
  `source` int(1) NOT NULL DEFAULT '1' COMMENT '1.系统会员卡，2微信会员卡',
  `card_id` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_mc_card_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(50) NOT NULL,
  `cid` int(10) NOT NULL DEFAULT '0',
  `cardsn` varchar(20) NOT NULL DEFAULT '',
  `mobile` varchar(11) NOT NULL COMMENT '注册手机号',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `realname` varchar(255) NOT NULL COMMENT '真实姓名',
  `status` tinyint(1) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `nums` int(10) unsigned NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fields` varchar(2500) NOT NULL COMMENT '扩展的信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_mc_card_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(15) NOT NULL,
  `model` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1：充值，2：消费',
  `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `tag` varchar(10) NOT NULL COMMENT '次数|时长|充值金额',
  `note` varchar(255) NOT NULL,
  `remark` varchar(200) NOT NULL COMMENT '备注，只有管理员可以看',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_mc_member_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `property` varchar(200) NOT NULL DEFAULT '' COMMENT '当前公众号用户属性',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_member` (
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
CREATE TABLE IF NOT EXISTS `ims_storex_notices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:公共消息，2:个人消息',
  `title` varchar(30) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `groupid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '通知会员组。默认为所有会员',
  `content` text NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_notices_unread` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice_id` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `is_new` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:公共通知，2：个人通知',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`),
  KEY `notice_id` (`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `roomid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `remark` varchar(255) DEFAULT '',
  `btime` int(11) DEFAULT '0',
  `etime` int(11) DEFAULT '0',
  `style` varchar(255) DEFAULT '',
  `nums` int(11) DEFAULT '0',
  `oprice` decimal(10,2) DEFAULT '0.00',
  `cprice` decimal(10,2) DEFAULT '0.00',
  `mprice` decimal(10,2) DEFAULT '0.00',
  `info` text,
  `time` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `mode_distribute` int(11) NOT NULL COMMENT '配送方式  1自提 ，2配送',
  `order_time` int(11) NOT NULL DEFAULT '0' COMMENT '自提是自提时间，配送是配送时间',
  `addressid` int(11) NOT NULL COMMENT '配送选择的地址id',
  `goods_status` int(11) NOT NULL COMMENT '货物状态：1未发送，2已发送，3已收货',
  `paytype` int(11) DEFAULT '0',
  `action` int(11) NOT NULL DEFAULT '2' COMMENT '1预定  2购买',
  `paystatus` int(11) DEFAULT '0',
  `comment` int(3) NOT NULL DEFAULT '0',
  `msg` text,
  `mngtime` int(11) DEFAULT '0',
  `contact_name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人',
  `day` tinyint(2) NOT NULL DEFAULT '0' COMMENT '住几晚',
  `sum_price` decimal(10,2) DEFAULT '0.00',
  `ordersn` varchar(30) DEFAULT '',
  `clerkcomment` int(11) DEFAULT '0',
  `track_number` varchar(64) NOT NULL COMMENT '物流单号',
  `express_name` varchar(50) NOT NULL COMMENT '物流类型',
  `coupon` int(11) NOT NULL COMMENT '使用卡券信息',
  PRIMARY KEY (`id`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_weid` (`weid`),
  KEY `indx_roomid` (`roomid`),
  KEY `indx_memberid` (`memberid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_paycenter_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `clerk_id` int(10) unsigned NOT NULL DEFAULT '0',
  `store_id` int(10) unsigned NOT NULL DEFAULT '0',
  `clerk_type` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `uniontid` varchar(40) NOT NULL,
  `transaction_id` varchar(40) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT '支付方式',
  `trade_type` varchar(10) NOT NULL COMMENT '支付类型:刷卡支付,扫描支付',
  `body` varchar(255) NOT NULL COMMENT '商品信息',
  `fee` varchar(15) NOT NULL COMMENT '商品费用',
  `final_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠后应付价格',
  `credit1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '抵消积分',
  `credit1_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '积分抵消金额',
  `credit2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额支付金额',
  `cash` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '线上支付金额',
  `remark` varchar(255) NOT NULL,
  `auth_code` varchar(30) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL COMMENT '付款人',
  `follow` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否关注公众号',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '线上支付状态',
  `credit_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '积分,余额的交易状态.0:未扣除,1:已扣除',
  `paytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `hotelid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotelid` int(11) DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类id',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类id',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `oprice` decimal(10,2) DEFAULT '0.00',
  `cprice` decimal(10,2) DEFAULT '0.00',
  `mprice` varchar(255) NOT NULL DEFAULT '',
  `thumbs` text,
  `device` text,
  `reserve_device` text COMMENT '预定说明',
  `area` varchar(255) DEFAULT '',
  `floor` varchar(255) DEFAULT '',
  `smoke` varchar(255) DEFAULT '',
  `bed` varchar(255) DEFAULT '',
  `persons` int(11) DEFAULT '0',
  `bedadd` varchar(30) DEFAULT '',
  `status` int(11) DEFAULT '0',
  `can_reserve` int(11) NOT NULL DEFAULT '1' COMMENT '预定设置',
  `can_buy` int(11) NOT NULL DEFAULT '1' COMMENT '购买设置',
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
  `sold_num` int(11) NOT NULL DEFAULT '0' COMMENT '已售的数量',
  `nickname` varchar(255) NOT NULL DEFAULT '',
  `service` decimal(10,2) DEFAULT '0.00',
  `store_type` int(8) NOT NULL DEFAULT '1' COMMENT '所属店铺类型',
  `is_house` int(11) NOT NULL DEFAULT '1' COMMENT '是否是房型 1 是，2不是 ',
  PRIMARY KEY (`id`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_room_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `hotelid` int(11) DEFAULT '0',
  `roomid` int(11) DEFAULT '0',
  `roomdate` int(11) DEFAULT '0',
  `thisdate` varchar(255) NOT NULL DEFAULT '' COMMENT '当天日期',
  `oprice` decimal(10,2) DEFAULT '0.00',
  `cprice` decimal(10,2) DEFAULT '0.00',
  `mprice` decimal(10,2) DEFAULT '0.00',
  `num` varchar(255) DEFAULT '-1',
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_hotelid` (`hotelid`),
  KEY `indx_roomid` (`roomid`),
  KEY `indx_roomdate` (`roomdate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_set` (
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
  `template` varchar(32) DEFAULT NULL COMMENT '发送模板消息',
  `templateid` varchar(255) NOT NULL,
  `paytype1` tinyint(1) DEFAULT '0',
  `paytype2` tinyint(1) DEFAULT '0',
  `paytype3` tinyint(1) DEFAULT '0',
  `version` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0单酒店版1多酒店版',
  `location_p` varchar(50) DEFAULT '',
  `location_c` varchar(50) DEFAULT '',
  `location_a` varchar(50) DEFAULT '',
  `smscode` int(3) NOT NULL DEFAULT '0',
  `refund` int(3) NOT NULL DEFAULT '0',
  `refuse_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱',
  `confirm_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱',
  `check_in_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '酒店已入住通知模板id',
  `finish_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '酒店订单完成通知模板id',
  `nickname` varchar(20) NOT NULL COMMENT '提醒接收微信',
  `extend_switch` varchar(400) NOT NULL COMMENT '扩展开关',
  `source` tinyint(4) NOT NULL DEFAULT '2' COMMENT '卡券类型，1为系统卡券，2为微信卡券',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_storex_sign_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `credit` int(10) unsigned NOT NULL DEFAULT '0',
  `is_grant` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `year` smallint(4) NOT NULL COMMENT '签到的年',
  `month` smallint(2) NOT NULL COMMENT '签到的月',
  `day` smallint(2) NOT NULL COMMENT '签到的日',
  `remedy` tinyint(2) NOT NULL COMMENT '是否是补签 1 是补签,2 是额外',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_sign_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `sign` varchar(1000) NOT NULL,
  `share` varchar(500) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_storex_users_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `type` varchar(30) NOT NULL,
  `permission` varchar(10000) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('storex_activity_clerk_menu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `displayorder` int(4) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `pid` int(6) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'group_name')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `group_name` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `title` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `icon` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'url')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `url` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'type')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `type` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'permission')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `permission` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerk_menu',  'system')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerk_menu')." ADD `system` int(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_activity_clerks',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_activity_clerks',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerks',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关联users表uid';");
}
if(!pdo_fieldexists('storex_activity_clerks',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD `storeid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_activity_clerks',  'name')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD `name` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerks',  'password')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD `password` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerks',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD `mobile` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerks',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_clerks',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD `nickname` varchar(30) NOT NULL;");
}
if(!pdo_indexexists('storex_activity_clerks',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('storex_activity_clerks',  'password')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD KEY `password` (`password`);");
}
if(!pdo_indexexists('storex_activity_clerks',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_clerks')." ADD KEY `openid` (`openid`);");
}
if(!pdo_fieldexists('storex_activity_exchange',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_activity_exchange',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_exchange',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `title` varchar(100) NOT NULL COMMENT '物品名称';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'description')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `description` text NOT NULL COMMENT '描述信息';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `thumb` varchar(500) NOT NULL COMMENT '缩略图';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'type')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `type` tinyint(1) unsigned NOT NULL COMMENT '物品类型，1系统卡券，2微信呢卡券，3实物，4虚拟物品(未启用)，5营销模块操作次数';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'extra')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `extra` varchar(3000) NOT NULL DEFAULT '' COMMENT '兑换产品属性 卡券自增id';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `credit` int(10) unsigned NOT NULL COMMENT '兑换积分数量';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'credittype')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `credittype` varchar(10) NOT NULL COMMENT '兑换积分类型';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'pretotal')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `pretotal` int(11) NOT NULL COMMENT '每个人最大兑换次数';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'num')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `num` int(11) NOT NULL COMMENT '已兑换礼品数量';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'total')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `total` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总量';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态';");
}
if(!pdo_fieldexists('storex_activity_exchange',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `starttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_exchange',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD `endtime` int(10) NOT NULL;");
}
if(!pdo_indexexists('storex_activity_exchange',  'extra')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_exchange')." ADD KEY `extra` (`extra`(255));");
}
if(!pdo_fieldexists('storex_activity_stores',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_activity_stores',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'business_name')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `business_name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'branch_name')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `branch_name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'category')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `category` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'province')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `province` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'city')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `city` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'district')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `district` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'address')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `address` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'longitude')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `longitude` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'latitude')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `latitude` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `telephone` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'photo_list')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `photo_list` varchar(10000) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'avg_price')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `avg_price` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'recommend')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `recommend` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'special')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `special` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'introduction')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `introduction` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'open_time')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `open_time` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'location_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `location_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `status` tinyint(3) unsigned NOT NULL COMMENT '1 审核通过 2 审核中 3审核未通过';");
}
if(!pdo_fieldexists('storex_activity_stores',  'source')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `source` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1为系统门店，2为微信门店';");
}
if(!pdo_fieldexists('storex_activity_stores',  'message')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `message` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('storex_activity_stores',  'store_base_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD `store_base_id` int(11) NOT NULL COMMENT '普通店铺添加为微信门店';");
}
if(!pdo_indexexists('storex_activity_stores',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('storex_activity_stores',  'location_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_activity_stores')." ADD KEY `location_id` (`location_id`);");
}
if(!pdo_fieldexists('storex_bases',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_bases',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_bases',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_bases',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `lng` decimal(10,6) DEFAULT '0.000000';");
}
if(!pdo_fieldexists('storex_bases',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `lat` decimal(10,6) DEFAULT '0.000000';");
}
if(!pdo_fieldexists('storex_bases',  'address')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `address` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_bases',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `location_p` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_bases',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `location_c` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_bases',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `location_a` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_bases',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_bases',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `phone` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_bases',  'mail')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `mail` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_bases',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_bases',  'thumborder')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `thumborder` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_bases',  'description')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `description` text;");
}
if(!pdo_fieldexists('storex_bases',  'content')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `content` text;");
}
if(!pdo_fieldexists('storex_bases',  'store_info')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `store_info` text COMMENT '关于我们';");
}
if(!pdo_fieldexists('storex_bases',  'traffic')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `traffic` text;");
}
if(!pdo_fieldexists('storex_bases',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `thumbs` text;");
}
if(!pdo_fieldexists('storex_bases',  'detail_thumbs')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `detail_thumbs` text COMMENT '详情页图片';");
}
if(!pdo_fieldexists('storex_bases',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_bases',  'integral_rate')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `integral_rate` int(11) NOT NULL DEFAULT '0' COMMENT '在该店铺消费返积分的比例';");
}
if(!pdo_fieldexists('storex_bases',  'store_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `store_type` int(8) NOT NULL DEFAULT '0' COMMENT '店铺类型';");
}
if(!pdo_fieldexists('storex_bases',  'extend_table')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `extend_table` varchar(50) NOT NULL COMMENT '该店铺对应的扩张表';");
}
if(!pdo_fieldexists('storex_bases',  'timestart')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `timestart` varchar(50) NOT NULL DEFAULT '0' COMMENT '运营开始时间';");
}
if(!pdo_fieldexists('storex_bases',  'timeend')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `timeend` varchar(50) NOT NULL DEFAULT '0' COMMENT '运营结束时间';");
}
if(!pdo_fieldexists('storex_bases',  'distance')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `distance` int(11) NOT NULL COMMENT '配送距离';");
}
if(!pdo_fieldexists('storex_bases',  'category_set')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `category_set` tinyint(4) NOT NULL DEFAULT '1' COMMENT '分类开启设置1开启，2关闭';");
}
if(!pdo_fieldexists('storex_bases',  'skin_style')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD `skin_style` varchar(48) NOT NULL DEFAULT 'display' COMMENT '皮肤选择';");
}
if(!pdo_indexexists('storex_bases',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_bases')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('storex_brand',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_brand')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_brand',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_brand')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_brand',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_brand')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_brand',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('storex_brand')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_brand',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_brand')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('storex_brand',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_brand')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('storex_brand',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('storex_brand')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('storex_business',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_business')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_business',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_business')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_business',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_business')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_business',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('storex_business')." ADD `location_p` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_business',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('storex_business')." ADD `location_c` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_business',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('storex_business')." ADD `location_a` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_business',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('storex_business')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_business',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_business')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('storex_business',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_business')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('storex_categorys',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_categorys',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('storex_categorys',  'name')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('storex_categorys',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `thumb` varchar(255) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists('storex_categorys',  'store_base_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `store_base_id` int(11) NOT NULL COMMENT '该分类属于哪个店铺的';");
}
if(!pdo_fieldexists('storex_categorys',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('storex_categorys',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `isrecommand` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_categorys',  'description')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `description` varchar(500) NOT NULL COMMENT '分类介绍';");
}
if(!pdo_fieldexists('storex_categorys',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('storex_categorys',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('storex_categorys',  'category_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_categorys')." ADD `category_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '分类类型 1 酒店，2,普通';");
}
if(!pdo_fieldexists('storex_clerk',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_clerk',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_clerk',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `userid` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_clerk',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `from_user` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_clerk',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `realname` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_clerk',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `mobile` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_clerk',  'score')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `score` int(11) DEFAULT '0' COMMENT '积分';");
}
if(!pdo_fieldexists('storex_clerk',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_clerk',  'userbind')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `userbind` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_clerk',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_clerk',  'username')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `username` varchar(30) DEFAULT '' COMMENT '用户名';");
}
if(!pdo_fieldexists('storex_clerk',  'password')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `password` varchar(200) DEFAULT '' COMMENT '密码';");
}
if(!pdo_fieldexists('storex_clerk',  'salt')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `salt` varchar(8) NOT NULL DEFAULT '' COMMENT '加密盐';");
}
if(!pdo_fieldexists('storex_clerk',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `nickname` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_clerk',  'permission')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD `permission` text NOT NULL COMMENT '店员权限';");
}
if(!pdo_indexexists('storex_clerk',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_clerk')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('storex_code',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_code')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_code',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_code')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_code',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_code')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_code',  'code')) {
	pdo_query("ALTER TABLE ".tablename('storex_code')." ADD `code` varchar(6) NOT NULL;");
}
if(!pdo_fieldexists('storex_code',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('storex_code')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('storex_code',  'total')) {
	pdo_query("ALTER TABLE ".tablename('storex_code')." ADD `total` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_code',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_code')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_code',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_code')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('storex_code',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_code')." ADD KEY `openid` (`openid`);");
}
if(!pdo_fieldexists('storex_comment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_comment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_comment',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_comment',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment')." ADD `uid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_comment',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_comment',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment')." ADD `comment` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_comment',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment')." ADD `goodsid` int(11) NOT NULL COMMENT '评论商品的id';");
}
if(!pdo_fieldexists('storex_comment',  'comment_level')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment')." ADD `comment_level` tinyint(11) NOT NULL COMMENT '评论商品的级别';");
}
if(!pdo_fieldexists('storex_comment_clerk',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment_clerk')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_comment_clerk',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment_clerk')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_comment_clerk',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment_clerk')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_comment_clerk',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment_clerk')." ADD `orderid` int(25) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_comment_clerk',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment_clerk')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_comment_clerk',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment_clerk')." ADD `comment` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_comment_clerk',  'clerkid')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment_clerk')." ADD `clerkid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_comment_clerk',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment_clerk')." ADD `realname` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('storex_comment_clerk',  'grade')) {
	pdo_query("ALTER TABLE ".tablename('storex_comment_clerk')." ADD `grade` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_coupon',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `acid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_coupon',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `card_id` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'type')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `type` varchar(15) NOT NULL COMMENT '卡券类型';");
}
if(!pdo_fieldexists('storex_coupon',  'logo_url')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `logo_url` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'code_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `code_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'code类型（二维码/条形码/code码）';");
}
if(!pdo_fieldexists('storex_coupon',  'brand_name')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `brand_name` varchar(15) NOT NULL COMMENT '商家名称';");
}
if(!pdo_fieldexists('storex_coupon',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `title` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'sub_title')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `sub_title` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'color')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `color` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `notice` varchar(15) NOT NULL COMMENT '使用说明';");
}
if(!pdo_fieldexists('storex_coupon',  'description')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'date_info')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `date_info` varchar(200) NOT NULL COMMENT '使用期限';");
}
if(!pdo_fieldexists('storex_coupon',  'quantity')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `quantity` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总库存';");
}
if(!pdo_fieldexists('storex_coupon',  'use_custom_code')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `use_custom_code` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_coupon',  'bind_openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `bind_openid` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_coupon',  'can_share')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `can_share` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否可分享';");
}
if(!pdo_fieldexists('storex_coupon',  'can_give_friend')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `can_give_friend` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否可转赠给朋友';");
}
if(!pdo_fieldexists('storex_coupon',  'get_limit')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `get_limit` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '每人领取限制';");
}
if(!pdo_fieldexists('storex_coupon',  'service_phone')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `service_phone` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'extra')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `extra` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:审核中,2:未通过,3:已通过,4:卡券被商户删除,5:未知';");
}
if(!pdo_fieldexists('storex_coupon',  'is_display')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `is_display` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架';");
}
if(!pdo_fieldexists('storex_coupon',  'is_selfconsume')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `is_selfconsume` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启自助核销';");
}
if(!pdo_fieldexists('storex_coupon',  'promotion_url_name')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `promotion_url_name` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'promotion_url')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `promotion_url` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'promotion_url_sub_title')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `promotion_url_sub_title` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon',  'source')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `source` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '来源，1是系统，2是微信';");
}
if(!pdo_fieldexists('storex_coupon',  'dosage')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD `dosage` int(10) unsigned DEFAULT '0' COMMENT '已领取数量';");
}
if(!pdo_indexexists('storex_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD KEY `uniacid` (`uniacid`,`acid`);");
}
if(!pdo_indexexists('storex_coupon',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon')." ADD KEY `card_id` (`card_id`);");
}
if(!pdo_fieldexists('storex_coupon_activity',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_coupon_activity',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_activity',  'msg_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `msg_id` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_coupon_activity',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `status` int(10) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('storex_coupon_activity',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `title` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_coupon_activity',  'type')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `type` int(3) NOT NULL DEFAULT '0' COMMENT '1 发送系统卡券 2发送微信卡券';");
}
if(!pdo_fieldexists('storex_coupon_activity',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `thumb` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_coupon_activity',  'coupons')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `coupons` int(11) NOT NULL COMMENT '选择派发的卡券的id';");
}
if(!pdo_fieldexists('storex_coupon_activity',  'description')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `description` varchar(255) NOT NULL DEFAULT '‘’';");
}
if(!pdo_fieldexists('storex_coupon_activity',  'members')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_activity')." ADD `members` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_coupon_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_coupon_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `acid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `card_id` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'friend_openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `friend_openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'givebyfriend')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `givebyfriend` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'code')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `code` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'hash')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `hash` varchar(32) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `addtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'usetime')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `usetime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `status` tinyint(3) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'clerk_name')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `clerk_name` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'clerk_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `clerk_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'store_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `store_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'clerk_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `clerk_type` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `couponid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'grantmodule')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `grantmodule` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `remark` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_record',  'granttype')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD `granttype` tinyint(4) NOT NULL COMMENT '获取卡券的方式：1 兑换，2 扫码';");
}
if(!pdo_indexexists('storex_coupon_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD KEY `uniacid` (`uniacid`,`acid`);");
}
if(!pdo_indexexists('storex_coupon_record',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD KEY `card_id` (`card_id`);");
}
if(!pdo_indexexists('storex_coupon_record',  'hash')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_record')." ADD KEY `hash` (`hash`);");
}
if(!pdo_fieldexists('storex_coupon_store',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_store')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_coupon_store',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_store')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('storex_coupon_store',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_store')." ADD `couponid` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_coupon_store',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_store')." ADD `storeid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('storex_coupon_store',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('storex_coupon_store')." ADD KEY `couponid` (`couponid`);");
}
if(!pdo_fieldexists('storex_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_goods',  'store_base_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `store_base_id` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_goods',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_goods',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_goods',  'oprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `oprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_goods',  'cprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `cprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_goods',  'mprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `mprice` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_goods',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `thumbs` text;");
}
if(!pdo_fieldexists('storex_goods',  'device')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `device` text;");
}
if(!pdo_fieldexists('storex_goods',  'reserve_device')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `reserve_device` text COMMENT '预定说明';");
}
if(!pdo_fieldexists('storex_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_goods',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `sales` text;");
}
if(!pdo_fieldexists('storex_goods',  'can_reserve')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `can_reserve` int(11) NOT NULL DEFAULT '1' COMMENT '预定设置';");
}
if(!pdo_fieldexists('storex_goods',  'can_buy')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `can_buy` int(11) NOT NULL DEFAULT '1' COMMENT '购买设置';");
}
if(!pdo_fieldexists('storex_goods',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `isshow` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_goods',  'score')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `score` int(11) DEFAULT '0' COMMENT '购买商品积分';");
}
if(!pdo_fieldexists('storex_goods',  'sortid')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `sortid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_goods',  'sold_num')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `sold_num` int(11) NOT NULL DEFAULT '0' COMMENT '已售的数量';");
}
if(!pdo_fieldexists('storex_goods',  'store_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD `store_type` int(8) NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('storex_goods',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_goods')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('storex_hotel',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_hotel',  'store_base_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `store_base_id` int(11) NOT NULL COMMENT '店铺基表对应的id';");
}
if(!pdo_fieldexists('storex_hotel',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_hotel',  'ordermax')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `ordermax` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_hotel',  'numsmax')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `numsmax` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_hotel',  'daymax')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `daymax` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_hotel',  'roomcount')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `roomcount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_hotel',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `sales` text;");
}
if(!pdo_fieldexists('storex_hotel',  'level')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `level` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_hotel',  'device')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `device` text;");
}
if(!pdo_fieldexists('storex_hotel',  'brandid')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `brandid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_hotel',  'businessid')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD `businessid` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('storex_hotel',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_hotel')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('storex_mc_card',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_mc_card',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `title` varchar(100) NOT NULL DEFAULT '' COMMENT '会员卡名称';");
}
if(!pdo_fieldexists('storex_mc_card',  'color')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `color` varchar(255) NOT NULL DEFAULT '' COMMENT '会员卡字颜色';");
}
if(!pdo_fieldexists('storex_mc_card',  'background')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `background` varchar(255) NOT NULL DEFAULT '' COMMENT '背景设置';");
}
if(!pdo_fieldexists('storex_mc_card',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `logo` varchar(255) NOT NULL DEFAULT '' COMMENT 'logo图片';");
}
if(!pdo_fieldexists('storex_mc_card',  'format_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `format_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否用手机号作为会员卡号';");
}
if(!pdo_fieldexists('storex_mc_card',  'format')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `format` varchar(50) NOT NULL DEFAULT '' COMMENT '会员卡卡号规则';");
}
if(!pdo_fieldexists('storex_mc_card',  'description')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `description` varchar(512) NOT NULL DEFAULT '' COMMENT '会员卡说明';");
}
if(!pdo_fieldexists('storex_mc_card',  'fields')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `fields` varchar(1000) NOT NULL DEFAULT '' COMMENT '会员卡资料';");
}
if(!pdo_fieldexists('storex_mc_card',  'snpos')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `snpos` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_mc_card',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用1:启用0:关闭';");
}
if(!pdo_fieldexists('storex_mc_card',  'business')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `business` text NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card',  'discount_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `discount_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '折扣类型.1:满减,2:折扣';");
}
if(!pdo_fieldexists('storex_mc_card',  'discount')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `discount` varchar(3000) NOT NULL DEFAULT '' COMMENT '各个会员组的优惠详情';");
}
if(!pdo_fieldexists('storex_mc_card',  'grant')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `grant` varchar(3000) NOT NULL COMMENT '领卡赠送:积分,余额,优惠券';");
}
if(!pdo_fieldexists('storex_mc_card',  'grant_rate')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `grant_rate` varchar(20) NOT NULL DEFAULT '0' COMMENT '消费返积分比率';");
}
if(!pdo_fieldexists('storex_mc_card',  'offset_rate')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `offset_rate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分抵现比例';");
}
if(!pdo_fieldexists('storex_mc_card',  'offset_max')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `offset_max` int(10) NOT NULL DEFAULT '0' COMMENT '每单最多可抵现金数量';");
}
if(!pdo_fieldexists('storex_mc_card',  'nums_status')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `nums_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '计次是否开启，0为关闭，1为开启';");
}
if(!pdo_fieldexists('storex_mc_card',  'nums_text')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `nums_text` varchar(15) NOT NULL COMMENT '计次名称';");
}
if(!pdo_fieldexists('storex_mc_card',  'nums')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `nums` varchar(1000) NOT NULL DEFAULT '' COMMENT '计次规则';");
}
if(!pdo_fieldexists('storex_mc_card',  'times_status')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `times_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '计时是否开启，0为关闭，1为开启';");
}
if(!pdo_fieldexists('storex_mc_card',  'times_text')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `times_text` varchar(15) NOT NULL COMMENT '计时名称';");
}
if(!pdo_fieldexists('storex_mc_card',  'times')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `times` varchar(1000) NOT NULL DEFAULT '' COMMENT '计时规则';");
}
if(!pdo_fieldexists('storex_mc_card',  'params')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `params` longtext NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card',  'html')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `html` longtext NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card',  'recommend_status')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `recommend_status` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_mc_card',  'sign_status')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `sign_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '签到功能是否开启，0为关闭，1为开启';");
}
if(!pdo_fieldexists('storex_mc_card',  'brand_name')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `brand_name` varchar(128) NOT NULL DEFAULT '' COMMENT '商户名字,';");
}
if(!pdo_fieldexists('storex_mc_card',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `notice` varchar(48) NOT NULL DEFAULT '' COMMENT '卡券使用提醒';");
}
if(!pdo_fieldexists('storex_mc_card',  'quantity')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `quantity` int(10) NOT NULL DEFAULT '0' COMMENT '会员卡库存';");
}
if(!pdo_fieldexists('storex_mc_card',  'max_increase_bonus')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `max_increase_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '用户单次可获取的积分上限';");
}
if(!pdo_fieldexists('storex_mc_card',  'least_money_to_use_bonus')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `least_money_to_use_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '抵扣条件';");
}
if(!pdo_fieldexists('storex_mc_card',  'source')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `source` int(1) NOT NULL DEFAULT '1' COMMENT '1.系统会员卡，2微信会员卡';");
}
if(!pdo_fieldexists('storex_mc_card',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD `card_id` varchar(250) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('storex_mc_card',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('storex_mc_card_members',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_mc_card_members',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card_members',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `uid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('storex_mc_card_members',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card_members',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `cid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_mc_card_members',  'cardsn')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `cardsn` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_mc_card_members',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `mobile` varchar(11) NOT NULL COMMENT '注册手机号';");
}
if(!pdo_fieldexists('storex_mc_card_members',  'email')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `email` varchar(50) NOT NULL COMMENT '邮箱';");
}
if(!pdo_fieldexists('storex_mc_card_members',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `realname` varchar(255) NOT NULL COMMENT '真实姓名';");
}
if(!pdo_fieldexists('storex_mc_card_members',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `status` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card_members',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card_members',  'nums')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `nums` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_mc_card_members',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `endtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_mc_card_members',  'fields')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_members')." ADD `fields` varchar(2500) NOT NULL COMMENT '扩展的信息';");
}
if(!pdo_fieldexists('storex_mc_card_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_mc_card_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_mc_card_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_mc_card_record',  'type')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `type` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card_record',  'model')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `model` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1：充值，2：消费';");
}
if(!pdo_fieldexists('storex_mc_card_record',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '充值金额';");
}
if(!pdo_fieldexists('storex_mc_card_record',  'tag')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `tag` varchar(10) NOT NULL COMMENT '次数|时长|充值金额';");
}
if(!pdo_fieldexists('storex_mc_card_record',  'note')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `note` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_card_record',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `remark` varchar(200) NOT NULL COMMENT '备注，只有管理员可以看';");
}
if(!pdo_fieldexists('storex_mc_card_record',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('storex_mc_card_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('storex_mc_card_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD KEY `uid` (`uid`);");
}
if(!pdo_indexexists('storex_mc_card_record',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_card_record')." ADD KEY `addtime` (`addtime`);");
}
if(!pdo_fieldexists('storex_mc_member_property',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_member_property')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_mc_member_property',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_member_property')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('storex_mc_member_property',  'property')) {
	pdo_query("ALTER TABLE ".tablename('storex_mc_member_property')." ADD `property` varchar(200) NOT NULL DEFAULT '' COMMENT '当前公众号用户属性';");
}
if(!pdo_fieldexists('storex_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_member',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_member',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `userid` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_member',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `from_user` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_member',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `realname` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `mobile` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_member',  'score')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `score` int(11) DEFAULT '0' COMMENT '积分';");
}
if(!pdo_fieldexists('storex_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_member',  'userbind')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `userbind` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_member',  'clerk')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `clerk` varchar(32) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_member',  'username')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `username` varchar(30) DEFAULT '' COMMENT '用户名';");
}
if(!pdo_fieldexists('storex_member',  'password')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `password` varchar(200) DEFAULT '' COMMENT '密码';");
}
if(!pdo_fieldexists('storex_member',  'salt')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `salt` varchar(8) NOT NULL DEFAULT '' COMMENT '加密盐';");
}
if(!pdo_fieldexists('storex_member',  'islogin')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `islogin` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_member',  'isauto')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `isauto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '自动添加，0否，1是';");
}
if(!pdo_fieldexists('storex_member',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD `nickname` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('storex_member',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_member')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('storex_notices',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_notices',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_notices',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_notices',  'type')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:公共消息，2:个人消息';");
}
if(!pdo_fieldexists('storex_notices',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('storex_notices',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD `thumb` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('storex_notices',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD `groupid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '通知会员组。默认为所有会员';");
}
if(!pdo_fieldexists('storex_notices',  'content')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('storex_notices',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('storex_notices',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('storex_notices',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices')." ADD KEY `uid` (`uid`);");
}
if(!pdo_fieldexists('storex_notices_unread',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices_unread')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_notices_unread',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices_unread')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_notices_unread',  'notice_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices_unread')." ADD `notice_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_notices_unread',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices_unread')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_notices_unread',  'is_new')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices_unread')." ADD `is_new` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('storex_notices_unread',  'type')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices_unread')." ADD `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1:公共通知，2：个人通知';");
}
if(!pdo_indexexists('storex_notices_unread',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices_unread')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('storex_notices_unread',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices_unread')." ADD KEY `uid` (`uid`);");
}
if(!pdo_indexexists('storex_notices_unread',  'notice_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_notices_unread')." ADD KEY `notice_id` (`notice_id`);");
}
if(!pdo_fieldexists('storex_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'roomid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `roomid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'memberid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `memberid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_order',  'name')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `name` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `mobile` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `remark` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_order',  'btime')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `btime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'etime')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `etime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'style')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `style` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_order',  'nums')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `nums` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'oprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `oprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_order',  'cprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `cprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_order',  'mprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `mprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_order',  'info')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `info` text;");
}
if(!pdo_fieldexists('storex_order',  'time')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `time` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'mode_distribute')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `mode_distribute` int(11) NOT NULL COMMENT '配送方式  1自提 ，2配送';");
}
if(!pdo_fieldexists('storex_order',  'order_time')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `order_time` int(11) NOT NULL DEFAULT '0' COMMENT '自提是自提时间，配送是配送时间';");
}
if(!pdo_fieldexists('storex_order',  'addressid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `addressid` int(11) NOT NULL COMMENT '配送选择的地址id';");
}
if(!pdo_fieldexists('storex_order',  'goods_status')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `goods_status` int(11) NOT NULL COMMENT '货物状态：1未发送，2已发送，3已收货';");
}
if(!pdo_fieldexists('storex_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `paytype` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'action')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `action` int(11) NOT NULL DEFAULT '2' COMMENT '1预定  2购买';");
}
if(!pdo_fieldexists('storex_order',  'paystatus')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `paystatus` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `comment` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'msg')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `msg` text;");
}
if(!pdo_fieldexists('storex_order',  'mngtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `mngtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'contact_name')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `contact_name` varchar(30) NOT NULL DEFAULT '' COMMENT '联系人';");
}
if(!pdo_fieldexists('storex_order',  'day')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `day` tinyint(2) NOT NULL DEFAULT '0' COMMENT '住几晚';");
}
if(!pdo_fieldexists('storex_order',  'sum_price')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `sum_price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `ordersn` varchar(30) DEFAULT '';");
}
if(!pdo_fieldexists('storex_order',  'clerkcomment')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `clerkcomment` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_order',  'track_number')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `track_number` varchar(64) NOT NULL COMMENT '物流单号';");
}
if(!pdo_fieldexists('storex_order',  'express_name')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `express_name` varchar(50) NOT NULL COMMENT '物流类型';");
}
if(!pdo_fieldexists('storex_order',  'coupon')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD `coupon` int(11) NOT NULL COMMENT '使用卡券信息';");
}
if(!pdo_indexexists('storex_order',  'indx_hotelid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD KEY `indx_hotelid` (`hotelid`);");
}
if(!pdo_indexexists('storex_order',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('storex_order',  'indx_roomid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD KEY `indx_roomid` (`roomid`);");
}
if(!pdo_indexexists('storex_order',  'indx_memberid')) {
	pdo_query("ALTER TABLE ".tablename('storex_order')." ADD KEY `indx_memberid` (`memberid`);");
}
if(!pdo_fieldexists('storex_paycenter_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_paycenter_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `pid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'clerk_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `clerk_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'store_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `store_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'clerk_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `clerk_type` tinyint(3) unsigned NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'uniontid')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `uniontid` varchar(40) NOT NULL;");
}
if(!pdo_fieldexists('storex_paycenter_order',  'transaction_id')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `transaction_id` varchar(40) NOT NULL;");
}
if(!pdo_fieldexists('storex_paycenter_order',  'type')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `type` varchar(10) NOT NULL COMMENT '支付方式';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'trade_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `trade_type` varchar(10) NOT NULL COMMENT '支付类型:刷卡支付,扫描支付';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'body')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `body` varchar(255) NOT NULL COMMENT '商品信息';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `fee` varchar(15) NOT NULL COMMENT '商品费用';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'final_fee')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `final_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '优惠后应付价格';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'credit1')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `credit1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '抵消积分';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'credit1_fee')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `credit1_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '积分抵消金额';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'credit2')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `credit2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额支付金额';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'cash')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `cash` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '线上支付金额';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `remark` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('storex_paycenter_order',  'auth_code')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `auth_code` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('storex_paycenter_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('storex_paycenter_order',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `nickname` varchar(50) NOT NULL COMMENT '付款人';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'follow')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `follow` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否关注公众号';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '线上支付状态';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'credit_status')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `credit_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '积分,余额的交易状态.0:未扣除,1:已扣除';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `paytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间';");
}
if(!pdo_fieldexists('storex_paycenter_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_indexexists('storex_paycenter_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_paycenter_order')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('storex_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_reply')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('storex_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('storex_reply')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('storex_reply',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('storex_reply')." ADD `hotelid` int(11) NOT NULL;");
}
if(!pdo_indexexists('storex_reply',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_reply')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('storex_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('storex_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('storex_room',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_room',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一级分类id';");
}
if(!pdo_fieldexists('storex_room',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二级分类id';");
}
if(!pdo_fieldexists('storex_room',  'title')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_room',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_room',  'oprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `oprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_room',  'cprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `cprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_room',  'mprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `mprice` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_room',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `thumbs` text;");
}
if(!pdo_fieldexists('storex_room',  'device')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `device` text;");
}
if(!pdo_fieldexists('storex_room',  'reserve_device')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `reserve_device` text COMMENT '预定说明';");
}
if(!pdo_fieldexists('storex_room',  'area')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `area` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_room',  'floor')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `floor` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_room',  'smoke')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `smoke` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_room',  'bed')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `bed` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('storex_room',  'persons')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `persons` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'bedadd')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `bedadd` varchar(30) DEFAULT '';");
}
if(!pdo_fieldexists('storex_room',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'can_reserve')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `can_reserve` int(11) NOT NULL DEFAULT '1' COMMENT '预定设置';");
}
if(!pdo_fieldexists('storex_room',  'can_buy')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `can_buy` int(11) NOT NULL DEFAULT '1' COMMENT '购买设置';");
}
if(!pdo_fieldexists('storex_room',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `isshow` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `sales` text;");
}
if(!pdo_fieldexists('storex_room',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'area_show')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `area_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'floor_show')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `floor_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'smoke_show')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `smoke_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'bed_show')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `bed_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'persons_show')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `persons_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'bedadd_show')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `bedadd_show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room',  'score')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `score` int(11) DEFAULT '0' COMMENT '订房积分';");
}
if(!pdo_fieldexists('storex_room',  'breakfast')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `breakfast` tinyint(3) DEFAULT '0' COMMENT '0无早 1单早 2双早';");
}
if(!pdo_fieldexists('storex_room',  'sortid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `sortid` int(11) NOT NULL DEFAULT '0' COMMENT '房间id，排序时使用';");
}
if(!pdo_fieldexists('storex_room',  'sold_num')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `sold_num` int(11) NOT NULL DEFAULT '0' COMMENT '已售的数量';");
}
if(!pdo_fieldexists('storex_room',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `nickname` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('storex_room',  'service')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `service` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_room',  'store_type')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `store_type` int(8) NOT NULL DEFAULT '1' COMMENT '所属店铺类型';");
}
if(!pdo_fieldexists('storex_room',  'is_house')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD `is_house` int(11) NOT NULL DEFAULT '1' COMMENT '是否是房型 1 是，2不是 ';");
}
if(!pdo_indexexists('storex_room',  'indx_hotelid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD KEY `indx_hotelid` (`hotelid`);");
}
if(!pdo_indexexists('storex_room',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('storex_room_price',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_room_price',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room_price',  'hotelid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `hotelid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room_price',  'roomid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `roomid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room_price',  'roomdate')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `roomdate` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_room_price',  'thisdate')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `thisdate` varchar(255) NOT NULL DEFAULT '' COMMENT '当天日期';");
}
if(!pdo_fieldexists('storex_room_price',  'oprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `oprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_room_price',  'cprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `cprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_room_price',  'mprice')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `mprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('storex_room_price',  'num')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `num` varchar(255) DEFAULT '-1';");
}
if(!pdo_fieldexists('storex_room_price',  'status')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD `status` int(11) DEFAULT '1';");
}
if(!pdo_indexexists('storex_room_price',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('storex_room_price',  'indx_hotelid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD KEY `indx_hotelid` (`hotelid`);");
}
if(!pdo_indexexists('storex_room_price',  'indx_roomid')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD KEY `indx_roomid` (`roomid`);");
}
if(!pdo_indexexists('storex_room_price',  'indx_roomdate')) {
	pdo_query("ALTER TABLE ".tablename('storex_room_price')." ADD KEY `indx_roomdate` (`roomdate`);");
}
if(!pdo_fieldexists('storex_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_set',  'user')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `user` tinyint(1) DEFAULT '0' COMMENT '用户类型0微信用户1独立用户';");
}
if(!pdo_fieldexists('storex_set',  'reg')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `reg` tinyint(1) DEFAULT '0' COMMENT '是否允许注册0禁止注册1允许注册';");
}
if(!pdo_fieldexists('storex_set',  'bind')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `bind` tinyint(1) DEFAULT '0' COMMENT '是否绑定';");
}
if(!pdo_fieldexists('storex_set',  'regcontent')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `regcontent` text COMMENT '注册提示';");
}
if(!pdo_fieldexists('storex_set',  'ordertype')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `ordertype` tinyint(1) DEFAULT '0' COMMENT '预定类型0电话预定1电话和网络预订';");
}
if(!pdo_fieldexists('storex_set',  'is_unify')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `is_unify` tinyint(1) DEFAULT '0' COMMENT '0使用各分店电话,1使用统一电话';");
}
if(!pdo_fieldexists('storex_set',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `tel` varchar(20) DEFAULT '' COMMENT '统一电话';");
}
if(!pdo_fieldexists('storex_set',  'email')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `email` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱';");
}
if(!pdo_fieldexists('storex_set',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '提醒接受手机';");
}
if(!pdo_fieldexists('storex_set',  'template')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `template` varchar(32) DEFAULT NULL COMMENT '发送模板消息';");
}
if(!pdo_fieldexists('storex_set',  'templateid')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `templateid` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('storex_set',  'paytype1')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `paytype1` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_set',  'paytype2')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `paytype2` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_set',  'paytype3')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `paytype3` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('storex_set',  'version')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `version` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0单酒店版1多酒店版';");
}
if(!pdo_fieldexists('storex_set',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `location_p` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_set',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `location_c` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_set',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `location_a` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('storex_set',  'smscode')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `smscode` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_set',  'refund')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `refund` int(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_set',  'refuse_templateid')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `refuse_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱';");
}
if(!pdo_fieldexists('storex_set',  'confirm_templateid')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `confirm_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱';");
}
if(!pdo_fieldexists('storex_set',  'check_in_templateid')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `check_in_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '酒店已入住通知模板id';");
}
if(!pdo_fieldexists('storex_set',  'finish_templateid')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `finish_templateid` varchar(255) NOT NULL DEFAULT '' COMMENT '酒店订单完成通知模板id';");
}
if(!pdo_fieldexists('storex_set',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `nickname` varchar(20) NOT NULL COMMENT '提醒接收微信';");
}
if(!pdo_fieldexists('storex_set',  'extend_switch')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `extend_switch` varchar(400) NOT NULL COMMENT '扩展开关';");
}
if(!pdo_fieldexists('storex_set',  'source')) {
	pdo_query("ALTER TABLE ".tablename('storex_set')." ADD `source` tinyint(4) NOT NULL DEFAULT '2' COMMENT '卡券类型，1为系统卡券，2为微信卡券';");
}
if(!pdo_fieldexists('storex_sign_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_sign_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_sign_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_sign_record',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `credit` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_sign_record',  'is_grant')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `is_grant` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_sign_record',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `addtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_sign_record',  'year')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `year` smallint(4) NOT NULL COMMENT '签到的年';");
}
if(!pdo_fieldexists('storex_sign_record',  'month')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `month` smallint(2) NOT NULL COMMENT '签到的月';");
}
if(!pdo_fieldexists('storex_sign_record',  'day')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `day` smallint(2) NOT NULL COMMENT '签到的日';");
}
if(!pdo_fieldexists('storex_sign_record',  'remedy')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD `remedy` tinyint(2) NOT NULL COMMENT '是否是补签 1 是补签,2 是额外';");
}
if(!pdo_indexexists('storex_sign_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('storex_sign_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_record')." ADD KEY `uid` (`uid`);");
}
if(!pdo_fieldexists('storex_sign_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_sign_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_set')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('storex_sign_set',  'sign')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_set')." ADD `sign` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('storex_sign_set',  'share')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_set')." ADD `share` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('storex_sign_set',  'content')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_set')." ADD `content` text NOT NULL;");
}
if(!pdo_indexexists('storex_sign_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_sign_set')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('storex_users_permission',  'id')) {
	pdo_query("ALTER TABLE ".tablename('storex_users_permission')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('storex_users_permission',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('storex_users_permission')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_users_permission',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('storex_users_permission')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('storex_users_permission',  'type')) {
	pdo_query("ALTER TABLE ".tablename('storex_users_permission')." ADD `type` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('storex_users_permission',  'permission')) {
	pdo_query("ALTER TABLE ".tablename('storex_users_permission')." ADD `permission` varchar(10000) NOT NULL;");
}
if(!pdo_fieldexists('storex_users_permission',  'url')) {
	pdo_query("ALTER TABLE ".tablename('storex_users_permission')." ADD `url` varchar(255) NOT NULL;");
}

?>