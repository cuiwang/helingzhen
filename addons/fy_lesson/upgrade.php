<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_fy_lesson_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `author` varchar(100) DEFAULT NULL COMMENT '作者',
  `content` text COMMENT '内容',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '前台展示 0.关闭 1.开启',
  `displayorder` varchar(255) DEFAULT '0' COMMENT '排序 数值越大越靠前',
  `addtime` int(10) DEFAULT NULL COMMENT '发布时间',
  `view` int(11) NOT NULL DEFAULT '0' COMMENT '访问量',
  `linkurl` varchar(1000) DEFAULT NULL COMMENT '原文链接',
  `images` varchar(255) DEFAULT NULL COMMENT '分享图片',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_title` (`title`),
  KEY `idx_author` (`author`),
  KEY `idx_isshow` (`isshow`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_blacklist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `addtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_cashlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `cash_type` tinyint(1) NOT NULL COMMENT '提现方式 1.管理员审核 2.自动到账',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `openid` varchar(255) NOT NULL COMMENT '粉丝编号',
  `cash_num` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.待审核 1.提现成功 -1.审核未通过',
  `disposetime` int(10) NOT NULL DEFAULT '0' COMMENT '处理时间',
  `partner_trade_no` varchar(255) DEFAULT NULL COMMENT '商户订单号',
  `payment_no` varchar(255) DEFAULT NULL COMMENT '微信订单号',
  `remark` text COMMENT '管理员备注',
  `lesson_type` tinyint(1) NOT NULL COMMENT '提现类型 1.分销佣金提现 2.课程收入提现',
  `addtime` int(10) NOT NULL COMMENT '申请时间',
  `cash_way` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1.提现到余额  2.提现到微信钱包',
  `pay_account` varchar(50) DEFAULT NULL COMMENT '提现帐号',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_cash_type` (`cash_type`),
  KEY `idx_cash_way` (`cash_way`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_status` (`status`),
  KEY `idx_lesson_type` (`lesson_type`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='佣金提现表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `ico` varchar(255) DEFAULT NULL COMMENT '分类图标',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `addtime` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程分类表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_collect` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `openid` varchar(255) NOT NULL COMMENT '粉丝编号',
  `outid` int(11) NOT NULL COMMENT '外部编号(课程编号或讲师编号)',
  `ctype` tinyint(1) NOT NULL COMMENT '收藏类型 1.课程 2.讲师',
  `addtime` int(10) NOT NULL COMMENT '收藏时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_ctype` (`ctype`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='收藏表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_commission_level` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `levelname` varchar(50) DEFAULT NULL COMMENT '分销等级名称',
  `commission1` decimal(10,2) DEFAULT '0.00' COMMENT '一级分销佣金比例',
  `commission2` decimal(10,2) DEFAULT '0.00' COMMENT '二级分销佣金比例',
  `commission3` decimal(10,2) DEFAULT '0.00' COMMENT '三级分销佣金比例',
  `updatemoney` decimal(10,2) DEFAULT '0.00' COMMENT '升级条件(分销佣金满多少)',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_levelname` (`levelname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商等级表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_commission_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `orderid` varchar(255) DEFAULT NULL COMMENT '订单id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `openid` varchar(255) DEFAULT NULL COMMENT '粉丝编号',
  `nickname` varchar(100) DEFAULT NULL COMMENT '会员昵称',
  `bookname` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `change_num` decimal(10,2) DEFAULT '0.00' COMMENT '变动数目',
  `grade` tinyint(1) DEFAULT NULL COMMENT '佣金等级',
  `remark` varchar(255) DEFAULT NULL COMMENT '变动说明',
  `addtime` int(10) DEFAULT NULL COMMENT '变动时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_orderid` (`orderid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_nickname` (`nickname`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_grade` (`grade`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='佣金日志表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_coupon` (
  `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `password` varchar(18) NOT NULL COMMENT '优惠码密钥',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠码面值',
  `validity` int(10) NOT NULL COMMENT '有效期',
  `conditions` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用条件(满x元可用)',
  `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未使用 1.已使用',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `uid` int(11) DEFAULT NULL COMMENT '会员编号',
  `openid` varchar(50) DEFAULT NULL COMMENT '粉丝编号',
  `ordersn` varchar(50) DEFAULT NULL COMMENT '订单编号',
  `use_time` int(10) DEFAULT NULL COMMENT '使用时间',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`card_id`),
  UNIQUE KEY `idx_ordersn` (`ordersn`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_password` (`password`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_validity` (`validity`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_evaluate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `orderid` int(11) NOT NULL COMMENT '订单id',
  `ordersn` varchar(255) NOT NULL COMMENT '订单编号',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `bookname` varchar(255) NOT NULL COMMENT '课程名称',
  `openid` varchar(255) NOT NULL COMMENT '粉丝编号',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `nickname` varchar(50) NOT NULL COMMENT '会员昵称',
  `grade` tinyint(1) NOT NULL COMMENT '评价 1.好评 2.中评 3.差评',
  `content` text NOT NULL COMMENT '评价内容',
  `addtime` int(10) NOT NULL COMMENT '评价时间',
  `reply` text COMMENT '评价回复',
  `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(与fy_lesson_teacher表的id字段对应)',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_orderid` (`orderid`),
  KEY `idx_ordersn` (`ordersn`),
  KEY `idx_lessonid` (`lessonid`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_teacherid` (`teacherid`),
  KEY `idx_grade` (`grade`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评价课程表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `openid` varchar(255) NOT NULL COMMENT '粉丝编号',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `addtime` int(10) NOT NULL COMMENT '最后进入时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `openid` varchar(255) NOT NULL COMMENT '粉丝标识',
  `studentno` varchar(20) DEFAULT NULL COMMENT '学号',
  `nickname` varchar(100) DEFAULT NULL COMMENT '会员昵称',
  `parentid` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人id',
  `nopay_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '未结算佣金',
  `pay_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已结算佣金',
  `nopay_lesson` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '未提现课程收入',
  `pay_lesson` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已提现课程收入',
  `vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否vip 0.否 1.是',
  `validity` bigint(11) NOT NULL DEFAULT '0' COMMENT 'vip有效期',
  `pastnotice` int(10) NOT NULL DEFAULT '0' COMMENT 'vip服务过期前最新通知时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销状态 0.关闭 1.开启',
  `uptime` int(10) NOT NULL COMMENT '更新时间',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `agent_level` int(11) NOT NULL DEFAULT '0' COMMENT '分销代理级别',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_vip` (`vip`),
  KEY `idx_validity` (`validity`),
  KEY `idx_pastnotice` (`pastnotice`),
  KEY `idx_status` (`status`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_member_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `ordersn` varchar(255) NOT NULL COMMENT '订单编号',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `openid` varchar(255) NOT NULL COMMENT '粉丝编号',
  `viptime` int(4) NOT NULL COMMENT '会员服务时间',
  `vipmoney` decimal(10,2) NOT NULL COMMENT '会员服务价格',
  `paytype` varchar(50) NOT NULL COMMENT '支付方式',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 0.未支付 1.已支付',
  `paytime` int(10) DEFAULT '0' COMMENT '订单支付时间',
  `addtime` int(10) NOT NULL COMMENT '订单添加时间',
  `member1` int(11) NOT NULL COMMENT '一级代理会员id',
  `commission1` decimal(10,2) NOT NULL COMMENT '一级代理佣金',
  `member2` int(11) NOT NULL COMMENT '二级代理会员id',
  `commission2` decimal(10,2) NOT NULL COMMENT '二级代理佣金',
  `member3` int(11) NOT NULL COMMENT '三级代理会员id',
  `commission3` decimal(10,2) NOT NULL COMMENT '三级代理佣金',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `refer_id` int(11) DEFAULT NULL COMMENT '充值卡id(与vip卡的id对应)',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_paytype` (`paytype`),
  KEY `idx_status` (`status`),
  KEY `idx_refer_id` (`refer_id`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `ordersn` varchar(255) NOT NULL COMMENT '订单编号',
  `uid` int(11) NOT NULL COMMENT '会员id',
  `openid` varchar(255) NOT NULL COMMENT '粉丝编号',
  `lessonid` int(11) NOT NULL COMMENT '课程id',
  `bookname` varchar(255) NOT NULL COMMENT '课程名称',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '课程价格',
  `teacher_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '讲师收入(课程价格分成%)',
  `integral` int(4) NOT NULL DEFAULT '0' COMMENT '赠送积分',
  `paytype` varchar(50) NOT NULL DEFAULT '0' COMMENT '支付方式',
  `paytime` int(10) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `member1` int(11) NOT NULL DEFAULT '0' COMMENT '一级代理会员id',
  `commission1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '一级佣金',
  `member2` int(11) NOT NULL DEFAULT '0' COMMENT '二级代理会员id',
  `commission2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级佣金',
  `member3` int(11) NOT NULL DEFAULT '0' COMMENT '三级代理会员id',
  `commission3` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '三级佣金',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 -1.已取消 0.未支付 1.已支付 2.已评价',
  `addtime` int(10) DEFAULT NULL COMMENT '下单时间',
  `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(与fy_lesson_teacher表的id字段对应)',
  `invoice` varchar(100) DEFAULT NULL COMMENT '发票抬头',
  `coupon` varchar(50) DEFAULT NULL COMMENT '课程优惠码',
  `coupon_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠码面值',
  `validity` int(11) NOT NULL DEFAULT '0' COMMENT '有效期 在有效期内可观看学习课程',
  PRIMARY KEY (`id`),
  KEY `idx_acid` (`acid`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_ordersn` (`ordersn`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_lessonid` (`lessonid`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_teacherid` (`teacherid`),
  KEY `idx_paytype` (`paytype`),
  KEY `idx_status` (`status`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_parent` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号ID',
  `cid` int(11) NOT NULL COMMENT '分类ID',
  `bookname` varchar(255) NOT NULL COMMENT '课程名称',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '课程价格',
  `isdiscount` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启该课程折扣',
  `vipdiscount` int(3) NOT NULL DEFAULT '0' COMMENT 'vip会员折扣',
  `integral` int(11) NOT NULL DEFAULT '0' COMMENT '购买赠送积分',
  `images` varchar(255) DEFAULT NULL COMMENT '课程封图',
  `descript` text COMMENT '课程介绍',
  `difficulty` varchar(100) DEFAULT NULL COMMENT '课程难度',
  `buynum` int(11) NOT NULL DEFAULT '0' COMMENT '正常购买人数',
  `virtual_buynum` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟购买人数',
  `score` decimal(5,2) NOT NULL COMMENT '课程好评率',
  `teacherid` int(11) NOT NULL COMMENT '主讲老师id',
  `commission` text COMMENT '佣金比例',
  `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '课程排序',
  `status` tinyint(1) NOT NULL COMMENT '是否上架',
  `recommendid` varchar(255) DEFAULT NULL COMMENT '推荐板块id',
  `vipview` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'vip权限是否可免费观看',
  `teacher_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '讲师分成%',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  `stock` int(11) NOT NULL COMMENT '课程库存',
  `poster` text COMMENT '视频播放封面图',
  `validity` int(11) NOT NULL DEFAULT '0' COMMENT '有效期 即购买时起多少天内有效',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_cid` (`cid`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_teacherid` (`teacherid`),
  KEY `idx_displayorder` (`displayorder`),
  KEY `idx_status` (`status`),
  KEY `idx_recommendid` (`recommendid`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程主表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_playrecord` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `openid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '粉丝编号',
  `lessonid` int(11) DEFAULT NULL COMMENT '课程id',
  `sectionid` int(11) DEFAULT NULL COMMENT '章节id',
  `addtime` int(10) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_qiniu_upload` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员编号',
  `openid` varchar(255) DEFAULT NULL COMMENT '粉丝编号',
  `teacher` int(11) DEFAULT NULL COMMENT '讲师编号',
  `name` varchar(500) DEFAULT NULL COMMENT '文件名',
  `com_name` varchar(1000) DEFAULT NULL COMMENT '完成文件名',
  `qiniu_url` varchar(1000) DEFAULT NULL COMMENT '文件链接',
  `size` varchar(100) DEFAULT NULL COMMENT '文件大小',
  `addtime` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_teacher` (`teacher`),
  KEY `idx_name` (`name`(333))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_recommend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `rec_name` varchar(255) DEFAULT NULL COMMENT '模块名称',
  `displayorder` int(4) DEFAULT NULL COMMENT '排序',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_is_show` (`is_show`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `tjgx` text COMMENT '推荐关系',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='推荐关系表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `logo` varchar(255) NOT NULL COMMENT 'app端logo',
  `istplnotice` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启模版消息 0.关闭 1.开启',
  `buysucc` varchar(255) DEFAULT NULL COMMENT '用户购买课程通知',
  `pastvip` varchar(255) DEFAULT NULL COMMENT '用户会员服务过期',
  `cnotice` varchar(255) DEFAULT NULL COMMENT '佣金提醒',
  `newjoin` varchar(255) DEFAULT NULL COMMENT '三级分销下级加入提醒',
  `newlesson` varchar(255) DEFAULT NULL COMMENT '新开课提醒学员',
  `neworder` varchar(255) NOT NULL COMMENT '订单通知(管理员)',
  `manageopenid` text NOT NULL COMMENT '新订单提醒(管理员)',
  `sitename` varchar(100) DEFAULT NULL,
  `banner` text COMMENT '焦点图',
  `copyright` varchar(255) NOT NULL COMMENT '版权',
  `vipserver` text COMMENT 'vip时长和价格',
  `sharelink` text COMMENT '链接分享',
  `sharelesson` text COMMENT '分享课程',
  `shareteacher` text COMMENT '分享讲师',
  `closespace` int(4) NOT NULL DEFAULT '60' COMMENT '关闭未付款订单时间间隔',
  `closelast` int(10) NOT NULL DEFAULT '0' COMMENT '上次执行关闭未付款订单时间',
  `is_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销功能 0.关闭 1.开启',
  `self_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销内购 0.关闭 1.开启',
  `sale_rank` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销身份 1.任何人 2.VIP身份',
  `level` tinyint(1) NOT NULL DEFAULT '3' COMMENT '分销等级',
  `commission` text COMMENT '佣金比例',
  `cash_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '提现方式 1.管理员审核 2.自动到账',
  `cash_lower` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '提现最低下限 默认为1元',
  `mchid` varchar(100) DEFAULT NULL COMMENT '微信支付商户号',
  `mchkey` varchar(255) DEFAULT NULL COMMENT '微信支付商户支付密钥',
  `serverIp` varchar(100) DEFAULT NULL COMMENT '服务器Ip',
  `savetype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0.其他存储方式 1.七牛云存储 2.腾讯云存储',
  `qiniu` text COMMENT '七牛云存储参数',
  `qcloud` text COMMENT '腾讯云存储',
  `print_error` tinyint(1) NOT NULL DEFAULT '0' COMMENT '打印视频错误信息 0.关闭 1.开启',
  `vipdiscount` int(3) NOT NULL DEFAULT '0' COMMENT 'vip会员购买课程折扣',
  `footnav` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单显示方式 0.底部菜单 1.悬浮菜单',
  `lessonshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '首页课程显示 1.一行一个课程 2.一行两个课程',
  `teacher_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '讲师收入(课程价格分成%)',
  `isfollow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '强制关注公众号 0.不强制 1.强制',
  `qrcode` varchar(255) DEFAULT NULL COMMENT '公众号二维码',
  `mustinfo` tinyint(1) NOT NULL DEFAULT '0',
  `autogood` int(4) NOT NULL DEFAULT '0' COMMENT '超时自动好评 默认0为关闭',
  `posterbg` varchar(255) DEFAULT NULL COMMENT '推广海报背景图',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  `vipdesc` text NOT NULL COMMENT 'vip服务描述',
  `vip_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'VIP订单分销开关',
  `cash_way` text NOT NULL COMMENT '提现方式',
  `adv` text NOT NULL COMMENT '课程详情页广告',
  `newcash` varchar(255) NOT NULL COMMENT '提现申请通知(管理员)',
  `mobilechange` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启修改手机链接 0.关闭 1.开启',
  `main_color` varchar(50) DEFAULT NULL COMMENT '前台主色调',
  `minor_color` varchar(50) DEFAULT NULL COMMENT '前台副色调',
  `teacherlist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示讲师列表 0.不显示  1.显示',
  `category_ico` varchar(255) NOT NULL COMMENT '所有分类图标',
  `rec_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '直接推荐奖励金额',
  `apply_teacher` varchar(255) DEFAULT NULL COMMENT '申请讲师入驻审核通知',
  `viporder_commission` text COMMENT 'VIP订单佣金比例(如果该值不设定，则使用全局分销佣金比例)',
  `index_lazyload` text COMMENT '首页延迟加载',
  `cash_follow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '提现是否需要关注公众号',
  `front_color` text COMMENT '前台界面颜色',
  `self_diy` text COMMENT '个人中心自定义栏目',
  `stock_config` tinyint(1) DEFAULT '0' COMMENT '是否启用库存 0.否 1.是',
  `is_invoice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开具发票 0.不支持 1.支持',
  `index_upgrade` tinyint(1) NOT NULL DEFAULT '0' COMMENT '索引升级',
  `poster_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '推广海报样式 1.直接进入微课堂  2.直接进入公众号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='基本设置';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_son` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `parentid` int(11) NOT NULL COMMENT '课程关联id',
  `title` varchar(255) NOT NULL COMMENT '章节名称',
  `sectiontype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '章节类型 1.视频章节 2.图文章节',
  `savetype` tinyint(1) NOT NULL COMMENT '存储方式 0.其他存储方式 1.七牛存储 2.内嵌播放代码模式',
  `videourl` text COMMENT '章节视频url',
  `videotime` varchar(100) NOT NULL COMMENT '视频时长',
  `content` text COMMENT '章节内容',
  `displayorder` int(4) NOT NULL DEFAULT '0',
  `is_free` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为试听章节 0.否 1.是',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0.隐藏 1.显示',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='课程章节内容';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_syslog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `admin_uid` int(11) DEFAULT NULL COMMENT '管理员id',
  `admin_username` varchar(50) DEFAULT NULL COMMENT '管理员昵称',
  `log_type` tinyint(1) DEFAULT NULL COMMENT '操作类型 1.增加 2.删除 3更新',
  `function` varchar(100) DEFAULT NULL COMMENT '操作的功能',
  `content` varchar(1000) DEFAULT NULL COMMENT '操作描述',
  `ip` varchar(50) DEFAULT NULL COMMENT '操作IP地址',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_admin_uid` (`admin_uid`),
  KEY `idx_log_type` (`log_type`),
  KEY `idx_function` (`function`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='操作日志表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_teacher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `openid` varchar(100) NOT NULL DEFAULT '0' COMMENT '粉丝编号',
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `teacher` varchar(100) NOT NULL COMMENT '讲师名称',
  `qq` varchar(20) DEFAULT NULL COMMENT '讲师QQ',
  `qqgroup` varchar(20) DEFAULT NULL COMMENT '讲师QQ群',
  `qqgroupLink` varchar(255) DEFAULT NULL COMMENT 'QQ群加群链接',
  `weixin_qrcode` varchar(255) NOT NULL COMMENT '讲师微信二维码',
  `first_letter` varchar(10) DEFAULT NULL COMMENT '讲师名称首字母拼音',
  `teacherdes` text COMMENT '讲师介绍',
  `teacherphoto` varchar(255) DEFAULT NULL COMMENT '讲师相片',
  `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '讲师状态 -1.审核不通过 1.正常 2.审核中',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `account` varchar(20) DEFAULT NULL COMMENT '讲师登录帐号',
  `password` varchar(32) DEFAULT NULL COMMENT '讲师登录密码',
  `upload` tinyint(1) NOT NULL DEFAULT '1' COMMENT '上传权限 0.禁止 1.允许',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_account` (`account`),
  KEY `idx_status` (`status`),
  KEY `idx_upload` (`upload`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_teacher_income` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `openid` varchar(100) DEFAULT NULL COMMENT '粉丝id',
  `teacher` varchar(255) DEFAULT NULL COMMENT '讲师名称',
  `ordersn` varchar(100) DEFAULT NULL COMMENT '订单编号',
  `bookname` varchar(255) DEFAULT NULL COMMENT '课程名称',
  `orderprice` decimal(10,2) DEFAULT '0.00' COMMENT '订单价格',
  `teacher_income` tinyint(3) DEFAULT NULL COMMENT '讲师分成',
  `income_amount` decimal(10,2) DEFAULT '0.00' COMMENT '讲师实际收入',
  `addtime` int(10) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_teacher` (`teacher`),
  KEY `idx_ordersn` (`ordersn`),
  KEY `idx_bookname` (`bookname`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='讲师收入表';
CREATE TABLE IF NOT EXISTS `ims_fy_lesson_vipcard` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `card_id` varchar(50) DEFAULT NULL COMMENT '卡号',
  `password` varchar(100) DEFAULT NULL COMMENT '服务卡密码',
  `viptime` int(11) DEFAULT NULL COMMENT '服务卡时长',
  `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未使用 1.已使用',
  `nickname` varchar(100) DEFAULT NULL COMMENT '会员昵称',
  `uid` int(11) DEFAULT NULL COMMENT '会员id',
  `openid` varchar(100) DEFAULT NULL COMMENT '粉丝编号',
  `ordersn` varchar(50) DEFAULT NULL COMMENT '使用订单编号(对应vip订单表的ordersn)',
  `use_time` int(10) DEFAULT NULL COMMENT '使用时间',
  `validity` int(10) DEFAULT NULL COMMENT '有效期',
  `addtime` int(10) unsigned DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_card_id` (`card_id`),
  KEY `idx_is_use` (`is_use`),
  KEY `idx_uid` (`uid`),
  KEY `idx_openid` (`openid`),
  KEY `idx_nickname` (`nickname`),
  KEY `idx_ordersn` (`ordersn`),
  KEY `idx_validity` (`validity`),
  KEY `idx_use_time` (`use_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('fy_lesson_article',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_article',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `uniacid` int(11) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_article',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `title` varchar(255) DEFAULT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('fy_lesson_article',  'author')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `author` varchar(100) DEFAULT NULL COMMENT '作者';");
}
if(!pdo_fieldexists('fy_lesson_article',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `content` text COMMENT '内容';");
}
if(!pdo_fieldexists('fy_lesson_article',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `isshow` tinyint(1) DEFAULT '1' COMMENT '前台展示 0.关闭 1.开启';");
}
if(!pdo_fieldexists('fy_lesson_article',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `displayorder` varchar(255) DEFAULT '0' COMMENT '排序 数值越大越靠前';");
}
if(!pdo_fieldexists('fy_lesson_article',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `addtime` int(10) DEFAULT NULL COMMENT '发布时间';");
}
if(!pdo_fieldexists('fy_lesson_article',  'view')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `view` int(11) NOT NULL DEFAULT '0' COMMENT '访问量';");
}
if(!pdo_fieldexists('fy_lesson_article',  'linkurl')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `linkurl` varchar(1000) DEFAULT NULL COMMENT '原文链接';");
}
if(!pdo_fieldexists('fy_lesson_article',  'images')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD `images` varchar(255) DEFAULT NULL COMMENT '分享图片';");
}
if(!pdo_indexexists('fy_lesson_article',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_article',  'idx_title')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD KEY `idx_title` (`title`);");
}
if(!pdo_indexexists('fy_lesson_article',  'idx_author')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD KEY `idx_author` (`author`);");
}
if(!pdo_indexexists('fy_lesson_article',  'idx_isshow')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD KEY `idx_isshow` (`isshow`);");
}
if(!pdo_indexexists('fy_lesson_article',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_article')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_blacklist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_blacklist')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_blacklist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_blacklist')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('fy_lesson_blacklist',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_blacklist')." ADD `openid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('fy_lesson_blacklist',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_blacklist')." ADD `addtime` int(10) DEFAULT NULL;");
}
if(!pdo_indexexists('fy_lesson_blacklist',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_blacklist')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_blacklist',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_blacklist')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_blacklist',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_blacklist')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'cash_type')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `cash_type` tinyint(1) NOT NULL COMMENT '提现方式 1.管理员审核 2.自动到账';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `uid` int(11) DEFAULT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `openid` varchar(255) NOT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'cash_num')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `cash_num` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.待审核 1.提现成功 -1.审核未通过';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'disposetime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `disposetime` int(10) NOT NULL DEFAULT '0' COMMENT '处理时间';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'partner_trade_no')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `partner_trade_no` varchar(255) DEFAULT NULL COMMENT '商户订单号';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'payment_no')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `payment_no` varchar(255) DEFAULT NULL COMMENT '微信订单号';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `remark` text COMMENT '管理员备注';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'lesson_type')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `lesson_type` tinyint(1) NOT NULL COMMENT '提现类型 1.分销佣金提现 2.课程收入提现';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `addtime` int(10) NOT NULL COMMENT '申请时间';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'cash_way')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `cash_way` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1.提现到余额  2.提现到微信钱包';");
}
if(!pdo_fieldexists('fy_lesson_cashlog',  'pay_account')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD `pay_account` varchar(50) DEFAULT NULL COMMENT '提现帐号';");
}
if(!pdo_indexexists('fy_lesson_cashlog',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_cashlog',  'idx_cash_type')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD KEY `idx_cash_type` (`cash_type`);");
}
if(!pdo_indexexists('fy_lesson_cashlog',  'idx_cash_way')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD KEY `idx_cash_way` (`cash_way`);");
}
if(!pdo_indexexists('fy_lesson_cashlog',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_cashlog',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_cashlog',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('fy_lesson_cashlog',  'idx_lesson_type')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD KEY `idx_lesson_type` (`lesson_type`);");
}
if(!pdo_indexexists('fy_lesson_cashlog',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_cashlog')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_category')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('fy_lesson_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('fy_lesson_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('fy_lesson_category',  'ico')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_category')." ADD `ico` varchar(255) DEFAULT NULL COMMENT '分类图标';");
}
if(!pdo_fieldexists('fy_lesson_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('fy_lesson_category',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_category')." ADD `addtime` int(10) DEFAULT NULL COMMENT '添加时间';");
}
if(!pdo_fieldexists('fy_lesson_collect',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_collect',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_collect',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD `uid` int(11) NOT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_collect',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD `openid` varchar(255) NOT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_collect',  'outid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD `outid` int(11) NOT NULL COMMENT '外部编号(课程编号或讲师编号)';");
}
if(!pdo_fieldexists('fy_lesson_collect',  'ctype')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD `ctype` tinyint(1) NOT NULL COMMENT '收藏类型 1.课程 2.讲师';");
}
if(!pdo_fieldexists('fy_lesson_collect',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD `addtime` int(10) NOT NULL COMMENT '收藏时间';");
}
if(!pdo_indexexists('fy_lesson_collect',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_collect',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_collect',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_collect',  'idx_ctype')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD KEY `idx_ctype` (`ctype`);");
}
if(!pdo_indexexists('fy_lesson_collect',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_collect')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_commission_level',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_level')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_commission_level',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_level')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_commission_level',  'levelname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_level')." ADD `levelname` varchar(50) DEFAULT NULL COMMENT '分销等级名称';");
}
if(!pdo_fieldexists('fy_lesson_commission_level',  'commission1')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_level')." ADD `commission1` decimal(10,2) DEFAULT '0.00' COMMENT '一级分销佣金比例';");
}
if(!pdo_fieldexists('fy_lesson_commission_level',  'commission2')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_level')." ADD `commission2` decimal(10,2) DEFAULT '0.00' COMMENT '二级分销佣金比例';");
}
if(!pdo_fieldexists('fy_lesson_commission_level',  'commission3')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_level')." ADD `commission3` decimal(10,2) DEFAULT '0.00' COMMENT '三级分销佣金比例';");
}
if(!pdo_fieldexists('fy_lesson_commission_level',  'updatemoney')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_level')." ADD `updatemoney` decimal(10,2) DEFAULT '0.00' COMMENT '升级条件(分销佣金满多少)';");
}
if(!pdo_indexexists('fy_lesson_commission_level',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_level')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_commission_level',  'idx_levelname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_level')." ADD KEY `idx_levelname` (`levelname`);");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `uniacid` int(11) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `orderid` varchar(255) DEFAULT NULL COMMENT '订单id';");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `uid` int(11) DEFAULT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `openid` varchar(255) DEFAULT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `nickname` varchar(100) DEFAULT NULL COMMENT '会员昵称';");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `bookname` varchar(255) DEFAULT NULL COMMENT '课程名称';");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'change_num')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `change_num` decimal(10,2) DEFAULT '0.00' COMMENT '变动数目';");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'grade')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `grade` tinyint(1) DEFAULT NULL COMMENT '佣金等级';");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `remark` varchar(255) DEFAULT NULL COMMENT '变动说明';");
}
if(!pdo_fieldexists('fy_lesson_commission_log',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD `addtime` int(10) DEFAULT NULL COMMENT '变动时间';");
}
if(!pdo_indexexists('fy_lesson_commission_log',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_commission_log',  'idx_orderid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD KEY `idx_orderid` (`orderid`);");
}
if(!pdo_indexexists('fy_lesson_commission_log',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_commission_log',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_commission_log',  'idx_nickname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD KEY `idx_nickname` (`nickname`);");
}
if(!pdo_indexexists('fy_lesson_commission_log',  'idx_bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD KEY `idx_bookname` (`bookname`);");
}
if(!pdo_indexexists('fy_lesson_commission_log',  'idx_grade')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD KEY `idx_grade` (`grade`);");
}
if(!pdo_indexexists('fy_lesson_commission_log',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_commission_log')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `card_id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'password')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `password` varchar(18) NOT NULL COMMENT '优惠码密钥';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠码面值';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'validity')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `validity` int(10) NOT NULL COMMENT '有效期';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'conditions')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `conditions` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用条件(满x元可用)';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'is_use')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未使用 1.已使用';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `nickname` varchar(50) DEFAULT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `uid` int(11) DEFAULT NULL COMMENT '会员编号';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `openid` varchar(50) DEFAULT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `ordersn` varchar(50) DEFAULT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'use_time')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `use_time` int(10) DEFAULT NULL COMMENT '使用时间';");
}
if(!pdo_fieldexists('fy_lesson_coupon',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD `addtime` int(10) NOT NULL COMMENT '添加时间';");
}
if(!pdo_indexexists('fy_lesson_coupon',  'idx_ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD UNIQUE KEY `idx_ordersn` (`ordersn`);");
}
if(!pdo_indexexists('fy_lesson_coupon',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_coupon',  'idx_password')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD KEY `idx_password` (`password`);");
}
if(!pdo_indexexists('fy_lesson_coupon',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_coupon',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_coupon',  'idx_validity')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD KEY `idx_validity` (`validity`);");
}
if(!pdo_indexexists('fy_lesson_coupon',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_coupon')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `orderid` int(11) NOT NULL COMMENT '订单id';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `ordersn` varchar(255) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'lessonid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `lessonid` int(11) NOT NULL COMMENT '课程id';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `bookname` varchar(255) NOT NULL COMMENT '课程名称';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `openid` varchar(255) NOT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `uid` int(11) NOT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `nickname` varchar(50) NOT NULL COMMENT '会员昵称';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'grade')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `grade` tinyint(1) NOT NULL COMMENT '评价 1.好评 2.中评 3.差评';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `content` text NOT NULL COMMENT '评价内容';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `addtime` int(10) NOT NULL COMMENT '评价时间';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'reply')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `reply` text COMMENT '评价回复';");
}
if(!pdo_fieldexists('fy_lesson_evaluate',  'teacherid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(与fy_lesson_teacher表的id字段对应)';");
}
if(!pdo_indexexists('fy_lesson_evaluate',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_evaluate',  'idx_orderid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD KEY `idx_orderid` (`orderid`);");
}
if(!pdo_indexexists('fy_lesson_evaluate',  'idx_ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD KEY `idx_ordersn` (`ordersn`);");
}
if(!pdo_indexexists('fy_lesson_evaluate',  'idx_lessonid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD KEY `idx_lessonid` (`lessonid`);");
}
if(!pdo_indexexists('fy_lesson_evaluate',  'idx_bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD KEY `idx_bookname` (`bookname`);");
}
if(!pdo_indexexists('fy_lesson_evaluate',  'idx_teacherid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD KEY `idx_teacherid` (`teacherid`);");
}
if(!pdo_indexexists('fy_lesson_evaluate',  'idx_grade')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_evaluate')." ADD KEY `idx_grade` (`grade`);");
}
if(!pdo_fieldexists('fy_lesson_history',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_history',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_history',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD `uid` int(11) NOT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_history',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD `openid` varchar(255) NOT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_history',  'lessonid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD `lessonid` int(11) NOT NULL COMMENT '课程id';");
}
if(!pdo_fieldexists('fy_lesson_history',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD `addtime` int(10) NOT NULL COMMENT '最后进入时间';");
}
if(!pdo_indexexists('fy_lesson_history',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_history',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_history',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_history',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_history')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_member',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `uid` int(11) NOT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `openid` varchar(255) NOT NULL COMMENT '粉丝标识';");
}
if(!pdo_fieldexists('fy_lesson_member',  'studentno')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `studentno` varchar(20) DEFAULT NULL COMMENT '学号';");
}
if(!pdo_fieldexists('fy_lesson_member',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `nickname` varchar(100) DEFAULT NULL COMMENT '会员昵称';");
}
if(!pdo_fieldexists('fy_lesson_member',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `parentid` int(11) NOT NULL DEFAULT '0' COMMENT '推荐人id';");
}
if(!pdo_fieldexists('fy_lesson_member',  'nopay_commission')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `nopay_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '未结算佣金';");
}
if(!pdo_fieldexists('fy_lesson_member',  'pay_commission')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `pay_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已结算佣金';");
}
if(!pdo_fieldexists('fy_lesson_member',  'nopay_lesson')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `nopay_lesson` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '未提现课程收入';");
}
if(!pdo_fieldexists('fy_lesson_member',  'pay_lesson')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `pay_lesson` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已提现课程收入';");
}
if(!pdo_fieldexists('fy_lesson_member',  'vip')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `vip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否vip 0.否 1.是';");
}
if(!pdo_fieldexists('fy_lesson_member',  'validity')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `validity` bigint(11) NOT NULL DEFAULT '0' COMMENT 'vip有效期';");
}
if(!pdo_fieldexists('fy_lesson_member',  'pastnotice')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `pastnotice` int(10) NOT NULL DEFAULT '0' COMMENT 'vip服务过期前最新通知时间';");
}
if(!pdo_fieldexists('fy_lesson_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销状态 0.关闭 1.开启';");
}
if(!pdo_fieldexists('fy_lesson_member',  'uptime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `uptime` int(10) NOT NULL COMMENT '更新时间';");
}
if(!pdo_fieldexists('fy_lesson_member',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `addtime` int(11) NOT NULL COMMENT '添加时间';");
}
if(!pdo_fieldexists('fy_lesson_member',  'agent_level')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD `agent_level` int(11) NOT NULL DEFAULT '0' COMMENT '分销代理级别';");
}
if(!pdo_indexexists('fy_lesson_member',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_member',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_member',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_member',  'idx_parentid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD KEY `idx_parentid` (`parentid`);");
}
if(!pdo_indexexists('fy_lesson_member',  'idx_vip')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD KEY `idx_vip` (`vip`);");
}
if(!pdo_indexexists('fy_lesson_member',  'idx_validity')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD KEY `idx_validity` (`validity`);");
}
if(!pdo_indexexists('fy_lesson_member',  'idx_pastnotice')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD KEY `idx_pastnotice` (`pastnotice`);");
}
if(!pdo_indexexists('fy_lesson_member',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('fy_lesson_member',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `acid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `ordersn` varchar(255) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `uid` int(11) NOT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `openid` varchar(255) NOT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'viptime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `viptime` int(4) NOT NULL COMMENT '会员服务时间';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'vipmoney')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `vipmoney` decimal(10,2) NOT NULL COMMENT '会员服务价格';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `paytype` varchar(50) NOT NULL COMMENT '支付方式';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 0.未支付 1.已支付';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `paytime` int(10) DEFAULT '0' COMMENT '订单支付时间';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `addtime` int(10) NOT NULL COMMENT '订单添加时间';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'member1')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `member1` int(11) NOT NULL COMMENT '一级代理会员id';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'commission1')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `commission1` decimal(10,2) NOT NULL COMMENT '一级代理佣金';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'member2')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `member2` int(11) NOT NULL COMMENT '二级代理会员id';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'commission2')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `commission2` decimal(10,2) NOT NULL COMMENT '二级代理佣金';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'member3')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `member3` int(11) NOT NULL COMMENT '三级代理会员id';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'commission3')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `commission3` decimal(10,2) NOT NULL COMMENT '三级代理佣金';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'update_time')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `update_time` int(10) DEFAULT NULL COMMENT '更新时间';");
}
if(!pdo_fieldexists('fy_lesson_member_order',  'refer_id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD `refer_id` int(11) DEFAULT NULL COMMENT '充值卡id(与vip卡的id对应)';");
}
if(!pdo_indexexists('fy_lesson_member_order',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_member_order',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_member_order',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_member_order',  'idx_paytype')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD KEY `idx_paytype` (`paytype`);");
}
if(!pdo_indexexists('fy_lesson_member_order',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('fy_lesson_member_order',  'idx_refer_id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD KEY `idx_refer_id` (`refer_id`);");
}
if(!pdo_indexexists('fy_lesson_member_order',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_member_order')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_order',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `acid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('fy_lesson_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `ordersn` varchar(255) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('fy_lesson_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `uid` int(11) NOT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `openid` varchar(255) NOT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_order',  'lessonid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `lessonid` int(11) NOT NULL COMMENT '课程id';");
}
if(!pdo_fieldexists('fy_lesson_order',  'bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `bookname` varchar(255) NOT NULL COMMENT '课程名称';");
}
if(!pdo_fieldexists('fy_lesson_order',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价';");
}
if(!pdo_fieldexists('fy_lesson_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '课程价格';");
}
if(!pdo_fieldexists('fy_lesson_order',  'teacher_income')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `teacher_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '讲师收入(课程价格分成%)';");
}
if(!pdo_fieldexists('fy_lesson_order',  'integral')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `integral` int(4) NOT NULL DEFAULT '0' COMMENT '赠送积分';");
}
if(!pdo_fieldexists('fy_lesson_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `paytype` varchar(50) NOT NULL DEFAULT '0' COMMENT '支付方式';");
}
if(!pdo_fieldexists('fy_lesson_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `paytime` int(10) NOT NULL DEFAULT '0' COMMENT '支付时间';");
}
if(!pdo_fieldexists('fy_lesson_order',  'member1')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `member1` int(11) NOT NULL DEFAULT '0' COMMENT '一级代理会员id';");
}
if(!pdo_fieldexists('fy_lesson_order',  'commission1')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `commission1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '一级佣金';");
}
if(!pdo_fieldexists('fy_lesson_order',  'member2')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `member2` int(11) NOT NULL DEFAULT '0' COMMENT '二级代理会员id';");
}
if(!pdo_fieldexists('fy_lesson_order',  'commission2')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `commission2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '二级佣金';");
}
if(!pdo_fieldexists('fy_lesson_order',  'member3')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `member3` int(11) NOT NULL DEFAULT '0' COMMENT '三级代理会员id';");
}
if(!pdo_fieldexists('fy_lesson_order',  'commission3')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `commission3` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '三级佣金';");
}
if(!pdo_fieldexists('fy_lesson_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态 -1.已取消 0.未支付 1.已支付 2.已评价';");
}
if(!pdo_fieldexists('fy_lesson_order',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `addtime` int(10) DEFAULT NULL COMMENT '下单时间';");
}
if(!pdo_fieldexists('fy_lesson_order',  'teacherid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `teacherid` int(11) DEFAULT NULL COMMENT '讲师id(与fy_lesson_teacher表的id字段对应)';");
}
if(!pdo_fieldexists('fy_lesson_order',  'invoice')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `invoice` varchar(100) DEFAULT NULL COMMENT '发票抬头';");
}
if(!pdo_fieldexists('fy_lesson_order',  'coupon')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `coupon` varchar(50) DEFAULT NULL COMMENT '课程优惠码';");
}
if(!pdo_fieldexists('fy_lesson_order',  'coupon_amount')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `coupon_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠码面值';");
}
if(!pdo_fieldexists('fy_lesson_order',  'validity')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD `validity` int(11) NOT NULL DEFAULT '0' COMMENT '有效期 在有效期内可观看学习课程';");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_acid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_acid` (`acid`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_ordersn` (`ordersn`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_lessonid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_lessonid` (`lessonid`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_bookname` (`bookname`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_teacherid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_teacherid` (`teacherid`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_paytype')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_paytype` (`paytype`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('fy_lesson_order',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_order')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_parent',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_parent',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `cid` int(11) NOT NULL COMMENT '分类ID';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `bookname` varchar(255) NOT NULL COMMENT '课程名称';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'price')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '课程价格';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'isdiscount')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `isdiscount` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启该课程折扣';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'vipdiscount')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `vipdiscount` int(3) NOT NULL DEFAULT '0' COMMENT 'vip会员折扣';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'integral')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `integral` int(11) NOT NULL DEFAULT '0' COMMENT '购买赠送积分';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'images')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `images` varchar(255) DEFAULT NULL COMMENT '课程封图';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'descript')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `descript` text COMMENT '课程介绍';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'difficulty')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `difficulty` varchar(100) DEFAULT NULL COMMENT '课程难度';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'buynum')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `buynum` int(11) NOT NULL DEFAULT '0' COMMENT '正常购买人数';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'virtual_buynum')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `virtual_buynum` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟购买人数';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'score')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `score` decimal(5,2) NOT NULL COMMENT '课程好评率';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'teacherid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `teacherid` int(11) NOT NULL COMMENT '主讲老师id';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'commission')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `commission` text COMMENT '佣金比例';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `displayorder` int(4) NOT NULL DEFAULT '0' COMMENT '课程排序';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `status` tinyint(1) NOT NULL COMMENT '是否上架';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'recommendid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `recommendid` varchar(255) DEFAULT NULL COMMENT '推荐板块id';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'vipview')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `vipview` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'vip权限是否可免费观看';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'teacher_income')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `teacher_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '讲师分成%';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `addtime` int(10) NOT NULL COMMENT '添加时间';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'stock')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `stock` int(11) NOT NULL COMMENT '课程库存';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'poster')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `poster` text COMMENT '视频播放封面图';");
}
if(!pdo_fieldexists('fy_lesson_parent',  'validity')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD `validity` int(11) NOT NULL DEFAULT '0' COMMENT '有效期 即购买时起多少天内有效';");
}
if(!pdo_indexexists('fy_lesson_parent',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_parent',  'idx_cid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD KEY `idx_cid` (`cid`);");
}
if(!pdo_indexexists('fy_lesson_parent',  'idx_bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD KEY `idx_bookname` (`bookname`);");
}
if(!pdo_indexexists('fy_lesson_parent',  'idx_teacherid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD KEY `idx_teacherid` (`teacherid`);");
}
if(!pdo_indexexists('fy_lesson_parent',  'idx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD KEY `idx_displayorder` (`displayorder`);");
}
if(!pdo_indexexists('fy_lesson_parent',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('fy_lesson_parent',  'idx_recommendid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD KEY `idx_recommendid` (`recommendid`);");
}
if(!pdo_indexexists('fy_lesson_parent',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_parent')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_playrecord',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_playrecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD `uniacid` int(11) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_playrecord',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD `uid` int(11) DEFAULT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_playrecord',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD `openid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_playrecord',  'lessonid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD `lessonid` int(11) DEFAULT NULL COMMENT '课程id';");
}
if(!pdo_fieldexists('fy_lesson_playrecord',  'sectionid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD `sectionid` int(11) DEFAULT NULL COMMENT '章节id';");
}
if(!pdo_fieldexists('fy_lesson_playrecord',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD `addtime` int(10) DEFAULT NULL COMMENT '更新时间';");
}
if(!pdo_indexexists('fy_lesson_playrecord',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_playrecord',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_playrecord',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_playrecord',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_playrecord')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `uid` int(11) DEFAULT NULL COMMENT '会员编号';");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `openid` varchar(255) DEFAULT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'teacher')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `teacher` int(11) DEFAULT NULL COMMENT '讲师编号';");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'name')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `name` varchar(500) DEFAULT NULL COMMENT '文件名';");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'com_name')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `com_name` varchar(1000) DEFAULT NULL COMMENT '完成文件名';");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'qiniu_url')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `qiniu_url` varchar(1000) DEFAULT NULL COMMENT '文件链接';");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'size')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `size` varchar(100) DEFAULT NULL COMMENT '文件大小';");
}
if(!pdo_fieldexists('fy_lesson_qiniu_upload',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD `addtime` int(10) DEFAULT NULL COMMENT '添加时间';");
}
if(!pdo_indexexists('fy_lesson_qiniu_upload',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_qiniu_upload',  'idx_teacher')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD KEY `idx_teacher` (`teacher`);");
}
if(!pdo_indexexists('fy_lesson_qiniu_upload',  'idx_name')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_qiniu_upload')." ADD KEY `idx_name` (`name`(333));");
}
if(!pdo_fieldexists('fy_lesson_recommend',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_recommend')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_recommend',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_recommend')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_recommend',  'rec_name')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_recommend')." ADD `rec_name` varchar(255) DEFAULT NULL COMMENT '模块名称';");
}
if(!pdo_fieldexists('fy_lesson_recommend',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_recommend')." ADD `displayorder` int(4) DEFAULT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('fy_lesson_recommend',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_recommend')." ADD `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示';");
}
if(!pdo_fieldexists('fy_lesson_recommend',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_recommend')." ADD `addtime` int(10) NOT NULL COMMENT '添加时间';");
}
if(!pdo_indexexists('fy_lesson_recommend',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_recommend')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_recommend',  'idx_is_show')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_recommend')." ADD KEY `idx_is_show` (`is_show`);");
}
if(!pdo_fieldexists('fy_lesson_relation',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_relation')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_relation',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_relation')." ADD `uniacid` int(11) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_relation',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_relation')." ADD `uid` int(11) DEFAULT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_relation',  'tjgx')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_relation')." ADD `tjgx` text COMMENT '推荐关系';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_setting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `logo` varchar(255) NOT NULL COMMENT 'app端logo';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'istplnotice')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `istplnotice` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启模版消息 0.关闭 1.开启';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'buysucc')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `buysucc` varchar(255) DEFAULT NULL COMMENT '用户购买课程通知';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'pastvip')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `pastvip` varchar(255) DEFAULT NULL COMMENT '用户会员服务过期';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'cnotice')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `cnotice` varchar(255) DEFAULT NULL COMMENT '佣金提醒';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'newjoin')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `newjoin` varchar(255) DEFAULT NULL COMMENT '三级分销下级加入提醒';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'newlesson')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `newlesson` varchar(255) DEFAULT NULL COMMENT '新开课提醒学员';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'neworder')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `neworder` varchar(255) NOT NULL COMMENT '订单通知(管理员)';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'manageopenid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `manageopenid` text NOT NULL COMMENT '新订单提醒(管理员)';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'sitename')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `sitename` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('fy_lesson_setting',  'banner')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `banner` text COMMENT '焦点图';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `copyright` varchar(255) NOT NULL COMMENT '版权';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'vipserver')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `vipserver` text COMMENT 'vip时长和价格';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'sharelink')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `sharelink` text COMMENT '链接分享';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'sharelesson')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `sharelesson` text COMMENT '分享课程';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'shareteacher')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `shareteacher` text COMMENT '分享讲师';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'closespace')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `closespace` int(4) NOT NULL DEFAULT '60' COMMENT '关闭未付款订单时间间隔';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'closelast')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `closelast` int(10) NOT NULL DEFAULT '0' COMMENT '上次执行关闭未付款订单时间';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'is_sale')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `is_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销功能 0.关闭 1.开启';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'self_sale')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `self_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分销内购 0.关闭 1.开启';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'sale_rank')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `sale_rank` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分销身份 1.任何人 2.VIP身份';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'level')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `level` tinyint(1) NOT NULL DEFAULT '3' COMMENT '分销等级';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'commission')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `commission` text COMMENT '佣金比例';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'cash_type')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `cash_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '提现方式 1.管理员审核 2.自动到账';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'cash_lower')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `cash_lower` decimal(10,2) NOT NULL DEFAULT '1.00' COMMENT '提现最低下限 默认为1元';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'mchid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `mchid` varchar(100) DEFAULT NULL COMMENT '微信支付商户号';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'mchkey')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `mchkey` varchar(255) DEFAULT NULL COMMENT '微信支付商户支付密钥';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'serverIp')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `serverIp` varchar(100) DEFAULT NULL COMMENT '服务器Ip';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'savetype')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `savetype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0.其他存储方式 1.七牛云存储 2.腾讯云存储';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'qiniu')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `qiniu` text COMMENT '七牛云存储参数';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'qcloud')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `qcloud` text COMMENT '腾讯云存储';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'print_error')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `print_error` tinyint(1) NOT NULL DEFAULT '0' COMMENT '打印视频错误信息 0.关闭 1.开启';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'vipdiscount')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `vipdiscount` int(3) NOT NULL DEFAULT '0' COMMENT 'vip会员购买课程折扣';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'footnav')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `footnav` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单显示方式 0.底部菜单 1.悬浮菜单';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'lessonshow')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `lessonshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '首页课程显示 1.一行一个课程 2.一行两个课程';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'teacher_income')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `teacher_income` tinyint(3) NOT NULL DEFAULT '0' COMMENT '讲师收入(课程价格分成%)';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'isfollow')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `isfollow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '强制关注公众号 0.不强制 1.强制';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `qrcode` varchar(255) DEFAULT NULL COMMENT '公众号二维码';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'mustinfo')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `mustinfo` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'autogood')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `autogood` int(4) NOT NULL DEFAULT '0' COMMENT '超时自动好评 默认0为关闭';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'posterbg')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `posterbg` varchar(255) DEFAULT NULL COMMENT '推广海报背景图';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `addtime` int(10) NOT NULL COMMENT '添加时间';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'vipdesc')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `vipdesc` text NOT NULL COMMENT 'vip服务描述';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'vip_sale')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `vip_sale` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'VIP订单分销开关';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'cash_way')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `cash_way` text NOT NULL COMMENT '提现方式';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'adv')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `adv` text NOT NULL COMMENT '课程详情页广告';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'newcash')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `newcash` varchar(255) NOT NULL COMMENT '提现申请通知(管理员)';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'mobilechange')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `mobilechange` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启修改手机链接 0.关闭 1.开启';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'main_color')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `main_color` varchar(50) DEFAULT NULL COMMENT '前台主色调';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'minor_color')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `minor_color` varchar(50) DEFAULT NULL COMMENT '前台副色调';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'teacherlist')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `teacherlist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示讲师列表 0.不显示  1.显示';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'category_ico')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `category_ico` varchar(255) NOT NULL COMMENT '所有分类图标';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'rec_income')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `rec_income` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '直接推荐奖励金额';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'apply_teacher')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `apply_teacher` varchar(255) DEFAULT NULL COMMENT '申请讲师入驻审核通知';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'viporder_commission')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `viporder_commission` text COMMENT 'VIP订单佣金比例(如果该值不设定，则使用全局分销佣金比例)';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'index_lazyload')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `index_lazyload` text COMMENT '首页延迟加载';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'cash_follow')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `cash_follow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '提现是否需要关注公众号';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'front_color')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `front_color` text COMMENT '前台界面颜色';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'self_diy')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `self_diy` text COMMENT '个人中心自定义栏目';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'stock_config')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `stock_config` tinyint(1) DEFAULT '0' COMMENT '是否启用库存 0.否 1.是';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'is_invoice')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `is_invoice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开具发票 0.不支持 1.支持';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'index_upgrade')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `index_upgrade` tinyint(1) NOT NULL DEFAULT '0' COMMENT '索引升级';");
}
if(!pdo_fieldexists('fy_lesson_setting',  'poster_type')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD `poster_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '推广海报样式 1.直接进入微课堂  2.直接进入公众号';");
}
if(!pdo_indexexists('fy_lesson_setting',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_setting')." ADD UNIQUE KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('fy_lesson_son',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_son',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_son',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `parentid` int(11) NOT NULL COMMENT '课程关联id';");
}
if(!pdo_fieldexists('fy_lesson_son',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `title` varchar(255) NOT NULL COMMENT '章节名称';");
}
if(!pdo_fieldexists('fy_lesson_son',  'sectiontype')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `sectiontype` tinyint(1) NOT NULL DEFAULT '1' COMMENT '章节类型 1.视频章节 2.图文章节';");
}
if(!pdo_fieldexists('fy_lesson_son',  'savetype')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `savetype` tinyint(1) NOT NULL COMMENT '存储方式 0.其他存储方式 1.七牛存储 2.内嵌播放代码模式';");
}
if(!pdo_fieldexists('fy_lesson_son',  'videourl')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `videourl` text COMMENT '章节视频url';");
}
if(!pdo_fieldexists('fy_lesson_son',  'videotime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `videotime` varchar(100) NOT NULL COMMENT '视频时长';");
}
if(!pdo_fieldexists('fy_lesson_son',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `content` text COMMENT '章节内容';");
}
if(!pdo_fieldexists('fy_lesson_son',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `displayorder` int(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fy_lesson_son',  'is_free')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `is_free` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为试听章节 0.否 1.是';");
}
if(!pdo_fieldexists('fy_lesson_son',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示 0.隐藏 1.显示';");
}
if(!pdo_fieldexists('fy_lesson_son',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD `addtime` int(10) NOT NULL COMMENT '添加时间';");
}
if(!pdo_indexexists('fy_lesson_son',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_son',  'idx_parentid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD KEY `idx_parentid` (`parentid`);");
}
if(!pdo_indexexists('fy_lesson_son',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_son')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_fieldexists('fy_lesson_syslog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_syslog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD `uniacid` int(11) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_syslog',  'admin_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD `admin_uid` int(11) DEFAULT NULL COMMENT '管理员id';");
}
if(!pdo_fieldexists('fy_lesson_syslog',  'admin_username')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD `admin_username` varchar(50) DEFAULT NULL COMMENT '管理员昵称';");
}
if(!pdo_fieldexists('fy_lesson_syslog',  'log_type')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD `log_type` tinyint(1) DEFAULT NULL COMMENT '操作类型 1.增加 2.删除 3更新';");
}
if(!pdo_fieldexists('fy_lesson_syslog',  'function')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD `function` varchar(100) DEFAULT NULL COMMENT '操作的功能';");
}
if(!pdo_fieldexists('fy_lesson_syslog',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD `content` varchar(1000) DEFAULT NULL COMMENT '操作描述';");
}
if(!pdo_fieldexists('fy_lesson_syslog',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD `ip` varchar(50) DEFAULT NULL COMMENT '操作IP地址';");
}
if(!pdo_fieldexists('fy_lesson_syslog',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD `addtime` int(10) NOT NULL COMMENT '添加时间';");
}
if(!pdo_indexexists('fy_lesson_syslog',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_syslog',  'idx_admin_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD KEY `idx_admin_uid` (`admin_uid`);");
}
if(!pdo_indexexists('fy_lesson_syslog',  'idx_log_type')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD KEY `idx_log_type` (`log_type`);");
}
if(!pdo_indexexists('fy_lesson_syslog',  'idx_function')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD KEY `idx_function` (`function`);");
}
if(!pdo_indexexists('fy_lesson_syslog',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_syslog')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `openid` varchar(100) NOT NULL DEFAULT '0' COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'teacher')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `teacher` varchar(100) NOT NULL COMMENT '讲师名称';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `qq` varchar(20) DEFAULT NULL COMMENT '讲师QQ';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'qqgroup')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `qqgroup` varchar(20) DEFAULT NULL COMMENT '讲师QQ群';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'qqgroupLink')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `qqgroupLink` varchar(255) DEFAULT NULL COMMENT 'QQ群加群链接';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'weixin_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `weixin_qrcode` varchar(255) NOT NULL COMMENT '讲师微信二维码';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'first_letter')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `first_letter` varchar(10) DEFAULT NULL COMMENT '讲师名称首字母拼音';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'teacherdes')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `teacherdes` text COMMENT '讲师介绍';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'teacherphoto')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `teacherphoto` varchar(255) DEFAULT NULL COMMENT '讲师相片';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '讲师状态 -1.审核不通过 1.正常 2.审核中';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `addtime` int(11) NOT NULL COMMENT '添加时间';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'account')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `account` varchar(20) DEFAULT NULL COMMENT '讲师登录帐号';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'password')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `password` varchar(32) DEFAULT NULL COMMENT '讲师登录密码';");
}
if(!pdo_fieldexists('fy_lesson_teacher',  'upload')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD `upload` tinyint(1) NOT NULL DEFAULT '1' COMMENT '上传权限 0.禁止 1.允许';");
}
if(!pdo_indexexists('fy_lesson_teacher',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_teacher',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_teacher',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_teacher',  'idx_account')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD KEY `idx_account` (`account`);");
}
if(!pdo_indexexists('fy_lesson_teacher',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_indexexists('fy_lesson_teacher',  'idx_upload')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher')." ADD KEY `idx_upload` (`upload`);");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `uniacid` int(11) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `uid` int(11) DEFAULT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `openid` varchar(100) DEFAULT NULL COMMENT '粉丝id';");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'teacher')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `teacher` varchar(255) DEFAULT NULL COMMENT '讲师名称';");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `ordersn` varchar(100) DEFAULT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `bookname` varchar(255) DEFAULT NULL COMMENT '课程名称';");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'orderprice')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `orderprice` decimal(10,2) DEFAULT '0.00' COMMENT '订单价格';");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'teacher_income')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `teacher_income` tinyint(3) DEFAULT NULL COMMENT '讲师分成';");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'income_amount')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `income_amount` decimal(10,2) DEFAULT '0.00' COMMENT '讲师实际收入';");
}
if(!pdo_fieldexists('fy_lesson_teacher_income',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD `addtime` int(10) DEFAULT NULL COMMENT '添加时间';");
}
if(!pdo_indexexists('fy_lesson_teacher_income',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_teacher_income',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_teacher_income',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_teacher_income',  'idx_teacher')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD KEY `idx_teacher` (`teacher`);");
}
if(!pdo_indexexists('fy_lesson_teacher_income',  'idx_ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD KEY `idx_ordersn` (`ordersn`);");
}
if(!pdo_indexexists('fy_lesson_teacher_income',  'idx_bookname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD KEY `idx_bookname` (`bookname`);");
}
if(!pdo_indexexists('fy_lesson_teacher_income',  'idx_addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_teacher_income')." ADD KEY `idx_addtime` (`addtime`);");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `card_id` varchar(50) DEFAULT NULL COMMENT '卡号';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'password')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `password` varchar(100) DEFAULT NULL COMMENT '服务卡密码';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'viptime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `viptime` int(11) DEFAULT NULL COMMENT '服务卡时长';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'is_use')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `is_use` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0.未使用 1.已使用';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `nickname` varchar(100) DEFAULT NULL COMMENT '会员昵称';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `uid` int(11) DEFAULT NULL COMMENT '会员id';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `openid` varchar(100) DEFAULT NULL COMMENT '粉丝编号';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `ordersn` varchar(50) DEFAULT NULL COMMENT '使用订单编号(对应vip订单表的ordersn)';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'use_time')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `use_time` int(10) DEFAULT NULL COMMENT '使用时间';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'validity')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `validity` int(10) DEFAULT NULL COMMENT '有效期';");
}
if(!pdo_fieldexists('fy_lesson_vipcard',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD `addtime` int(10) unsigned DEFAULT NULL COMMENT '添加时间';");
}
if(!pdo_indexexists('fy_lesson_vipcard',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fy_lesson_vipcard',  'idx_card_id')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD KEY `idx_card_id` (`card_id`);");
}
if(!pdo_indexexists('fy_lesson_vipcard',  'idx_is_use')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD KEY `idx_is_use` (`is_use`);");
}
if(!pdo_indexexists('fy_lesson_vipcard',  'idx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD KEY `idx_uid` (`uid`);");
}
if(!pdo_indexexists('fy_lesson_vipcard',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_indexexists('fy_lesson_vipcard',  'idx_nickname')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD KEY `idx_nickname` (`nickname`);");
}
if(!pdo_indexexists('fy_lesson_vipcard',  'idx_ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD KEY `idx_ordersn` (`ordersn`);");
}
if(!pdo_indexexists('fy_lesson_vipcard',  'idx_validity')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD KEY `idx_validity` (`validity`);");
}
if(!pdo_indexexists('fy_lesson_vipcard',  'idx_use_time')) {
	pdo_query("ALTER TABLE ".tablename('fy_lesson_vipcard')." ADD KEY `idx_use_time` (`use_time`);");
}

?>