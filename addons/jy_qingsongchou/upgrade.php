<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_jy_qsc_address` (
`id` int(111) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`mid` int(11),
`province` varchar(255),
`city` varchar(255),
`area` varchar(255),
`address` varchar(255),
`name` varchar(32),
`tel` varchar(32),
`youbian` varchar(32),
`is_def` int(1) NOT NULL DEFAULT '0',
`upbdate` varchar(32),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_bang` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`mid` int(11),
`fid` int(11),
`prove_relation` varchar(32),
`prove_name` varchar(32),
`prove_desc` varchar(255),
`upbdate` varchar(32),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_bank` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`mid` int(11),
`bank` varchar(255),
`holder` varchar(255),
`cardNo` varchar(255),
`type` int(1),
`upbdate` varchar(32),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_banner` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`thumb` varchar(255),
`thumb_url` varchar(255),
`upbdate` varchar(32),
`thumb_name` varchar(255),
`thumb_rank` int(1) DEFAULT '50',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_fabu` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`pid` int(11),
`openid` varchar(233),
`tar_monet` int(11),
`use` varchar(233),
`name` varchar(255),
`detail` text,
`thumb` text,
`upbdate` varchar(32),
`status` int(1) NOT NULL DEFAULT '0',
`rand_time` varchar(32),
`rand_day` varchar(32),
`project_texdesc` text,
`cur_day` varchar(32),
`cover_thumb` text,
`has_monet` float NOT NULL DEFAULT '0',
`is_sup` int(11) NOT NULL DEFAULT '0',
`is_admin` int(1) NOT NULL DEFAULT '0',
`mid` int(11),
`uid` int(11),
`shouc` int(11) DEFAULT '0',
`reward` text,
`is_secret` int(1) DEFAULT '0',
`has_sh` int(1) DEFAULT '0',
`yunfei` varchar(221),
`deliveryTime` varchar(221),
`has_money` float(255,2) DEFAULT '0.00',
`is_share` int(11) DEFAULT '0',
`fhsj` varchar(221),
`views` int(11),
`is_p` int(1) DEFAULT '0',
`dream` text,
`rank` int(1) DEFAULT '50',
`youxiaoqi` varchar(255) NOT NULL DEFAULT '0',
`tuikuan` int(1) NOT NULL DEFAULT '0',
`token` varchar(255) NOT NULL DEFAULT '0',
`early` int(255) DEFAULT '0',
`is_live` int(1) DEFAULT '0',
`is_get` int(1) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_fahuo` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`fid` int(11),
`reid` int(11),
`pid` int(11),
`kuaidi` varchar(32),
`kuai_order` varchar(32),
`fahuo_time` varchar(32),
`address_id` int(11),
`status` int(11) NOT NULL DEFAULT '0',
`upbdate` varchar(32),
`mid` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_hospital` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`mid` int(11),
`uid` int(11),
`fid` int(11),
`upbdate` varchar(32),
`dis_name` varchar(233),
`dis_idcar` varchar(233),
`hospital` varchar(233),
`disease` varchar(233),
`creator_name` varchar(233),
`creator_id` varchar(233),
`creator_phone` varchar(233),
`desction` varchar(233),
`idcar` varchar(233),
`status` int(1) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_huzhu` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`uid` int(11),
`mid` int(11),
`openid` varchar(255),
`upbdate` varchar(32),
`name` varchar(32),
`idcar` varchar(32),
`age` int(11),
`number` varchar(32),
`status` int(11),
`moneys` float,
`maxday` varchar(255) NOT NULL DEFAULT '0',
`transaction_id` varchar(255) NOT NULL DEFAULT '0',
`order_id` varchar(255) NOT NULL DEFAULT '0',
`hid` varchar(255) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_huzhu_pay` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`upbdate` varchar(32),
`tid` varchar(32),
`transaction_id` varchar(32),
`status` int(11),
`ids` varchar(255) NOT NULL DEFAULT '0',
`mmm` varchar(255) NOT NULL DEFAULT '0',
`hid` int(255) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_huzhu_set` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`vir_peo` varchar(255),
`vir_mon` varchar(32),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_jubao` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`fid` int(11),
`mid` int(111),
`report_reason` text,
`thumb` text,
`upbdate` varchar(32),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_liuyan` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`tel` varchar(255),
`email` varchar(255),
`content` varchar(255),
`upbdate` varchar(32),
`mid` int(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_member` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`openid` varchar(233),
`nickname` varchar(233),
`headimgurl` varchar(233),
`is_roob` int(1) DEFAULT '0',
`is_shouc` text,
`tel` varchar(32),
`status` int(1) NOT NULL DEFAULT '0',
`allows` int(11) DEFAULT '0',
`allow` varchar(32),
`is_manger` int(1) DEFAULT '0',
`subscribe` int(1) NOT NULL DEFAULT '0',
`type` int(255) DEFAULT '0',
`mobile` varchar(255),
`mobile_status` int(1) DEFAULT '0',
`mobile_code` int(255) DEFAULT '0',
`wallet` decimal(65,2) DEFAULT '0.00',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_message` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`payid` int(11),
`mid` int(11),
`fid` int(11),
`content` varchar(255),
`upbdate` varchar(32),
`mesid` varchar(255) NOT NULL DEFAULT '',
`tmid` varchar(255) NOT NULL DEFAULT '',
`token` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_msgcode` (
`id` int(111) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`mid` int(11),
`token` varchar(233),
`upbdate` varchar(32),
`code` varchar(32),
`status` int(1) DEFAULT '0',
`tel` varchar(32),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_oques` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`level` int(1) NOT NULL DEFAULT '1',
`title` varchar(32),
`rank` int(11) NOT NULL DEFAULT '50',
`type` varchar(32),
`pre_id` int(11),
`content` text,
`upbdate` varchar(32),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_paylog` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`uid` int(11),
`fid` int(11),
`avatar` varchar(255),
`upbdate` varchar(32),
`status` int(1) NOT NULL DEFAULT '0',
`tid` varchar(255),
`fee` float(255,2),
`msg` varchar(255) NOT NULL DEFAULT '支持',
`mid` int(11),
`address_id` int(11),
`reid` varchar(255),
`type` int(1) NOT NULL DEFAULT '0',
`dream_id` varchar(255),
`shouxufei` float(11,2) NOT NULL DEFAULT '0.00',
`count` int(11) NOT NULL DEFAULT '0',
`fahuo` int(1) NOT NULL DEFAULT '0',
`bank_id` int(11) NOT NULL DEFAULT '0',
`transaction_id` varchar(255) NOT NULL DEFAULT '0',
`wantSupportTel` varchar(11) NOT NULL DEFAULT '0',
`is_roob` int(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_pc_login` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`upbdate` varchar(255),
`token` varchar(255),
`status` int(1) NOT NULL DEFAULT '0',
`openid` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_photo` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`pic` varchar(255),
`thumb` varchar(255),
`dir` varchar(255),
`upbdate` varchar(32),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_project` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`project_name` varchar(32),
`project_logo` varchar(233),
`project_plus1` int(1) NOT NULL DEFAULT '0',
`project_plus2` int(1) NOT NULL DEFAULT '0',
`project_plus3` int(1) NOT NULL DEFAULT '0',
`project_plus4` int(1) NOT NULL DEFAULT '0',
`project_desc` text,
`project_shuoming` text,
`upbdate` varchar(32),
`project_min` int(11),
`project_max` int(11),
`project_moren` int(11),
`project_texdesc` varchar(255),
`project_mstips` varchar(255),
`title_placeholder` varchar(255),
`is_p` int(1) DEFAULT '0',
`desc_placeholder` varchar(255),
`project_gg` varchar(255),
`is_shenhe` int(1) NOT NULL DEFAULT '0',
`pre_id` int(11) NOT NULL DEFAULT '0',
`rank` int(11) NOT NULL DEFAULT '50',
`yongjin` float(11,2),
`shouchishenfenz` text,
`end_type` int(11) NOT NULL DEFAULT '0',
`is_goods` int(1) NOT NULL DEFAULT '0',
`is_suptel` int(1) NOT NULL DEFAULT '0',
`is_hospital` int(1) DEFAULT '0',
`project_plus5` int(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_ques` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`pid` int(11),
`title` varchar(233),
`content` text,
`upbdate` varchar(32),
`rank` int(11) NOT NULL DEFAULT '50',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_setting` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`sitename` varchar(255),
`sitebanner` varchar(255),
`share_title` varchar(255),
`share_desc` varchar(255),
`share_img` varchar(255),
`upbdate` varchar(255),
`head_title1` varchar(32),
`project_fqtk` text,
`file_type` int(1) DEFAULT '0',
`pay_type` int(1) DEFAULT '0',
`qniu_access` varchar(255) DEFAULT '0',
`qniu_secret` varchar(255) DEFAULT '0',
`qniu_bucket` varchar(255) DEFAULT '0',
`qniu_url` varchar(255) DEFAULT '0',
`oss_access` varchar(255) DEFAULT '0',
`oss_secret` varchar(255) DEFAULT '0',
`oss_bucket` varchar(255) DEFAULT '0',
`pay_appid` varchar(255) DEFAULT '0',
`pay_appsecret` varchar(255) DEFAULT '0',
`pay_miyao` varchar(255) DEFAULT '0',
`oss_url` varchar(255) DEFAULT '0',
`oss_endpoint` varchar(255) DEFAULT '0',
`weibo_content` varchar(255) DEFAULT '0',
`weibo_pic` varchar(255) DEFAULT '0',
`weibo_url` varchar(255) DEFAULT '0',
`qqzon_content` varchar(255) DEFAULT '0',
`qqzon_pic` varchar(255) DEFAULT '0',
`qqzon_url` varchar(255) DEFAULT '0',
`qqweibo_content` varchar(255) DEFAULT '0',
`qqweibo_pic` varchar(255) DEFAULT '0',
`qqweibo_url` varchar(255) DEFAULT '0',
`qq_content` varchar(255) DEFAULT '0',
`qq_pic` varchar(255) DEFAULT '0',
`qq_url` varchar(255) DEFAULT '0',
`pay_number` varchar(255) DEFAULT '0',
`about` text,
`tel` varchar(32),
`dayu_appkey` varchar(32),
`dayu_secretkey` varchar(233),
`dayu_sign` varchar(233),
`dayu_temp` varchar(233),
`logo` varchar(255),
`project_header` varchar(255),
`kf_link` varchar(255),
`kf_sup_success` text,
`kf_pl_success` text,
`kf_news` text,
`kf_sup_type` int(1) NOT NULL DEFAULT '0',
`kf_sup_temp` int(255),
`kf_pl_type` int(1) NOT NULL DEFAULT '0',
`kf_pl_tmp` int(255),
`liuyan` varchar(255),
`kf_news_type` int(1) NOT NULL DEFAULT '0',
`kf_news_tmp` int(255),
`share_url` varchar(255),
`help_back` varchar(255),
`detaillogo` varchar(255),
`pay_com_title` varchar(255),
`pay_hb_wishing` varchar(255),
`pay_hb_send_name` varchar(255),
`pay_hb_act_name` varchar(255),
`pay_hb_remark` varchar(255),
`faqishuomingwenzi` varchar(255) NOT NULL DEFAULT '0',
`ip_address` varchar(255),
`kf_cksuccess_type` int(1) NOT NULL DEFAULT '0',
`kf_cksuccess_content` varchar(255),
`kf_cksuccess_tmp` int(11) NOT NULL DEFAULT '0',
`kf_ckfail_type` int(1) NOT NULL DEFAULT '0',
`kf_ckfail_content` varchar(255),
`kf_ckfail_tmp` int(11) NOT NULL DEFAULT '0',
`kf_tuikuan_type` int(1) NOT NULL DEFAULT '0',
`kf_tuikuan_content` varchar(255),
`kf_tuikuan_tmp` int(11) NOT NULL DEFAULT '0',
`yanzheng` text,
`verifypeer` int(1) NOT NULL DEFAULT '0',
`mobileshuom` varchar(255) NOT NULL DEFAULT '',
`mobileshuom_desc` text NOT NULL,
`ziliao_tips` text NOT NULL,
`weixn_width` int(1) NOT NULL DEFAULT '0',
`tixian_suilv` int(1) NOT NULL DEFAULT '0',
`web_logo` varchar(255) NOT NULL DEFAULT '',
`zhengshishuoming` text NOT NULL,
`supr_rank` int(1) NOT NULL DEFAULT '0',
`pc_1_pic` varchar(255) NOT NULL DEFAULT '',
`pc_2_pic` varchar(255) NOT NULL DEFAULT '',
`pc_3_pic` varchar(255) NOT NULL DEFAULT '',
`follow_display` int(1) NOT NULL DEFAULT '0',
`follow_btn_envt` int(1) NOT NULL DEFAULT '0',
`follow_logo` varchar(255) NOT NULL DEFAULT '',
`follow_qrcode` varchar(255) NOT NULL DEFAULT '',
`follow_btn` varchar(255) NOT NULL DEFAULT '',
`follow_txt` varchar(255) NOT NULL DEFAULT '',
`follow_url` varchar(255) NOT NULL DEFAULT '',
`follow_qrcode_txt` varchar(255) NOT NULL DEFAULT '',
`huzhu_desc` text NOT NULL,
`thirth_desc` text NOT NULL,
`hz_share_desc` text NOT NULL,
`vip_desc` text NOT NULL,
`pan_desc` text NOT NULL,
`hz_question` text NOT NULL,
`hz_img` text NOT NULL,
`hz_img_index` text NOT NULL,
`hz_switch` int(1) NOT NULL DEFAULT '0',
`hz_money` varchar(255) NOT NULL DEFAULT '0',
`hz_gongyue` text NOT NULL,
`hz_jiangkang` text NOT NULL,
`bro_wx` varchar(1) NOT NULL DEFAULT '0',
`alipay_type` varchar(1) NOT NULL DEFAULT '0',
`user_img` varchar(255) NOT NULL DEFAULT '0',
`is_h5` int(1) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_shiming` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`mid` int(11),
`real_name` varchar(32),
`cert_no` varchar(32),
`upbdate` varchar(32),
`status` int(1) NOT NULL,
`thumb` varchar(255),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_shouchishenfenz` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`fid` int(111),
`idcar` varchar(255),
`zuzhi` varchar(255),
`zhengm` varchar(255),
`creator_name` varchar(255),
`creator_id` varchar(255),
`creator_phone` varchar(255),
`zuzhir_name` varchar(255),
`zuzhi_info` varchar(255),
`upbdate` varchar(32),
`type` int(1) NOT NULL DEFAULT '0',
`status` int(1) NOT NULL DEFAULT '0',
`sz_creator_id` varchar(255) NOT NULL DEFAULT '0',
`hospital` varchar(255) NOT NULL DEFAULT '0',
`sz_name` varchar(255) NOT NULL DEFAULT '0',
`creator_relation` varchar(255) NOT NULL DEFAULT '0',
`disease` varchar(255) NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_sysmsg` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`ms_title` varchar(255),
`ms_content` text,
`upbdate` varchar(32),
`status` int(1) NOT NULL DEFAULT '0',
`thumb` varchar(233),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_temp` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`parama` text,
`upbdate` varchar(32),
`catename` varchar(255),
`tempid` varchar(255),
`url` varchar(255),
`content` text,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_update` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`mid` int(11),
`fid` int(11),
`content` varchar(32),
`thumb` text,
`upbdate` varchar(32),
`type` int(255) DEFAULT '0',
`status` int(255) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_withdraw` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`mid` int(11),
`uid` int(11),
`money` float(11,2) NOT NULL DEFAULT '0.00',
`upbdate` varchar(255),
`status` int(1) NOT NULL,
`type` int(1) DEFAULT '0',
`payid` int(1),
`update` varchar(255),
`token` varchar(255) DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_jy_qsc_yongjin` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11),
`mid` int(11),
`uid` int(11),
`fid` int(11),
`pid` int(11),
`fee` float(11,2),
`user_fee` float(11,2),
`upbdate` varchar(32),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `id` int(111) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'province')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `province` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'city')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `city` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'area')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `area` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'address')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `address` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `name` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `tel` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'youbian')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `youbian` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'is_def')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `is_def` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_address')) {
	if(!pdo_fieldexists('ims_jy_qsc_address',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_address')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bang')) {
	if(!pdo_fieldexists('ims_jy_qsc_bang',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bang')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bang')) {
	if(!pdo_fieldexists('ims_jy_qsc_bang',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bang')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bang')) {
	if(!pdo_fieldexists('ims_jy_qsc_bang',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bang')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bang')) {
	if(!pdo_fieldexists('ims_jy_qsc_bang',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bang')." ADD `fid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bang')) {
	if(!pdo_fieldexists('ims_jy_qsc_bang',  'prove_relation')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bang')." ADD `prove_relation` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bang')) {
	if(!pdo_fieldexists('ims_jy_qsc_bang',  'prove_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bang')." ADD `prove_name` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bang')) {
	if(!pdo_fieldexists('ims_jy_qsc_bang',  'prove_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bang')." ADD `prove_desc` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bang')) {
	if(!pdo_fieldexists('ims_jy_qsc_bang',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bang')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bank')) {
	if(!pdo_fieldexists('ims_jy_qsc_bank',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bank')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bank')) {
	if(!pdo_fieldexists('ims_jy_qsc_bank',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bank')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bank')) {
	if(!pdo_fieldexists('ims_jy_qsc_bank',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bank')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bank')) {
	if(!pdo_fieldexists('ims_jy_qsc_bank',  'bank')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bank')." ADD `bank` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bank')) {
	if(!pdo_fieldexists('ims_jy_qsc_bank',  'holder')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bank')." ADD `holder` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bank')) {
	if(!pdo_fieldexists('ims_jy_qsc_bank',  'cardNo')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bank')." ADD `cardNo` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bank')) {
	if(!pdo_fieldexists('ims_jy_qsc_bank',  'type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bank')." ADD `type` int(1);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_bank')) {
	if(!pdo_fieldexists('ims_jy_qsc_bank',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_bank')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_banner')) {
	if(!pdo_fieldexists('ims_jy_qsc_banner',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_banner')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_banner')) {
	if(!pdo_fieldexists('ims_jy_qsc_banner',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_banner')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_banner')) {
	if(!pdo_fieldexists('ims_jy_qsc_banner',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_banner')." ADD `thumb` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_banner')) {
	if(!pdo_fieldexists('ims_jy_qsc_banner',  'thumb_url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_banner')." ADD `thumb_url` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_banner')) {
	if(!pdo_fieldexists('ims_jy_qsc_banner',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_banner')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_banner')) {
	if(!pdo_fieldexists('ims_jy_qsc_banner',  'thumb_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_banner')." ADD `thumb_name` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_banner')) {
	if(!pdo_fieldexists('ims_jy_qsc_banner',  'thumb_rank')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_banner')." ADD `thumb_rank` int(1) DEFAULT '50';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `pid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `openid` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'tar_monet')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `tar_monet` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'use')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `use` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `name` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'detail')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `detail` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `thumb` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `status` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'rand_time')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `rand_time` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'rand_day')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `rand_day` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'project_texdesc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `project_texdesc` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'cur_day')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `cur_day` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'cover_thumb')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `cover_thumb` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'has_monet')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `has_monet` float NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'is_sup')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `is_sup` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'is_admin')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `is_admin` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `uid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'shouc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `shouc` int(11) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'reward')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `reward` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'is_secret')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `is_secret` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'has_sh')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `has_sh` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'yunfei')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `yunfei` varchar(221);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'deliveryTime')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `deliveryTime` varchar(221);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'has_money')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `has_money` float(255,2) DEFAULT '0.00';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'is_share')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `is_share` int(11) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'fhsj')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `fhsj` varchar(221);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'views')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `views` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'is_p')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `is_p` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'dream')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `dream` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'rank')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `rank` int(1) DEFAULT '50';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'youxiaoqi')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `youxiaoqi` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'tuikuan')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `tuikuan` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'token')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `token` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'early')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `early` int(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'is_live')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `is_live` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fabu')) {
	if(!pdo_fieldexists('ims_jy_qsc_fabu',  'is_get')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fabu')." ADD `is_get` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `fid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `reid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `pid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'kuaidi')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `kuaidi` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'kuai_order')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `kuai_order` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'fahuo_time')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `fahuo_time` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'address_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `address_id` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `status` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_fahuo')) {
	if(!pdo_fieldexists('ims_jy_qsc_fahuo',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_fahuo')." ADD `mid` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `uid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `fid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'dis_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `dis_name` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'dis_idcar')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `dis_idcar` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'hospital')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `hospital` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'disease')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `disease` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'creator_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `creator_name` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'creator_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `creator_id` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'creator_phone')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `creator_phone` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'desction')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `desction` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'idcar')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `idcar` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_hospital')) {
	if(!pdo_fieldexists('ims_jy_qsc_hospital',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_hospital')." ADD `status` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `uid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `openid` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `name` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'idcar')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `idcar` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'age')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `age` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'number')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `number` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `status` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'moneys')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `moneys` float;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'maxday')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `maxday` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'transaction_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `transaction_id` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'order_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `order_id` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu',  'hid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu')." ADD `hid` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_pay')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_pay',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_pay')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_pay')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_pay',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_pay')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_pay')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_pay',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_pay')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_pay')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_pay',  'tid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_pay')." ADD `tid` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_pay')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_pay',  'transaction_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_pay')." ADD `transaction_id` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_pay')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_pay',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_pay')." ADD `status` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_pay')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_pay',  'ids')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_pay')." ADD `ids` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_pay')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_pay',  'mmm')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_pay')." ADD `mmm` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_pay')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_pay',  'hid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_pay')." ADD `hid` int(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_set')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_set',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_set')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_set')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_set',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_set')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_set')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_set',  'vir_peo')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_set')." ADD `vir_peo` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_huzhu_set')) {
	if(!pdo_fieldexists('ims_jy_qsc_huzhu_set',  'vir_mon')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_huzhu_set')." ADD `vir_mon` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_jubao')) {
	if(!pdo_fieldexists('ims_jy_qsc_jubao',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_jubao')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_jubao')) {
	if(!pdo_fieldexists('ims_jy_qsc_jubao',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_jubao')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_jubao')) {
	if(!pdo_fieldexists('ims_jy_qsc_jubao',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_jubao')." ADD `fid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_jubao')) {
	if(!pdo_fieldexists('ims_jy_qsc_jubao',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_jubao')." ADD `mid` int(111);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_jubao')) {
	if(!pdo_fieldexists('ims_jy_qsc_jubao',  'report_reason')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_jubao')." ADD `report_reason` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_jubao')) {
	if(!pdo_fieldexists('ims_jy_qsc_jubao',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_jubao')." ADD `thumb` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_jubao')) {
	if(!pdo_fieldexists('ims_jy_qsc_jubao',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_jubao')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_liuyan')) {
	if(!pdo_fieldexists('ims_jy_qsc_liuyan',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_liuyan')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_liuyan')) {
	if(!pdo_fieldexists('ims_jy_qsc_liuyan',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_liuyan')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_liuyan')) {
	if(!pdo_fieldexists('ims_jy_qsc_liuyan',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_liuyan')." ADD `tel` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_liuyan')) {
	if(!pdo_fieldexists('ims_jy_qsc_liuyan',  'email')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_liuyan')." ADD `email` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_liuyan')) {
	if(!pdo_fieldexists('ims_jy_qsc_liuyan',  'content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_liuyan')." ADD `content` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_liuyan')) {
	if(!pdo_fieldexists('ims_jy_qsc_liuyan',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_liuyan')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_liuyan')) {
	if(!pdo_fieldexists('ims_jy_qsc_liuyan',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_liuyan')." ADD `mid` int(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `openid` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `nickname` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'headimgurl')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `headimgurl` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'is_roob')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `is_roob` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'is_shouc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `is_shouc` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `tel` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `status` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'allows')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `allows` int(11) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'allow')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `allow` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'is_manger')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `is_manger` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'subscribe')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `subscribe` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `type` int(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `mobile` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'mobile_status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `mobile_status` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'mobile_code')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `mobile_code` int(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_member')) {
	if(!pdo_fieldexists('ims_jy_qsc_member',  'wallet')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_member')." ADD `wallet` decimal(65,2) DEFAULT '0.00';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'payid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `payid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `fid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `content` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'mesid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `mesid` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'tmid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `tmid` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_message')) {
	if(!pdo_fieldexists('ims_jy_qsc_message',  'token')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_message')." ADD `token` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_msgcode')) {
	if(!pdo_fieldexists('ims_jy_qsc_msgcode',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_msgcode')." ADD `id` int(111) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_msgcode')) {
	if(!pdo_fieldexists('ims_jy_qsc_msgcode',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_msgcode')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_msgcode')) {
	if(!pdo_fieldexists('ims_jy_qsc_msgcode',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_msgcode')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_msgcode')) {
	if(!pdo_fieldexists('ims_jy_qsc_msgcode',  'token')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_msgcode')." ADD `token` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_msgcode')) {
	if(!pdo_fieldexists('ims_jy_qsc_msgcode',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_msgcode')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_msgcode')) {
	if(!pdo_fieldexists('ims_jy_qsc_msgcode',  'code')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_msgcode')." ADD `code` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_msgcode')) {
	if(!pdo_fieldexists('ims_jy_qsc_msgcode',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_msgcode')." ADD `status` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_msgcode')) {
	if(!pdo_fieldexists('ims_jy_qsc_msgcode',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_msgcode')." ADD `tel` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_oques')) {
	if(!pdo_fieldexists('ims_jy_qsc_oques',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_oques')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_oques')) {
	if(!pdo_fieldexists('ims_jy_qsc_oques',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_oques')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_oques')) {
	if(!pdo_fieldexists('ims_jy_qsc_oques',  'level')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_oques')." ADD `level` int(1) NOT NULL DEFAULT '1';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_oques')) {
	if(!pdo_fieldexists('ims_jy_qsc_oques',  'title')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_oques')." ADD `title` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_oques')) {
	if(!pdo_fieldexists('ims_jy_qsc_oques',  'rank')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_oques')." ADD `rank` int(11) NOT NULL DEFAULT '50';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_oques')) {
	if(!pdo_fieldexists('ims_jy_qsc_oques',  'type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_oques')." ADD `type` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_oques')) {
	if(!pdo_fieldexists('ims_jy_qsc_oques',  'pre_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_oques')." ADD `pre_id` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_oques')) {
	if(!pdo_fieldexists('ims_jy_qsc_oques',  'content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_oques')." ADD `content` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_oques')) {
	if(!pdo_fieldexists('ims_jy_qsc_oques',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_oques')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `uid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `fid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `avatar` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `status` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'tid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `tid` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'fee')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `fee` float(255,2);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'msg')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `msg` varchar(255) NOT NULL DEFAULT '支持';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'address_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `address_id` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `reid` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `type` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'dream_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `dream_id` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'shouxufei')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `shouxufei` float(11,2) NOT NULL DEFAULT '0.00';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'count')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `count` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'fahuo')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `fahuo` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'bank_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `bank_id` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'transaction_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `transaction_id` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'wantSupportTel')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `wantSupportTel` varchar(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_paylog')) {
	if(!pdo_fieldexists('ims_jy_qsc_paylog',  'is_roob')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_paylog')." ADD `is_roob` int(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_pc_login')) {
	if(!pdo_fieldexists('ims_jy_qsc_pc_login',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_pc_login')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_pc_login')) {
	if(!pdo_fieldexists('ims_jy_qsc_pc_login',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_pc_login')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_pc_login')) {
	if(!pdo_fieldexists('ims_jy_qsc_pc_login',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_pc_login')." ADD `upbdate` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_pc_login')) {
	if(!pdo_fieldexists('ims_jy_qsc_pc_login',  'token')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_pc_login')." ADD `token` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_pc_login')) {
	if(!pdo_fieldexists('ims_jy_qsc_pc_login',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_pc_login')." ADD `status` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_pc_login')) {
	if(!pdo_fieldexists('ims_jy_qsc_pc_login',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_pc_login')." ADD `openid` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_photo')) {
	if(!pdo_fieldexists('ims_jy_qsc_photo',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_photo')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_photo')) {
	if(!pdo_fieldexists('ims_jy_qsc_photo',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_photo')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_photo')) {
	if(!pdo_fieldexists('ims_jy_qsc_photo',  'pic')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_photo')." ADD `pic` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_photo')) {
	if(!pdo_fieldexists('ims_jy_qsc_photo',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_photo')." ADD `thumb` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_photo')) {
	if(!pdo_fieldexists('ims_jy_qsc_photo',  'dir')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_photo')." ADD `dir` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_photo')) {
	if(!pdo_fieldexists('ims_jy_qsc_photo',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_photo')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_name` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_logo')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_logo` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_plus1')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_plus1` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_plus2')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_plus2` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_plus3')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_plus3` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_plus4')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_plus4` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_desc` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_shuoming')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_shuoming` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_min')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_min` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_max')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_max` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_moren')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_moren` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_texdesc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_texdesc` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_mstips')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_mstips` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'title_placeholder')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `title_placeholder` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'is_p')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `is_p` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'desc_placeholder')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `desc_placeholder` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_gg')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_gg` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'is_shenhe')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `is_shenhe` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'pre_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `pre_id` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'rank')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `rank` int(11) NOT NULL DEFAULT '50';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'yongjin')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `yongjin` float(11,2);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'shouchishenfenz')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `shouchishenfenz` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'end_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `end_type` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'is_goods')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `is_goods` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'is_suptel')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `is_suptel` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'is_hospital')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `is_hospital` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_project')) {
	if(!pdo_fieldexists('ims_jy_qsc_project',  'project_plus5')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_project')." ADD `project_plus5` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_ques')) {
	if(!pdo_fieldexists('ims_jy_qsc_ques',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_ques')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_ques')) {
	if(!pdo_fieldexists('ims_jy_qsc_ques',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_ques')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_ques')) {
	if(!pdo_fieldexists('ims_jy_qsc_ques',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_ques')." ADD `pid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_ques')) {
	if(!pdo_fieldexists('ims_jy_qsc_ques',  'title')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_ques')." ADD `title` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_ques')) {
	if(!pdo_fieldexists('ims_jy_qsc_ques',  'content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_ques')." ADD `content` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_ques')) {
	if(!pdo_fieldexists('ims_jy_qsc_ques',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_ques')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_ques')) {
	if(!pdo_fieldexists('ims_jy_qsc_ques',  'rank')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_ques')." ADD `rank` int(11) NOT NULL DEFAULT '50';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'sitename')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `sitename` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'sitebanner')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `sitebanner` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'share_title')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `share_title` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'share_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `share_desc` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'share_img')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `share_img` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `upbdate` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'head_title1')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `head_title1` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'project_fqtk')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `project_fqtk` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'file_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `file_type` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_type` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qniu_access')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qniu_access` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qniu_secret')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qniu_secret` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qniu_bucket')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qniu_bucket` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qniu_url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qniu_url` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'oss_access')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `oss_access` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'oss_secret')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `oss_secret` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'oss_bucket')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `oss_bucket` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_appid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_appid` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_appsecret')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_appsecret` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_miyao')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_miyao` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'oss_url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `oss_url` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'oss_endpoint')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `oss_endpoint` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'weibo_content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `weibo_content` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'weibo_pic')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `weibo_pic` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'weibo_url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `weibo_url` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qqzon_content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qqzon_content` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qqzon_pic')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qqzon_pic` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qqzon_url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qqzon_url` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qqweibo_content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qqweibo_content` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qqweibo_pic')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qqweibo_pic` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qqweibo_url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qqweibo_url` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qq_content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qq_content` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qq_pic')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qq_pic` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'qq_url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `qq_url` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_number')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_number` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'about')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `about` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `tel` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'dayu_appkey')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `dayu_appkey` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'dayu_secretkey')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `dayu_secretkey` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'dayu_sign')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `dayu_sign` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'dayu_temp')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `dayu_temp` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'logo')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `logo` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'project_header')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `project_header` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_link')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_link` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_sup_success')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_sup_success` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_pl_success')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_pl_success` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_news')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_news` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_sup_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_sup_type` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_sup_temp')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_sup_temp` int(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_pl_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_pl_type` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_pl_tmp')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_pl_tmp` int(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'liuyan')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `liuyan` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_news_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_news_type` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_news_tmp')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_news_tmp` int(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'share_url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `share_url` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'help_back')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `help_back` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'detaillogo')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `detaillogo` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_com_title')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_com_title` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_hb_wishing')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_hb_wishing` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_hb_send_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_hb_send_name` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_hb_act_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_hb_act_name` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pay_hb_remark')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pay_hb_remark` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'faqishuomingwenzi')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `faqishuomingwenzi` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'ip_address')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `ip_address` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_cksuccess_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_cksuccess_type` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_cksuccess_content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_cksuccess_content` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_cksuccess_tmp')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_cksuccess_tmp` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_ckfail_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_ckfail_type` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_ckfail_content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_ckfail_content` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_ckfail_tmp')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_ckfail_tmp` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_tuikuan_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_tuikuan_type` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_tuikuan_content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_tuikuan_content` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'kf_tuikuan_tmp')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `kf_tuikuan_tmp` int(11) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'yanzheng')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `yanzheng` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'verifypeer')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `verifypeer` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'mobileshuom')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `mobileshuom` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'mobileshuom_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `mobileshuom_desc` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'ziliao_tips')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `ziliao_tips` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'weixn_width')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `weixn_width` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'tixian_suilv')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `tixian_suilv` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'web_logo')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `web_logo` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'zhengshishuoming')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `zhengshishuoming` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'supr_rank')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `supr_rank` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pc_1_pic')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pc_1_pic` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pc_2_pic')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pc_2_pic` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pc_3_pic')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pc_3_pic` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'follow_display')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `follow_display` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'follow_btn_envt')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `follow_btn_envt` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'follow_logo')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `follow_logo` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'follow_qrcode')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `follow_qrcode` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'follow_btn')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `follow_btn` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'follow_txt')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `follow_txt` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'follow_url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `follow_url` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'follow_qrcode_txt')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `follow_qrcode_txt` varchar(255) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'huzhu_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `huzhu_desc` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'thirth_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `thirth_desc` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'hz_share_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `hz_share_desc` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'vip_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `vip_desc` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'pan_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `pan_desc` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'hz_question')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `hz_question` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'hz_img')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `hz_img` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'hz_img_index')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `hz_img_index` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'hz_switch')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `hz_switch` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'hz_money')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `hz_money` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'hz_gongyue')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `hz_gongyue` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'hz_jiangkang')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `hz_jiangkang` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'bro_wx')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `bro_wx` varchar(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'alipay_type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `alipay_type` varchar(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'user_img')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `user_img` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_setting')) {
	if(!pdo_fieldexists('ims_jy_qsc_setting',  'is_h5')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_setting')." ADD `is_h5` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shiming')) {
	if(!pdo_fieldexists('ims_jy_qsc_shiming',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shiming')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shiming')) {
	if(!pdo_fieldexists('ims_jy_qsc_shiming',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shiming')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shiming')) {
	if(!pdo_fieldexists('ims_jy_qsc_shiming',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shiming')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shiming')) {
	if(!pdo_fieldexists('ims_jy_qsc_shiming',  'real_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shiming')." ADD `real_name` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shiming')) {
	if(!pdo_fieldexists('ims_jy_qsc_shiming',  'cert_no')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shiming')." ADD `cert_no` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shiming')) {
	if(!pdo_fieldexists('ims_jy_qsc_shiming',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shiming')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shiming')) {
	if(!pdo_fieldexists('ims_jy_qsc_shiming',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shiming')." ADD `status` int(1) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shiming')) {
	if(!pdo_fieldexists('ims_jy_qsc_shiming',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shiming')." ADD `thumb` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `fid` int(111);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'idcar')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `idcar` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'zuzhi')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `zuzhi` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'zhengm')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `zhengm` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'creator_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `creator_name` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'creator_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `creator_id` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'creator_phone')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `creator_phone` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'zuzhir_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `zuzhir_name` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'zuzhi_info')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `zuzhi_info` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `type` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `status` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'sz_creator_id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `sz_creator_id` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'hospital')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `hospital` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'sz_name')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `sz_name` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'creator_relation')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `creator_relation` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_shouchishenfenz')) {
	if(!pdo_fieldexists('ims_jy_qsc_shouchishenfenz',  'disease')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_shouchishenfenz')." ADD `disease` varchar(255) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_sysmsg')) {
	if(!pdo_fieldexists('ims_jy_qsc_sysmsg',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_sysmsg')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_sysmsg')) {
	if(!pdo_fieldexists('ims_jy_qsc_sysmsg',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_sysmsg')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_sysmsg')) {
	if(!pdo_fieldexists('ims_jy_qsc_sysmsg',  'ms_title')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_sysmsg')." ADD `ms_title` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_sysmsg')) {
	if(!pdo_fieldexists('ims_jy_qsc_sysmsg',  'ms_content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_sysmsg')." ADD `ms_content` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_sysmsg')) {
	if(!pdo_fieldexists('ims_jy_qsc_sysmsg',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_sysmsg')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_sysmsg')) {
	if(!pdo_fieldexists('ims_jy_qsc_sysmsg',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_sysmsg')." ADD `status` int(1) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_sysmsg')) {
	if(!pdo_fieldexists('ims_jy_qsc_sysmsg',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_sysmsg')." ADD `thumb` varchar(233);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_temp')) {
	if(!pdo_fieldexists('ims_jy_qsc_temp',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_temp')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_temp')) {
	if(!pdo_fieldexists('ims_jy_qsc_temp',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_temp')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_temp')) {
	if(!pdo_fieldexists('ims_jy_qsc_temp',  'parama')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_temp')." ADD `parama` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_temp')) {
	if(!pdo_fieldexists('ims_jy_qsc_temp',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_temp')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_temp')) {
	if(!pdo_fieldexists('ims_jy_qsc_temp',  'catename')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_temp')." ADD `catename` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_temp')) {
	if(!pdo_fieldexists('ims_jy_qsc_temp',  'tempid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_temp')." ADD `tempid` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_temp')) {
	if(!pdo_fieldexists('ims_jy_qsc_temp',  'url')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_temp')." ADD `url` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_temp')) {
	if(!pdo_fieldexists('ims_jy_qsc_temp',  'content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_temp')." ADD `content` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_update')) {
	if(!pdo_fieldexists('ims_jy_qsc_update',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_update')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_update')) {
	if(!pdo_fieldexists('ims_jy_qsc_update',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_update')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_update')) {
	if(!pdo_fieldexists('ims_jy_qsc_update',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_update')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_update')) {
	if(!pdo_fieldexists('ims_jy_qsc_update',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_update')." ADD `fid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_update')) {
	if(!pdo_fieldexists('ims_jy_qsc_update',  'content')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_update')." ADD `content` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_update')) {
	if(!pdo_fieldexists('ims_jy_qsc_update',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_update')." ADD `thumb` text;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_update')) {
	if(!pdo_fieldexists('ims_jy_qsc_update',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_update')." ADD `upbdate` varchar(32);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_update')) {
	if(!pdo_fieldexists('ims_jy_qsc_update',  'type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_update')." ADD `type` int(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_update')) {
	if(!pdo_fieldexists('ims_jy_qsc_update',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_update')." ADD `status` int(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `uid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'money')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `money` float(11,2) NOT NULL DEFAULT '0.00';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `upbdate` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `status` int(1) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'type')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `type` int(1) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'payid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `payid` int(1);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'update')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `update` varchar(255);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_withdraw')) {
	if(!pdo_fieldexists('ims_jy_qsc_withdraw',  'token')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_withdraw')." ADD `token` varchar(255) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_jy_qsc_yongjin')) {
	if(!pdo_fieldexists('ims_jy_qsc_yongjin',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_yongjin')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_jy_qsc_yongjin')) {
	if(!pdo_fieldexists('ims_jy_qsc_yongjin',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_yongjin')." ADD `weid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_yongjin')) {
	if(!pdo_fieldexists('ims_jy_qsc_yongjin',  'mid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_yongjin')." ADD `mid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_yongjin')) {
	if(!pdo_fieldexists('ims_jy_qsc_yongjin',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_yongjin')." ADD `uid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_yongjin')) {
	if(!pdo_fieldexists('ims_jy_qsc_yongjin',  'fid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_yongjin')." ADD `fid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_yongjin')) {
	if(!pdo_fieldexists('ims_jy_qsc_yongjin',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_yongjin')." ADD `pid` int(11);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_yongjin')) {
	if(!pdo_fieldexists('ims_jy_qsc_yongjin',  'fee')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_yongjin')." ADD `fee` float(11,2);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_yongjin')) {
	if(!pdo_fieldexists('ims_jy_qsc_yongjin',  'user_fee')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_yongjin')." ADD `user_fee` float(11,2);");
	}	
}
if(pdo_tableexists('ims_jy_qsc_yongjin')) {
	if(!pdo_fieldexists('ims_jy_qsc_yongjin',  'upbdate')) {
		pdo_query("ALTER TABLE ".tablename('ims_jy_qsc_yongjin')." ADD `upbdate` varchar(32);");
	}	
}
