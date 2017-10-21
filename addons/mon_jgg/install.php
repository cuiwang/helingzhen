<?php

/**
 * 安装
 */


/**
 * 活动定义
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_jgg') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`rid` int(10) NOT NULL,
 `weid` int(11) NOT NULL  ,
 `title` varchar(200) NOT NULL,
 `starttime` int(10),
 `endtime` int(10),
 `rule` varchar(1000),
 `join_intro` varchar(1000),
 `day_play_count` int(3) default 0,
  `follow_btn` varchar(50) default '点击参加抽奖活动',
   `follow_welbtn` varchar(50) default '欢迎参加微信九宫格',
  `follow_url` varchar(200),
  `copyright` varchar(100) NOT NULL,
     `prize_level_0`varchar(100) default '没有中奖',
   `prize_name_0`varchar(100) NOT NULL,
  `prize_img_0` varchar(200) NOT NULL,
  `prize_p_0` int(3) NOT NULL,
     `prize_level_1`varchar(100) default '一等奖',
  `prize_name_1`varchar(100) NOT NULL,
  `prize_img_1` varchar(200) NOT NULL,
  `prize_p_1` int(3) NOT NULL default 0,
   `prize_num_1` int(10) NOT NULL,
     `prize_level_2`varchar(100) default '二等奖',
   `prize_name_2`varchar(100) NOT NULL,
  `prize_img_2` varchar(200) NOT NULL,
  `prize_p_2` int(3) NOT NULL,
  `prize_num_2` int(10) NOT NULL default 0,
    `prize_level_3`varchar(100) default '三等奖',
  `prize_name_3`varchar(100) NOT NULL,
  `prize_img_3` varchar(200) NOT NULL,
  `prize_p_3` int(3) NOT NULL,
  `prize_num_3` int(10) NOT NULL default 0,
  `prize_level_4`varchar(100) default '四等奖',
  `prize_name_4`varchar(100) NOT NULL,
  `prize_img_4` varchar(200) NOT NULL,
  `prize_p_4` int(3) NOT NULL,
  `prize_num_4` int(10) NOT NULL default 0,
    `prize_level_5`varchar(100) default '五等奖',
  `prize_name_5`varchar(100) NOT NULL,
  `prize_img_5` varchar(200) NOT NULL,
  `prize_p_5` int(3) NOT NULL,
  `prize_num_5` int(10) NOT NULL default 0,
 `prize_level_6`varchar(100) default '六等奖',
   `prize_name_6`varchar(100) NOT NULL,
  `prize_img_6` varchar(200) NOT NULL,
  `prize_p_6` int(3) NOT NULL,
  `prize_num_6` int(10) NOT NULL default 0,
  `prize_level_7`varchar(100) default '七等奖',
   `prize_name_7`varchar(100) NOT NULL,
  `prize_img_7` varchar(200) NOT NULL,
  `prize_p_7` int(3) NOT NULL,
  `prize_num_7` int(10) NOT NULL default 0 ,
  `award_count` int(10) default 0,
  `new_title` varchar(200),
  `new_icon` varchar(200),
  `new_content` varchar(200),
  `share_title` varchar(200),
  `share_icon` varchar(200),
  `share_content` varchar(200),
  `createtime` int(10) DEFAULT 0,
  `day_award_count` int(10) DEFAULT '0',
  `bg` varchar(1000) DEFAULT NULL,
  `bgcolor` varchar(40) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


/**
 * 参加用户
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_jgg_user') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`jid` int(10) NOT NULL,
 `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
 `tel` varchar(20) NOT NULL,
 `uname` varchar(20) NOT NULL,
 `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


/**

 * sn

 */

$sql = "

CREATE TABLE IF NOT EXISTS " . tablename('mon_jgg_sn') . " (

`id` int(10) unsigned  AUTO_INCREMENT,

`jid` int(10) NOT NULL,
`uid` int(10) NOT NULL,

  `sn` varchar(500) NOT NULL,

 `createtime` int(10) DEFAULT 0,

 PRIMARY KEY (`id`)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);

/**
 * 参加用户_记录
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_jgg_user_record') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`jid` int(10) NOT NULL,
 `uid` varchar(200) NOT NULL,
 `openid` varchar(200) NOT NULL,
  `award_name` varchar(200) NOT NULL,
    `award_level` varchar(200) NOT NULL,
    `level` int(3) default 0,
 `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


/**
 * 参加用户中奖记录
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_jgg_user_award') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`jid` int(10) NOT NULL,
 `uid` varchar(200) NOT NULL,
 `openid` varchar(200) NOT NULL,
  `award_name` varchar(200) NOT NULL,
    `award_level` varchar(200) NOT NULL,
  `level` int(3) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `remark` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);






