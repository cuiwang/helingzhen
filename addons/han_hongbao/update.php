<?php

//decode by QQ:270656184 http://www.yunlu99.com/
pdo_query("CREATE TABLE IF NOT EXISTS `ims_han_hongbao_checklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `arc_name` varchar(50) NOT NULL,
  `nick_name` text NOT NULL,
  `uid` int(10) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '0',
  `get_money` int(11) NOT NULL DEFAULT '0',
  `get_time` int(10) unsigned NOT NULL DEFAULT '0',
  `get_status` varchar(50) NOT NULL DEFAULT '0',
  `msg` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `arc_name` varchar(50) NOT NULL,
  `nick_name` text NOT NULL,
  `uid` int(10) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '0',
  `get_money` int(11) NOT NULL DEFAULT '0',
  `get_time` int(10) unsigned NOT NULL DEFAULT '0',
  `get_status` varchar(50) NOT NULL DEFAULT '0',
  `msg` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34679 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_klfans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `openid` varchar(35) NOT NULL,
  `redPackCount` int(10) NOT NULL,
  `lastTime` int(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `remark` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_klrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `openid` varchar(35) NOT NULL,
  `nick_name` varchar(50) NOT NULL,
  `moneyCount` float NOT NULL,
  `time` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `remark` varchar(50) DEFAULT NULL,
  `imgurl` text,
  `content` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_klset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `is_open` tinyint(1) NOT NULL DEFAULT '0',
  `up_img` tinyint(1) NOT NULL,
  `day_total` int(10) NOT NULL,
  `total` int(10) NOT NULL,
  `num_grade` text NOT NULL,
  `cool_time` int(2) unsigned NOT NULL DEFAULT '0',
  `hours_time` text NOT NULL,
  `remark` text,
  `refail` text,
  `bgimg` text,
  `prompt` varchar(50) DEFAULT NULL,
  `rule` text,
  `rid` int(10) NOT NULL,
  `kid` int(10) NOT NULL,
  `oauth` tinyint(2) NOT NULL,
  `redirect` text,
  `gender` varchar(100) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` text NOT NULL,
  `area` varchar(255) NOT NULL,
  `need_rgst` tinyint(2) NOT NULL,
  `mem_group` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `bgimge` varchar(255) NOT NULL,
  `shareimg` varchar(255) NOT NULL,
  `check` text,
  `act_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_kouling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `money_min` varchar(50) NOT NULL,
  `money_max` varchar(50) NOT NULL,
  `kouling` varchar(50) NOT NULL,
  `createtime` int(11) NOT NULL,
  `state` varchar(50) DEFAULT '0',
  `days` int(10) NOT NULL DEFAULT '0',
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_putong` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `arc_name` varchar(50) NOT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '0',
  `is_check` tinyint(1) DEFAULT NULL,
  `starttime` int(10) NOT NULL,
  `endtime` int(10) NOT NULL,
  `moneymin` int(11) NOT NULL DEFAULT '0',
  `moneymax` int(11) NOT NULL DEFAULT '0',
  `no_total` int(2) DEFAULT NULL,
  `num_grade` text,
  `cool_time` int(2) unsigned NOT NULL DEFAULT '0',
  `succeed_url` text,
  `failed_url` text,
  `hours_time` text NOT NULL,
  `createtime` int(10) NOT NULL,
  `rid` int(10) NOT NULL,
  `kid` int(10) NOT NULL,
  `oauth` tinyint(2) NOT NULL,
  `redirect` text,
  `gender` varchar(100) NOT NULL,
  `province` varchar(255) NOT NULL,
  `city` text NOT NULL,
  `area` varchar(255) NOT NULL,
  `need_rgst` tinyint(2) NOT NULL,
  `mem_group` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `bgimg` varchar(255) NOT NULL,
  `shareimg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_recordlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `arc_name` varchar(50) NOT NULL,
  `nick_name` text NOT NULL,
  `uid` int(10) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '0',
  `get_money` int(11) NOT NULL DEFAULT '0',
  `get_time` int(10) unsigned NOT NULL DEFAULT '0',
  `get_status` varchar(50) NOT NULL DEFAULT '0',
  `msg` text,
  `remark` text,
  `imgurl` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2946 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_sdhb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `money` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_sdhb_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `arc_name` varchar(50) NOT NULL,
  `nick_name` text NOT NULL,
  `uid` int(10) NOT NULL,
  `openid` varchar(50) NOT NULL DEFAULT '0',
  `get_money` int(11) NOT NULL DEFAULT '0',
  `get_time` int(10) unsigned NOT NULL DEFAULT '0',
  `get_status` varchar(50) NOT NULL DEFAULT '0',
  `msg` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_time` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `time` varchar(50) NOT NULL COMMENT '时间段',
  `soft` int(4) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_yedh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL DEFAULT '0',
  `on_off` tinyint(2) NOT NULL,
  `title` varchar(100) NOT NULL,
  `rid` int(10) NOT NULL,
  `kid` int(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `bgimg` varchar(255) DEFAULT NULL,
  `shareimg` varchar(255) DEFAULT NULL,
  `type` tinyint(2) NOT NULL,
  `intl` int(10) NOT NULL,
  `check` tinyint(2) NOT NULL,
  `oauth` tinyint(2) NOT NULL DEFAULT '0',
  `redirect` text,
  `gender` varchar(100) NOT NULL DEFAULT '0',
  `province` varchar(255) DEFAULT NULL,
  `city` text,
  `area` varchar(255) DEFAULT '1',
  `is_rlname` tinyint(2) NOT NULL,
  `need_rgst` tinyint(2) NOT NULL DEFAULT '2',
  `mem_group` varchar(255) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
");
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `weid` int(11) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'arc_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `arc_name` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'nick_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `nick_name` text NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'uid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `uid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'openid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `openid` varchar(50) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'get_money')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `get_money` int(11) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'get_time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `get_time` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'get_status')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `get_status` varchar(50) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_checklist')) {
	if (!pdo_fieldexists('han_hongbao_checklist', 'msg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_checklist') . ' ADD `msg` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `weid` int(11) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'arc_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `arc_name` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'nick_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `nick_name` text NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'uid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `uid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'openid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `openid` varchar(50) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'get_money')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `get_money` int(11) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'get_time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `get_time` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'get_status')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `get_status` varchar(50) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_history')) {
	if (!pdo_fieldexists('han_hongbao_history', 'msg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_history') . ' ADD `msg` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klfans')) {
	if (!pdo_fieldexists('han_hongbao_klfans', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klfans') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klfans')) {
	if (!pdo_fieldexists('han_hongbao_klfans', 'uniacid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klfans') . ' ADD `uniacid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klfans')) {
	if (!pdo_fieldexists('han_hongbao_klfans', 'openid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klfans') . ' ADD `openid` varchar(35) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klfans')) {
	if (!pdo_fieldexists('han_hongbao_klfans', 'redPackCount')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klfans') . ' ADD `redPackCount` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klfans')) {
	if (!pdo_fieldexists('han_hongbao_klfans', 'lastTime')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klfans') . ' ADD `lastTime` int(50)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klfans')) {
	if (!pdo_fieldexists('han_hongbao_klfans', 'type')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klfans') . ' ADD `type` varchar(50)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klfans')) {
	if (!pdo_fieldexists('han_hongbao_klfans', 'remark')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klfans') . ' ADD `remark` varchar(50)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'uniacid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `uniacid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'openid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `openid` varchar(35) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'nick_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `nick_name` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'moneyCount')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `moneyCount` float NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `time` varchar(50)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'type')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `type` varchar(50)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'state')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `state` varchar(50)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'remark')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `remark` varchar(50)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'imgurl')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `imgurl` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klrecord')) {
	if (!pdo_fieldexists('han_hongbao_klrecord', 'content')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klrecord') . ' ADD `content` varchar(100) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `weid` int(11) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'is_open')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `is_open` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'up_img')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `up_img` tinyint(1) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'day_total')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `day_total` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'total')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `total` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'num_grade')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `num_grade` text NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'cool_time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `cool_time` int(2) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'hours_time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `hours_time` text NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'remark')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `remark` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'refail')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `refail` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'bgimg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `bgimg` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'prompt')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `prompt` varchar(50)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'rule')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `rule` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'rid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `rid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'kid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `kid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'oauth')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `oauth` tinyint(2) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'redirect')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `redirect` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'gender')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `gender` varchar(100) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'province')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `province` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'city')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `city` text NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'area')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `area` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'need_rgst')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `need_rgst` tinyint(2) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'mem_group')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `mem_group` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'title')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `title` varchar(100) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'description')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `description` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'bgimge')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `bgimge` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'shareimg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `shareimg` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'check')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `check` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_klset')) {
	if (!pdo_fieldexists('han_hongbao_klset', 'act_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_klset') . ' ADD `act_name` varchar(100) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'uniacid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'money_min')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `money_min` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'money_max')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `money_max` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'kouling')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `kouling` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'createtime')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `createtime` int(11) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'state')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `state` varchar(50)   DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'days')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `days` int(10) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'count')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `count` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_kouling')) {
	if (!pdo_fieldexists('han_hongbao_kouling', 'remark')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_kouling') . ' ADD `remark` varchar(50)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `weid` int(11) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'arc_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `arc_name` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'is_show')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `is_show` tinyint(1) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'is_check')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `is_check` tinyint(1)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'starttime')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `starttime` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'endtime')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `endtime` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'moneymin')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `moneymin` int(11) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'moneymax')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `moneymax` int(11) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'no_total')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `no_total` int(2)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'num_grade')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `num_grade` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'cool_time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `cool_time` int(2) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'succeed_url')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `succeed_url` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'failed_url')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `failed_url` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'hours_time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `hours_time` text NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'createtime')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `createtime` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'rid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `rid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'kid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `kid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'oauth')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `oauth` tinyint(2) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'redirect')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `redirect` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'gender')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `gender` varchar(100) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'province')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `province` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'city')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `city` text NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'area')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `area` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'need_rgst')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `need_rgst` tinyint(2) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'mem_group')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `mem_group` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'title')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `title` varchar(100) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'description')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `description` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'bgimg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `bgimg` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_putong')) {
	if (!pdo_fieldexists('han_hongbao_putong', 'shareimg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_putong') . ' ADD `shareimg` varchar(255) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `weid` int(11) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'type')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `type` varchar(32) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'arc_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `arc_name` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'nick_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `nick_name` text NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'uid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `uid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'openid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `openid` varchar(50) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'get_money')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `get_money` int(11) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'get_time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `get_time` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'get_status')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `get_status` varchar(50) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'msg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `msg` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'remark')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `remark` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_recordlist')) {
	if (!pdo_fieldexists('han_hongbao_recordlist', 'imgurl')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_recordlist') . ' ADD `imgurl` varchar(100)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb')) {
	if (!pdo_fieldexists('han_hongbao_sdhb', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb')) {
	if (!pdo_fieldexists('han_hongbao_sdhb', 'uniacid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb') . ' ADD `uniacid` int(11) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb')) {
	if (!pdo_fieldexists('han_hongbao_sdhb', 'nickname')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb') . ' ADD `nickname` varchar(100) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb')) {
	if (!pdo_fieldexists('han_hongbao_sdhb', 'openid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb') . ' ADD `openid` varchar(100) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb')) {
	if (!pdo_fieldexists('han_hongbao_sdhb', 'money')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb') . ' ADD `money` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `weid` int(11) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'type')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `type` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'arc_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `arc_name` varchar(50) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'nick_name')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `nick_name` text NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'uid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `uid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'openid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `openid` varchar(50) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'get_money')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `get_money` int(11) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'get_time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `get_time` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'get_status')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `get_status` varchar(50) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_sdhb_history')) {
	if (!pdo_fieldexists('han_hongbao_sdhb_history', 'msg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_sdhb_history') . ' ADD `msg` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_time')) {
	if (!pdo_fieldexists('han_hongbao_time', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_time') . ' ADD `id` int(10) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_time')) {
	if (!pdo_fieldexists('han_hongbao_time', 'time')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_time') . ' ADD `time` varchar(50) NOT NULL   COMMENT \'时间段\';');
	}
}
if (pdo_tableexists('han_hongbao_time')) {
	if (!pdo_fieldexists('han_hongbao_time', 'soft')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_time') . ' ADD `soft` int(4) NOT NULL   COMMENT \'排序\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'id')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `id` int(11) NOT NULL auto_increment  COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'weid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `weid` int(11) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'on_off')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `on_off` tinyint(2) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'title')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `title` varchar(100) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'rid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `rid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'kid')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `kid` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'description')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `description` varchar(255)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'bgimg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `bgimg` varchar(255)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'shareimg')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `shareimg` varchar(255)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'type')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `type` tinyint(2) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'intl')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `intl` int(10) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'check')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `check` tinyint(2) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'oauth')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `oauth` tinyint(2) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'redirect')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `redirect` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'gender')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `gender` varchar(100) NOT NULL  DEFAULT 0 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'province')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `province` varchar(255)    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'city')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `city` text    COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'area')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `area` varchar(255)   DEFAULT 1 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'is_rlname')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `is_rlname` tinyint(2) NOT NULL   COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'need_rgst')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `need_rgst` tinyint(2) NOT NULL  DEFAULT 2 COMMENT \'\';');
	}
}
if (pdo_tableexists('han_hongbao_yedh')) {
	if (!pdo_fieldexists('han_hongbao_yedh', 'mem_group')) {
		pdo_query('ALTER TABLE ' . tablename('han_hongbao_yedh') . ' ADD `mem_group` varchar(255) NOT NULL  DEFAULT 1 COMMENT \'\';');
	}
}