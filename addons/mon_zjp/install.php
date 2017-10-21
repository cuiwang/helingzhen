<?php


/**
 *
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_zjp') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`rid` int(10) NOT NULL,
 `weid` int(11) NOT NULL  ,
 `title` varchar(200) NOT NULL,
 `starttime` int(10),
 `endtime` int(10),
 `index_pic` varchar(200),
 `prize_intro` varchar(1000),
 `rule_intro` varchar(1000),
 `share_award_enable` int(1) default 0,
  `share_award_count` int(3) default 0,
  `share_award_time` int(3) default 0,
  `u_award_count`int(3) default 1,
 `play_count` int(3) default 0,
  `follow_url` varchar(200),
  `copyright` varchar(100) NOT NULL,
  `new_title` varchar(200),
`new_icon` varchar(200),
`new_content` varchar(200),
 `share_title` varchar(200),
`share_icon` varchar(200),
`share_content` varchar(200),
`status` int(3) default 0,
`banner_ad_pic` varchar(200),
`dialog_tips` varchar(1000),
`success_award_tips` varchar(1000),
`fail_award_tips` varchar(1000),
`lock_tip` varchar(100),
`day_play_count` int(3),
`prize_sharebtn_name` varchar(50),

`luck_sharebtn_name` varchar(50),

  `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


/**
 *
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_zjp_prize') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`zid` int(10) NOT NULL,
 `pname` varchar(50) NOT NULL,
 `psummary` varchar(50) NOT NULL,
  `picon` varchar(200) NOT NULL,
  `unit` varchar(200) NOT NULL,
  `count` int(10) default 1,
  `percent` int(3) default 0,
  `sort` int(3) default 0,
  `left_count` int(10) default 1,
 `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_zjp_user') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`zid` int(10) NOT NULL,
 `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
 `tel` varchar(20),
 `share_award_count` int(10) default 0,
 `play_count` int(10) default 0,
 `award_play_count` int(10) default 0,

 `share_count` int(10) NOT NULL,
 `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

/**
 * 参加用户_记录
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_zjp_record') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`zid` int(10) NOT NULL,
 `uid` varchar(200) NOT NULL,
 `openid` varchar(200) NOT NULL,
  `pid` int(10),
  `award_status` int(1) default 0,
  `msg` varchar(500),
  `stauts` int(1) default 0,
 `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);







