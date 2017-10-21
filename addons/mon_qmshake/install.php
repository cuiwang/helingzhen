<?php

/**
 *摇一摇活动
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_qmshake') . " (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `shake_follow_enable` int(1) DEFAULT NULL,
  `copyright` varchar(50) DEFAULT NULL,
  `randking_count` int(10) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `rule` varchar(2000) DEFAULT NULL,
  `top_banner` varchar(500) DEFAULT NULL,
  `top_banner_title` varchar(100) DEFAULT NULL,
  `top_banner_show` int(1) DEFAULT '0',
  `top_banner_url` varchar(500) DEFAULT NULL,
  `follow_dlg_tip` varchar(500) DEFAULT NULL,
  `follow_btn_name` varchar(20) DEFAULT NULL,
  `shake_day_limit` int(3) DEFAULT '0',
  `prize_limit` int(3) DEFAULT '0',
  `total_limit` int(3) DEFAULT '0',
  `dpassword` varchar(20) DEFAULT NULL,
  `title_bg` varchar(300) DEFAULT NULL,
  `shake_bg` varchar(300) DEFAULT NULL,
  `index_bg` varchar(300) DEFAULT NULL,
  `share_bg` varchar(300) DEFAULT NULL,
  `share_enable` int(1) DEFAULT '0',
  `share_times` int(3) DEFAULT '0',
  `share_award_count` int(3) DEFAULT '0',
  `share_url` varchar(1000) DEFAULT NULL,
  `unstarttip` text,
  `tmpId` varchar(2000) DEFAULT NULL,
  `tmpenable` int(1) DEFAULT NULL,
  `udefine` varchar(200) DEFAULT NULL,
  `lj_tip` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);



/**
 * 摇一摇奖品
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_qmshake_prize') . " (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) DEFAULT NULL,
  `pname` varchar(50) DEFAULT NULL,
  `p_summary` varchar(500) DEFAULT NULL,
  `pimg` varchar(250) DEFAULT NULL,
  `p_url` varchar(250) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `pcount` int(10) DEFAULT NULL,
  `left_count` int(10) DEFAULT NULL,
  `pb` int(10) DEFAULT '0',
  `display_order` int(3) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `tgs` varchar(250) DEFAULT NULL,
  `tgs_url` varchar(1000) DEFAULT NULL,
  `virtual_count` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);




/**
 * 摇一摇记录
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_qmshake_record') . " (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL,
  `pid` int(10) DEFAULT NULL,
  `pname` varchar(200) DEFAULT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `status` int(1) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `djtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


/**
 * 摇一摇share
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_qmshake_share') . " (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) NOT NULL,
  `uid` int(10) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL,
  `award_count` int(10) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);

/**
 * 摇一摇用户
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_qmshake_user') . " (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `uname` varchar(200) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `udefine` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);














