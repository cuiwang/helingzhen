<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  `oauth_openid` varchar(50) DEFAULT '',
  `avatar` varchar(200) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `realname` varchar(50) DEFAULT '',
  `mobile` varchar(50) DEFAULT '',
  `qq` varchar(50) DEFAULT '',
  `email` varchar(50) DEFAULT '',
  `address` varchar(50) DEFAULT '',
  `status` int(1) DEFAULT '0',
  `wordcount` int(11) DEFAULT '0',
  `sharecount` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0' COMMENT '剩余游戏次数',
  `createtime` int(11) DEFAULT '0',
  `award` int(11) DEFAULT '0',
  `astatus` tinyint(1) DEFAULT '0',
  `choice` tinyint(1) DEFAULT '0',
  `lasttime` int(11) DEFAULT '0' COMMENT '最后增长时间',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`,`rid`),
  KEY `award` (`award`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `prizepro` varchar(200) DEFAULT '',
  `prizetotal` int(11) DEFAULT '0',
  `prizetype` int(1) DEFAULT '0',
  `prizename` varchar(50) DEFAULT '' COMMENT '奖品 q名称，当奖品为其他模块东西时，这里为ID',
  `prizepic` varchar(200) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_record` (
  `rid` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `word` varchar(11) DEFAULT '',
  `createtime` varchar(20) DEFAULT NULL,
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_rule` (
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `thumb` varchar(250) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `hword` varchar(20) DEFAULT '',
  `title2` varchar(50) DEFAULT '',
  `thumb2` varchar(250) DEFAULT '',
  `description2` varchar(255) DEFAULT '',
  `stitle` varchar(50) DEFAULT '',
  `sthumb` varchar(250) DEFAULT '',
  `sdesc` varchar(255) DEFAULT '',
  `atitle` varchar(50) DEFAULT '',
  `athumb` varchar(250) DEFAULT '',
  `adesc` varchar(255) DEFAULT '',
  `words` text,
  `sliders` text,
  `rank` int(11) DEFAULT '0',
  `awardNum` int(11) DEFAULT '0',
  `isinfo` int(1) DEFAULT '0',
  `isinfo2` int(1) DEFAULT '0',
  `awardtips` varchar(200) DEFAULT '',
  `isrealname` int(1) DEFAULT '0',
  `ismobile` int(1) DEFAULT '0',
  `isqq` int(1) DEFAULT '0',
  `isemail` int(1) DEFAULT '0',
  `isaddress` int(1) DEFAULT '0',
  `isfans` int(1) DEFAULT '0',
  `lastshow` int(1) DEFAULT '0',
  `describelimit` tinyint(1) DEFAULT '0',
  `describelimit2` tinyint(1) DEFAULT '0',
  `endtime` int(10) DEFAULT '0',
  `starttime` int(10) DEFAULT '0',
  `content` text,
  `firstnum` int(11) DEFAULT '0',
  `slideH` int(11) DEFAULT '0',
  `sharenum1` int(11) DEFAULT '0',
  `sharenum2` int(11) DEFAULT '0',
  `daynum` int(11) DEFAULT '0',
  `password` varchar(20) DEFAULT '',
  `citys` text,
  `outtips` varchar(200) DEFAULT '',
  `outurl` varchar(250) DEFAULT '',
  `advImg` varchar(200) DEFAULT '',
  `selword` varchar(200) DEFAULT '',
  `bgsong` varchar(255) DEFAULT '',
  `colsong` varchar(255) DEFAULT '',
  `copyright` varchar(255) DEFAULT '',
  `rmode` tinyint(1) DEFAULT '0',
  `advTime` int(10) DEFAULT '0',
  `prizetime` int(10) DEFAULT '0',
  `checked` tinyint(1) DEFAULT '0',
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_share` (
  `openid` varchar(50) DEFAULT '',
  `avatar` varchar(200) DEFAULT '',
  `nickname` varchar(50) DEFAULT '',
  `pid` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  KEY `openid` (`openid`,`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_netcollect_slog` (
  `rid` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `openid` varchar(50) DEFAULT '',
  KEY `openid` (`openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
");
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'id')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `id` int(11) NOT NULL auto_increment  COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `weid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `rid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `openid` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'oauth_openid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `oauth_openid` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `avatar` varchar(200)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `nickname` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'realname')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `realname` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `mobile` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'qq')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `qq` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'email')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `email` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'address')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `address` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'status')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `status` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'wordcount')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `wordcount` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'sharecount')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `sharecount` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'times')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `times` int(11)   DEFAULT 0 COMMENT '剩余游戏次数';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `createtime` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'award')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `award` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'astatus')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `astatus` tinyint(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'choice')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `choice` tinyint(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_player')) {
	if(!pdo_fieldexists('junsion_netcollect_player',  'lasttime')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_player')." ADD `lasttime` int(11)   DEFAULT 0 COMMENT '最后增长时间';");
	}	
}
if(pdo_tableexists('junsion_netcollect_prize')) {
	if(!pdo_fieldexists('junsion_netcollect_prize',  'id')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_prize')." ADD `id` int(11) NOT NULL auto_increment  COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_prize')) {
	if(!pdo_fieldexists('junsion_netcollect_prize',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_prize')." ADD `rid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_prize')) {
	if(!pdo_fieldexists('junsion_netcollect_prize',  'prizepro')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_prize')." ADD `prizepro` varchar(200)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_prize')) {
	if(!pdo_fieldexists('junsion_netcollect_prize',  'prizetotal')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_prize')." ADD `prizetotal` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_prize')) {
	if(!pdo_fieldexists('junsion_netcollect_prize',  'prizetype')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_prize')." ADD `prizetype` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_prize')) {
	if(!pdo_fieldexists('junsion_netcollect_prize',  'prizename')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_prize')." ADD `prizename` varchar(50)    COMMENT '奖品 q名称，当奖品为其他模块东西时，这里为ID';");
	}	
}
if(pdo_tableexists('junsion_netcollect_prize')) {
	if(!pdo_fieldexists('junsion_netcollect_prize',  'prizepic')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_prize')." ADD `prizepic` varchar(200)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_record')) {
	if(!pdo_fieldexists('junsion_netcollect_record',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_record')." ADD `rid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_record')) {
	if(!pdo_fieldexists('junsion_netcollect_record',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_record')." ADD `pid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_record')) {
	if(!pdo_fieldexists('junsion_netcollect_record',  'word')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_record')." ADD `word` varchar(11)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_record')) {
	if(!pdo_fieldexists('junsion_netcollect_record',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_record')." ADD `createtime` varchar(20)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `weid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `rid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'title')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `title` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `thumb` varchar(250)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'description')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `description` varchar(255)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'hword')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `hword` varchar(20)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'title2')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `title2` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'thumb2')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `thumb2` varchar(250)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'description2')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `description2` varchar(255)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'stitle')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `stitle` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'sthumb')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `sthumb` varchar(250)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'sdesc')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `sdesc` varchar(255)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'atitle')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `atitle` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'athumb')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `athumb` varchar(250)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'adesc')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `adesc` varchar(255)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'words')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `words` text    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'sliders')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `sliders` text    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'rank')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `rank` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'awardNum')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `awardNum` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'isinfo')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `isinfo` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'isinfo2')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `isinfo2` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'awardtips')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `awardtips` varchar(200)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'isrealname')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `isrealname` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'ismobile')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `ismobile` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'isqq')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `isqq` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'isemail')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `isemail` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'isaddress')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `isaddress` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'isfans')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `isfans` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'lastshow')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `lastshow` int(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'describelimit')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `describelimit` tinyint(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'describelimit2')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `describelimit2` tinyint(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'endtime')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `endtime` int(10)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'starttime')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `starttime` int(10)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'content')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `content` text    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'firstnum')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `firstnum` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'slideH')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `slideH` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'sharenum1')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `sharenum1` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'sharenum2')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `sharenum2` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'daynum')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `daynum` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'password')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `password` varchar(20)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'citys')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `citys` text    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'outtips')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `outtips` varchar(200)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'outurl')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `outurl` varchar(250)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'advImg')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `advImg` varchar(200)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'selword')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `selword` varchar(200)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'bgsong')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `bgsong` varchar(255)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'colsong')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `colsong` varchar(255)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'copyright')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `copyright` varchar(255)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'rmode')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `rmode` tinyint(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'advTime')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `advTime` int(10)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'prizetime')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `prizetime` int(10)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_rule')) {
	if(!pdo_fieldexists('junsion_netcollect_rule',  'checked')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_rule')." ADD `checked` tinyint(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_share')) {
	if(!pdo_fieldexists('junsion_netcollect_share',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `openid` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_share')) {
	if(!pdo_fieldexists('junsion_netcollect_share',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `avatar` varchar(200)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_share')) {
	if(!pdo_fieldexists('junsion_netcollect_share',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `nickname` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_share')) {
	if(!pdo_fieldexists('junsion_netcollect_share',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `pid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_share')) {
	if(!pdo_fieldexists('junsion_netcollect_share',  'times')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `times` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_share')) {
	if(!pdo_fieldexists('junsion_netcollect_share',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `rid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_share')) {
	if(!pdo_fieldexists('junsion_netcollect_share',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_share')." ADD `createtime` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_slog')) {
	if(!pdo_fieldexists('junsion_netcollect_slog',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_slog')." ADD `rid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_slog')) {
	if(!pdo_fieldexists('junsion_netcollect_slog',  'pid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_slog')." ADD `pid` int(11)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('junsion_netcollect_slog')) {
	if(!pdo_fieldexists('junsion_netcollect_slog',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('junsion_netcollect_slog')." ADD `openid` varchar(50)    COMMENT '';");
	}	
}
