<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_activity` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`logo` varchar(200) NOT NULL,
`content` text,
`tel` varchar(20) NOT NULL,
`address` varchar(200) NOT NULL,
`url` varchar(200) NOT NULL,
`start_time` int(10) NOT NULL,
`end_time` int(10) NOT NULL,
`isfirst` tinyint(1) NOT NULL,
`status` tinyint(1) NOT NULL,
`displayorder` tinyint(3) NOT NULL,
`dateline` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_activity_feedback` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`activityid` int(11) NOT NULL,
`parentid` int(11),
`from_user` varchar(100),
`nickname` varchar(30),
`headimgurl` varchar(500),
`content` varchar(600),
`top` tinyint(1) NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1),
`dateline` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_activity_user` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`activityid` int(10) unsigned NOT NULL,
`from_user` varchar(100),
`nickname` varchar(100),
`headimgurl` varchar(500),
`title` varchar(200),
`username` varchar(100),
`tel` varchar(30),
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`name` varchar(50) NOT NULL,
`parentid` int(10) unsigned NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_goods` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`thumb` varchar(100) NOT NULL,
`description` varchar(1000) NOT NULL,
`marketprice` varchar(10) NOT NULL,
`productprice` varchar(10) NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1) unsigned NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_introduce` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`title` varchar(50) NOT NULL,
`logo` varchar(200) NOT NULL,
`info` varchar(1000) NOT NULL,
`content` text NOT NULL,
`savewinerule` text NOT NULL,
`tel` varchar(20) NOT NULL,
`location_p` varchar(100) NOT NULL,
`location_c` varchar(100) NOT NULL,
`location_a` varchar(100) NOT NULL,
`hours` varchar(200) NOT NULL,
`address` varchar(200) NOT NULL,
`contact` varchar(100) NOT NULL,
`consume` varchar(100) NOT NULL,
`wifi` varchar(200) NOT NULL,
`place` varchar(200) NOT NULL,
`lat` decimal(18,10) NOT NULL,
`lng` decimal(18,10) NOT NULL,
`displayorder` tinyint(3) NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_neighbor_feedback` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`from_user` varchar(100),
`nickname` varchar(30),
`headimgurl` varchar(500),
`content` varchar(600),
`top` tinyint(1) NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1),
`dateline` int(11),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_neighbor_user` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(100),
`nickname` varchar(100),
`headimgurl` varchar(500),
`username` varchar(100),
`weixin` varchar(50),
`tel` varchar(30),
`qq` varchar(30),
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1),
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_photo` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`url` varchar(200) NOT NULL,
`description` varchar(1000) NOT NULL,
`attachment` varchar(100) NOT NULL,
`from_user` varchar(100),
`nickname` varchar(100),
`likecount` int(10) unsigned NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`isfirst` tinyint(1) NOT NULL,
`mode` tinyint(1) NOT NULL,
`checked` tinyint(1) NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_product` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`pcate` int(10) unsigned NOT NULL,
`ccate` int(10) unsigned NOT NULL,
`title` varchar(50) NOT NULL,
`logo` varchar(200) NOT NULL,
`content` text NOT NULL,
`url` varchar(200) NOT NULL,
`isfirst` tinyint(1) NOT NULL,
`top` tinyint(1) NOT NULL,
`displayorder` tinyint(3) NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_savewine_log` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`from_user` varchar(100),
`nickname` varchar(100),
`headimgurl` varchar(500),
`savenumber` varchar(100) NOT NULL,
`title` varchar(200),
`username` varchar(100),
`tel` varchar(30),
`remark` text NOT NULL,
`displayorder` int(10) unsigned NOT NULL,
`status` tinyint(1) NOT NULL,
`takeouttime` int(10) unsigned NOT NULL,
`savetime` int(10) unsigned NOT NULL,
`dateline` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_weisrc_nightclub_setting` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`weid` int(10) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`bg` varchar(500) NOT NULL,
`pagesize` int(10) unsigned NOT NULL,
`topcolor` varchar(20) NOT NULL,
`topbgcolor` varchar(20) NOT NULL,
`announcebordercolor` varchar(20) NOT NULL,
`announcebgcolor` varchar(20) NOT NULL,
`announcecolor` varchar(20) NOT NULL,
`storestitlecolor` varchar(20) NOT NULL,
`storesstatuscolor` varchar(20) NOT NULL,
`showcity` tinyint(1) NOT NULL,
`settled` tinyint(1) unsigned NOT NULL,
`feedback_show_enable` tinyint(1) NOT NULL,
`feedback_check_enable` tinyint(1) NOT NULL,
`photo_check_enable` tinyint(1) NOT NULL,
`scroll_announce` varchar(500) NOT NULL,
`scroll_announce_speed` tinyint(2) unsigned NOT NULL,
`scroll_announce_link` varchar(500) NOT NULL,
`scroll_announce_enable` tinyint(1) NOT NULL,
`copyright` varchar(500) NOT NULL,
`copyright_link` varchar(500) NOT NULL,
`appid` varchar(300) NOT NULL,
`secret` varchar(300) NOT NULL,
`share_title` varchar(100) NOT NULL,
`share_image` varchar(500) NOT NULL,
`share_desc` varchar(200) NOT NULL,
`share_cancel` varchar(200) NOT NULL,
`share_url` varchar(200) NOT NULL,
`share_num` int(10) NOT NULL,
`follow_url` varchar(200) NOT NULL,
`dateline` int(10) unsigned NOT NULL,
`tplinfowine` varchar(500) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `weid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'pcate')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `pcate` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'ccate')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `ccate` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'title')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `title` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'logo')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `logo` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'content')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `content` text;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `tel` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'address')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `address` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'url')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `url` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'start_time')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `start_time` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'end_time')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `end_time` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'isfirst')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `isfirst` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `status` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `displayorder` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity')." ADD `dateline` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'activityid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `activityid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'parentid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `parentid` int(11);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'from_user')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `from_user` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `nickname` varchar(30);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'headimgurl')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `headimgurl` varchar(500);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'content')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `content` varchar(600);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'top')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `top` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `displayorder` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `status` tinyint(1);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_feedback',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_feedback')." ADD `dateline` int(11);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'activityid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `activityid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'from_user')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `from_user` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `nickname` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'headimgurl')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `headimgurl` varchar(500);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'title')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `title` varchar(200);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'username')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `username` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `tel` varchar(30);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `displayorder` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `status` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_activity_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_activity_user',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_activity_user')." ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_category')) {
	if(!pdo_fieldexists('weisrc_nightclub_category',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_category')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_category')) {
	if(!pdo_fieldexists('weisrc_nightclub_category',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_category')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_category')) {
	if(!pdo_fieldexists('weisrc_nightclub_category',  'name')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_category')." ADD `name` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_category')) {
	if(!pdo_fieldexists('weisrc_nightclub_category',  'parentid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_category')." ADD `parentid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_category')) {
	if(!pdo_fieldexists('weisrc_nightclub_category',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_category')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_category')) {
	if(!pdo_fieldexists('weisrc_nightclub_category',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_category')." ADD `status` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'pcate')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `pcate` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'ccate')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `ccate` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'title')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `thumb` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'description')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `description` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'marketprice')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `marketprice` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'productprice')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `productprice` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `displayorder` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `status` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_goods')) {
	if(!pdo_fieldexists('weisrc_nightclub_goods',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_goods')." ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `weid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'title')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `title` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'logo')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `logo` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'info')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `info` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'content')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `content` text NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'savewinerule')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `savewinerule` text NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `tel` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'location_p')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `location_p` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'location_c')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `location_c` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'location_a')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `location_a` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'hours')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `hours` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'address')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `address` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'contact')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `contact` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'consume')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `consume` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'wifi')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `wifi` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'place')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `place` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'lat')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `lat` decimal(18,10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'lng')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `lng` decimal(18,10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `displayorder` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `status` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_introduce')) {
	if(!pdo_fieldexists('weisrc_nightclub_introduce',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_introduce')." ADD `dateline` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'from_user')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `from_user` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `nickname` varchar(30);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'headimgurl')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `headimgurl` varchar(500);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'content')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `content` varchar(600);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'top')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `top` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `displayorder` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `status` tinyint(1);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_feedback')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_feedback',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_feedback')." ADD `dateline` int(11);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'from_user')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `from_user` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `nickname` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'headimgurl')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `headimgurl` varchar(500);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'username')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `username` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'weixin')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `weixin` varchar(50);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `tel` varchar(30);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'qq')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `qq` varchar(30);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `displayorder` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `status` tinyint(1);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_neighbor_user')) {
	if(!pdo_fieldexists('weisrc_nightclub_neighbor_user',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_neighbor_user')." ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'title')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'url')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `url` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'description')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `description` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'attachment')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `attachment` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'from_user')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `from_user` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `nickname` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'likecount')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `likecount` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `displayorder` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'isfirst')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `isfirst` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'mode')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `mode` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'checked')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `checked` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `status` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_photo')) {
	if(!pdo_fieldexists('weisrc_nightclub_photo',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_photo')." ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `weid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'pcate')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `pcate` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'ccate')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `ccate` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'title')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `title` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'logo')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `logo` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'content')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `content` text NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'url')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `url` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'isfirst')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `isfirst` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'top')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `top` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `displayorder` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `status` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_product')) {
	if(!pdo_fieldexists('weisrc_nightclub_product',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_product')." ADD `dateline` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'from_user')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `from_user` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `nickname` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'headimgurl')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `headimgurl` varchar(500);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'savenumber')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `savenumber` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'title')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `title` varchar(200);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'username')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `username` varchar(100);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'tel')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `tel` varchar(30);");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'remark')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `remark` text NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `displayorder` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'status')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `status` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'takeouttime')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `takeouttime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'savetime')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `savetime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_savewine_log')) {
	if(!pdo_fieldexists('weisrc_nightclub_savewine_log',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_savewine_log')." ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'id')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `weid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'title')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'bg')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `bg` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'pagesize')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `pagesize` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'topcolor')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `topcolor` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'topbgcolor')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `topbgcolor` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'announcebordercolor')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `announcebordercolor` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'announcebgcolor')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `announcebgcolor` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'announcecolor')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `announcecolor` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'storestitlecolor')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `storestitlecolor` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'storesstatuscolor')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `storesstatuscolor` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'showcity')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `showcity` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'settled')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `settled` tinyint(1) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'feedback_show_enable')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `feedback_show_enable` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'feedback_check_enable')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `feedback_check_enable` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'photo_check_enable')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `photo_check_enable` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'scroll_announce')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `scroll_announce` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'scroll_announce_speed')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `scroll_announce_speed` tinyint(2) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'scroll_announce_link')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `scroll_announce_link` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'scroll_announce_enable')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `scroll_announce_enable` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'copyright')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `copyright` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'copyright_link')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `copyright_link` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'appid')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `appid` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'secret')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `secret` varchar(300) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'share_title')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `share_title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'share_image')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `share_image` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'share_desc')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `share_desc` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'share_cancel')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `share_cancel` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'share_url')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `share_url` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'share_num')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `share_num` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'follow_url')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `follow_url` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'dateline')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `dateline` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('weisrc_nightclub_setting')) {
	if(!pdo_fieldexists('weisrc_nightclub_setting',  'tplinfowine')) {
		pdo_query("ALTER TABLE ".tablename('weisrc_nightclub_setting')." ADD `tplinfowine` varchar(500) NOT NULL;");
	}	
}
