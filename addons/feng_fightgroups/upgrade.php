<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_tg_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `openid` varchar(300) NOT NULL,
  `cname` varchar(30) NOT NULL COMMENT '收货人名称',
  `tel` varchar(20) NOT NULL COMMENT '手机号',
  `province` varchar(20) NOT NULL COMMENT '省',
  `city` varchar(20) NOT NULL COMMENT '市',
  `county` varchar(20) NOT NULL COMMENT '县(区)',
  `detailed_address` varchar(225) NOT NULL COMMENT '详细地址',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  `addtime` varchar(45) NOT NULL,
  `status` int(2) NOT NULL COMMENT '1为默认',
  `type` int(11) NOT NULL,
  `wlname` varchar(32) DEFAULT NULL,
  `wltel` varchar(32) DEFAULT NULL,
  `enterprise_name` varchar(255) NOT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(30) NOT NULL COMMENT '管理员名称',
  `password` varchar(20) NOT NULL COMMENT '管理员密码',
  `email` varchar(60) NOT NULL COMMENT '邮箱',
  `tel` varchar(20) NOT NULL COMMENT '手机号',
  `uniacid` int(10) DEFAULT NULL COMMENT '公众号id',
  `openid` varchar(100) DEFAULT NULL COMMENT '用户openid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `openid` (`openid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_arealimit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `arealimitname` varchar(56) NOT NULL,
  `areas` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
  `uniacid` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  `visible_level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `thumb` varchar(255) NOT NULL COMMENT '分类图片',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `isrecommand` int(10) DEFAULT '0',
  `description` varchar(500) NOT NULL COMMENT '分类介绍',
  `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启',
  `visible_level` int(11) NOT NULL,
  `open` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_collect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `openid` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(300) NOT NULL,
  `title` varchar(200) NOT NULL,
  `detail` varchar(1000) NOT NULL,
  `createtime` varchar(145) NOT NULL COMMENT '晒单时间',
  `status` int(11) NOT NULL COMMENT '1待审核2通过3未通过',
  `goodstitle` varchar(145) NOT NULL,
  `thumbs` varchar(2048) NOT NULL COMMENT '图集',
  `type` int(11) NOT NULL COMMENT '0:表示晒单；1：表示言论',
  `speechcount` int(11) NOT NULL COMMENT '评论条数',
  `count` int(11) NOT NULL COMMENT '被赞次数',
  `praise` text NOT NULL COMMENT '赞的人',
  `sid` int(11) DEFAULT NULL COMMENT '商家ID',
  `mid` int(11) DEFAULT NULL COMMENT '会员ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `coupon_template_id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `cash` varchar(20) NOT NULL,
  `is_at_least` tinyint(3) unsigned NOT NULL,
  `at_least` varchar(20) NOT NULL,
  `description` varchar(255) NOT NULL,
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `use_time` int(10) unsigned NOT NULL,
  `openid` varchar(100) NOT NULL,
  `uniacid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_coupon_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '优惠券名称',
  `value` varchar(50) NOT NULL COMMENT '最小面值',
  `value_to` varchar(50) NOT NULL COMMENT '最大面值',
  `is_random` tinyint(3) unsigned NOT NULL COMMENT '是否随机',
  `is_at_least` tinyint(3) unsigned NOT NULL COMMENT '是否存在最低消费',
  `at_least` varchar(20) NOT NULL COMMENT '最低消费',
  `is_sync_weixin` tinyint(11) unsigned NOT NULL,
  `user_level` tinyint(11) unsigned DEFAULT NULL,
  `quota` tinyint(10) unsigned NOT NULL COMMENT '领取限制',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `fans_tag` int(10) unsigned NOT NULL,
  `expire_notice` tinyint(4) unsigned NOT NULL,
  `is_share` tinyint(3) unsigned NOT NULL,
  `range_type` tinyint(3) unsigned NOT NULL,
  `is_forbid_preference` tinyint(3) unsigned NOT NULL,
  `description` varchar(255) NOT NULL COMMENT '描述',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `enable` tinyint(3) unsigned NOT NULL COMMENT '优惠券状态，1正常',
  `total` int(10) unsigned NOT NULL COMMENT '优惠券总量',
  `quantity_issue` int(10) unsigned NOT NULL,
  `quantity_used` int(10) unsigned NOT NULL COMMENT '已使用数量',
  `uid` int(10) unsigned NOT NULL,
  `uniacid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_credit1rechargerecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(245) NOT NULL,
  `num` varchar(100) NOT NULL COMMENT '充值金额',
  `createtime` varchar(145) NOT NULL,
  `transid` varchar(145) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0充值失败1充值成功',
  `paytype` int(2) NOT NULL,
  `orderno` varchar(145) NOT NULL COMMENT '订单号',
  `type` int(2) NOT NULL COMMENT '0充值并消费1仅充值3积分兑换',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_credit_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(245) NOT NULL,
  `num` varchar(30) NOT NULL,
  `createtime` varchar(145) NOT NULL,
  `transid` varchar(145) NOT NULL,
  `status` int(11) NOT NULL,
  `paytype` int(2) NOT NULL COMMENT '1微信2后台',
  `ordersn` varchar(145) NOT NULL,
  `type` int(2) NOT NULL COMMENT '1积分2余额',
  `remark` varchar(145) NOT NULL,
  `table` tinyint(4) DEFAULT NULL COMMENT '1微赞2tg',
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_delivery_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_id` int(10) unsigned NOT NULL,
  `province` varchar(12) NOT NULL,
  `city` varchar(12) NOT NULL,
  `district` varchar(12) NOT NULL,
  `first_weight` varchar(20) NOT NULL,
  `first_fee` varchar(20) NOT NULL,
  `additional_weight` varchar(20) NOT NULL,
  `additional_fee` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tid` (`template_id`),
  KEY `district` (`province`,`city`,`district`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_delivery_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `region` longtext NOT NULL,
  `data` longtext NOT NULL,
  `updatetime` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `uniacid` int(11) NOT NULL,
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_discuss` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自动排序',
  `openid` varchar(445) NOT NULL COMMENT '评论者openid',
  `content` varchar(225) NOT NULL COMMENT '评论类容',
  `parentid` int(11) NOT NULL COMMENT '晒单或者讨论id',
  `status` int(11) NOT NULL COMMENT '状态',
  `createtime` varchar(32) NOT NULL COMMENT '创建时间',
  `uniacid` int(11) NOT NULL COMMENT '公众号id',
  `commentid` int(11) DEFAULT NULL,
  `goodsid` int(11) DEFAULT NULL,
  `merchantid` int(11) DEFAULT '0',
  `star` int(11) DEFAULT '0',
  `orderid` int(11) DEFAULT '0',
  `storereply` varchar(445) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';
CREATE TABLE IF NOT EXISTS `ims_tg_dispatch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `dispatchname` varchar(50) DEFAULT '',
  `dispatchtype` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `firstprice` decimal(10,2) DEFAULT '0.00',
  `secondprice` decimal(10,2) DEFAULT '0.00',
  `firstweight` int(11) DEFAULT '0',
  `secondweight` int(11) DEFAULT '0',
  `express` varchar(250) DEFAULT '',
  `areas` text,
  `carriers` text,
  `enabled` int(11) DEFAULT '0',
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(145) DEFAULT NULL COMMENT '活动名称',
  `uniacid` int(11) NOT NULL,
  `goodsid` int(11) NOT NULL COMMENT '商品id',
  `starttime` varchar(145) DEFAULT NULL COMMENT '活动开启时间',
  `endtime` varchar(145) DEFAULT NULL COMMENT '活动结束时间',
  `gettime` int(11) DEFAULT NULL COMMENT '有效领取时间',
  `times` int(11) DEFAULT NULL COMMENT '领取次数',
  `sendnum` int(11) DEFAULT NULL COMMENT '赠送数量',
  `getnum` int(11) DEFAULT NULL COMMENT '领取数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `gname` varchar(225) NOT NULL COMMENT '商品名称',
  `fk_typeid` int(10) unsigned NOT NULL COMMENT '所属分类id',
  `gsn` varchar(50) NOT NULL COMMENT '商品货号',
  `gnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存',
  `groupnum` int(10) unsigned NOT NULL COMMENT '最低拼团人数',
  `mprice` decimal(10,2) NOT NULL,
  `gprice` decimal(10,2) NOT NULL COMMENT '团购价',
  `oprice` decimal(10,2) NOT NULL COMMENT '单买价',
  `freight` decimal(10,2) NOT NULL,
  `gdesc` longtext NOT NULL COMMENT '商品简介',
  `gimg` varchar(225) DEFAULT NULL COMMENT '商品图片路径',
  `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否上架',
  `salenum` int(10) unsigned NOT NULL COMMENT '销量',
  `ishot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热卖',
  `displayorder` int(11) NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '最后修改时间',
  `uniacid` int(10) NOT NULL COMMENT '公众号的id',
  `endtime` int(11) NOT NULL COMMENT '团购限时（小时数）',
  `yunfei_id` int(11) NOT NULL,
  `is_discount` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `is_hexiao` int(2) NOT NULL,
  `hexiao_id` text,
  `is_share` int(2) NOT NULL,
  `gdetaile` longtext NOT NULL,
  `isnew` int(11) NOT NULL,
  `isrecommand` int(11) NOT NULL,
  `isdiscount` int(11) NOT NULL,
  `hasoption` int(11) NOT NULL,
  `group_level` varchar(1000) NOT NULL,
  `group_level_status` int(11) NOT NULL,
  `merchantid` int(11) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_image` varchar(250) NOT NULL,
  `share_desc` varchar(200) NOT NULL,
  `one_limit` int(11) NOT NULL,
  `many_limit` int(11) NOT NULL,
  `firstdiscount` decimal(10,2) NOT NULL,
  `category_childid` int(11) NOT NULL,
  `category_parentid` int(11) NOT NULL,
  `pv` int(11) NOT NULL,
  `uv` int(11) NOT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `goodstab` varchar(32) DEFAULT NULL,
  `op_one_limit` int(11) NOT NULL,
  `first_free` int(11) NOT NULL,
  `give_coupon_id` int(11) NOT NULL,
  `give_gift_id` int(11) NOT NULL,
  `allsalenum` int(11) DEFAULT NULL,
  `falsenum` int(11) DEFAULT NULL,
  `paysuccess` text NOT NULL COMMENT '支付成功详情',
  `atlas` text NOT NULL COMMENT '图集',
  `g_type` int(2) NOT NULL DEFAULT '1' COMMENT '商品类型',
  `repeatjoin` int(11) NOT NULL,
  `visible_level` varchar(100) DEFAULT NULL,
  `goodscode` varchar(100) DEFAULT NULL,
  `category_parentid_top` int(11) NOT NULL,
  `redbag` text,
  `balance` int(11) DEFAULT NULL,
  `hexiaolimittime` varchar(145) DEFAULT NULL,
  `comment` int(11) DEFAULT NULL,
  `prize` int(11) DEFAULT '0',
  `share_group` int(11) DEFAULT NULL,
  `hexiaolimittimetype` int(11) DEFAULT NULL,
  `share_image_group` varchar(445) DEFAULT NULL,
  `share_title_group` varchar(445) DEFAULT NULL,
  `share_desc_group` text,
  `lacktimetip` decimal(10,2) DEFAULT '0.00' COMMENT '剩余小时',
  `is_own` int(11) DEFAULT '0',
  `noticetime` int(11) DEFAULT '0',
  `is_norefund` int(11) DEFAULT '0',
  `stockstatus` int(11) DEFAULT '0',
  `forcegroup` int(11) DEFAULT '0',
  `norefundnotice` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_goods_atlas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `g_id` int(11) NOT NULL COMMENT '商品id',
  `thumb` varchar(145) NOT NULL COMMENT '图片路径',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_goods_imgs` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `fk_gid` int(10) NOT NULL COMMENT '对应商品的id',
  `albumpath` varchar(225) NOT NULL COMMENT '图片路径',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_gid` (`fk_gid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_goods_option` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_goods_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goodsid` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `value` text,
  `displayorder` int(11) DEFAULT '0',
  `tagcontent` text,
  PRIMARY KEY (`id`),
  KEY `indx_goodsid` (`goodsid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_goods_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `cname` varchar(30) NOT NULL COMMENT '分类名称',
  `pid` int(10) DEFAULT NULL COMMENT '上级分类的id',
  `uniacid` int(10) DEFAULT NULL COMMENT '公众号的id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupnumber` varchar(115) NOT NULL COMMENT '团编号',
  `goodsid` int(11) NOT NULL COMMENT '商品ID',
  `goodsname` varchar(1024) NOT NULL COMMENT '商品名称',
  `groupstatus` int(11) NOT NULL COMMENT '团状态',
  `neednum` int(11) NOT NULL COMMENT '所需人数',
  `lacknum` int(11) NOT NULL COMMENT '缺少人数',
  `starttime` varchar(225) NOT NULL COMMENT '开团时间',
  `endtime` varchar(225) NOT NULL COMMENT '到期时间',
  `uniacid` int(11) NOT NULL,
  `merchantid` int(11) NOT NULL,
  `price` varchar(11) DEFAULT NULL,
  `successtime` varchar(100) NOT NULL,
  `lottery_id` int(11) NOT NULL,
  `iflottery` int(11) NOT NULL,
  `lottery_status` int(11) NOT NULL,
  `endnum` int(11) NOT NULL,
  `lacktimetip` decimal(10,2) DEFAULT '0.00' COMMENT '剩余小时',
  `iftip` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_helpbuy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(45) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_lottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gname` varchar(145) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1进行2未开始3已结束4暂停',
  `prize` varchar(445) DEFAULT NULL,
  `createtime` varchar(145) DEFAULT NULL,
  `fk_goodsid` int(11) DEFAULT NULL COMMENT '商品ID',
  `num` int(11) DEFAULT NULL COMMENT '奖品数量',
  `displayorder` int(11) DEFAULT NULL COMMENT '排序',
  `lprice` decimal(10,2) DEFAULT NULL COMMENT '抽奖价',
  `gprice` decimal(10,2) DEFAULT NULL COMMENT '团购价',
  `groupnum` int(11) DEFAULT NULL COMMENT '团人数',
  `starttime` varchar(145) DEFAULT NULL COMMENT '开始时间',
  `endtime` varchar(145) DEFAULT NULL COMMENT '结束时间',
  `dostatus` int(11) DEFAULT '0' COMMENT '1已抽奖',
  `one_limit` int(11) DEFAULT NULL COMMENT '1单人可购买多次2不可',
  `gdetaile` text COMMENT '图文详情',
  `gimg` varchar(145) DEFAULT NULL,
  `num2` int(11) DEFAULT NULL COMMENT '二等奖品数',
  `num3` int(11) DEFAULT NULL COMMENT '三等数量',
  `imgs` varchar(225) DEFAULT NULL COMMENT '图集',
  `gdesc` text COMMENT '规则简介',
  `nogetmessage` int(11) NOT NULL,
  `pattern` int(11) DEFAULT '0',
  `ceshi` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_marketing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `fk_goodsid` int(11) DEFAULT NULL COMMENT '外键goodsid',
  `type` int(11) DEFAULT NULL COMMENT '1满减2包邮3抵扣',
  `value` text COMMENT '设置的值',
  PRIMARY KEY (`id`),
  KEY `goodsidd` (`fk_goodsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品的营销';
CREATE TABLE IF NOT EXISTS `ims_tg_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id',
  `openid` varchar(100) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `tag` varchar(1000) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `uid` int(11) NOT NULL,
  `credit1` varchar(100) DEFAULT NULL,
  `credit2` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `realname` varchar(100) DEFAULT NULL,
  `appopenid` varchar(100) DEFAULT NULL,
  `unionid` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_merchant` (
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
  `salenum` int(11) NOT NULL COMMENT '商家销量',
  `open` int(11) NOT NULL COMMENT '是否分配商家权限',
  `uname` varchar(45) NOT NULL,
  `password` varchar(145) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `messageopenid` varchar(150) NOT NULL,
  `openid` varchar(150) NOT NULL,
  `goodsnum` int(11) NOT NULL,
  `percent` varchar(100) NOT NULL,
  `allsalenum` int(11) DEFAULT NULL,
  `falsenum` int(11) DEFAULT NULL,
  `tag` text,
  `lng` varchar(145) DEFAULT NULL,
  `lat` varchar(145) DEFAULT NULL,
  `kefuimg` varchar(225) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_merchant_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT '商家ID',
  `uniacid` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '操作员id',
  `amount` decimal(10,2) NOT NULL COMMENT '交易总金额',
  `updatetime` varchar(45) NOT NULL COMMENT '上次结算时间',
  `no_money` decimal(10,2) NOT NULL COMMENT '目前未结算金额',
  `no_money_doing` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_merchant_money_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `merchantid` int(11) DEFAULT NULL COMMENT '商家ID',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '变动金额',
  `createtime` varchar(145) DEFAULT NULL COMMENT '变动时间',
  `orderid` int(11) DEFAULT NULL COMMENT '订单ID',
  `type` int(11) DEFAULT NULL COMMENT '1支付成功2发货成功成为可结算金额3取消发货4商家结算5退款',
  `detail` text COMMENT '详情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商家金额记录';
CREATE TABLE IF NOT EXISTS `ims_tg_merchant_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL COMMENT '商家id',
  `money` varchar(45) NOT NULL COMMENT '本次结算金额',
  `uid` int(11) NOT NULL COMMENT '操作员id',
  `createtime` varchar(45) NOT NULL COMMENT '结算时间',
  `uniacid` int(11) NOT NULL,
  `orderno` varchar(100) DEFAULT NULL,
  `commission` varchar(100) DEFAULT NULL,
  `percent` varchar(100) DEFAULT NULL,
  `get_money` varchar(100) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `updatetime` varchar(145) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
  `uniacid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `link` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `enabled` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
  `uniacid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `enabled` int(11) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_oplog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `describe` varchar(225) DEFAULT NULL,
  `view_url` varchar(225) DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL COMMENT 'IP',
  `data` varchar(1024) DEFAULT NULL,
  `createtime` varchar(32) DEFAULT NULL,
  `user` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` varchar(45) NOT NULL COMMENT '公众号',
  `gnum` int(11) NOT NULL COMMENT '购买数量',
  `openid` varchar(45) NOT NULL COMMENT '用户名',
  `ptime` varchar(45) NOT NULL COMMENT '支付成功时间',
  `orderno` varchar(45) NOT NULL COMMENT '订单编号',
  `price` varchar(45) NOT NULL COMMENT '价格',
  `status` int(9) NOT NULL COMMENT '订单状态0未支1支付，2已发货，3完成订单，9取消订单',
  `addressid` int(11) NOT NULL COMMENT '地址id',
  `g_id` int(11) NOT NULL COMMENT '商品id',
  `tuan_id` int(11) NOT NULL COMMENT '团id',
  `is_tuan` int(2) NOT NULL COMMENT '是否为团1为团0为单人',
  `createtime` varchar(45) NOT NULL COMMENT '订单生成时间',
  `pay_type` int(4) NOT NULL COMMENT '支付方式',
  `starttime` varchar(45) NOT NULL COMMENT '开始时间',
  `endtime` int(45) NOT NULL COMMENT '结束时间（小时）',
  `tuan_first` int(11) NOT NULL COMMENT '团长',
  `express` varchar(50) DEFAULT NULL COMMENT '快递公司名称',
  `expresssn` varchar(50) DEFAULT NULL COMMENT '快递单号',
  `transid` varchar(50) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `success` int(11) NOT NULL,
  `addname` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `address` varchar(300) NOT NULL,
  `goodsprice` varchar(45) NOT NULL,
  `pay_price` varchar(45) NOT NULL,
  `freight` varchar(45) NOT NULL,
  `credits` int(11) NOT NULL,
  `is_usecard` int(11) NOT NULL,
  `is_hexiao` int(2) NOT NULL,
  `hexiaoma` varchar(50) NOT NULL,
  `veropenid` varchar(200) NOT NULL,
  `sendtime` varchar(115) NOT NULL,
  `gettime` varchar(115) NOT NULL,
  `addresstype` int(11) NOT NULL,
  `optionid` int(11) NOT NULL,
  `checkpay` int(11) NOT NULL,
  `merchantid` int(11) NOT NULL,
  `optionname` varchar(100) NOT NULL,
  `issettlement` int(11) NOT NULL,
  `message` text NOT NULL COMMENT '代付留言',
  `ordertype` int(11) NOT NULL,
  `othername` varchar(100) NOT NULL,
  `successtime` varchar(100) NOT NULL,
  `adminremark` varchar(100) NOT NULL,
  `discount_fee` varchar(100) NOT NULL,
  `first_fee` varchar(100) NOT NULL,
  `couponid` int(11) NOT NULL,
  `bdeltime` int(11) NOT NULL,
  `helpbuy_opneid` varchar(100) DEFAULT NULL,
  `giftid` int(11) NOT NULL,
  `getcouponid` int(11) NOT NULL,
  `storeid` int(11) NOT NULL,
  `first_free` int(11) NOT NULL,
  `lottery_status` int(11) NOT NULL,
  `lotteryid` int(11) NOT NULL,
  `marketing` text NOT NULL COMMENT '图集',
  `limitnotice` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `mid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_order_goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `fk_orderid` int(10) NOT NULL COMMENT '订单id',
  `fk_goodid` int(10) NOT NULL COMMENT '商品id',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fk_orderid` (`fk_orderid`),
  UNIQUE KEY `fk_goodid` (`fk_goodid`),
  UNIQUE KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tg_order_print` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `pid` int(3) NOT NULL,
  `oid` int(10) NOT NULL,
  `foid` varchar(50) NOT NULL,
  `status` int(3) NOT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `params` longtext NOT NULL,
  `html` longtext NOT NULL,
  `click_pv` varchar(10) NOT NULL,
  `click_uv` varchar(10) NOT NULL,
  `enter_pv` varchar(10) NOT NULL,
  `enter_uv` varchar(10) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_print` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `sid` int(10) NOT NULL,
  `name` varchar(45) NOT NULL,
  `print_no` varchar(50) NOT NULL,
  `key` varchar(50) NOT NULL,
  `member_code` varchar(50) NOT NULL,
  `print_nums` int(3) NOT NULL,
  `qrcode_link` varchar(100) NOT NULL,
  `status` int(3) NOT NULL,
  `mode` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_puv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(45) NOT NULL,
  `pv` varchar(20) DEFAULT NULL COMMENT '总浏览人次',
  `uv` varchar(50) NOT NULL COMMENT '总浏览人数',
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_puv_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` varchar(45) NOT NULL,
  `openid` varchar(145) NOT NULL,
  `goodsid` int(11) NOT NULL COMMENT '商品id',
  `createtime` varchar(120) DEFAULT NULL COMMENT '访问时间',
  `status` int(11) NOT NULL,
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_refund_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL COMMENT '1手机端2Web端3最后一人退款4部分退款',
  `goodsid` int(11) NOT NULL COMMENT '商品ID',
  `payfee` varchar(100) NOT NULL COMMENT '支付金额',
  `refundfee` varchar(100) NOT NULL COMMENT '退还金额',
  `transid` varchar(115) NOT NULL COMMENT '订单编号',
  `refund_id` varchar(115) NOT NULL COMMENT '微信退款单号',
  `refundername` varchar(100) NOT NULL COMMENT '退款人姓名',
  `refundermobile` varchar(100) NOT NULL COMMENT '退款人电话',
  `goodsname` varchar(100) NOT NULL COMMENT '商品名称',
  `createtime` varchar(45) NOT NULL COMMENT '退款时间',
  `status` int(11) NOT NULL COMMENT '0未成功1成功',
  `uniacid` int(11) NOT NULL,
  `orderid` varchar(45) NOT NULL,
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_rules` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `rulesname` varchar(40) NOT NULL COMMENT '规则名称',
  `rulesdetail` varchar(4000) DEFAULT NULL COMMENT '规则详情',
  `uniacid` int(10) NOT NULL COMMENT '公众号的id',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rulesname` (`rulesname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_saler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeid` varchar(225) DEFAULT '',
  `uniacid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '',
  `nickname` varchar(145) NOT NULL,
  `avatar` varchar(225) NOT NULL,
  `status` tinyint(3) DEFAULT '0',
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_storeid` (`storeid`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_scratch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL COMMENT '活动名称',
  `starttime` varchar(32) DEFAULT NULL COMMENT '开始时间',
  `endtime` varchar(32) DEFAULT NULL COMMENT '结束时间\n',
  `detail` varchar(145) DEFAULT NULL COMMENT '说明',
  `use_credits` varchar(32) DEFAULT NULL COMMENT '需花费积分',
  `get_credits` varchar(32) DEFAULT NULL COMMENT '得到积分',
  `join_times` int(11) DEFAULT NULL COMMENT '参与次数',
  `winning_rate` varchar(32) DEFAULT NULL COMMENT '中奖率',
  `prize` varchar(1024) DEFAULT NULL COMMENT '奖品',
  `uniacid` int(11) DEFAULT NULL,
  `only_others` int(11) DEFAULT NULL COMMENT '1为只送积分给未中奖人',
  `status` int(11) DEFAULT NULL COMMENT '1开启',
  `alert_logo` varchar(145) DEFAULT NULL COMMENT '弹出的抽奖提示图',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_scratch_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(145) NOT NULL COMMENT '参与人openid',
  `activity_id` int(11) NOT NULL COMMENT '活动id',
  `type` varchar(45) DEFAULT NULL COMMENT '活动类型',
  `status` int(11) DEFAULT NULL COMMENT '2待领取3已领取',
  `prize` varchar(445) DEFAULT NULL COMMENT '奖品详情',
  `createtime` varchar(145) DEFAULT NULL COMMENT '参与时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `key` varchar(200) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `displaytype` tinyint(3) unsigned NOT NULL,
  `content` text NOT NULL,
  `goodsid` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `merchantid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_spec_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `specid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `show` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`uniacid`),
  KEY `indx_specid` (`specid`),
  KEY `indx_show` (`show`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `storename` varchar(255) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `tel` varchar(255) DEFAULT '',
  `lat` varchar(255) DEFAULT '',
  `lng` varchar(255) DEFAULT '',
  `status` tinyint(3) DEFAULT '0',
  `createtime` varchar(45) NOT NULL,
  `merchantid` int(11) NOT NULL,
  `storehours` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `title` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tg_user_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `extend_title` varchar(50) NOT NULL,
  `extend_url` varchar(255) NOT NULL,
  `extend_icon` varchar(255) NOT NULL,
  `active_urls` text NOT NULL,
  `is_system` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tg_user_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `url` varchar(300) NOT NULL,
  `do` varchar(255) NOT NULL,
  `ac` varchar(32) DEFAULT NULL,
  `op` varchar(32) DEFAULT NULL,
  `ac_id` int(11) DEFAULT NULL,
  `do_id` int(6) unsigned NOT NULL,
  `remark` varchar(255) NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `flag` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`do_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merchantid` int(11) NOT NULL,
  `nodes` text NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_tg_users` (
  `id` int(10) NOT NULL COMMENT '主键',
  `username` varchar(30) NOT NULL COMMENT '用户名',
  `password` varchar(20) NOT NULL COMMENT '用户密码',
  `email` varchar(60) NOT NULL COMMENT '邮箱',
  `tel` varchar(20) NOT NULL COMMENT '电话',
  `uniacid` int(10) NOT NULL COMMENT '公众号id',
  `openid` varchar(100) NOT NULL COMMENT '用户openid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `uniacid` (`uniacid`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_tg_waittask` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `value` varchar(145) DEFAULT NULL,
  `key` varchar(145) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('tg_address',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('tg_address',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `openid` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('tg_address',  'cname')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `cname` varchar(30) NOT NULL COMMENT '收货人名称';");
}
if(!pdo_fieldexists('tg_address',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `tel` varchar(20) NOT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('tg_address',  'province')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `province` varchar(20) NOT NULL COMMENT '省';");
}
if(!pdo_fieldexists('tg_address',  'city')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `city` varchar(20) NOT NULL COMMENT '市';");
}
if(!pdo_fieldexists('tg_address',  'county')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `county` varchar(20) NOT NULL COMMENT '县(区)';");
}
if(!pdo_fieldexists('tg_address',  'detailed_address')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `detailed_address` varchar(225) NOT NULL COMMENT '详细地址';");
}
if(!pdo_fieldexists('tg_address',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('tg_address',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `addtime` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_address',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `status` int(2) NOT NULL COMMENT '1为默认';");
}
if(!pdo_fieldexists('tg_address',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `type` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_address',  'wlname')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `wlname` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_address',  'wltel')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `wltel` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_address',  'enterprise_name')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `enterprise_name` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_address',  'branch_name')) {
	pdo_query("ALTER TABLE ".tablename('tg_address')." ADD `branch_name` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_admin',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('tg_admin',  'username')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD `username` varchar(30) NOT NULL COMMENT '管理员名称';");
}
if(!pdo_fieldexists('tg_admin',  'password')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD `password` varchar(20) NOT NULL COMMENT '管理员密码';");
}
if(!pdo_fieldexists('tg_admin',  'email')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD `email` varchar(60) NOT NULL COMMENT '邮箱';");
}
if(!pdo_fieldexists('tg_admin',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD `tel` varchar(20) NOT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('tg_admin',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD `uniacid` int(10) DEFAULT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('tg_admin',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD `openid` varchar(100) DEFAULT NULL COMMENT '用户openid';");
}
if(!pdo_indexexists('tg_admin',  'username')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD UNIQUE KEY `username` (`username`);");
}
if(!pdo_indexexists('tg_admin',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD UNIQUE KEY `openid` (`openid`);");
}
if(!pdo_indexexists('tg_admin',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_admin')." ADD UNIQUE KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tg_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_adv',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_adv',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('tg_adv',  'link')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD `link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_adv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_adv',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('tg_adv',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_indexexists('tg_adv',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('tg_adv',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_adv')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('tg_arealimit',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_arealimit')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_arealimit',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_arealimit')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_arealimit',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('tg_arealimit')." ADD `enabled` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_arealimit',  'arealimitname')) {
	pdo_query("ALTER TABLE ".tablename('tg_arealimit')." ADD `arealimitname` varchar(56) NOT NULL;");
}
if(!pdo_fieldexists('tg_arealimit',  'areas')) {
	pdo_query("ALTER TABLE ".tablename('tg_arealimit')." ADD `areas` text NOT NULL;");
}
if(!pdo_fieldexists('tg_banner',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_banner')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标识';");
}
if(!pdo_fieldexists('tg_banner',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_banner')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_banner',  'link')) {
	pdo_query("ALTER TABLE ".tablename('tg_banner')." ADD `link` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_banner',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tg_banner')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_banner',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_banner')." ADD `displayorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_banner',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('tg_banner')." ADD `enabled` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_banner',  'visible_level')) {
	pdo_query("ALTER TABLE ".tablename('tg_banner')." ADD `visible_level` int(11) NOT NULL;");
}
if(!pdo_indexexists('tg_banner',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_banner')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('tg_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_category',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_category',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `name` varchar(50) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('tg_category',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `thumb` varchar(255) NOT NULL COMMENT '分类图片';");
}
if(!pdo_fieldexists('tg_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('tg_category',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `isrecommand` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `description` varchar(500) NOT NULL COMMENT '分类介绍';");
}
if(!pdo_fieldexists('tg_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('tg_category',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启';");
}
if(!pdo_fieldexists('tg_category',  'visible_level')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `visible_level` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_category',  'open')) {
	pdo_query("ALTER TABLE ".tablename('tg_category')." ADD `open` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_collect',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_collect')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_collect',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_collect')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_collect',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('tg_collect')." ADD `sid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_collect',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_collect')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tg_comment',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_comment',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `goodsid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_comment',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_comment',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `openid` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('tg_comment',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tg_comment',  'detail')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `detail` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('tg_comment',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `createtime` varchar(145) NOT NULL COMMENT '晒单时间';");
}
if(!pdo_fieldexists('tg_comment',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `status` int(11) NOT NULL COMMENT '1待审核2通过3未通过';");
}
if(!pdo_fieldexists('tg_comment',  'goodstitle')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `goodstitle` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_comment',  'thumbs')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `thumbs` varchar(2048) NOT NULL COMMENT '图集';");
}
if(!pdo_fieldexists('tg_comment',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `type` int(11) NOT NULL COMMENT '0:表示晒单；1：表示言论';");
}
if(!pdo_fieldexists('tg_comment',  'speechcount')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `speechcount` int(11) NOT NULL COMMENT '评论条数';");
}
if(!pdo_fieldexists('tg_comment',  'count')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `count` int(11) NOT NULL COMMENT '被赞次数';");
}
if(!pdo_fieldexists('tg_comment',  'praise')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `praise` text NOT NULL COMMENT '赞的人';");
}
if(!pdo_fieldexists('tg_comment',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `sid` int(11) DEFAULT NULL COMMENT '商家ID';");
}
if(!pdo_fieldexists('tg_comment',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('tg_comment')." ADD `mid` int(11) DEFAULT NULL COMMENT '会员ID';");
}
if(!pdo_fieldexists('tg_coupon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_coupon',  'coupon_template_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `coupon_template_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'cash')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `cash` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'is_at_least')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `is_at_least` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'at_least')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `at_least` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'description')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'start_time')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `start_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'end_time')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `end_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'use_time')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `use_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `openid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_coupon_template',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `name` varchar(50) NOT NULL COMMENT '优惠券名称';");
}
if(!pdo_fieldexists('tg_coupon_template',  'value')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `value` varchar(50) NOT NULL COMMENT '最小面值';");
}
if(!pdo_fieldexists('tg_coupon_template',  'value_to')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `value_to` varchar(50) NOT NULL COMMENT '最大面值';");
}
if(!pdo_fieldexists('tg_coupon_template',  'is_random')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `is_random` tinyint(3) unsigned NOT NULL COMMENT '是否随机';");
}
if(!pdo_fieldexists('tg_coupon_template',  'is_at_least')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `is_at_least` tinyint(3) unsigned NOT NULL COMMENT '是否存在最低消费';");
}
if(!pdo_fieldexists('tg_coupon_template',  'at_least')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `at_least` varchar(20) NOT NULL COMMENT '最低消费';");
}
if(!pdo_fieldexists('tg_coupon_template',  'is_sync_weixin')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `is_sync_weixin` tinyint(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'user_level')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `user_level` tinyint(11) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'quota')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `quota` tinyint(10) unsigned NOT NULL COMMENT '领取限制';");
}
if(!pdo_fieldexists('tg_coupon_template',  'start_time')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `start_time` int(10) unsigned NOT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('tg_coupon_template',  'end_time')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `end_time` int(10) unsigned NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('tg_coupon_template',  'fans_tag')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `fans_tag` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'expire_notice')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `expire_notice` tinyint(4) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'is_share')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `is_share` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'range_type')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `range_type` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'is_forbid_preference')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `is_forbid_preference` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'description')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `description` varchar(255) NOT NULL COMMENT '描述';");
}
if(!pdo_fieldexists('tg_coupon_template',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('tg_coupon_template',  'enable')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `enable` tinyint(3) unsigned NOT NULL COMMENT '优惠券状态，1正常';");
}
if(!pdo_fieldexists('tg_coupon_template',  'total')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `total` int(10) unsigned NOT NULL COMMENT '优惠券总量';");
}
if(!pdo_fieldexists('tg_coupon_template',  'quantity_issue')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `quantity_issue` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'quantity_used')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `quantity_used` int(10) unsigned NOT NULL COMMENT '已使用数量';");
}
if(!pdo_fieldexists('tg_coupon_template',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `uid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_coupon_template',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_coupon_template')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `openid` varchar(245) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'num')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `num` varchar(100) NOT NULL COMMENT '充值金额';");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `createtime` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `transid` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `status` int(11) NOT NULL COMMENT '0充值失败1充值成功';");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `paytype` int(2) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'orderno')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `orderno` varchar(145) NOT NULL COMMENT '订单号';");
}
if(!pdo_fieldexists('tg_credit1rechargerecord',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit1rechargerecord')." ADD `type` int(2) NOT NULL COMMENT '0充值并消费1仅充值3积分兑换';");
}
if(!pdo_fieldexists('tg_credit_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_credit_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `openid` varchar(245) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit_record',  'num')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `num` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `createtime` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit_record',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `transid` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit_record',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `paytype` int(2) NOT NULL COMMENT '1微信2后台';");
}
if(!pdo_fieldexists('tg_credit_record',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `ordersn` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit_record',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `type` int(2) NOT NULL COMMENT '1积分2余额';");
}
if(!pdo_fieldexists('tg_credit_record',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `remark` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_credit_record',  'table')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `table` tinyint(4) DEFAULT NULL COMMENT '1微赞2tg';");
}
if(!pdo_fieldexists('tg_credit_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('tg_credit_record')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_price',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_delivery_price',  'template_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD `template_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_price',  'province')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD `province` varchar(12) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_price',  'city')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD `city` varchar(12) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_price',  'district')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD `district` varchar(12) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_price',  'first_weight')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD `first_weight` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_price',  'first_fee')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD `first_fee` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_price',  'additional_weight')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD `additional_weight` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_price',  'additional_fee')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD `additional_fee` varchar(20) NOT NULL;");
}
if(!pdo_indexexists('tg_delivery_price',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD KEY `tid` (`template_id`);");
}
if(!pdo_indexexists('tg_delivery_price',  'district')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_price')." ADD KEY `district` (`province`,`city`,`district`);");
}
if(!pdo_fieldexists('tg_delivery_template',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_delivery_template',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_template',  'code')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `code` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_template',  'region')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `region` longtext NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_template',  'data')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `data` longtext NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_template',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `updatetime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_template',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `status` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_template',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_template',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_delivery_template',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_indexexists('tg_delivery_template',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_delivery_template')." ADD KEY `name` (`name`);");
}
if(!pdo_fieldexists('tg_discuss',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自动排序';");
}
if(!pdo_fieldexists('tg_discuss',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `openid` varchar(445) NOT NULL COMMENT '评论者openid';");
}
if(!pdo_fieldexists('tg_discuss',  'content')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `content` varchar(225) NOT NULL COMMENT '评论类容';");
}
if(!pdo_fieldexists('tg_discuss',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `parentid` int(11) NOT NULL COMMENT '晒单或者讨论id';");
}
if(!pdo_fieldexists('tg_discuss',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `status` int(11) NOT NULL COMMENT '状态';");
}
if(!pdo_fieldexists('tg_discuss',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `createtime` varchar(32) NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('tg_discuss',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `uniacid` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('tg_discuss',  'commentid')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `commentid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_discuss',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `goodsid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_discuss',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `merchantid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_discuss',  'star')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `star` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_discuss',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `orderid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_discuss',  'storereply')) {
	pdo_query("ALTER TABLE ".tablename('tg_discuss')." ADD `storereply` varchar(445) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_dispatch',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_dispatch',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_dispatch',  'dispatchname')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `dispatchname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('tg_dispatch',  'dispatchtype')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `dispatchtype` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_dispatch',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_dispatch',  'firstprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `firstprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('tg_dispatch',  'secondprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `secondprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('tg_dispatch',  'firstweight')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `firstweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_dispatch',  'secondweight')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `secondweight` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_dispatch',  'express')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `express` varchar(250) DEFAULT '';");
}
if(!pdo_fieldexists('tg_dispatch',  'areas')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `areas` text;");
}
if(!pdo_fieldexists('tg_dispatch',  'carriers')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `carriers` text;");
}
if(!pdo_fieldexists('tg_dispatch',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_dispatch',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_indexexists('tg_dispatch',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('tg_dispatch',  'idx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_dispatch')." ADD KEY `idx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('tg_gift',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_gift',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `name` varchar(145) DEFAULT NULL COMMENT '活动名称';");
}
if(!pdo_fieldexists('tg_gift',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_gift',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `goodsid` int(11) NOT NULL COMMENT '商品id';");
}
if(!pdo_fieldexists('tg_gift',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `starttime` varchar(145) DEFAULT NULL COMMENT '活动开启时间';");
}
if(!pdo_fieldexists('tg_gift',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `endtime` varchar(145) DEFAULT NULL COMMENT '活动结束时间';");
}
if(!pdo_fieldexists('tg_gift',  'gettime')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `gettime` int(11) DEFAULT NULL COMMENT '有效领取时间';");
}
if(!pdo_fieldexists('tg_gift',  'times')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `times` int(11) DEFAULT NULL COMMENT '领取次数';");
}
if(!pdo_fieldexists('tg_gift',  'sendnum')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `sendnum` int(11) DEFAULT NULL COMMENT '赠送数量';");
}
if(!pdo_fieldexists('tg_gift',  'getnum')) {
	pdo_query("ALTER TABLE ".tablename('tg_gift')." ADD `getnum` int(11) DEFAULT NULL COMMENT '领取数量';");
}
if(!pdo_fieldexists('tg_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('tg_goods',  'gname')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `gname` varchar(225) NOT NULL COMMENT '商品名称';");
}
if(!pdo_fieldexists('tg_goods',  'fk_typeid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `fk_typeid` int(10) unsigned NOT NULL COMMENT '所属分类id';");
}
if(!pdo_fieldexists('tg_goods',  'gsn')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `gsn` varchar(50) NOT NULL COMMENT '商品货号';");
}
if(!pdo_fieldexists('tg_goods',  'gnum')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `gnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存';");
}
if(!pdo_fieldexists('tg_goods',  'groupnum')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `groupnum` int(10) unsigned NOT NULL COMMENT '最低拼团人数';");
}
if(!pdo_fieldexists('tg_goods',  'mprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `mprice` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'gprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `gprice` decimal(10,2) NOT NULL COMMENT '团购价';");
}
if(!pdo_fieldexists('tg_goods',  'oprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `oprice` decimal(10,2) NOT NULL COMMENT '单买价';");
}
if(!pdo_fieldexists('tg_goods',  'freight')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `freight` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'gdesc')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `gdesc` longtext NOT NULL COMMENT '商品简介';");
}
if(!pdo_fieldexists('tg_goods',  'gimg')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `gimg` varchar(225) DEFAULT NULL COMMENT '商品图片路径';");
}
if(!pdo_fieldexists('tg_goods',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `isshow` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否上架';");
}
if(!pdo_fieldexists('tg_goods',  'salenum')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `salenum` int(10) unsigned NOT NULL COMMENT '销量';");
}
if(!pdo_fieldexists('tg_goods',  'ishot')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `ishot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热卖';");
}
if(!pdo_fieldexists('tg_goods',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `displayorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '最后修改时间';");
}
if(!pdo_fieldexists('tg_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号的id';");
}
if(!pdo_fieldexists('tg_goods',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `endtime` int(11) NOT NULL COMMENT '团购限时（小时数）';");
}
if(!pdo_fieldexists('tg_goods',  'yunfei_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `yunfei_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'is_discount')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `is_discount` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'credits')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `credits` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'is_hexiao')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `is_hexiao` int(2) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'hexiao_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `hexiao_id` text;");
}
if(!pdo_fieldexists('tg_goods',  'is_share')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `is_share` int(2) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'gdetaile')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `gdetaile` longtext NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'isnew')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `isnew` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'isrecommand')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `isrecommand` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'isdiscount')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `isdiscount` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'hasoption')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `hasoption` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'group_level')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `group_level` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'group_level_status')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `group_level_status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `share_title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'share_image')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `share_image` varchar(250) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `share_desc` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'one_limit')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `one_limit` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'many_limit')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `many_limit` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'firstdiscount')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `firstdiscount` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'category_childid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `category_childid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'category_parentid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `category_parentid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'pv')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `pv` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'uv')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `uv` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'unit')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `unit` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'goodstab')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `goodstab` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'op_one_limit')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `op_one_limit` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'first_free')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `first_free` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'give_coupon_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `give_coupon_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'give_gift_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `give_gift_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'allsalenum')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `allsalenum` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'falsenum')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `falsenum` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'paysuccess')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `paysuccess` text NOT NULL COMMENT '支付成功详情';");
}
if(!pdo_fieldexists('tg_goods',  'atlas')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `atlas` text NOT NULL COMMENT '图集';");
}
if(!pdo_fieldexists('tg_goods',  'g_type')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `g_type` int(2) NOT NULL DEFAULT '1' COMMENT '商品类型';");
}
if(!pdo_fieldexists('tg_goods',  'repeatjoin')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `repeatjoin` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'visible_level')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `visible_level` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'goodscode')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `goodscode` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'category_parentid_top')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `category_parentid_top` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'redbag')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `redbag` text;");
}
if(!pdo_fieldexists('tg_goods',  'balance')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `balance` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'hexiaolimittime')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `hexiaolimittime` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'comment')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `comment` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'prize')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `prize` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods',  'share_group')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `share_group` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'hexiaolimittimetype')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `hexiaolimittimetype` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'share_image_group')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `share_image_group` varchar(445) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'share_title_group')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `share_title_group` varchar(445) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_goods',  'share_desc_group')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `share_desc_group` text;");
}
if(!pdo_fieldexists('tg_goods',  'lacktimetip')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `lacktimetip` decimal(10,2) DEFAULT '0.00' COMMENT '剩余小时';");
}
if(!pdo_fieldexists('tg_goods',  'is_own')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `is_own` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods',  'noticetime')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `noticetime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods',  'is_norefund')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `is_norefund` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods',  'stockstatus')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `stockstatus` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods',  'forcegroup')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `forcegroup` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods',  'norefundnotice')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods')." ADD `norefundnotice` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods_atlas',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_atlas')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id';");
}
if(!pdo_fieldexists('tg_goods_atlas',  'g_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_atlas')." ADD `g_id` int(11) NOT NULL COMMENT '商品id';");
}
if(!pdo_fieldexists('tg_goods_atlas',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_atlas')." ADD `thumb` varchar(145) NOT NULL COMMENT '图片路径';");
}
if(!pdo_fieldexists('tg_goods_imgs',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_imgs')." ADD `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('tg_goods_imgs',  'fk_gid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_imgs')." ADD `fk_gid` int(10) NOT NULL COMMENT '对应商品的id';");
}
if(!pdo_fieldexists('tg_goods_imgs',  'albumpath')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_imgs')." ADD `albumpath` varchar(225) NOT NULL COMMENT '图片路径';");
}
if(!pdo_fieldexists('tg_goods_imgs',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_imgs')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号id';");
}
if(!pdo_indexexists('tg_goods_imgs',  'fk_gid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_imgs')." ADD UNIQUE KEY `fk_gid` (`fk_gid`);");
}
if(!pdo_indexexists('tg_goods_imgs',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_imgs')." ADD UNIQUE KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tg_goods_option',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_goods_option',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `goodsid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods_option',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('tg_goods_option',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `thumb` varchar(60) DEFAULT '';");
}
if(!pdo_fieldexists('tg_goods_option',  'productprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `productprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('tg_goods_option',  'marketprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `marketprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('tg_goods_option',  'costprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `costprice` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('tg_goods_option',  'stock')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `stock` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods_option',  'weight')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `weight` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('tg_goods_option',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods_option',  'specs')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD `specs` text;");
}
if(!pdo_indexexists('tg_goods_option',  'indx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD KEY `indx_goodsid` (`goodsid`);");
}
if(!pdo_indexexists('tg_goods_option',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_option')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('tg_goods_param',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_param')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_goods_param',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_param')." ADD `goodsid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods_param',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_param')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('tg_goods_param',  'value')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_param')." ADD `value` text;");
}
if(!pdo_fieldexists('tg_goods_param',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_param')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_goods_param',  'tagcontent')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_param')." ADD `tagcontent` text;");
}
if(!pdo_indexexists('tg_goods_param',  'indx_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_param')." ADD KEY `indx_goodsid` (`goodsid`);");
}
if(!pdo_indexexists('tg_goods_param',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_param')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('tg_goods_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_type')." ADD `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('tg_goods_type',  'cname')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_type')." ADD `cname` varchar(30) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('tg_goods_type',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_type')." ADD `pid` int(10) DEFAULT NULL COMMENT '上级分类的id';");
}
if(!pdo_fieldexists('tg_goods_type',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_type')." ADD `uniacid` int(10) DEFAULT NULL COMMENT '公众号的id';");
}
if(!pdo_indexexists('tg_goods_type',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_goods_type')." ADD UNIQUE KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tg_group',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_group',  'groupnumber')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `groupnumber` varchar(115) NOT NULL COMMENT '团编号';");
}
if(!pdo_fieldexists('tg_group',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `goodsid` int(11) NOT NULL COMMENT '商品ID';");
}
if(!pdo_fieldexists('tg_group',  'goodsname')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `goodsname` varchar(1024) NOT NULL COMMENT '商品名称';");
}
if(!pdo_fieldexists('tg_group',  'groupstatus')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `groupstatus` int(11) NOT NULL COMMENT '团状态';");
}
if(!pdo_fieldexists('tg_group',  'neednum')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `neednum` int(11) NOT NULL COMMENT '所需人数';");
}
if(!pdo_fieldexists('tg_group',  'lacknum')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `lacknum` int(11) NOT NULL COMMENT '缺少人数';");
}
if(!pdo_fieldexists('tg_group',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `starttime` varchar(225) NOT NULL COMMENT '开团时间';");
}
if(!pdo_fieldexists('tg_group',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `endtime` varchar(225) NOT NULL COMMENT '到期时间';");
}
if(!pdo_fieldexists('tg_group',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_group',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_group',  'price')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `price` varchar(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_group',  'successtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `successtime` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_group',  'lottery_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `lottery_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_group',  'iflottery')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `iflottery` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_group',  'lottery_status')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `lottery_status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_group',  'endnum')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `endnum` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_group',  'lacktimetip')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `lacktimetip` decimal(10,2) DEFAULT '0.00' COMMENT '剩余小时';");
}
if(!pdo_fieldexists('tg_group',  'iftip')) {
	pdo_query("ALTER TABLE ".tablename('tg_group')." ADD `iftip` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_helpbuy',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_helpbuy')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_helpbuy',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_helpbuy')." ADD `uniacid` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_helpbuy',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_helpbuy')." ADD `name` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_lottery',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_lottery',  'gname')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `gname` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_lottery',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_lottery',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `status` int(11) DEFAULT NULL COMMENT '1进行2未开始3已结束4暂停';");
}
if(!pdo_fieldexists('tg_lottery',  'prize')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `prize` varchar(445) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_lottery',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `createtime` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_lottery',  'fk_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `fk_goodsid` int(11) DEFAULT NULL COMMENT '商品ID';");
}
if(!pdo_fieldexists('tg_lottery',  'num')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `num` int(11) DEFAULT NULL COMMENT '奖品数量';");
}
if(!pdo_fieldexists('tg_lottery',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `displayorder` int(11) DEFAULT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('tg_lottery',  'lprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `lprice` decimal(10,2) DEFAULT NULL COMMENT '抽奖价';");
}
if(!pdo_fieldexists('tg_lottery',  'gprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `gprice` decimal(10,2) DEFAULT NULL COMMENT '团购价';");
}
if(!pdo_fieldexists('tg_lottery',  'groupnum')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `groupnum` int(11) DEFAULT NULL COMMENT '团人数';");
}
if(!pdo_fieldexists('tg_lottery',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `starttime` varchar(145) DEFAULT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('tg_lottery',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `endtime` varchar(145) DEFAULT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('tg_lottery',  'dostatus')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `dostatus` int(11) DEFAULT '0' COMMENT '1已抽奖';");
}
if(!pdo_fieldexists('tg_lottery',  'one_limit')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `one_limit` int(11) DEFAULT NULL COMMENT '1单人可购买多次2不可';");
}
if(!pdo_fieldexists('tg_lottery',  'gdetaile')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `gdetaile` text COMMENT '图文详情';");
}
if(!pdo_fieldexists('tg_lottery',  'gimg')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `gimg` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_lottery',  'num2')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `num2` int(11) DEFAULT NULL COMMENT '二等奖品数';");
}
if(!pdo_fieldexists('tg_lottery',  'num3')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `num3` int(11) DEFAULT NULL COMMENT '三等数量';");
}
if(!pdo_fieldexists('tg_lottery',  'imgs')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `imgs` varchar(225) DEFAULT NULL COMMENT '图集';");
}
if(!pdo_fieldexists('tg_lottery',  'gdesc')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `gdesc` text COMMENT '规则简介';");
}
if(!pdo_fieldexists('tg_lottery',  'nogetmessage')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `nogetmessage` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_lottery',  'pattern')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `pattern` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_lottery',  'ceshi')) {
	pdo_query("ALTER TABLE ".tablename('tg_lottery')." ADD `ceshi` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_marketing',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_marketing')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_marketing',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_marketing')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_marketing',  'fk_goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_marketing')." ADD `fk_goodsid` int(11) DEFAULT NULL COMMENT '外键goodsid';");
}
if(!pdo_fieldexists('tg_marketing',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_marketing')." ADD `type` int(11) DEFAULT NULL COMMENT '1满减2包邮3抵扣';");
}
if(!pdo_fieldexists('tg_marketing',  'value')) {
	pdo_query("ALTER TABLE ".tablename('tg_marketing')." ADD `value` text COMMENT '设置的值';");
}
if(!pdo_indexexists('tg_marketing',  'goodsidd')) {
	pdo_query("ALTER TABLE ".tablename('tg_marketing')." ADD KEY `goodsidd` (`fk_goodsid`);");
}
if(!pdo_fieldexists('tg_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号id';");
}
if(!pdo_fieldexists('tg_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `openid` varchar(100) NOT NULL COMMENT '微信会员openID';");
}
if(!pdo_fieldexists('tg_member',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `nickname` varchar(50) NOT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('tg_member',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `avatar` varchar(255) NOT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('tg_member',  'tag')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `tag` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('tg_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `mobile` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tg_member',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_member',  'credit1')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `credit1` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_member',  'credit2')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `credit2` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_member',  'address')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `address` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_member',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `realname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_member',  'appopenid')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `appopenid` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_member',  'unionid')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD `unionid` varchar(100) DEFAULT NULL;");
}
if(!pdo_indexexists('tg_member',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_member')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tg_merchant',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_merchant',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `name` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `logo` varchar(225) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'industry')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `industry` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'address')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `address` varchar(115) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'linkman_name')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `linkman_name` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'linkman_mobile')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `linkman_mobile` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `createtime` varchar(115) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'detail')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `detail` varchar(1222) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'salenum')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `salenum` int(11) NOT NULL COMMENT '商家销量';");
}
if(!pdo_fieldexists('tg_merchant',  'open')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `open` int(11) NOT NULL COMMENT '是否分配商家权限';");
}
if(!pdo_fieldexists('tg_merchant',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `uname` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'password')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `password` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `uid` int(11) NOT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('tg_merchant',  'messageopenid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `messageopenid` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `openid` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'goodsnum')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `goodsnum` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'percent')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `percent` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'allsalenum')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `allsalenum` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'falsenum')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `falsenum` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'tag')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `tag` text;");
}
if(!pdo_fieldexists('tg_merchant',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `lng` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `lat` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant',  'kefuimg')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant')." ADD `kefuimg` varchar(225) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant_account',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_account')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_merchant_account',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_account')." ADD `merchantid` int(11) NOT NULL COMMENT '商家ID';");
}
if(!pdo_fieldexists('tg_merchant_account',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_account')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant_account',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_account')." ADD `uid` int(11) NOT NULL COMMENT '操作员id';");
}
if(!pdo_fieldexists('tg_merchant_account',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_account')." ADD `amount` decimal(10,2) NOT NULL COMMENT '交易总金额';");
}
if(!pdo_fieldexists('tg_merchant_account',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_account')." ADD `updatetime` varchar(45) NOT NULL COMMENT '上次结算时间';");
}
if(!pdo_fieldexists('tg_merchant_account',  'no_money')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_account')." ADD `no_money` decimal(10,2) NOT NULL COMMENT '目前未结算金额';");
}
if(!pdo_fieldexists('tg_merchant_account',  'no_money_doing')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_account')." ADD `no_money_doing` decimal(10,2) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant_money_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_money_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_merchant_money_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_money_record')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant_money_record',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_money_record')." ADD `merchantid` int(11) DEFAULT NULL COMMENT '商家ID';");
}
if(!pdo_fieldexists('tg_merchant_money_record',  'money')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_money_record')." ADD `money` decimal(10,2) DEFAULT '0.00' COMMENT '变动金额';");
}
if(!pdo_fieldexists('tg_merchant_money_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_money_record')." ADD `createtime` varchar(145) DEFAULT NULL COMMENT '变动时间';");
}
if(!pdo_fieldexists('tg_merchant_money_record',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_money_record')." ADD `orderid` int(11) DEFAULT NULL COMMENT '订单ID';");
}
if(!pdo_fieldexists('tg_merchant_money_record',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_money_record')." ADD `type` int(11) DEFAULT NULL COMMENT '1支付成功2发货成功成为可结算金额3取消发货4商家结算5退款';");
}
if(!pdo_fieldexists('tg_merchant_money_record',  'detail')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_money_record')." ADD `detail` text COMMENT '详情';");
}
if(!pdo_fieldexists('tg_merchant_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_merchant_record',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `merchantid` int(11) NOT NULL COMMENT '商家id';");
}
if(!pdo_fieldexists('tg_merchant_record',  'money')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `money` varchar(45) NOT NULL COMMENT '本次结算金额';");
}
if(!pdo_fieldexists('tg_merchant_record',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `uid` int(11) NOT NULL COMMENT '操作员id';");
}
if(!pdo_fieldexists('tg_merchant_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `createtime` varchar(45) NOT NULL COMMENT '结算时间';");
}
if(!pdo_fieldexists('tg_merchant_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_merchant_record',  'orderno')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `orderno` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant_record',  'commission')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `commission` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant_record',  'percent')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `percent` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant_record',  'get_money')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `get_money` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant_record',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `type` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant_record',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `updatetime` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_merchant_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_merchant_record')." ADD `status` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_nav',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_nav')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标识';");
}
if(!pdo_fieldexists('tg_nav',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_nav')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_nav',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_nav')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_nav',  'link')) {
	pdo_query("ALTER TABLE ".tablename('tg_nav')." ADD `link` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_nav',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tg_nav')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_nav',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_nav')." ADD `displayorder` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_nav',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('tg_nav')." ADD `enabled` int(11) NOT NULL;");
}
if(!pdo_indexexists('tg_nav',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_nav')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('tg_notice',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_notice')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '唯一标识';");
}
if(!pdo_fieldexists('tg_notice',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_notice')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_notice',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tg_notice')." ADD `title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_notice',  'content')) {
	pdo_query("ALTER TABLE ".tablename('tg_notice')." ADD `content` text;");
}
if(!pdo_fieldexists('tg_notice',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('tg_notice')." ADD `enabled` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_notice',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_notice')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_oplog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_oplog')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_oplog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_oplog')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_oplog',  'describe')) {
	pdo_query("ALTER TABLE ".tablename('tg_oplog')." ADD `describe` varchar(225) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_oplog',  'view_url')) {
	pdo_query("ALTER TABLE ".tablename('tg_oplog')." ADD `view_url` varchar(225) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_oplog',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('tg_oplog')." ADD `ip` varchar(32) DEFAULT NULL COMMENT 'IP';");
}
if(!pdo_fieldexists('tg_oplog',  'data')) {
	pdo_query("ALTER TABLE ".tablename('tg_oplog')." ADD `data` varchar(1024) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_oplog',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_oplog')." ADD `createtime` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_oplog',  'user')) {
	pdo_query("ALTER TABLE ".tablename('tg_oplog')." ADD `user` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('tg_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `uniacid` varchar(45) NOT NULL COMMENT '公众号';");
}
if(!pdo_fieldexists('tg_order',  'gnum')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `gnum` int(11) NOT NULL COMMENT '购买数量';");
}
if(!pdo_fieldexists('tg_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `openid` varchar(45) NOT NULL COMMENT '用户名';");
}
if(!pdo_fieldexists('tg_order',  'ptime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `ptime` varchar(45) NOT NULL COMMENT '支付成功时间';");
}
if(!pdo_fieldexists('tg_order',  'orderno')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `orderno` varchar(45) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('tg_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `price` varchar(45) NOT NULL COMMENT '价格';");
}
if(!pdo_fieldexists('tg_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `status` int(9) NOT NULL COMMENT '订单状态0未支1支付，2已发货，3完成订单，9取消订单';");
}
if(!pdo_fieldexists('tg_order',  'addressid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `addressid` int(11) NOT NULL COMMENT '地址id';");
}
if(!pdo_fieldexists('tg_order',  'g_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `g_id` int(11) NOT NULL COMMENT '商品id';");
}
if(!pdo_fieldexists('tg_order',  'tuan_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `tuan_id` int(11) NOT NULL COMMENT '团id';");
}
if(!pdo_fieldexists('tg_order',  'is_tuan')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `is_tuan` int(2) NOT NULL COMMENT '是否为团1为团0为单人';");
}
if(!pdo_fieldexists('tg_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `createtime` varchar(45) NOT NULL COMMENT '订单生成时间';");
}
if(!pdo_fieldexists('tg_order',  'pay_type')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `pay_type` int(4) NOT NULL COMMENT '支付方式';");
}
if(!pdo_fieldexists('tg_order',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `starttime` varchar(45) NOT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('tg_order',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `endtime` int(45) NOT NULL COMMENT '结束时间（小时）';");
}
if(!pdo_fieldexists('tg_order',  'tuan_first')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `tuan_first` int(11) NOT NULL COMMENT '团长';");
}
if(!pdo_fieldexists('tg_order',  'express')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `express` varchar(50) DEFAULT NULL COMMENT '快递公司名称';");
}
if(!pdo_fieldexists('tg_order',  'expresssn')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `expresssn` varchar(50) DEFAULT NULL COMMENT '快递单号';");
}
if(!pdo_fieldexists('tg_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `transid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `remark` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'success')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `success` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'addname')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `addname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `mobile` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `address` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'goodsprice')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `goodsprice` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'pay_price')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `pay_price` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'freight')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `freight` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'credits')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `credits` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'is_usecard')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `is_usecard` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'is_hexiao')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `is_hexiao` int(2) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'hexiaoma')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `hexiaoma` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'veropenid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `veropenid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'sendtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `sendtime` varchar(115) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'gettime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `gettime` varchar(115) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'addresstype')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `addresstype` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'optionid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `optionid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'checkpay')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `checkpay` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'optionname')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `optionname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'issettlement')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `issettlement` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'message')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `message` text NOT NULL COMMENT '代付留言';");
}
if(!pdo_fieldexists('tg_order',  'ordertype')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `ordertype` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'othername')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `othername` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'successtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `successtime` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'adminremark')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `adminremark` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'discount_fee')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `discount_fee` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'first_fee')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `first_fee` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'couponid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `couponid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'bdeltime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `bdeltime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'helpbuy_opneid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `helpbuy_opneid` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_order',  'giftid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `giftid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'getcouponid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `getcouponid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `storeid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'first_free')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `first_free` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'lottery_status')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `lottery_status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'lotteryid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `lotteryid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_order',  'marketing')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `marketing` text NOT NULL COMMENT '图集';");
}
if(!pdo_fieldexists('tg_order',  'limitnotice')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `limitnotice` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `uid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_order',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order')." ADD `mid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_order_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_goods')." ADD `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('tg_order_goods',  'fk_orderid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_goods')." ADD `fk_orderid` int(10) NOT NULL COMMENT '订单id';");
}
if(!pdo_fieldexists('tg_order_goods',  'fk_goodid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_goods')." ADD `fk_goodid` int(10) NOT NULL COMMENT '商品id';");
}
if(!pdo_fieldexists('tg_order_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_goods')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号id';");
}
if(!pdo_indexexists('tg_order_goods',  'fk_orderid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_goods')." ADD UNIQUE KEY `fk_orderid` (`fk_orderid`);");
}
if(!pdo_indexexists('tg_order_goods',  'fk_goodid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_goods')." ADD UNIQUE KEY `fk_goodid` (`fk_goodid`);");
}
if(!pdo_indexexists('tg_order_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_goods')." ADD UNIQUE KEY `uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tg_order_print',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_print')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_order_print',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_print')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_order_print',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_print')." ADD `sid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_order_print',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_print')." ADD `pid` int(3) NOT NULL;");
}
if(!pdo_fieldexists('tg_order_print',  'oid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_print')." ADD `oid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_order_print',  'foid')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_print')." ADD `foid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_order_print',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_print')." ADD `status` int(3) NOT NULL;");
}
if(!pdo_fieldexists('tg_order_print',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_order_print')." ADD `addtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_page',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'description')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'params')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `params` longtext NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'html')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `html` longtext NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'click_pv')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `click_pv` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'click_uv')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `click_uv` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'enter_pv')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `enter_pv` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'enter_uv')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `enter_uv` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `type` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_page',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_page')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_print',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `sid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `name` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'print_no')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `print_no` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'key')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `key` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'member_code')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `member_code` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'print_nums')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `print_nums` int(3) NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'qrcode_link')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `qrcode_link` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `status` int(3) NOT NULL;");
}
if(!pdo_fieldexists('tg_print',  'mode')) {
	pdo_query("ALTER TABLE ".tablename('tg_print')." ADD `mode` int(3) NOT NULL;");
}
if(!pdo_fieldexists('tg_puv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_puv',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv')." ADD `uniacid` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_puv',  'pv')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv')." ADD `pv` varchar(20) DEFAULT NULL COMMENT '总浏览人次';");
}
if(!pdo_fieldexists('tg_puv',  'uv')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv')." ADD `uv` varchar(50) NOT NULL COMMENT '总浏览人数';");
}
if(!pdo_fieldexists('tg_puv',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_puv_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_puv_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv_record')." ADD `uniacid` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_puv_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv_record')." ADD `openid` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_puv_record',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv_record')." ADD `goodsid` int(11) NOT NULL COMMENT '商品id';");
}
if(!pdo_fieldexists('tg_puv_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv_record')." ADD `createtime` varchar(120) DEFAULT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('tg_puv_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv_record')." ADD `status` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_puv_record',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_puv_record')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_refund_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_refund_record',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `type` int(11) NOT NULL COMMENT '1手机端2Web端3最后一人退款4部分退款';");
}
if(!pdo_fieldexists('tg_refund_record',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `goodsid` int(11) NOT NULL COMMENT '商品ID';");
}
if(!pdo_fieldexists('tg_refund_record',  'payfee')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `payfee` varchar(100) NOT NULL COMMENT '支付金额';");
}
if(!pdo_fieldexists('tg_refund_record',  'refundfee')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `refundfee` varchar(100) NOT NULL COMMENT '退还金额';");
}
if(!pdo_fieldexists('tg_refund_record',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `transid` varchar(115) NOT NULL COMMENT '订单编号';");
}
if(!pdo_fieldexists('tg_refund_record',  'refund_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `refund_id` varchar(115) NOT NULL COMMENT '微信退款单号';");
}
if(!pdo_fieldexists('tg_refund_record',  'refundername')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `refundername` varchar(100) NOT NULL COMMENT '退款人姓名';");
}
if(!pdo_fieldexists('tg_refund_record',  'refundermobile')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `refundermobile` varchar(100) NOT NULL COMMENT '退款人电话';");
}
if(!pdo_fieldexists('tg_refund_record',  'goodsname')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `goodsname` varchar(100) NOT NULL COMMENT '商品名称';");
}
if(!pdo_fieldexists('tg_refund_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `createtime` varchar(45) NOT NULL COMMENT '退款时间';");
}
if(!pdo_fieldexists('tg_refund_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `status` int(11) NOT NULL COMMENT '0未成功1成功';");
}
if(!pdo_fieldexists('tg_refund_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_refund_record',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `orderid` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_refund_record',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_refund_record')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_rules',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_rules')." ADD `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('tg_rules',  'rulesname')) {
	pdo_query("ALTER TABLE ".tablename('tg_rules')." ADD `rulesname` varchar(40) NOT NULL COMMENT '规则名称';");
}
if(!pdo_fieldexists('tg_rules',  'rulesdetail')) {
	pdo_query("ALTER TABLE ".tablename('tg_rules')." ADD `rulesdetail` varchar(4000) DEFAULT NULL COMMENT '规则详情';");
}
if(!pdo_fieldexists('tg_rules',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_rules')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号的id';");
}
if(!pdo_indexexists('tg_rules',  'rulesname')) {
	pdo_query("ALTER TABLE ".tablename('tg_rules')." ADD UNIQUE KEY `rulesname` (`rulesname`);");
}
if(!pdo_fieldexists('tg_saler',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_saler',  'storeid')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD `storeid` varchar(225) DEFAULT '';");
}
if(!pdo_fieldexists('tg_saler',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_saler',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD `openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_saler',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD `nickname` varchar(145) NOT NULL;");
}
if(!pdo_fieldexists('tg_saler',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD `avatar` varchar(225) NOT NULL;");
}
if(!pdo_fieldexists('tg_saler',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_saler',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_indexexists('tg_saler',  'idx_storeid')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD KEY `idx_storeid` (`storeid`);");
}
if(!pdo_indexexists('tg_saler',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_saler')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('tg_scratch',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_scratch',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `name` varchar(32) DEFAULT NULL COMMENT '活动名称';");
}
if(!pdo_fieldexists('tg_scratch',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `starttime` varchar(32) DEFAULT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('tg_scratch',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `endtime` varchar(32) DEFAULT NULL COMMENT '结束时间\n';");
}
if(!pdo_fieldexists('tg_scratch',  'detail')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `detail` varchar(145) DEFAULT NULL COMMENT '说明';");
}
if(!pdo_fieldexists('tg_scratch',  'use_credits')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `use_credits` varchar(32) DEFAULT NULL COMMENT '需花费积分';");
}
if(!pdo_fieldexists('tg_scratch',  'get_credits')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `get_credits` varchar(32) DEFAULT NULL COMMENT '得到积分';");
}
if(!pdo_fieldexists('tg_scratch',  'join_times')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `join_times` int(11) DEFAULT NULL COMMENT '参与次数';");
}
if(!pdo_fieldexists('tg_scratch',  'winning_rate')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `winning_rate` varchar(32) DEFAULT NULL COMMENT '中奖率';");
}
if(!pdo_fieldexists('tg_scratch',  'prize')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `prize` varchar(1024) DEFAULT NULL COMMENT '奖品';");
}
if(!pdo_fieldexists('tg_scratch',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_scratch',  'only_others')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `only_others` int(11) DEFAULT NULL COMMENT '1为只送积分给未中奖人';");
}
if(!pdo_fieldexists('tg_scratch',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `status` int(11) DEFAULT NULL COMMENT '1开启';");
}
if(!pdo_fieldexists('tg_scratch',  'alert_logo')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch')." ADD `alert_logo` varchar(145) DEFAULT NULL COMMENT '弹出的抽奖提示图';");
}
if(!pdo_fieldexists('tg_scratch_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_scratch_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch_record')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_scratch_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch_record')." ADD `openid` varchar(145) NOT NULL COMMENT '参与人openid';");
}
if(!pdo_fieldexists('tg_scratch_record',  'activity_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch_record')." ADD `activity_id` int(11) NOT NULL COMMENT '活动id';");
}
if(!pdo_fieldexists('tg_scratch_record',  'type')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch_record')." ADD `type` varchar(45) DEFAULT NULL COMMENT '活动类型';");
}
if(!pdo_fieldexists('tg_scratch_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch_record')." ADD `status` int(11) DEFAULT NULL COMMENT '2待领取3已领取';");
}
if(!pdo_fieldexists('tg_scratch_record',  'prize')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch_record')." ADD `prize` varchar(445) DEFAULT NULL COMMENT '奖品详情';");
}
if(!pdo_fieldexists('tg_scratch_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_scratch_record')." ADD `createtime` varchar(145) DEFAULT NULL COMMENT '参与时间';");
}
if(!pdo_fieldexists('tg_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_setting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_setting')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_setting',  'key')) {
	pdo_query("ALTER TABLE ".tablename('tg_setting')." ADD `key` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('tg_setting',  'value')) {
	pdo_query("ALTER TABLE ".tablename('tg_setting')." ADD `value` text NOT NULL;");
}
if(!pdo_fieldexists('tg_spec',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_spec',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_spec',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_spec',  'description')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('tg_spec',  'displaytype')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD `displaytype` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_spec',  'content')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('tg_spec',  'goodsid')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD `goodsid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_spec',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_spec',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_spec_item',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_spec_item',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_spec_item',  'specid')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD `specid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_spec_item',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_spec_item',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_spec_item',  'show')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD `show` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_spec_item',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('tg_spec_item',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD KEY `indx_weid` (`uniacid`);");
}
if(!pdo_indexexists('tg_spec_item',  'indx_specid')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD KEY `indx_specid` (`specid`);");
}
if(!pdo_indexexists('tg_spec_item',  'indx_show')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD KEY `indx_show` (`show`);");
}
if(!pdo_indexexists('tg_spec_item',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_spec_item')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('tg_store',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_store',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_store',  'storename')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `storename` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_store',  'address')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `address` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_store',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `tel` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_store',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `lat` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_store',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `lng` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('tg_store',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `status` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('tg_store',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `createtime` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_store',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_store',  'storehours')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD `storehours` varchar(100) DEFAULT NULL;");
}
if(!pdo_indexexists('tg_store',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('tg_store',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('tg_store')." ADD KEY `idx_status` (`status`);");
}
if(!pdo_fieldexists('tg_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_user')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('tg_user')." ADD `uid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_user')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_user',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tg_user')." ADD `title` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_user_menu',  'title')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'url')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `icon` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'level')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `level` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `pid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'extend_title')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `extend_title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'extend_url')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `extend_url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'extend_icon')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `extend_icon` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'active_urls')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `active_urls` text NOT NULL;");
}
if(!pdo_fieldexists('tg_user_menu',  'is_system')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_menu')." ADD `is_system` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_user_node',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `name` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'url')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `url` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'do')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `do` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'ac')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `ac` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'op')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `op` varchar(32) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'ac_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `ac_id` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'do_id')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `do_id` int(6) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `remark` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'level')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `level` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'status')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `status` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('tg_user_node',  'flag')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD `flag` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('tg_user_node',  'level')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD KEY `level` (`level`);");
}
if(!pdo_indexexists('tg_user_node',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD KEY `pid` (`do_id`);");
}
if(!pdo_indexexists('tg_user_node',  'name')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_node')." ADD KEY `name` (`name`);");
}
if(!pdo_fieldexists('tg_user_role',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_role')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_user_role',  'merchantid')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_role')." ADD `merchantid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_user_role',  'nodes')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_role')." ADD `nodes` text NOT NULL;");
}
if(!pdo_fieldexists('tg_user_role',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_user_role')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('tg_users',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD `id` int(10) NOT NULL COMMENT '主键';");
}
if(!pdo_fieldexists('tg_users',  'username')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD `username` varchar(30) NOT NULL COMMENT '用户名';");
}
if(!pdo_fieldexists('tg_users',  'password')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD `password` varchar(20) NOT NULL COMMENT '用户密码';");
}
if(!pdo_fieldexists('tg_users',  'email')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD `email` varchar(60) NOT NULL COMMENT '邮箱';");
}
if(!pdo_fieldexists('tg_users',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD `tel` varchar(20) NOT NULL COMMENT '电话';");
}
if(!pdo_fieldexists('tg_users',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD `uniacid` int(10) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('tg_users',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD `openid` varchar(100) NOT NULL COMMENT '用户openid';");
}
if(!pdo_indexexists('tg_users',  'username')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD UNIQUE KEY `username` (`username`);");
}
if(!pdo_indexexists('tg_users',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD UNIQUE KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('tg_users',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('tg_users')." ADD UNIQUE KEY `openid` (`openid`);");
}
if(!pdo_fieldexists('tg_waittask',  'id')) {
	pdo_query("ALTER TABLE ".tablename('tg_waittask')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('tg_waittask',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('tg_waittask')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_waittask',  'value')) {
	pdo_query("ALTER TABLE ".tablename('tg_waittask')." ADD `value` varchar(145) DEFAULT NULL;");
}
if(!pdo_fieldexists('tg_waittask',  'key')) {
	pdo_query("ALTER TABLE ".tablename('tg_waittask')." ADD `key` varchar(145) DEFAULT NULL;");
}

?>