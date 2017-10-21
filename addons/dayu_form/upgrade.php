<?php
pdo_query("CREATE TABLE IF NOT EXISTS `ims_dayu_form` (
`reid` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`title` varchar(100) NOT NULL,
`description` varchar(1000) NOT NULL,
`content` text NOT NULL,
`information` varchar(500) NOT NULL,
`thumb` varchar(200) NOT NULL,
`inhome` tinyint(4) NOT NULL,
`starttime` int(10) NOT NULL,
`endtime` int(10) unsigned NOT NULL,
`status` int(1) NOT NULL,
`member` varchar(20) NOT NULL,
`phone` varchar(20) NOT NULL,
`noticeemail` varchar(50) NOT NULL,
`k_templateid` varchar(50) NOT NULL,
`kfid` varchar(50) NOT NULL,
`m_templateid` varchar(50) NOT NULL,
`kfirst` varchar(100) NOT NULL,
`kfoot` varchar(100) NOT NULL,
`mfirst` varchar(100) NOT NULL,
`mfoot` varchar(100) NOT NULL,
`mobile` varchar(50) NOT NULL,
`adds` varchar(20) NOT NULL,
`skins` varchar(20) NOT NULL,
`custom_status` int(1) NOT NULL,
`mbgroup` int(10) unsigned NOT NULL,
`outlink` varchar(250) NOT NULL,
`isinfo` tinyint(1) NOT NULL,
`isvoice` tinyint(1) NOT NULL,
`isrevoice` tinyint(1) NOT NULL,
`ivoice` tinyint(1) NOT NULL,
`voice` varchar(50) NOT NULL,
`voicedec` varchar(50) NOT NULL,
`isloc` tinyint(1) NOT NULL,
`isrethumb` tinyint(1) NOT NULL,
`isrecord` tinyint(1) NOT NULL,
`isget` tinyint(1) NOT NULL,
`credit` decimal(10,2) NOT NULL,
`smsid` int(11) NOT NULL,
`smsnotice` int(11) NOT NULL,
`smstype` int(1) NOT NULL,
`agreement` varchar(50) NOT NULL,
`paixu` int(1) NOT NULL,
`field` tinyint(1) NOT NULL,
`fields` text NOT NULL,
`avatar` tinyint(1) NOT NULL,
`bcolor` varchar(10) NOT NULL,
`pluraltit` varchar(50) NOT NULL,
`plural` tinyint(1) NOT NULL,
`par` text NOT NULL,
`linkage` text NOT NULL,
`score_total` int(11) NOT NULL,
`score_vr` int(11) NOT NULL,
`score_num` int(11) NOT NULL,
`createtime` int(10) NOT NULL,
`list` tinyint(1) NOT NULL,
`cid` int(11) NOT NULL,
PRIMARY KEY (`reid`),
KEY `weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_custom` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`raply` varchar(200) NOT NULL,
`displayorder` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_data` (
`redid` bigint(20) NOT NULL AUTO_INCREMENT,
`reid` int(11) NOT NULL,
`rerid` int(11) NOT NULL,
`refid` int(11) NOT NULL,
`data` varchar(800) NOT NULL,
`displayorder` int(11) NOT NULL,
PRIMARY KEY (`redid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_fields` (
`refid` int(11) NOT NULL AUTO_INCREMENT,
`reid` int(11) NOT NULL,
`title` varchar(200) NOT NULL,
`type` varchar(20) NOT NULL,
`essential` tinyint(1) NOT NULL,
`only` tinyint(1) NOT NULL,
`bind` varchar(30) NOT NULL,
`value` varchar(255) NOT NULL,
`description` varchar(255) NOT NULL,
`image` varchar(255) NOT NULL,
`loc` tinyint(1) NOT NULL,
`displayorder` int(11) unsigned NOT NULL,
PRIMARY KEY (`refid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_info` (
`rerid` int(11) NOT NULL AUTO_INCREMENT,
`reid` int(11) NOT NULL,
`member` varchar(50) NOT NULL,
`mobile` varchar(11) NOT NULL,
`address` varchar(100) NOT NULL,
`openid` varchar(50) NOT NULL,
`thumb` text NOT NULL,
`voice` varchar(250) NOT NULL,
`revoice` varchar(250) NOT NULL,
`rethumb` varchar(250) NOT NULL,
`loc_x` varchar(20) NOT NULL,
`loc_y` varchar(20) NOT NULL,
`status` tinyint(4) NOT NULL,
`yuyuetime` int(10) NOT NULL,
`kf` varchar(50) NOT NULL,
`kfinfo` varchar(100) NOT NULL,
`var1` varchar(250) NOT NULL,
`var2` varchar(250) NOT NULL,
`var3` varchar(250) NOT NULL,
`icredit` tinyint(1) NOT NULL,
`file` text NOT NULL,
`linkage` text NOT NULL,
`kid` int(11) NOT NULL,
`commentid` int(11) NOT NULL,
`createtime` int(10) NOT NULL,
PRIMARY KEY (`rerid`),
KEY `reid` (`reid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_linkage` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`reid` int(11) unsigned NOT NULL,
`title` varchar(100) NOT NULL,
`parentid` int(11) unsigned NOT NULL,
`displayorder` int(5) unsigned NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_reply` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`rid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_role` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
`roleid` int(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_dayu_form_staff` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`weid` int(11) NOT NULL,
`reid` int(11) NOT NULL,
`nickname` varchar(50) NOT NULL,
`openid` varchar(50) NOT NULL,
`createtime` int(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `reid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'title')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'description')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `description` varchar(1000) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'content')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `content` text NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'information')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `information` varchar(500) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `thumb` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'inhome')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `inhome` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'starttime')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `starttime` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'endtime')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `endtime` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'status')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `status` int(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'member')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `member` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'phone')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `phone` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'noticeemail')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `noticeemail` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'k_templateid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `k_templateid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'kfid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `kfid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'm_templateid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `m_templateid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'kfirst')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `kfirst` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'kfoot')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `kfoot` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'mfirst')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `mfirst` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'mfoot')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `mfoot` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `mobile` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'adds')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `adds` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'skins')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `skins` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'custom_status')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `custom_status` int(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'mbgroup')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `mbgroup` int(10) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'outlink')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `outlink` varchar(250) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'isinfo')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `isinfo` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'isvoice')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `isvoice` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'isrevoice')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `isrevoice` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'ivoice')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `ivoice` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'voice')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `voice` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'voicedec')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `voicedec` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'isloc')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `isloc` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'isrethumb')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `isrethumb` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'isrecord')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `isrecord` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'isget')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `isget` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'credit')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `credit` decimal(10,2) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'smsid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `smsid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'smsnotice')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `smsnotice` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'smstype')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `smstype` int(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'agreement')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `agreement` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'paixu')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `paixu` int(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'field')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `field` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'fields')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `fields` text NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'avatar')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `avatar` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'bcolor')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `bcolor` varchar(10) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'pluraltit')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `pluraltit` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'plural')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `plural` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'par')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `par` text NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'linkage')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `linkage` text NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'score_total')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `score_total` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'score_vr')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `score_vr` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'score_num')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `score_num` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `createtime` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'list')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `list` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form')) {
	if(!pdo_fieldexists('dayu_form',  'cid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form')." ADD `cid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_custom')) {
	if(!pdo_fieldexists('dayu_form_custom',  'id')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_custom')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('dayu_form_custom')) {
	if(!pdo_fieldexists('dayu_form_custom',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_custom')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_custom')) {
	if(!pdo_fieldexists('dayu_form_custom',  'raply')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_custom')." ADD `raply` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_custom')) {
	if(!pdo_fieldexists('dayu_form_custom',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_custom')." ADD `displayorder` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_data')) {
	if(!pdo_fieldexists('dayu_form_data',  'redid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_data')." ADD `redid` bigint(20) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('dayu_form_data')) {
	if(!pdo_fieldexists('dayu_form_data',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_data')." ADD `reid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_data')) {
	if(!pdo_fieldexists('dayu_form_data',  'rerid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_data')." ADD `rerid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_data')) {
	if(!pdo_fieldexists('dayu_form_data',  'refid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_data')." ADD `refid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_data')) {
	if(!pdo_fieldexists('dayu_form_data',  'data')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_data')." ADD `data` varchar(800) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_data')) {
	if(!pdo_fieldexists('dayu_form_data',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_data')." ADD `displayorder` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'refid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `refid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `reid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'title')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `title` varchar(200) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'type')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `type` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'essential')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `essential` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'only')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `only` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'bind')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `bind` varchar(30) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'value')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `value` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'description')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `description` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'image')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `image` varchar(255) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'loc')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `loc` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_fields')) {
	if(!pdo_fieldexists('dayu_form_fields',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_fields')." ADD `displayorder` int(11) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'rerid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `rerid` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `reid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'member')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `member` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'mobile')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `mobile` varchar(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'address')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `address` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `openid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'thumb')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `thumb` text NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'voice')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `voice` varchar(250) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'revoice')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `revoice` varchar(250) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'rethumb')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `rethumb` varchar(250) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'loc_x')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `loc_x` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'loc_y')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `loc_y` varchar(20) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'status')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `status` tinyint(4) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'yuyuetime')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `yuyuetime` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'kf')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `kf` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'kfinfo')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `kfinfo` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'var1')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `var1` varchar(250) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'var2')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `var2` varchar(250) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'var3')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `var3` varchar(250) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'icredit')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `icredit` tinyint(1) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'file')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `file` text NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'linkage')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `linkage` text NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'kid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `kid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'commentid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `commentid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_info')) {
	if(!pdo_fieldexists('dayu_form_info',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_info')." ADD `createtime` int(10) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_linkage')) {
	if(!pdo_fieldexists('dayu_form_linkage',  'id')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_linkage')." ADD `id` int(11) unsigned NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('dayu_form_linkage')) {
	if(!pdo_fieldexists('dayu_form_linkage',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_linkage')." ADD `reid` int(11) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_linkage')) {
	if(!pdo_fieldexists('dayu_form_linkage',  'title')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_linkage')." ADD `title` varchar(100) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_linkage')) {
	if(!pdo_fieldexists('dayu_form_linkage',  'parentid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_linkage')." ADD `parentid` int(11) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_linkage')) {
	if(!pdo_fieldexists('dayu_form_linkage',  'displayorder')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_linkage')." ADD `displayorder` int(5) unsigned NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_reply')) {
	if(!pdo_fieldexists('dayu_form_reply',  'id')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('dayu_form_reply')) {
	if(!pdo_fieldexists('dayu_form_reply',  'rid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_reply')." ADD `rid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_reply')) {
	if(!pdo_fieldexists('dayu_form_reply',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_reply')." ADD `reid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_role')) {
	if(!pdo_fieldexists('dayu_form_role',  'id')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_role')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('dayu_form_role')) {
	if(!pdo_fieldexists('dayu_form_role',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_role')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_role')) {
	if(!pdo_fieldexists('dayu_form_role',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_role')." ADD `reid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_role')) {
	if(!pdo_fieldexists('dayu_form_role',  'roleid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_role')." ADD `roleid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_staff')) {
	if(!pdo_fieldexists('dayu_form_staff',  'id')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_staff')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
	}	
}
if(pdo_tableexists('dayu_form_staff')) {
	if(!pdo_fieldexists('dayu_form_staff',  'weid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_staff')." ADD `weid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_staff')) {
	if(!pdo_fieldexists('dayu_form_staff',  'reid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_staff')." ADD `reid` int(11) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_staff')) {
	if(!pdo_fieldexists('dayu_form_staff',  'nickname')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_staff')." ADD `nickname` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_staff')) {
	if(!pdo_fieldexists('dayu_form_staff',  'openid')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_staff')." ADD `openid` varchar(50) NOT NULL;");
	}	
}
if(pdo_tableexists('dayu_form_staff')) {
	if(!pdo_fieldexists('dayu_form_staff',  'createtime')) {
		pdo_query("ALTER TABLE ".tablename('dayu_form_staff')." ADD `createtime` int(10) NOT NULL;");
	}	
}
