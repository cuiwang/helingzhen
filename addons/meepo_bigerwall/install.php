<?php
if(pdo_tableexists('weixin_cookie')) {
		pdo_query("DROP TABLE ".tablename("weixin_cookie")." ");
}
if(pdo_tableexists('weixin_flag')) {
		pdo_query("DROP TABLE ".tablename("weixin_flag")." ");
}
if(pdo_tableexists('weixin_vote')) {
		pdo_query("DROP TABLE ".tablename("weixin_vote")." ");
}
if(pdo_tableexists('weixin_wall')) {
		pdo_query("DROP TABLE ".tablename("weixin_wall")." ");
}
if(pdo_tableexists('weixin_shake_toshake')) {
		pdo_query("DROP TABLE ".tablename("weixin_shake_toshake")." ");
}
if(pdo_tableexists('weixin_wall_num')) {
		pdo_query("DROP TABLE ".tablename("weixin_wall_num")." ");
}
if(pdo_tableexists('weixin_wall_reply')) {
		pdo_query("DROP TABLE ".tablename("weixin_wall_reply")." ");
}
if(pdo_tableexists('weixin_luckuser')) {
		pdo_query("DROP TABLE ".tablename("weixin_luckuser")." ");
}
if(pdo_tableexists('weixin_awardlist')) {
		pdo_query("DROP TABLE ".tablename("weixin_awardlist")." ");
}
if(pdo_tableexists('weixin_shake_data')) {
		pdo_query("DROP TABLE ".tablename("weixin_shake_data")." ");
}
if(pdo_tableexists('weixin_signs')) {
		pdo_query("DROP TABLE ".tablename("weixin_signs")." ");
}
if(pdo_tableexists('weixin_modules')) {
		pdo_query("DROP TABLE ".tablename("weixin_modules")." ");
}
if(pdo_tableexists('weixin_mobile_manage')) {
		pdo_query("DROP TABLE ".tablename("weixin_mobile_manage")." ");
}
if(pdo_tableexists('weixin_mobile_upload')) {
		pdo_query("DROP TABLE ".tablename("weixin_mobile_upload")." ");
}
$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_cookie')." (
`id` int(11) NOT NULL AUTO_INCREMENT,
`cookie` text NOT NULL,  
`cookies` text NOT NULL, 
`token` int(11) NOT NULL,
 `weid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "CREATE TABLE IF NOT EXISTS ".tablename('weixin_flag')." (
`id` int(11) NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `openid` varchar(255) NOT NULL,
 `fakeid` varchar(100) NOT NULL,
 `flag` int(11) NOT NULL,
 `vote` int(11) NOT NULL,
 `nickname` varchar(255) NOT NULL,
 `avatar` text NOT NULL,
`content` text NOT NULL,
 `sex` varchar(255) NOT NULL,
 `cjstatu` int(11) NOT NULL DEFAULT '0',
`rid` int(10) unsigned NOT NULL COMMENT '用户当前所在的微信墙话题',
 `isjoin` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否正在加入话题',
 `isblacklist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否是黑名单',
 `lastupdate` int(10) unsigned NOT NULL COMMENT '用户最后发表时间',
 `verify` varchar(10) NOT NULL,
  `status` int(1) NOT NULL,
 `othid` int(10) NOT NULL,
 `sign` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户是否已经签到',
 `signtime` int(12) NOT NULL DEFAULT '0' COMMENT '用户签到时间',
 `getaward` int(12) NOT NULL DEFAULT '0',
 `msgid` varchar(12) NOT NULL,
 `mobile` varchar(15) NOT NULL,
 `realname` varchar(20) NOT NULL,
 `award_id` varchar(20) NOT NULL DEFAULT 'meepo' COMMENT '是否正在加入话题',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_vote')." (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`name` text NOT NULL,
`vote_img` varchar(300) NOT NULL,
`res` int(11) NOT NULL,
`rid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_wall')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
`openid` varchar(100) NOT NULL,
 `messageid` int(11) NOT NULL,
 `num` int(11) NOT NULL,
`content` text NOT NULL,
`nickname` text NOT NULL,
 `avatar` text NOT NULL,
`ret` int(11) NOT NULL,
 `status` int(11) NOT NULL,
 `image` text NOT NULL, 
`type` varchar(10) NOT NULL COMMENT '发表内容类型',
 `isshow` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示',
 `createtime` int(10) NOT NULL,
`rid` int(10) unsigned NOT NULL COMMENT '用户当前所在的微信墙话题',
 `isblacklist` int(1) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_shake_toshake')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `avatar` text NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_wall_num')." (
`id` int(11) NOT NULL AUTO_INCREMENT,
 `rid` int(10)  NOT NULL COMMENT '用户当前所在的微信墙话题',
 `num` int(11) NOT NULL,
`weid` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_wall_reply')." (
`id` int(10)  NOT NULL AUTO_INCREMENT,
`rid` int(10)  NOT NULL COMMENT '规则ID',
 `weid` int(10) NOT NULL,
 `enter_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '进入提示',
 `subit_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '首次关注进入提示',
 `quit_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '退出提示',
 `send_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
 `3dsign` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核',
 `3dsign_title` varchar(300) NOT NULL DEFAULT '' COMMENT '退出提示',
 `3dsign_bg` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
 `3dsign_join_words` varchar(300) NOT NULL DEFAULT '' COMMENT '退出提示',
 `3d_noavatar` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
 `3dsign_show_info` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核',
 `3dsign_logo` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
 `3dsign_words` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
 `3dsign_gap` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核',
 `3dsign_persons` int(10)  NOT NULL DEFAULT '200' COMMENT '是否需要审核',
 `table_time` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核',
 `sphere_time` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核',
 `helix_time` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核',
 `grid_time` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核',
 `quit_command` varchar(10) NOT NULL DEFAULT '' COMMENT '退出指令', 
 `timeout` int(10)  NOT NULL DEFAULT '0' COMMENT '超时时间',  
`isshow` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核',
 `lurumobile` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核',
 `lurucheck` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核',
 `gz_must` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核',
  `chaoshi_tips` varchar(300) NOT NULL DEFAULT '' COMMENT '发表提示',
  `isopen` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '摇一摇状态',
`votetitle` varchar(300) NOT NULL DEFAULT '' COMMENT '投票标题',
`qdtitle` varchar(300) NOT NULL DEFAULT '' COMMENT '签到标题',
`votepower` varchar(300) NOT NULL DEFAULT '' COMMENT '投票页面版权',
`yyyzhuti` varchar(300) NOT NULL DEFAULT '' COMMENT '摇一摇主题',
`cjname` varchar(300) NOT NULL DEFAULT '' COMMENT '抽奖名字',
`cjimgurl` varchar(300) NOT NULL DEFAULT '' COMMENT '抽奖主题图片',
`loginpass` varchar(300) NOT NULL DEFAULT '' COMMENT '主持人登录密码',
`indexstyle` varchar(300) NOT NULL DEFAULT '' COMMENT '风格',
`danmutime` int(10)  NOT NULL DEFAULT '20' COMMENT '弹幕时间',
`refreshtime` int(10)  NOT NULL DEFAULT '0' COMMENT '刷新时间',
`saytasktime` int(10)  NOT NULL DEFAULT '0' COMMENT '刷新时间',
`yyyendtime` int(10) NOT NULL DEFAULT '0' COMMENT '摇一摇结束总摇晃数目',
`yyyshowperson` int(10)  NOT NULL DEFAULT '0' COMMENT '摇一摇结果显示人数',
`voterefreshtime` int(10)  NOT NULL DEFAULT '0' COMMENT 'tp刷新时间',
`qdqshow` int(10)  NOT NULL DEFAULT '0' COMMENT '签到墙是否显示',
`baheshow` int(10)  NOT NULL DEFAULT '0' COMMENT '签到墙是否显示',
`yyyshow` int(10)  NOT NULL DEFAULT '0' COMMENT '摇一摇是否显示',
`ddpshow` int(10)  NOT NULL DEFAULT '0' COMMENT '对对碰是否显示',
`tpshow` int(10)  NOT NULL DEFAULT '0' COMMENT '投票是否显示',
`cjshow` int(10) NOT NULL DEFAULT '0' COMMENT '抽奖是否显示',
`danmushow` int(10)  NOT NULL DEFAULT '0' COMMENT '抽奖是否显示',
`cjnum_tag` int(10)  NOT NULL DEFAULT '0' COMMENT '按人数抽奖是否开启',
`cjnum_exclude` int(10)  NOT NULL DEFAULT '0' COMMENT '按人数抽奖是否可以重复中奖',
`cjtag_exclude` int(10)  NOT NULL DEFAULT '0' COMMENT '按人数抽奖是否可以重复中奖',
`defaultshow` int(10)  NOT NULL DEFAULT '2' COMMENT '默认打开哪面墙',
`yyyrealman` int(10)  NOT NULL DEFAULT '0' COMMENT '真实人数',
`yyybgimg` varchar(300) NOT NULL COMMENT '摇一摇背景',
`danmubgimg` varchar(300) NOT NULL COMMENT '弹幕背景',
`gz_url` varchar(300) NOT NULL COMMENT '弹幕背景',
`bg_music` varchar(300) NOT NULL COMMENT '弹幕背景',
`bg_music_on` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '真实人数',
`image_open` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '真实人数',
`bahe_status` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '真实人数',
`saywords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`signwords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`cjwords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`votewords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`ddpwords` varchar(300) NOT NULL COMMENT '摇一摇背景',
`danmuwords` varchar(300) NOT NULL COMMENT '弹幕标题',
`toplogo` varchar(300) NOT NULL COMMENT '弹幕标题',
`realman` int(10)  COMMENT '摇一摇随机抽取人数',
`bgimg` varchar(100)  COMMENT '首页背景图片',
`fontcolor` varchar(20)  COMMENT '文字颜色',
`votemam` int(20)  COMMENT '投票总人数限制',
`starttime` int(11) NOT NULL DEFAULT '0',
`endtime` int(11) NOT NULL DEFAULT '0',
`signcheck` tinyint(1) NOT NULL  DEFAULT '2',
`followagain` tinyint(1) NOT NULL  DEFAULT '2',
`renzhen` tinyint(1) NOT NULL  DEFAULT '0',
`erweima` varchar(300) NOT NULL,
`yyy_keyword` varchar(50) NOT NULL,
`tp_keyword` varchar(50) NOT NULL,
`qd_keyword` varchar(50) NOT NULL,
`login_bg` varchar(300) NOT NULL,
`mg_words` text NOT NULL COMMENT '顶部标语',
`webopen` int(10)  NOT NULL DEFAULT '0' COMMENT '真实人数',
`luru_words` text NOT NULL COMMENT '顶部标语',
`sign_success` text NOT NULL COMMENT '顶部标语',
`had_sign_content` text NOT NULL COMMENT '顶部标语',
`danmufontcolor` varchar(30) NOT NULL DEFAULT '' COMMENT '进入提示',
`danmufontsmall` int(11) NOT NULL DEFAULT '20',
`danmufontbig` int(11) NOT NULL DEFAULT '40',
`can_send` int(11) NOT NULL DEFAULT '1',
`send_luck_words` varchar(300) NOT NULL DEFAULT '你中奖啦',
`3dsign_logo_width` int(11) NOT NULL DEFAULT '400',
`3dsign_logo_height` int(11) NOT NULL DEFAULT '400',
`new_mess` tinyint(1) NOT NULL  DEFAULT '1',
`bahe_time` int(11) NOT NULL DEFAULT '20',
`bahe_web_bg_image` varchar(300) NOT NULL DEFAULT '',
`bahe_web_adv4_image` varchar(300) NOT NULL DEFAULT '',
`bahe_web_adv3_image` varchar(300) NOT NULL DEFAULT '',
`bahe_web_adv2_image` varchar(300) NOT NULL DEFAULT '',
`bahe_web_adv1_image` varchar(300) NOT NULL DEFAULT '',
`bahe_web_person4_image` varchar(300) NOT NULL DEFAULT '',
`bahe_web_person3_image` varchar(300) NOT NULL DEFAULT '',
`bahe_web_person2_image` varchar(300) NOT NULL DEFAULT '',
`bahe_web_person1_image` varchar(300) NOT NULL DEFAULT '',
`bahe_web_zhuti2_img` varchar(300) NOT NULL DEFAULT '',
`bahe_web_zhuti1_img` varchar(300) NOT NULL DEFAULT '',
`bahe_team2_image` varchar(300) NOT NULL DEFAULT '',
`bahe_team1_image` varchar(300) NOT NULL DEFAULT '',
`bahe_team2_name` varchar(300) NOT NULL DEFAULT '',
`bahe_team1_name` varchar(300) NOT NULL DEFAULT '',
`bahe_title` varchar(300) NOT NULL DEFAULT '',
`bahe_joinwords` text NOT NULL DEFAULT '',
`bahe_bgmusic` varchar(300) NOT NULL DEFAULT '',
`bahe_web_big_bg` varchar(300) NOT NULL DEFAULT '',
`activity_starttime` int(11) NOT NULL DEFAULT '0',
`activity_endtime` int(11) NOT NULL DEFAULT '0',
`activity_title` varchar(300) NOT NULL DEFAULT '',
`cj_config` tinyint(1) NOT NULL DEFAULT '1',
`qd_zhufus` text NOT NULL COMMENT '顶部标语',
`qd_weixin_bg` varchar(300) NOT NULL,
`yyy_pc_word` varchar(300) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_awardlist')." (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`displayid` int(10)  NOT NULL DEFAULT '0' COMMENT '排序', 
`weid` int(10)  NOT NULL COMMENT '主公众号', 
 `luck_name` varchar(100) NOT NULL DEFAULT '' COMMENT '奖品名称',
 `luckid` int(10) NOT NULL DEFAULT '0' COMMENT '奖项活动ID来此关键词的rid也是按人数抽奖的id',
 `num` int(10) NOT NULL DEFAULT '0' COMMENT '此项奖品的已经中奖人数',
 `tag_name` varchar(100) NOT NULL DEFAULT '' COMMENT '第几等奖',
 `tagNum` int(10) NOT NULL DEFAULT '0' COMMENT '奖品数量',
 `num_exclude` tinyint(1)  NOT NULL DEFAULT '1' COMMENT '是否准许按人数抽奖的时候重复中奖',
`tag_exclude` tinyint(1)  NOT NULL DEFAULT '1' COMMENT '是否准许按第几等奖抽奖的时候重复中奖',
 `nd` varchar(500)   NULL  COMMENT '内定抽奖粉丝ID字符串',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_luckuser')." (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10)  NOT NULL COMMENT '主公众号',  
 `awardid` int(10) NOT NULL DEFAULT '0' COMMENT '奖项活动ID',
 `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '中奖时间',
 `openid` varchar(200) NOT NULL DEFAULT '' COMMENT '粉丝标识',
 `bypername` varchar(200) NULL  COMMENT '默认为空，只要选择了按人数才能有值',
 `rid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS ".tablename('weixin_shake_data')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `avatar` text NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "CREATE TABLE IF NOT EXISTS ".tablename('weixin_signs')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `openid` varchar(255) NOT NULL,
	`nickname` varchar(255) NOT NULL,
	`avatar` varchar(300) NOT NULL,
  `content` varchar(255) NOT NULL,
	`status` tinyint(1) NOT NULL,
  `rid` int(11) NOT NULL,
	 `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
pdo_query($sql);

$sql = "CREATE TABLE IF NOT EXISTS ".tablename('weixin_modules')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
	`bg` varchar(300) NOT NULL,
  `modules_url` varchar(500) NOT NULL,
	`rid` int(11) NOT NULL,
	`createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
pdo_query($sql);

$sql = "CREATE TABLE IF NOT EXISTS ".tablename('weixin_mobile_manage')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `mobile` varchar(15) NOT NULL,
 `realname` varchar(20) NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('weixin_mobile_upload') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
 `rid` int(11) NOT NULL,
`weid` int(11) NOT NULL  ,
`previous_name`varchar(100) NOT NULL,
`now_name`varchar(100) NOT NULL,
`file_path`varchar(300) NOT NULL,
`createtime` int(10) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "CREATE TABLE IF NOT EXISTS " . tablename('weixin_bahe_team') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`openid` varchar(255) NOT NULL,
`old_team` tinyint(1) NOT NULL DEFAULT '0',
`team` tinyint(1) NOT NULL DEFAULT '0',
`avatar` varchar(300) NOT NULL,
`nickname` varchar(300) NOT NULL,
`point` int(11) NOT NULL,
`createtime` int(10) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

$sql = "CREATE TABLE IF NOT EXISTS " . tablename('weixin_bahe_prize') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`prize` text NOT NULL,  
`createtime` int(10) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);
