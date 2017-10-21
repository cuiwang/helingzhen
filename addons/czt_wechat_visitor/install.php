<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_czt_wechat_visitor_fans` (
`fanid` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`openid` varchar(50) NOT NULL,
`nickname` varchar(50) NOT NULL,
`headimgurl` varchar(256) NOT NULL,
`sex` tinyint(1),
`city` varchar(20) NOT NULL,
`country` varchar(20) NOT NULL,
`province` varchar(20) NOT NULL,
`create_time` int(10) unsigned NOT NULL,
PRIMARY KEY (`fanid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_czt_wechat_visitor_lists` (
`listid` int(11) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(11) unsigned NOT NULL,
`fanid` int(11) unsigned NOT NULL,
`topic` varchar(256) NOT NULL,
`create_time` int(10) unsigned NOT NULL,
`zan` int(11) unsigned NOT NULL,
PRIMARY KEY (`listid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_czt_wechat_visitor_visitors` (
`listid` int(11) unsigned NOT NULL,
`fanid` int(11) unsigned NOT NULL,
`create_time` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
