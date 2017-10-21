<?php
$sql = 
"
CREATE TABLE IF NOT EXISTS ".tablename('messikefu_chat')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `openid` varchar(100) NOT NULL,
 `toopenid` varchar(100) NOT NULL,
 `content` varchar(255) NOT NULL,
 `time` int(11) NOT NULL,
 `nickname` varchar(50) NOT NULL,
 `avatar` varchar(200) NOT NULL,
 `type` tinyint(1) NOT NULL,
 `hasread` tinyint(1) NOT NULL,
 `fkid` int(11) NOT NULL,
 `yuyintime` smallint(6) NOT NULL,
 `hasyuyindu` tinyint(1) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_sanchat')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `sanfkid` int(11) NOT NULL,
 `content` varchar(255) NOT NULL,
 `time` int(11) NOT NULL,
 `type` tinyint(1) NOT NULL,
 `openid` varchar(100) NOT NULL,
 `yuyintime` smallint(6) NOT NULL,
 `hasyuyindu` tinyint(1) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_sanfanskefu')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `fansopenid` varchar(100) NOT NULL,
 `kefuopenid` varchar(100) NOT NULL,
 `fansavatar` varchar(200) NOT NULL,
 `kefuavatar` varchar(200) NOT NULL,
 `fansnickname` varchar(100) NOT NULL,
 `kefunickname` varchar(100) NOT NULL,
 `lasttime` int(11) NOT NULL,
 `notread` int(11) NOT NULL,
 `lastcon` varchar(255) NOT NULL,
 `msgtype` smallint(6) NOT NULL,
 `seetime` int(11) NOT NULL,
 `qudao` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_adv')." (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `weid` int(11) DEFAULT '0',
 `advname` varchar(50) DEFAULT '',
 `link` varchar(255) DEFAULT '',
 `thumb` varchar(255) DEFAULT '',
 `displayorder` int(11) DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_group')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `groupname` varchar(100) NOT NULL,
 `thumb` varchar(200) NOT NULL,
 `admin` varchar(100) NOT NULL,
 `time` int(11) NOT NULL,
 `autoreply` varchar(200) NOT NULL,
 `quickcon` text NOT NULL,
 `isautosub` tinyint(1) NOT NULL,
 `cservicegroupid` int(11) NOT NULL,
 `lasttime` int(11) NOT NULL,
 `maxnum` int(11) NOT NULL,
 `isguanzhu` tinyint(1) NOT NULL,
 `jinyan` tinyint(1) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_groupchat')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `nickname` varchar(100) NOT NULL,
 `avatar` varchar(255) NOT NULL,
 `weid` int(11) NOT NULL,
 `groupid` int(11) NOT NULL,
 `openid` varchar(100) NOT NULL,
 `content` varchar(255) NOT NULL,
 `time` int(11) NOT NULL,
 `type` tinyint(1) NOT NULL,
 `yuyintime` smallint(6) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_groupmember')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `groupid` int(11) NOT NULL,
 `openid` varchar(100) NOT NULL,
 `nickname` varchar(50) NOT NULL,
 `avatar` varchar(255) NOT NULL,
 `type` tinyint(1) NOT NULL,
 `status` tinyint(1) NOT NULL,
 `intime` int(11) NOT NULL,
 `lasttime` int(11) NOT NULL,
 `notread` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_fanskefu')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `fansopenid` varchar(100) NOT NULL,
 `kefuopenid` varchar(100) NOT NULL,
 `fansavatar` varchar(200) NOT NULL,
 `kefuavatar` varchar(200) NOT NULL,
 `fansnickname` varchar(100) NOT NULL,
 `kefunickname` varchar(100) NOT NULL,
 `lasttime` int(11) NOT NULL,
 `notread` int(11) NOT NULL,
 `lastcon` varchar(255) NOT NULL,
 `kefulasttime` int(11) NOT NULL,
 `kefulastcon` varchar(255) NOT NULL,
 `kefunotread` int(11) NOT NULL,
 `msgtype` smallint(6) NOT NULL,
 `kefumsgtype` smallint(6) NOT NULL,
 `kefuseetime` int(11) NOT NULL,
 `fansseetime` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_biaoqian')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `kefuopenid` varchar(200) NOT NULL,
 `fensiopenid` varchar(200) NOT NULL,
 `name` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_cservice')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `name` varchar(50) NOT NULL,
 `ctype` tinyint(1) NOT NULL,
 `content` varchar(100) NOT NULL,
 `thumb` varchar(255) NOT NULL,
 `displayorder` int(11) NOT NULL,
 `starthour` smallint(6) NOT NULL,
 `endhour` smallint(6) NOT NULL,
 `autoreply` varchar(200) NOT NULL,
 `isonline` tinyint(1) NOT NULL,
 `groupid` int(11) NOT NULL,
 `fansauto` text NOT NULL,
 `kefuauto` text NOT NULL,
 `isautosub` tinyint(1) NOT NULL,
 `qrtext` varchar(50) NOT NULL,
 `qrbg` varchar(20) NOT NULL,
 `qrcolor` varchar(20) NOT NULL,
 `iskefuqrcode` tinyint(1) NOT NULL,
 `kefuqrcode` varchar(200) NOT NULL,
 `ishow` tinyint(1) NOT NULL,
 `notonline` varchar(255) NOT NULL,
 `lingjie` tinyint(1) NOT NULL,
 `typename` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_set')." (
 `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `title` varchar(30) NOT NULL,
 `istplon` tinyint(1) NOT NULL,
 `unfollowtext` text NOT NULL,
 `followqrcode` varchar(100) NOT NULL,
 `sharetitle` varchar(100) NOT NULL,
 `sharedes` varchar(255) NOT NULL,
 `sharethumb` varchar(155) NOT NULL,
 `kefutplminute` int(11) NOT NULL,
 `bgcolor` varchar(10) NOT NULL,
 `defaultavatar` varchar(100) NOT NULL,
 `fansauto` text NOT NULL,
 `kefuauto` text NOT NULL,
 `issharemsg` tinyint(1) NOT NULL,
 `isautosub` tinyint(1) NOT NULL,
 `isshowwgz` tinyint(1) NOT NULL,
 `sharetype` tinyint(1) NOT NULL,
 `mingan` text NOT NULL,
 `temcolor` varchar(50) NOT NULL,
 `candel` tinyint(1) NOT NULL,
 `copyright` varchar(255) NOT NULL,
 `canservicequn` tinyint(1) NOT NULL,
 `canfansqun` tinyint(1) NOT NULL,
 `isgrouptplon` tinyint(1) NOT NULL,
 `grouptplminute` int(11) NOT NULL,
 `isgroupon` tinyint(1) NOT NULL,
 `footertext1` varchar(50) NOT NULL,
 `footertext2` varchar(50) NOT NULL,
 `footertext3` varchar(50) NOT NULL,
 `footertext4` varchar(50) NOT NULL,
 `isqiniu` tinyint(1) NOT NULL,
 `qiniuaccesskey` varchar(255) NOT NULL,
 `qiniusecretkey` varchar(255) NOT NULL,
 `qiniubucket` varchar(255) NOT NULL,
 `qiniuurl` varchar(255) NOT NULL,
 `httptype` tinyint(1) NOT NULL,
 `istxfon` tinyint(1) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_tplmessage_sendlog')." (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `tpl_id` int(11) DEFAULT NULL,
 `tpl_title` varchar(50) DEFAULT NULL,
 `message` text COMMENT '消息内容',
 `success` int(11) DEFAULT '0' COMMENT '成功人数',
 `fail` int(11) DEFAULT '0' COMMENT '失败人数',
 `time` int(11) DEFAULT NULL COMMENT '发送时间',
 `uniacid` int(5) DEFAULT NULL,
 `type` int(5) DEFAULT '0' COMMENT '消息类型 0为群发 1为个人',
 `target` varchar(80) DEFAULT '' COMMENT '发送对象 type 为0时 是粉丝组 type 为1时是openid',
 `status` int(2) DEFAULT '0' COMMENT '状态 0为发送中 1为完成 2为失败',
 `error` text COMMENT '错误记录',
 `mid` int(11) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_tplmessage_tpllist')." (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `tplbh` varchar(50) NOT NULL,
 `tpl_id` varchar(80) DEFAULT NULL,
 `tpl_title` varchar(20) DEFAULT NULL,
 `tpl_key` varchar(500) DEFAULT NULL COMMENT '模板内容key',
 `tpl_example` varchar(500) DEFAULT NULL,
 `uniacid` int(5) DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_cservicegroup')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `name` varchar(50) NOT NULL,
 `thumb` varchar(255) NOT NULL,
 `displayorder` int(11) NOT NULL,
 `qrtext` varchar(50) NOT NULL,
 `qrbg` varchar(20) NOT NULL,
 `qrcolor` varchar(20) NOT NULL,
 `cangroup` tinyint(1) NOT NULL,
 `typename` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ".tablename('messikefu_jqr')." (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `weid` int(11) NOT NULL,
 `title` varchar(100) NOT NULL,
 `huifu` text NOT NULL,
 `kefuid` int(11) NOT NULL,
 `type` tinyint(1) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

";
pdo_query($sql);

if(pdo_tableexists('messikefu_cservice')) 
{
	if(!pdo_fieldexists('messikefu_cservice','starthour'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `starthour` smallint(6) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','endhour'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `endhour` smallint(6) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','autoreply'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `autoreply` varchar(200) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','isonline'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `isonline` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','groupid'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `groupid` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','fansauto'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `fansauto` text NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','kefuauto'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `kefuauto` text NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','isautosub'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `isautosub` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','qrtext'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `qrtext` varchar(50) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','qrbg'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `qrbg` varchar(20) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','qrcolor'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `qrcolor` varchar(20) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','iskefuqrcode'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `iskefuqrcode` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','kefuqrcode'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `kefuqrcode` varchar(200) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','ishow'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `ishow` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','notonline'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `notonline` varchar(255) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','lingjie'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `lingjie` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservice','typename'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservice')." ADD `typename` varchar(50) NOT NULL COMMENT '';");
	}
}
if(pdo_tableexists('messikefu_chat')) 
{
	if(!pdo_fieldexists('messikefu_chat','hasread'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `hasread` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_chat','fkid'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `fkid` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_chat','yuyintime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `yuyintime` smallint(6) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_chat','hasyuyindu'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_chat')." ADD `hasyuyindu` tinyint(1) NOT NULL COMMENT '';");
	}
}
if(pdo_tableexists('messikefu_set')) 
{
	if(!pdo_fieldexists('messikefu_set','fansauto'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `fansauto` text NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','kefuauto'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `kefuauto` text NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','issharemsg'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `issharemsg` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','isautosub'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isautosub` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','isshowwgz'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isshowwgz` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','sharetype'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `sharetype` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','mingan'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `mingan` text NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','temcolor'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `temcolor` varchar(50) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','candel'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `candel` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','copyright'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `copyright` varchar(255) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','canservicequn'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `canservicequn` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','canfansqun'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `canfansqun` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','isgrouptplon'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isgrouptplon` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','grouptplminute'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `grouptplminute` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','isgroupon'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isgroupon` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','footertext1'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `footertext1` varchar(50) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','footertext2'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `footertext2` varchar(50) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','footertext3'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `footertext3` varchar(50) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','footertext4'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `footertext4` varchar(50) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','isqiniu'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `isqiniu` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','qiniuaccesskey'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `qiniuaccesskey` varchar(255) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','qiniusecretkey'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `qiniusecretkey` varchar(255) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','qiniubucket'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `qiniubucket` varchar(255) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','qiniuurl'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `qiniuurl` varchar(255) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','httptype'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `httptype` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_set','istxfon'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_set')." ADD `istxfon` tinyint(1) NOT NULL COMMENT '';");
	}
}
if(pdo_tableexists('messikefu_jqr')) 
{
	if(!pdo_fieldexists('messikefu_jqr','kefuid'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_jqr')." ADD `kefuid` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_jqr','type'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_jqr')." ADD `type` tinyint(1) NOT NULL COMMENT '';");
	}
}
if(pdo_tableexists('messikefu_cservicegroup')) 
{
	if(!pdo_fieldexists('messikefu_cservicegroup','qrtext'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `qrtext` varchar(50) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservicegroup','qrbg'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `qrbg` varchar(20) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservicegroup','qrcolor'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `qrcolor` varchar(20) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservicegroup','cangroup'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `cangroup` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_cservicegroup','typename'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_cservicegroup')." ADD `typename` varchar(50) NOT NULL COMMENT '';");
	}
}
if(pdo_tableexists('messikefu_groupmember')) 
{
	if(!pdo_fieldexists('messikefu_groupmember','status'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `status` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_groupmember','intime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `intime` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_groupmember','lasttime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `lasttime` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_groupmember','notread'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_groupmember')." ADD `notread` int(11) NOT NULL COMMENT '';");
	}
}
if(pdo_tableexists('messikefu_group')) 
{
	if(!pdo_fieldexists('messikefu_group','cservicegroupid'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `cservicegroupid` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_group','lasttime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `lasttime` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_group','maxnum'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `maxnum` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_group','isguanzhu'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `isguanzhu` tinyint(1) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_group','jinyan'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_group')." ADD `jinyan` tinyint(1) NOT NULL COMMENT '';");
	}
}
if(pdo_tableexists('messikefu_fanskefu')) 
{
	if(!pdo_fieldexists('messikefu_fanskefu','lasttime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `lasttime` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_fanskefu','notread'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `notread` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_fanskefu','lastcon'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `lastcon` varchar(255) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_fanskefu','kefulasttime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefulasttime` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_fanskefu','kefulastcon'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefulastcon` varchar(255) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_fanskefu','kefunotread'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefunotread` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_fanskefu','msgtype'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `msgtype` smallint(6) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_fanskefu','kefumsgtype'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefumsgtype` smallint(6) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_fanskefu','kefuseetime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `kefuseetime` int(11) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_fanskefu','fansseetime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_fanskefu')." ADD `fansseetime` int(11) NOT NULL COMMENT '';");
	}
}
if(pdo_tableexists('messikefu_sanchat')) 
{
	if(!pdo_fieldexists('messikefu_sanchat','yuyintime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `yuyintime` smallint(6) NOT NULL COMMENT '';");
	}
	if(!pdo_fieldexists('messikefu_sanchat','hasyuyindu'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_sanchat')." ADD `hasyuyindu` tinyint(1) NOT NULL COMMENT '';");
	}
}
if(pdo_tableexists('messikefu_groupchat')) 
{
	if(!pdo_fieldexists('messikefu_groupchat','yuyintime'))
	{
		pdo_query("ALTER TABLE ".tablename('messikefu_groupchat')." ADD `yuyintime` smallint(6) NOT NULL COMMENT '';");
	}
}