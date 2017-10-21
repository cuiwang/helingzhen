<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hx_integral_jf` (
`telphone` varchar(15) NOT NULL,
`password` varchar(255) NOT NULL,
`credit` int(8) NOT NULL,
`is_show` tinyint(2) NOT NULL,
`uniacid` int(10) unsigned NOT NULL,
`createtime` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
