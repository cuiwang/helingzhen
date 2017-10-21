<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_hx_dialect_questions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) NOT NULL,
  `title` varchar(50) NOT NULL,
  `audio` varchar(200) NOT NULL,
  `a` varchar(50) NOT NULL,
  `b` varchar(50) NOT NULL,
  `c` varchar(50) NOT NULL,
  `d` varchar(50) NOT NULL,
  `answer` varchar(2) NOT NULL,
  `mark` int(10) NOT NULL,
  `hard` varchar(5) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `remark` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_hx_dialect_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `uniacid` int(10) NOT NULL,
  `r_name` varchar(200) NOT NULL,
  `r_title` varchar(200) NOT NULL,
  `thumb` varchar(1000) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `s_title` varchar(200) NOT NULL,
  `s_icon` varchar(1000) NOT NULL,
  `s_des` varchar(1000) NOT NULL,
  `s_cancel` varchar(200) NOT NULL,
  `s_share` varchar(2000) NOT NULL,
  `s_sucai` varchar(2000) NOT NULL,
  `py_1` varchar(200) NOT NULL,
  `py_2` varchar(200) NOT NULL,
  `py_3` varchar(200) NOT NULL,
  `py_4` varchar(200) NOT NULL,
  `py_5` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('hx_dialect_questions',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'title')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'audio')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `audio` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'a')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `a` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'b')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `b` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'c')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `c` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'd')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `d` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'answer')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `answer` varchar(2) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'mark')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `mark` int(10) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'hard')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `hard` varchar(5) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'status')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_questions',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_questions')." ADD `remark` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `rid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `uniacid` int(10) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'r_name')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `r_name` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'r_title')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `r_title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `thumb` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'num')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `num` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  's_title')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `s_title` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  's_icon')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `s_icon` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  's_des')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `s_des` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  's_cancel')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `s_cancel` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  's_share')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `s_share` varchar(2000) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  's_sucai')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `s_sucai` varchar(2000) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'py_1')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `py_1` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'py_2')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `py_2` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'py_3')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `py_3` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'py_4')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `py_4` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('hx_dialect_reply',  'py_5')) {
	pdo_query("ALTER TABLE ".tablename('hx_dialect_reply')." ADD `py_5` varchar(200) NOT NULL;");
}

?>