<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_qyhb_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `user_id` varchar(100) NOT NULL COMMENT '用户id',
  `user_name` varchar(100) DEFAULT NULL COMMENT '用户昵称',
  `user_image` varchar(200) DEFAULT NULL COMMENT '用户头像',
  `ipaddr` varchar(30) DEFAULT NULL COMMENT '用户ip地址',
  `status` varchar(1) DEFAULT NULL COMMENT '是否发放红包',
  `num` int(11) DEFAULT '0',
  `referee` varchar(100) DEFAULT NULL COMMENT '推荐人id',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('qyhb_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('qyhb_user',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('qyhb_user',  'user_id')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `user_id` varchar(100) NOT NULL COMMENT '用户id';");
}
if(!pdo_fieldexists('qyhb_user',  'user_name')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `user_name` varchar(100) DEFAULT NULL COMMENT '用户昵称';");
}
if(!pdo_fieldexists('qyhb_user',  'user_image')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `user_image` varchar(200) DEFAULT NULL COMMENT '用户头像';");
}
if(!pdo_fieldexists('qyhb_user',  'ipaddr')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `ipaddr` varchar(30) DEFAULT NULL COMMENT '用户ip地址';");
}
if(!pdo_fieldexists('qyhb_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `status` varchar(1) DEFAULT NULL COMMENT '是否发放红包';");
}
if(!pdo_fieldexists('qyhb_user',  'num')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `num` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('qyhb_user',  'referee')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `referee` varchar(100) DEFAULT NULL COMMENT '推荐人id';");
}
if(!pdo_fieldexists('qyhb_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('qyhb_user')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}

?>