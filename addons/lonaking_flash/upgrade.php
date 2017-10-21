<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_lonaking_flash_cache` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `do_option` varchar(100) DEFAULT NULL,
  `do_res` text,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lonaking_hb_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `to_openid` varchar(255) DEFAULT '',
  `status` tinyint(1) DEFAULT '0' COMMENT '0 失败 1成功',
  `mch_billno` varchar(100) DEFAULT '',
  `send_data` text COMMENT '发送的数据json',
  `error_code` varchar(100) DEFAULT '',
  `response_obj_text` text COMMENT '微信回复内容',
  `response_return_code` varchar(200) DEFAULT '',
  `response_result_code` varchar(200) DEFAULT '',
  `response_return_msg` varchar(200) DEFAULT '',
  `response_err_code` varchar(200) DEFAULT '',
  `response_err_code_des` varchar(255) DEFAULT '',
  `amount` int(11) DEFAULT '0' COMMENT '发送金额',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_lonaking_location_cache` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(255) DEFAULT '',
  `w_lng` decimal(10,6) DEFAULT '0.000000',
  `w_lat` decimal(10,6) DEFAULT '0.000000',
  `b_lng` decimal(10,6) DEFAULT '0.000000',
  `b_lat` decimal(10,6) DEFAULT '0.000000',
  `d` decimal(10,2) DEFAULT '0.00',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('lonaking_flash_cache',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_flash_cache')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lonaking_flash_cache',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_flash_cache')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_flash_cache',  'do_option')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_flash_cache')." ADD `do_option` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_flash_cache',  'do_res')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_flash_cache')." ADD `do_res` text;");
}
if(!pdo_fieldexists('lonaking_flash_cache',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_flash_cache')." ADD `create_time` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_flash_cache',  'update_time')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_flash_cache')." ADD `update_time` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_hb_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lonaking_hb_record',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_hb_record',  'to_openid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `to_openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'status')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `status` tinyint(1) DEFAULT '0' COMMENT '0 失败 1成功';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'mch_billno')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `mch_billno` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'send_data')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `send_data` text COMMENT '发送的数据json';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'error_code')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `error_code` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'response_obj_text')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `response_obj_text` text COMMENT '微信回复内容';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'response_return_code')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `response_return_code` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'response_result_code')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `response_result_code` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'response_return_msg')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `response_return_msg` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'response_err_code')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `response_err_code` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'response_err_code_des')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `response_err_code_des` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `amount` int(11) DEFAULT '0' COMMENT '发送金额';");
}
if(!pdo_fieldexists('lonaking_hb_record',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `create_time` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_hb_record',  'update_time')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_hb_record')." ADD `update_time` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_location_cache',  'id')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('lonaking_location_cache',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_location_cache',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `openid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('lonaking_location_cache',  'w_lng')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `w_lng` decimal(10,6) DEFAULT '0.000000';");
}
if(!pdo_fieldexists('lonaking_location_cache',  'w_lat')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `w_lat` decimal(10,6) DEFAULT '0.000000';");
}
if(!pdo_fieldexists('lonaking_location_cache',  'b_lng')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `b_lng` decimal(10,6) DEFAULT '0.000000';");
}
if(!pdo_fieldexists('lonaking_location_cache',  'b_lat')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `b_lat` decimal(10,6) DEFAULT '0.000000';");
}
if(!pdo_fieldexists('lonaking_location_cache',  'd')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `d` decimal(10,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('lonaking_location_cache',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `create_time` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('lonaking_location_cache',  'update_time')) {
	pdo_query("ALTER TABLE ".tablename('lonaking_location_cache')." ADD `update_time` int(11) DEFAULT NULL;");
}

?>