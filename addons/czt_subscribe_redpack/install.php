<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_czt_subscribe_redpack_records` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`uniacid` int(10) unsigned NOT NULL,
`openid` varchar(40) NOT NULL,
`fee` varchar(20) NOT NULL,
`log` varchar(500) NOT NULL,
`status` tinyint(1) NOT NULL,
`create_t` int(10) unsigned NOT NULL,
`success_t` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `openid` (`openid`),
KEY `log` (`log`),
KEY `uniacid` (`uniacid`),
KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
