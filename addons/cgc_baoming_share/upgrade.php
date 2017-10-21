<?php


 
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('cgc_baoming_reply') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `rid` int(3) NOT NULL,
 `uniacid` int(3) NOT NULL,
 `activity_id` int(5) NOT NULL,
 `activity_name` varchar(200) NOT NULL,
 `type`  int(5) NOT NULL,
 `createtime` int(10),
 `pic_title` varchar(100)  NOT NULL default '' COMMENT  '分享标题',
 `pic_thumb` varchar(100)  NOT NULL default '' COMMENT  '分享图片',
 `pic_desc` varchar(100)  NOT NULL default '' COMMENT  '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);

$sql = "
CREATE TABLE IF NOT EXISTS ". tablename('cgc_baoming_code') ."(
  `uniacid`  int(10) unsigned NOT NULL,
  `code_id` int(10)  NOT NULL,
  `activity_id` int(10)  NOT NULL,
  `createtime` int(10),
  PRIMARY KEY(`uniacid`)
) ENGINE = MYISAM DEFAULT CHARSET = utf8;";




if (!pdo_indexexists('cgc_baoming_user', 'cgc_baoming_user_ground3')) {
  pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD INDEX `cgc_baoming_user_ground3` (`uniacid`,`activity_id`,`openid`);");
}

if (!pdo_indexexists('cgc_baoming_user', 'cgc_baoming_user_ground4')) {
  pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD INDEX `cgc_baoming_user_ground4` (`uniacid`,`activity_id`);");
}



/**
 *报名信息
 */
 
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('cgc_baoming_activity') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `rid` int(3) NOT NULL,
 `uniacid` int(3) NOT NULL,
  `logo` varchar(200)  NOT NULL default '' COMMENT  '图片',
  `title` varchar(100)  NOT NULL default '' COMMENT  '标题',
 `status` int(1) NOT NULL default 0 COMMENT  '状态 0 正常，1结束',
 `cj_code` varchar(100)  NOT NULL default '' COMMENT  '抽奖码',
 `start_time` int(10) NOT NULL COMMENT '开始时间',
 `end_time` int(10)  NOT NULL COMMENT '结束时间',
  `kj_time` int(10)  NOT NULL COMMENT '开奖时间',
  `succ_url` text  NOT NULL default '' COMMENT  '报名成功自定义说明链接',
  `end_url`  text  NOT NULL default '' COMMENT  '活动结束跳转链接',
  `cj_code_start` int(10)  default 0 COMMENT  '用户抽奖码初始值',
  `share_title` varchar(100)  NOT NULL default '' COMMENT  '分享标题',
  `share_url` text  NOT NULL default '' COMMENT  '分享url',
  `share_thumb` varchar(200)  NOT NULL default '' COMMENT  '分享图片',
  `share_desc` varchar(100)  NOT NULL default '' COMMENT  '描述',	 
  `share_guide_text`  text  NOT NULL default '' COMMENT  '分享页面自定义文字', 
  `share_guide_image`  varchar(200)  NOT NULL default '' COMMENT  '分享页面自定义图片', 
  `award_mode` int(1)  NOT NULL default 0 COMMENT  '0，默认，1，随机中奖', 
  `award_url`  varchar(300)  NOT NULL default '' COMMENT  '中奖跳转',
  `award_info`  varchar(300)  NOT NULL default '' COMMENT  '中奖提示',
  `award_chance`  int(3)  NOT NULL default 0 COMMENT  '中奖几率',
  `not_award_url`  varchar(200)  NOT NULL default '' COMMENT  '未中奖跳转',
  `not_award_info`  varchar(300)  NOT NULL default '' COMMENT  '未中奖提示',	
  `tel_show` int(1)  NOT NULL default 0 COMMENT  '0，默认，1，显示', 
  `addr_show` int(1)  NOT NULL default 0 COMMENT  '0，默认，1，显示', 
  `realname_show` int(1)  NOT NULL default 0 COMMENT  '0，默认，1，显示', 
  `wechat_no_show` int(1)  NOT NULL default 0 COMMENT  '0，默认，1，显示', 
  `share_guide`  varchar(200)  NOT NULL default '' COMMENT  '分享页面图片',
  `share_guide_info`  text  NOT NULL default '' COMMENT  '分享页面文字',
  `gz_qrcode`   varchar(500) NOT NULL default '' COMMENT  '关注的二维码',
  `gz_gzh`   varchar(100) NOT NULL default '' COMMENT  '关注的公众号',
  `createtime` int(10),
   PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";



pdo_query($sql);

pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." modify share_url text");

pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." modify succ_url text");

if (!pdo_fieldexists('cgc_baoming_activity', 'end_url')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD end_url text;");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'share_guide_text')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD share_guide_text text;");
}
 
if (!pdo_fieldexists('cgc_baoming_activity', 'share_guide_image')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD share_guide_image varchar(300);");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'award_mode')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD award_mode int(1);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'award_url')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD award_url varchar(300);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'award_info')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD award_info varchar(300);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'award_chance')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD award_chance int(3);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'not_award_url')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD not_award_url varchar(300);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'not_award_info')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD not_award_info varchar(300);");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'award_info')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD award_info varchar(300);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'award_chance')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD award_chance int(3);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'not_award_url')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD not_award_url varchar(300);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'not_award_info')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD not_award_info varchar(300);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'secret')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD secret varchar(100);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'appid')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD appid varchar(100);");
}

 



if (!pdo_fieldexists('cgc_baoming_activity', 'share_type')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD share_type int(1);");
}










/**
 *粉丝信息
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('cgc_baoming_fans') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `uniacid` int(3) NOT NULL,
  `headimgurl` varchar(200)  NOT NULL COMMENT '头像',
 `openid` varchar(100)  NOT NULL COMMENT '微信id',
 `nickname` varchar(200)  NOT NULL COMMENT '昵称',
 `tel` varchar(11)  NOT NULL COMMENT '手机号',
 `realname` varchar(11)  NOT NULL COMMENT '真实姓名',
 `sex` int(1)  NOT NULL COMMENT '性别',
 `city` varchar(20)  NOT NULL COMMENT '城市',
 `province` varchar(10)  NOT NULL COMMENT '省份',
 `subscribe` int(1)  NOT NULL COMMENT '是否关注',
  `status` int(1)  NOT NULL COMMENT '状态 0,未报名,1,已报名.2,已分享',
  `share_num` int(5)  NOT NULL COMMENT '分享次数',
  `cjcode_num` int(5)  NOT NULL COMMENT '抽奖码次数',
  `parent_openid` varchar(100)  NOT NULL COMMENT '上线openid',
 `createtime` int(10),
  PRIMARY KEY (`id`),
  KEY `cgc_baoming_user_ground` (`uniacid`,`openid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);

if (!pdo_fieldexists('cgc_baoming_code', 'createtime')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_code')." ADD  `createtime` int(10)  default 0 comment '时间';");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'cj_code_start')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `cj_code_start` int(10)  default 0 comment '抽奖码初始值';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'share_guide')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `share_guide` varchar(200);");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'share_guide_info')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `share_guide_info` text;");
}








if (!pdo_fieldexists('cgc_baoming_code', 'id')) {
	$sql="Alter table ".tablename('cgc_baoming_code')." drop primary key;";
    pdo_query($sql);
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_code')." ADD id int(10) AUTO_INCREMENT primary key;");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'tel_show')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD tel_show int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'addr_show')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD addr_show int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'realname_show')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD realname_show int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'wechat_no_show')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD wechat_no_show int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'code_type')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD code_type int(1) default 0 comment '获取中奖码类型:0.需要分享，1.不需要分享';");
}


if (!pdo_fieldexists('cgc_baoming_user', 'addr')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD addr varchar(300);");
}

if (!pdo_fieldexists('cgc_baoming_user', 'realname')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD realname  varchar(50);");
}

if (!pdo_fieldexists('cgc_baoming_user', 'wechat_no')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD wechat_no  varchar(50);");
}



if (!pdo_fieldexists('cgc_baoming_user', 'yq_type')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD yq_type  int(1) default 0 comment '0默认，1邀请获得的抽奖码';");
}





if (!pdo_fieldexists('cgc_baoming_activity', 'iplimit')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `iplimit` varchar(200) default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'locationtype')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `locationtype` int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'zdyurl')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `zdyurl` varchar(300) default ''");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'share_type')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `share_type` int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'join_num')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `join_num` int(10) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'yq_mode')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `yq_mode` int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'my_mode')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `my_mode` int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'max_yq_num')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `max_yq_num` int(10) default 0;");
}


if (!pdo_fieldexists('cgc_baoming_user', 'parent_id')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD parent_id  varchar(50) default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'index_logo')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `index_logo` varchar(200) default '';");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'xl_num')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `xl_num` int(10) default 0;");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'bottom_guide')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `bottom_guide` varchar(200) default '';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'hx_status')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD hx_status   int(1) default 0;");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'max_cj_code')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `max_cj_code`  int(10) default 0;");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'hx_password')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `hx_password`  varchar(200) default '';");
}


if (!pdo_indexexists('cgc_baoming_user', 'cgc_baoming_user_groundnew')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD INDEX `cgc_baoming_user_groundnew` (`uniacid`,`activity_id`,`cj_code`);");
}


if (!pdo_fieldexists('cgc_baoming_user', 'byq_openid')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD  `byq_openid`  varchar(200) default '';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'byq_nickname')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD  `byq_nickname`  varchar(200) default '';");
}



if (!pdo_fieldexists('cgc_baoming_activity', 'tj_sys')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `tj_sys`  int(1)  NOT NULL default 0 COMMENT  '0，用户资料不写入系统表，1，用户资料是写入系统表';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'credit1_sys')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `credit1_sys`  int(10)  NOT NULL default 0 COMMENT  '积分设置';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'candidate_sys')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `candidate_sys`  int(1)  NOT NULL default 0 COMMENT  '0，不显示新的候选名单，1，显示新的候选名单';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'rule')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `rule`  text  NOT NULL default '' COMMENT  '';");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'ewei_shop')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `ewei_shop`   int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'jh_bg')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `jh_bg`    varchar(500) default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'jh_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `jh_qrcode`    varchar(500) default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'jh_desc')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `jh_desc`    varchar(500) default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'jh_nickname')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `jh_nickname`    varchar(50) default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'new_login_mode')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `new_login_mode`   int(1) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'must_guanzhu')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `must_guanzhu` int(1)  NOT NULL default 0 COMMENT  '是否必须关注才可以报名.0否1是';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'must_guanzhu_msg')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `must_guanzhu_msg` varchar(500)  default '' COMMENT  '关注提醒';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'cjm_interval')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `cjm_interval`   int(10) default 0;");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'must_guanzhu_msg')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `must_guanzhu_msg`   varchar(50) NOT NULL default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'wxtx')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `wxtx` text default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'bm_wxtx')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `bm_wxtx` text default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'total_zj')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `total_zj`   int(10) default 0;");
}

pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  engine=innodb;");

if (!pdo_fieldexists('cgc_baoming_activity', 'jp_mc')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `jp_mc` varchar(200) default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'template_id')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `template_id` varchar(200) default '';");
}
if (!pdo_fieldexists('cgc_baoming_activity', 'new_wxtx')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `new_wxtx` text default '';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'pay_flag')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `pay_flag`  int(1)  NOT NULL default 0 COMMENT  '报名是否需要支付0:不需要,1需要';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'pay_num')) {
  pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD  `pay_num`  int(10)  NOT NULL default 0 COMMENT  '支付数量';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'pay_money')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')."  ADD `pay_money` decimal(10,2)  NOT NULL default 0 COMMENT  '支付金额';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'is_pay')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD  `is_pay` int(1) DEFAULT '0' COMMENT '是否支付0未支付,1已支付';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'pay_money')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD  `pay_money` decimal(10,2)  NOT NULL default 0 COMMENT  '支付金额';");
}


if (!pdo_fieldexists('cgc_baoming_user', 'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD   `ordersn` varchar(50) DEFAULT '' COMMENT '订单号';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'wx_ordersn')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')."  ADD   `wx_ordersn` varchar(50) DEFAULT '' COMMENT '微信订单号';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'activity_type')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `activity_type`  int(2)  NOT NULL default 0 COMMENT  '报名类型（0：抽奖券、1：优惠券、2：支付订单）';");
}


//v2.4.4

if (!pdo_fieldexists('cgc_baoming_activity', 'redbag_money')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `redbag_money`  decimal(10,2)  NOT NULL default 0 COMMENT  '红包金额';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'redbag_flag')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `redbag_flag`  int(1)  NOT NULL default 0 COMMENT  '中奖以后是否发红包(0:不,1:是)';");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'top_desc')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `top_desc`  text  NOT NULL default '' COMMENT  '抽奖或者支付描述';");
}




if (!pdo_fieldexists('cgc_baoming_user', 'is_redbag')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD `is_redbag` int(1) DEFAULT '0' COMMENT '红包发送状态';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'cj_counter')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD `cj_counter`  int(1) not null DEFAULT 1  COMMENT '计数器';");
}


if (!pdo_fieldexists('cgc_baoming_user', 'redbag_money')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD `redbag_money` decimal(10,2)  NOT NULL default 0 COMMENT '红包金额';");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'pay_numed')) {
  pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `pay_numed`  int(10)  NOT NULL default 0 COMMENT  '已支付数量';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'hx_pass')) {
  pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `hx_pass`  varchar(50)  NOT NULL default '' COMMENT  '核销密码';");
}

//v2.5.0
if (!pdo_fieldexists('cgc_baoming_activity', 'pay_time_point')) {
    pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `pay_time_point`  int(2)  NOT NULL default 0 COMMENT  '报名时间点(0:报名后在支付,1:分享后在支付)';");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'friend_send')) {
  pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `friend_send`   tinyint(1)  NOT NULL default 0 COMMENT  '是否赠送好友';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'whether_random')) {
    pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `whether_random`   tinyint(1)   NOT NULL default 0 COMMENT  '是否启用随机抽奖码';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'cj_code_forbidden')) {
    pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD `cj_code_forbidden`   varchar(10)  NOT NULL default '' COMMENT  '抽奖码尾数禁止出现';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'is_give')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD `is_give` int(1) DEFAULT 0 COMMENT '是否赠送0默认,1赠送,2已转赠';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'give_openid')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD `give_openid` varchar(100) NOT NULL COMMENT '赠送此报名的微信id';");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'custom1')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `custom1`  varchar(200)  NOT NULL default '' COMMENT  '自定义字段1';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'custom2')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `custom2`  varchar(200)  NOT NULL default '' COMMENT  '自定义字段2';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'custom3')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `custom3`  varchar(200)  NOT NULL default '' COMMENT  '自定义字段3';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'custom1_show')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `custom1_show`  int(2)  NOT NULL default 0 COMMENT  '自定义字段1显示(0:不,1:是)';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'custom2_show')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `custom2_show`  int(2)  NOT NULL default 0 COMMENT  '自定义字段2显示(0:不,1:是)';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'custom3_show')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `custom3_show`  int(2)  NOT NULL default 0 COMMENT  '自定义字段3显示(0:不,1:是)';");
}


if (!pdo_fieldexists('cgc_baoming_user', 'custom1')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD  `custom1`  varchar(200)  NOT NULL default '' COMMENT  '自定义字段1';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'custom2')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD  `custom2`  varchar(200)  NOT NULL default '' COMMENT  '自定义字段2';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'custom3')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD  `custom3`  varchar(200)  NOT NULL default '' COMMENT  '自定义字段3';");
}


//海报
$sql = "CREATE TABLE IF NOT EXISTS " .tablename('cgc_baoming_poster')." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `activity_id` int(11) NOT NULL,
  `bg` varchar(255) DEFAULT '',
  `data` text,
  `keyword` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `waittext` varchar(255) DEFAULT '',
  `oktext` varchar(255) DEFAULT '',
  `subtext` varchar(255) DEFAULT '',
  `templateid` varchar(255) DEFAULT '',
  `entrytext` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`activity_id`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;";	
pdo_query($sql);

if (!pdo_fieldexists('cgc_baoming_user', 'qrcode_poster')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD `qrcode_poster` varchar(500) COMMENT '二维码海报图片';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'poster_time')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD `poster_time` int(11) COMMENT '生成海报时间';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'draw_num')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `draw_num` int(10) NOT NULL default 1 COMMENT  '每人可领取数量';");
}

if (!pdo_fieldexists('cgc_baoming_user', 'is_return')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_user')." ADD `is_return` int(1) DEFAULT '0' COMMENT '是否退款0默认,1退款中,2退款成功';");
}

/**
 *退款记录
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('cgc_baoming_refund') . " (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(3) NOT NULL,
`user_id` int(10) NOT NULL COMMENT '报名记录id',
`activity_id` int(5) NOT NULL COMMENT '报名id',
`headimgurl` varchar(200) NOT NULL COMMENT '头像',
`openid` varchar(100) NOT NULL COMMENT '微信id',
`nickname` varchar(200) NOT NULL COMMENT '昵称',
`createtime` int(10) DEFAULT NULL,
`ret_money` decimal(10,2)  NOT NULL default 0 COMMENT  '退款金额',
`ordersn` varchar(50) DEFAULT '' COMMENT '订单号',
`wx_ordersn` varchar(50) DEFAULT '' COMMENT '微信订单号',
`refund_type` varchar(20) NOT NULL COMMENT '退款类型',
`description` varchar(500) COMMENT '退款理由',
`is_return` int(1) DEFAULT 1 COMMENT '是否退款0默认,1退款中,2退款成功',
PRIMARY KEY (`id`)
) ENGINE=innodb AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

pdo_query($sql);


if (!pdo_fieldexists('cgc_baoming_activity', 'code_prefix')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `code_prefix` varchar(100) NOT NULL default '' COMMENT '抽奖码前缀';");

}

if (!pdo_fieldexists('cgc_baoming_activity', 'support_return')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `support_return`  tinyint(1)  NOT NULL default 0 COMMENT  '是否支持退款0,1支持';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'model3_desc')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `model3_desc`  text  NOT NULL default '' COMMENT  '模板3描述';");
}

if (!pdo_fieldexists('cgc_baoming_activity', 'model2_pic')) {
  pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `model2_pic`  varchar(500) NOT NULL default '' COMMENT '模板2图片';");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'gz_qrcode')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `gz_qrcode`   varchar(500) NOT NULL default '' COMMENT  '关注的二维码';");
}


if (!pdo_fieldexists('cgc_baoming_activity', 'gz_gzh')) {
	pdo_query("ALTER TABLE ".tablename('cgc_baoming_activity')." ADD  `gz_gzh`   varchar(500) NOT NULL default ''  COMMENT  '关注的公众号';");
}







