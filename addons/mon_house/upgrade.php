<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_mon_house` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `news_title` varchar(255) NOT NULL,
  `lpaddress` varchar(255) NOT NULL,
  `price` int(10) NOT NULL,
  `sltel` varchar(25) NOT NULL,
  `zxtel` varchar(25) NOT NULL,
  `news_icon` varchar(255) NOT NULL,
  `news_content` varchar(500) NOT NULL,
  `title` varchar(100) NOT NULL,
  `kptime` int(10) DEFAULT '0',
  `rztime` int(10) DEFAULT '0',
  `kfs` varchar(100) NOT NULL,
  `cover_img` varchar(200) NOT NULL,
  `overview_img` varchar(200) NOT NULL,
  `intro_img` varchar(200) NOT NULL,
  `intro` varchar(1000) DEFAULT NULL,
  `order_title` varchar(50) NOT NULL,
  `order_remark` varchar(100) NOT NULL,
  `share_icon` varchar(200) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_content` varchar(500) NOT NULL,
  `dt_img` varchar(300) DEFAULT NULL,
  `dt_intro` varchar(2000) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_house_agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `gname` varchar(255) NOT NULL,
  `headimgurl` varchar(255) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `workyear` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_house_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `iname` varchar(255) NOT NULL,
  `icontent` varchar(255) NOT NULL,
  `sort` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_house_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `uname` varchar(20) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  `tel` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_house_pic_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT '0',
  `pre_img` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_house_pic_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `pname` varchar(255) NOT NULL,
  `sort` int(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_house_timage` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `tid` int(11) DEFAULT '0',
  `pre_img` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_house_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `tname` varchar(255) NOT NULL,
  `sort` int(3) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_hid` (`hid`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('mon_house',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_house',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house',  'news_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `news_title` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'lpaddress')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `lpaddress` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'price')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `price` int(10) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'sltel')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `sltel` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'zxtel')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `zxtel` varchar(25) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'news_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `news_icon` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'news_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `news_content` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'title')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'kptime')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `kptime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house',  'rztime')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `rztime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house',  'kfs')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `kfs` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'cover_img')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `cover_img` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'overview_img')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `overview_img` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'intro_img')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `intro_img` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `intro` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_house',  'order_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `order_title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'order_remark')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `order_remark` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'share_icon')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `share_icon` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'share_title')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `share_title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'share_content')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `share_content` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('mon_house',  'dt_img')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `dt_img` varchar(300) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_house',  'dt_intro')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `dt_intro` varchar(2000) DEFAULT NULL;");
}
if(!pdo_fieldexists('mon_house',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_indexexists('mon_house',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('mon_house',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('mon_house_agent',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_agent')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_house_agent',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_agent')." ADD `hid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_agent',  'gname')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_agent')." ADD `gname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_agent',  'headimgurl')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_agent')." ADD `headimgurl` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_agent',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_agent')." ADD `tel` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_agent',  'workyear')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_agent')." ADD `workyear` int(3) DEFAULT '0';");
}
if(!pdo_indexexists('mon_house_agent',  'indx_hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_agent')." ADD KEY `indx_hid` (`hid`);");
}
if(!pdo_fieldexists('mon_house_item',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_item')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_house_item',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_item')." ADD `hid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_item',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_item')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_item',  'iname')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_item')." ADD `iname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_item',  'icontent')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_item')." ADD `icontent` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_item',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_item')." ADD `sort` int(3) DEFAULT '0';");
}
if(!pdo_indexexists('mon_house_item',  'indx_hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_item')." ADD KEY `indx_hid` (`hid`);");
}
if(!pdo_fieldexists('mon_house_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_house_order',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_order')." ADD `hid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_order',  'uname')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_order')." ADD `uname` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_order')." ADD `createtime` int(10) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_order',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_order')." ADD `tel` varchar(20) NOT NULL;");
}
if(!pdo_indexexists('mon_house_order',  'indx_hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_order')." ADD KEY `indx_hid` (`hid`);");
}
if(!pdo_fieldexists('mon_house_pic_image',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_image')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_house_pic_image',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_image')." ADD `hid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_pic_image',  'pid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_image')." ADD `pid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_pic_image',  'pre_img')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_image')." ADD `pre_img` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_pic_image',  'img')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_image')." ADD `img` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_pic_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_type')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_house_pic_type',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_type')." ADD `hid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_pic_type',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_type')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_pic_type',  'pname')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_type')." ADD `pname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_pic_type',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_pic_type')." ADD `sort` int(3) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_timage',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_timage')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_house_timage',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_timage')." ADD `hid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_timage',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_timage')." ADD `tid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_timage',  'pre_img')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_timage')." ADD `pre_img` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_timage',  'img')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_timage')." ADD `img` varchar(255) NOT NULL;");
}
if(!pdo_indexexists('mon_house_timage',  'indx_hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_timage')." ADD KEY `indx_hid` (`hid`);");
}
if(!pdo_indexexists('mon_house_timage',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_timage')." ADD KEY `tid` (`tid`);");
}
if(!pdo_fieldexists('mon_house_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_type')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('mon_house_type',  'hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_type')." ADD `hid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_type',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_type')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('mon_house_type',  'tname')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_type')." ADD `tname` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('mon_house_type',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_type')." ADD `sort` int(3) DEFAULT '0';");
}
if(!pdo_indexexists('mon_house_type',  'indx_hid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_type')." ADD KEY `indx_hid` (`hid`);");
}
if(!pdo_indexexists('mon_house_type',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('mon_house_type')." ADD KEY `indx_rid` (`rid`);");
}

?>