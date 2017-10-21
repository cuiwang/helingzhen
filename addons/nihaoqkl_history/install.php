<?php

$sql =<<<EOF
CREATE TABLE IF NOT EXISTS `ims_addons_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned DEFAULT '0',
  `title` varchar(30) DEFAULT '',
  `summary` varchar(100) DEFAULT '',
  `url` varchar(300) DEFAULT '',
  `cover` varchar(100) DEFAULT '' COMMENT '封面',
  `create_time` int(10) unsigned DEFAULT '0',
  `update_time` int(10) unsigned DEFAULT '0',
  `sort` tinyint(3) unsigned DEFAULT '0',
  `status` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_addons_history_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `keyword` varchar(30) DEFAULT '',
  `title` varchar(30) DEFAULT '',
  `sort` tinyint(2) unsigned DEFAULT '0',
  `status` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_addons_history_mode` (
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0',
  `mode` tinyint(30) unsigned DEFAULT '0' COMMENT '0无封面 1表示有封面',
  PRIMARY KEY (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
EOF;
pdo_run($sql);
