<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_jing_donation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `starttime` int(10) unsigned DEFAULT NULL,
  `endtime` int(10) unsigned DEFAULT NULL,
  `content` text,
  `company` varchar(255) DEFAULT NULL,
  `account` varchar(255) DEFAULT NULL,
  `fixed_money1` decimal(10,2) unsigned DEFAULT NULL,
  `fixed_money2` decimal(10,2) unsigned DEFAULT NULL,
  `fixed_money3` decimal(10,2) unsigned DEFAULT NULL,
  `fixed_money4` decimal(10,2) unsigned DEFAULT NULL,
  `tip` varchar(255) DEFAULT NULL COMMENT '募捐提示语',
  `share_content1` varchar(255) DEFAULT NULL,
  `share_content2` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `share_title` varchar(255) DEFAULT NULL,
  `share_pic` varchar(255) DEFAULT NULL,
  `share_des` varchar(255) DEFAULT NULL,
  `circle_name` varchar(255) DEFAULT NULL,
  `text1` varchar(20) DEFAULT NULL,
  `text2` varchar(20) DEFAULT NULL,
  `numbers` int(10) NOT NULL DEFAULT '10',
  `video` varchar(200) NOT NULL DEFAULT '',
  `need_remark` tinyint(1) NOT NULL DEFAULT '0',
  `need_name` tinyint(1) NOT NULL DEFAULT '0',
  `need_mobile` tinyint(1) NOT NULL DEFAULT '0',
  `money` decimal(10,2) unsigned DEFAULT NULL,
  `thanks` varchar(255) DEFAULT NULL,
  `xieyi` text,
  `enabled` tinyint(1) unsigned DEFAULT NULL,
  `createtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_dynamic` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT '0',
  `did` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `content` text,
  `createtime` int(10) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_enabled` (`enabled`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_invitation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `did` int(10) unsigned DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `createtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `did` int(10) unsigned DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `ordersn` int(10) unsigned DEFAULT NULL,
  `price` decimal(10,2) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `paytype` varchar(10) DEFAULT NULL,
  `transid` varchar(255) DEFAULT NULL,
  `remark` varchar(255) NOT NULL DEFAULT '',
  `realname` varchar(20) NOT NULL DEFAULT '',
  `mobile` varchar(11) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `donationid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `unionid` varchar(50) DEFAULT NULL,
  `nickname` varchar(20) DEFAULT NULL,
  `sex` tinyint(1) unsigned DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_yxz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `did` int(10) unsigned DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `yxz` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('jing_donation',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jing_donation',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `title` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `thumb` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `description` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `starttime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `endtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'content')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `content` text;");
}
if(!pdo_fieldexists('jing_donation',  'company')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `company` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'account')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `account` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'fixed_money1')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `fixed_money1` decimal(10,2) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'fixed_money2')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `fixed_money2` decimal(10,2) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'fixed_money3')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `fixed_money3` decimal(10,2) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'fixed_money4')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `fixed_money4` decimal(10,2) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'tip')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `tip` varchar(255) DEFAULT NULL COMMENT '募捐提示语';");
}
if(!pdo_fieldexists('jing_donation',  'share_content1')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `share_content1` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'share_content2')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `share_content2` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `logo` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `share_title` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'share_pic')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `share_pic` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'share_des')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `share_des` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'circle_name')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `circle_name` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'text1')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `text1` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'text2')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `text2` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'numbers')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `numbers` int(10) NOT NULL DEFAULT '10';");
}
if(!pdo_fieldexists('jing_donation',  'video')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `video` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation',  'need_remark')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `need_remark` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jing_donation',  'need_name')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `need_name` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jing_donation',  'need_mobile')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `need_mobile` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('jing_donation',  'money')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `money` decimal(10,2) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'thanks')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `thanks` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'xieyi')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `xieyi` text;");
}
if(!pdo_fieldexists('jing_donation',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `enabled` tinyint(1) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation')." ADD `createtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_adv',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jing_donation_adv',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jing_donation_adv',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_adv',  'link')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD `link` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_adv',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_adv',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('jing_donation_adv',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('jing_donation_adv',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('jing_donation_adv',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('jing_donation_adv',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_adv')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `uniacid` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'did')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `did` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'title')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'link')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `link` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'description')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'content')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `content` text;");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('jing_donation_dynamic',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('jing_donation_dynamic',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('jing_donation_dynamic',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_dynamic')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_fieldexists('jing_donation_invitation',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_invitation')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jing_donation_invitation',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_invitation')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_invitation',  'did')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_invitation')." ADD `did` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_invitation',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_invitation')." ADD `openid` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_invitation',  'content')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_invitation')." ADD `content` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_invitation',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_invitation')." ADD `status` tinyint(1) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_invitation',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_invitation')." ADD `createtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jing_donation_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_order',  'did')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `did` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `openid` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `ordersn` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `price` decimal(10,2) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `status` tinyint(1) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `paytype` varchar(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `transid` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `remark` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_order',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `realname` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `mobile` varchar(11) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('jing_donation_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_order')." ADD `createtime` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jing_donation_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_reply')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jing_donation_reply',  'donationid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_reply')." ADD `donationid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('jing_donation_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jing_donation_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `openid` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_user',  'unionid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `unionid` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `nickname` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_user',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `sex` tinyint(1) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_user',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `avatar` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_user',  'country')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `country` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_user',  'province')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `province` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_user',  'city')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_user')." ADD `city` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_yxz',  'id')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_yxz')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('jing_donation_yxz',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_yxz')." ADD `uniacid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_yxz',  'did')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_yxz')." ADD `did` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_yxz',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_yxz')." ADD `openid` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('jing_donation_yxz',  'yxz')) {
	pdo_query("ALTER TABLE ".tablename('jing_donation_yxz')." ADD `yxz` int(10) unsigned DEFAULT NULL;");
}

?>