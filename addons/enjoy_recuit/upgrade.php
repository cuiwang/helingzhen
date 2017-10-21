<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_basic` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`openid` varchar(100),
`uname` varchar(20),
`sex` varchar(10),
`age` varchar(10),
`ed` varchar(10),
`mobile` varchar(20),
`email` varchar(100),
`avatar` longtext,
`present` varchar(200),
`italy` int(2) DEFAULT '0',
`createtime` varchar(30),
`param_1` varchar(50),
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_card` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) NOT NULL,
`openid` varchar(100) NOT NULL,
`cname` varchar(50) NOT NULL,
`param1` varchar(50),
`param2` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_culture` (
`id` int(5) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(50),
`cname` varchar(200),
`logo` varchar(200),
`email` varchar(200),
`mobile` varchar(50),
`place` varchar(200),
`intro` longtext,
`cact` longtext,
`culture` longtext,
`quest` longtext,
`share_title` varchar(500),
`share_desc` varchar(500),
`share_icon` varchar(500),
`share_credit` int(50) DEFAULT '0',
`createtime` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_deliver` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`openid` varchar(100) NOT NULL,
`pid` int(10) NOT NULL,
`createtime` int(30),
`param_1` varchar(50),
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_exper` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`openid` varchar(100) NOT NULL,
`company` varchar(100),
`position` varchar(100),
`salary` int(10),
`stime` varchar(50),
`etime` varchar(50),
`descript` varchar(1000),
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_fans` (
`uid` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`openid` varchar(40) NOT NULL DEFAULT '',
`proxy` varchar(40) NOT NULL DEFAULT '',
`unionid` varchar(40) NOT NULL DEFAULT '',
`nickname` varchar(20) NOT NULL DEFAULT '',
`gender` varchar(2) DEFAULT '',
`state` varchar(20) NOT NULL DEFAULT '',
`city` varchar(20) NOT NULL DEFAULT '',
`country` varchar(20) NOT NULL DEFAULT '',
`avatar` varchar(500) NOT NULL DEFAULT '',
`status` tinyint(4) NOT NULL DEFAULT '0',
PRIMARY KEY (`uid`),
KEY `uniacid` (`uniacid`),
KEY `openid` (`openid`),
KEY `proxy` (`proxy`),
KEY `nickname` (`nickname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_info` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`openid` varchar(100) NOT NULL,
`birth` varchar(50),
`register` varchar(200),
`address` varchar(200),
`marriage` varchar(10),
`weight` varchar(10),
`height` varchar(10),
`school` varchar(50),
`createtime` varchar(50),
`param_1` varchar(50),
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_position` (
`id` int(8) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`pname` varchar(50),
`hot` int(10) DEFAULT '0',
`sex` varchar(5),
`ed` varchar(10),
`height` int(5),
`weight` int(5),
`type` varchar(50),
`key` varchar(50),
`num` int(10),
`place` varchar(50),
`way` varchar(10),
`descript` varchar(5000),
`competence` varchar(5000),
`views` varchar(10) DEFAULT '0',
`deliveries` varchar(10) DEFAULT '0',
`stime` varchar(50),
`etime` varchar(50),
`play` int(2) DEFAULT '0',
`param_2` varchar(50),
`param_3` varchar(50),
`param_4` varchar(50),
`param_5` varchar(50),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_position_range` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`pid` int(10) NOT NULL,
`maxage` int(10),
`minage` int(10),
`maxsalary` int(10),
`minsalary` int(10),
`maxexper` int(10),
`minexper` int(10),
`param_1` varchar(20),
`param_2` varchar(20),
`param_3` varchar(20),
`param_4` varchar(20),
`param_5` varchar(20),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_enjoy_recuit_view` (
`id` int(100) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10),
`pid` int(10),
`openid` varchar(100),
`time` varchar(100),
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `openid` varchar(100);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'uname')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `uname` varchar(20);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'sex')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `sex` varchar(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'age')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `age` varchar(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'ed')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `ed` varchar(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `mobile` varchar(20);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'email')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `email` varchar(100);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `avatar` longtext;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'present')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `present` varchar(200);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'italy')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `italy` int(2) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `createtime` varchar(30);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'param_1')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `param_1` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'param_2')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `param_2` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'param_3')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `param_3` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'param_4')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `param_4` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_basic')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_basic',  'param_5')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_basic')." ADD `param_5` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_card')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_card',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_card')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_card')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_card',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_card')." ADD `uniacid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_card')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_card',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_card')." ADD `openid` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_card')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_card',  'cname')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_card')." ADD `cname` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_card')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_card',  'param1')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_card')." ADD `param1` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_card')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_card',  'param2')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_card')." ADD `param2` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `id` int(5) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `uniacid` int(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'cname')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `cname` varchar(200);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'logo')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `logo` varchar(200);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'email')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `email` varchar(200);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `mobile` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'place')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `place` varchar(200);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'intro')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `intro` longtext;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'cact')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `cact` longtext;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'culture')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `culture` longtext;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'quest')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `quest` longtext;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'share_title')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `share_title` varchar(500);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'share_desc')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `share_desc` varchar(500);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'share_icon')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `share_icon` varchar(500);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'share_credit')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `share_credit` int(50) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_culture')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_culture',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_culture')." ADD `createtime` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `openid` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `pid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `createtime` int(30);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'param_1')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `param_1` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'param_2')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `param_2` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'param_3')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `param_3` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'param_4')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `param_4` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_deliver')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_deliver',  'param_5')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_deliver')." ADD `param_5` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `openid` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'company')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `company` varchar(100);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'position')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `position` varchar(100);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'salary')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `salary` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'stime')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `stime` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'etime')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `etime` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'descript')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `descript` varchar(1000);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'param_2')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `param_2` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'param_3')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `param_3` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'param_4')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `param_4` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_exper')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_exper',  'param_5')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_exper')." ADD `param_5` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'uid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `uid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `uniacid` int(11) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `openid` varchar(40) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'proxy')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `proxy` varchar(40) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'unionid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `unionid` varchar(40) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `nickname` varchar(20) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'gender')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `gender` varchar(2) DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'state')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `state` varchar(20) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'city')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `city` varchar(20) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'country')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `country` varchar(20) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `avatar` varchar(500) NOT NULL DEFAULT '';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_fans')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_fans',  'status')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_fans')." ADD `status` tinyint(4) NOT NULL DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `openid` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'birth')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `birth` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'register')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `register` varchar(200);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'address')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `address` varchar(200);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'marriage')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `marriage` varchar(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'weight')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `weight` varchar(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'height')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `height` varchar(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'school')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `school` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `createtime` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'param_1')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `param_1` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'param_2')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `param_2` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'param_3')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `param_3` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'param_4')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `param_4` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_info')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_info',  'param_5')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_info')." ADD `param_5` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `id` int(8) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'pname')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `pname` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'hot')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `hot` int(10) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'sex')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `sex` varchar(5);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'ed')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `ed` varchar(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'height')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `height` int(5);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'weight')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `weight` int(5);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'type')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `type` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'key')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `key` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'num')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `num` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'place')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `place` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'way')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `way` varchar(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'descript')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `descript` varchar(5000);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'competence')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `competence` varchar(5000);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'views')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `views` varchar(10) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'deliveries')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `deliveries` varchar(10) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'stime')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `stime` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'etime')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `etime` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'play')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `play` int(2) DEFAULT '0';");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'param_2')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `param_2` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'param_3')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `param_3` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'param_4')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `param_4` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position',  'param_5')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position')." ADD `param_5` varchar(50);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `pid` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'maxage')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `maxage` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'minage')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `minage` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'maxsalary')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `maxsalary` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'minsalary')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `minsalary` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'maxexper')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `maxexper` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'minexper')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `minexper` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'param_1')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `param_1` varchar(20);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'param_2')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `param_2` varchar(20);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'param_3')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `param_3` varchar(20);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'param_4')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `param_4` varchar(20);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_position_range')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_position_range',  'param_5')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_position_range')." ADD `param_5` varchar(20);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_view')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_view',  'id')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_view')." ADD `id` int(100) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_view')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_view',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_view')." ADD `uniacid` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_view')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_view',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_view')." ADD `pid` int(10);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_view')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_view',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_view')." ADD `openid` varchar(100);");
	}	
}
if(pdo_tableexists('ims_enjoy_recuit_view')) {
	if(!pdo_fieldexists('ims_enjoy_recuit_view',  'time')) {
		pdo_query("ALTER TABLE ".tablename('ims_enjoy_recuit_view')." ADD `time` varchar(100);");
	}	
}
