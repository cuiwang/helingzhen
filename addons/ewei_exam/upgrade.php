<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_ewei_exam_course` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `pcate` int(11) DEFAULT '0',
  `ccate` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '' COMMENT '课程标题',
  `ctype` int(11) DEFAULT '0' COMMENT '0 时间限制 1 人数限制',
  `starttime` int(11) DEFAULT '0' COMMENT '报名开始时间',
  `endtime` int(11) DEFAULT '0' COMMENT '报名截止时间',
  `ctotal` int(11) DEFAULT '0' COMMENT '报名人数限制',
  `description` text,
  `content` text,
  `thumb` varchar(255) DEFAULT '',
  `viewnum` int(11) DEFAULT '0' COMMENT '访问人数',
  `fansnum` int(11) DEFAULT '0' COMMENT '报名人数',
  `teachers` text COMMENT '授课讲师',
  `coursetime` int(11) DEFAULT '0' COMMENT '开始时间',
  `times` int(11) DEFAULT '0' COMMENT '授课时长',
  `week` int(11) DEFAULT '0' COMMENT '第几期',
  `address` text,
  `location_p` varchar(255) DEFAULT NULL,
  `location_c` varchar(255) DEFAULT NULL,
  `location_a` varchar(255) DEFAULT NULL,
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0' COMMENT '题目排序',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_pcate` (`ccate`),
  KEY `idx_ccate` (`ccate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_course_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `parentid` int(11) DEFAULT '0',
  `cname` varchar(255) DEFAULT '',
  `description` text COMMENT '描述',
  `displayorder` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_course_reserve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `courseid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0' COMMENT '用时',
  `createtime` int(11) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `username` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `email` varchar(255) DEFAULT '',
  `ordersn` varchar(255) DEFAULT '',
  `msg` text,
  `mngtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_memberid` (`memberid`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`courseid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '',
  `userid` varchar(255) DEFAULT '',
  `username` varchar(255) DEFAULT '',
  `mobile` varchar(255) DEFAULT '',
  `email` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_paper` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `pcate` int(11) DEFAULT '0',
  `ccate` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '' COMMENT '试卷标题',
  `level` int(11) DEFAULT '0' COMMENT '难度',
  `score` int(11) DEFAULT '0' COMMENT '分值',
  `description` text,
  `thumb` varchar(255) DEFAULT '',
  `year` int(11) DEFAULT '0' COMMENT '年份',
  `viewnum` int(11) DEFAULT '0' COMMENT '访问人数',
  `fansnum` int(11) DEFAULT '0' COMMENT '考试人数',
  `times` int(11) DEFAULT '0' COMMENT '时间限制 0不限制',
  `types` varchar(5) DEFAULT NULL COMMENT '考题类型选择 例如 11111 包含5种题型',
  `avscore` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '平均分',
  `avtimes` int(11) NOT NULL DEFAULT '0' COMMENT '平均用时',
  `createtime` int(11) DEFAULT '0',
  `displayorder` int(11) DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '考题类型id',
  `status` tinyint(1) DEFAULT '0',
  `isfull` tinyint(1) NOT NULL DEFAULT '0' COMMENT '试题是否完整1完整0不完整',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_pcate` (`ccate`),
  KEY `idx_ccate` (`ccate`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_paper_category` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `parentid` int(11) DEFAULT '0',
  `cname` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `description` text COMMENT '描述',
  `status` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_parentid` (`parentid`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_paper_member_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `paperid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `recordid` int(11) DEFAULT '0' COMMENT '学员考试记录id',
  `questionid` int(11) NOT NULL DEFAULT '0',
  `answer` text,
  `times` int(11) DEFAULT '0' COMMENT '单题用时',
  `createtime` int(11) DEFAULT '0',
  `isright` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否回答正确',
  `type` int(11) DEFAULT '0' COMMENT '1 判断 2单选 3多选 4 填空  5 解答',
  `pageid` int(11) NOT NULL DEFAULT '0' COMMENT '顺序id',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`),
  KEY `idx_memberid` (`memberid`),
  KEY `idx_paperid` (`paperid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_paper_member_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `paperid` int(11) DEFAULT '0',
  `memberid` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0' COMMENT '用时',
  `countdown` int(11) DEFAULT '0' COMMENT '倒计时',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '得分',
  `did` int(11) DEFAULT '0' COMMENT '是否完成考试',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_memberid` (`memberid`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`paperid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_paper_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `questionid` int(11) DEFAULT '0' COMMENT '题ID',
  `displayorder` int(11) DEFAULT '0' COMMENT '题目排序',
  `paperid` bigint(20) NOT NULL DEFAULT '0' COMMENT '试卷ID',
  PRIMARY KEY (`id`),
  KEY `idx_questionid` (`questionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_paper_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `title` varchar(255) DEFAULT '' COMMENT '试卷标题',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '分值',
  `types` text COMMENT '试题类型设置 包含试题类型 试题分数',
  `times` int(11) NOT NULL DEFAULT '0' COMMENT '考试时间',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_pool` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `description` text COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_question` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `poolid` int(11) DEFAULT '0' COMMENT '题库ID',
  `paperid1` int(11) DEFAULT '0' COMMENT '题库ID',
  `type` int(11) DEFAULT '0' COMMENT '1 判断 2单选 3多选 4 填空  5 解答',
  `level` int(11) DEFAULT '0' COMMENT '难度',
  `question` text COMMENT '问题',
  `thumb` varchar(255) DEFAULT '' COMMENT '问题图片',
  `answer` text COMMENT '答案',
  `isimg` tinyint(1) DEFAULT '0' COMMENT '答案是否包含图片',
  `explain` text COMMENT '讲解',
  `fansnum` int(11) DEFAULT '0' COMMENT '多少人做过',
  `correctnum` int(11) DEFAULT '0' COMMENT '多少人正确',
  `items` text,
  `img_items` text,
  PRIMARY KEY (`id`),
  KEY `idx_poolid` (`poolid`),
  KEY `idx_type` (`type`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`paperid1`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `weid` int(11) DEFAULT '0' COMMENT '所属帐号',
  `paperid` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`),
  KEY `idx_weid` (`weid`),
  KEY `idx_paperid` (`paperid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_ewei_exam_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `classopen` int(11) DEFAULT '1',
  `login_flag` tinyint(1) DEFAULT '0' COMMENT '是否开启登录',
  `about` text COMMENT '帮助',
  PRIMARY KEY (`id`),
  KEY `idx_weid` (`weid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists('ewei_exam_course',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `id` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_course',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `weid` int(11) DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('ewei_exam_course',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `pcate` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `ccate` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course',  'title')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `title` varchar(255) DEFAULT '' COMMENT '课程标题';");
}
if(!pdo_fieldexists('ewei_exam_course',  'ctype')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `ctype` int(11) DEFAULT '0' COMMENT '0 时间限制 1 人数限制';");
}
if(!pdo_fieldexists('ewei_exam_course',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `starttime` int(11) DEFAULT '0' COMMENT '报名开始时间';");
}
if(!pdo_fieldexists('ewei_exam_course',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `endtime` int(11) DEFAULT '0' COMMENT '报名截止时间';");
}
if(!pdo_fieldexists('ewei_exam_course',  'ctotal')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `ctotal` int(11) DEFAULT '0' COMMENT '报名人数限制';");
}
if(!pdo_fieldexists('ewei_exam_course',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `description` text;");
}
if(!pdo_fieldexists('ewei_exam_course',  'content')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `content` text;");
}
if(!pdo_fieldexists('ewei_exam_course',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_course',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `viewnum` int(11) DEFAULT '0' COMMENT '访问人数';");
}
if(!pdo_fieldexists('ewei_exam_course',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `fansnum` int(11) DEFAULT '0' COMMENT '报名人数';");
}
if(!pdo_fieldexists('ewei_exam_course',  'teachers')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `teachers` text COMMENT '授课讲师';");
}
if(!pdo_fieldexists('ewei_exam_course',  'coursetime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `coursetime` int(11) DEFAULT '0' COMMENT '开始时间';");
}
if(!pdo_fieldexists('ewei_exam_course',  'times')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `times` int(11) DEFAULT '0' COMMENT '授课时长';");
}
if(!pdo_fieldexists('ewei_exam_course',  'week')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `week` int(11) DEFAULT '0' COMMENT '第几期';");
}
if(!pdo_fieldexists('ewei_exam_course',  'address')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `address` text;");
}
if(!pdo_fieldexists('ewei_exam_course',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `location_p` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_exam_course',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `location_c` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_exam_course',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `location_a` varchar(255) DEFAULT NULL;");
}
if(!pdo_fieldexists('ewei_exam_course',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000';");
}
if(!pdo_fieldexists('ewei_exam_course',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000';");
}
if(!pdo_fieldexists('ewei_exam_course',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD `displayorder` int(11) DEFAULT '0' COMMENT '题目排序';");
}
if(!pdo_indexexists('ewei_exam_course',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('ewei_exam_course',  'idx_pcate')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD KEY `idx_pcate` (`ccate`);");
}
if(!pdo_indexexists('ewei_exam_course',  'idx_ccate')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course')." ADD KEY `idx_ccate` (`ccate`);");
}
if(!pdo_fieldexists('ewei_exam_course_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD `id` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_course_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD `weid` int(11) DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('ewei_exam_course_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD `parentid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course_category',  'cname')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD `cname` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_course_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD `description` text COMMENT '描述';");
}
if(!pdo_fieldexists('ewei_exam_course_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course_category',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('ewei_exam_course_category',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('ewei_exam_course_category',  'idx_parentid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD KEY `idx_parentid` (`parentid`);");
}
if(!pdo_indexexists('ewei_exam_course_category',  'idx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_category')." ADD KEY `idx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'courseid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `courseid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'memberid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `memberid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'times')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `times` int(11) DEFAULT '0' COMMENT '用时';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'username')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `username` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `mobile` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'email')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `email` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `ordersn` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'msg')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `msg` text;");
}
if(!pdo_fieldexists('ewei_exam_course_reserve',  'mngtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD `mngtime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('ewei_exam_course_reserve',  'idx_memberid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD KEY `idx_memberid` (`memberid`);");
}
if(!pdo_indexexists('ewei_exam_course_reserve',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('ewei_exam_course_reserve',  'idx_paperid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_course_reserve')." ADD KEY `idx_paperid` (`courseid`);");
}
if(!pdo_fieldexists('ewei_exam_member',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_member',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_member',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD `from_user` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_member',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD `userid` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_member',  'username')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD `username` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_member',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD `mobile` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_member',  'email')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD `email` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_member',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_member',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态';");
}
if(!pdo_indexexists('ewei_exam_member',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_member')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_fieldexists('ewei_exam_paper',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `id` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_paper',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `weid` int(11) DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'pcate')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `pcate` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'ccate')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `ccate` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'title')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `title` varchar(255) DEFAULT '' COMMENT '试卷标题';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'level')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `level` int(11) DEFAULT '0' COMMENT '难度';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'score')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `score` int(11) DEFAULT '0' COMMENT '分值';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `description` text;");
}
if(!pdo_fieldexists('ewei_exam_paper',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'year')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `year` int(11) DEFAULT '0' COMMENT '年份';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `viewnum` int(11) DEFAULT '0' COMMENT '访问人数';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `fansnum` int(11) DEFAULT '0' COMMENT '考试人数';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'times')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `times` int(11) DEFAULT '0' COMMENT '时间限制 0不限制';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'types')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `types` varchar(5) DEFAULT NULL COMMENT '考题类型选择 例如 11111 包含5种题型';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'avscore')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `avscore` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '平均分';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'avtimes')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `avtimes` int(11) NOT NULL DEFAULT '0' COMMENT '平均用时';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `tid` int(11) NOT NULL DEFAULT '0' COMMENT '考题类型id';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `status` tinyint(1) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper',  'isfull')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD `isfull` tinyint(1) NOT NULL DEFAULT '0' COMMENT '试题是否完整1完整0不完整';");
}
if(!pdo_indexexists('ewei_exam_paper',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('ewei_exam_paper',  'idx_pcate')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD KEY `idx_pcate` (`ccate`);");
}
if(!pdo_indexexists('ewei_exam_paper',  'idx_ccate')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD KEY `idx_ccate` (`ccate`);");
}
if(!pdo_indexexists('ewei_exam_paper',  'idx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper')." ADD KEY `idx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('ewei_exam_paper_category',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD `id` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_paper_category',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD `weid` int(11) DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('ewei_exam_paper_category',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD `parentid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_category',  'cname')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD `cname` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('ewei_exam_paper_category',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_category',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD `description` text COMMENT '描述';");
}
if(!pdo_fieldexists('ewei_exam_paper_category',  'status')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD `status` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('ewei_exam_paper_category',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('ewei_exam_paper_category',  'idx_parentid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD KEY `idx_parentid` (`parentid`);");
}
if(!pdo_indexexists('ewei_exam_paper_category',  'idx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_category')." ADD KEY `idx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'paperid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `paperid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'memberid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `memberid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'recordid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `recordid` int(11) DEFAULT '0' COMMENT '学员考试记录id';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'questionid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `questionid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'answer')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `answer` text;");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'times')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `times` int(11) DEFAULT '0' COMMENT '单题用时';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'isright')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `isright` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否回答正确';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'type')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `type` int(11) DEFAULT '0' COMMENT '1 判断 2单选 3多选 4 填空  5 解答';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_data',  'pageid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD `pageid` int(11) NOT NULL DEFAULT '0' COMMENT '顺序id';");
}
if(!pdo_indexexists('ewei_exam_paper_member_data',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('ewei_exam_paper_member_data',  'idx_memberid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD KEY `idx_memberid` (`memberid`);");
}
if(!pdo_indexexists('ewei_exam_paper_member_data',  'idx_paperid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_data')." ADD KEY `idx_paperid` (`paperid`);");
}
if(!pdo_fieldexists('ewei_exam_paper_member_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_paper_member_record',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_record',  'paperid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD `paperid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_record',  'memberid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD `memberid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_record',  'times')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD `times` int(11) DEFAULT '0' COMMENT '用时';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_record',  'countdown')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD `countdown` int(11) DEFAULT '0' COMMENT '倒计时';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_record',  'score')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD `score` decimal(10,2) DEFAULT '0.00' COMMENT '得分';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_record',  'did')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD `did` int(11) DEFAULT '0' COMMENT '是否完成考试';");
}
if(!pdo_fieldexists('ewei_exam_paper_member_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('ewei_exam_paper_member_record',  'idx_memberid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD KEY `idx_memberid` (`memberid`);");
}
if(!pdo_indexexists('ewei_exam_paper_member_record',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('ewei_exam_paper_member_record',  'idx_paperid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_member_record')." ADD KEY `idx_paperid` (`paperid`);");
}
if(!pdo_fieldexists('ewei_exam_paper_question',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_question')." ADD `id` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_paper_question',  'questionid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_question')." ADD `questionid` int(11) DEFAULT '0' COMMENT '题ID';");
}
if(!pdo_fieldexists('ewei_exam_paper_question',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_question')." ADD `displayorder` int(11) DEFAULT '0' COMMENT '题目排序';");
}
if(!pdo_fieldexists('ewei_exam_paper_question',  'paperid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_question')." ADD `paperid` bigint(20) NOT NULL DEFAULT '0' COMMENT '试卷ID';");
}
if(!pdo_indexexists('ewei_exam_paper_question',  'idx_questionid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_question')." ADD KEY `idx_questionid` (`questionid`);");
}
if(!pdo_fieldexists('ewei_exam_paper_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_type')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_paper_type',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_type')." ADD `weid` int(11) DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('ewei_exam_paper_type',  'title')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_type')." ADD `title` varchar(255) DEFAULT '' COMMENT '试卷标题';");
}
if(!pdo_fieldexists('ewei_exam_paper_type',  'score')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_type')." ADD `score` decimal(10,2) DEFAULT '0.00' COMMENT '分值';");
}
if(!pdo_fieldexists('ewei_exam_paper_type',  'types')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_type')." ADD `types` text COMMENT '试题类型设置 包含试题类型 试题分数';");
}
if(!pdo_fieldexists('ewei_exam_paper_type',  'times')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_type')." ADD `times` int(11) NOT NULL DEFAULT '0' COMMENT '考试时间';");
}
if(!pdo_indexexists('ewei_exam_paper_type',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_paper_type')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_fieldexists('ewei_exam_pool',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_pool')." ADD `id` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_pool',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_pool')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_pool',  'title')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_pool')." ADD `title` varchar(255) DEFAULT '' COMMENT '标题';");
}
if(!pdo_fieldexists('ewei_exam_pool',  'description')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_pool')." ADD `description` text COMMENT '描述';");
}
if(!pdo_indexexists('ewei_exam_pool',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_pool')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_fieldexists('ewei_exam_question',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `id` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_question',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_question',  'poolid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `poolid` int(11) DEFAULT '0' COMMENT '题库ID';");
}
if(!pdo_fieldexists('ewei_exam_question',  'paperid1')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `paperid1` int(11) DEFAULT '0' COMMENT '题库ID';");
}
if(!pdo_fieldexists('ewei_exam_question',  'type')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `type` int(11) DEFAULT '0' COMMENT '1 判断 2单选 3多选 4 填空  5 解答';");
}
if(!pdo_fieldexists('ewei_exam_question',  'level')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `level` int(11) DEFAULT '0' COMMENT '难度';");
}
if(!pdo_fieldexists('ewei_exam_question',  'question')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `question` text COMMENT '问题';");
}
if(!pdo_fieldexists('ewei_exam_question',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `thumb` varchar(255) DEFAULT '' COMMENT '问题图片';");
}
if(!pdo_fieldexists('ewei_exam_question',  'answer')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `answer` text COMMENT '答案';");
}
if(!pdo_fieldexists('ewei_exam_question',  'isimg')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `isimg` tinyint(1) DEFAULT '0' COMMENT '答案是否包含图片';");
}
if(!pdo_fieldexists('ewei_exam_question',  'explain')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `explain` text COMMENT '讲解';");
}
if(!pdo_fieldexists('ewei_exam_question',  'fansnum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `fansnum` int(11) DEFAULT '0' COMMENT '多少人做过';");
}
if(!pdo_fieldexists('ewei_exam_question',  'correctnum')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `correctnum` int(11) DEFAULT '0' COMMENT '多少人正确';");
}
if(!pdo_fieldexists('ewei_exam_question',  'items')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `items` text;");
}
if(!pdo_fieldexists('ewei_exam_question',  'img_items')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD `img_items` text;");
}
if(!pdo_indexexists('ewei_exam_question',  'idx_poolid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD KEY `idx_poolid` (`poolid`);");
}
if(!pdo_indexexists('ewei_exam_question',  'idx_type')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD KEY `idx_type` (`type`);");
}
if(!pdo_indexexists('ewei_exam_question',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('ewei_exam_question',  'idx_paperid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_question')." ADD KEY `idx_paperid` (`paperid1`);");
}
if(!pdo_fieldexists('ewei_exam_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_reply')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_reply')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_reply')." ADD `weid` int(11) DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('ewei_exam_reply',  'paperid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_reply')." ADD `paperid` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('ewei_exam_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_reply')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('ewei_exam_reply',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_reply')." ADD KEY `idx_weid` (`weid`);");
}
if(!pdo_indexexists('ewei_exam_reply',  'idx_paperid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_reply')." ADD KEY `idx_paperid` (`paperid`);");
}
if(!pdo_fieldexists('ewei_exam_sysset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_sysset')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('ewei_exam_sysset',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_sysset')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('ewei_exam_sysset',  'classopen')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_sysset')." ADD `classopen` int(11) DEFAULT '1';");
}
if(!pdo_fieldexists('ewei_exam_sysset',  'login_flag')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_sysset')." ADD `login_flag` tinyint(1) DEFAULT '0' COMMENT '是否开启登录';");
}
if(!pdo_fieldexists('ewei_exam_sysset',  'about')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_sysset')." ADD `about` text COMMENT '帮助';");
}
if(!pdo_indexexists('ewei_exam_sysset',  'idx_weid')) {
	pdo_query("ALTER TABLE ".tablename('ewei_exam_sysset')." ADD KEY `idx_weid` (`weid`);");
}

?>