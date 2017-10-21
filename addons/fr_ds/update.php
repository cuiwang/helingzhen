<?php
pdo_query("
CREATE TABLE IF NOT EXISTS `ims_fr_ds_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `module_name` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `sign` varchar(100) DEFAULT NULL,
  `openid` varchar(100) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `tid` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
");
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'id')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `id` int(11) NOT NULL auto_increment  COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `uniacid` int(10) unsigned    COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'module_name')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `module_name` varchar(50)    COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'title')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `title` varchar(255)    COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'referer')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `referer` varchar(255)    COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'sign')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `sign` varchar(100)    COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `openid` varchar(100)    COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'money')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `money` decimal(10,2)    COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `createtime` int(11)    COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'status')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `status` tinyint(1)   DEFAULT 0 COMMENT '';");
	}	
}
if(pdo_tableexists('fr_ds_record')) {
	if(!pdo_fieldexists('fr_ds_record',  'tid')) {
		pdo_query("ALTER TABLE ".tablename('fr_ds_record')." ADD `tid` varchar(255)    COMMENT '';");
	}	
}
