<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_water_live_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `headimgurl` varchar(300) DEFAULT NULL,
  `fname` varchar(20) DEFAULT NULL,
  `fmobile` varchar(20) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `flocation` varchar(300) DEFAULT NULL,
  `balance` float DEFAULT '0' COMMENT '余额',
  `signtime` datetime DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `status` int(2) DEFAULT '0',
  `state` int(2) DEFAULT '0',
  `sharetime` datetime DEFAULT NULL,
  `sex` int(2) DEFAULT '0',
  `fage` int(11) DEFAULT '18',
  `faddress` varchar(200) DEFAULT NULL,
  `fdesc` varchar(100) DEFAULT NULL,
  `fimg` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_water_live_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `thefansid` int(11) DEFAULT NULL,
  `fansid` int(11) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `state` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_water_live_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `sectionid` int(11) DEFAULT NULL,
  `fansid` int(11) DEFAULT NULL,
  `nickname` varchar(300) DEFAULT NULL,
  `headimgurl` varchar(300) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL COMMENT '付款人',
  `addtime` datetime DEFAULT NULL COMMENT '时间',
  `state` int(2) DEFAULT '0' COMMENT '状态',
  `topic` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_water_live_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `orderno` varchar(50) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL COMMENT '类别',
  `sectionid` int(11) DEFAULT NULL,
  `sfansid` int(11) DEFAULT NULL,
  `sopenid` varchar(300) DEFAULT NULL,
  `fee` float DEFAULT '0' COMMENT '金额',
  `fansid` int(11) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL,
  `nickname` varchar(300) DEFAULT NULL,
  `headimgurl` varchar(300) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL COMMENT '时间',
  `paytime` datetime DEFAULT NULL COMMENT '时间',
  `msg` varchar(100) DEFAULT NULL,
  `state` int(2) DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_water_live_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `sectionid` int(11) DEFAULT NULL,
  `datato` int(11) DEFAULT NULL,
  `toname` varchar(100) DEFAULT NULL,
  `datafrom` int(11) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `content` varchar(300) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL COMMENT '时间',
  `state` int(2) DEFAULT '2' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_water_live_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `topicid` int(11) DEFAULT NULL,
  `fansid` int(11) DEFAULT NULL,
  `openid` varchar(300) DEFAULT NULL,
  `nickname` varchar(300) DEFAULT NULL,
  `headimgurl` varchar(300) DEFAULT NULL,
  `sharetitle` varchar(100) DEFAULT NULL,
  `sharedesc` varchar(200) DEFAULT NULL,
  `content` text COMMENT '内容',
  `imgs` varchar(2000) DEFAULT NULL COMMENT '上传照片',
  `addtime` datetime DEFAULT NULL COMMENT '时间',
  `toptime` datetime DEFAULT NULL,
  `scansum` int(11) DEFAULT '0',
  `settop` int(2) DEFAULT '0',
  `status` int(2) DEFAULT '0',
  `state` int(2) DEFAULT '2' COMMENT '状态',
  `address` varchar(200) DEFAULT NULL,
  `audiosrc` varchar(300) DEFAULT NULL,
  `audiosid` varchar(300) DEFAULT NULL,
  `audiotime` int(10) DEFAULT '0',
  `vediosrc` varchar(300) DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `fmobile` varchar(50) DEFAULT NULL,
  `showtitle` text,
  `fee` float DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_water_live_talk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `thefansid` int(11) DEFAULT NULL,
  `fansid` int(11) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `state` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_water_live_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `typeid` int(11) DEFAULT NULL,
  `stitle` varchar(100) NOT NULL,
  `sdesc` varchar(500) DEFAULT NULL,
  `simg` varchar(300) DEFAULT NULL,
  `hot` int(2) NOT NULL DEFAULT '0',
  `new` int(2) NOT NULL DEFAULT '2',
  `sindex` int(2) NOT NULL DEFAULT '0',
  `state` int(2) NOT NULL DEFAULT '2',
  `isaudio` int(2) DEFAULT '0',
  `isvedio` int(2) DEFAULT '0',
  `addtitle` varchar(50) DEFAULT '发表帖子',
  `isadmin` int(2) DEFAULT '0',
  `shorttitle` varchar(50) DEFAULT NULL,
  `placeholder` varchar(100) DEFAULT NULL,
  `maxchar` int(11) DEFAULT NULL,
  `isgetinfo` int(2) DEFAULT NULL,
  `issell` int(2) DEFAULT '0',
  `settop` int(2) DEFAULT NULL,
  `toptime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('water_live_fans',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_live_fans',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `openid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `nickname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `headimgurl` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'fname')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `fname` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'fmobile')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `fmobile` varchar(20) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'address')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `address` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'flocation')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `flocation` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'balance')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `balance` float DEFAULT '0' COMMENT '余额';");
}
if(!pdo_fieldexists('water_live_fans',  'signtime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `signtime` datetime DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `addtime` datetime DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'status')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `status` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_fans',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `state` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_fans',  'sharetime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `sharetime` datetime DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `sex` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_fans',  'fage')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `fage` int(11) DEFAULT '18';");
}
if(!pdo_fieldexists('water_live_fans',  'faddress')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `faddress` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'fdesc')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `fdesc` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_fans',  'fimg')) {
	pdo_query("ALTER TABLE ".tablename('water_live_fans')." ADD `fimg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_follow',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_live_follow')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_live_follow',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_follow')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_follow',  'thefansid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_follow')." ADD `thefansid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_follow',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_follow')." ADD `fansid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_follow',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_follow')." ADD `addtime` datetime DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_follow',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_live_follow')." ADD `state` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_like',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_live_like',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_like',  'sectionid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `sectionid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_like',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `fansid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_like',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `nickname` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_like',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `headimgurl` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_like',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `openid` varchar(300) DEFAULT NULL COMMENT '付款人';");
}
if(!pdo_fieldexists('water_live_like',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `addtime` datetime DEFAULT NULL COMMENT '时间';");
}
if(!pdo_fieldexists('water_live_like',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `state` int(2) DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('water_live_like',  'topic')) {
	pdo_query("ALTER TABLE ".tablename('water_live_like')." ADD `topic` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_live_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'orderno')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `orderno` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'type')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `type` varchar(20) DEFAULT NULL COMMENT '类别';");
}
if(!pdo_fieldexists('water_live_order',  'sectionid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `sectionid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'sfansid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `sfansid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'sopenid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `sopenid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `fee` float DEFAULT '0' COMMENT '金额';");
}
if(!pdo_fieldexists('water_live_order',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `fansid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `openid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `nickname` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `headimgurl` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `addtime` datetime DEFAULT NULL COMMENT '时间';");
}
if(!pdo_fieldexists('water_live_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `paytime` datetime DEFAULT NULL COMMENT '时间';");
}
if(!pdo_fieldexists('water_live_order',  'msg')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `msg` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_order',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_live_order')." ADD `state` int(2) DEFAULT '0' COMMENT '状态';");
}
if(!pdo_fieldexists('water_live_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_live_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_reply',  'sectionid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `sectionid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_reply',  'datato')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `datato` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_reply',  'toname')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `toname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_reply',  'datafrom')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `datafrom` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_reply',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `nickname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_reply',  'content')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `content` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_reply',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `addtime` datetime DEFAULT NULL COMMENT '时间';");
}
if(!pdo_fieldexists('water_live_reply',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_live_reply')." ADD `state` int(2) DEFAULT '2' COMMENT '状态';");
}
if(!pdo_fieldexists('water_live_section',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_live_section',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'topicid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `topicid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `fansid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `openid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `nickname` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `headimgurl` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `sharetitle` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `sharedesc` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'content')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `content` text COMMENT '内容';");
}
if(!pdo_fieldexists('water_live_section',  'imgs')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `imgs` varchar(2000) DEFAULT NULL COMMENT '上传照片';");
}
if(!pdo_fieldexists('water_live_section',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `addtime` datetime DEFAULT NULL COMMENT '时间';");
}
if(!pdo_fieldexists('water_live_section',  'toptime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `toptime` datetime DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'scansum')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `scansum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_section',  'settop')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `settop` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_section',  'status')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `status` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_section',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `state` int(2) DEFAULT '2' COMMENT '状态';");
}
if(!pdo_fieldexists('water_live_section',  'address')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `address` varchar(200) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'audiosrc')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `audiosrc` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'audiosid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `audiosid` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'audiotime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `audiotime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_section',  'vediosrc')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `vediosrc` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'fname')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `fname` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'fmobile')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `fmobile` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_section',  'showtitle')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `showtitle` text;");
}
if(!pdo_fieldexists('water_live_section',  'fee')) {
	pdo_query("ALTER TABLE ".tablename('water_live_section')." ADD `fee` float DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_talk',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_live_talk')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_live_talk',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_talk')." ADD `uniacid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_talk',  'thefansid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_talk')." ADD `thefansid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_talk',  'fansid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_talk')." ADD `fansid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_talk',  'addtime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_talk')." ADD `addtime` datetime DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_talk',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_live_talk')." ADD `state` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'id')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('water_live_topic',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `uniacid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'typeid')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `typeid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'stitle')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `stitle` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'sdesc')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `sdesc` varchar(500) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'simg')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `simg` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'hot')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `hot` int(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_topic',  'new')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `new` int(2) NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('water_live_topic',  'sindex')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `sindex` int(2) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_topic',  'state')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `state` int(2) NOT NULL DEFAULT '2';");
}
if(!pdo_fieldexists('water_live_topic',  'isaudio')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `isaudio` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_topic',  'isvedio')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `isvedio` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_topic',  'addtitle')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `addtitle` varchar(50) DEFAULT '发表帖子';");
}
if(!pdo_fieldexists('water_live_topic',  'isadmin')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `isadmin` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_topic',  'shorttitle')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `shorttitle` varchar(50) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'placeholder')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `placeholder` varchar(100) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'maxchar')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `maxchar` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'isgetinfo')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `isgetinfo` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'issell')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `issell` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('water_live_topic',  'settop')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `settop` int(2) DEFAULT NULL;");
}
if(!pdo_fieldexists('water_live_topic',  'toptime')) {
	pdo_query("ALTER TABLE ".tablename('water_live_topic')." ADD `toptime` datetime DEFAULT NULL;");
}

?>