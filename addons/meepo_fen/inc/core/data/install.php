<?php
if(!pdo_tableexists('meepo_module_update')){
	$sql = "CREATE TABLE ".tablename('meepo_module_update')." (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `module` varchar(64) DEFAULT '',
	  `desc` text,
	  `time` int(11) DEFAULT '0',
	  `version` varchar(64) DEFAULT '',
	  `alltables` text,
	  `files` mediumblob,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_module')){
	$sql = "CREATE TABLE ".tablename('meepo_module')." (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`module` varchar(32) NOT NULL DEFAULT '' COMMENT '模块名称',
	`set` text NOT NULL COMMENT '模块设置',
	`time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
	PRIMARY KEY (`id`),
	KEY `IDX_MODULE` (`module`) USING BTREE
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='模块安装表'";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_common_menu')){
	$sql = "CREATE TABLE ".tablename('meepo_common_menu')." (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(32) NOT NULL DEFAULT '',
	`icon` varchar(132) NOT NULL DEFAULT '',
	`module` varchar(32) NOT NULL DEFAULT '',
	`code` varchar(32) DEFAULT '',
	`pluginid` int(11) DEFAULT '0',
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

if(!pdo_tableexists('meepo_common_plugin')){
	$sql = "CREATE TABLE ".tablename('meepo_common_plugin')." (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`displayorder` int(11) DEFAULT '0',
	`name` varchar(50) DEFAULT '',
	`version` varchar(10) DEFAULT '',
	`author` varchar(20) DEFAULT '',
	`module` varchar(50) DEFAULT 'Empty String',
	`set` text,
	`desc` text,
	`fee` varchar(32) DEFAULT '',
	`num` int(11) DEFAULT '0',
	`code` varchar(50) DEFAULT '',
	PRIMARY KEY (`id`),
	KEY `idx_displayorder` (`displayorder`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}
if(!pdo_tableexists('meepo_common_setting')){
	$sql = "CREATE TABLE ".tablename('meepo_common_setting')." (
	  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	  `module` varchar(32) NOT NULL DEFAULT '',
	  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
	  `set` text NOT NULL,
	  `time` int(11) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`),
	  KEY `uniacid` (`uniacid`) USING BTREE
	) ENGINE=MyISAM AUTO_INCREMENT=127 DEFAULT CHARSET=utf8";
	pdo_query($sql);
}

