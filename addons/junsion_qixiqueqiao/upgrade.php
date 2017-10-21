<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_junsion_qixiqueqiao_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `qq` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `myname` varchar(50) NOT NULL,
  `hname` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  `createtime` varchar(11) NOT NULL,
  `successtime` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_qixiqueqiao_prize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_qixiqueqiao_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_qixiqueqiao_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `stitle` varchar(50) NOT NULL,
  `sthumb` varchar(200) NOT NULL,
  `sdesc` text NOT NULL,
  `niulang` varchar(200) NOT NULL,
  `zhinv` varchar(200) NOT NULL,
  `bg` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `starttime` varchar(11) NOT NULL,
  `endtime` varchar(11) NOT NULL,
  `prize_mode` int(1) NOT NULL,
  `describe_limit` int(1) NOT NULL,
  `describe_limit2` int(1) DEFAULT '0',
  `prize_limit` int(11) NOT NULL,
  `birds_success` int(11) NOT NULL,
  `birds_limit` varchar(10) NOT NULL,
  `sharetitle` varchar(200) NOT NULL,
  `sharethumb` varchar(200) NOT NULL,
  `sharedesc` text NOT NULL,
  `isinfo` int(1) NOT NULL,
  `awardtips` varchar(200) NOT NULL,
  `isrealname` int(1) NOT NULL,
  `ismobile` int(1) NOT NULL,
  `isqq` int(1) NOT NULL,
  `isemail` int(1) NOT NULL,
  `isaddress` int(1) NOT NULL,
  `isfans` int(1) NOT NULL,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_junsion_qixiqueqiao_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `birds_num` int(11) NOT NULL,
  `createtime` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'id')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `avatar` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `realname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `mobile` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'qq')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `qq` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'email')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `email` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'address')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `address` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'myname')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `myname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'hname')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `hname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'status')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `status` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `createtime` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_player',  'successtime')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_player')." ADD `successtime` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_prize',  'id')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_prize')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_prize',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_prize')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_prize',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_prize')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_prize',  'title')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_prize')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_prize',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_prize')." ADD `thumb` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_prize',  'description')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_prize')." ADD `description` text NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_prize',  'level')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_prize')." ADD `level` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_record')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_record',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_record')." ADD `pid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'id')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'stitle')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `stitle` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'sthumb')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `sthumb` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'sdesc')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `sdesc` text NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'niulang')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `niulang` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'zhinv')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `zhinv` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'bg')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `bg` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'content')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `content` text NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `starttime` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `endtime` varchar(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'prize_mode')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `prize_mode` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'describe_limit')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `describe_limit` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'describe_limit2')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `describe_limit2` int(1) DEFAULT '0';");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'prize_limit')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `prize_limit` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'birds_success')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `birds_success` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'birds_limit')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `birds_limit` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `sharetitle` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'sharethumb')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `sharethumb` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'sharedesc')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `sharedesc` text NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'isinfo')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `isinfo` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'awardtips')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `awardtips` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'isrealname')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `isrealname` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'ismobile')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `ismobile` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'isqq')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `isqq` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'isemail')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `isemail` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'isaddress')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `isaddress` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'isfans')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `isfans` int(1) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_rule',  'rank')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_rule')." ADD `rank` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_share')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_share',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_share')." ADD `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_share',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_share')." ADD `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_share',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_share')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_share',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_share')." ADD `avatar` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_share',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_share')." ADD `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_share',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_share')." ADD `pid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_share',  'birds_num')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_share')." ADD `birds_num` int(11) NOT NULL;");
}
if(!pdo_fieldexists('junsion_qixiqueqiao_share',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('junsion_qixiqueqiao_share')." ADD `createtime` varchar(11) NOT NULL;");
}

?>