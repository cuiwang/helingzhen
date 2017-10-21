<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_kuaiwei_exmall_ad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `type` tinyint(1) DEFAULT '0',
  `adtitle` varchar(250) DEFAULT '' COMMENT '广告标题',
  `adimg` varchar(250) DEFAULT '' COMMENT '广告图片',
  `adurl` varchar(250) DEFAULT '0' COMMENT '广告链接',
  `status` tinyint(2) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_kuaiwei_exmall_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `from_user2` varchar(50) DEFAULT '0' COMMENT '非认证服务号借用获取的ID',
  `fname` varchar(20) DEFAULT '' COMMENT '登记信息(姓名等)',
  `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `faddr` varchar(300) DEFAULT '' COMMENT '登记信息(地址等)',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `todaycredit` int(11) DEFAULT '0' COMMENT '已兑换积分数',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑换前积分',
  `spname` varchar(250) DEFAULT '' COMMENT '商品名称',
  `sp_integrals` int(11) DEFAULT '0' COMMENT '商品兑换积分数',
  `states` tinyint(4) DEFAULT '0' COMMENT '商品状态',
  `prizetype` varchar(250) DEFAULT '' COMMENT '类型',
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `giscredt` tinyint(2) DEFAULT '0',
  `gcredit` decimal(11,2) DEFAULT '0.00',
  `ticket` varchar(255) DEFAULT '' COMMENT '微信卡券',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_kuaiwei_exmall_base` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0' COMMENT '规则id',
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号id',
  `title` varchar(255) DEFAULT '' COMMENT '商城标题',
  `share_type` tinyint(1) DEFAULT '0',
  `guanzhu_txt` varchar(300) DEFAULT '',
  `guanzhu_img` varchar(250) DEFAULT '' COMMENT '关注二维码',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(1000) DEFAULT '',
  `share_img` varchar(300) DEFAULT '',
  `btm_adtype` tinyint(1) DEFAULT '0' COMMENT '底部广告类型',
  `share_top` int(11) DEFAULT '0' COMMENT '转发赠送上限',
  `share_num` int(11) DEFAULT '0' COMMENT '分享赠送的积分数',
  `top_adtitle` varchar(250) DEFAULT '' COMMENT '头部广告标题',
  `top_adimg` varchar(250) DEFAULT '' COMMENT '头部广告图片',
  `top_adurl` varchar(250) DEFAULT '0' COMMENT '头部广告链接',
  `btm_adtitle` varchar(250) DEFAULT '' COMMENT '底部广告标题',
  `btm_adimg` varchar(250) DEFAULT '' COMMENT '底部广告图片',
  `btm_adurl` varchar(250) DEFAULT '0' COMMENT '底部广告链接',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_kuaiwei_exmall_data` (
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
  `sharecutnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享砍了多少',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_from_user` (`from_user`),
  KEY `indx_fromuser` (`fromuser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_kuaiwei_exmall_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `fansID` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '' COMMENT '用户ID',
  `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `fname` varchar(20) DEFAULT '' COMMENT '登记信息(姓名等)',
  `fqq` varchar(20) DEFAULT '' COMMENT '登记信息(QQ等)',
  `femail` varchar(50) DEFAULT '' COMMENT '是否兑奖过了',
  `faddr` varchar(300) DEFAULT '' COMMENT '登记信息(地址等)',
  `todaynum` int(11) DEFAULT '0',
  `todaycredit` int(11) DEFAULT '0' COMMENT '已兑换积分数',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `totalnum` int(11) DEFAULT '0',
  `creditnum` int(11) DEFAULT '0',
  `awardnum` int(11) DEFAULT '0',
  `last_time` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  `minad` tinyint(1) DEFAULT '0' COMMENT '首次广告',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享得积分数量',
  `cutnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '拉黑状态',
  `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_kuaiwei_exmall_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0',
  `sp_img` varchar(250) DEFAULT '' COMMENT '商品图片',
  `sp_title` varchar(250) DEFAULT '' COMMENT '商品名称',
  `sp_url` varchar(250) DEFAULT '' COMMENT '商品链接',
  `sp_numbers` int(11) DEFAULT '0' COMMENT '商品数量',
  `status` tinyint(2) DEFAULT '0' COMMENT '状态（是否启用）',
  `sp_integrals` int(11) DEFAULT '0' COMMENT '商品兑换积分数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_kuaiwei_exmall_sysset` (
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
if(!pdo_fieldexists('kuaiwei_exmall_ad',  'id')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('kuaiwei_exmall_ad',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_ad',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_ad',  'type')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD `type` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_ad',  'adtitle')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD `adtitle` varchar(250) DEFAULT '' COMMENT '广告标题';");
}
if(!pdo_fieldexists('kuaiwei_exmall_ad',  'adimg')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD `adimg` varchar(250) DEFAULT '' COMMENT '广告图片';");
}
if(!pdo_fieldexists('kuaiwei_exmall_ad',  'adurl')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD `adurl` varchar(250) DEFAULT '0' COMMENT '广告链接';");
}
if(!pdo_fieldexists('kuaiwei_exmall_ad',  'status')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD `status` tinyint(2) DEFAULT '0' COMMENT '状态';");
}
if(!pdo_indexexists('kuaiwei_exmall_ad',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('kuaiwei_exmall_ad',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_ad')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'id')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'from_user2')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `from_user2` varchar(50) DEFAULT '0' COMMENT '非认证服务号借用获取的ID';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'fname')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `fname` varchar(20) DEFAULT '' COMMENT '登记信息(姓名等)';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'faddr')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `faddr` varchar(300) DEFAULT '' COMMENT '登记信息(地址等)';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'todaycredit')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `todaycredit` int(11) DEFAULT '0' COMMENT '已兑换积分数';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '兑换前积分';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'spname')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `spname` varchar(250) DEFAULT '' COMMENT '商品名称';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'sp_integrals')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `sp_integrals` int(11) DEFAULT '0' COMMENT '商品兑换积分数';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'states')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `states` tinyint(4) DEFAULT '0' COMMENT '商品状态';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'prizetype')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `prizetype` varchar(250) DEFAULT '' COMMENT '类型';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'consumetime')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `consumetime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'giscredt')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `giscredt` tinyint(2) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'gcredit')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `gcredit` decimal(11,2) DEFAULT '0.00';");
}
if(!pdo_fieldexists('kuaiwei_exmall_award',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD `ticket` varchar(255) DEFAULT '' COMMENT '微信卡券';");
}
if(!pdo_indexexists('kuaiwei_exmall_award',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('kuaiwei_exmall_award',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_award')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'id')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `rid` int(10) unsigned DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `uniacid` int(11) DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'title')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `title` varchar(255) DEFAULT '' COMMENT '商城标题';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'share_type')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `share_type` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'guanzhu_txt')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `guanzhu_txt` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'guanzhu_img')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `guanzhu_img` varchar(250) DEFAULT '' COMMENT '关注二维码';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `share_title` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `share_desc` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'share_img')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `share_img` varchar(300) DEFAULT '';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'btm_adtype')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `btm_adtype` tinyint(1) DEFAULT '0' COMMENT '底部广告类型';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'share_top')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `share_top` int(11) DEFAULT '0' COMMENT '转发赠送上限';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'share_num')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `share_num` int(11) DEFAULT '0' COMMENT '分享赠送的积分数';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'top_adtitle')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `top_adtitle` varchar(250) DEFAULT '' COMMENT '头部广告标题';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'top_adimg')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `top_adimg` varchar(250) DEFAULT '' COMMENT '头部广告图片';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'top_adurl')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `top_adurl` varchar(250) DEFAULT '0' COMMENT '头部广告链接';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'btm_adtitle')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `btm_adtitle` varchar(250) DEFAULT '' COMMENT '底部广告标题';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'btm_adimg')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `btm_adimg` varchar(250) DEFAULT '' COMMENT '底部广告图片';");
}
if(!pdo_fieldexists('kuaiwei_exmall_base',  'btm_adurl')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD `btm_adurl` varchar(250) DEFAULT '0' COMMENT '底部广告链接';");
}
if(!pdo_indexexists('kuaiwei_exmall_base',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('kuaiwei_exmall_base',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_base')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `fromuser` varchar(50) NOT NULL DEFAULT '' COMMENT '分享人openid';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'visitorstime')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'sharecreditnum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `sharecreditnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享得积分数量';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'sharecutnum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `sharecutnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享砍了多少';");
}
if(!pdo_fieldexists('kuaiwei_exmall_data',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数';");
}
if(!pdo_indexexists('kuaiwei_exmall_data',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('kuaiwei_exmall_data',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('kuaiwei_exmall_data',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_indexexists('kuaiwei_exmall_data',  'indx_fromuser')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_data')." ADD KEY `indx_fromuser` (`fromuser`);");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'fansID')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `fansID` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `from_user` varchar(50) DEFAULT '' COMMENT '用户ID';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `tel` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'fname')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `fname` varchar(20) DEFAULT '' COMMENT '登记信息(姓名等)';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'fqq')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `fqq` varchar(20) DEFAULT '' COMMENT '登记信息(QQ等)';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'femail')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `femail` varchar(50) DEFAULT '' COMMENT '是否兑奖过了';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'faddr')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `faddr` varchar(300) DEFAULT '' COMMENT '登记信息(地址等)';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'todaynum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `todaynum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'todaycredit')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `todaycredit` int(11) DEFAULT '0' COMMENT '已兑换积分数';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'totalnum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `totalnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'creditnum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `creditnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'awardnum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `awardnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'last_time')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `last_time` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'minad')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `minad` tinyint(1) DEFAULT '0' COMMENT '首次广告';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享得积分数量';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'cutnum')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `cutnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '拉黑状态';");
}
if(!pdo_fieldexists('kuaiwei_exmall_fans',  'sharetime')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD `sharetime` int(10) DEFAULT '0' COMMENT '最后分享时间';");
}
if(!pdo_indexexists('kuaiwei_exmall_fans',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('kuaiwei_exmall_fans',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_fans')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_fieldexists('kuaiwei_exmall_goods',  'id')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('kuaiwei_exmall_goods',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD `rid` int(10) unsigned DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_goods',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_goods',  'sp_img')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD `sp_img` varchar(250) DEFAULT '' COMMENT '商品图片';");
}
if(!pdo_fieldexists('kuaiwei_exmall_goods',  'sp_title')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD `sp_title` varchar(250) DEFAULT '' COMMENT '商品名称';");
}
if(!pdo_fieldexists('kuaiwei_exmall_goods',  'sp_url')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD `sp_url` varchar(250) DEFAULT '' COMMENT '商品链接';");
}
if(!pdo_fieldexists('kuaiwei_exmall_goods',  'sp_numbers')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD `sp_numbers` int(11) DEFAULT '0' COMMENT '商品数量';");
}
if(!pdo_fieldexists('kuaiwei_exmall_goods',  'status')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD `status` tinyint(2) DEFAULT '0' COMMENT '状态（是否启用）';");
}
if(!pdo_fieldexists('kuaiwei_exmall_goods',  'sp_integrals')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD `sp_integrals` int(11) DEFAULT '0' COMMENT '商品兑换积分数';");
}
if(!pdo_indexexists('kuaiwei_exmall_goods',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('kuaiwei_exmall_goods',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_goods')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('kuaiwei_exmall_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('kuaiwei_exmall_sysset',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_sysset')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('kuaiwei_exmall_sysset',  'appid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_sysset')." ADD `appid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('kuaiwei_exmall_sysset',  'appsecret')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_sysset')." ADD `appsecret` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('kuaiwei_exmall_sysset',  'appid_share')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_sysset')." ADD `appid_share` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('kuaiwei_exmall_sysset',  'appsecret_share')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_sysset')." ADD `appsecret_share` varchar(255) DEFAULT '';");
}
if(!pdo_indexexists('kuaiwei_exmall_sysset',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('kuaiwei_exmall_sysset')." ADD KEY `idx_uniacid` (`uniacid`);");
}

?>