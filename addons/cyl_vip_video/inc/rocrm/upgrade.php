<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(5) NOT NULL,
`title` varchar(255) NOT NULL,
`uid` varchar(25) NOT NULL,
`openid` varchar(255) NOT NULL,
`time` varchar(15) NOT NULL,
`video_url` text NOT NULL,
`share` int(3) NOT NULL,
`yvideo_url` text NOT NULL,
`type` varchar(25) NOT NULL,
`index` int(2) NOT NULL,
`video_id` int(11) NOT NULL,
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`parentid` int(10) NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`name` varchar(20) NOT NULL,
`status` tinyint(3) unsigned NOT NULL,
`displayorder` tinyint(3) unsigned NOT NULL,
`url` varchar(1000) NOT NULL,
PRIMARY KEY (`id`),
KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_hdp` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`title` varchar(255) NOT NULL,
`thumb` varchar(1000) NOT NULL,
`link` varchar(1000) NOT NULL,
`out_link` varchar(1000) NOT NULL,
`type` varchar(15) NOT NULL,
`sort` int(5) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_keyword` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`title` varchar(25) NOT NULL,
`card_id` varchar(25) NOT NULL,
`num` int(11) NOT NULL,
`day` int(11) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_keyword_id` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`openid` varchar(1000) NOT NULL,
`uniacid` int(11) NOT NULL,
`pwd` varchar(25) NOT NULL,
`card_id` varchar(25) NOT NULL,
`day` int(11) NOT NULL,
`status` int(2) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_manage` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`title` varchar(25) NOT NULL,
`thumb` varchar(1000) NOT NULL,
`year` varchar(25) NOT NULL,
`star` varchar(25) NOT NULL,
`type` varchar(25) NOT NULL,
`actor` varchar(25) NOT NULL,
`video_url` text NOT NULL,
`desc` text NOT NULL,
`time` varchar(25) NOT NULL,
`screen` varchar(25) NOT NULL,
`cid` int(3) NOT NULL,
`pid` int(3) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_member` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`openid` varchar(255) NOT NULL,
`uid` varchar(25) NOT NULL,
`nickname` varchar(255) NOT NULL,
`avatar` varchar(1000) NOT NULL,
`end_time` varchar(15) NOT NULL,
`is_pay` int(2) NOT NULL,
`time` varchar(15) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_cyl_vip_video_order` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(5) NOT NULL,
`openid` varchar(255) NOT NULL,
`uid` varchar(25) NOT NULL,
`status` int(2) NOT NULL,
`fee` decimal(10,2) NOT NULL,
`time` varchar(15) NOT NULL,
`tid` varchar(255) NOT NULL,
`day` int(5) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `uniacid` int(5) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'title')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `title` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'uid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `uid` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'openid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `openid` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'time')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `time` varchar(15) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'video_url')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `video_url` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'share')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `share` int(3) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'yvideo_url')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `yvideo_url` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'type')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `type` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'index')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `index` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video')) {
	if(!pdo_fieldexists('ims_cyl_vip_video',  'video_id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video` ADD `video_id` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_category')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_category',  'id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_category` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_category')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_category',  'parentid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_category` ADD `parentid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_category')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_category',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_category` ADD `uniacid` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_category')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_category',  'name')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_category` ADD `name` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_category')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_category',  'status')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_category` ADD `status` tinyint(3) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_category')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_category',  'displayorder')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_category` ADD `displayorder` tinyint(3) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_category')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_category',  'url')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_category` ADD `url` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_hdp')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_hdp',  'id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_hdp` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_hdp')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_hdp',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_hdp` ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_hdp')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_hdp',  'title')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_hdp` ADD `title` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_hdp')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_hdp',  'thumb')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_hdp` ADD `thumb` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_hdp')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_hdp',  'link')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_hdp` ADD `link` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_hdp')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_hdp',  'out_link')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_hdp` ADD `out_link` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_hdp')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_hdp',  'type')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_hdp` ADD `type` varchar(15) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_hdp')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_hdp',  'sort')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_hdp` ADD `sort` int(5) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword',  'id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword` ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword',  'title')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword` ADD `title` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword',  'card_id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword` ADD `card_id` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword',  'num')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword` ADD `num` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword',  'day')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword` ADD `day` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword_id')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword_id',  'id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword_id` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword_id')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword_id',  'openid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword_id` ADD `openid` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword_id')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword_id',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword_id` ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword_id')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword_id',  'pwd')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword_id` ADD `pwd` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword_id')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword_id',  'card_id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword_id` ADD `card_id` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword_id')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword_id',  'day')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword_id` ADD `day` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_keyword_id')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_keyword_id',  'status')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_keyword_id` ADD `status` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'title')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `title` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'thumb')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `thumb` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'year')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `year` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'star')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `star` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'type')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `type` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'actor')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `actor` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'video_url')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `video_url` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'desc')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `desc` text NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'time')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `time` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'screen')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `screen` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'cid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `cid` int(3) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_manage')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_manage',  'pid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_manage` ADD `pid` int(3) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_member')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_member',  'id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_member` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_member')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_member',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_member` ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_member')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_member',  'openid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_member` ADD `openid` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_member')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_member',  'uid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_member` ADD `uid` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_member')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_member',  'nickname')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_member` ADD `nickname` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_member')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_member',  'avatar')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_member` ADD `avatar` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_member')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_member',  'end_time')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_member` ADD `end_time` varchar(15) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_member')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_member',  'is_pay')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_member` ADD `is_pay` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_member')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_member',  'time')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_member` ADD `time` varchar(15) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_order')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_order',  'id')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_order` ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_order')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_order',  'uniacid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_order` ADD `uniacid` int(5) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_order')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_order',  'openid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_order` ADD `openid` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_order')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_order',  'uid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_order` ADD `uid` varchar(25) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_order')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_order',  'status')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_order` ADD `status` int(2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_order')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_order',  'fee')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_order` ADD `fee` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_order')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_order',  'time')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_order` ADD `time` varchar(15) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_order')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_order',  'tid')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_order` ADD `tid` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_cyl_vip_video_order')) {
	if(!pdo_fieldexists('ims_cyl_vip_video_order',  'day')) {
		pdo_query("ALTER TABLE `ims_cyl_vip_video_order` ADD `day` int(5) NOT NULL;");
	}	
}
