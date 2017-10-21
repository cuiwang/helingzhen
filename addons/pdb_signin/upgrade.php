<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_pdb_signin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `rid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '活动主题',
  `times_perday` int(11) NOT NULL DEFAULT '0' COMMENT '每天签到次数',
  `credit_pertime` int(11) NOT NULL DEFAULT '0' COMMENT '每天签到次数',
  `credit_first` int(11) NOT NULL DEFAULT '0' COMMENT '首次签到奖励积分',
  `times_total` int(11) NOT NULL DEFAULT '0' COMMENT '累计签到多少次',
  `credit_total` int(11) NOT NULL DEFAULT '0' COMMENT '累计签到奖励积分',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `is_longterm` tinyint(1) NOT NULL DEFAULT '0',
  `notify_message` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '签到成功的提示',
  `repeat_message` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '重复签到的提示',
  `finished_message` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '活动结束的提示',
  `nostart_message` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '活动未开始的提示',
  `ad_content` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '广告内容',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_pdb_signin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `signin_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID',
  `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `fans_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '粉丝openid',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员Id',
  `credit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '获得的积分',
  `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '签到备注',
  `log_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '签到时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('pdb_signin',  'id')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('pdb_signin',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `uniacid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('pdb_signin',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `rid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('pdb_signin',  'title')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '活动主题';");
}
if(!pdo_fieldexists('pdb_signin',  'times_perday')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `times_perday` int(11) NOT NULL DEFAULT '0' COMMENT '每天签到次数';");
}
if(!pdo_fieldexists('pdb_signin',  'credit_pertime')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `credit_pertime` int(11) NOT NULL DEFAULT '0' COMMENT '每天签到次数';");
}
if(!pdo_fieldexists('pdb_signin',  'credit_first')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `credit_first` int(11) NOT NULL DEFAULT '0' COMMENT '首次签到奖励积分';");
}
if(!pdo_fieldexists('pdb_signin',  'times_total')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `times_total` int(11) NOT NULL DEFAULT '0' COMMENT '累计签到多少次';");
}
if(!pdo_fieldexists('pdb_signin',  'credit_total')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `credit_total` int(11) NOT NULL DEFAULT '0' COMMENT '累计签到奖励积分';");
}
if(!pdo_fieldexists('pdb_signin',  'start_time')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间';");
}
if(!pdo_fieldexists('pdb_signin',  'end_time')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间';");
}
if(!pdo_fieldexists('pdb_signin',  'is_longterm')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `is_longterm` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('pdb_signin',  'notify_message')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `notify_message` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '签到成功的提示';");
}
if(!pdo_fieldexists('pdb_signin',  'repeat_message')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `repeat_message` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '重复签到的提示';");
}
if(!pdo_fieldexists('pdb_signin',  'finished_message')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `finished_message` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '活动结束的提示';");
}
if(!pdo_fieldexists('pdb_signin',  'nostart_message')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `nostart_message` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '活动未开始的提示';");
}
if(!pdo_fieldexists('pdb_signin',  'ad_content')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `ad_content` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '广告内容';");
}
if(!pdo_fieldexists('pdb_signin',  'create_time')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间';");
}
if(!pdo_fieldexists('pdb_signin',  'update_time')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间';");
}
if(!pdo_fieldexists('pdb_signin',  'status')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('pdb_signin_log',  'id')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('pdb_signin_log',  'signin_id')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin_log')." ADD `signin_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动ID';");
}
if(!pdo_fieldexists('pdb_signin_log',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin_log')." ADD `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT '公众号ID';");
}
if(!pdo_fieldexists('pdb_signin_log',  'fans_id')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin_log')." ADD `fans_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '粉丝openid';");
}
if(!pdo_fieldexists('pdb_signin_log',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin_log')." ADD `uid` int(11) NOT NULL DEFAULT '0' COMMENT '会员Id';");
}
if(!pdo_fieldexists('pdb_signin_log',  'credit')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin_log')." ADD `credit` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '获得的积分';");
}
if(!pdo_fieldexists('pdb_signin_log',  'note')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin_log')." ADD `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '签到备注';");
}
if(!pdo_fieldexists('pdb_signin_log',  'log_time')) {
	pdo_query("ALTER TABLE ".tablename('pdb_signin_log')." ADD `log_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '签到时间';");
}

?>