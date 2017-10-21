<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hsh_tools_interaction_time` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `scene_id` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `precision` varchar(20) NOT NULL,
  `update_times` int(10) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hsh_tools_notice_order_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `notice_id` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `uid` varchar(45) NOT NULL,
  `field_value` text NOT NULL,
  `notice_list` varchar(60) NOT NULL,
  `remark` varchar(500) NOT NULL,
  `add_time` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hsh_tools_notice_setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(90) NOT NULL,
  `field_setting` text NOT NULL,
  `options` text NOT NULL,
  `template_name` varchar(60) NOT NULL,
  `notice_list` varchar(60) NOT NULL,
  `notice_option` text NOT NULL,
  `message_script` varchar(80) NOT NULL,
  `sms_template_id` varchar(45) NOT NULL,
  `foot_info` text NOT NULL,
  `success_hint` varchar(300) NOT NULL,
  `opening_hour_begin` int(3) NOT NULL,
  `opening_hour_end` int(3) NOT NULL,
  `closing_hint` varchar(300) NOT NULL,
  `opening_time` int(11) NOT NULL,
  `pause_hint` varchar(300) NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hsh_tools_tm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned DEFAULT NULL,
  `title` varchar(60) NOT NULL,
  `template_id` varchar(50) NOT NULL,
  `url` varchar(500) NOT NULL,
  `topcolor` varchar(10) NOT NULL,
  `data` varchar(800) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hsh_tools_tm_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `template_id` varchar(50) NOT NULL,
  `send_time` int(10) unsigned NOT NULL,
  `send_data` text NOT NULL,
  `send_state` int(11) NOT NULL,
  `error_data` varchar(800) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hsh_tools_url_redirect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(180) NOT NULL,
  `go_url` varchar(500) NOT NULL,
  `back_url` varchar(500) NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `redirect_type` varchar(45) NOT NULL,
  `test_mode` int(10) unsigned NOT NULL DEFAULT '0',
  `param_name` varchar(45) NOT NULL,
  `arg_state` varchar(60) NOT NULL DEFAULT '1',
  `state` int(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('hsh_tools_interaction_time',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_interaction_time')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hsh_tools_interaction_time',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_interaction_time')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_interaction_time',  'scene_id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_interaction_time')." ADD `scene_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_interaction_time',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_interaction_time')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_interaction_time',  'latitude')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_interaction_time')." ADD `latitude` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_interaction_time',  'longitude')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_interaction_time')." ADD `longitude` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_interaction_time',  'precision')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_interaction_time')." ADD `precision` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_interaction_time',  'update_times')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_interaction_time')." ADD `update_times` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_interaction_time',  'add_time')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_interaction_time')." ADD `add_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'notice_id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `notice_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `uid` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'field_value')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `field_value` text NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'notice_list')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `notice_list` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `remark` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'add_time')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `add_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_order_list',  'state')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_order_list')." ADD `state` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `title` varchar(90) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'field_setting')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `field_setting` text NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'options')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `options` text NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'template_name')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `template_name` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'notice_list')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `notice_list` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'notice_option')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `notice_option` text NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'message_script')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `message_script` varchar(80) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'sms_template_id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `sms_template_id` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'foot_info')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `foot_info` text NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'success_hint')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `success_hint` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'opening_hour_begin')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `opening_hour_begin` int(3) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'opening_hour_end')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `opening_hour_end` int(3) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'closing_hint')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `closing_hint` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'opening_time')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `opening_time` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'pause_hint')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `pause_hint` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_notice_setting',  'state')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_notice_setting')." ADD `state` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hsh_tools_tm',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm')." ADD `weid` int(10) unsigned DEFAULT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm')." ADD `title` varchar(60) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm',  'template_id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm')." ADD `template_id` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm',  'url')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm')." ADD `url` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm',  'topcolor')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm')." ADD `topcolor` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm',  'data')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm')." ADD `data` varchar(800) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hsh_tools_tm_log',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm_log')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm_log',  'template_id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm_log')." ADD `template_id` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm_log',  'send_time')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm_log')." ADD `send_time` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm_log',  'send_data')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm_log')." ADD `send_data` text NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm_log',  'send_state')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm_log')." ADD `send_state` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_tm_log',  'error_data')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_tm_log')." ADD `error_data` varchar(800) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `title` varchar(180) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'go_url')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `go_url` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'back_url')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `back_url` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'count')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `count` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'redirect_type')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `redirect_type` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'test_mode')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `test_mode` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'param_name')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `param_name` varchar(45) NOT NULL;");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'arg_state')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `arg_state` varchar(60) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('hsh_tools_url_redirect',  'state')) {
	pdo_query("ALTER TABLE ".tablename('hsh_tools_url_redirect')." ADD `state` int(3) NOT NULL DEFAULT '1';");
}

?>