<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_wx_carpool_comment` (
  `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT,
  `abc_weid_abc` int(11) NOT NULL,
  `abc_order_id_abc` int(11) NOT NULL,
  `abc_comment_abc` text NOT NULL,
  `abc_create_time_abc` datetime NOT NULL,
  `abc_comment_user_openid_abc` varchar(255) NOT NULL,
  `abc_nickname_abc` varchar(255) NOT NULL,
  `abc_img_abc` varchar(255) NOT NULL,
  PRIMARY KEY (`abc_id_abc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_carpool_order` (
  `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `abc_openid_abc` text NOT NULL,
  `abc_weid_abc` int(11) NOT NULL,
  `abc_state_for_manager_abc` int(11) NOT NULL DEFAULT '1' COMMENT '订单状态 1=正常订单 -1删除的订单',
  `abc_state_for_user_abc` int(11) NOT NULL DEFAULT '1' COMMENT '订单状态 1=正常订单 -1删除的订单',
  `abc_order_type_abc` int(11) NOT NULL COMMENT '订单类型 0=人找车 1车找人',
  `abc_uid_abc` int(11) NOT NULL COMMENT '用户ID',
  `abc_place_of_departure_abc` varchar(255) NOT NULL COMMENT '出发地',
  `abc_destination_abc` varchar(255) NOT NULL COMMENT '目的地',
  `abc_pathway_abc` varchar(255) DEFAULT NULL COMMENT '途径地',
  `abc_departure_time_abc` datetime NOT NULL COMMENT '出发时间',
  `abc_replenishment_time_abc` varchar(255) DEFAULT NULL COMMENT '补充时间',
  `abc_phone_abc` varchar(255) NOT NULL COMMENT '手机号',
  `abc_name_abc` varchar(255) NOT NULL COMMENT '联系人',
  `abc_number_abc` int(11) NOT NULL COMMENT '人数或空位数',
  `abc_isTop_abc` int(11) NOT NULL DEFAULT '0' COMMENT '是否置顶 0=不置顶 1=置顶1天 2=置顶3天',
  `abc_describe_abc` text COMMENT '描述',
  `abc_order_create_time_abc` datetime NOT NULL COMMENT '订单创建时间',
  `abc_attention_degree_abc` varchar(255) NOT NULL DEFAULT '0' COMMENT '关注度',
  PRIMARY KEY (`abc_id_abc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_carpool_pay` (
  `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `abc_weid_abc` int(11) NOT NULL COMMENT '公众号id',
  `abc_uid_abc` text NOT NULL COMMENT '用户表用户id',
  `abc_order_id_abc` int(11) NOT NULL COMMENT '订单id',
  `abc_num_abc` decimal(6,2) NOT NULL COMMENT '支付金额',
  `abc_status_abc` int(11) NOT NULL DEFAULT '0' COMMENT '支付状态 0=未支付 1=已支付',
  `abc_create_time_abc` datetime NOT NULL COMMENT '创建时间',
  `abc_update_time_abc` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`abc_id_abc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_carpool_picconfig` (
  `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `abc_weid_abc` int(11) NOT NULL,
  `abc_title_abc` text NOT NULL COMMENT '图片标题',
  `abc_path_abc` text NOT NULL COMMENT '图片地址',
  `abc_link_abc` text NOT NULL COMMENT '连接跳转地址',
  PRIMARY KEY (`abc_id_abc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_carpool_textconfig` (
  `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `abc_weid_abc` int(11) NOT NULL,
  `abc_text_abc` text NOT NULL COMMENT '配置名称',
  `abc_value_abc` text NOT NULL COMMENT '配置值',
  PRIMARY KEY (`abc_id_abc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_carpool_user` (
  `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `abc_weid_abc` int(11) NOT NULL,
  `abc_openid_abc` text NOT NULL,
  `abc_balance_abc` int(11) NOT NULL DEFAULT '0' COMMENT '余额',
  `abc_last_time_to_recharge_abc` datetime DEFAULT NULL COMMENT '最后一次充值时间',
  `abc_create_time_abc` datetime NOT NULL COMMENT '用户创建时间',
  PRIMARY KEY (`abc_id_abc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wxcard_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `card_id` varchar(50) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `brand_name` varchar(30) NOT NULL,
  `logo_url` varchar(255) NOT NULL,
  `success` varchar(255) NOT NULL,
  `error` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('wx_carpool_comment',  'abc_id_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_comment')." ADD `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_carpool_comment',  'abc_weid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_comment')." ADD `abc_weid_abc` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_comment',  'abc_order_id_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_comment')." ADD `abc_order_id_abc` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_comment',  'abc_comment_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_comment')." ADD `abc_comment_abc` text NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_comment',  'abc_create_time_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_comment')." ADD `abc_create_time_abc` datetime NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_comment',  'abc_comment_user_openid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_comment')." ADD `abc_comment_user_openid_abc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_comment',  'abc_nickname_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_comment')." ADD `abc_nickname_abc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_comment',  'abc_img_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_comment')." ADD `abc_img_abc` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_id_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_openid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_openid_abc` text NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_weid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_weid_abc` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_state_for_manager_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_state_for_manager_abc` int(11) NOT NULL DEFAULT '1' COMMENT '订单状态 1=正常订单 -1删除的订单';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_state_for_user_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_state_for_user_abc` int(11) NOT NULL DEFAULT '1' COMMENT '订单状态 1=正常订单 -1删除的订单';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_order_type_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_order_type_abc` int(11) NOT NULL COMMENT '订单类型 0=人找车 1车找人';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_uid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_uid_abc` int(11) NOT NULL COMMENT '用户ID';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_place_of_departure_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_place_of_departure_abc` varchar(255) NOT NULL COMMENT '出发地';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_destination_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_destination_abc` varchar(255) NOT NULL COMMENT '目的地';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_pathway_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_pathway_abc` varchar(255) DEFAULT NULL COMMENT '途径地';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_departure_time_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_departure_time_abc` datetime NOT NULL COMMENT '出发时间';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_replenishment_time_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_replenishment_time_abc` varchar(255) DEFAULT NULL COMMENT '补充时间';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_phone_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_phone_abc` varchar(255) NOT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_name_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_name_abc` varchar(255) NOT NULL COMMENT '联系人';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_number_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_number_abc` int(11) NOT NULL COMMENT '人数或空位数';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_isTop_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_isTop_abc` int(11) NOT NULL DEFAULT '0' COMMENT '是否置顶 0=不置顶 1=置顶1天 2=置顶3天';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_describe_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_describe_abc` text COMMENT '描述';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_order_create_time_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_order_create_time_abc` datetime NOT NULL COMMENT '订单创建时间';");
}
if(!pdo_fieldexists('wx_carpool_order',  'abc_attention_degree_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_order')." ADD `abc_attention_degree_abc` varchar(255) NOT NULL DEFAULT '0' COMMENT '关注度';");
}
if(!pdo_fieldexists('wx_carpool_pay',  'abc_id_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_pay')." ADD `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键';");
}
if(!pdo_fieldexists('wx_carpool_pay',  'abc_weid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_pay')." ADD `abc_weid_abc` int(11) NOT NULL COMMENT '公众号id';");
}
if(!pdo_fieldexists('wx_carpool_pay',  'abc_uid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_pay')." ADD `abc_uid_abc` text NOT NULL COMMENT '用户表用户id';");
}
if(!pdo_fieldexists('wx_carpool_pay',  'abc_order_id_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_pay')." ADD `abc_order_id_abc` int(11) NOT NULL COMMENT '订单id';");
}
if(!pdo_fieldexists('wx_carpool_pay',  'abc_num_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_pay')." ADD `abc_num_abc` decimal(6,2) NOT NULL COMMENT '支付金额';");
}
if(!pdo_fieldexists('wx_carpool_pay',  'abc_status_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_pay')." ADD `abc_status_abc` int(11) NOT NULL DEFAULT '0' COMMENT '支付状态 0=未支付 1=已支付';");
}
if(!pdo_fieldexists('wx_carpool_pay',  'abc_create_time_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_pay')." ADD `abc_create_time_abc` datetime NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('wx_carpool_pay',  'abc_update_time_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_pay')." ADD `abc_update_time_abc` datetime DEFAULT NULL COMMENT '更新时间';");
}
if(!pdo_fieldexists('wx_carpool_picconfig',  'abc_id_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_picconfig')." ADD `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号';");
}
if(!pdo_fieldexists('wx_carpool_picconfig',  'abc_weid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_picconfig')." ADD `abc_weid_abc` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_picconfig',  'abc_title_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_picconfig')." ADD `abc_title_abc` text NOT NULL COMMENT '图片标题';");
}
if(!pdo_fieldexists('wx_carpool_picconfig',  'abc_path_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_picconfig')." ADD `abc_path_abc` text NOT NULL COMMENT '图片地址';");
}
if(!pdo_fieldexists('wx_carpool_picconfig',  'abc_link_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_picconfig')." ADD `abc_link_abc` text NOT NULL COMMENT '连接跳转地址';");
}
if(!pdo_fieldexists('wx_carpool_textconfig',  'abc_id_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_textconfig')." ADD `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号';");
}
if(!pdo_fieldexists('wx_carpool_textconfig',  'abc_weid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_textconfig')." ADD `abc_weid_abc` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_textconfig',  'abc_text_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_textconfig')." ADD `abc_text_abc` text NOT NULL COMMENT '配置名称';");
}
if(!pdo_fieldexists('wx_carpool_textconfig',  'abc_value_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_textconfig')." ADD `abc_value_abc` text NOT NULL COMMENT '配置值';");
}
if(!pdo_fieldexists('wx_carpool_user',  'abc_id_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_user')." ADD `abc_id_abc` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号';");
}
if(!pdo_fieldexists('wx_carpool_user',  'abc_weid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_user')." ADD `abc_weid_abc` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_user',  'abc_openid_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_user')." ADD `abc_openid_abc` text NOT NULL;");
}
if(!pdo_fieldexists('wx_carpool_user',  'abc_balance_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_user')." ADD `abc_balance_abc` int(11) NOT NULL DEFAULT '0' COMMENT '余额';");
}
if(!pdo_fieldexists('wx_carpool_user',  'abc_last_time_to_recharge_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_user')." ADD `abc_last_time_to_recharge_abc` datetime DEFAULT NULL COMMENT '最后一次充值时间';");
}
if(!pdo_fieldexists('wx_carpool_user',  'abc_create_time_abc')) {
	pdo_query("ALTER TABLE ".tablename('wx_carpool_user')." ADD `abc_create_time_abc` datetime NOT NULL COMMENT '用户创建时间';");
}
if(!pdo_fieldexists('wxcard_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wxcard_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wxcard_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('wxcard_reply',  'card_id')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD `card_id` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wxcard_reply',  'cid')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD `cid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wxcard_reply',  'brand_name')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD `brand_name` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('wxcard_reply',  'logo_url')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD `logo_url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wxcard_reply',  'success')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD `success` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wxcard_reply',  'error')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD `error` varchar(255) NOT NULL;");
}
if(!pdo_indexexists('wxcard_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('wxcard_reply')." ADD KEY `rid` (`rid`);");
}

?>