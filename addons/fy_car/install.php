<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_fy_car_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT '公众号id',
  `account` varchar(50) NOT NULL COMMENT '店主登录帐号',
  `password` varchar(32) NOT NULL COMMENT '登录密码',
  `contact` varchar(100) DEFAULT NULL COMMENT '联系人',
  `mobile` varchar(20) DEFAULT NULL COMMENT '联系电话',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '帐号状态 1.正常  2.冻结',
  `add_time` int(10) NOT NULL COMMENT '创建时间',
  `alipay` varchar(100) DEFAULT NULL COMMENT '支付宝帐号',
  `realname` varchar(100) DEFAULT NULL COMMENT '收款人姓名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='洗车核销帐号表' AUTO_INCREMENT=7 ;



CREATE TABLE IF NOT EXISTS `ims_fy_car_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT '公众号id',
  `store_number` int(4) NOT NULL DEFAULT '1' COMMENT '单个帐号最多创建服务点数量',
  `commission` decimal(5,2) NOT NULL COMMENT '佣金比例',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  `multiple` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '积分最多为单价多少倍',
  `conver` decimal(10,2) NOT NULL COMMENT '结算积分兑换佣金比率',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `ims_fy_car_settle` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL COMMENT '公众号id',
  `accountid` int(11) NOT NULL COMMENT '登录用户id',
  `username` varchar(255) NOT NULL COMMENT '用户名/登陆账号',
  `settle_sn` varchar(255) NOT NULL COMMENT '结算单号',
  `business_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运营总额',
  `commission_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金总额',
  `settle_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应结金额',
  `settle_date` varchar(255) NOT NULL COMMENT '结算月份',
  `settle_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '结算状态 1.已出账 2.已完成',
  `add_time` int(10) NOT NULL COMMENT '出账时间',
  `conver` decimal(10,2) NOT NULL COMMENT '结算积分兑换佣金比率',
  `totalIntegral` int(11) NOT NULL COMMENT '赠送总积分',
  `integralAmount` decimal(10,2) NOT NULL COMMENT '赠送积分应付金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='洗车结算表' AUTO_INCREMENT=31 ;


CREATE TABLE IF NOT EXISTS `ims_fy_car_worker` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `worker_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '电子邮箱/手机号码(用户获取openid)',
  `nickname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '微信昵称',
  `openid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `worker_mobile` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `worker_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `car_number` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `storeid` int(11) NOT NULL COMMENT '服务点id',
  `store_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '服务点名称',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '工作状态 0.休假 1.工作',
  `accountid` int(11) NOT NULL DEFAULT '0' COMMENT '所属用户id',
  `add_time` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='洗车工作人员表' AUTO_INCREMENT=8 ;

");
