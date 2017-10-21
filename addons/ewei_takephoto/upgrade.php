<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_ewei_takephoto_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `bgimg` varchar(255) DEFAULT '',
  `helpimg` varchar(255) DEFAULT '',
  `shareimg` varchar(255) DEFAULT '',
  `titleimg` varchar(255) DEFAULT '',
  `cameraimg` varchar(255) DEFAULT '',
  `numberimg` varchar(255) DEFAULT '',
  `items` text COMMENT '物品',
  `follow_url` varchar(1000) DEFAULT '',
  `follow_button` varchar(1000) DEFAULT '',
  `share_url` varchar(1000) DEFAULT '',
  `viewnum` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `share_desc` varchar(500) DEFAULT '',
  `share_icon` varchar(255) DEFAULT '',
  `share_title` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('ewei_takephoto_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `description` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `starttime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `endtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'bgimg')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `bgimg` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'helpimg')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `helpimg` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'shareimg')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `shareimg` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'titleimg')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `titleimg` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'cameraimg')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `cameraimg` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'numberimg')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `numberimg` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'items')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `items` text COMMENT '物品';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'follow_url')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `follow_url` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'follow_button')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `follow_button` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'share_url')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `share_url` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `viewnum` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'share_desc')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `share_desc` varchar(500) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `share_icon` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `share_title` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_takephoto_reply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('ewei_takephoto_reply',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('ewei_takephoto_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('ewei_takephoto_reply',  'idx_status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_takephoto_reply')." ADD KEY `idx_status` (`status`);");
}

?>