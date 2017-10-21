<?php


$sql = "
	CREATE TABLE IF NOT EXISTS `ims_mc_card` (
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
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_mc_card_members` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(10) unsigned NOT NULL,
	  `uid` int(10) DEFAULT NULL,
	  `openid` varchar(50) NOT NULL,
	  `cid` int(10) NOT NULL DEFAULT '0',
	  `cardsn` varchar(20) NOT NULL DEFAULT '',
	  `status` tinyint(1) NOT NULL,
	  `createtime` int(10) unsigned NOT NULL,
	  `nums` int(10) unsigned NOT NULL DEFAULT '0',
	  `endtime` int(10) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_mc_card_record` (
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
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `ims_mc_card_sign_record` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
	  `uid` int(10) unsigned NOT NULL DEFAULT '0',
	  `credit` int(10) unsigned NOT NULL DEFAULT '0',
	  `is_grant` tinyint(3) unsigned NOT NULL DEFAULT '0',
	  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`),
	  KEY `uniacid` (`uniacid`),
	  KEY `uid` (`uid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_mc_card_credit_set` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
	  `sign` varchar(1000) NOT NULL,
	  `share` varchar(500) NOT NULL,
	  `content` text NOT NULL,
	  PRIMARY KEY (`id`),
	  KEY `uniacid` (`uniacid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_mc_card_notices` (
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
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	
	CREATE TABLE IF NOT EXISTS `ims_mc_card_notices_unread` (
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
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_activity_clerk_menu` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `uniacid` int(11) NOT NULL,
	  `displayorder` int(4) NOT NULL,
	  `pid` int(6) NOT NULL,
	  `group_name` varchar(20) NOT NULL,
	  `title` varchar(20) NOT NULL,
	  `icon` varchar(50) NOT NULL,
	  `url` varchar(60) NOT NULL,
	  `type` varchar(20) NOT NULL,
	  `permission` varchar(50) NOT NULL,
	  `system` int(2) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_activity_clerks` (
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
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='积分兑换店员表';

	CREATE TABLE IF NOT EXISTS `ims_activity_exchange` (
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
	  KEY `extra` (`extra`(333))
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='真实物品兑换表';
		
	CREATE TABLE IF NOT EXISTS `ims_activity_exchange_trades` (
	  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(10) unsigned NOT NULL COMMENT '统一公号',
	  `uid` int(10) unsigned NOT NULL COMMENT '用户(粉丝)id',
	  `exid` int(10) unsigned NOT NULL COMMENT '兑换产品 exchangeid',
	  `type` int(10) unsigned NOT NULL,
	  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交换记录创建时间',
	  PRIMARY KEY (`tid`),
	  KEY `uniacid` (`uniacid`,`uid`,`exid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='真实物品兑换记录表';

	CREATE TABLE IF NOT EXISTS `ims_activity_exchange_trades_shipping` (
	  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(10) unsigned NOT NULL,
	  `exid` int(10) unsigned NOT NULL,
	  `uid` int(10) unsigned NOT NULL,
	  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单状态，0为正常，-1为关闭，1为已发货，2为已完成',
	  `createtime` int(10) unsigned NOT NULL,
	  `province` varchar(30) NOT NULL,
	  `city` varchar(30) NOT NULL,
	  `district` varchar(30) NOT NULL,
	  `address` varchar(255) NOT NULL,
	  `zipcode` varchar(6) NOT NULL,
	  `mobile` varchar(30) NOT NULL,
	  `name` varchar(30) NOT NULL COMMENT '收件人',
	  PRIMARY KEY (`tid`),
	  KEY `uniacid` (`uniacid`),
	  KEY `uid` (`uid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='真实物品兑换发货表';

	CREATE TABLE IF NOT EXISTS `ims_activity_stores` (
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
	  PRIMARY KEY (`id`),
	  KEY `uniacid` (`uniacid`),
	  KEY `location_id` (`location_id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

	CREATE TABLE IF NOT EXISTS `ims_coupon` (
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
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_coupon_activity` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(10) NOT NULL,
	  `msg_id` int(10) NOT NULL DEFAULT '0',
	  `status` int(10) NOT NULL DEFAULT '1',
	  `title` varchar(255) NOT NULL DEFAULT '',
	  `type` int(3) NOT NULL DEFAULT '0' COMMENT '1 发送系统卡券 2发送微信卡券',
	  `thumb` varchar(255) NOT NULL DEFAULT '',
	  `coupons` varchar(255) NOT NULL DEFAULT '',
	  `description` varchar(255) NOT NULL DEFAULT '‘’',
	  `members` varchar(255) NOT NULL DEFAULT '',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_coupon_groups` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(10) NOT NULL,
	  `couponid` varchar(255) NOT NULL DEFAULT '',
	  `groupid` int(10) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_coupon_modules` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(10) unsigned NOT NULL,
	  `acid` int(10) unsigned NOT NULL,
	  `couponid` int(10) unsigned NOT NULL DEFAULT '0',
	  `module` varchar(30) NOT NULL,
	  PRIMARY KEY (`id`),
	  KEY `cid` (`couponid`),
	  KEY `uniacid` (`uniacid`,`acid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_coupon_record` (
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
	  PRIMARY KEY (`id`),
	  KEY `uniacid` (`uniacid`,`acid`),
	  KEY `card_id` (`card_id`),
	  KEY `hash` (`hash`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_coupon_store` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(10) NOT NULL,
	  `couponid` varchar(255) NOT NULL DEFAULT '',
	  `storeid` int(10) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`),
	  KEY `couponid` (`couponid`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

	CREATE TABLE IF NOT EXISTS `ims_mc_member_property` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `uniacid` int(11) NOT NULL,
	  `property` varchar(200) NOT NULL DEFAULT '' COMMENT '当前公众号用户属性',
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户属性设置表';
	
	CREATE TABLE IF NOT EXISTS `ims_paycenter_order` (
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
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;	
";

pdo_run($sql);

global $_W;
//当前公众号是否使用系统卡券
$uni_settings = pdo_getall('uni_settings', '', array('exchange_enable', 'coupon_type', 'uniacid'), 'uniacid');
if (!empty($uni_settings)) {
	foreach ($uni_settings as $key => $value) {
		if (!empty($key)) {
			$cachekey = "modulesetting:{$key}:we7_coupon";
			$setting['coupon_type'] = $value['coupon_type'];
			$setting['exchange_enable'] = $value['exchange_enable'];
			cache_write($cachekey, $setting);
		}
	}
}
