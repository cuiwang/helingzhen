<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_addons_history` (
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
");
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'id')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `id` int(11) unsigned NOT NULL auto_increment  COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `uniacid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'cid')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `cid` int(10) unsigned   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'title')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `title` varchar(30)    COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'summary')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `summary` varchar(100)    COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'url')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `url` varchar(300)    COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'cover')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `cover` varchar(100)    COMMENT '封面';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'create_time')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `create_time` int(10) unsigned   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'update_time')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `update_time` int(10) unsigned   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `sort` tinyint(3) unsigned   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history')) {
	if(!pdo_fieldexists('addons_history',  'status')) {
		pdo_query("ALTER TABLE ".tablename('addons_history')." ADD `status` tinyint(1) unsigned   DEFAULT 1 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history_cate')) {
	if(!pdo_fieldexists('addons_history_cate',  'id')) {
		pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `id` int(11) unsigned NOT NULL auto_increment  COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history_cate')) {
	if(!pdo_fieldexists('addons_history_cate',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `uniacid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history_cate')) {
	if(!pdo_fieldexists('addons_history_cate',  'keyword')) {
		pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `keyword` varchar(30)    COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history_cate')) {
	if(!pdo_fieldexists('addons_history_cate',  'title')) {
		pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `title` varchar(30)    COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history_cate')) {
	if(!pdo_fieldexists('addons_history_cate',  'sort')) {
		pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `sort` tinyint(2) unsigned   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history_cate')) {
	if(!pdo_fieldexists('addons_history_cate',  'status')) {
		pdo_query("ALTER TABLE ".tablename('addons_history_cate')." ADD `status` tinyint(1) unsigned   DEFAULT 1 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history_mode')) {
	if(!pdo_fieldexists('addons_history_mode',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('addons_history_mode')." ADD `uniacid` int(10) unsigned NOT NULL  DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('addons_history_mode')) {
	if(!pdo_fieldexists('addons_history_mode',  'mode')) {
		pdo_query("ALTER TABLE ".tablename('addons_history_mode')." ADD `mode` tinyint(30) unsigned   DEFAULT 0 COMMENT '0无封面 1表示有封面';");
	}	
}
