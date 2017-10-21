<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_mon_wkj` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `p_name` varchar(100) DEFAULT NULL,
  `p_kc` int(10) DEFAULT '0',
  `p_y_price` float(10,2) DEFAULT NULL,
  `p_low_price` float(10,2) DEFAULT NULL,
  `yf_price` float(10,2) DEFAULT '0.00',
  `p_pic` varchar(200) DEFAULT NULL,
  `p_preview_pic` varchar(200) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `copyright` varchar(100) NOT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `p_url` varchar(500) DEFAULT NULL,
  `copyright_url` varchar(500) DEFAULT NULL,
  `hot_tel` varchar(50) DEFAULT NULL,
  `p_intro` varchar(1000) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `kj_dialog_tip` varchar(1000) DEFAULT NULL,
  `rank_tip` varchar(1000) DEFAULT NULL,
  `u_fist_tip` varchar(1000) DEFAULT NULL,
  `u_already_tip` varchar(1000) DEFAULT NULL,
  `fk_fist_tip` varchar(1000) DEFAULT NULL,
  `fk_already_tip` varchar(1000) DEFAULT NULL,
  `kj_rule` varchar(1000) DEFAULT NULL,
  `pay_type` int(2) DEFAULT NULL,
  `p_model` varchar(1000) DEFAULT NULL,
  `friend_help_limit` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_wkj_firend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `k_price` float(10,2) DEFAULT NULL,
  `kh_price` float(10,2) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `ip` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_wkj_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `uname` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `order_no` varchar(100) DEFAULT NULL,
  `wxorder_no` varchar(100) DEFAULT NULL,
  `y_price` float(10,2) DEFAULT NULL,
  `kh_price` float(10,2) DEFAULT NULL,
  `yf_price` float(10,2) DEFAULT NULL,
  `total_price` float(10,2) DEFAULT NULL,
  `pay_type` int(2) DEFAULT NULL,
  `p_model` varchar(1000) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `wxnotify` varchar(200) DEFAULT NULL,
  `notifytime` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_wkj_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `appid` varchar(200) DEFAULT NULL,
  `appsecret` varchar(200) DEFAULT NULL,
  `mchid` varchar(100) DEFAULT NULL,
  `shkey` varchar(100) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_wkj_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `price` float(10,2) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('mon_wkj',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_wkj',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `rid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `starttime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `endtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'p_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'p_kc')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_kc` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_wkj',  'p_y_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_y_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'p_low_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_low_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'yf_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `yf_price` float(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('mon_wkj',  'p_pic')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_pic` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'p_preview_pic')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_preview_pic` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `follow_url` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `copyright` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `new_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'new_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `new_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'new_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `new_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `share_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `share_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `share_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'p_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_url` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'copyright_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `copyright_url` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'hot_tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `hot_tel` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'p_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_intro` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_wkj',  'kj_dialog_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `kj_dialog_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'rank_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `rank_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'u_fist_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `u_fist_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'u_already_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `u_already_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'fk_fist_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `fk_fist_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'fk_already_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `fk_already_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'kj_rule')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `kj_rule` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'pay_type')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `pay_type` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'p_model')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `p_model` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj',  'friend_help_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj')." ADD `friend_help_limit` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_firend',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_wkj_firend',  'kid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `kid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_firend',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_firend',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_firend',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_firend',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_firend',  'k_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `k_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_firend',  'kh_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `kh_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_firend',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_wkj_firend',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_firend')." ADD `ip` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_wkj_order',  'kid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `kid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `uname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `address` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `tel` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `openid` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'order_no')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `order_no` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'wxorder_no')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `wxorder_no` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'y_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `y_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'kh_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `kh_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'yf_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `yf_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'total_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `total_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'pay_type')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `pay_type` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'p_model')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `p_model` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'wxnotify')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `wxnotify` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_order',  'notifytime')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `notifytime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_wkj_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_order')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_wkj_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_wkj_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_setting')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_setting',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_setting')." ADD `appid` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_setting',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_setting')." ADD `appsecret` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_setting',  'mchid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_setting')." ADD `mchid` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_setting',  'shkey')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_setting')." ADD `shkey` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_setting',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_setting')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_wkj_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_wkj_user',  'kid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_user')." ADD `kid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_user')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_user')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_user')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_wkj_user',  'price')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_user')." ADD `price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_user',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_user')." ADD `ip` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_wkj_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_wkj_user')." ADD `createtime` int(10) DEFAULT '0';");
}

?>