<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_mon_xkwkj` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `kj_intro` text,
  `p_name` varchar(100) DEFAULT NULL,
  `p_kc` int(10) DEFAULT '0',
  `p_y_price` float(10,2) DEFAULT NULL,
  `p_low_price` float(10,2) DEFAULT NULL,
  `yf_price` float(10,2) DEFAULT '0.00',
  `p_pic` varchar(1000) DEFAULT NULL,
  `p_preview_pic` varchar(1000) DEFAULT NULL,
  `follow_url` varchar(1000) DEFAULT NULL,
  `copyright` varchar(100) NOT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `p_url` varchar(1000) DEFAULT NULL,
  `copyright_url` varchar(500) DEFAULT NULL,
  `hot_tel` varchar(50) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `kj_dialog_tip` varchar(1000) DEFAULT NULL,
  `rank_tip` varchar(1000) DEFAULT NULL,
  `u_fist_tip` varchar(1000) DEFAULT NULL,
  `u_already_tip` varchar(1000) DEFAULT NULL,
  `fk_fist_tip` varchar(1000) DEFAULT NULL,
  `fk_already_tip` varchar(1000) DEFAULT NULL,
  `kj_rule` text,
  `pay_type` int(2) DEFAULT NULL,
  `p_model` varchar(1000) DEFAULT NULL,
  `kj_follow_enable` int(1) DEFAULT NULL,
  `join_follow_enable` int(1) DEFAULT NULL,
  `follow_dlg_tip` varchar(500) DEFAULT NULL,
  `follow_btn_name` varchar(20) DEFAULT NULL,
  `share_bg` varchar(300) DEFAULT NULL,
  `rank_num` int(10) DEFAULT NULL,
  `join_rank_num` int(10) DEFAULT NULL,
  `v_user` int(10) DEFAULT NULL,
  `zt_address` varchar(1000) DEFAULT NULL,
  `one_kj_enable` int(1) DEFAULT NULL,
  `day_help_count` int(10) DEFAULT NULL,
  `submit_money_limit` float(10,2) DEFAULT NULL,
  `hx_enabled` int(1) DEFAULT NULL,
  `tmp_enable` int(1) DEFAULT NULL,
  `tmpId` varchar(1000) DEFAULT NULL,
  `fh_tmp_enable` int(1) DEFAULT NULL,
  `fh_tmp_id` varchar(1000) DEFAULT NULL,
  `show_index_enable` int(1) DEFAULT NULL,
  `index_sort` int(10) DEFAULT NULL,
  `announcement` varchar(2000) DEFAULT NULL,
  `zgg_url` varchar(500) DEFAULT NULL,
  `is_collec_order_address` int(1) DEFAULT NULL,
  `join_user_limit` int(10) DEFAULT NULL,
  `help_limit` int(10) DEFAULT NULL,
  `locationlimit` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_mon_xkwkj_firend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `kd` int(2) DEFAULT NULL,
  `kname` int(3) DEFAULT NULL,
  `msg` varchar(500) DEFAULT NULL,
  `ac` varchar(50) DEFAULT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `k_price` float(10,2) DEFAULT NULL,
  `kh_price` float(10,2) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `ip` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_mon_xkwkj_index_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `pagesize` varchar(200) DEFAULT NULL,
  `announcement` varchar(2000) DEFAULT NULL,
  `banner_bg` varchar(1000) DEFAULT NULL,
  `banner_url` varchar(2000) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_mon_xkwkj_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `uname` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `privnce` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `town` varchar(100) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `zipcode` varchar(100) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `outno` varchar(200) DEFAULT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_mon_xkwkj_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `appid` varchar(200) DEFAULT NULL,
  `appsecret` varchar(200) DEFAULT NULL,
  `mchid` varchar(100) DEFAULT NULL,
  `shkey` varchar(100) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `is_collect_user_info` int(1) DEFAULT NULL,
  `help_kj_limit` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_mon_xkwkj_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `kd` int(2) DEFAULT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `price` float(10,2) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_mon_xkwkj_user_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `uname` varchar(100) DEFAULT NULL,
  `tel` varchar(100) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('mon_xkwkj',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_xkwkj',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `rid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `starttime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `endtime` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'kj_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `kj_intro` text;");
}
if(!pdo_fieldexists('mon_xkwkj',  'p_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `p_name` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'p_kc')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `p_kc` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_xkwkj',  'p_y_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `p_y_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'p_low_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `p_low_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'yf_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `yf_price` float(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('mon_xkwkj',  'p_pic')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `p_pic` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'p_preview_pic')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `p_preview_pic` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `follow_url` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `copyright` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'new_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `new_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'new_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `new_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'new_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `new_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `share_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `share_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `share_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'p_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `p_url` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'copyright_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `copyright_url` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'hot_tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `hot_tel` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_xkwkj',  'kj_dialog_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `kj_dialog_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'rank_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `rank_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'u_fist_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `u_fist_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'u_already_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `u_already_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'fk_fist_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `fk_fist_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'fk_already_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `fk_already_tip` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'kj_rule')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `kj_rule` text;");
}
if(!pdo_fieldexists('mon_xkwkj',  'pay_type')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `pay_type` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'p_model')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `p_model` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'kj_follow_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `kj_follow_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'join_follow_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `join_follow_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'follow_dlg_tip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `follow_dlg_tip` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'follow_btn_name')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `follow_btn_name` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'share_bg')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `share_bg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'rank_num')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `rank_num` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'join_rank_num')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `join_rank_num` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'v_user')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `v_user` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'zt_address')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `zt_address` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'one_kj_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `one_kj_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'day_help_count')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `day_help_count` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'submit_money_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `submit_money_limit` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'hx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `hx_enabled` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'tmp_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `tmp_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'tmpId')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `tmpId` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'fh_tmp_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `fh_tmp_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'fh_tmp_id')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `fh_tmp_id` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'show_index_enable')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `show_index_enable` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'index_sort')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `index_sort` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'announcement')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `announcement` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'zgg_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `zgg_url` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'is_collec_order_address')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `is_collec_order_address` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'join_user_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `join_user_limit` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'help_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `help_limit` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj',  'locationlimit')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj')." ADD `locationlimit` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'kid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `kid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'kd')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `kd` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'kname')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `kname` int(3) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'msg')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `msg` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'ac')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `ac` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'k_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `k_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'kh_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `kh_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_xkwkj_firend',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_firend')." ADD `ip` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'pagesize')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `pagesize` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'announcement')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `announcement` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'banner_bg')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `banner_bg` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'banner_url')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `banner_url` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `share_title` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `share_icon` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_index_setting',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_index_setting')." ADD `share_content` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'kid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `kid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `uid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `uname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'address')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `address` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'privnce')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `privnce` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'city')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `city` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'town')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `town` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `tel` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'zipcode')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `zipcode` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `openid` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'outno')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `outno` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'order_no')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `order_no` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'wxorder_no')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `wxorder_no` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'y_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `y_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'kh_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `kh_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'yf_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `yf_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'total_price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `total_price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'pay_type')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `pay_type` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'p_model')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `p_model` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `status` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'wxnotify')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `wxnotify` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'notifytime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `notifytime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_xkwkj_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_order')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_xkwkj_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_xkwkj_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_setting')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_setting',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_setting')." ADD `appid` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_setting',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_setting')." ADD `appsecret` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_setting',  'mchid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_setting')." ADD `mchid` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_setting',  'shkey')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_setting')." ADD `shkey` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_setting',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_setting')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_xkwkj_setting',  'is_collect_user_info')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_setting')." ADD `is_collect_user_info` int(1) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_setting',  'help_kj_limit')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_setting')." ADD `help_kj_limit` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_xkwkj_user',  'kid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user')." ADD `kid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user',  'kd')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user')." ADD `kd` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user',  'price')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user')." ADD `price` float(10,2) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user')." ADD `ip` varchar(30) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_xkwkj_user_info',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user_info')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_xkwkj_user_info',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user_info')." ADD `weid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user_info',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user_info')." ADD `nickname` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user_info',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user_info')." ADD `openid` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user_info',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user_info')." ADD `headimgurl` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user_info',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user_info')." ADD `uname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user_info',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user_info')." ADD `tel` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_xkwkj_user_info',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_xkwkj_user_info')." ADD `createtime` int(10) DEFAULT '0';");
}

?>