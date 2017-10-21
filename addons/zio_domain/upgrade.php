<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_zio_domain` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`uniacid` int(11) NOT NULL,
`domain` varchar(40) NOT NULL,
`title` varchar(50) NOT NULL,
`entry` varchar(255) NOT NULL,
`url` varchar(255) NOT NULL,
`module` varchar(20) NOT NULL,
`eid` int(11) NOT NULL,
`ext` text,
`type` tinyint(3) NOT NULL,
`all` tinyint(3) NOT NULL,
`redirect` tinyint(3) NOT NULL,
`status` tinyint(3) NOT NULL,
`createtime` int(10) unsigned NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_uniacid` (`uniacid`),
KEY `idx_domain` (`domain`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_zio_domain_group` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(40) NOT NULL,
`host` varchar(80) NOT NULL,
`groupid` int(11) NOT NULL,
`isaccount` tinyint(3) NOT NULL,
`limit` int(10) unsigned NOT NULL,
`ext` text,
`type` tinyint(3) NOT NULL,
`domain` tinyint(3) NOT NULL,
`right` tinyint(3) NOT NULL,
`status` tinyint(3) NOT NULL,
PRIMARY KEY (`id`),
KEY `idx_groupid` (`groupid`),
KEY `idx_isaccount` (`isaccount`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'id')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'uniacid')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `uniacid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'domain')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `domain` varchar(40) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'title')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `title` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'entry')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `entry` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'url')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `url` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'module')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `module` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'eid')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `eid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'ext')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `ext` text;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'type')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `type` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'all')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `all` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'redirect')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `redirect` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'status')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `status` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain')) {
	if(!pdo_fieldexists('zio_domain',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain')." ADD `createtime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'id')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'title')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `title` varchar(40) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'host')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `host` varchar(80) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'groupid')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `groupid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'isaccount')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `isaccount` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'limit')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `limit` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'ext')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `ext` text;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'type')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `type` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'domain')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `domain` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'right')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `right` tinyint(3) NOT NULL;");
	}	
}
if(pdo_tableexists('zio_domain_group')) {
	if(!pdo_fieldexists('zio_domain_group',  'status')) {
		pdo_query("ALTER TABLE ".tablename('zio_domain_group')." ADD `status` tinyint(3) NOT NULL;");
	}	
}
