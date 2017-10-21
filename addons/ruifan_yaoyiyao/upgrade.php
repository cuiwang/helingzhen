<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_ruifan_yaoyiyao_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  `adcontent` text NOT NULL,
  `showtime` int(5) DEFAULT '0',
  `adtitle` varchar(250) DEFAULT '' COMMENT '广告标题',
  `adimg` varchar(250) DEFAULT '' COMMENT '广告图片',
  `adurl` varchar(250) DEFAULT '0' COMMENT '广告链接',
  `status` tinyint(2) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ruifan_yaoyiyao_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `from_user2` varchar(50) DEFAULT '0' COMMENT '非认证服务号借用获取的ID',
  `fname` varchar(20) DEFAULT '' COMMENT '登记信息(姓名等)',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `name` varchar(250) DEFAULT '' COMMENT '名称',
  `prizetype` varchar(250) DEFAULT '' COMMENT '类型',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  `giscredt` tinyint(2) DEFAULT '0',
  `gcredit` decimal(11,2) DEFAULT '0.00',
  `ticket` varchar(255) DEFAULT '' COMMENT '微信卡券',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ruifan_yaoyiyao_base` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `rules` text NOT NULL,
  `content` varchar(200) DEFAULT '',
  `ad_url` varchar(300) DEFAULT '' COMMENT 'dpm1',
  `start_picurl` varchar(200) DEFAULT '',
  `top_picurl` varchar(200) DEFAULT '',
  `isshow` tinyint(1) DEFAULT '0',
  `afansshow` tinyint(2) DEFAULT '0' COMMENT '显示中奖人数量',
  `isqq` tinyint(1) DEFAULT '0' COMMENT '显示首页中奖滚动信息',
  `isemail` tinyint(1) DEFAULT '0' COMMENT '显示摇一摇图标',
  `isaddr` tinyint(1) DEFAULT '0',
  `isjiangpin` tinyint(1) DEFAULT '0',
  `istoday` tinyint(1) DEFAULT '0',
  `starttime` int(10) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `backpicurl` varchar(200) DEFAULT '',
  `indeximg` varchar(300) DEFAULT '',
  `award_times` int(11) DEFAULT '0',
  `number_times` int(11) DEFAULT '0',
  `copyright` varchar(20) DEFAULT '',
  `createtime` int(10) DEFAULT '0',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(300) DEFAULT '',
  `share_url` varchar(300) DEFAULT '',
  `share_top` int(11) DEFAULT '0',
  `share_num` int(11) DEFAULT '0',
  `sendhb_num` int(11) DEFAULT '0',
  `share_img` varchar(300) DEFAULT '',
  `show_num` tinyint(2) DEFAULT '0',
  `sendhb_type` tinyint(1) DEFAULT '0',
  `issbck` tinyint(1) DEFAULT '0' COMMENT '红包提现是否需要审核',
  `istel_show` tinyint(2) DEFAULT '0' COMMENT '填手机号方式',
  `share_type` tinyint(2) DEFAULT '0' COMMENT '转发类型',
  `most_num_times` int(11) DEFAULT '0',
  `hexiaopassword` varchar(50) DEFAULT '',
  `minad_type` tinyint(1) DEFAULT '0',
  `show_minad` int(5) DEFAULT '0',
  `minad_content` text NOT NULL,
  `minad_url` varchar(300) DEFAULT '',
  `minad_backcolor` varchar(200) DEFAULT '',
  `viewnum` int(11) DEFAULT '0',
  `fansnum` int(11) DEFAULT '0',
  `allowip` text NOT NULL,
  `isallowip` tinyint(2) DEFAULT '0',
  `isadshow` tinyint(2) DEFAULT '0',
  `indexthemes` tinyint(2) DEFAULT '0',
  `dpm_img` varchar(300) DEFAULT '',
  `dpm_title` varchar(300) DEFAULT '',
  `dpm_music` varchar(300) DEFAULT '',
  `dpm_logo` varchar(300) DEFAULT '',
  `dpm_time` int(11) DEFAULT '0',
  `dpm_djtime` tinyint(2) DEFAULT '0',
  `dpm_jptype` int(11) DEFAULT '0',
  `dpm_hb` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ruifan_yaoyiyao_cardticket` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `createtime` varchar(20) DEFAULT NULL,
  `ticket` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ruifan_yaoyiyao_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `sharecreditnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享得积分数量',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ruifan_yaoyiyao_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `fname` varchar(20) DEFAULT '' COMMENT '登记信息(姓名等)',
  `fqq` varchar(20) DEFAULT '' COMMENT '登记信息(QQ等)',
  `femail` varchar(50) DEFAULT '' COMMENT '登记信息(邮箱等)',
  `faddr` varchar(300) DEFAULT '' COMMENT '登记信息(地址等)',
  `todaynum` int(11) DEFAULT '0',
  `todaycredit` int(11) DEFAULT '0' COMMENT '分享已经被使用的量',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `totalnum` int(11) DEFAULT '0',
  `creditnum` int(11) DEFAULT '0',
  `awardnum` int(11) DEFAULT '0',
  `last_time` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `minad` tinyint(1) DEFAULT '0' COMMENT '首次广告',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享得积分数量',
  `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ruifan_yaoyiyao_hsetting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `set` text,
  `hbwishing` varchar(100) DEFAULT '' COMMENT '红包祝福语',
  `hbactname` varchar(100) DEFAULT '' COMMENT '红包活动',
  `hbremark` varchar(150) DEFAULT '' COMMENT '红包描述',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ruifan_yaoyiyao_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `weid` int(11) DEFAULT '0',
  `gtype` varchar(250) DEFAULT '' COMMENT '奖品名称',
  `gname` varchar(250) DEFAULT '' COMMENT '奖品图片',
  `gnum` int(11) DEFAULT '0' COMMENT '奖品数量',
  `gdraw` int(11) DEFAULT '0' COMMENT '中奖数量',
  `grate` double DEFAULT '0' COMMENT '奖品概率',
  `giscredt` tinyint(2) DEFAULT '0' COMMENT '奖品类型',
  `gcredit` double DEFAULT '0' COMMENT '奖品方式',
  `hbcredit1` decimal(11,2) DEFAULT '0.00' COMMENT '红包金额一',
  `hbcredit2` decimal(11,2) DEFAULT '0.00' COMMENT '红包金额二',
  `ticket` varchar(255) DEFAULT '' COMMENT '微信卡券',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ruifan_yaoyiyao_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'type')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `type` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'adcontent')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `adcontent` text NOT NULL;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'showtime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `showtime` int(5) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'adtitle')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `adtitle` varchar(250) DEFAULT '' COMMENT '广告标题';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'adimg')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `adimg` varchar(250) DEFAULT '' COMMENT '广告图片';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'adurl')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `adurl` varchar(250) DEFAULT '0' COMMENT '广告链接';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_ad',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD `status` tinyint(2) DEFAULT '0' COMMENT '状态';");
}
if(!pdo_indexexists('ruifan_yaoyiyao_ad',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('ruifan_yaoyiyao_ad',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_ad')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'from_user2')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `from_user2` varchar(50) DEFAULT '0' COMMENT '非认证服务号借用获取的ID';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'fname')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `fname` varchar(20) DEFAULT '' COMMENT '登记信息(姓名等)';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'name')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `name` varchar(250) DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `prizetype` varchar(250) DEFAULT '' COMMENT '类型';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `consumetime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `status` tinyint(4) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'giscredt')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `giscredt` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'gcredit')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `gcredit` decimal(11,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_award',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD `ticket` varchar(255) DEFAULT '' COMMENT '微信卡券';");
}
if(!pdo_indexexists('ruifan_yaoyiyao_award',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('ruifan_yaoyiyao_award',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_award')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'title')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'rules')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `rules` text NOT NULL;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'content')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `content` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'ad_url')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `ad_url` varchar(300) DEFAULT '' COMMENT 'dpm1';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'start_picurl')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `start_picurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'top_picurl')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `top_picurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'isshow')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `isshow` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'afansshow')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `afansshow` tinyint(2) DEFAULT '0' COMMENT '显示中奖人数量';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'isqq')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `isqq` tinyint(1) DEFAULT '0' COMMENT '显示首页中奖滚动信息';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'isemail')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `isemail` tinyint(1) DEFAULT '0' COMMENT '显示摇一摇图标';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'isaddr')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `isaddr` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'isjiangpin')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `isjiangpin` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'istoday')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `istoday` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `starttime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `endtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'backpicurl')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `backpicurl` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'indeximg')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `indeximg` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'award_times')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `award_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'number_times')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `number_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `copyright` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `share_title` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `share_desc` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `share_url` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'share_top')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `share_top` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'share_num')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `share_num` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'sendhb_num')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `sendhb_num` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'share_img')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `share_img` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'show_num')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `show_num` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'sendhb_type')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `sendhb_type` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'issbck')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `issbck` tinyint(1) DEFAULT '0' COMMENT '红包提现是否需要审核';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'istel_show')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `istel_show` tinyint(2) DEFAULT '0' COMMENT '填手机号方式';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'share_type')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `share_type` tinyint(2) DEFAULT '0' COMMENT '转发类型';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'most_num_times')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `most_num_times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'hexiaopassword')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `hexiaopassword` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'minad_type')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `minad_type` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'show_minad')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `show_minad` int(5) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'minad_content')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `minad_content` text NOT NULL;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'minad_url')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `minad_url` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'minad_backcolor')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `minad_backcolor` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `viewnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `fansnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'allowip')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `allowip` text NOT NULL;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'isallowip')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `isallowip` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'isadshow')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `isadshow` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'indexthemes')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `indexthemes` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'dpm_img')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `dpm_img` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'dpm_title')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `dpm_title` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'dpm_music')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `dpm_music` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'dpm_logo')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `dpm_logo` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'dpm_time')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `dpm_time` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'dpm_djtime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `dpm_djtime` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'dpm_jptype')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `dpm_jptype` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_base',  'dpm_hb')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD `dpm_hb` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('ruifan_yaoyiyao_base',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('ruifan_yaoyiyao_base',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_base')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_cardticket',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_cardticket')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_cardticket',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_cardticket')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_cardticket',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_cardticket')." ADD `createtime` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_cardticket',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_cardticket')." ADD `ticket` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'visitorstime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'sharecreditnum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `sharecreditnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享得积分数量';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_data',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数';");
}
if(!pdo_indexexists('ruifan_yaoyiyao_data',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('ruifan_yaoyiyao_data',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_data')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `from_user` varchar(50) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'fname')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `fname` varchar(20) DEFAULT '' COMMENT '登记信息(姓名等)';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'fqq')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `fqq` varchar(20) DEFAULT '' COMMENT '登记信息(QQ等)';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'femail')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `femail` varchar(50) DEFAULT '' COMMENT '登记信息(邮箱等)';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'faddr')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `faddr` varchar(300) DEFAULT '' COMMENT '登记信息(地址等)';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'todaynum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `todaynum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'todaycredit')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `todaycredit` int(11) DEFAULT '0' COMMENT '分享已经被使用的量';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'totalnum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `totalnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'creditnum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `creditnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'awardnum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `awardnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'last_time')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `last_time` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'minad')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `minad` tinyint(1) DEFAULT '0' COMMENT '首次广告';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享得积分数量';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_fans',  'sharetime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间';");
}
if(!pdo_indexexists('ruifan_yaoyiyao_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_hsetting',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_hsetting')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_hsetting',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_hsetting')." ADD `uniacid` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_hsetting',  'set')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_hsetting')." ADD `set` text;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_hsetting',  'hbwishing')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_hsetting')." ADD `hbwishing` varchar(100) DEFAULT '' COMMENT '红包祝福语';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_hsetting',  'hbactname')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_hsetting')." ADD `hbactname` varchar(100) DEFAULT '' COMMENT '红包活动';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_hsetting',  'hbremark')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_hsetting')." ADD `hbremark` varchar(150) DEFAULT '' COMMENT '红包描述';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_hsetting',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_hsetting')." ADD `createtime` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'gtype')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `gtype` varchar(250) DEFAULT '' COMMENT '奖品名称';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'gname')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `gname` varchar(250) DEFAULT '' COMMENT '奖品图片';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'gnum')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `gnum` int(11) DEFAULT '0' COMMENT '奖品数量';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'gdraw')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `gdraw` int(11) DEFAULT '0' COMMENT '中奖数量';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'grate')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `grate` double DEFAULT '0' COMMENT '奖品概率';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'giscredt')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `giscredt` tinyint(2) DEFAULT '0' COMMENT '奖品类型';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'gcredit')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `gcredit` double DEFAULT '0' COMMENT '奖品方式';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'hbcredit1')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `hbcredit1` decimal(11,2) DEFAULT '0.00' COMMENT '红包金额一';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'hbcredit2')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `hbcredit2` decimal(11,2) DEFAULT '0.00' COMMENT '红包金额二';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_reply',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD `ticket` varchar(255) DEFAULT '' COMMENT '微信卡券';");
}
if(!pdo_indexexists('ruifan_yaoyiyao_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('ruifan_yaoyiyao_reply',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_reply')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_sysset',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_sysset')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_sysset',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_sysset')." ADD `appid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_sysset',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_sysset')." ADD `appsecret` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_sysset',  'appid_share')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_sysset')." ADD `appid_share` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ruifan_yaoyiyao_sysset',  'appsecret_share')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_sysset')." ADD `appsecret_share` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('ruifan_yaoyiyao_sysset',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ruifan_yaoyiyao_sysset')." ADD KEY `idx_uniacid` (`uniacid`);");
}

?>