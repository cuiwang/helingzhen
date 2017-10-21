<?php

$sql =<<<EOF
CREATE TABLE IF NOT EXISTS `ims_han_hongbao_checklist` (
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
EOF;
pdo_run($sql);
