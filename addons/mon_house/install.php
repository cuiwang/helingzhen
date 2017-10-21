<?php

// 基本设置
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `weid` int(11) default 0 ,
`rid` 	int(11) default 0 ,
`news_title` varchar(255) NOT NULL,
`lpaddress` varchar(255) NOT NULL,
`price` int(10) NOT NULL,
`sltel` varchar(25) NOT NULL,
`zxtel` varchar(25)  NOT NULL,
`news_icon` varchar(255) NOT NULL,
`news_content` varchar(500) NOT NULL,
`title` varchar(100) NOT NULL,		
`kptime` int(10) DEFAULT 0,
`rztime` 	int(10) DEFAULT 0,
`kfs` varchar(100) NOT NULL,
`cover_img` varchar(200) NOT NULL,
`overview_img` varchar(200) NOT NULL,
`intro_img` varchar(200) NOT NULL,
`intro` varchar(1000) ,
`order_title`  varchar(50) NOT NULL ,
`order_remark` varchar(100) NOT NULL,
`share_icon` varchar(200) NOT NULL,
`share_title` varchar(200) NOT NULL,
`share_content` varchar(500) NOT NULL,
`dt_img` varchar(300),
`dt_intro` varchar(2000) ,
`createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`),KEY `indx_weid` (`weid`),KEY `indx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);

/**
 * 项目
 */
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house_item') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `hid` int(11) default 0 ,
`rid` int(11) default 0 ,		
`iname` varchar(255) NOT NULL,
`icontent` varchar(255) NOT NULL,
 `sort` int(3) default 0,   
 PRIMARY KEY (`id`),KEY `indx_hid` (`hid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);


//顾问
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house_agent') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `hid` int(11) default 0 ,

`gname` varchar(255) NOT NULL,
`headimgurl` varchar(255) NOT NULL,
 `tel` varchar(20) NOT NULL,   
 `workyear` int(3) default 0,
  PRIMARY KEY (`id`),KEY `indx_hid` (`hid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);


//报名表
$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house_order') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `hid` int(11) default 0 ,
`uname` varchar(20) NOT NULL,
`createtime` int(10) DEFAULT 0,
`tel` varchar(20) NOT NULL,

 PRIMARY KEY (`id`),KEY `indx_hid` (`hid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);


// 户型类型

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house_type') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `hid` int(11) default 0 ,
  `rid` int(11) default 0 ,
`tname` varchar(255) NOT NULL,
 `sort` int(3) default 0,
  PRIMARY KEY (`id`),KEY `indx_hid` (`hid`),KEY `indx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);


// 户型 图片设置

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house_timage') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `hid` int(11) default 0 ,
  `tid` int(11) default 0 ,
`pre_img` varchar(255) NOT NULL,
`img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),KEY `indx_hid` (`hid`),KEY `tid` (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);



//1.4
// 相册

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house_pic_type') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `hid` int(11) default 0 ,
  `rid` int(11) default 0 ,
 `pname` varchar(255) NOT NULL,
 `sort` int(3) default 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);


// 相册图片

$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_house_pic_image') . " (
 `id` int(10) unsigned  AUTO_INCREMENT,
 `hid` int(11) default 0 ,
 `pid` int(11) default 0 ,
`pre_img` varchar(255) NOT NULL,
`img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

pdo_query($sql);
























