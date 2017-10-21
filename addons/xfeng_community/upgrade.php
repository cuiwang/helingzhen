<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_xcommunity_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `regionid` text NOT NULL,
  `starttime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `enddate` varchar(30) NOT NULL,
  `picurl` varchar(1000) NOT NULL,
  `number` int(11) NOT NULL DEFAULT '1',
  `content` varchar(2000) NOT NULL,
  `status` int(1) NOT NULL COMMENT '1置顶',
  `createtime` int(11) unsigned NOT NULL,
  `resnumber` int(11) unsigned NOT NULL COMMENT '报名人数',
  `price` decimal(12,2) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_admap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `regionid` int(11) NOT NULL,
  `adid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_alipayment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL COMMENT '公众号ID',
  `pid` int(11) NOT NULL,
  `account` varchar(50) NOT NULL COMMENT '支付宝账号',
  `partner` varchar(50) NOT NULL COMMENT '合作者身份',
  `secret` varchar(50) NOT NULL COMMENT '校验密钥',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='独立支付宝配置';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_announcement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `regionid` text,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `author` varchar(50) NOT NULL COMMENT '作者',
  `createtime` int(10) unsigned NOT NULL,
  `starttime` int(11) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(11) unsigned NOT NULL COMMENT '结束时间',
  `status` tinyint(1) NOT NULL COMMENT '状态 1禁用，2启用',
  `enable` tinyint(1) NOT NULL COMMENT '模板类型',
  `datetime` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL COMMENT '通知范围',
  `reason` text,
  `remark` varchar(100) NOT NULL COMMENT '通知备注',
  `pid` int(10) unsigned NOT NULL COMMENT '物业ID',
  `tit` varchar(255) NOT NULL COMMENT '标题',
  `time` varchar(100) NOT NULL COMMENT '门禁卡失效时间',
  `scope` varchar(100) NOT NULL COMMENT '门禁卡失效范围',
  `method` varchar(300) NOT NULL COMMENT '门禁卡重新激活办法',
  `uid` int(11) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='发布公告';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_building_device` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `regionid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '楼宇名称',
  `unit` varchar(30) NOT NULL COMMENT '单元号',
  `api_key` varchar(100) NOT NULL COMMENT '设备apikey',
  `device_code` int(11) NOT NULL COMMENT '设备编号',
  `lock_code` varchar(11) NOT NULL COMMENT '锁编号',
  `type` int(1) NOT NULL COMMENT '1单元门，2大门',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_business` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `qq` int(11) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` int(1) unsigned NOT NULL COMMENT '0未审核，1审核',
  `balance` varchar(100) DEFAULT NULL,
  `commission` float DEFAULT NULL,
  `alipay` varchar(100) NOT NULL COMMENT '支付宝账户',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_carpool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `seat` int(2) unsigned NOT NULL COMMENT '座位',
  `sprice` int(10) unsigned NOT NULL COMMENT '价格',
  `month` int(2) unsigned NOT NULL,
  `yday` int(2) unsigned NOT NULL,
  `contact` varchar(50) NOT NULL COMMENT '联系人',
  `mobile` varchar(13) NOT NULL COMMENT '电话',
  `openid` varchar(50) NOT NULL,
  `start_position` varchar(100) NOT NULL COMMENT '出发地',
  `end_position` varchar(100) NOT NULL COMMENT '目的地',
  `startMinute` int(10) unsigned NOT NULL,
  `startSeconds` int(10) unsigned NOT NULL,
  `license_number` varchar(100) NOT NULL,
  `car_model` varchar(100) NOT NULL,
  `car_brand` varchar(100) NOT NULL,
  `content` varchar(300) NOT NULL,
  `enable` int(1) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `gotime` varchar(10) NOT NULL COMMENT '出发时间',
  `backtime` varchar(10) NOT NULL COMMENT '返回时间',
  `createtime` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL COMMENT '所属小区',
  `type` int(11) NOT NULL COMMENT '类型1司机，2乘客',
  `thumb` varchar(2000) NOT NULL COMMENT '图片',
  `black` int(1) NOT NULL COMMENT '1设置黑名单',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购物车表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_cash_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户ID',
  `cash` decimal(10,2) NOT NULL,
  `status` int(1) NOT NULL,
  `createtime` int(11) NOT NULL,
  `ordersn` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='提现订单';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `parentid` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL COMMENT '服务项目',
  `description` text NOT NULL COMMENT '分类描述',
  `thumb` varchar(100) NOT NULL COMMENT '分类图片',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `type` int(10) unsigned NOT NULL COMMENT '1家政，2报修，3投诉，4二手，5超市，6商家',
  `price` decimal(12,2) NOT NULL COMMENT '服务价格',
  `gtime` varchar(50) NOT NULL COMMENT '服务工时',
  `regionid` int(11) DEFAULT NULL,
  `isshow` int(1) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='类型表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_cost` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `costtime` varchar(30) NOT NULL COMMENT '费用时间',
  `enable` int(1) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物业费用时间表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_cost_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL COMMENT '费用时间id',
  `mobile` varchar(13) NOT NULL,
  `username` varchar(30) NOT NULL,
  `homenumber` varchar(30) NOT NULL,
  `costtime` varchar(30) NOT NULL,
  `propertyfee` varchar(50) NOT NULL,
  `otherfee` varchar(1000) NOT NULL,
  `total` varchar(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` varchar(3) NOT NULL COMMENT '是代表缴费，否代表未缴费',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `fee` varchar(500) DEFAULT NULL,
  `area` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='物业费表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_dp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `regionid` text NOT NULL,
  `sjname` varchar(30) NOT NULL,
  `picurl` varchar(1000) NOT NULL,
  `contactname` varchar(30) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `qq` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  `address` varchar(150) NOT NULL,
  `shopdesc` varchar(500) NOT NULL,
  `businnesstime` varchar(20) NOT NULL,
  `businessurl` varchar(100) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `lat` varchar(20) NOT NULL,
  `lng` varchar(20) NOT NULL,
  `businesstime` varchar(50) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `child` int(11) DEFAULT NULL,
  `rid` int(11) DEFAULT NULL,
  `displayorder` int(11) DEFAULT NULL,
  `price` int(10) DEFAULT NULL,
  `area` varchar(50) DEFAULT NULL,
  `instruction` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_fled` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `title` varchar(20) NOT NULL,
  `rolex` varchar(30) NOT NULL,
  `category` int(11) NOT NULL,
  `yprice` int(10) DEFAULT NULL,
  `zprice` int(10) NOT NULL,
  `realname` varchar(18) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `description` varchar(100) NOT NULL,
  `regionid` int(10) NOT NULL,
  `createtime` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `images` text,
  `black` int(1) NOT NULL COMMENT '1设置黑名单',
  `enable` int(1) DEFAULT NULL,
  `cate` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `thumb_url` text,
  `unit` varchar(5) NOT NULL DEFAULT '',
  `content` text NOT NULL COMMENT '抢购详情',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售价',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `total` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `credit` int(11) DEFAULT '0',
  `isrecommand` int(11) DEFAULT '0',
  `description` text NOT NULL COMMENT '购买须知',
  `dpid` int(11) DEFAULT '0' COMMENT '商家店铺id',
  `sold` int(11) DEFAULT '0' COMMENT '已售多少份',
  `type` int(11) DEFAULT '0' COMMENT '1超市2商家',
  `uid` int(11) NOT NULL,
  `regionid` text,
  `starttime` int(11) DEFAULT NULL,
  `endtime` int(11) DEFAULT NULL,
  `businesstime` varchar(50) DEFAULT NULL,
  `parent` varchar(20) DEFAULT NULL,
  `child` int(11) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  `instruction` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_homemaking` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `category` int(11) NOT NULL COMMENT '服务类型',
  `servicetime` varchar(30) NOT NULL COMMENT '服务时间',
  `realname` varchar(30) NOT NULL COMMENT '姓名',
  `mobile` varchar(15) NOT NULL COMMENT '电话',
  `address` varchar(100) DEFAULT NULL,
  `content` varchar(500) NOT NULL COMMENT '说明',
  `status` int(10) unsigned NOT NULL COMMENT '0未完成,1已完成',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='家政服务表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_houselease` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `category` int(11) NOT NULL COMMENT '1出租，2求租，3出售，4求购',
  `way` varchar(20) NOT NULL COMMENT '出租方式',
  `model_room` int(11) NOT NULL,
  `model_hall` int(11) NOT NULL,
  `model_toilet` int(11) NOT NULL,
  `model_area` varchar(15) NOT NULL COMMENT '房屋面积',
  `floor_layer` int(11) NOT NULL,
  `floor_number` int(11) NOT NULL,
  `fitment` varchar(40) NOT NULL COMMENT '装修情况',
  `house` varchar(40) NOT NULL COMMENT '住宅类别',
  `allocation` varchar(500) NOT NULL COMMENT '房屋配置',
  `price_way` varchar(30) NOT NULL COMMENT '押金方式',
  `price` int(10) unsigned NOT NULL COMMENT '租金',
  `checktime` varchar(30) NOT NULL COMMENT '入住时间',
  `title` varchar(30) NOT NULL COMMENT '标题',
  `realname` varchar(30) NOT NULL COMMENT '姓名',
  `mobile` varchar(15) NOT NULL COMMENT '电话',
  `content` varchar(500) NOT NULL COMMENT '说明',
  `status` int(10) unsigned NOT NULL COMMENT '0未成交,1已成交',
  `createtime` int(10) unsigned NOT NULL,
  `images` text,
  `enable` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='房屋租赁表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(255) DEFAULT NULL,
  `file` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='图片表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `openid` varchar(50) DEFAULT NULL,
  `memberid` int(10) NOT NULL,
  `realname` varchar(50) NOT NULL COMMENT '真实姓名',
  `mobile` varchar(15) NOT NULL COMMENT '手机号',
  `regionname` varchar(50) NOT NULL COMMENT '小区名称',
  `address` varchar(100) NOT NULL COMMENT '楼栋门牌',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `manage_status` tinyint(1) unsigned NOT NULL COMMENT '授权管理员',
  `type` tinyint(1) unsigned NOT NULL COMMENT '业主身份',
  `build` varchar(10) DEFAULT NULL,
  `unit` int(5) DEFAULT NULL,
  `room` varchar(10) DEFAULT NULL,
  `enable` int(1) DEFAULT NULL,
  `open_status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`openid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='注册用户';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_member_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `mid` int(10) unsigned NOT NULL DEFAULT '0',
  `regionid` int(10) unsigned NOT NULL DEFAULT '0',
  `openid` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `realname` varchar(15) NOT NULL,
  `build` varchar(50) DEFAULT NULL,
  `unit` int(10) DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `enable` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `pcate` int(10) NOT NULL,
  `title` varchar(30) NOT NULL COMMENT '菜单标题',
  `url` varchar(1000) NOT NULL COMMENT '菜单链接',
  `do` varchar(20) NOT NULL COMMENT '动作',
  `method` varchar(20) DEFAULT NULL,
  `xcommunity_menu` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='后台菜单管理';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `displayorder` int(10) NOT NULL,
  `pcate` int(10) NOT NULL,
  `title` varchar(30) NOT NULL COMMENT '菜单标题',
  `url` varchar(1000) NOT NULL COMMENT '菜单链接',
  `styleid` int(10) NOT NULL COMMENT '风格id',
  `status` int(1) NOT NULL COMMENT '是否显示状态',
  `icon` varchar(50) NOT NULL COMMENT '系统图标',
  `bgcolor` varchar(20) NOT NULL COMMENT '背景颜色',
  `enable` int(11) NOT NULL,
  `regionid` text NOT NULL COMMENT '小区id',
  `do` varchar(20) NOT NULL COMMENT '动作',
  `thumb` varchar(500) NOT NULL COMMENT '菜单图片',
  `isshow` int(1) NOT NULL COMMENT '1首页推荐',
  `view` int(1) DEFAULT '1',
  `add` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_navextension` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `navurl` varchar(100) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `content` text NOT NULL COMMENT '说明',
  `cate` int(1) NOT NULL,
  `bgcolor` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `regionid` text NOT NULL,
  `fansopenid` varchar(50) NOT NULL,
  `type` int(1) NOT NULL COMMENT '1模板消息通知,2短信通知，3全部通知',
  `enable` int(1) NOT NULL COMMENT '1报修,2建议，3家政',
  `cid` int(11) NOT NULL COMMENT '分类id',
  `uid` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_notice_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `template_id_1` varchar(100) NOT NULL,
  `template_id_2` varchar(100) NOT NULL,
  `template_id_3` varchar(100) NOT NULL,
  `template_id_4` varchar(100) NOT NULL,
  `template_id_5` varchar(100) NOT NULL,
  `template_id_6` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告模板消息ID设置';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_open_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `regionid` int(11) NOT NULL,
  `type` varchar(50) NOT NULL COMMENT '门类型',
  `openid` varchar(50) NOT NULL COMMENT '业主openid',
  `realname` varchar(50) NOT NULL COMMENT '业主姓名',
  `createtime` int(11) NOT NULL COMMENT '开门时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_opendoor_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `regionid` int(11) NOT NULL,
  `build` varchar(50) NOT NULL COMMENT '楼宇名称',
  `unit` varchar(30) NOT NULL COMMENT '单元号',
  `room` varchar(100) NOT NULL COMMENT '房号',
  `createtime` int(11) NOT NULL COMMENT '生成时间',
  `opentime` int(11) NOT NULL COMMENT '开门时间',
  `type` int(1) NOT NULL COMMENT '1单元门，2大门',
  `address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `price` decimal(10,2) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1关闭状态，0普通状态，1为已付款，2为已发货，3已成功',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付，4后台支付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00' COMMENT '商品总价',
  `createtime` int(10) unsigned NOT NULL,
  `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID',
  `gid` int(11) unsigned NOT NULL COMMENT '优惠券id',
  `type` varchar(10) NOT NULL COMMENT '订单来源类型',
  `uid` int(11) unsigned NOT NULL COMMENT '操作员用户id',
  `pid` int(11) unsigned NOT NULL COMMENT '物业费id',
  `aid` int(11) unsigned NOT NULL COMMENT '活动预约id',
  `couponsn` varchar(20) NOT NULL COMMENT '团购券号',
  `num` int(11) unsigned NOT NULL COMMENT '购买数量',
  `enable` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0不限，1为未使用，2已核销',
  `usetime` int(10) unsigned NOT NULL,
  `companyid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单商品附表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_pay` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `pay` varchar(200) NOT NULL,
  `type` int(1) NOT NULL COMMENT '1超市2物业费3商家4小区活动',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支付方式配置表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_pay_api` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `pay` text NOT NULL,
  `type` int(1) NOT NULL,
  `paytype` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_payment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL COMMENT '公众号ID',
  `pid` int(11) NOT NULL,
  `account` varchar(50) NOT NULL COMMENT '支付宝账号',
  `partner` varchar(50) NOT NULL COMMENT '合作者身份',
  `secret` varchar(50) NOT NULL COMMENT '校验密钥',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='支付设置';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_phone` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) unsigned NOT NULL COMMENT '公众号',
  `phone` varchar(50) NOT NULL COMMENT '号码',
  `content` varchar(50) NOT NULL COMMENT '描述',
  `regionid` text NOT NULL COMMENT '小区编号',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `thumb` varchar(1000) NOT NULL COMMENT '图片',
  `displayorder` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='常用电话';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_print` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `print_status` int(10) unsigned NOT NULL,
  `print_type` int(10) unsigned NOT NULL COMMENT '1报修,2投诉，3超市订单，0全部打印',
  `member_code` varchar(80) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  `deviceNo` varchar(50) NOT NULL,
  `regionid` text NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  `printid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='打印机表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_property` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `topPicture` varchar(255) NOT NULL COMMENT '照片',
  `mcommunity` varchar(255) NOT NULL COMMENT '微社区URL',
  `content` varchar(2000) NOT NULL COMMENT '内容',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `regionid` text NOT NULL COMMENT '小区id',
  `telphone` varchar(13) NOT NULL COMMENT '物业电话',
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='物业介绍';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_propertyfree` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `username` varchar(30) NOT NULL,
  `homenumber` varchar(15) NOT NULL,
  `profree` int(4) NOT NULL,
  `tcf` int(4) NOT NULL,
  `gtsf` int(4) NOT NULL,
  `gtdf` int(4) NOT NULL,
  `protimeid` int(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` int(1) NOT NULL,
  `paytype` tinyint(1) NOT NULL,
  `transid` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_protime` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `protime` varchar(30) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `companyid` int(10) unsigned NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_pstyle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `regionid` text NOT NULL COMMENT '小区编号',
  `pid` int(10) unsigned NOT NULL COMMENT '物业ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '封面图',
  `content` text NOT NULL COMMENT '内容',
  `createtime` int(10) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_pstyle_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `sid` int(10) unsigned NOT NULL,
  `wid` int(10) unsigned NOT NULL,
  `regionid` text NOT NULL COMMENT '小区编号',
  `pid` int(10) unsigned NOT NULL COMMENT '物业ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '封面图',
  `content` text NOT NULL COMMENT '内容',
  `createtime` int(10) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_rank` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `type` int(1) unsigned NOT NULL COMMENT '1商家,2超市',
  `content` varchar(2000) NOT NULL COMMENT '评价内容',
  `dpid` int(11) DEFAULT '0' COMMENT '商家店铺id',
  `openid` varchar(100) NOT NULL COMMENT '粉丝openid',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='评价表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_reading_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `aid` int(10) unsigned NOT NULL COMMENT '公告id',
  `openid` varchar(50) NOT NULL,
  `status` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='公告阅读记录表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_region` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `title` varchar(50) NOT NULL COMMENT '标题',
  `linkmen` varchar(50) NOT NULL COMMENT '联系人',
  `linkway` varchar(50) NOT NULL COMMENT '联系电话',
  `lng` varchar(10) NOT NULL,
  `lat` varchar(10) NOT NULL,
  `address` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `url` varchar(100) NOT NULL,
  `thumb` varchar(1000) NOT NULL,
  `qq` varchar(15) DEFAULT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  `rid` int(11) DEFAULT NULL,
  `pic` varchar(1000) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='添加小区信息';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `reportid` int(10) unsigned NOT NULL COMMENT '报告ID',
  `isreply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是回复',
  `content` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_report` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL COMMENT '用户身份',
  `weid` int(11) unsigned NOT NULL COMMENT '公众号ID',
  `regionid` int(10) unsigned NOT NULL COMMENT '小区编号',
  `type` tinyint(1) NOT NULL COMMENT '1为报修，2为投诉',
  `category` varchar(50) NOT NULL DEFAULT '' COMMENT '类目',
  `content` varchar(255) NOT NULL COMMENT '投诉内容',
  `requirement` varchar(1000) NOT NULL,
  `createtime` int(11) unsigned NOT NULL COMMENT '投诉日期',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态,1已处理,2未处理,3受理中',
  `newmsg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有新信息',
  `rank` tinyint(3) unsigned NOT NULL COMMENT '评级 1满意，2一般，3不满意',
  `comment` varchar(1000) NOT NULL,
  `resolve` varchar(1000) NOT NULL COMMENT '处理结果',
  `resolver` varchar(50) NOT NULL COMMENT '处理人',
  `resolvetime` int(10) NOT NULL COMMENT '处理时间',
  `address` varchar(80) NOT NULL COMMENT '地址',
  `images` text,
  `print_sta` int(3) NOT NULL COMMENT '打印状态',
  `cid` int(11) DEFAULT NULL,
  `enable` int(1) DEFAULT NULL,
  `dealing` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_weid_regionid` (`weid`,`regionid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_res` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `truename` varchar(30) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `num` int(2) unsigned NOT NULL COMMENT '报名人数',
  `aid` int(11) unsigned NOT NULL COMMENT '活动id',
  `rid` int(11) unsigned NOT NULL,
  `sex` varchar(6) NOT NULL,
  `createtime` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_room` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `mobile` varchar(13) NOT NULL,
  `room` varchar(50) DEFAULT NULL,
  `code` varchar(10) NOT NULL,
  `regionid` int(10) unsigned NOT NULL,
  `realname` varchar(30) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `build` varchar(50) DEFAULT NULL,
  `unit` int(5) DEFAULT NULL,
  `house` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='房号表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_search` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `sname` varchar(30) NOT NULL,
  `surl` varchar(100) NOT NULL,
  `status` int(1) NOT NULL,
  `icon` varchar(1000) NOT NULL,
  `regionid` text NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `regionid` text NOT NULL COMMENT '小区编号',
  `servicecategory` int(10) unsigned NOT NULL COMMENT '生活服务大分类 1家政服务，2租赁服务',
  `servicesmallcategory` varchar(50) NOT NULL COMMENT '生活服务小分类',
  `requirement` varchar(255) NOT NULL COMMENT '精准要求,如保洁需要填写 平米大小',
  `remark` varchar(500) NOT NULL COMMENT '备注',
  `contacttype` int(10) unsigned NOT NULL COMMENT '联系类型:1.随时联系;2.白天联系;3:晚上联系;4:自定义',
  `contactdesc` varchar(255) NOT NULL COMMENT '联系描述',
  `status` int(10) unsigned NOT NULL COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL,
  `images` text,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `pid` int(10) unsigned NOT NULL COMMENT '物业ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  `description` varchar(100) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_service_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `type` int(1) NOT NULL COMMENT '物业费1，超市2，商家3',
  `sub_mch_id` varchar(50) NOT NULL,
  `tid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_service_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `switch` int(1) NOT NULL,
  `mchid` varchar(50) NOT NULL,
  `signkey` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_servicecategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `description` varchar(50) NOT NULL COMMENT '分类描述',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `code_status` int(10) unsigned NOT NULL COMMENT '验证码开启',
  `range` int(10) unsigned NOT NULL COMMENT 'lbs范围',
  `room_status` int(10) unsigned NOT NULL COMMENT '验证码开启',
  `room_enable` int(10) unsigned NOT NULL COMMENT '验证码开启',
  `h_status` int(10) unsigned NOT NULL COMMENT '房屋租赁托管',
  `s_status` int(10) unsigned NOT NULL COMMENT '商家提成',
  `c_status` int(1) NOT NULL,
  `r_status` int(10) DEFAULT NULL,
  `r_enable` int(10) DEFAULT NULL,
  `tel` varchar(30) DEFAULT NULL,
  `region_status` int(1) DEFAULT NULL,
  `business_status` int(1) DEFAULT NULL,
  `visitor_status` int(1) DEFAULT NULL,
  `shop_credit` float DEFAULT NULL,
  `business_credit` float DEFAULT NULL,
  `cost_credit` float DEFAULT NULL,
  `fled_status` int(1) DEFAULT NULL,
  `house_status` int(1) DEFAULT NULL,
  `car_status` int(1) DEFAULT NULL,
  `open_status` int(1) DEFAULT NULL,
  `repair_status` int(1) DEFAULT NULL,
  `report_status` int(1) DEFAULT NULL,
  `houselease_status` int(1) DEFAULT NULL,
  `fleds_status` int(1) DEFAULT NULL,
  `cars_status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小区设置';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `realname` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `province` varchar(30) NOT NULL,
  `city` varchar(30) NOT NULL,
  `area` varchar(30) NOT NULL,
  `address` varchar(300) NOT NULL,
  `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `goodsid` int(11) NOT NULL,
  `goodstype` tinyint(1) NOT NULL DEFAULT '1',
  `from_user` varchar(50) NOT NULL,
  `total` int(10) unsigned NOT NULL,
  `optionid` int(10) DEFAULT '0',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_openid` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='超市商品分类';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` int(11) DEFAULT '0',
  `description` text,
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  `enable` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_express` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `express_name` varchar(50) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `express_price` varchar(10) DEFAULT '',
  `express_area` varchar(100) DEFAULT '',
  `express_url` varchar(255) DEFAULT '',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝',
  `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号',
  `transid` varchar(30) NOT NULL COMMENT '订单号',
  `reason` varchar(1000) NOT NULL COMMENT '理由',
  `solution` varchar(1000) NOT NULL COMMENT '期待解决方案',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_feedbackid` (`feedbackid`),
  KEY `idx_createtime` (`createtime`),
  KEY `idx_transid` (`transid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `unit` varchar(5) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `goodssn` varchar(50) NOT NULL DEFAULT '',
  `productsn` varchar(50) NOT NULL DEFAULT '',
  `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `productprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `costprice` decimal(10,2) NOT NULL DEFAULT '0.00',
  `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `total` int(10) NOT NULL DEFAULT '0',
  `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减',
  `sales` int(10) unsigned NOT NULL DEFAULT '0',
  `spec` varchar(5000) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `weight` decimal(10,2) NOT NULL DEFAULT '0.00',
  `credit` int(11) DEFAULT '0',
  `maxbuy` int(11) DEFAULT '0',
  `hasoption` int(11) DEFAULT '0',
  `dispatch` int(11) DEFAULT '0',
  `thumb_url` text,
  `isnew` int(11) DEFAULT '0',
  `ishot` int(11) DEFAULT '0',
  `isdiscount` int(11) DEFAULT '0',
  `isrecommand` int(11) DEFAULT '0',
  `istime` int(11) DEFAULT '0',
  `timestart` int(11) DEFAULT '0',
  `timeend` int(11) DEFAULT '0',
  `viewcount` int(11) DEFAULT '0',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_goods_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(60) DEFAULT '',
  `productprice` decimal(10,2) DEFAULT '0.00',
  `marketprice` decimal(10,2) DEFAULT '0.00',
  `costprice` decimal(10,2) DEFAULT '0.00',
  `stock` int(11) DEFAULT '0',
  `weight` decimal(10,2) DEFAULT '0.00',
  `displayorder` int(11) DEFAULT '0',
  `specs` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `addressid` int(10) unsigned NOT NULL,
  `expresscom` varchar(30) NOT NULL DEFAULT '',
  `expresssn` varchar(50) NOT NULL DEFAULT '',
  `express` varchar(200) NOT NULL DEFAULT '',
  `goodsprice` decimal(10,2) DEFAULT '0.00',
  `dispatchprice` decimal(10,2) DEFAULT '0.00',
  `dispatch` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID',
  `gid` int(11) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `aid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) DEFAULT '0.00',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `optionid` int(10) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  `optionname` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_print` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `print_status` int(10) unsigned NOT NULL,
  `api_key` varchar(50) NOT NULL,
  `deviceNo` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='超市打印机表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `productsn` varchar(50) NOT NULL,
  `title` varchar(1000) NOT NULL,
  `marketprice` decimal(10,0) unsigned NOT NULL,
  `productprice` decimal(10,0) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `spec` varchar(5000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_goodsid` (`goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `notice` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `regionid` varchar(1000) NOT NULL COMMENT '小区ID',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_shopping_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_sjdp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `regionid` varchar(20000) DEFAULT NULL,
  `sjname` varchar(30) NOT NULL,
  `picurl` varchar(100) NOT NULL,
  `contactname` varchar(30) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `qq` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  `address` varchar(150) NOT NULL,
  `shopdesc` varchar(500) NOT NULL,
  `businnesstime` varchar(20) NOT NULL,
  `businessurl` varchar(100) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `lat` int(10) NOT NULL,
  `lng` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_sjdp_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `sid` int(11) NOT NULL COMMENT '店铺id',
  `goodsname` varchar(80) NOT NULL,
  `thumb` varchar(80) NOT NULL,
  `marketprice` varchar(80) NOT NULL,
  `originalprice` varchar(80) NOT NULL,
  `total` int(11) NOT NULL,
  `credit` decimal(10,2) NOT NULL,
  `description` varchar(500) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商家商品';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_sjdp_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL COMMENT '订单ID',
  `gid` int(10) unsigned NOT NULL COMMENT '优惠券id',
  `openid` varchar(30) NOT NULL,
  `couponsn` varchar(60) NOT NULL,
  `type` int(1) NOT NULL COMMENT '是否被使用 1未使用，2已使用',
  `createtime` int(11) NOT NULL COMMENT '领取时间',
  `usetime` int(11) NOT NULL COMMENT '使用时间',
  `uid` int(10) unsigned NOT NULL COMMENT '商家id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠劵';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `displayorder` int(10) NOT NULL,
  `title` varchar(30) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `url` varchar(100) NOT NULL,
  `regionid` text NOT NULL,
  `type` int(11) NOT NULL COMMENT '1小区首页,2超市',
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='幻灯管理';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `styleid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模板设置';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `companyid` int(11) NOT NULL,
  `regionid` int(11) NOT NULL,
  `good_tplid` varchar(80) DEFAULT NULL,
  `menus` varchar(500) NOT NULL,
  `balance` decimal(10,2) NOT NULL COMMENT '商家余额',
  `commission` float NOT NULL COMMENT '分成，0-1之间',
  `groupid` int(11) DEFAULT NULL,
  `uuid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员表';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_users_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `title` varchar(50) NOT NULL,
  `maxaccount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_verifycode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `verifycode` varchar(6) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `total` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_xcommunity_wechat_notice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `regionid` text NOT NULL,
  `fansopenid` varchar(50) NOT NULL,
  `repair_status` int(1) NOT NULL,
  `report_status` int(1) NOT NULL,
  `shopping_status` int(1) NOT NULL,
  `type` int(1) NOT NULL COMMENT '1模板消息通知,2短信通知，3全部通知',
  `uid` int(11) DEFAULT NULL,
  `homemaking_status` int(1) NOT NULL,
  `change_status` int(1) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `dist` varchar(50) DEFAULT NULL,
  `cost_status` int(1) DEFAULT NULL,
  `business_status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信设置';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_wechat_smsid` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `shopping_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `sms_account` varchar(80) NOT NULL,
  `verify` int(1) NOT NULL,
  `businesscode` int(1) NOT NULL,
  `verifycode` int(1) NOT NULL,
  `report_type` int(1) NOT NULL,
  `shopping_status` int(1) NOT NULL,
  `property_status` int(1) NOT NULL,
  `reportid` int(11) NOT NULL,
  `resgisterid` int(11) NOT NULL,
  `room_status` int(1) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='短信设置';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_wechat_tplid` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `property_tplid` varchar(80) NOT NULL,
  `water_tplid` varchar(80) NOT NULL,
  `gas_tplid` varchar(80) NOT NULL,
  `power_tplid` varchar(80) NOT NULL,
  `guard_tplid` varchar(80) NOT NULL,
  `lift_tplid` varchar(80) NOT NULL,
  `car_tplid` varchar(80) NOT NULL,
  `shopping_tplid` varchar(80) NOT NULL,
  `repair_tplid` varchar(80) NOT NULL,
  `report_tplid` varchar(80) NOT NULL,
  `other_tplid` varchar(80) NOT NULL,
  `good_tplid` varchar(80) NOT NULL,
  `grab_wc_tplid` varchar(80) NOT NULL,
  `report_wc_tplid` varchar(80) DEFAULT NULL,
  `homemaking_tplid` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模板设置';
CREATE TABLE IF NOT EXISTS `ims_xcommunity_wnotice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `regionid` text NOT NULL COMMENT '小区编号',
  `pid` int(10) unsigned NOT NULL COMMENT '物业ID',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `thumb` varchar(255) NOT NULL COMMENT '封面图',
  `content` text NOT NULL COMMENT '内容',
  `createtime` int(10) unsigned NOT NULL,
  `uid` int(11) NOT NULL,
  `province` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `dist` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('xcommunity_activity',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_activity',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `regionid` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `starttime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `endtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'enddate')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `enddate` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'picurl')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `picurl` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'number')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `number` int(11) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_activity',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `content` varchar(2000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `status` int(1) NOT NULL COMMENT '1置顶';");
}
if(!pdo_fieldexists('xcommunity_activity',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `createtime` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'resnumber')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `resnumber` int(11) unsigned NOT NULL COMMENT '报名人数';");
}
if(!pdo_fieldexists('xcommunity_activity',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `price` decimal(12,2) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_activity',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_activity')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_admap',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_admap')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_admap',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_admap')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_admap',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_admap')." ADD `regionid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_admap',  'adid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_admap')." ADD `adid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_alipayment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_alipayment')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_alipayment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_alipayment')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_alipayment',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_alipayment')." ADD `pid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_alipayment',  'account')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_alipayment')." ADD `account` varchar(50) NOT NULL COMMENT '支付宝账号';");
}
if(!pdo_fieldexists('xcommunity_alipayment',  'partner')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_alipayment')." ADD `partner` varchar(50) NOT NULL COMMENT '合作者身份';");
}
if(!pdo_fieldexists('xcommunity_alipayment',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_alipayment')." ADD `secret` varchar(50) NOT NULL COMMENT '校验密钥';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_announcement',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `regionid` text;");
}
if(!pdo_fieldexists('xcommunity_announcement',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `title` varchar(255) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `content` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'author')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `author` varchar(50) NOT NULL COMMENT '作者';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_announcement',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `starttime` int(11) unsigned NOT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `endtime` int(11) unsigned NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `status` tinyint(1) NOT NULL COMMENT '状态 1禁用，2启用';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `enable` tinyint(1) NOT NULL COMMENT '模板类型';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'datetime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `datetime` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_announcement',  'location')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `location` varchar(100) NOT NULL COMMENT '通知范围';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'reason')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `reason` text;");
}
if(!pdo_fieldexists('xcommunity_announcement',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `remark` varchar(100) NOT NULL COMMENT '通知备注';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `pid` int(10) unsigned NOT NULL COMMENT '物业ID';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'tit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `tit` varchar(255) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'time')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `time` varchar(100) NOT NULL COMMENT '门禁卡失效时间';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'scope')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `scope` varchar(100) NOT NULL COMMENT '门禁卡失效范围';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'method')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `method` varchar(300) NOT NULL COMMENT '门禁卡重新激活办法';");
}
if(!pdo_fieldexists('xcommunity_announcement',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `uid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_announcement',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_announcement',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_announcement',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_announcement')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_building_device',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_building_device')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_building_device',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_building_device')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xcommunity_building_device',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_building_device')." ADD `regionid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_building_device',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_building_device')." ADD `title` varchar(50) NOT NULL COMMENT '楼宇名称';");
}
if(!pdo_fieldexists('xcommunity_building_device',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_building_device')." ADD `unit` varchar(30) NOT NULL COMMENT '单元号';");
}
if(!pdo_fieldexists('xcommunity_building_device',  'api_key')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_building_device')." ADD `api_key` varchar(100) NOT NULL COMMENT '设备apikey';");
}
if(!pdo_fieldexists('xcommunity_building_device',  'device_code')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_building_device')." ADD `device_code` int(11) NOT NULL COMMENT '设备编号';");
}
if(!pdo_fieldexists('xcommunity_building_device',  'lock_code')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_building_device')." ADD `lock_code` varchar(11) NOT NULL COMMENT '锁编号';");
}
if(!pdo_fieldexists('xcommunity_building_device',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_building_device')." ADD `type` int(1) NOT NULL COMMENT '1单元门，2大门';");
}
if(!pdo_fieldexists('xcommunity_business',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_business',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_business',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `mobile` varchar(12) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_business',  'username')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `username` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_business',  'password')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `password` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_business',  'photo')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `photo` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_business',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `qq` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_business',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_business',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `status` int(1) unsigned NOT NULL COMMENT '0未审核，1审核';");
}
if(!pdo_fieldexists('xcommunity_business',  'balance')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `balance` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_business',  'commission')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `commission` float DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_business',  'alipay')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_business')." ADD `alipay` varchar(100) NOT NULL COMMENT '支付宝账户';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `title` varchar(50) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'seat')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `seat` int(2) unsigned NOT NULL COMMENT '座位';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'sprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `sprice` int(10) unsigned NOT NULL COMMENT '价格';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'month')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `month` int(2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'yday')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `yday` int(2) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'contact')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `contact` varchar(50) NOT NULL COMMENT '联系人';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `mobile` varchar(13) NOT NULL COMMENT '电话';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'start_position')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `start_position` varchar(100) NOT NULL COMMENT '出发地';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'end_position')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `end_position` varchar(100) NOT NULL COMMENT '目的地';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'startMinute')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `startMinute` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'startSeconds')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `startSeconds` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'license_number')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `license_number` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'car_model')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `car_model` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'car_brand')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `car_brand` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `content` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'gotime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `gotime` varchar(10) NOT NULL COMMENT '出发时间';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'backtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `backtime` varchar(10) NOT NULL COMMENT '返回时间';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_carpool',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `regionid` int(10) unsigned NOT NULL COMMENT '所属小区';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `type` int(11) NOT NULL COMMENT '类型1司机，2乘客';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `thumb` varchar(2000) NOT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('xcommunity_carpool',  'black')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_carpool')." ADD `black` int(1) NOT NULL COMMENT '1设置黑名单';");
}
if(!pdo_fieldexists('xcommunity_cart',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cart')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_cart',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cart')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cart',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cart')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cart',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cart')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cart',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cart')." ADD `total` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cart',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cart')." ADD `marketprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_cash_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cash_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_cash_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cash_order')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cash_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cash_order')." ADD `uid` int(11) NOT NULL COMMENT '用户ID';");
}
if(!pdo_fieldexists('xcommunity_cash_order',  'cash')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cash_order')." ADD `cash` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cash_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cash_order')." ADD `status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cash_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cash_order')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cash_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cash_order')." ADD `ordersn` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `weid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `parentid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `name` varchar(50) NOT NULL COMMENT '服务项目';");
}
if(!pdo_fieldexists('xcommunity_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `description` text NOT NULL COMMENT '分类描述';");
}
if(!pdo_fieldexists('xcommunity_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `thumb` varchar(100) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists('xcommunity_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xcommunity_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('xcommunity_category',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `type` int(10) unsigned NOT NULL COMMENT '1家政，2报修，3投诉，4二手，5超市，6商家';");
}
if(!pdo_fieldexists('xcommunity_category',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `price` decimal(12,2) NOT NULL COMMENT '服务价格';");
}
if(!pdo_fieldexists('xcommunity_category',  'gtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `gtime` varchar(50) NOT NULL COMMENT '服务工时';");
}
if(!pdo_fieldexists('xcommunity_category',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `regionid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_category',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `isshow` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_category',  'url')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_category')." ADD `url` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_cost',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost')." ADD `regionid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost',  'costtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost')." ADD `costtime` varchar(30) NOT NULL COMMENT '费用时间';");
}
if(!pdo_fieldexists('xcommunity_cost',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost')." ADD `enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost')." ADD `status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `regionid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `cid` int(10) unsigned NOT NULL COMMENT '费用时间id';");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `mobile` varchar(13) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'username')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `username` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'homenumber')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `homenumber` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'costtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `costtime` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'propertyfee')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `propertyfee` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'otherfee')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `otherfee` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `total` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `status` varchar(3) NOT NULL COMMENT '是代表缴费，否代表未缴费';");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付';");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `fee` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_cost_list',  'area')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_cost_list')." ADD `area` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_dp',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `regionid` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'sjname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `sjname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'picurl')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `picurl` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'contactname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `contactname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `mobile` varchar(12) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `phone` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `qq` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `dist` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `address` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'shopdesc')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `shopdesc` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'businnesstime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `businnesstime` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'businessurl')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `businessurl` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `lat` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `lng` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'businesstime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `businesstime` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'parent')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `parent` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'child')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `child` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `rid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `displayorder` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `price` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'area')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `area` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_dp',  'instruction')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_dp')." ADD `instruction` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_fled',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `title` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'rolex')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `rolex` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'category')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `category` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'yprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `yprice` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'zprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `zprice` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `realname` varchar(18) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `mobile` varchar(12) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `description` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `regionid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'images')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `images` text;");
}
if(!pdo_fieldexists('xcommunity_fled',  'black')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `black` int(1) NOT NULL COMMENT '1设置黑名单';");
}
if(!pdo_fieldexists('xcommunity_fled',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_fled',  'cate')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_fled')." ADD `cate` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_goods',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_goods',  'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `thumb_url` text;");
}
if(!pdo_fieldexists('xcommunity_goods',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `unit` varchar(5) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_goods',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `content` text NOT NULL COMMENT '抢购详情';");
}
if(!pdo_fieldexists('xcommunity_goods',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '销售价';");
}
if(!pdo_fieldexists('xcommunity_goods',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `productprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价';");
}
if(!pdo_fieldexists('xcommunity_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `total` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `credit` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_goods',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `isrecommand` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_goods',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `description` text NOT NULL COMMENT '购买须知';");
}
if(!pdo_fieldexists('xcommunity_goods',  'dpid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `dpid` int(11) DEFAULT '0' COMMENT '商家店铺id';");
}
if(!pdo_fieldexists('xcommunity_goods',  'sold')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `sold` int(11) DEFAULT '0' COMMENT '已售多少份';");
}
if(!pdo_fieldexists('xcommunity_goods',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `type` int(11) DEFAULT '0' COMMENT '1超市2商家';");
}
if(!pdo_fieldexists('xcommunity_goods',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `regionid` text;");
}
if(!pdo_fieldexists('xcommunity_goods',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `starttime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `endtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'businesstime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `businesstime` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'parent')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `parent` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'child')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `child` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_goods',  'instruction')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_goods')." ADD `instruction` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `regionid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'category')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `category` int(11) NOT NULL COMMENT '服务类型';");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'servicetime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `servicetime` varchar(30) NOT NULL COMMENT '服务时间';");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `realname` varchar(30) NOT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `mobile` varchar(15) NOT NULL COMMENT '电话';");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `address` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `content` varchar(500) NOT NULL COMMENT '说明';");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `status` int(10) unsigned NOT NULL COMMENT '0未完成,1已完成';");
}
if(!pdo_fieldexists('xcommunity_homemaking',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_homemaking')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `regionid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'category')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `category` int(11) NOT NULL COMMENT '1出租，2求租，3出售，4求购';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'way')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `way` varchar(20) NOT NULL COMMENT '出租方式';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'model_room')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `model_room` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'model_hall')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `model_hall` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'model_toilet')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `model_toilet` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'model_area')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `model_area` varchar(15) NOT NULL COMMENT '房屋面积';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'floor_layer')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `floor_layer` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'floor_number')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `floor_number` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'fitment')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `fitment` varchar(40) NOT NULL COMMENT '装修情况';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'house')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `house` varchar(40) NOT NULL COMMENT '住宅类别';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'allocation')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `allocation` varchar(500) NOT NULL COMMENT '房屋配置';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'price_way')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `price_way` varchar(30) NOT NULL COMMENT '押金方式';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `price` int(10) unsigned NOT NULL COMMENT '租金';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'checktime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `checktime` varchar(30) NOT NULL COMMENT '入住时间';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `title` varchar(30) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `realname` varchar(30) NOT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `mobile` varchar(15) NOT NULL COMMENT '电话';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `content` varchar(500) NOT NULL COMMENT '说明';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `status` int(10) unsigned NOT NULL COMMENT '0未成交,1已成交';");
}
if(!pdo_fieldexists('xcommunity_houselease',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'images')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `images` text;");
}
if(!pdo_fieldexists('xcommunity_houselease',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_houselease')." ADD `enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_images',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_images')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_images',  'src')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_images')." ADD `src` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_images',  'file')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_images')." ADD `file` longtext;");
}
if(!pdo_fieldexists('xcommunity_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_member',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `weid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_member',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `regionid` int(10) unsigned NOT NULL COMMENT '小区编号';");
}
if(!pdo_fieldexists('xcommunity_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `openid` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_member',  'memberid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `memberid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_member',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `realname` varchar(50) NOT NULL COMMENT '真实姓名';");
}
if(!pdo_fieldexists('xcommunity_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `mobile` varchar(15) NOT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('xcommunity_member',  'regionname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `regionname` varchar(50) NOT NULL COMMENT '小区名称';");
}
if(!pdo_fieldexists('xcommunity_member',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `address` varchar(100) NOT NULL COMMENT '楼栋门牌';");
}
if(!pdo_fieldexists('xcommunity_member',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `remark` varchar(1000) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('xcommunity_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_member',  'manage_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `manage_status` tinyint(1) unsigned NOT NULL COMMENT '授权管理员';");
}
if(!pdo_fieldexists('xcommunity_member',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `type` tinyint(1) unsigned NOT NULL COMMENT '业主身份';");
}
if(!pdo_fieldexists('xcommunity_member',  'build')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `build` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_member',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `unit` int(5) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_member',  'room')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `room` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_member',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_member',  'open_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD `open_status` int(1) DEFAULT NULL;");
}
if(!pdo_indexexists('xcommunity_member',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member')." ADD KEY `idx_openid` (`openid`);");
}
if(!pdo_fieldexists('xcommunity_member_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_member_address',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xcommunity_member_address',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_member_address',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `mid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_member_address',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `regionid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_member_address',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_member_address',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `address` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_member_address',  'telephone')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `telephone` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_member_address',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `realname` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_member_address',  'build')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `build` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_member_address',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `unit` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_member_address',  'room')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `room` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_member_address',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_member_address')." ADD `enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_menu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_menu')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_menu',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_menu')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_menu',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_menu')." ADD `pcate` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_menu',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_menu')." ADD `title` varchar(30) NOT NULL COMMENT '菜单标题';");
}
if(!pdo_fieldexists('xcommunity_menu',  'url')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_menu')." ADD `url` varchar(1000) NOT NULL COMMENT '菜单链接';");
}
if(!pdo_fieldexists('xcommunity_menu',  'do')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_menu')." ADD `do` varchar(20) NOT NULL COMMENT '动作';");
}
if(!pdo_fieldexists('xcommunity_menu',  'method')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_menu')." ADD `method` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_menu',  'xcommunity_menu')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_menu')." ADD `xcommunity_menu` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_nav',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_nav',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_nav',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `displayorder` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_nav',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `pcate` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_nav',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `title` varchar(30) NOT NULL COMMENT '菜单标题';");
}
if(!pdo_fieldexists('xcommunity_nav',  'url')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `url` varchar(1000) NOT NULL COMMENT '菜单链接';");
}
if(!pdo_fieldexists('xcommunity_nav',  'styleid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `styleid` int(10) NOT NULL COMMENT '风格id';");
}
if(!pdo_fieldexists('xcommunity_nav',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `status` int(1) NOT NULL COMMENT '是否显示状态';");
}
if(!pdo_fieldexists('xcommunity_nav',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `icon` varchar(50) NOT NULL COMMENT '系统图标';");
}
if(!pdo_fieldexists('xcommunity_nav',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `bgcolor` varchar(20) NOT NULL COMMENT '背景颜色';");
}
if(!pdo_fieldexists('xcommunity_nav',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `enable` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_nav',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `regionid` text NOT NULL COMMENT '小区id';");
}
if(!pdo_fieldexists('xcommunity_nav',  'do')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `do` varchar(20) NOT NULL COMMENT '动作';");
}
if(!pdo_fieldexists('xcommunity_nav',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `thumb` varchar(500) NOT NULL COMMENT '菜单图片';");
}
if(!pdo_fieldexists('xcommunity_nav',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `isshow` int(1) NOT NULL COMMENT '1首页推荐';");
}
if(!pdo_fieldexists('xcommunity_nav',  'view')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `view` int(1) DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_nav',  'add')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_nav')." ADD `add` int(1) DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_navextension',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_navextension')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_navextension',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_navextension')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_navextension',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_navextension')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_navextension',  'navurl')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_navextension')." ADD `navurl` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_navextension',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_navextension')." ADD `icon` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_navextension',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_navextension')." ADD `content` text NOT NULL COMMENT '说明';");
}
if(!pdo_fieldexists('xcommunity_navextension',  'cate')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_navextension')." ADD `cate` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_navextension',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_navextension')." ADD `bgcolor` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_notice',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `regionid` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice',  'fansopenid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `fansopenid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `type` int(1) NOT NULL COMMENT '1模板消息通知,2短信通知，3全部通知';");
}
if(!pdo_fieldexists('xcommunity_notice',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `enable` int(1) NOT NULL COMMENT '1报修,2建议，3家政';");
}
if(!pdo_fieldexists('xcommunity_notice',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `cid` int(11) NOT NULL COMMENT '分类id';");
}
if(!pdo_fieldexists('xcommunity_notice',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice')." ADD `dist` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_notice_setting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice_setting')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice_setting',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice_setting')." ADD `regionid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice_setting',  'template_id_1')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice_setting')." ADD `template_id_1` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice_setting',  'template_id_2')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice_setting')." ADD `template_id_2` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice_setting',  'template_id_3')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice_setting')." ADD `template_id_3` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice_setting',  'template_id_4')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice_setting')." ADD `template_id_4` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice_setting',  'template_id_5')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice_setting')." ADD `template_id_5` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_notice_setting',  'template_id_6')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_notice_setting')." ADD `template_id_6` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_open_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_open_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_open_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_open_log')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xcommunity_open_log',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_open_log')." ADD `regionid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_open_log',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_open_log')." ADD `type` varchar(50) NOT NULL COMMENT '门类型';");
}
if(!pdo_fieldexists('xcommunity_open_log',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_open_log')." ADD `openid` varchar(50) NOT NULL COMMENT '业主openid';");
}
if(!pdo_fieldexists('xcommunity_open_log',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_open_log')." ADD `realname` varchar(50) NOT NULL COMMENT '业主姓名';");
}
if(!pdo_fieldexists('xcommunity_open_log',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_open_log')." ADD `createtime` int(11) NOT NULL COMMENT '开门时间';");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `regionid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'build')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `build` varchar(50) NOT NULL COMMENT '楼宇名称';");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `unit` varchar(30) NOT NULL COMMENT '单元号';");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'room')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `room` varchar(100) NOT NULL COMMENT '房号';");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `createtime` int(11) NOT NULL COMMENT '生成时间';");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'opentime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `opentime` int(11) NOT NULL COMMENT '开门时间';");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `type` int(1) NOT NULL COMMENT '1单元门，2大门';");
}
if(!pdo_fieldexists('xcommunity_opendoor_data',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_opendoor_data')." ADD `address` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `ordersn` varchar(20) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('xcommunity_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `price` decimal(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1关闭状态，0普通状态，1为已付款，2为已发货，3已成功';");
}
if(!pdo_fieldexists('xcommunity_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付，4后台支付';");
}
if(!pdo_fieldexists('xcommunity_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
if(!pdo_fieldexists('xcommunity_order',  'goodstype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `remark` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_order',  'goodsprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `goodsprice` decimal(10,2) DEFAULT '0.00' COMMENT '商品总价';");
}
if(!pdo_fieldexists('xcommunity_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_order',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID';");
}
if(!pdo_fieldexists('xcommunity_order',  'gid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `gid` int(11) unsigned NOT NULL COMMENT '优惠券id';");
}
if(!pdo_fieldexists('xcommunity_order',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `type` varchar(10) NOT NULL COMMENT '订单来源类型';");
}
if(!pdo_fieldexists('xcommunity_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `uid` int(11) unsigned NOT NULL COMMENT '操作员用户id';");
}
if(!pdo_fieldexists('xcommunity_order',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `pid` int(11) unsigned NOT NULL COMMENT '物业费id';");
}
if(!pdo_fieldexists('xcommunity_order',  'aid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `aid` int(11) unsigned NOT NULL COMMENT '活动预约id';");
}
if(!pdo_fieldexists('xcommunity_order',  'couponsn')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `couponsn` varchar(20) NOT NULL COMMENT '团购券号';");
}
if(!pdo_fieldexists('xcommunity_order',  'num')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `num` int(11) unsigned NOT NULL COMMENT '购买数量';");
}
if(!pdo_fieldexists('xcommunity_order',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `enable` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0不限，1为未使用，2已核销';");
}
if(!pdo_fieldexists('xcommunity_order',  'usetime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `usetime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_order',  'companyid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order')." ADD `companyid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_order_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_order_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_order_goods',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order_goods')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_order_goods',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order_goods')." ADD `goodsid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_order_goods',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order_goods')." ADD `price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_order_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order_goods')." ADD `total` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_order_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_order_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pay',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_pay',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pay',  'pay')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay')." ADD `pay` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pay',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay')." ADD `type` int(1) NOT NULL COMMENT '1超市2物业费3商家4小区活动';");
}
if(!pdo_fieldexists('xcommunity_pay_api',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay_api')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_pay_api',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay_api')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pay_api',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay_api')." ADD `cid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pay_api',  'pay')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay_api')." ADD `pay` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pay_api',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay_api')." ADD `type` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pay_api',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pay_api')." ADD `paytype` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_payment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_payment')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_payment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_payment')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_payment',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_payment')." ADD `pid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_payment',  'account')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_payment')." ADD `account` varchar(50) NOT NULL COMMENT '支付宝账号';");
}
if(!pdo_fieldexists('xcommunity_payment',  'partner')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_payment')." ADD `partner` varchar(50) NOT NULL COMMENT '合作者身份';");
}
if(!pdo_fieldexists('xcommunity_payment',  'secret')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_payment')." ADD `secret` varchar(50) NOT NULL COMMENT '校验密钥';");
}
if(!pdo_fieldexists('xcommunity_phone',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_phone',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `weid` int(11) unsigned NOT NULL COMMENT '公众号';");
}
if(!pdo_fieldexists('xcommunity_phone',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `phone` varchar(50) NOT NULL COMMENT '号码';");
}
if(!pdo_fieldexists('xcommunity_phone',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `content` varchar(50) NOT NULL COMMENT '描述';");
}
if(!pdo_fieldexists('xcommunity_phone',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `regionid` text NOT NULL COMMENT '小区编号';");
}
if(!pdo_fieldexists('xcommunity_phone',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `title` varchar(50) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_phone',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `thumb` varchar(1000) NOT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('xcommunity_phone',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `displayorder` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_phone',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_phone',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_phone',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_phone',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_phone')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_print',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'print_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `print_status` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'print_type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `print_type` int(10) unsigned NOT NULL COMMENT '1报修,2投诉，3超市订单，0全部打印';");
}
if(!pdo_fieldexists('xcommunity_print',  'member_code')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `member_code` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'api_key')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `api_key` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'deviceNo')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `deviceNo` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `regionid` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_print',  'printid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_print')." ADD `printid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_property',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_property',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_property',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `title` varchar(255) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_property',  'topPicture')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `topPicture` varchar(255) NOT NULL COMMENT '照片';");
}
if(!pdo_fieldexists('xcommunity_property',  'mcommunity')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `mcommunity` varchar(255) NOT NULL COMMENT '微社区URL';");
}
if(!pdo_fieldexists('xcommunity_property',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `content` varchar(2000) NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('xcommunity_property',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('xcommunity_property',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `regionid` text NOT NULL COMMENT '小区id';");
}
if(!pdo_fieldexists('xcommunity_property',  'telphone')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `telphone` varchar(13) NOT NULL COMMENT '物业电话';");
}
if(!pdo_fieldexists('xcommunity_property',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_property',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_property',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_property',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_property')." ADD `uid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `mobile` varchar(13) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'username')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `username` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'homenumber')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `homenumber` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'profree')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `profree` int(4) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'tcf')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `tcf` int(4) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'gtsf')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `gtsf` int(4) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'gtdf')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `gtdf` int(4) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'protimeid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `protimeid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `paytype` tinyint(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_propertyfree',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_propertyfree')." ADD `transid` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_protime',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_protime')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_protime',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_protime')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_protime',  'protime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_protime')." ADD `protime` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_protime',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_protime')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_protime',  'companyid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_protime')." ADD `companyid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_protime',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_protime')." ADD `regionid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `regionid` text NOT NULL COMMENT '小区编号';");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `pid` int(10) unsigned NOT NULL COMMENT '物业ID';");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `title` varchar(255) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `thumb` varchar(255) NOT NULL COMMENT '封面图';");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `content` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle')." ADD `dist` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `sid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'wid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `wid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `regionid` text NOT NULL COMMENT '小区编号';");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `pid` int(10) unsigned NOT NULL COMMENT '物业ID';");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `title` varchar(255) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `thumb` varchar(255) NOT NULL COMMENT '封面图';");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `content` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_pstyle_content',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_pstyle_content')." ADD `dist` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_rank',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_rank')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_rank',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_rank')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_rank',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_rank')." ADD `type` int(1) unsigned NOT NULL COMMENT '1商家,2超市';");
}
if(!pdo_fieldexists('xcommunity_rank',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_rank')." ADD `content` varchar(2000) NOT NULL COMMENT '评价内容';");
}
if(!pdo_fieldexists('xcommunity_rank',  'dpid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_rank')." ADD `dpid` int(11) DEFAULT '0' COMMENT '商家店铺id';");
}
if(!pdo_fieldexists('xcommunity_rank',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_rank')." ADD `openid` varchar(100) NOT NULL COMMENT '粉丝openid';");
}
if(!pdo_fieldexists('xcommunity_rank',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_rank')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('xcommunity_reading_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reading_member')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_reading_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reading_member')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_reading_member',  'aid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reading_member')." ADD `aid` int(10) unsigned NOT NULL COMMENT '公告id';");
}
if(!pdo_fieldexists('xcommunity_reading_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reading_member')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_reading_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reading_member')." ADD `status` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_region',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_region',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `title` varchar(50) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_region',  'linkmen')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `linkmen` varchar(50) NOT NULL COMMENT '联系人';");
}
if(!pdo_fieldexists('xcommunity_region',  'linkway')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `linkway` varchar(50) NOT NULL COMMENT '联系电话';");
}
if(!pdo_fieldexists('xcommunity_region',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `lng` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `lat` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `address` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `pid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'url')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `url` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `thumb` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `qq` varchar(15) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `dist` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `rid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `pic` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_region',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_region')." ADD `uid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reply')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_reply',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reply')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_reply',  'reportid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reply')." ADD `reportid` int(10) unsigned NOT NULL COMMENT '报告ID';");
}
if(!pdo_fieldexists('xcommunity_reply',  'isreply')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reply')." ADD `isreply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是回复';");
}
if(!pdo_fieldexists('xcommunity_reply',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reply')." ADD `content` varchar(5000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_reply')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_report',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_report',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `openid` varchar(50) NOT NULL COMMENT '用户身份';");
}
if(!pdo_fieldexists('xcommunity_report',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `weid` int(11) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_report',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `regionid` int(10) unsigned NOT NULL COMMENT '小区编号';");
}
if(!pdo_fieldexists('xcommunity_report',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `type` tinyint(1) NOT NULL COMMENT '1为报修，2为投诉';");
}
if(!pdo_fieldexists('xcommunity_report',  'category')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `category` varchar(50) NOT NULL DEFAULT '' COMMENT '类目';");
}
if(!pdo_fieldexists('xcommunity_report',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `content` varchar(255) NOT NULL COMMENT '投诉内容';");
}
if(!pdo_fieldexists('xcommunity_report',  'requirement')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `requirement` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_report',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `createtime` int(11) unsigned NOT NULL COMMENT '投诉日期';");
}
if(!pdo_fieldexists('xcommunity_report',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `status` tinyint(1) unsigned NOT NULL COMMENT '状态,1已处理,2未处理,3受理中';");
}
if(!pdo_fieldexists('xcommunity_report',  'newmsg')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `newmsg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有新信息';");
}
if(!pdo_fieldexists('xcommunity_report',  'rank')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `rank` tinyint(3) unsigned NOT NULL COMMENT '评级 1满意，2一般，3不满意';");
}
if(!pdo_fieldexists('xcommunity_report',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `comment` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_report',  'resolve')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `resolve` varchar(1000) NOT NULL COMMENT '处理结果';");
}
if(!pdo_fieldexists('xcommunity_report',  'resolver')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `resolver` varchar(50) NOT NULL COMMENT '处理人';");
}
if(!pdo_fieldexists('xcommunity_report',  'resolvetime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `resolvetime` int(10) NOT NULL COMMENT '处理时间';");
}
if(!pdo_fieldexists('xcommunity_report',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `address` varchar(80) NOT NULL COMMENT '地址';");
}
if(!pdo_fieldexists('xcommunity_report',  'images')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `images` text;");
}
if(!pdo_fieldexists('xcommunity_report',  'print_sta')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `print_sta` int(3) NOT NULL COMMENT '打印状态';");
}
if(!pdo_fieldexists('xcommunity_report',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `cid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_report',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_report',  'dealing')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD `dealing` varchar(100) DEFAULT NULL;");
}
if(!pdo_indexexists('xcommunity_report',  'idx_weid_regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_report')." ADD KEY `idx_weid_regionid` (`weid`,`regionid`);");
}
if(!pdo_fieldexists('xcommunity_res',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_res',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_res',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_res',  'truename')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `truename` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_res',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `mobile` varchar(12) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_res',  'num')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `num` int(2) unsigned NOT NULL COMMENT '报名人数';");
}
if(!pdo_fieldexists('xcommunity_res',  'aid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `aid` int(11) unsigned NOT NULL COMMENT '活动id';");
}
if(!pdo_fieldexists('xcommunity_res',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `rid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_res',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `sex` varchar(6) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_res',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_res',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_res')." ADD `status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_room',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_room',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `mobile` varchar(13) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'room')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `room` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'code')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `code` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `regionid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `realname` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `pid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'build')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `build` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `unit` int(5) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_room',  'house')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_room')." ADD `house` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_search',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'sname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `sname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'surl')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `surl` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `icon` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `regionid` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `uid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_search',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_search')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_service',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `regionid` text NOT NULL COMMENT '小区编号';");
}
if(!pdo_fieldexists('xcommunity_service',  'servicecategory')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `servicecategory` int(10) unsigned NOT NULL COMMENT '生活服务大分类 1家政服务，2租赁服务';");
}
if(!pdo_fieldexists('xcommunity_service',  'servicesmallcategory')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `servicesmallcategory` varchar(50) NOT NULL COMMENT '生活服务小分类';");
}
if(!pdo_fieldexists('xcommunity_service',  'requirement')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `requirement` varchar(255) NOT NULL COMMENT '精准要求,如保洁需要填写 平米大小';");
}
if(!pdo_fieldexists('xcommunity_service',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `remark` varchar(500) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('xcommunity_service',  'contacttype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `contacttype` int(10) unsigned NOT NULL COMMENT '联系类型:1.随时联系;2.白天联系;3:晚上联系;4:自定义';");
}
if(!pdo_fieldexists('xcommunity_service',  'contactdesc')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `contactdesc` varchar(255) NOT NULL COMMENT '联系描述';");
}
if(!pdo_fieldexists('xcommunity_service',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `status` int(10) unsigned NOT NULL COMMENT '状态';");
}
if(!pdo_fieldexists('xcommunity_service',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'images')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `images` text;");
}
if(!pdo_fieldexists('xcommunity_service',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_service',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `pid` int(10) unsigned NOT NULL COMMENT '物业ID';");
}
if(!pdo_fieldexists('xcommunity_service',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `title` varchar(255) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_service',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `content` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('xcommunity_service',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `cid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `dist` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `description` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service')." ADD `thumb` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_data')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_service_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_data')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xcommunity_service_data',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_data')." ADD `type` int(1) NOT NULL COMMENT '物业费1，超市2，商家3';");
}
if(!pdo_fieldexists('xcommunity_service_data',  'sub_mch_id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_data')." ADD `sub_mch_id` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service_data',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_data')." ADD `tid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_service_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_set')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xcommunity_service_set',  'switch')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_set')." ADD `switch` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service_set',  'mchid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_set')." ADD `mchid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_service_set',  'signkey')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_service_set')." ADD `signkey` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_servicecategory',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_servicecategory')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_servicecategory',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_servicecategory')." ADD `weid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_servicecategory',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_servicecategory')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('xcommunity_servicecategory',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_servicecategory')." ADD `description` varchar(50) NOT NULL COMMENT '分类描述';");
}
if(!pdo_fieldexists('xcommunity_servicecategory',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_servicecategory')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('xcommunity_servicecategory',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_servicecategory')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xcommunity_servicecategory',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_servicecategory')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('xcommunity_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_set',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'code_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `code_status` int(10) unsigned NOT NULL COMMENT '验证码开启';");
}
if(!pdo_fieldexists('xcommunity_set',  'range')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `range` int(10) unsigned NOT NULL COMMENT 'lbs范围';");
}
if(!pdo_fieldexists('xcommunity_set',  'room_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `room_status` int(10) unsigned NOT NULL COMMENT '验证码开启';");
}
if(!pdo_fieldexists('xcommunity_set',  'room_enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `room_enable` int(10) unsigned NOT NULL COMMENT '验证码开启';");
}
if(!pdo_fieldexists('xcommunity_set',  'h_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `h_status` int(10) unsigned NOT NULL COMMENT '房屋租赁托管';");
}
if(!pdo_fieldexists('xcommunity_set',  's_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `s_status` int(10) unsigned NOT NULL COMMENT '商家提成';");
}
if(!pdo_fieldexists('xcommunity_set',  'c_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `c_status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'r_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `r_status` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'r_enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `r_enable` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `tel` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'region_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `region_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'business_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `business_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'visitor_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `visitor_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'shop_credit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `shop_credit` float DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'business_credit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `business_credit` float DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'cost_credit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `cost_credit` float DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'fled_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `fled_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'house_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `house_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'car_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `car_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'open_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `open_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'repair_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `repair_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'report_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `report_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'houselease_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `houselease_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'fleds_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `fleds_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_set',  'cars_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_set')." ADD `cars_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `realname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `province` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `city` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'area')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `area` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `address` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'isdefault')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `isdefault` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_address',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_address')." ADD `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID';");
}
if(!pdo_fieldexists('xcommunity_shopping_cart',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_cart')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_cart',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_cart')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_cart',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_cart')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_cart',  'goodstype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_cart')." ADD `goodstype` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_shopping_cart',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_cart')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_cart',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_cart')." ADD `total` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_cart',  'optionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_cart')." ADD `optionid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_cart',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_cart')." ADD `marketprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_indexexists('xcommunity_shopping_cart',  'idx_openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_cart')." ADD KEY `idx_openid` (`from_user`);");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `thumb` varchar(255) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `isrecommand` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `description` varchar(500) NOT NULL COMMENT '分类介绍';");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('xcommunity_shopping_category',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_category')." ADD `regionid` varchar(1000) NOT NULL COMMENT '小区ID';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'dispatchname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `dispatchname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'dispatchtype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `dispatchtype` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'firstprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `firstprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'secondprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `secondprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'firstweight')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `firstweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'secondweight')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `secondweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'express')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `express` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `description` text;");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `regionid` varchar(1000) NOT NULL COMMENT '小区ID';");
}
if(!pdo_fieldexists('xcommunity_shopping_dispatch',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD `enable` int(1) DEFAULT '0';");
}
if(!pdo_indexexists('xcommunity_shopping_dispatch',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('xcommunity_shopping_dispatch',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_dispatch')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('xcommunity_shopping_express',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_express',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_express',  'express_name')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD `express_name` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_express',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_express',  'express_price')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD `express_price` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_express',  'express_area')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD `express_area` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_express',  'express_url')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD `express_url` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_express',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD `regionid` varchar(1000) NOT NULL COMMENT '小区ID';");
}
if(!pdo_indexexists('xcommunity_shopping_express',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('xcommunity_shopping_express',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_express')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为维权，2为告擎';");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态0未解决，1用户同意，2用户拒绝';");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'feedbackid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `feedbackid` varchar(30) NOT NULL COMMENT '投诉单号';");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `transid` varchar(30) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'reason')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `reason` varchar(1000) NOT NULL COMMENT '理由';");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'solution')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `solution` varchar(1000) NOT NULL COMMENT '期待解决方案';");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `remark` varchar(1000) NOT NULL COMMENT '备注';");
}
if(!pdo_fieldexists('xcommunity_shopping_feedback',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('xcommunity_shopping_feedback',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('xcommunity_shopping_feedback',  'idx_feedbackid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD KEY `idx_feedbackid` (`feedbackid`);");
}
if(!pdo_indexexists('xcommunity_shopping_feedback',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_indexexists('xcommunity_shopping_feedback',  'idx_transid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_feedback')." ADD KEY `idx_transid` (`transid`);");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `pcate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `ccate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1为实体，2为虚拟';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `unit` varchar(5) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `description` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'goodssn')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `goodssn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'productsn')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `productsn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `marketprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `productprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'costprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `costprice` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'originalprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `originalprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `total` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'totalcnf')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `totalcnf` int(11) DEFAULT '0' COMMENT '0 拍下减库存 1 付款减库存 2 永久不减';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'sales')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `sales` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'spec')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `spec` varchar(5000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'weight')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `weight` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `credit` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'maxbuy')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `maxbuy` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'hasoption')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `hasoption` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `dispatch` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `thumb_url` text;");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'isnew')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `isnew` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'ishot')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `ishot` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'isdiscount')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `isdiscount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `isrecommand` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'istime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `istime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'timestart')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `timestart` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'timeend')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `timeend` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'viewcount')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `viewcount` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'deleted')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods')." ADD `regionid` varchar(1000) NOT NULL COMMENT '小区ID';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `goodsid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `thumb` varchar(60) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `productprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `marketprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'costprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `costprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'stock')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `stock` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'weight')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `weight` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_option',  'specs')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD `specs` text;");
}
if(!pdo_indexexists('xcommunity_shopping_goods_option',  'indx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD KEY `indx_goodsid` (`goodsid`);");
}
if(!pdo_indexexists('xcommunity_shopping_goods_option',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_option')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_param',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_param')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_param',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_param')." ADD `goodsid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_param',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_param')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_param',  'value')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_param')." ADD `value` text;");
}
if(!pdo_fieldexists('xcommunity_shopping_goods_param',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_param')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('xcommunity_shopping_goods_param',  'indx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_param')." ADD KEY `indx_goodsid` (`goodsid`);");
}
if(!pdo_indexexists('xcommunity_shopping_goods_param',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_goods_param')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `ordersn` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `price` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'sendtype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `sendtype` tinyint(1) unsigned NOT NULL COMMENT '1为快递，2为自提';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'goodstype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `goodstype` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `remark` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'addressid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `addressid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'expresscom')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `expresscom` varchar(30) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'expresssn')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `expresssn` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'express')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `express` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'goodsprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `goodsprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'dispatchprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `dispatchprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'dispatch')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `dispatch` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `regionid` int(11) unsigned NOT NULL COMMENT '当前小区ID';");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'gid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `gid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `type` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `uid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `pid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order',  'aid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order')." ADD `aid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_order_goods',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order_goods')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order_goods',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order_goods')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order_goods',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order_goods')." ADD `goodsid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order_goods',  'price')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order_goods')." ADD `price` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('xcommunity_shopping_order_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order_goods')." ADD `total` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_shopping_order_goods',  'optionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order_goods')." ADD `optionid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_order_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order_goods')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_order_goods',  'optionname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_order_goods')." ADD `optionname` text;");
}
if(!pdo_fieldexists('xcommunity_shopping_print',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_print')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_print',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_print')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_print',  'print_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_print')." ADD `print_status` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_print',  'api_key')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_print')." ADD `api_key` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_print',  'deviceNo')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_print')." ADD `deviceNo` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_product',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_product',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_product',  'productsn')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD `productsn` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_product',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD `title` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_product',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD `marketprice` decimal(10,0) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_product',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD `productprice` decimal(10,0) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_product',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD `total` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_product',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD `status` tinyint(3) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('xcommunity_shopping_product',  'spec')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD `spec` varchar(5000) NOT NULL;");
}
if(!pdo_indexexists('xcommunity_shopping_product',  'idx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_product')." ADD KEY `idx_goodsid` (`goodsid`);");
}
if(!pdo_fieldexists('xcommunity_shopping_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_set')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_set',  'notice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_set')." ADD `notice` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_slide',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_slide',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_slide',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_slide',  'link')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD `link` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_slide',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_slide',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_slide',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_slide',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD `regionid` varchar(1000) NOT NULL COMMENT '小区ID';");
}
if(!pdo_indexexists('xcommunity_shopping_slide',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('xcommunity_shopping_slide',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('xcommunity_shopping_slide',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_slide')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('xcommunity_shopping_spec',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_spec',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_spec',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_spec',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_spec',  'displaytype')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec')." ADD `displaytype` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_spec',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_shopping_spec',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec')." ADD `goodsid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_spec',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_spec_item',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_shopping_spec_item',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_spec_item',  'specid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD `specid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_spec_item',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_spec_item',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('xcommunity_shopping_spec_item',  'show')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD `show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('xcommunity_shopping_spec_item',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('xcommunity_shopping_spec_item',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('xcommunity_shopping_spec_item',  'indx_specid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD KEY `indx_specid` (`specid`);");
}
if(!pdo_indexexists('xcommunity_shopping_spec_item',  'indx_show')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD KEY `indx_show` (`show`);");
}
if(!pdo_indexexists('xcommunity_shopping_spec_item',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_shopping_spec_item')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `regionid` varchar(20000) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'sjname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `sjname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'picurl')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `picurl` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'contactname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `contactname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `mobile` varchar(12) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `phone` varchar(15) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `qq` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `dist` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'address')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `address` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'shopdesc')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `shopdesc` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'businnesstime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `businnesstime` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'businessurl')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `businessurl` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `lat` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp')." ADD `lng` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `sid` int(11) NOT NULL COMMENT '店铺id';");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'goodsname')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `goodsname` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `thumb` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `marketprice` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'originalprice')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `originalprice` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `total` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `credit` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'description')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `description` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_goods')." ADD `status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `orderid` int(10) unsigned NOT NULL COMMENT '订单ID';");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'gid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `gid` int(10) unsigned NOT NULL COMMENT '优惠券id';");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `openid` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'couponsn')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `couponsn` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `type` int(1) NOT NULL COMMENT '是否被使用 1未使用，2已使用';");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `createtime` int(11) NOT NULL COMMENT '领取时间';");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'usetime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `usetime` int(11) NOT NULL COMMENT '使用时间';");
}
if(!pdo_fieldexists('xcommunity_sjdp_member',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_sjdp_member')." ADD `uid` int(10) unsigned NOT NULL COMMENT '商家id';");
}
if(!pdo_fieldexists('xcommunity_slide',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_slide',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_slide',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `displayorder` int(10) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_slide',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_slide',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `thumb` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_slide',  'url')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `url` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_slide',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `regionid` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_slide',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `type` int(11) NOT NULL COMMENT '1小区首页,2超市';");
}
if(!pdo_fieldexists('xcommunity_slide',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_slide',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_slide',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_slide')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_template',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_template')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_template',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_template')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_template',  'styleid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_template')." ADD `styleid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_users',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_users',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_users',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_users',  'companyid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `companyid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_users',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `regionid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_users',  'good_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `good_tplid` varchar(80) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_users',  'menus')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `menus` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_users',  'balance')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `balance` decimal(10,2) NOT NULL COMMENT '商家余额';");
}
if(!pdo_fieldexists('xcommunity_users',  'commission')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `commission` float NOT NULL COMMENT '分成，0-1之间';");
}
if(!pdo_fieldexists('xcommunity_users',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `groupid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_users',  'uuid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users')." ADD `uuid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_users_group',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users_group')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_users_group',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users_group')." ADD `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('xcommunity_users_group',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users_group')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_users_group',  'maxaccount')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_users_group')." ADD `maxaccount` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_verifycode',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_verifycode')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_verifycode',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_verifycode')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_verifycode',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_verifycode')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_verifycode',  'verifycode')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_verifycode')." ADD `verifycode` varchar(6) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_verifycode',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_verifycode')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_verifycode',  'total')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_verifycode')." ADD `total` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_verifycode',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_verifycode')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('xcommunity_verifycode',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_verifycode')." ADD KEY `openid` (`openid`);");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `regionid` text NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'fansopenid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `fansopenid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'repair_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `repair_status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'report_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `report_status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'shopping_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `shopping_status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `type` int(1) NOT NULL COMMENT '1模板消息通知,2短信通知，3全部通知';");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `uid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'homemaking_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `homemaking_status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'change_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `change_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `province` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `city` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `dist` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'cost_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `cost_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_notice',  'business_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_notice')." ADD `business_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'shopping_id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `shopping_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'property_id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `property_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'sms_account')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `sms_account` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'verify')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `verify` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'businesscode')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `businesscode` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'verifycode')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `verifycode` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'report_type')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `report_type` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'shopping_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `shopping_status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'property_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `property_status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'reportid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `reportid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'resgisterid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `resgisterid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'room_status')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `room_status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_smsid',  'room_id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_smsid')." ADD `room_id` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'property_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `property_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'water_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `water_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'gas_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `gas_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'power_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `power_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'guard_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `guard_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'lift_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `lift_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'car_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `car_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'shopping_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `shopping_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'repair_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `repair_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'report_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `report_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'other_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `other_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'good_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `good_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'grab_wc_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `grab_wc_tplid` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'report_wc_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `report_wc_tplid` varchar(80) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wechat_tplid',  'homemaking_tplid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wechat_tplid')." ADD `homemaking_tplid` varchar(80) DEFAULT NULL;");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'id')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'regionid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `regionid` text NOT NULL COMMENT '小区编号';");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `pid` int(10) unsigned NOT NULL COMMENT '物业ID';");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'title')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `title` varchar(255) NOT NULL COMMENT '标题';");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `thumb` varchar(255) NOT NULL COMMENT '封面图';");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'content')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `content` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'province')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `province` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'city')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `city` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('xcommunity_wnotice',  'dist')) {
	pdo_query("ALTER TABLE ".tablename('xcommunity_wnotice')." ADD `dist` varchar(50) NOT NULL;");
}

?>