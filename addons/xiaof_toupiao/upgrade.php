<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`groups` tinyint(255) NOT NULL,
`verify` tinyint(255) NOT NULL,
`openid` varchar(50) NOT NULL,
`nickname` varchar(20) NOT NULL,
`avatar` varchar(255) NOT NULL,
`ip` bigint(20) NOT NULL,
`name` char(36) NOT NULL,
`phone` char(36) NOT NULL,
`pic` varchar(255) NOT NULL,
`sound` varchar(255) NOT NULL,
`describe` char(255) NOT NULL,
`detail` text,
`data` mediumtext NOT NULL,
`click` int(11) NOT NULL,
`share` int(11) NOT NULL,
`good` int(11) NOT NULL,
`open` tinyint(255) NOT NULL,
`double_at` int(10) NOT NULL,
`created_at` int(10) NOT NULL,
`updated_at` int(10) NOT NULL,
`poster` varchar(255) NOT NULL,
`locking_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `sid_2` (`sid`,`openid`),
UNIQUE KEY `sid_3` (`sid`,`phone`),
KEY `sid` (`sid`),
KEY `sid_4` (`sid`,`verify`),
KEY `groups` (`groups`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_acid` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`acid` int(11) NOT NULL,
`qrcode` varchar(255) NOT NULL,
PRIMARY KEY (`id`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_draw` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`prizeid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`uname` varchar(255) NOT NULL,
`avatar` varchar(255) NOT NULL,
`uses` tinyint(255) NOT NULL,
`attr` tinyint(255) NOT NULL,
`credit` int(11) NOT NULL,
`name` varchar(50) NOT NULL,
`num` int(11) NOT NULL,
`openid` varchar(50) NOT NULL,
`ip` bigint(20) NOT NULL,
`created_at` int(10) NOT NULL,
`bdelete_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `sid` (`sid`,`uid`),
KEY `sid_2` (`sid`,`attr`),
KEY `sid_3` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_drawlog` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`uid` int(11) NOT NULL,
`uname` varchar(255) NOT NULL,
`avatar` varchar(255) NOT NULL,
`attr` int(11) NOT NULL,
`data` varchar(255) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_log` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`fanid` int(11) NOT NULL,
`nickname` varchar(20) NOT NULL,
`avatar` varchar(255) NOT NULL,
`num` tinyint(255) NOT NULL,
`openid` varchar(50) NOT NULL,
`ip` bigint(20) NOT NULL,
`valid` tinyint(255) NOT NULL,
`unique_at` int(8) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `pid_2` (`pid`,`openid`,`unique_at`),
KEY `pid` (`pid`),
KEY `sid_2` (`sid`),
KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_manage` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`ip` bigint(20) NOT NULL,
`num` int(11) NOT NULL,
`operation` varchar(255) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `pid` (`pid`,`ip`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_pic` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`pid` int(11) NOT NULL,
`thumb` varchar(255) NOT NULL,
`url` varchar(255) NOT NULL,
`is_show` tinyint(255) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `sid` (`sid`,`pid`),
KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_rule` (
`rid` int(11) NOT NULL,
`sid` int(11) NOT NULL,
`action` tinyint(255) NOT NULL,
`keyword` varchar(255) NOT NULL,
`uniacid` int(11) NOT NULL,
PRIMARY KEY (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_safe` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`sid` int(11) NOT NULL,
`ip` bigint(20) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `ip` (`ip`),
KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_setting` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`tit` varchar(255) NOT NULL,
`data` text NOT NULL,
`groups` text NOT NULL,
`unfollow` tinyint(255) NOT NULL,
`detail` text NOT NULL,
`bottom` text NOT NULL,
`click` int(11) NOT NULL,
`created_at` int(10) NOT NULL,
PRIMARY KEY (`id`),
KEY `unfollow` (`unfollow`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_xiaof_toupiao_smslog` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`phone` char(20) NOT NULL,
`ip` bigint(20) NOT NULL,
`created_at` int(10) NOT NULL,
`unique_at` int(8) NOT NULL,
PRIMARY KEY (`id`),
KEY `phone` (`phone`),
KEY `ip` (`ip`),
KEY `day` (`unique_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'groups')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `groups` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'verify')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `verify` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `openid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `nickname` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `avatar` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'ip')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `ip` bigint(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'name')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `name` char(36) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'phone')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `phone` char(36) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'pic')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `pic` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'sound')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `sound` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'describe')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `describe` char(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'detail')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `detail` text;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'data')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `data` mediumtext NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'click')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `click` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'share')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `share` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'good')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `good` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'open')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `open` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'double_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `double_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'created_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `created_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'updated_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `updated_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'poster')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `poster` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao')) {
	if(!pdo_fieldexists('xiaof_toupiao',  'locking_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao')." ADD `locking_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_acid')) {
	if(!pdo_fieldexists('xiaof_toupiao_acid',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_acid')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_acid')) {
	if(!pdo_fieldexists('xiaof_toupiao_acid',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_acid')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_acid')) {
	if(!pdo_fieldexists('xiaof_toupiao_acid',  'acid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_acid')." ADD `acid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_acid')) {
	if(!pdo_fieldexists('xiaof_toupiao_acid',  'qrcode')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_acid')." ADD `qrcode` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'prizeid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `prizeid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'uname')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `uname` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `avatar` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'uses')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `uses` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'attr')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `attr` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'credit')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `credit` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'name')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `name` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'num')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `num` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `openid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'ip')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `ip` bigint(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'created_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `created_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_draw')) {
	if(!pdo_fieldexists('xiaof_toupiao_draw',  'bdelete_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_draw')." ADD `bdelete_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_drawlog')) {
	if(!pdo_fieldexists('xiaof_toupiao_drawlog',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_drawlog')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_drawlog')) {
	if(!pdo_fieldexists('xiaof_toupiao_drawlog',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_drawlog')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_drawlog')) {
	if(!pdo_fieldexists('xiaof_toupiao_drawlog',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_drawlog')." ADD `pid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_drawlog')) {
	if(!pdo_fieldexists('xiaof_toupiao_drawlog',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_drawlog')." ADD `uid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_drawlog')) {
	if(!pdo_fieldexists('xiaof_toupiao_drawlog',  'uname')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_drawlog')." ADD `uname` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_drawlog')) {
	if(!pdo_fieldexists('xiaof_toupiao_drawlog',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_drawlog')." ADD `avatar` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_drawlog')) {
	if(!pdo_fieldexists('xiaof_toupiao_drawlog',  'attr')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_drawlog')." ADD `attr` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_drawlog')) {
	if(!pdo_fieldexists('xiaof_toupiao_drawlog',  'data')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_drawlog')." ADD `data` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_drawlog')) {
	if(!pdo_fieldexists('xiaof_toupiao_drawlog',  'created_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_drawlog')." ADD `created_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `pid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'fanid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `fanid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `nickname` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `avatar` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'num')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `num` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `openid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'ip')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `ip` bigint(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'valid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `valid` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'unique_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `unique_at` int(8) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_log')) {
	if(!pdo_fieldexists('xiaof_toupiao_log',  'created_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_log')." ADD `created_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_manage')) {
	if(!pdo_fieldexists('xiaof_toupiao_manage',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_manage')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_manage')) {
	if(!pdo_fieldexists('xiaof_toupiao_manage',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_manage')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_manage')) {
	if(!pdo_fieldexists('xiaof_toupiao_manage',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_manage')." ADD `pid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_manage')) {
	if(!pdo_fieldexists('xiaof_toupiao_manage',  'ip')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_manage')." ADD `ip` bigint(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_manage')) {
	if(!pdo_fieldexists('xiaof_toupiao_manage',  'num')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_manage')." ADD `num` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_manage')) {
	if(!pdo_fieldexists('xiaof_toupiao_manage',  'operation')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_manage')." ADD `operation` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_manage')) {
	if(!pdo_fieldexists('xiaof_toupiao_manage',  'created_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_manage')." ADD `created_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_pic')) {
	if(!pdo_fieldexists('xiaof_toupiao_pic',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_pic')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_pic')) {
	if(!pdo_fieldexists('xiaof_toupiao_pic',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_pic')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_pic')) {
	if(!pdo_fieldexists('xiaof_toupiao_pic',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_pic')." ADD `pid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_pic')) {
	if(!pdo_fieldexists('xiaof_toupiao_pic',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_pic')." ADD `thumb` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_pic')) {
	if(!pdo_fieldexists('xiaof_toupiao_pic',  'url')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_pic')." ADD `url` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_pic')) {
	if(!pdo_fieldexists('xiaof_toupiao_pic',  'is_show')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_pic')." ADD `is_show` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_pic')) {
	if(!pdo_fieldexists('xiaof_toupiao_pic',  'created_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_pic')." ADD `created_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_rule')) {
	if(!pdo_fieldexists('xiaof_toupiao_rule',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_rule')." ADD `rid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_rule')) {
	if(!pdo_fieldexists('xiaof_toupiao_rule',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_rule')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_rule')) {
	if(!pdo_fieldexists('xiaof_toupiao_rule',  'action')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_rule')." ADD `action` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_rule')) {
	if(!pdo_fieldexists('xiaof_toupiao_rule',  'keyword')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_rule')." ADD `keyword` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_rule')) {
	if(!pdo_fieldexists('xiaof_toupiao_rule',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_rule')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_safe')) {
	if(!pdo_fieldexists('xiaof_toupiao_safe',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_safe')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_safe')) {
	if(!pdo_fieldexists('xiaof_toupiao_safe',  'sid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_safe')." ADD `sid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_safe')) {
	if(!pdo_fieldexists('xiaof_toupiao_safe',  'ip')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_safe')." ADD `ip` bigint(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_safe')) {
	if(!pdo_fieldexists('xiaof_toupiao_safe',  'created_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_safe')." ADD `created_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'tit')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `tit` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'data')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `data` text NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'groups')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `groups` text NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'unfollow')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `unfollow` tinyint(255) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'detail')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `detail` text NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'bottom')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `bottom` text NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'click')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `click` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_setting')) {
	if(!pdo_fieldexists('xiaof_toupiao_setting',  'created_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_setting')." ADD `created_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_smslog')) {
	if(!pdo_fieldexists('xiaof_toupiao_smslog',  'id')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_smslog')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_smslog')) {
	if(!pdo_fieldexists('xiaof_toupiao_smslog',  'phone')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_smslog')." ADD `phone` char(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_smslog')) {
	if(!pdo_fieldexists('xiaof_toupiao_smslog',  'ip')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_smslog')." ADD `ip` bigint(20) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_smslog')) {
	if(!pdo_fieldexists('xiaof_toupiao_smslog',  'created_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_smslog')." ADD `created_at` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('xiaof_toupiao_smslog')) {
	if(!pdo_fieldexists('xiaof_toupiao_smslog',  'unique_at')) {
		pdo_query("ALTER TABLE ".tablename('xiaof_toupiao_smslog')." ADD `unique_at` int(8) NOT NULL;");
	}	
}
