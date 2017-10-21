<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_shake_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `shakecount` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(500) NOT NULL DEFAULT '',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0为不可摇奖，1为可摇奖',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_openid_replyid` (`openid`,`rid`),
  KEY `idx_replyid` (`rid`),
  KEY `idx_shakecount` (`rid`,`shakecount`),
  KEY `createtime` (`createtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_shake_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `cover` varchar(255) NOT NULL DEFAULT '',
  `qrcode` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `rule` text NOT NULL,
  `speed` int(10) unsigned NOT NULL DEFAULT '3000',
  `speedandroid` int(10) unsigned NOT NULL DEFAULT '8000',
  `interval` int(10) unsigned NOT NULL DEFAULT '100',
  `countdown` tinyint(1) unsigned NOT NULL DEFAULT '10',
  `maxshake` int(10) unsigned NOT NULL DEFAULT '100',
  `maxwinner` int(10) unsigned NOT NULL DEFAULT '1',
  `maxjoin` int(10) unsigned NOT NULL,
  `joinprobability` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未开始，1为进行中，2为已结束',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('shake_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shake_member',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shake_member',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('shake_member',  'shakecount')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD `shakecount` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shake_member',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD `remark` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shake_member',  'lastupdate')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD `lastupdate` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('shake_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0为不可摇奖，1为可摇奖';");
}
if(!pdo_fieldexists('shake_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('shake_member',  'idx_openid_replyid')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD KEY `idx_openid_replyid` (`openid`,`rid`);");
}
if(!pdo_indexexists('shake_member',  'idx_replyid')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD KEY `idx_replyid` (`rid`);");
}
if(!pdo_indexexists('shake_member',  'idx_shakecount')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD KEY `idx_shakecount` (`rid`,`shakecount`);");
}
if(!pdo_indexexists('shake_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('shake_member')." ADD KEY `createtime` (`createtime`);");
}
if(!pdo_fieldexists('shake_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('shake_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shake_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shake_reply',  'cover')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `cover` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shake_reply',  'qrcode')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `qrcode` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('shake_reply',  'background')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `background` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shake_reply',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `logo` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shake_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `description` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('shake_reply',  'rule')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `rule` text NOT NULL;");
}
if(!pdo_fieldexists('shake_reply',  'speed')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `speed` int(10) unsigned NOT NULL DEFAULT '3000';");
}
if(!pdo_fieldexists('shake_reply',  'speedandroid')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `speedandroid` int(10) unsigned NOT NULL DEFAULT '8000';");
}
if(!pdo_fieldexists('shake_reply',  'interval')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `interval` int(10) unsigned NOT NULL DEFAULT '100';");
}
if(!pdo_fieldexists('shake_reply',  'countdown')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `countdown` tinyint(1) unsigned NOT NULL DEFAULT '10';");
}
if(!pdo_fieldexists('shake_reply',  'maxshake')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `maxshake` int(10) unsigned NOT NULL DEFAULT '100';");
}
if(!pdo_fieldexists('shake_reply',  'maxwinner')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `maxwinner` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('shake_reply',  'maxjoin')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `maxjoin` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shake_reply',  'joinprobability')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `joinprobability` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('shake_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未开始，1为进行中，2为已结束';");
}
if(!pdo_indexexists('shake_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('shake_reply')." ADD KEY `idx_rid` (`rid`);");
}

?>