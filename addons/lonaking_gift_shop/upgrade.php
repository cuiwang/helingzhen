<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `image` varchar(255) DEFAULT '',
  `url` varchar(255) DEFAULT '',
  `type` tinyint(2) DEFAULT '1',
  `delay` int(5) DEFAULT '5',
  `createtime` int(11) DEFAULT NULL,
  `updatetime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_gift` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '礼品名',
  `price` int(11) NOT NULL DEFAULT '0' COMMENT '价格',
  `type` varchar(10) NOT NULL DEFAULT '' COMMENT '礼品类型',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '数量',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1在售 0停止',
  `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未删除 1删除',
  `description` text COMMENT '描述',
  `createtime` int(11) NOT NULL,
  `updatetime` int(11) NOT NULL,
  `pic` varchar(255) NOT NULL COMMENT '礼品图片',
  `mode` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1微信红包 2充值 3实物礼品 4自领礼品',
  `send_price` decimal(10,1) DEFAULT '0.0' COMMENT '配送费',
  `mobile_fee_money` int(10) DEFAULT '0' COMMENT '话费金额',
  `hongbao_money` int(10) DEFAULT '0' COMMENT '红包金额，若是随机红包，则以此为基础红包金额',
  `ziling_address` varchar(255) DEFAULT '' COMMENT '自领礼品地址',
  `ziling_mobile` varchar(11) DEFAULT '' COMMENT '自领礼品联系电话',
  `check_password` varchar(255) DEFAULT '' COMMENT '自领礼品核销密码',
  `hide` tinyint(1) DEFAULT '0' COMMENT '是否隐藏 1隐藏 0不隐藏',
  `sold` int(11) DEFAULT '0' COMMENT '已售出数量',
  `limit_num` int(11) DEFAULT '0' COMMENT '限制领取次数',
  `raffle` tinyint(1) DEFAULT '0' COMMENT '是否是抽奖:0普通模式 1抽奖',
  `hongbao_mode` tinyint(1) DEFAULT '1' COMMENT '1定额红包 2随机红包',
  `hongbao_min` int(11) DEFAULT '0' COMMENT '红包随机下限',
  `hongbao_max` int(11) DEFAULT '0' COMMENT '红包随机上限',
  `hongbao_send_num` varchar(255) DEFAULT '' COMMENT '随机红包命中随机数',
  `raffle_min` int(11) DEFAULT '0' COMMENT '随机下限',
  `raffle_max` int(11) DEFAULT '0' COMMENT '随机上限',
  `raffle_send_num` varchar(255) DEFAULT '' COMMENT '中奖号码',
  `auto_success` tinyint(1) DEFAULT '0' COMMENT '是否自动审核 0:否 1:是',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_gift_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '' COMMENT '管理员id',
  `gift_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_gift_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(128) NOT NULL,
  `gift` int(11) NOT NULL COMMENT '兑换的礼品',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '状态 0进行中 1成功 2失败',
  `name` varchar(10) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `target` varchar(30) NOT NULL,
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `pay_method` tinyint(1) DEFAULT '1' COMMENT '1微信支付 2货到支付',
  `pay_status` tinyint(1) DEFAULT '0' COMMENT '0 未支付 1已支付',
  `trans_num` varchar(100) DEFAULT '0' COMMENT '快递单号',
  `order_num` varchar(255) DEFAULT '' COMMENT '订单编号',
  `send_price` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '配送费',
  `order_price` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '订单价格',
  `raffle_status` tinyint(1) DEFAULT '0' COMMENT '是否中奖:0未中奖 1中奖',
  `order_mode` tinyint(1) DEFAULT '0' COMMENT '0.默认正常模式 1抽奖模式',
  `order_hongbao_money` int(11) DEFAULT '0' COMMENT '红包金额',
  `remark` varchar(255) DEFAULT '' COMMENT '备注，拒绝理由等',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lonaking_gift_shop_tpl_template_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `get_notice` varchar(255) DEFAULT '',
  `check_status_access_notice` varchar(255) DEFAULT '',
  `check_status_refuse_notice` varchar(255) DEFAULT '',
  `send_notice` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('lonaking_gift_shop_ad',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_ad')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lonaking_gift_shop_ad',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_ad')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_ad',  'title')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_ad')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_gift_shop_ad',  'image')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_ad')." ADD `image` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_gift_shop_ad',  'url')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_ad')." ADD `url` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_gift_shop_ad',  'type')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_ad')." ADD `type` tinyint(2) DEFAULT '1';");
}
if(!pdo_fieldexists('lonaking_gift_shop_ad',  'delay')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_ad')." ADD `delay` int(5) DEFAULT '5';");
}
if(!pdo_fieldexists('lonaking_gift_shop_ad',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_ad')." ADD `createtime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_ad',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_ad')." ADD `updatetime` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'name')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `name` varchar(20) NOT NULL COMMENT '礼品名';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'price')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `price` int(11) NOT NULL DEFAULT '0' COMMENT '价格';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'type')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `type` varchar(10) NOT NULL DEFAULT '' COMMENT '礼品类型';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'num')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `num` int(11) NOT NULL DEFAULT '0' COMMENT '数量';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'status')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1在售 0停止';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'del')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 未删除 1删除';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'description')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `description` text COMMENT '描述';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `createtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `updatetime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `pic` varchar(255) NOT NULL COMMENT '礼品图片';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'mode')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `mode` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1微信红包 2充值 3实物礼品 4自领礼品';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'send_price')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `send_price` decimal(10,1) DEFAULT '0.0' COMMENT '配送费';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'mobile_fee_money')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `mobile_fee_money` int(10) DEFAULT '0' COMMENT '话费金额';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'hongbao_money')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `hongbao_money` int(10) DEFAULT '0' COMMENT '红包金额，若是随机红包，则以此为基础红包金额';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'ziling_address')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `ziling_address` varchar(255) DEFAULT '' COMMENT '自领礼品地址';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'ziling_mobile')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `ziling_mobile` varchar(11) DEFAULT '' COMMENT '自领礼品联系电话';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'check_password')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `check_password` varchar(255) DEFAULT '' COMMENT '自领礼品核销密码';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'hide')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `hide` tinyint(1) DEFAULT '0' COMMENT '是否隐藏 1隐藏 0不隐藏';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'sold')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `sold` int(11) DEFAULT '0' COMMENT '已售出数量';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'limit_num')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `limit_num` int(11) DEFAULT '0' COMMENT '限制领取次数';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'raffle')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `raffle` tinyint(1) DEFAULT '0' COMMENT '是否是抽奖:0普通模式 1抽奖';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'hongbao_mode')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `hongbao_mode` tinyint(1) DEFAULT '1' COMMENT '1定额红包 2随机红包';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'hongbao_min')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `hongbao_min` int(11) DEFAULT '0' COMMENT '红包随机下限';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'hongbao_max')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `hongbao_max` int(11) DEFAULT '0' COMMENT '红包随机上限';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'hongbao_send_num')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `hongbao_send_num` varchar(255) DEFAULT '' COMMENT '随机红包命中随机数';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'raffle_min')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `raffle_min` int(11) DEFAULT '0' COMMENT '随机下限';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'raffle_max')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `raffle_max` int(11) DEFAULT '0' COMMENT '随机上限';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'raffle_send_num')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `raffle_send_num` varchar(255) DEFAULT '' COMMENT '中奖号码';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift',  'auto_success')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift')." ADD `auto_success` tinyint(1) DEFAULT '0' COMMENT '是否自动审核 0:否 1:是';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_admin',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_admin')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_admin',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_admin')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_admin',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_admin')." ADD `openid` varchar(40) NOT NULL DEFAULT '' COMMENT '管理员id';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_admin',  'gift_id')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_admin')." ADD `gift_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `openid` varchar(128) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'gift')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `gift` int(11) NOT NULL COMMENT '兑换的礼品';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `status` int(2) NOT NULL DEFAULT '0' COMMENT '状态 0进行中 1成功 2失败';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'name')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `name` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `mobile` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'target')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `target` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'updatetime')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `updatetime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'pay_method')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `pay_method` tinyint(1) DEFAULT '1' COMMENT '1微信支付 2货到支付';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'pay_status')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `pay_status` tinyint(1) DEFAULT '0' COMMENT '0 未支付 1已支付';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'trans_num')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `trans_num` varchar(100) DEFAULT '0' COMMENT '快递单号';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'order_num')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `order_num` varchar(255) DEFAULT '' COMMENT '订单编号';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'send_price')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `send_price` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '配送费';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'order_price')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `order_price` decimal(10,1) NOT NULL DEFAULT '0.0' COMMENT '订单价格';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'raffle_status')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `raffle_status` tinyint(1) DEFAULT '0' COMMENT '是否中奖:0未中奖 1中奖';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'order_mode')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `order_mode` tinyint(1) DEFAULT '0' COMMENT '0.默认正常模式 1抽奖模式';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'order_hongbao_money')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `order_hongbao_money` int(11) DEFAULT '0' COMMENT '红包金额';");
}
if(!pdo_fieldexists('lonaking_gift_shop_gift_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_gift_order')." ADD `remark` varchar(255) DEFAULT '' COMMENT '备注，拒绝理由等';");
}
if(!pdo_fieldexists('lonaking_gift_shop_tpl_template_config',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_tpl_template_config')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lonaking_gift_shop_tpl_template_config',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_tpl_template_config')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('lonaking_gift_shop_tpl_template_config',  'get_notice')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_tpl_template_config')." ADD `get_notice` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_gift_shop_tpl_template_config',  'check_status_access_notice')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_tpl_template_config')." ADD `check_status_access_notice` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_gift_shop_tpl_template_config',  'check_status_refuse_notice')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_tpl_template_config')." ADD `check_status_refuse_notice` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_gift_shop_tpl_template_config',  'send_notice')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_gift_shop_tpl_template_config')." ADD `send_notice` varchar(255) DEFAULT '';");
}

?>