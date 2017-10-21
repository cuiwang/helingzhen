<?php

if(!pdo_fieldexists('hao_recommend_list', 'category')) {
	pdo_query("ALTER TABLE ".tablename('hao_recommend_list')." ADD `category` INT NOT NULL AFTER `id`;");
}

if(!pdo_fieldexists('hao_recommend_list', 'sort')) {
	pdo_query("ALTER TABLE ".tablename('hao_recommend_list')." ADD `sort` INT NOT NULL AFTER `uniacid`;");
}

if(!pdo_fieldexists('hao_recommend_setting', 'tempID')) {
	pdo_query("ALTER TABLE ".tablename('hao_recommend_setting')." ADD `tempID` varchar(255) NOT NULL AFTER `image`;");
}

$sql = "CREATE TABLE IF NOT EXISTS `ims_hao_recommend_banner` (
		  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  `image` varchar(255) NOT NULL,
		  `link` varchar(300) NOT NULL,
		  `sort` varchar(255) NOT NULL,
		  `display` int(11) NOT NULL,
		  `uniacid` int(10) NOT NULL
		) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;";

pdo_query($sql);

?>