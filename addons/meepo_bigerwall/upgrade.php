<?php
if(!pdo_fieldexists('weixin_wall_reply', 'activity_starttime')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `activity_starttime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'activity_title')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `activity_title` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_bgmusic')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_bgmusic` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'activity_endtime')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `activity_endtime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'danmushow')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmushow` int(3) NOT NULL DEFAULT '0' COMMENT '弹幕显示';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'baheshow')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `baheshow` int(3) NOT NULL DEFAULT '0' COMMENT '拔河显示';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'danmutime')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmutime` int(10) NOT NULL DEFAULT '20'  COMMENT '弹幕显示时';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'gz_must')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `gz_must` tinyint(1) NOT NULL DEFAULT '0'  COMMENT '是否关注';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'new_mess')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `new_mess` tinyint(1) NOT NULL DEFAULT '1'  COMMENT '是否关注';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bgimg')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bgimg` varchar(100)  COMMENT '首页背景图片';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_show_info')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_show_info` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_gap')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_gap` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'table_time')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `table_time` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'sphere_time')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `sphere_time` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'helix_time')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `helix_time` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'grid_time')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `grid_time` int(10)  NOT NULL DEFAULT '15' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_persons')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_persons` int(10)  NOT NULL DEFAULT '200' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_title')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_title` varchar(200)  COMMENT '首页背景图片';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_bg')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_bg` varchar(300)  COMMENT '首页背景图片';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_logo')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_logo` varchar(300)  COMMENT '首页背景图片';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_words')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_words` varchar(300)  COMMENT '首页背景图片';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_join_words')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_join_words` varchar(200)  COMMENT '首页背景图片';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3d_noavatar')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3d_noavatar` varchar(200)  COMMENT '首页背景图片';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'realman')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `realman` int(10)  COMMENT '摇一摇随机抽取人数';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'fontcolor')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `fontcolor` varchar(20)  COMMENT '文字颜色';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'votemam')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `votemam` int(20)  COMMENT '投票总人数限制';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'starttime')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `starttime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'endtime')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `endtime` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'yyybgimg')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `yyybgimg` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'qdtitle')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `qdtitle` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'yyyrealman')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `yyyrealman` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'qd_weixin_bg')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `qd_weixin_bg` varchar(300)  COMMENT '首页背景图片';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'yyy_pc_word')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `yyy_pc_word` varchar(300)  COMMENT '首页背景图片';");
}
if(pdo_tableexists('weixin_shake_data')) {
		pdo_query("DROP TABLE ".tablename("weixin_shake_data")." ");
}
pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('weixin_shake_data')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `point` int(11) NOT NULL,
  `avatar` text NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
if(!pdo_fieldexists('weixin_wall_reply', 'danmubgimg')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmubgimg` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'saywords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `saywords` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'signwords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `signwords` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'cjwords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `cjwords` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'ddpwords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `ddpwords` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'votewords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `votewords` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'danmuwords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmuwords` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'toplogo')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `toplogo` varchar(300) NOT NULL;");
}
pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('weixin_signs')." (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
if(!pdo_fieldexists('weixin_wall_reply', 'signcheck')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `signcheck` tinyint(1) NOT NULL  DEFAULT '2' ;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'followagain')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `followagain` tinyint(1) NOT NULL  DEFAULT '2' ;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'renzhen')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `renzhen` tinyint(1) NOT NULL  DEFAULT '2' ;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'erweima')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `erweima` varchar(300) NOT NULL;");
}
pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('weixin_modules')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
	`bg` varchar(300) NOT NULL,
  `modules_url` varchar(500) NOT NULL,
	`rid` int(11) NOT NULL,
	`createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");
if(!pdo_fieldexists('weixin_wall_reply', 'saytasktime')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `saytasktime` int(11) NOT NULL DEFAULT '4';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'yyy_keyword')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `yyy_keyword` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'tp_keyword')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `tp_keyword` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'login_bg')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `login_bg` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_vote', 'vote_img')) {
    pdo_query("ALTER TABLE ".tablename('weixin_vote')." ADD `vote_img` varchar(300) NOT NULL;");
}
if(!pdo_fieldexists('weixin_wall_reply', 'qd_keyword')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `qd_keyword` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('weixin_flag', 'award_id')) {
    pdo_query("ALTER TABLE ".tablename('weixin_flag')." ADD `award_id` varchar(20) NOT NULL DEFAULT 'meepo' COMMENT '是否正在加入话题';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'mg_words')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `mg_words` text NOT NULL COMMENT '敏感词汇';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'lurucheck')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `lurucheck` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '是否需要审核';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'webopen')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `webopen` int(10)  NOT NULL DEFAULT '0' COMMENT '真实人数';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_logo_width')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_logo_width` int(10)  NOT NULL DEFAULT '400' COMMENT '真实人数';");
}
if(!pdo_fieldexists('weixin_wall_reply', '3dsign_logo_height')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `3dsign_logo_height` int(10)  NOT NULL DEFAULT '400' COMMENT '真实人数';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'luru_words')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `luru_words` text NOT NULL COMMENT '敏感词汇';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'sign_success')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `sign_success` text NOT NULL COMMENT '敏感词汇';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'had_sign_content')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `had_sign_content` text NOT NULL COMMENT '敏感词汇';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'send_luck_words')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `send_luck_words` varchar(300) NOT NULL DEFAULT '你中奖啦';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'gz_url')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `gz_url` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bg_music')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bg_music` varchar(300) NOT NULL DEFAULT '';");
}

if(!pdo_fieldexists('weixin_wall_reply', 'bg_music_on')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bg_music_on` tinyint(1)  NOT NULL DEFAULT '0' COMMENT '开关';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'can_send')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `can_send` int(10)  NOT NULL DEFAULT '1' COMMENT '真实人数';");
}
pdo_query("CREATE TABLE IF NOT EXISTS ".tablename('weixin_mobile_manage')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `mobile` varchar(15) NOT NULL,
 `realname` varchar(20) NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

pdo_query("CREATE TABLE IF NOT EXISTS " . tablename('weixin_mobile_upload') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
 `rid` int(11) NOT NULL,
`weid` int(11) NOT NULL  ,
`previous_name`varchar(100) NOT NULL,
`now_name`varchar(100) NOT NULL,
`file_path`varchar(300) NOT NULL,
`createtime` int(10) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
if(!pdo_fieldexists('weixin_wall_reply', 'danmufontsmall')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmufontsmall` int(11) NOT NULL DEFAULT '20';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'danmufontbig')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmufontbig` int(11) NOT NULL DEFAULT '40';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'danmufontcolor')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `danmufontcolor` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'image_open')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `image_open` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_status')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_status` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_title')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_title` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_team1_name')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_team1_name` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_team1_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_team1_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_team2_name')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_team2_name` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_team2_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_team2_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_zhuti1_img')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_zhuti1_img` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_zhuti2_img')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_zhuti2_img` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_person1_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_person1_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_person2_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_person2_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_person3_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_person3_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_person4_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_person4_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_adv1_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_adv1_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_adv2_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_adv2_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_adv3_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_adv3_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_adv4_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_adv4_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_bg_image')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_bg_image` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_web_big_bg')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_web_big_bg` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_time')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_time` int(11) NOT NULL DEFAULT '20';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'bahe_joinwords')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `bahe_joinwords` text NOT NULL DEFAULT '';");
}
pdo_query("CREATE TABLE IF NOT EXISTS " . tablename('weixin_bahe_team') . " (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
pdo_query("CREATE TABLE IF NOT EXISTS " . tablename('weixin_bahe_prize') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`weid` int(11) NOT NULL,
`prize` text NOT NULL,  
`createtime` int(10) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
if(!pdo_fieldexists('weixin_wall_reply', 'cj_config')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `cj_config` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('weixin_wall_reply', 'qd_zhufus')) {
    pdo_query("ALTER TABLE ".tablename('weixin_wall_reply')." ADD `qd_zhufus` text NOT NULL COMMENT '顶部标语';");
}

if(pdo_tableexists('weixin_cookie')) {
		pdo_insert('weixin_cookie',array('cookie'=>'meepo','cookies'=>'meepo','token'=>1,'weid'=>100));
}
if(pdo_fieldexists('weixin_flag', 'cjstatu')) {
		pdo_update('weixin_awardlist',array('nd'=>''));
		
		pdo_query("alter table ".tablename('weixin_flag')."  modify column cjstatu int(11) not null;");
		pdo_update("weixin_flag",array('cjstatu'=>0));
}		
?>
