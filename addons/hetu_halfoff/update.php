<?php

pdo_query('CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT \'主键\',
  `uniacid` int(10) NOT NULL COMMENT \'公众号id\',
  `openid` varchar(150) NOT NULL COMMENT \'微信号\',
  `username` varchar(20) NOT NULL COMMENT \'收货人\',
  `mobile` varchar(11) NOT NULL COMMENT \'邮箱\',
  `zipcode` varchar(6) NOT NULL COMMENT \'邮编\',
  `province` varchar(32) NOT NULL COMMENT \'省份\',
  `city` varchar(32) NOT NULL COMMENT \'城市\',
  `district` varchar(32) NOT NULL COMMENT \'县区\',
  `address` varchar(512) NOT NULL COMMENT \'详细地址\',
  `isdefault` tinyint(1) NOT NULL COMMENT \'默认地址\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT \'0\',
  `advname` varchar(50) DEFAULT \'\',
  `link` varchar(255) DEFAULT \'\',
  `thumb` varchar(255) DEFAULT \'\',
  `displayorder` int(11) DEFAULT \'0\',
  `enabled` int(11) DEFAULT \'0\',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(115) NOT NULL,
  `period_number` varchar(145) NOT NULL COMMENT \'期号\',
  `uniacid` int(11) NOT NULL,
  `title` varchar(145) NOT NULL,
  `ip` varchar(145) NOT NULL,
  `ipaddress` varchar(145) NOT NULL,
  `num` int(11) NOT NULL COMMENT \'购买人次数\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1281 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'所属帐号\',
  `name` varchar(50) NOT NULL COMMENT \'分类名称\',
  `thumb` varchar(255) NOT NULL COMMENT \'分类图片\',
  `parentid` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'上级分类ID,0为第一级\',
  `isrecommand` int(10) DEFAULT \'0\',
  `description` varchar(500) NOT NULL COMMENT \'分类介绍\',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT \'0\' COMMENT \'排序\',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT \'1\' COMMENT \'是否开启\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_comcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `numa` varchar(20) NOT NULL,
  `numb` varchar(11) NOT NULL,
  `periods` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `wincode` int(11) NOT NULL,
  `arecord` longtext NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_consumerecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(145) NOT NULL,
  `num` int(11) NOT NULL COMMENT \'消费夺宝币数量\',
  `status` int(2) NOT NULL COMMENT \'0消费失败1消费成功\',
  `period_number` varchar(145) NOT NULL COMMENT \'期号\',
  `createtime` varchar(145) NOT NULL COMMENT \'消费时间\',
  `ip` varchar(45) NOT NULL,
  `ipaddress` varchar(145) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=645 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_coupon` (
  `couponid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT \'商家ID\',
  `uniacid` int(10) unsigned NOT NULL,
  `type` tinyint(4) NOT NULL,
  `title` varchar(30) NOT NULL,
  `couponsn` varchar(50) NOT NULL,
  `description` text,
  `discount` decimal(10,2) NOT NULL,
  `condition` decimal(10,2) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `limit` int(11) NOT NULL,
  `dosage` int(11) unsigned NOT NULL,
  `amount` int(11) unsigned NOT NULL,
  `thumb` varchar(500) NOT NULL,
  `credit` int(10) unsigned NOT NULL,
  `credittype` varchar(20) NOT NULL,
  `module` varchar(30) NOT NULL,
  `use_module` tinyint(3) unsigned NOT NULL,
  `daylimit` int(11) NOT NULL COMMENT \'领取后几天有效\',
  PRIMARY KEY (`couponid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_coupon_record` (
  `recid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `grantmodule` varchar(50) NOT NULL,
  `granttime` int(10) unsigned NOT NULL,
  `usemodule` varchar(50) NOT NULL,
  `usetime` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT \'1未使用2已使用\',
  `operator` varchar(30) NOT NULL,
  `remark` varchar(300) NOT NULL,
  `couponid` int(10) unsigned NOT NULL,
  `clerk_id` int(10) unsigned NOT NULL,
  `merchantid` int(11) NOT NULL COMMENT \'商家ID\',
  `firstopenid` varchar(145) NOT NULL COMMENT \'拥有者opneid\',
  `secondopenid` varchar(145) NOT NULL COMMENT \'持有者openid\',
  `gettime` varchar(45) NOT NULL COMMENT \'生成时间\',
  `endtime` varchar(45) NOT NULL COMMENT \'结束时间\',
  `couponnum` int(11) NOT NULL COMMENT \'优惠卷数量\',
  `coupon_number` varchar(145) NOT NULL COMMENT \'优惠卷唯一编号\',
  `usedcouponnum` int(11) NOT NULL COMMENT \'已使用的优惠券数量\',
  PRIMARY KEY (`recid`),
  KEY `couponid` (`uid`,`grantmodule`,`usemodule`,`status`),
  KEY `uniacid` (`uniacid`)
) ENGINE=InnoDB AUTO_INCREMENT=502 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_goods_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT \'自增id\',
  `g_id` int(11) NOT NULL COMMENT \'商品id\',
  `thumb` varchar(145) NOT NULL COMMENT \'图片路径\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_goodslist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT \'主键\',
  `uniacid` int(10) unsigned NOT NULL COMMENT \'公众账号\',
  `merchant_id` int(11) NOT NULL COMMENT \'所属商家ID\',
  `title` varchar(100) DEFAULT NULL COMMENT \'商品标题\',
  `category_parentid` int(11) NOT NULL,
  `category_childid` int(11) NOT NULL,
  `price` int(10) DEFAULT \'0\' COMMENT \'金额\',
  `canyurenshu` int(10) unsigned DEFAULT \'0\' COMMENT \'已参与总人次数\',
  `periods` smallint(6) unsigned DEFAULT \'0\' COMMENT \'期数\',
  `maxperiods` smallint(5) unsigned DEFAULT \'1\' COMMENT \' 最大期数\',
  `picarr` text COMMENT \'商品图片\',
  `content` mediumtext COMMENT \'商品详情\',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT \'创建时间\',
  `pos` tinyint(4) unsigned DEFAULT NULL COMMENT \'是否推荐\',
  `status` int(11) NOT NULL COMMENT \'0:删除,1:下架, 2: 上架\',
  `isnew` int(11) NOT NULL,
  `ishot` int(11) NOT NULL,
  `jiexiao_time` int(11) NOT NULL COMMENT \'多少分钟后揭晓（整数）\',
  `couponid` int(11) NOT NULL COMMENT \'优惠卷ID\',
  `init_money` int(11) NOT NULL COMMENT \'几元专区\',
  `maxnum` int(11) NOT NULL COMMENT \'最大购买数量\',
  `sort` int(11) NOT NULL COMMENT \'排序\',
  `next_init_money` int(11) NOT NULL COMMENT \'下期专区价格\',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_hexiao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT \'核销id\',
  `name` varchar(50) NOT NULL COMMENT \'核销名称\',
  `discount` decimal(10,2) NOT NULL COMMENT \'核销金额\',
  `hexiaoperson` varchar(32) NOT NULL COMMENT \'核销人\',
  `usedperson` varchar(32) NOT NULL COMMENT \'被核销人\',
  `createtime` int(11) NOT NULL COMMENT \'核销时间\',
  `uniacid` int(10) DEFAULT NULL COMMENT \'公众号id\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_in` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT \'唯一标识\',
  `data` int(50) NOT NULL,
  `type` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  KEY `indx_displayorder` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `beinvited_openid` varchar(145) NOT NULL COMMENT \'被邀请人家的openid\',
  `invite_openid` varchar(145) NOT NULL COMMENT \'邀请人的openid\',
  `createtime` varchar(145) NOT NULL,
  `credit1` int(11) NOT NULL COMMENT \'已返利积分数\',
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_machineset` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT \'唯一标识\',
  `uniacid` int(11) NOT NULL COMMENT \'公众号id\',
  `period_number` varchar(145) NOT NULL COMMENT \'商品期号\',
  `machine_num` int(11) NOT NULL COMMENT \'使用机器人个数\',
  `createtime` varchar(145) NOT NULL COMMENT \'创建时间\',
  `start_time` varchar(145) NOT NULL COMMENT \'开始时间\',
  `end_time` varchar(145) NOT NULL COMMENT \'结束时间\',
  `next_time` varchar(145) NOT NULL COMMENT \'机器人下个自动购买时间\',
  `status` int(2) NOT NULL COMMENT \'机器人状态\',
  `max_num` int(11) NOT NULL,
  `timebucket` varchar(145) NOT NULL,
  `is_followed` int(2) NOT NULL,
  `goodsid` int(11) NOT NULL,
  `all_buy` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_member` (
  `mid` int(11) NOT NULL AUTO_INCREMENT COMMENT \'用户id\',
  `openid` varchar(145) NOT NULL COMMENT \'用户openid\',
  `uniacid` int(11) NOT NULL COMMENT \'公众号id\',
  `mobile` varchar(25) NOT NULL COMMENT \'手机\',
  `email` varchar(145) NOT NULL COMMENT \'电子邮件\',
  `credit1` decimal(10,2) NOT NULL COMMENT \'积分\',
  `credit2` decimal(10,2) NOT NULL COMMENT \'余额\',
  `createtime` varchar(145) NOT NULL COMMENT \'创建时间\',
  `nickname` varchar(145) NOT NULL COMMENT \'昵称\',
  `realname` varchar(145) NOT NULL COMMENT \'真实姓名\',
  `avatar` varchar(445) NOT NULL COMMENT \'头像\',
  `gender` int(2) NOT NULL COMMENT \'性别\',
  `vip` int(2) NOT NULL COMMENT \'vip等级\',
  `address` varchar(225) NOT NULL COMMENT \'地址\',
  `nationality` varchar(30) NOT NULL COMMENT \'国家\',
  `resideprovince` varchar(30) NOT NULL COMMENT \'省份\',
  `residecity` varchar(30) NOT NULL COMMENT \'城市\',
  `residedist` varchar(30) NOT NULL COMMENT \'地区\',
  `account` varchar(145) NOT NULL COMMENT \'账号\',
  `password` varchar(145) NOT NULL COMMENT \'密码\',
  `status` int(2) NOT NULL COMMENT \'用户状态\',
  `type` int(2) NOT NULL COMMENT \'用户类型\',
  `ip` varchar(35) NOT NULL COMMENT \'固定IP\',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=2384 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_merchant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) NOT NULL,
  `logo` varchar(225) NOT NULL,
  `industry` varchar(45) NOT NULL,
  `address` varchar(115) NOT NULL,
  `linkman_name` varchar(145) NOT NULL,
  `linkman_mobile` varchar(145) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `createtime` varchar(115) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `detail` varchar(1222) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_navi` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT \'唯一标识\',
  `uniacid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `link` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL COMMENT \'本期商品ID\',
  `periods` int(11) NOT NULL COMMENT \'该商品第几期\',
  `nickname` varchar(145) NOT NULL COMMENT \'获奖人昵称\',
  `avatar` varchar(225) NOT NULL COMMENT \'获奖人头像\',
  `openid` varchar(145) NOT NULL COMMENT \'获奖人openid\',
  `partakes` int(11) NOT NULL COMMENT \'获奖人参与次数\',
  `code` varchar(45) NOT NULL COMMENT \'获奖码\',
  `endtime` varchar(145) NOT NULL COMMENT \'本期结束时间\',
  `jiexiao_time` int(11) NOT NULL COMMENT \'多少分钟后揭晓（整数）\',
  `confirmtime` int(11) NOT NULL COMMENT \'确认地址时间\',
  `taketime` int(11) NOT NULL COMMENT \'确认收货时间\',
  `realname` varchar(20) NOT NULL COMMENT \'中奖人姓名\',
  `mobile` varchar(11) NOT NULL COMMENT \'中奖人电话\',
  `address` varchar(200) NOT NULL COMMENT \'中奖人地址\',
  `express` varchar(45) NOT NULL COMMENT \'快递公司\',
  `expressn` varchar(145) NOT NULL COMMENT \'快递单号\',
  `sendtime` varchar(145) NOT NULL COMMENT \'发货时间\',
  `codes` longtext NOT NULL COMMENT \'本期商品剩余夺宝码\',
  `uniacid` int(11) NOT NULL,
  `shengyu_codes` int(11) NOT NULL COMMENT \'剩余夺宝码个数\',
  `zong_codes` int(11) NOT NULL COMMENT \'总个数\',
  `period_number` varchar(145) NOT NULL COMMENT \'期号\',
  `canyurenshu` int(11) NOT NULL COMMENT \'参与人次数\',
  `status` int(4) NOT NULL COMMENT \'1进行中，2待揭晓，3已揭晓，4待发货，5已发货，6已完成，7已晒单\',
  `scale` int(11) NOT NULL COMMENT \'参与比例\',
  `createtime` varchar(145) NOT NULL,
  `recordid` int(11) NOT NULL COMMENT \'购买记录id\',
  `allcodes` longtext NOT NULL COMMENT \'备份总夺宝码\',
  `comment` varchar(2048) NOT NULL COMMENT \'备注\',
  `machine_time` varchar(145) NOT NULL COMMENT \'机器人时间\',
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `period_number` (`period_number`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_rechargerecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(245) NOT NULL,
  `num` int(11) NOT NULL COMMENT \'充值夺宝币个数\',
  `createtime` varchar(145) NOT NULL,
  `transid` varchar(145) NOT NULL,
  `status` int(11) NOT NULL,
  `paytype` int(2) NOT NULL,
  `ordersn` varchar(145) NOT NULL COMMENT \'订单号\',
  `type` int(2) NOT NULL COMMENT \'0充值并消费1仅充值3积分兑换\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3212 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `uniacid` int(10) unsigned NOT NULL COMMENT \'公众账号\',
  `goodsid` int(10) unsigned NOT NULL COMMENT \'商品ID\',
  `ordersn` varchar(20) NOT NULL COMMENT \'订单编号\',
  `status` smallint(4) NOT NULL DEFAULT \'0\' COMMENT \'0未支付，1为已付款,2待发货3待收货4已完成\',
  `transid` varchar(30) NOT NULL COMMENT \'微信订单号\',
  `count` int(10) unsigned NOT NULL COMMENT \'商品数量\',
  `code` longtext COMMENT \'获得的夺宝码\',
  `createtime` int(10) unsigned NOT NULL COMMENT \'购买时间\',
  `period_number` varchar(145) NOT NULL COMMENT \'期号\',
  PRIMARY KEY (`id`),
  KEY `ordersn` (`ordersn`)
) ENGINE=InnoDB AUTO_INCREMENT=358 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` varchar(225) DEFAULT \'\',
  `uniacid` int(11) DEFAULT \'0\',
  `openid` varchar(255) DEFAULT \'\',
  `nickname` varchar(145) NOT NULL,
  `avatar` varchar(225) NOT NULL,
  `status` tinyint(3) DEFAULT \'0\',
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_showprize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(300) NOT NULL,
  `title` varchar(200) NOT NULL,
  `detail` varchar(1000) NOT NULL,
  `period_number` varchar(45) NOT NULL COMMENT \'期号\',
  `createtime` varchar(145) NOT NULL COMMENT \'晒单时间\',
  `status` int(11) NOT NULL COMMENT \'1待审核2通过3未通过\',
  `goodstitle` varchar(145) NOT NULL,
  `thumbs` varchar(2048) NOT NULL COMMENT \'图集\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_syssetting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_weliam_indiana_withdraw` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT \'唯一标识\',
  `uniacid` int(11) NOT NULL COMMENT \'公众号id\',
  `openid` varchar(225) NOT NULL COMMENT \'提现人\',
  `createtime` varchar(45) NOT NULL COMMENT \'提现时间\',
  `number` int(11) NOT NULL COMMENT \'金额\',
  `status` int(2) NOT NULL COMMENT \'提现状态（1：等待提现；2：提现成功；3提现失败）\',
  `type` int(2) NOT NULL COMMENT \'提现方式（1：微信；2支付宝；3京东钱包；4：百度钱包）\',
  `order_no` varchar(225) NOT NULL COMMENT \'提现订单号\',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
');
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `id` int(10) NOT NULL auto_increment  COMMENT \'主键\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `uniacid` int(10) NOT NULL   COMMENT \'公众号id\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `openid` varchar(150) NOT NULL   COMMENT \'微信号\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'username')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `username` varchar(20) NOT NULL   COMMENT \'收货人\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'mobile')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `mobile` varchar(11) NOT NULL   COMMENT \'邮箱\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'zipcode')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `zipcode` varchar(6) NOT NULL   COMMENT \'邮编\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'province')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `province` varchar(32) NOT NULL   COMMENT \'省份\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'city')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `city` varchar(32) NOT NULL   COMMENT \'城市\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'district')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `district` varchar(32) NOT NULL   COMMENT \'县区\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'address')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `address` varchar(512) NOT NULL   COMMENT \'详细地址\';');
    }
}
if (pdo_tableexists('weliam_indiana_address')) {
    if (!pdo_fieldexists('weliam_indiana_address', 'isdefault')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_address') . ' ADD `isdefault` tinyint(1) NOT NULL   COMMENT \'默认地址\';');
    }
}
if (pdo_tableexists('weliam_indiana_adv')) {
    if (!pdo_fieldexists('weliam_indiana_adv', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_adv') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_adv')) {
    if (!pdo_fieldexists('weliam_indiana_adv', 'weid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_adv') . ' ADD `weid` int(11)   DEFAULT 0 COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_adv')) {
    if (!pdo_fieldexists('weliam_indiana_adv', 'advname')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_adv') . ' ADD `advname` varchar(50)    COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_adv')) {
    if (!pdo_fieldexists('weliam_indiana_adv', 'link')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_adv') . ' ADD `link` varchar(255)    COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_adv')) {
    if (!pdo_fieldexists('weliam_indiana_adv', 'thumb')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_adv') . ' ADD `thumb` varchar(255)    COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_adv')) {
    if (!pdo_fieldexists('weliam_indiana_adv', 'displayorder')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_adv') . ' ADD `displayorder` int(11)   DEFAULT 0 COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_adv')) {
    if (!pdo_fieldexists('weliam_indiana_adv', 'enabled')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_adv') . ' ADD `enabled` int(11)   DEFAULT 0 COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_cart')) {
    if (!pdo_fieldexists('weliam_indiana_cart', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_cart') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_cart')) {
    if (!pdo_fieldexists('weliam_indiana_cart', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_cart') . ' ADD `openid` varchar(115) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_cart')) {
    if (!pdo_fieldexists('weliam_indiana_cart', 'period_number')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_cart') . ' ADD `period_number` varchar(145) NOT NULL   COMMENT \'期号\';');
    }
}
if (pdo_tableexists('weliam_indiana_cart')) {
    if (!pdo_fieldexists('weliam_indiana_cart', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_cart') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_cart')) {
    if (!pdo_fieldexists('weliam_indiana_cart', 'title')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_cart') . ' ADD `title` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_cart')) {
    if (!pdo_fieldexists('weliam_indiana_cart', 'ip')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_cart') . ' ADD `ip` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_cart')) {
    if (!pdo_fieldexists('weliam_indiana_cart', 'ipaddress')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_cart') . ' ADD `ipaddress` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_cart')) {
    if (!pdo_fieldexists('weliam_indiana_cart', 'num')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_cart') . ' ADD `num` int(11) NOT NULL   COMMENT \'购买人次数\';');
    }
}
if (pdo_tableexists('weliam_indiana_category')) {
    if (!pdo_fieldexists('weliam_indiana_category', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_category') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_category')) {
    if (!pdo_fieldexists('weliam_indiana_category', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_category') . ' ADD `uniacid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'所属帐号\';');
    }
}
if (pdo_tableexists('weliam_indiana_category')) {
    if (!pdo_fieldexists('weliam_indiana_category', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_category') . ' ADD `name` varchar(50) NOT NULL   COMMENT \'分类名称\';');
    }
}
if (pdo_tableexists('weliam_indiana_category')) {
    if (!pdo_fieldexists('weliam_indiana_category', 'thumb')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_category') . ' ADD `thumb` varchar(255) NOT NULL   COMMENT \'分类图片\';');
    }
}
if (pdo_tableexists('weliam_indiana_category')) {
    if (!pdo_fieldexists('weliam_indiana_category', 'parentid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_category') . ' ADD `parentid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'上级分类ID,0为第一级\';');
    }
}
if (pdo_tableexists('weliam_indiana_category')) {
    if (!pdo_fieldexists('weliam_indiana_category', 'isrecommand')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_category') . ' ADD `isrecommand` int(10)   DEFAULT 0 COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_category')) {
    if (!pdo_fieldexists('weliam_indiana_category', 'description')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_category') . ' ADD `description` varchar(500) NOT NULL   COMMENT \'分类介绍\';');
    }
}
if (pdo_tableexists('weliam_indiana_category')) {
    if (!pdo_fieldexists('weliam_indiana_category', 'displayorder')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_category') . ' ADD `displayorder` tinyint(3) unsigned NOT NULL  DEFAULT 0 COMMENT \'排序\';');
    }
}
if (pdo_tableexists('weliam_indiana_category')) {
    if (!pdo_fieldexists('weliam_indiana_category', 'enabled')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_category') . ' ADD `enabled` tinyint(1) unsigned NOT NULL  DEFAULT 1 COMMENT \'是否开启\';');
    }
}
if (pdo_tableexists('weliam_indiana_comcode')) {
    if (!pdo_fieldexists('weliam_indiana_comcode', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_comcode') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_comcode')) {
    if (!pdo_fieldexists('weliam_indiana_comcode', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_comcode') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_comcode')) {
    if (!pdo_fieldexists('weliam_indiana_comcode', 'numa')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_comcode') . ' ADD `numa` varchar(20) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_comcode')) {
    if (!pdo_fieldexists('weliam_indiana_comcode', 'numb')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_comcode') . ' ADD `numb` varchar(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_comcode')) {
    if (!pdo_fieldexists('weliam_indiana_comcode', 'periods')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_comcode') . ' ADD `periods` varchar(50) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_comcode')) {
    if (!pdo_fieldexists('weliam_indiana_comcode', 'pid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_comcode') . ' ADD `pid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_comcode')) {
    if (!pdo_fieldexists('weliam_indiana_comcode', 'wincode')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_comcode') . ' ADD `wincode` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_comcode')) {
    if (!pdo_fieldexists('weliam_indiana_comcode', 'arecord')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_comcode') . ' ADD `arecord` longtext NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_comcode')) {
    if (!pdo_fieldexists('weliam_indiana_comcode', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_comcode') . ' ADD `createtime` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_consumerecord')) {
    if (!pdo_fieldexists('weliam_indiana_consumerecord', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_consumerecord') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_consumerecord')) {
    if (!pdo_fieldexists('weliam_indiana_consumerecord', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_consumerecord') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_consumerecord')) {
    if (!pdo_fieldexists('weliam_indiana_consumerecord', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_consumerecord') . ' ADD `openid` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_consumerecord')) {
    if (!pdo_fieldexists('weliam_indiana_consumerecord', 'num')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_consumerecord') . ' ADD `num` int(11) NOT NULL   COMMENT \'消费夺宝币数量\';');
    }
}
if (pdo_tableexists('weliam_indiana_consumerecord')) {
    if (!pdo_fieldexists('weliam_indiana_consumerecord', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_consumerecord') . ' ADD `status` int(2) NOT NULL   COMMENT \'0消费失败1消费成功\';');
    }
}
if (pdo_tableexists('weliam_indiana_consumerecord')) {
    if (!pdo_fieldexists('weliam_indiana_consumerecord', 'period_number')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_consumerecord') . ' ADD `period_number` varchar(145) NOT NULL   COMMENT \'期号\';');
    }
}
if (pdo_tableexists('weliam_indiana_consumerecord')) {
    if (!pdo_fieldexists('weliam_indiana_consumerecord', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_consumerecord') . ' ADD `createtime` varchar(145) NOT NULL   COMMENT \'消费时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_consumerecord')) {
    if (!pdo_fieldexists('weliam_indiana_consumerecord', 'ip')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_consumerecord') . ' ADD `ip` varchar(45) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_consumerecord')) {
    if (!pdo_fieldexists('weliam_indiana_consumerecord', 'ipaddress')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_consumerecord') . ' ADD `ipaddress` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'couponid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `couponid` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'merchantid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `merchantid` int(11) NOT NULL   COMMENT \'商家ID\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `uniacid` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'type')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `type` tinyint(4) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'title')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `title` varchar(30) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'couponsn')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `couponsn` varchar(50) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'description')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `description` text    COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'discount')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `discount` decimal(10,2) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'condition')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `condition` decimal(10,2) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'starttime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `starttime` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'endtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `endtime` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'limit')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `limit` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'dosage')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `dosage` int(11) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'amount')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `amount` int(11) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'thumb')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `thumb` varchar(500) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'credit')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `credit` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'credittype')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `credittype` varchar(20) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'module')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `module` varchar(30) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'use_module')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `use_module` tinyint(3) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon')) {
    if (!pdo_fieldexists('weliam_indiana_coupon', 'daylimit')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon') . ' ADD `daylimit` int(11) NOT NULL   COMMENT \'领取后几天有效\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'recid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `recid` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `uniacid` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'uid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `uid` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'grantmodule')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `grantmodule` varchar(50) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'granttime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `granttime` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'usemodule')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `usemodule` varchar(50) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'usetime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `usetime` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `status` tinyint(4) NOT NULL   COMMENT \'1未使用2已使用\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'operator')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `operator` varchar(30) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'remark')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `remark` varchar(300) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'couponid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `couponid` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'clerk_id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `clerk_id` int(10) unsigned NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'merchantid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `merchantid` int(11) NOT NULL   COMMENT \'商家ID\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'firstopenid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `firstopenid` varchar(145) NOT NULL   COMMENT \'拥有者opneid\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'secondopenid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `secondopenid` varchar(145) NOT NULL   COMMENT \'持有者openid\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'gettime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `gettime` varchar(45) NOT NULL   COMMENT \'生成时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'endtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `endtime` varchar(45) NOT NULL   COMMENT \'结束时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'couponnum')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `couponnum` int(11) NOT NULL   COMMENT \'优惠卷数量\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'coupon_number')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `coupon_number` varchar(145) NOT NULL   COMMENT \'优惠卷唯一编号\';');
    }
}
if (pdo_tableexists('weliam_indiana_coupon_record')) {
    if (!pdo_fieldexists('weliam_indiana_coupon_record', 'usedcouponnum')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_coupon_record') . ' ADD `usedcouponnum` int(11) NOT NULL   COMMENT \'已使用的优惠券数量\';');
    }
}
if (pdo_tableexists('weliam_indiana_goods_atlas')) {
    if (!pdo_fieldexists('weliam_indiana_goods_atlas', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goods_atlas') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'自增id\';');
    }
}
if (pdo_tableexists('weliam_indiana_goods_atlas')) {
    if (!pdo_fieldexists('weliam_indiana_goods_atlas', 'g_id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goods_atlas') . ' ADD `g_id` int(11) NOT NULL   COMMENT \'商品id\';');
    }
}
if (pdo_tableexists('weliam_indiana_goods_atlas')) {
    if (!pdo_fieldexists('weliam_indiana_goods_atlas', 'thumb')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goods_atlas') . ' ADD `thumb` varchar(145) NOT NULL   COMMENT \'图片路径\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'主键\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `uniacid` int(10) unsigned NOT NULL   COMMENT \'公众账号\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'merchant_id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `merchant_id` int(11) NOT NULL   COMMENT \'所属商家ID\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'title')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `title` varchar(100)    COMMENT \'商品标题\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'category_parentid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `category_parentid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'category_childid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `category_childid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'price')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `price` int(10)   DEFAULT 0 COMMENT \'金额\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'canyurenshu')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `canyurenshu` int(10) unsigned   DEFAULT 0 COMMENT \'已参与总人次数\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'periods')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `periods` smallint(6) unsigned   DEFAULT 0 COMMENT \'期数\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'maxperiods')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `maxperiods` smallint(5) unsigned   DEFAULT 1 COMMENT \' 最大期数\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'picarr')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `picarr` text    COMMENT \'商品图片\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'content')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `content` mediumtext    COMMENT \'商品详情\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `createtime` int(10) unsigned    COMMENT \'创建时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'pos')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `pos` tinyint(4) unsigned    COMMENT \'是否推荐\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `status` int(11) NOT NULL   COMMENT \'0:删除,1:下架, 2: 上架\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'isnew')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `isnew` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'ishot')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `ishot` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'jiexiao_time')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `jiexiao_time` int(11) NOT NULL   COMMENT \'多少分钟后揭晓（整数）\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'couponid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `couponid` int(11) NOT NULL   COMMENT \'优惠卷ID\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'init_money')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `init_money` int(11) NOT NULL   COMMENT \'几元专区\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'maxnum')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `maxnum` int(11) NOT NULL   COMMENT \'最大购买数量\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'sort')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `sort` int(11) NOT NULL   COMMENT \'排序\';');
    }
}
if (pdo_tableexists('weliam_indiana_goodslist')) {
    if (!pdo_fieldexists('weliam_indiana_goodslist', 'next_init_money')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_goodslist') . ' ADD `next_init_money` int(11) NOT NULL   COMMENT \'下期专区价格\';');
    }
}
if (pdo_tableexists('weliam_indiana_hexiao')) {
    if (!pdo_fieldexists('weliam_indiana_hexiao', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_hexiao') . ' ADD `id` int(11) unsigned NOT NULL auto_increment  COMMENT \'核销id\';');
    }
}
if (pdo_tableexists('weliam_indiana_hexiao')) {
    if (!pdo_fieldexists('weliam_indiana_hexiao', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_hexiao') . ' ADD `name` varchar(50) NOT NULL   COMMENT \'核销名称\';');
    }
}
if (pdo_tableexists('weliam_indiana_hexiao')) {
    if (!pdo_fieldexists('weliam_indiana_hexiao', 'discount')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_hexiao') . ' ADD `discount` decimal(10,2) NOT NULL   COMMENT \'核销金额\';');
    }
}
if (pdo_tableexists('weliam_indiana_hexiao')) {
    if (!pdo_fieldexists('weliam_indiana_hexiao', 'hexiaoperson')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_hexiao') . ' ADD `hexiaoperson` varchar(32) NOT NULL   COMMENT \'核销人\';');
    }
}
if (pdo_tableexists('weliam_indiana_hexiao')) {
    if (!pdo_fieldexists('weliam_indiana_hexiao', 'usedperson')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_hexiao') . ' ADD `usedperson` varchar(32) NOT NULL   COMMENT \'被核销人\';');
    }
}
if (pdo_tableexists('weliam_indiana_hexiao')) {
    if (!pdo_fieldexists('weliam_indiana_hexiao', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_hexiao') . ' ADD `createtime` int(11) NOT NULL   COMMENT \'核销时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_hexiao')) {
    if (!pdo_fieldexists('weliam_indiana_hexiao', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_hexiao') . ' ADD `uniacid` int(10)    COMMENT \'公众号id\';');
    }
}
if (pdo_tableexists('weliam_indiana_in')) {
    if (!pdo_fieldexists('weliam_indiana_in', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_in') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'唯一标识\';');
    }
}
if (pdo_tableexists('weliam_indiana_in')) {
    if (!pdo_fieldexists('weliam_indiana_in', 'data')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_in') . ' ADD `data` int(50) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_in')) {
    if (!pdo_fieldexists('weliam_indiana_in', 'type')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_in') . ' ADD `type` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_in')) {
    if (!pdo_fieldexists('weliam_indiana_in', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_in') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_invite')) {
    if (!pdo_fieldexists('weliam_indiana_invite', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_invite') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_invite')) {
    if (!pdo_fieldexists('weliam_indiana_invite', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_invite') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_invite')) {
    if (!pdo_fieldexists('weliam_indiana_invite', 'beinvited_openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_invite') . ' ADD `beinvited_openid` varchar(145) NOT NULL   COMMENT \'被邀请人家的openid\';');
    }
}
if (pdo_tableexists('weliam_indiana_invite')) {
    if (!pdo_fieldexists('weliam_indiana_invite', 'invite_openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_invite') . ' ADD `invite_openid` varchar(145) NOT NULL   COMMENT \'邀请人的openid\';');
    }
}
if (pdo_tableexists('weliam_indiana_invite')) {
    if (!pdo_fieldexists('weliam_indiana_invite', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_invite') . ' ADD `createtime` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_invite')) {
    if (!pdo_fieldexists('weliam_indiana_invite', 'credit1')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_invite') . ' ADD `credit1` int(11) NOT NULL   COMMENT \'已返利积分数\';');
    }
}
if (pdo_tableexists('weliam_indiana_invite')) {
    if (!pdo_fieldexists('weliam_indiana_invite', 'type')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_invite') . ' ADD `type` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'唯一标识\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'公众号id\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'period_number')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `period_number` varchar(145) NOT NULL   COMMENT \'商品期号\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'machine_num')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `machine_num` int(11) NOT NULL   COMMENT \'使用机器人个数\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `createtime` varchar(145) NOT NULL   COMMENT \'创建时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'start_time')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `start_time` varchar(145) NOT NULL   COMMENT \'开始时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'end_time')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `end_time` varchar(145) NOT NULL   COMMENT \'结束时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'next_time')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `next_time` varchar(145) NOT NULL   COMMENT \'机器人下个自动购买时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `status` int(2) NOT NULL   COMMENT \'机器人状态\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'max_num')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `max_num` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'timebucket')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `timebucket` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'is_followed')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `is_followed` int(2) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'goodsid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `goodsid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_machineset')) {
    if (!pdo_fieldexists('weliam_indiana_machineset', 'all_buy')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_machineset') . ' ADD `all_buy` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'mid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `mid` int(11) NOT NULL auto_increment  COMMENT \'用户id\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `openid` varchar(145) NOT NULL   COMMENT \'用户openid\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'公众号id\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'mobile')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `mobile` varchar(25) NOT NULL   COMMENT \'手机\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'email')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `email` varchar(145) NOT NULL   COMMENT \'电子邮件\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'credit1')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `credit1` decimal(10,2) NOT NULL   COMMENT \'积分\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'credit2')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `credit2` decimal(10,2) NOT NULL   COMMENT \'余额\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `createtime` varchar(145) NOT NULL   COMMENT \'创建时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'nickname')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `nickname` varchar(145) NOT NULL   COMMENT \'昵称\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'realname')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `realname` varchar(145) NOT NULL   COMMENT \'真实姓名\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'avatar')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `avatar` varchar(445) NOT NULL   COMMENT \'头像\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'gender')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `gender` int(2) NOT NULL   COMMENT \'性别\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'vip')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `vip` int(2) NOT NULL   COMMENT \'vip等级\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'address')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `address` varchar(225) NOT NULL   COMMENT \'地址\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'nationality')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `nationality` varchar(30) NOT NULL   COMMENT \'国家\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'resideprovince')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `resideprovince` varchar(30) NOT NULL   COMMENT \'省份\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'residecity')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `residecity` varchar(30) NOT NULL   COMMENT \'城市\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'residedist')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `residedist` varchar(30) NOT NULL   COMMENT \'地区\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'account')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `account` varchar(145) NOT NULL   COMMENT \'账号\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'password')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `password` varchar(145) NOT NULL   COMMENT \'密码\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `status` int(2) NOT NULL   COMMENT \'用户状态\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'type')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `type` int(2) NOT NULL   COMMENT \'用户类型\';');
    }
}
if (pdo_tableexists('weliam_indiana_member')) {
    if (!pdo_fieldexists('weliam_indiana_member', 'ip')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_member') . ' ADD `ip` varchar(35) NOT NULL   COMMENT \'固定IP\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `name` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'logo')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `logo` varchar(225) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'industry')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `industry` varchar(45) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'address')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `address` varchar(115) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'linkman_name')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `linkman_name` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'linkman_mobile')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `linkman_mobile` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `createtime` varchar(115) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'thumb')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `thumb` varchar(255) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_merchant')) {
    if (!pdo_fieldexists('weliam_indiana_merchant', 'detail')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_merchant') . ' ADD `detail` varchar(1222) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_navi')) {
    if (!pdo_fieldexists('weliam_indiana_navi', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_navi') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'唯一标识\';');
    }
}
if (pdo_tableexists('weliam_indiana_navi')) {
    if (!pdo_fieldexists('weliam_indiana_navi', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_navi') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_navi')) {
    if (!pdo_fieldexists('weliam_indiana_navi', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_navi') . ' ADD `name` varchar(50) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_navi')) {
    if (!pdo_fieldexists('weliam_indiana_navi', 'link')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_navi') . ' ADD `link` varchar(255) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_navi')) {
    if (!pdo_fieldexists('weliam_indiana_navi', 'thumb')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_navi') . ' ADD `thumb` varchar(255) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_navi')) {
    if (!pdo_fieldexists('weliam_indiana_navi', 'displayorder')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_navi') . ' ADD `displayorder` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_navi')) {
    if (!pdo_fieldexists('weliam_indiana_navi', 'enabled')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_navi') . ' ADD `enabled` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'goodsid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `goodsid` int(11) NOT NULL   COMMENT \'本期商品ID\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'periods')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `periods` int(11) NOT NULL   COMMENT \'该商品第几期\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'nickname')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `nickname` varchar(145) NOT NULL   COMMENT \'获奖人昵称\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'avatar')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `avatar` varchar(225) NOT NULL   COMMENT \'获奖人头像\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `openid` varchar(145) NOT NULL   COMMENT \'获奖人openid\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'partakes')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `partakes` int(11) NOT NULL   COMMENT \'获奖人参与次数\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'code')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `code` varchar(45) NOT NULL   COMMENT \'获奖码\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'endtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `endtime` varchar(145) NOT NULL   COMMENT \'本期结束时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'jiexiao_time')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `jiexiao_time` int(11) NOT NULL   COMMENT \'多少分钟后揭晓（整数）\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'confirmtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `confirmtime` int(11) NOT NULL   COMMENT \'确认地址时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'taketime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `taketime` int(11) NOT NULL   COMMENT \'确认收货时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'realname')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `realname` varchar(20) NOT NULL   COMMENT \'中奖人姓名\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'mobile')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `mobile` varchar(11) NOT NULL   COMMENT \'中奖人电话\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'address')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `address` varchar(200) NOT NULL   COMMENT \'中奖人地址\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'express')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `express` varchar(45) NOT NULL   COMMENT \'快递公司\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'expressn')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `expressn` varchar(145) NOT NULL   COMMENT \'快递单号\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'sendtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `sendtime` varchar(145) NOT NULL   COMMENT \'发货时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'codes')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `codes` longtext NOT NULL   COMMENT \'本期商品剩余夺宝码\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'shengyu_codes')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `shengyu_codes` int(11) NOT NULL   COMMENT \'剩余夺宝码个数\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'zong_codes')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `zong_codes` int(11) NOT NULL   COMMENT \'总个数\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'period_number')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `period_number` varchar(145) NOT NULL   COMMENT \'期号\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'canyurenshu')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `canyurenshu` int(11) NOT NULL   COMMENT \'参与人次数\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `status` int(4) NOT NULL   COMMENT \'1进行中，2待揭晓，3已揭晓，4待发货，5已发货，6已完成，7已晒单\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'scale')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `scale` int(11) NOT NULL   COMMENT \'参与比例\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `createtime` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'recordid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `recordid` int(11) NOT NULL   COMMENT \'购买记录id\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'allcodes')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `allcodes` longtext NOT NULL   COMMENT \'备份总夺宝码\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'comment')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `comment` varchar(2048) NOT NULL   COMMENT \'备注\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'machine_time')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `machine_time` varchar(145) NOT NULL   COMMENT \'机器人时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_period')) {
    if (!pdo_fieldexists('weliam_indiana_period', 'sort')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_period') . ' ADD `sort` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `openid` varchar(245) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'num')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `num` int(11) NOT NULL   COMMENT \'充值夺宝币个数\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `createtime` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'transid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `transid` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `status` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'paytype')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `paytype` int(2) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'ordersn')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `ordersn` varchar(145) NOT NULL   COMMENT \'订单号\';');
    }
}
if (pdo_tableexists('weliam_indiana_rechargerecord')) {
    if (!pdo_fieldexists('weliam_indiana_rechargerecord', 'type')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_rechargerecord') . ' ADD `type` int(2) NOT NULL   COMMENT \'0充值并消费1仅充值3积分兑换\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `openid` varchar(50) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `uniacid` int(10) unsigned NOT NULL   COMMENT \'公众账号\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'goodsid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `goodsid` int(10) unsigned NOT NULL   COMMENT \'商品ID\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'ordersn')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `ordersn` varchar(20) NOT NULL   COMMENT \'订单编号\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `status` smallint(4) NOT NULL  DEFAULT 0 COMMENT \'0未支付，1为已付款,2待发货3待收货4已完成\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'transid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `transid` varchar(30) NOT NULL   COMMENT \'微信订单号\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'count')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `count` int(10) unsigned NOT NULL   COMMENT \'商品数量\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'code')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `code` longtext    COMMENT \'获得的夺宝码\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `createtime` int(10) unsigned NOT NULL   COMMENT \'购买时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_record')) {
    if (!pdo_fieldexists('weliam_indiana_record', 'period_number')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_record') . ' ADD `period_number` varchar(145) NOT NULL   COMMENT \'期号\';');
    }
}
if (pdo_tableexists('weliam_indiana_saler')) {
    if (!pdo_fieldexists('weliam_indiana_saler', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_saler') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_saler')) {
    if (!pdo_fieldexists('weliam_indiana_saler', 'storeid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_saler') . ' ADD `storeid` varchar(225)    COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_saler')) {
    if (!pdo_fieldexists('weliam_indiana_saler', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_saler') . ' ADD `uniacid` int(11)   DEFAULT 0 COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_saler')) {
    if (!pdo_fieldexists('weliam_indiana_saler', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_saler') . ' ADD `openid` varchar(255)    COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_saler')) {
    if (!pdo_fieldexists('weliam_indiana_saler', 'nickname')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_saler') . ' ADD `nickname` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_saler')) {
    if (!pdo_fieldexists('weliam_indiana_saler', 'avatar')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_saler') . ' ADD `avatar` varchar(225) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_saler')) {
    if (!pdo_fieldexists('weliam_indiana_saler', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_saler') . ' ADD `status` tinyint(3)   DEFAULT 0 COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'goodsid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `goodsid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `openid` varchar(300) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'title')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `title` varchar(200) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'detail')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `detail` varchar(1000) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'period_number')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `period_number` varchar(45) NOT NULL   COMMENT \'期号\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `createtime` varchar(145) NOT NULL   COMMENT \'晒单时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `status` int(11) NOT NULL   COMMENT \'1待审核2通过3未通过\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'goodstitle')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `goodstitle` varchar(145) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_showprize')) {
    if (!pdo_fieldexists('weliam_indiana_showprize', 'thumbs')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_showprize') . ' ADD `thumbs` varchar(2048) NOT NULL   COMMENT \'图集\';');
    }
}
if (pdo_tableexists('weliam_indiana_syssetting')) {
    if (!pdo_fieldexists('weliam_indiana_syssetting', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_syssetting') . ' ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_syssetting')) {
    if (!pdo_fieldexists('weliam_indiana_syssetting', 'key')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_syssetting') . ' ADD `key` varchar(200) NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_syssetting')) {
    if (!pdo_fieldexists('weliam_indiana_syssetting', 'value')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_syssetting') . ' ADD `value` text NOT NULL   COMMENT \'\';');
    }
}
if (pdo_tableexists('weliam_indiana_withdraw')) {
    if (!pdo_fieldexists('weliam_indiana_withdraw', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_withdraw') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'唯一标识\';');
    }
}
if (pdo_tableexists('weliam_indiana_withdraw')) {
    if (!pdo_fieldexists('weliam_indiana_withdraw', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_withdraw') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'公众号id\';');
    }
}
if (pdo_tableexists('weliam_indiana_withdraw')) {
    if (!pdo_fieldexists('weliam_indiana_withdraw', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_withdraw') . ' ADD `openid` varchar(225) NOT NULL   COMMENT \'提现人\';');
    }
}
if (pdo_tableexists('weliam_indiana_withdraw')) {
    if (!pdo_fieldexists('weliam_indiana_withdraw', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_withdraw') . ' ADD `createtime` varchar(45) NOT NULL   COMMENT \'提现时间\';');
    }
}
if (pdo_tableexists('weliam_indiana_withdraw')) {
    if (!pdo_fieldexists('weliam_indiana_withdraw', 'number')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_withdraw') . ' ADD `number` int(11) NOT NULL   COMMENT \'金额\';');
    }
}
if (pdo_tableexists('weliam_indiana_withdraw')) {
    if (!pdo_fieldexists('weliam_indiana_withdraw', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_withdraw') . ' ADD `status` int(2) NOT NULL   COMMENT \'提现状态（1：等待提现；2：提现成功；3提现失败）\';');
    }
}
if (pdo_tableexists('weliam_indiana_withdraw')) {
    if (!pdo_fieldexists('weliam_indiana_withdraw', 'type')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_withdraw') . ' ADD `type` int(2) NOT NULL   COMMENT \'提现方式（1：微信；2支付宝；3京东钱包；4：百度钱包）\';');
    }
}
if (pdo_tableexists('weliam_indiana_withdraw')) {
    if (!pdo_fieldexists('weliam_indiana_withdraw', 'order_no')) {
        pdo_query('ALTER TABLE ' . tablename('weliam_indiana_withdraw') . ' ADD `order_no` varchar(225) NOT NULL   COMMENT \'提现订单号\';');
    }
}