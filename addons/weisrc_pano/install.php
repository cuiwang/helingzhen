<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_weisrc_pano_reply` (
`id` int(10) NOT NULL AUTO_INCREMENT,
`rid` int(10) NOT NULL,
`weid` int(10) NOT NULL,
`type` tinyint(1) NOT NULL,
`title` varchar(50) NOT NULL,
`description` text NOT NULL,
`picture` varchar(200) NOT NULL,
`picture1` varchar(200) NOT NULL,
`picture2` varchar(200) NOT NULL,
`picture3` varchar(200) NOT NULL,
`picture4` varchar(200) NOT NULL,
`picture5` varchar(200) NOT NULL,
`picture6` varchar(200) NOT NULL,
`music` varchar(400) NOT NULL,
`status` tinyint(1) NOT NULL,
`dateline` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
