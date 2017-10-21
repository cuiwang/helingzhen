<?php



$sql = "
CREATE TABLE IF NOT EXISTS " . tablename('mon_fool') . " (
`id` int(10) unsigned  AUTO_INCREMENT,
 `weid` int(11) NOT NULL  ,
 `rid` int(11) NOT NULL,
 `title` varchar(200) NOT NULL,
 `follow_url` varchar(200) NOT NULL,
 `new_title` varchar(200) NOT NULL,
  `new_icon` varchar(200) NOT NULL,
  `new_content` varchar(200) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_icon` varchar(200) NOT NULL,
  `share_content` varchar(200) NOT NULL,
  `createtime` int(10) DEFAULT 0,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
pdo_query($sql);


