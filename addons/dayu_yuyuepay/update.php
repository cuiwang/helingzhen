<?php
$sql = "
CREATE TABLE IF NOT EXISTS `ims_dayu_yuyuepay_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_query($sql);
if(!pdo_fieldexists('dayu_yuyuepay', 'code')) {
	pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')."  ADD `code` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'is_time')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD  `is_time` tinyint(1) DEFAULT '0';");
}

if(!pdo_fieldexists('dayu_yuyuepay', 'upsize')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `upsize` int(5) NOT NULL DEFAULT '640';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'icon')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `icon` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'is_list')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `is_list` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'subtitle')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `subtitle` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'is_num')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `is_num` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'numname')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `numname` varchar(50) NOT NULL DEFAULT '数量';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'srvtime')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `srvtime` text NOT NULL;");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'day')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `day` int(10) unsigned NOT NULL DEFAULT '5';");
}

if(!pdo_fieldexists('dayu_yuyuepay_info', 'address')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay_info')." ADD   `address` varchar(1024) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('dayu_yuyuepay_info', 'num')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay_info')." ADD   `num` int(3) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('dayu_yuyuepay_info', 'restime')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay_info')." ADD   `restime` varchar(50) NOT NULL;");
}
if(pdo_fieldexists('dayu_yuyuepay_info', 'price')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay_info')." CHANGE   `price` `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(pdo_fieldexists('dayu_yuyuepay_xiangmu', 'price')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay_xiangmu')." CHANGE   `price` `price` decimal(10,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'timelist')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD    `timelist` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'mbgroup')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD     `mbgroup` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'is_addr')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD     `is_addr` tinyint(1) DEFAULT '1';");
}

if(!pdo_fieldexists('dayu_yuyuepay', 'state1')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `state1` varchar(20) NOT NULL DEFAULT '待受理';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'state2')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `state2` varchar(20) NOT NULL DEFAULT '受理中';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'state3')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `state3` varchar(20) NOT NULL DEFAULT '已完成';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'state4')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `state4` varchar(20) NOT NULL DEFAULT '拒绝受理';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'state5')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `state5` varchar(20) NOT NULL DEFAULT '已取消';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'isdel')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `isdel` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'outlink')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `outlink` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'isthumb')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `isthumb` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'isreplace')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD   `isreplace` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('dayu_yuyuepay', 'image')) {
    pdo_query("ALTER TABLE ".tablename('dayu_yuyuepay')." ADD     `image` varchar(250) NOT NULL DEFAULT '';");
}