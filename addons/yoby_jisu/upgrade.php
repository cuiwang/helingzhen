<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_yoby_jisu_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT NULL,
  `weid` int(10) DEFAULT NULL,
  `names` varchar(20) DEFAULT NULL COMMENT '姓名',
  `phone` varchar(20) DEFAULT NULL COMMENT '手机号',
  `address` varchar(100) DEFAULT NULL COMMENT '地址',
  `play_num` int(10) DEFAULT '0' COMMENT '所玩次数',
  `nickname` varchar(100) DEFAULT NULL COMMENT '昵称',
  `headimgurl` varchar(500) DEFAULT NULL COMMENT '头像',
  `openid` varchar(128) DEFAULT NULL,
  `max_fen` int(10) DEFAULT '0' COMMENT '最高分数或总分',
  `g_fen` int(10) DEFAULT '0' COMMENT '借用分数',
  `fen` int(10) DEFAULT '0' COMMENT '自己分数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yoby_jisu_num` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分享增加次数表不用于助力游戏',
  `weid` int(10) DEFAULT NULL,
  `openid` varchar(60) DEFAULT NULL,
  `createtime` varchar(10) DEFAULT NULL COMMENT '所玩时间',
  `day_num` int(10) DEFAULT '0' COMMENT '当天所玩次数',
  `rid` int(10) DEFAULT NULL,
  `is_share` tinyint(1) DEFAULT '0' COMMENT '是否分享过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yoby_jisu_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) DEFAULT NULL,
  `weid` int(10) DEFAULT NULL,
  `start_time` int(10) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(10) DEFAULT NULL COMMENT '结束时间',
  `share_title` varchar(50) DEFAULT NULL COMMENT '分享标题',
  `share_img` varchar(200) DEFAULT NULL COMMENT '分享小图标',
  `share_desc` varchar(300) DEFAULT NULL COMMENT '分享描述',
  `hd_title` varchar(50) DEFAULT NULL COMMENT '活动图文标题',
  `hd_img` varchar(200) DEFAULT NULL COMMENT '活动图文图片',
  `hd_desc` varchar(300) DEFAULT NULL COMMENT '活动图文描述',
  `ad_img` varchar(200) DEFAULT NULL COMMENT '可替换背景图',
  `max_num` mediumint(8) DEFAULT '0' COMMENT '总次数',
  `day_num` mediumint(8) DEFAULT NULL COMMENT '每天最多次数',
  `desc` text COMMENT '本次活动介绍页面',
  `share_url` varchar(200) DEFAULT NULL COMMENT '引导链接或图片二维码',
  `copyright` varchar(100) DEFAULT NULL COMMENT '版权信息',
  `game_time` varchar(20) DEFAULT '60' COMMENT '游戏时间秒',
  `game_title` varchar(50) DEFAULT NULL COMMENT '游戏标题',
  `data` text COMMENT '参数设置',
  `isok` tinyint(1) DEFAULT '0' COMMENT '0未开始',
  `sharenum` int(10) DEFAULT '1' COMMENT '分享朋友圈增加次数',
  `pagenum` int(10) DEFAULT '20' COMMENT '排行榜显示数',
  `isreg` tinyint(1) DEFAULT '0' COMMENT '是否强制注册',
  `c_num` int(10) DEFAULT '0' COMMENT '虚假参与人数',
  `c_url` varchar(200) DEFAULT '0' COMMENT '抽奖地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_yoby_jisu_top` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '成绩记录表',
  `rid` int(10) DEFAULT NULL,
  `weid` int(10) DEFAULT NULL,
  `openid` varchar(128) DEFAULT NULL,
  `createtime` varchar(20) DEFAULT NULL COMMENT '所玩时间',
  `fen` int(10) DEFAULT '0' COMMENT '本次分数成绩',
  `g_openid` varchar(128) DEFAULT NULL COMMENT '借用者openid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('yoby_jisu_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `rid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'names')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `names` varchar(20) DEFAULT NULL COMMENT '姓名';");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'phone')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `phone` varchar(20) DEFAULT NULL COMMENT '手机号';");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `address` varchar(100) DEFAULT NULL COMMENT '地址';");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'play_num')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `play_num` int(10) DEFAULT '0' COMMENT '所玩次数';");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `nickname` varchar(100) DEFAULT NULL COMMENT '昵称';");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `headimgurl` varchar(500) DEFAULT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `openid` varchar(128) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'max_fen')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `max_fen` int(10) DEFAULT '0' COMMENT '最高分数或总分';");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'g_fen')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `g_fen` int(10) DEFAULT '0' COMMENT '借用分数';");
}
if(!pdo_fieldexists('yoby_jisu_fans',  'fen')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_fans')." ADD `fen` int(10) DEFAULT '0' COMMENT '自己分数';");
}
if(!pdo_fieldexists('yoby_jisu_num',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_num')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分享增加次数表不用于助力游戏';");
}
if(!pdo_fieldexists('yoby_jisu_num',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_num')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_num',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_num')." ADD `openid` varchar(60) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_num',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_num')." ADD `createtime` varchar(10) DEFAULT NULL COMMENT '所玩时间';");
}
if(!pdo_fieldexists('yoby_jisu_num',  'day_num')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_num')." ADD `day_num` int(10) DEFAULT '0' COMMENT '当天所玩次数';");
}
if(!pdo_fieldexists('yoby_jisu_num',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_num')." ADD `rid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_num',  'is_share')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_num')." ADD `is_share` tinyint(1) DEFAULT '0' COMMENT '是否分享过';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `rid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'start_time')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `start_time` int(10) DEFAULT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'end_time')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `end_time` int(10) DEFAULT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `share_title` varchar(50) DEFAULT NULL COMMENT '分享标题';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'share_img')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `share_img` varchar(200) DEFAULT NULL COMMENT '分享小图标';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `share_desc` varchar(300) DEFAULT NULL COMMENT '分享描述';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'hd_title')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `hd_title` varchar(50) DEFAULT NULL COMMENT '活动图文标题';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'hd_img')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `hd_img` varchar(200) DEFAULT NULL COMMENT '活动图文图片';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'hd_desc')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `hd_desc` varchar(300) DEFAULT NULL COMMENT '活动图文描述';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'ad_img')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `ad_img` varchar(200) DEFAULT NULL COMMENT '可替换背景图';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'max_num')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `max_num` mediumint(8) DEFAULT '0' COMMENT '总次数';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'day_num')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `day_num` mediumint(8) DEFAULT NULL COMMENT '每天最多次数';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'desc')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `desc` text COMMENT '本次活动介绍页面';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `share_url` varchar(200) DEFAULT NULL COMMENT '引导链接或图片二维码';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `copyright` varchar(100) DEFAULT NULL COMMENT '版权信息';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'game_time')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `game_time` varchar(20) DEFAULT '60' COMMENT '游戏时间秒';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'game_title')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `game_title` varchar(50) DEFAULT NULL COMMENT '游戏标题';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'data')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `data` text COMMENT '参数设置';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'isok')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `isok` tinyint(1) DEFAULT '0' COMMENT '0未开始';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `sharenum` int(10) DEFAULT '1' COMMENT '分享朋友圈增加次数';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'pagenum')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `pagenum` int(10) DEFAULT '20' COMMENT '排行榜显示数';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'isreg')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `isreg` tinyint(1) DEFAULT '0' COMMENT '是否强制注册';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'c_num')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `c_num` int(10) DEFAULT '0' COMMENT '虚假参与人数';");
}
if(!pdo_fieldexists('yoby_jisu_reply',  'c_url')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_reply')." ADD `c_url` varchar(200) DEFAULT '0' COMMENT '抽奖地址';");
}
if(!pdo_fieldexists('yoby_jisu_top',  'id')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_top')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '成绩记录表';");
}
if(!pdo_fieldexists('yoby_jisu_top',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_top')." ADD `rid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_top',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_top')." ADD `weid` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_top',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_top')." ADD `openid` varchar(128) DEFAULT NULL;");
}
if(!pdo_fieldexists('yoby_jisu_top',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_top')." ADD `createtime` varchar(20) DEFAULT NULL COMMENT '所玩时间';");
}
if(!pdo_fieldexists('yoby_jisu_top',  'fen')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_top')." ADD `fen` int(10) DEFAULT '0' COMMENT '本次分数成绩';");
}
if(!pdo_fieldexists('yoby_jisu_top',  'g_openid')) {
	pdo_query("ALTER TABLE ".tablename('yoby_jisu_top')." ADD `g_openid` varchar(128) DEFAULT NULL COMMENT '借用者openid';");
}

?>