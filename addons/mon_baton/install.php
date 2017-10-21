<?php


/**
 *接力活动
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_baton') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`rid` int(10) NOT NULL,
 `weid` int(11) NOT NULL  ,
 `title` varchar(200) NOT NULL,
 `starttime` int(10),
 `endtime` int(10),
  `follow_url` varchar(200),
  `copyright` varchar(100) NOT NULL,
  `copyright_url` varchar(500),
  `index_banner` varchar(500),
  `my_banner` varchar(500),
  `ry_banner` varchar(500),
  `default_logo` varchar(500),
  `default_name` varchar(20),
  `logo` varchar(500),
  `end_dialog_tip` varchar(500),
  `follow_dialog_tip` varchar(500),
  `hd_intro` varchar(2000),
  `rule_intro` varchar(2000),
  `prize_intro` varchar(2000),
  `add_intro` varchar(2000),
  `join_fans_enable` int(1),
  `sucess_banner` varchar(500),
  `new_title` varchar(200),
`new_icon` varchar(200),
`new_content` varchar(200),
 `share_title` varchar(200),
`share_icon` varchar(200),
`share_content` varchar(200),
 `createtime` int(10),
  `updatetime` int(10),
  `speak` varchar(1000),
  `follow_btn` varchar(50),
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


/**
 * 接力用户
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_baton_user') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`bid` int(10) NOT NULL,
`openid` varchar(200) NOT NULL,
`nickname` varchar(100) NOT NULL,
`headimgurl` varchar(200) NOT NULL,
`puid` int(10),
`baton_num` int(10) default 0 ,
`baton` int(10) default 0 ,
`uname` varchar(20),
`tel` varchar(20),
`speak` varchar(1000),
`createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

/**
 * 设置
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_baton_setting') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
`weid` int(10) NOT NULL,
`appid` varchar(200) ,
`appsecret` varchar(200),
 `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);











