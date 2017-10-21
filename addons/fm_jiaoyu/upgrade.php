<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_wx_school_allcamera` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `kcid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '画面名称',
  `conet` text COMMENT '说明',
  `videopic` varchar(1000) NOT NULL DEFAULT '' COMMENT '监控地址',
  `videourl` varchar(1000) NOT NULL DEFAULT '' COMMENT '监控地址',
  `starttime` varchar(50) NOT NULL,
  `endtime` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `allowpy` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1允许2拒绝',
  `videotype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1公共2指定班级',
  `bj_id` text COMMENT '关联班级组',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1监控2课程直播',
  `click` int(10) unsigned NOT NULL COMMENT '查看量',
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_area` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '区域名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `ssort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '显示状态',
  `type` char(20) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL,
  `schoolid` int(11) DEFAULT '0',
  `bannername` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `begintime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `leixing` int(1) NOT NULL DEFAULT '0' COMMENT '0学校,1平台',
  `arr` text COMMENT '列表信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_bjq` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID',
  `content` text NOT NULL COMMENT '详细内容或评价',
  `uid` int(10) unsigned NOT NULL COMMENT '发布者UID',
  `weiyiqibj` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `weixin` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `bj_id1` int(10) unsigned NOT NULL COMMENT '班级ID1',
  `bj_id2` int(10) unsigned NOT NULL COMMENT '班级ID2',
  `bj_id3` int(10) unsigned NOT NULL COMMENT '班级ID3',
  `sherid` int(10) unsigned NOT NULL COMMENT '所属图文id',
  `shername` varchar(50) DEFAULT '' COMMENT '分享者名字',
  `openid` varchar(30) NOT NULL COMMENT '帖子所属openid',
  `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型0为班级圈1为评论',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `weizanbj` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `msgtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1文字图片2语音3视频',
  `video` varchar(1000) DEFAULT '',
  `videoimg` varchar(1000) DEFAULT '',
  `plid` int(10) NOT NULL DEFAULT '0',
  `is_private` varchar(3) NOT NULL DEFAULT 'N' COMMENT '禁止评论',
  `audio` varchar(1000) DEFAULT '' COMMENT '音频地址',
  `audiotime` int(10) NOT NULL DEFAULT '0' COMMENT '音频时间',
  `link` varchar(1000) DEFAULT '' COMMENT '外链地址',
  `linkdesc` varchar(200) DEFAULT '' COMMENT '外链标题',
  `hftoname` varchar(100) DEFAULT '',
  `www` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_camerapl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `carmeraid` int(10) unsigned NOT NULL COMMENT '画面ID',
  `userid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `bj_id` int(10) unsigned NOT NULL COMMENT '班级ID',
  `conet` text COMMENT '内容',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1点赞2评论',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_checklog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `macid` int(10) unsigned NOT NULL,
  `cardid` varchar(200) NOT NULL DEFAULT '' COMMENT '卡号',
  `sid` int(10) unsigned NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `bj_id` int(10) unsigned NOT NULL,
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lon` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `temperature` varchar(10) DEFAULT '',
  `pic` varchar(255) DEFAULT '' COMMENT '图片',
  `type` varchar(50) DEFAULT '' COMMENT '进校类型',
  `leixing` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1进校2离校3迟到4早退',
  `pard` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他11老师',
  `createtime` int(10) unsigned NOT NULL,
  `isread` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1已读2未读',
  `checktype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1刷卡2微信',
  `isconfirm` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1确认2拒绝',
  `qdtid` int(11) NOT NULL COMMENT '代签userid',
  `pic2` varchar(255) DEFAULT '' COMMENT '图片2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_checkmac` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `macname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `macid` varchar(200) NOT NULL DEFAULT '' COMMENT '设备编号',
  `banner` varchar(2000) DEFAULT '',
  `is_on` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1启用2不启用',
  `createtime` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1进校2离校',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_classify` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id',
  `sname` varchar(50) NOT NULL,
  `ssort` int(5) NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `type` char(20) NOT NULL,
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `erwei` varchar(200) NOT NULL DEFAULT '' COMMENT '群二维码',
  `qun` varchar(200) NOT NULL DEFAULT '' COMMENT 'Q群链接',
  `tid` int(11) unsigned NOT NULL COMMENT '班级主任userid',
  `video` varchar(1000) NOT NULL DEFAULT '' COMMENT '教室监控地址',
  `video1` varchar(1000) NOT NULL DEFAULT '' COMMENT '教室监控地址1',
  `videostart` varchar(50) NOT NULL DEFAULT '',
  `videoend` varchar(50) NOT NULL DEFAULT '',
  `cost` varchar(50) NOT NULL DEFAULT '',
  `pname` varchar(50) NOT NULL DEFAULT '' COMMENT '称谓',
  `carmeraid` text COMMENT '说明',
  `videoclick` int(11) unsigned NOT NULL COMMENT '视频点击量',
  `allowpy` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1允许2拒绝',
  `icon` varchar(500) DEFAULT '' COMMENT '图标',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_cookbook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id',
  `weid` int(10) unsigned NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `begintime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `monday` text NOT NULL,
  `tuesday` text NOT NULL,
  `wednesday` text NOT NULL,
  `thursday` text NOT NULL,
  `friday` text NOT NULL,
  `saturday` text NOT NULL,
  `sunday` text NOT NULL,
  `ishow` int(1) NOT NULL DEFAULT '1' COMMENT '1:显示,2隐藏,默认1',
  `sort` int(11) NOT NULL DEFAULT '1',
  `type` varchar(15) NOT NULL DEFAULT '',
  `headpic` varchar(200) NOT NULL DEFAULT '',
  `infos` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_cost` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `cost` decimal(18,2) NOT NULL DEFAULT '0.00',
  `bj_id` text COMMENT '关联班级组',
  `name` varchar(100) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `description` text NOT NULL COMMENT '缴费说明',
  `about` int(10) unsigned NOT NULL,
  `displayorder` tinyint(3) unsigned NOT NULL,
  `is_sys` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1关联缴费，2不关联',
  `is_time` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1有时间限制，2不限制',
  `is_on` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1启用，2不启用',
  `createtime` int(10) unsigned NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `dataline` int(10) unsigned NOT NULL,
  `payweid` int(10) unsigned NOT NULL COMMENT '支付公众号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_coursetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id',
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `ishow` int(1) NOT NULL DEFAULT '1' COMMENT '1:显示,2隐藏,默认1',
  `sort` int(11) NOT NULL DEFAULT '1',
  `type` varchar(15) NOT NULL DEFAULT '',
  `headpic` varchar(200) NOT NULL DEFAULT '',
  `infos` varchar(500) NOT NULL,
  `xq_id` int(11) NOT NULL COMMENT '学期id',
  `bj_id` int(11) NOT NULL COMMENT '班级id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_dianzan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID',
  `uid` int(10) unsigned NOT NULL COMMENT '发布者UID',
  `sherid` int(10) unsigned NOT NULL COMMENT '所属图文id',
  `zname` varchar(50) DEFAULT '' COMMENT '点赞人名字',
  `order` int(10) unsigned NOT NULL COMMENT '排序',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '图片路径',
  `qxwh` varchar(30) NOT NULL DEFAULT '0' COMMENT '图片路径',
  `xbtx` varchar(30) NOT NULL DEFAULT '0' COMMENT '图片路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_email` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `bj_id` int(10) unsigned NOT NULL,
  `pard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他',
  `suggesd` varchar(1000) DEFAULT '',
  `emailid` int(10) unsigned NOT NULL,
  `isread` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_how` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_fans_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0',
  `count` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `group_desc` varchar(50) NOT NULL DEFAULT '',
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  `type` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '二维码状态',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生成时间',
  `is_zhu` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否本校主二维码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_icon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号',
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id',
  `name` varchar(50) NOT NULL COMMENT '按钮名称',
  `icon` varchar(1000) NOT NULL COMMENT '按钮图标',
  `url` varchar(1000) NOT NULL COMMENT '链接url',
  `place` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1首页菜单2底部菜单',
  `ssort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '显示状态',
  `beizhu` varchar(50) NOT NULL COMMENT '备注或小字',
  `color` varchar(50) NOT NULL COMMENT '颜色',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_idcard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `bj_id` int(10) unsigned NOT NULL,
  `pname` varchar(200) NOT NULL,
  `idcard` varchar(200) NOT NULL DEFAULT '' COMMENT '卡号',
  `orderid` int(10) unsigned NOT NULL,
  `spic` varchar(1000) NOT NULL,
  `tpic` varchar(1000) NOT NULL,
  `pard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他',
  `createtime` int(10) unsigned NOT NULL,
  `severend` int(10) unsigned NOT NULL,
  `is_on` int(1) NOT NULL DEFAULT '0' COMMENT '1:使用,2未用,默认0',
  `usertype` int(1) NOT NULL DEFAULT '0' COMMENT '1:老师,学生0',
  `is_frist` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:首次,2非首次',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_index` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `areaid` int(10) NOT NULL DEFAULT '0' COMMENT '区域id',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '学校logo',
  `thumb` varchar(200) NOT NULL DEFAULT '' COMMENT '图文消息缩略图',
  `info` varchar(1000) NOT NULL DEFAULT '' COMMENT '简短描述',
  `content` text NOT NULL COMMENT '简介',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省',
  `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市',
  `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区',
  `address` varchar(200) NOT NULL COMMENT '地址',
  `place` varchar(200) NOT NULL DEFAULT '',
  `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度',
  `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度',
  `password` varchar(20) NOT NULL DEFAULT '' COMMENT '登录密码',
  `hours` varchar(200) NOT NULL DEFAULT '' COMMENT '营业时间',
  `recharging_password` varchar(20) NOT NULL DEFAULT '' COMMENT '充值密码',
  `thumb_url` varchar(1000) DEFAULT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在手机端显示',
  `ssort` tinyint(3) NOT NULL DEFAULT '0',
  `is_sms` tinyint(1) NOT NULL DEFAULT '0',
  `dateline` int(10) NOT NULL DEFAULT '0',
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '搜索页显示',
  `gonggao` varchar(1000) NOT NULL DEFAULT '' COMMENT '通知',
  `is_rest` tinyint(1) NOT NULL DEFAULT '0',
  `typeid` int(10) NOT NULL DEFAULT '0' COMMENT '学校类型',
  `style1` varchar(200) NOT NULL DEFAULT '' COMMENT '模版名称',
  `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0显示1否',
  `qroce` varchar(200) NOT NULL DEFAULT '' COMMENT '二维码',
  `issale` tinyint(1) NOT NULL DEFAULT '5' COMMENT '5种状态',
  `zhaosheng` text NOT NULL COMMENT '招生简章',
  `uid` int(10) NOT NULL DEFAULT '0' COMMENT '账户ID',
  `style2` varchar(200) NOT NULL DEFAULT '' COMMENT '模版名称2',
  `style3` varchar(200) NOT NULL DEFAULT '' COMMENT '模版名称3',
  `is_sign` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1启用2不启用',
  `manger` varchar(200) NOT NULL DEFAULT '' COMMENT '信息审核员',
  `signset` varchar(200) NOT NULL COMMENT '报名设置',
  `is_cost` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用',
  `is_video` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用',
  `is_recordmac` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用',
  `spic` varchar(200) NOT NULL DEFAULT '' COMMENT '默认学生头像',
  `tpic` varchar(200) NOT NULL DEFAULT '' COMMENT '默认教师头像',
  `jxstart` varchar(50) DEFAULT '',
  `jxend` varchar(50) DEFAULT '',
  `lxstart` varchar(50) DEFAULT '',
  `lxend` varchar(50) DEFAULT '',
  `jxstart1` varchar(50) DEFAULT '',
  `jxend1` varchar(50) DEFAULT '',
  `lxstart1` varchar(50) DEFAULT '',
  `lxend1` varchar(50) DEFAULT '',
  `jxstart2` varchar(50) DEFAULT '',
  `jxend2` varchar(50) DEFAULT '',
  `lxstart2` varchar(50) DEFAULT '',
  `lxend2` varchar(50) DEFAULT '',
  `is_cardpay` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用',
  `is_cardlist` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用',
  `cardset` varchar(500) NOT NULL COMMENT '刷卡设置',
  `is_openht` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1启用2不启用独立后台',
  `is_showew` tinyint(1) NOT NULL DEFAULT '1' COMMENT '2显示1否',
  `wqgroupid` int(10) NOT NULL DEFAULT '0' COMMENT '微赞默认用户组',
  `cityid` int(10) NOT NULL DEFAULT '0' COMMENT '城市ID',
  `shoucename` varchar(200) NOT NULL DEFAULT '' COMMENT '手册名称',
  `videoname` varchar(200) NOT NULL DEFAULT '' COMMENT '监控名称',
  `videopic` varchar(1000) NOT NULL DEFAULT '' COMMENT '监控封面',
  `is_zjh` tinyint(1) NOT NULL DEFAULT '1' COMMENT '2显示1否',
  `is_showad` int(10) NOT NULL DEFAULT '0' COMMENT '广告ID',
  `is_comload` int(10) NOT NULL DEFAULT '0' COMMENT '广告ID',
  `is_wxsign` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1启用2不启用微信签到',
  `is_signneedcomfim` tinyint(1) NOT NULL DEFAULT '1' COMMENT '手机签到是否需确认1是2否',
  `userstyle` varchar(50) NOT NULL DEFAULT 'user' COMMENT '家长学生中心模板',
  `xbtx` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `is_kb` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1启用2不启公立课表',
  `is_fbvocie` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启语音',
  `is_fbnew` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用语音和视频',
  `txid` varchar(100) NOT NULL DEFAULT '' COMMENT '腾讯云APPID',
  `txms` varchar(100) NOT NULL DEFAULT '0' COMMENT '腾讯云密钥',
  `bjqstyle` varchar(50) NOT NULL DEFAULT 'old' COMMENT '班级圈模板',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_kcbiao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id',
  `weid` int(10) unsigned NOT NULL,
  `tid` int(11) NOT NULL COMMENT '所属教师ID',
  `kcid` int(11) NOT NULL COMMENT '所属课程ID',
  `nub` int(11) NOT NULL COMMENT '第几堂课或第几讲',
  `bj_id` int(11) NOT NULL,
  `km_id` int(11) NOT NULL,
  `xq_id` int(11) NOT NULL,
  `sd_id` int(11) NOT NULL,
  `date` int(10) unsigned NOT NULL COMMENT '开课日期',
  `isxiangqing` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0显示1否',
  `content` text NOT NULL COMMENT '课程内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_leave` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `leaveid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID',
  `uid` int(10) unsigned NOT NULL COMMENT '微赞UID',
  `tuid` int(10) unsigned NOT NULL COMMENT '老师微赞UID',
  `openid` varchar(200) DEFAULT '' COMMENT 'openid',
  `sid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生ID',
  `tid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '教师ID',
  `type` varchar(10) DEFAULT '' COMMENT '请假类型',
  `startime` varchar(200) DEFAULT '' COMMENT '开始时间',
  `endtime` varchar(200) DEFAULT '' COMMENT '结束时间',
  `conet` varchar(200) DEFAULT '' COMMENT '详细内容',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `cltime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '处理时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态',
  `bj_id` int(10) unsigned NOT NULL COMMENT '班级ID',
  `isliuyan` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否留言',
  `teacherid` int(11) DEFAULT NULL,
  `isfrist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1是0否',
  `userid` int(11) DEFAULT NULL,
  `touserid` int(11) DEFAULT NULL,
  `startime1` int(10) DEFAULT NULL,
  `endtime1` int(10) DEFAULT NULL,
  `cltid` int(10) unsigned NOT NULL COMMENT '老师id',
  `isread` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1是2否',
  `reconet` varchar(200) DEFAULT '' COMMENT '教师回复',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_media` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID',
  `uid` int(10) unsigned NOT NULL COMMENT '发布者UID',
  `picurl` varchar(255) DEFAULT '' COMMENT '图片',
  `bj_id1` int(10) unsigned NOT NULL COMMENT '班级ID1',
  `bj_id2` int(10) unsigned NOT NULL COMMENT '班级ID2',
  `bj_id3` int(10) unsigned NOT NULL COMMENT '班级ID3',
  `order` int(10) unsigned NOT NULL COMMENT '排序',
  `sherid` int(10) unsigned NOT NULL COMMENT '所属图文id',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `sid` int(10) unsigned NOT NULL COMMENT '学生SID',
  `fmpicurl` varchar(255) DEFAULT '' COMMENT '封面图片',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0班级圈1相册',
  `isfm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0否1是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `cateid` int(10) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `picarr` text COMMENT '图片组',
  `displayorder` int(10) unsigned NOT NULL COMMENT '排序',
  `description` varchar(255) NOT NULL,
  `is_display` tinyint(3) unsigned NOT NULL,
  `is_show_home` tinyint(3) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  `dianzan` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_notice` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID',
  `tid` int(10) unsigned NOT NULL COMMENT '教师ID',
  `tname` varchar(10) DEFAULT '' COMMENT '发布老师名字',
  `title` varchar(50) DEFAULT '' COMMENT '文章名称',
  `content` text NOT NULL COMMENT '详细内容',
  `picarr` text COMMENT '用户信息',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `bj_id` int(10) unsigned NOT NULL COMMENT '班级ID',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否班级通知',
  `groupid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为全体师生2为全体教师3为全体家长和学生',
  `km_id` int(10) unsigned NOT NULL COMMENT '科目ID',
  `ismobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0手机1电脑',
  `outurl` varchar(500) DEFAULT '' COMMENT '外部链接',
  `video` varchar(100) DEFAULT '' COMMENT '视频画面',
  `videopic` varchar(100) DEFAULT '' COMMENT '视频封面',
  `audio` varchar(100) DEFAULT '' COMMENT '音频',
  `audiotime` int(10) unsigned NOT NULL COMMENT '音频时长',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_object` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` int(10) unsigned NOT NULL,
  `type` varchar(50) NOT NULL,
  `displayorder` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID',
  `orderid` int(10) unsigned NOT NULL COMMENT '订单ID',
  `userid` int(10) unsigned NOT NULL COMMENT '发布者UID',
  `uid` int(10) unsigned NOT NULL COMMENT '发布者UID',
  `sid` int(10) unsigned NOT NULL COMMENT '所属图文id',
  `kcid` int(10) unsigned NOT NULL COMMENT '课程ID',
  `obid` int(10) unsigned NOT NULL COMMENT '项目ID',
  `cose` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1未支付2为未支付3为已退款',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1课程2项目3功能',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '支付LOGO',
  `pay_type` varchar(100) DEFAULT '' COMMENT '支付方式',
  `xufeitype` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1已续费2未续费',
  `lastorderid` int(10) unsigned NOT NULL COMMENT '继承订单,用于续费',
  `paytime` int(10) unsigned NOT NULL COMMENT '支付时间',
  `paytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1线上2现金',
  `tuitime` int(10) unsigned NOT NULL COMMENT '退款时间',
  `costid` int(10) unsigned NOT NULL COMMENT '项目ID',
  `signid` int(10) unsigned NOT NULL COMMENT '报名ID',
  `bdcardid` int(10) unsigned NOT NULL COMMENT '帮卡ID',
  `payweid` int(10) unsigned NOT NULL COMMENT '支付公众号',
  `xbtx` varchar(30) NOT NULL DEFAULT '0' COMMENT '支付LOGO',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_qrcode_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `qrcid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二维码场景ID',
  `gpid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '场景名称',
  `keyword` varchar(100) NOT NULL COMMENT '关联关键字',
  `model` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '模式，1临时，2为永久',
  `ticket` varchar(250) NOT NULL DEFAULT '' COMMENT '标识',
  `show_url` varchar(550) NOT NULL DEFAULT '' COMMENT '图片地址',
  `expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `subnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注扫描次数',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生成时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为未启用，1为启用',
  `group_id` int(3) unsigned NOT NULL DEFAULT '0',
  `rid` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_qrcid` (`qrcid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_qrcode_set` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bg` int(10) unsigned NOT NULL DEFAULT '0',
  `qrleft` int(10) unsigned NOT NULL DEFAULT '0',
  `qrtop` int(10) unsigned NOT NULL DEFAULT '0',
  `qrwidth` int(10) unsigned NOT NULL DEFAULT '0',
  `qrheight` int(10) unsigned NOT NULL DEFAULT '0',
  `model` int(10) unsigned NOT NULL DEFAULT '1',
  `logoheight` int(10) unsigned NOT NULL DEFAULT '0',
  `logowidth` int(10) unsigned NOT NULL DEFAULT '0',
  `logoqrheight` int(10) unsigned NOT NULL DEFAULT '0',
  `logoqrwidth` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_qrcode_statinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `qid` int(10) unsigned NOT NULL,
  `openid` varchar(150) NOT NULL DEFAULT '' COMMENT '用户的唯一身份ID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否发生在订阅时',
  `qrcid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二维码场景ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '场景名称',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生成时间',
  `group_id` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `noticeid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `openid` varchar(30) NOT NULL COMMENT 'openid',
  `createtime` int(10) unsigned NOT NULL,
  `readtime` int(10) unsigned NOT NULL,
  `type` int(1) unsigned NOT NULL COMMENT '类型1通知2作业',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0',
  `schoolid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_scforxs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `scid` int(10) unsigned NOT NULL,
  `setid` int(10) unsigned NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `iconsetid` int(10) unsigned NOT NULL COMMENT '评价id',
  `iconlevel` int(10) unsigned NOT NULL COMMENT '本评价等级',
  `tword` varchar(1000) DEFAULT '' COMMENT '老师评语',
  `jzword` varchar(1000) DEFAULT '' COMMENT '家长评语',
  `dianzan` varchar(1000) DEFAULT '' COMMENT '点赞数',
  `dianzopenid` varchar(500) DEFAULT '' COMMENT '点赞人openid',
  `fromto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1来自老师2来自家长',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1文字2表现评价3点赞',
  `createtime` int(10) unsigned NOT NULL,
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id',
  `xq_id` int(11) NOT NULL,
  `bj_id` int(11) NOT NULL,
  `qh_id` int(11) NOT NULL,
  `km_id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `my_score` varchar(50) NOT NULL,
  `info` varchar(1000) NOT NULL DEFAULT '' COMMENT '教师评价',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_set` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `istplnotice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否模版通知',
  `xsqingjia` varchar(200) DEFAULT '' COMMENT '学生请假申请ID',
  `xsqjsh` varchar(200) DEFAULT '' COMMENT '学生请假审核通知ID',
  `jsqingjia` varchar(200) DEFAULT '' COMMENT '教员请假申请体提醒ID',
  `jsqjsh` varchar(200) DEFAULT '' COMMENT '教员请假审核通知ID',
  `xxtongzhi` varchar(200) DEFAULT '' COMMENT '学校通知ID',
  `liuyan` varchar(200) DEFAULT '' COMMENT '家长留言ID',
  `liuyanhf` varchar(200) DEFAULT '' COMMENT '教师回复家长留言ID',
  `bjtz` varchar(200) DEFAULT '' COMMENT '班级通知ID',
  `zuoye` varchar(200) DEFAULT '' COMMENT '发布作业提醒ID',
  `bjqshjg` varchar(200) DEFAULT '',
  `bjqshtz` varchar(200) DEFAULT '',
  `guanli` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理方式',
  `jxlxtx` varchar(200) DEFAULT '' COMMENT '进校提醒',
  `jfjgtz` varchar(200) DEFAULT '' COMMENT '缴费结果通知',
  `jxtx` varchar(200) DEFAULT '' COMMENT '进校提醒',
  `htname` varchar(200) DEFAULT '' COMMENT '后台系统名称',
  `bgcolor` varchar(20) DEFAULT '' COMMENT '后台系统背景颜色',
  `banner1` varchar(200) DEFAULT '',
  `banner2` varchar(200) DEFAULT '',
  `banner3` varchar(200) DEFAULT '',
  `banner4` varchar(200) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_shouce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `bj_id` int(10) unsigned NOT NULL,
  `xq_id` int(10) unsigned NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `title` varchar(1000) DEFAULT '',
  `setid` int(10) unsigned NOT NULL COMMENT '设置ID',
  `kcid` int(10) unsigned NOT NULL COMMENT '课程ID',
  `ksid` int(10) unsigned NOT NULL COMMENT '课时ID',
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `sendtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1未发送2部分发送3全部发送',
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_shoucepyk` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `bj_id` int(10) unsigned NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `title` text COMMENT '内容',
  `createtime` int(10) unsigned NOT NULL,
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_shouceset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `title` varchar(7) DEFAULT '',
  `bottext` varchar(7) DEFAULT '',
  `boturl` varchar(1000) DEFAULT '',
  `lasttxet` varchar(7) DEFAULT '',
  `nj_id` int(10) unsigned NOT NULL,
  `icon` varchar(1000) DEFAULT '',
  `bg1` varchar(1000) DEFAULT '',
  `bg2` varchar(1000) DEFAULT '',
  `bg3` varchar(1000) DEFAULT '',
  `bg4` varchar(1000) DEFAULT '',
  `bg5` varchar(1000) DEFAULT '',
  `bg6` varchar(1000) DEFAULT '',
  `bgm` varchar(1000) DEFAULT '',
  `top1` varchar(1000) DEFAULT '',
  `top2` varchar(1000) DEFAULT '',
  `top3` varchar(1000) DEFAULT '',
  `top4` varchar(1000) DEFAULT '',
  `top5` varchar(1000) DEFAULT '',
  `guidword1` varchar(20) DEFAULT '',
  `guidword2` varchar(20) DEFAULT '',
  `guidurl` varchar(1000) DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  `allowshare` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1允许2禁止',
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_shouceseticon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `setid` int(10) unsigned NOT NULL COMMENT '设置ID',
  `title` varchar(7) DEFAULT '',
  `icon1title` varchar(10) DEFAULT '',
  `icon2title` varchar(10) DEFAULT '',
  `icon3title` varchar(10) DEFAULT '',
  `icon4title` varchar(10) DEFAULT '',
  `icon5title` varchar(10) DEFAULT '',
  `icon1` varchar(1000) DEFAULT '',
  `icon2` varchar(1000) DEFAULT '',
  `icon3` varchar(1000) DEFAULT '',
  `icon4` varchar(1000) DEFAULT '',
  `icon5` varchar(1000) DEFAULT '',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1教师使用2家长',
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_signup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `icon` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL,
  `numberid` int(11) DEFAULT NULL,
  `sex` int(1) NOT NULL,
  `mobile` char(11) NOT NULL,
  `nj_id` int(10) unsigned NOT NULL COMMENT '年级ID',
  `bj_id` int(10) unsigned NOT NULL COMMENT '班级ID',
  `idcard` varchar(18) NOT NULL,
  `cost` varchar(10) NOT NULL,
  `birthday` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `passtime` int(10) unsigned NOT NULL,
  `lasttime` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL COMMENT '发布者UID',
  `orderid` int(10) unsigned NOT NULL,
  `openid` varchar(30) NOT NULL COMMENT 'openid',
  `pard` tinyint(1) unsigned NOT NULL COMMENT '关系',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1审核中2审核通过3不通过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id',
  `xq_id` int(11) NOT NULL,
  `area_addr` varchar(200) NOT NULL DEFAULT '',
  `ck_id` int(11) NOT NULL,
  `bj_id` int(11) NOT NULL,
  `birthdate` int(10) unsigned NOT NULL,
  `sex` int(1) NOT NULL,
  `createdate` int(10) unsigned NOT NULL,
  `seffectivetime` int(10) unsigned NOT NULL,
  `weixin` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `stheendtime` int(10) unsigned NOT NULL,
  `jf_statu` int(11) DEFAULT NULL,
  `mobile` char(11) NOT NULL,
  `homephone` char(16) NOT NULL,
  `s_name` varchar(50) NOT NULL,
  `localdate_id` char(20) NOT NULL DEFAULT '',
  `note` varchar(50) NOT NULL DEFAULT '',
  `amount` int(11) NOT NULL,
  `area` varchar(50) NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `own` varchar(30) NOT NULL DEFAULT '0' COMMENT '本人微信info',
  `xjid` int(11) unsigned NOT NULL COMMENT '学籍信息',
  `mom` varchar(30) NOT NULL DEFAULT '0' COMMENT '母亲微信info',
  `dad` varchar(30) NOT NULL DEFAULT '0' COMMENT '父亲微信info',
  `ouid` int(10) unsigned NOT NULL COMMENT '系统memberID',
  `muid` int(10) unsigned NOT NULL COMMENT '系统memberID',
  `duid` int(10) unsigned NOT NULL COMMENT '系统memberID',
  `ouserid` int(11) unsigned NOT NULL,
  `muserid` int(11) unsigned NOT NULL,
  `duserid` int(11) unsigned NOT NULL,
  `otheruserid` int(11) unsigned NOT NULL,
  `icon` varchar(255) DEFAULT '' COMMENT '头像',
  `numberid` varchar(18) DEFAULT NULL COMMENT '学号',
  `other` varchar(30) DEFAULT '0' COMMENT '家长',
  `otheruid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID',
  `www` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_tcourse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id',
  `weid` int(10) unsigned NOT NULL,
  `tid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '课程名称',
  `dagang` text NOT NULL COMMENT '课程大纲',
  `start` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end` int(10) unsigned NOT NULL COMMENT '结束时间',
  `minge` int(11) NOT NULL COMMENT '名额限制',
  `adrr` varchar(100) NOT NULL DEFAULT '' COMMENT '授课地址或教室',
  `km_id` int(11) NOT NULL,
  `bj_id` int(11) NOT NULL,
  `xq_id` int(11) NOT NULL,
  `sd_id` int(11) NOT NULL,
  `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `yibao` int(11) NOT NULL COMMENT '已报人数',
  `cose` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1显示,2否',
  `ssort` tinyint(5) NOT NULL DEFAULT '0',
  `payweid` int(10) unsigned NOT NULL COMMENT '支付公众号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_teachers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id',
  `weid` int(10) unsigned NOT NULL,
  `tname` varchar(50) NOT NULL,
  `birthdate` int(10) unsigned NOT NULL,
  `tel` varchar(20) NOT NULL,
  `mobile` char(11) NOT NULL,
  `email` char(50) NOT NULL,
  `sex` int(1) NOT NULL,
  `km_id1` int(11) NOT NULL COMMENT '授课科目1',
  `km_id2` int(11) NOT NULL COMMENT '授课科目2',
  `bj_id1` int(11) NOT NULL COMMENT '授课班级1',
  `bj_id2` int(11) NOT NULL COMMENT '授课班级2',
  `bj_id3` int(11) NOT NULL COMMENT '授课班级3',
  `xq_id1` int(11) NOT NULL COMMENT '授课年级1',
  `xq_id2` int(11) NOT NULL COMMENT '授课年级2',
  `xq_id3` int(11) NOT NULL COMMENT '授课年级3',
  `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `jiontime` int(10) unsigned NOT NULL,
  `info` text NOT NULL COMMENT '教学成果',
  `jinyan` text NOT NULL COMMENT '教学经验',
  `headinfo` text NOT NULL COMMENT '教学特点',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort` int(11) DEFAULT NULL,
  `code` int(11) unsigned NOT NULL COMMENT '绑定码',
  `openid` varchar(30) NOT NULL DEFAULT '0' COMMENT '老师微信',
  `uid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `km_id3` int(11) NOT NULL COMMENT '授课科目3',
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `fz_id` int(11) NOT NULL COMMENT '分组ID',
  `cn` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_timetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schoolid` int(10) unsigned NOT NULL DEFAULT '0',
  `weid` int(10) unsigned NOT NULL,
  `bj_id` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `begintime` int(11) NOT NULL,
  `endtime` int(11) NOT NULL,
  `monday` text NOT NULL,
  `tuesday` text NOT NULL,
  `wednesday` text NOT NULL,
  `thursday` text NOT NULL,
  `friday` text NOT NULL,
  `saturday` text NOT NULL,
  `sunday` text NOT NULL,
  `ishow` int(1) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '1',
  `type` varchar(15) NOT NULL DEFAULT '',
  `headpic` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_wx_school_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(50) NOT NULL COMMENT '类型名称',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级',
  `ssort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '显示状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生ID',
  `tid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老师ID',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID',
  `uid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID',
  `openid` varchar(30) NOT NULL COMMENT 'openid',
  `userinfo` text COMMENT '用户信息',
  `pard` int(1) unsigned NOT NULL COMMENT '关系',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户状态',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `is_allowmsg` tinyint(1) NOT NULL DEFAULT '1' COMMENT '私聊信息接收语法',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_wxpay` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID',
  `orderid` int(10) unsigned NOT NULL COMMENT '返回订单ID',
  `od1` int(10) unsigned NOT NULL COMMENT '1',
  `od2` int(10) unsigned NOT NULL COMMENT '2',
  `od3` int(10) unsigned NOT NULL COMMENT '3',
  `od4` int(10) unsigned NOT NULL COMMENT '4',
  `od5` int(10) unsigned NOT NULL COMMENT '5',
  `cose` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1未支付2为未支付3为已退款',
  `openid` varchar(30) NOT NULL DEFAULT '' COMMENT 'openid',
  `payweid` int(10) unsigned NOT NULL COMMENT '支付公众号',
  `qxwh` varchar(30) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `xbtx` varchar(30) NOT NULL DEFAULT '0' COMMENT '订单ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_zjh` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_on` tinyint(1) NOT NULL DEFAULT '1',
  `picrul` varchar(1000) NOT NULL,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `planuid` varchar(37) NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `bj_id` int(10) unsigned NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1图片2文字',
  `start` int(10) unsigned NOT NULL,
  `end` int(10) unsigned NOT NULL,
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_zjhdetail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `planuid` varchar(37) NOT NULL,
  `curactivename` varchar(100) NOT NULL,
  `detailuid` varchar(37) NOT NULL,
  `curactiveid` varchar(100) NOT NULL,
  `activedesc` text COMMENT '内容',
  `week` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1-5',
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_wx_school_zjhset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `schoolid` int(10) unsigned NOT NULL,
  `planuid` varchar(37) NOT NULL,
  `activetypeid` varchar(100) NOT NULL,
  `curactiveid` varchar(100) NOT NULL,
  `activetypename` varchar(30) DEFAULT '' COMMENT '名称',
  `type` varchar(2) DEFAULT '' COMMENT 'AM,PM',
  `ssort` int(10) unsigned NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('wx_school_allcamera',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_allcamera',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_allcamera',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_allcamera',  'kcid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `kcid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_allcamera',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `name` varchar(50) NOT NULL COMMENT '画面名称';");
}
if(!pdo_fieldexists('wx_school_allcamera',  'conet')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `conet` text COMMENT '说明';");
}
if(!pdo_fieldexists('wx_school_allcamera',  'videopic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `videopic` varchar(1000) NOT NULL DEFAULT '' COMMENT '监控地址';");
}
if(!pdo_fieldexists('wx_school_allcamera',  'videourl')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `videourl` varchar(1000) NOT NULL DEFAULT '' COMMENT '监控地址';");
}
if(!pdo_fieldexists('wx_school_allcamera',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `starttime` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_allcamera',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `endtime` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_allcamera',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_allcamera',  'allowpy')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `allowpy` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1允许2拒绝';");
}
if(!pdo_fieldexists('wx_school_allcamera',  'videotype')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `videotype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1公共2指定班级';");
}
if(!pdo_fieldexists('wx_school_allcamera',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `bj_id` text COMMENT '关联班级组';");
}
if(!pdo_fieldexists('wx_school_allcamera',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1监控2课程直播';");
}
if(!pdo_fieldexists('wx_school_allcamera',  'click')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `click` int(10) unsigned NOT NULL COMMENT '查看量';");
}
if(!pdo_fieldexists('wx_school_allcamera',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_allcamera')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_area',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_area',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('wx_school_area',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `name` varchar(50) NOT NULL COMMENT '区域名称';");
}
if(!pdo_fieldexists('wx_school_area',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('wx_school_area',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `ssort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_area',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '显示状态';");
}
if(!pdo_fieldexists('wx_school_area',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `type` char(20) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_area',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_area')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_banners',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_banners',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_banners',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_banners',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `schoolid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_banners',  'bannername')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `bannername` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_banners',  'link')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_banners',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_banners',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_banners',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_banners',  'begintime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `begintime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_banners',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `endtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_banners',  'leixing')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `leixing` int(1) NOT NULL DEFAULT '0' COMMENT '0学校,1平台';");
}
if(!pdo_fieldexists('wx_school_banners',  'arr')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_banners')." ADD `arr` text COMMENT '列表信息';");
}
if(!pdo_fieldexists('wx_school_bjq',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_bjq',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_bjq',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID';");
}
if(!pdo_fieldexists('wx_school_bjq',  'content')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `content` text NOT NULL COMMENT '详细内容或评价';");
}
if(!pdo_fieldexists('wx_school_bjq',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `uid` int(10) unsigned NOT NULL COMMENT '发布者UID';");
}
if(!pdo_fieldexists('wx_school_bjq',  'weiyiqibj')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `weiyiqibj` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('wx_school_bjq',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `weixin` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('wx_school_bjq',  'bj_id1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `bj_id1` int(10) unsigned NOT NULL COMMENT '班级ID1';");
}
if(!pdo_fieldexists('wx_school_bjq',  'bj_id2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `bj_id2` int(10) unsigned NOT NULL COMMENT '班级ID2';");
}
if(!pdo_fieldexists('wx_school_bjq',  'bj_id3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `bj_id3` int(10) unsigned NOT NULL COMMENT '班级ID3';");
}
if(!pdo_fieldexists('wx_school_bjq',  'sherid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `sherid` int(10) unsigned NOT NULL COMMENT '所属图文id';");
}
if(!pdo_fieldexists('wx_school_bjq',  'shername')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `shername` varchar(50) DEFAULT '' COMMENT '分享者名字';");
}
if(!pdo_fieldexists('wx_school_bjq',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `openid` varchar(30) NOT NULL COMMENT '帖子所属openid';");
}
if(!pdo_fieldexists('wx_school_bjq',  'isopen')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示';");
}
if(!pdo_fieldexists('wx_school_bjq',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '类型0为班级圈1为评论';");
}
if(!pdo_fieldexists('wx_school_bjq',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('wx_school_bjq',  'weizanbj')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `weizanbj` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('wx_school_bjq',  'msgtype')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `msgtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1文字图片2语音3视频';");
}
if(!pdo_fieldexists('wx_school_bjq',  'video')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `video` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_bjq',  'videoimg')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `videoimg` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_bjq',  'plid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `plid` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_bjq',  'is_private')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `is_private` varchar(3) NOT NULL DEFAULT 'N' COMMENT '禁止评论';");
}
if(!pdo_fieldexists('wx_school_bjq',  'audio')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `audio` varchar(1000) DEFAULT '' COMMENT '音频地址';");
}
if(!pdo_fieldexists('wx_school_bjq',  'audiotime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `audiotime` int(10) NOT NULL DEFAULT '0' COMMENT '音频时间';");
}
if(!pdo_fieldexists('wx_school_bjq',  'link')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `link` varchar(1000) DEFAULT '' COMMENT '外链地址';");
}
if(!pdo_fieldexists('wx_school_bjq',  'linkdesc')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `linkdesc` varchar(200) DEFAULT '' COMMENT '外链标题';");
}
if(!pdo_fieldexists('wx_school_bjq',  'hftoname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `hftoname` varchar(100) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_bjq',  'www')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_bjq')." ADD `www` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('wx_school_camerapl',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_camerapl',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_camerapl',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_camerapl',  'carmeraid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `carmeraid` int(10) unsigned NOT NULL COMMENT '画面ID';");
}
if(!pdo_fieldexists('wx_school_camerapl',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `userid` int(10) unsigned NOT NULL COMMENT '用户ID';");
}
if(!pdo_fieldexists('wx_school_camerapl',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `bj_id` int(10) unsigned NOT NULL COMMENT '班级ID';");
}
if(!pdo_fieldexists('wx_school_camerapl',  'conet')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `conet` text COMMENT '内容';");
}
if(!pdo_fieldexists('wx_school_camerapl',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1点赞2评论';");
}
if(!pdo_fieldexists('wx_school_camerapl',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_camerapl')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checklog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_checklog',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checklog',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checklog',  'macid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `macid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checklog',  'cardid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `cardid` varchar(200) NOT NULL DEFAULT '' COMMENT '卡号';");
}
if(!pdo_fieldexists('wx_school_checklog',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `sid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checklog',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `tid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checklog',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `bj_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checklog',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('wx_school_checklog',  'lon')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `lon` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('wx_school_checklog',  'temperature')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `temperature` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_checklog',  'pic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `pic` varchar(255) DEFAULT '' COMMENT '图片';");
}
if(!pdo_fieldexists('wx_school_checklog',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `type` varchar(50) DEFAULT '' COMMENT '进校类型';");
}
if(!pdo_fieldexists('wx_school_checklog',  'leixing')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `leixing` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1进校2离校3迟到4早退';");
}
if(!pdo_fieldexists('wx_school_checklog',  'pard')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `pard` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他11老师';");
}
if(!pdo_fieldexists('wx_school_checklog',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checklog',  'isread')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `isread` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1已读2未读';");
}
if(!pdo_fieldexists('wx_school_checklog',  'checktype')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `checktype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1刷卡2微信';");
}
if(!pdo_fieldexists('wx_school_checklog',  'isconfirm')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `isconfirm` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1确认2拒绝';");
}
if(!pdo_fieldexists('wx_school_checklog',  'qdtid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `qdtid` int(11) NOT NULL COMMENT '代签userid';");
}
if(!pdo_fieldexists('wx_school_checklog',  'pic2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checklog')." ADD `pic2` varchar(255) DEFAULT '' COMMENT '图片2';");
}
if(!pdo_fieldexists('wx_school_checkmac',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_checkmac',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checkmac',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checkmac',  'macname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `macname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checkmac',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checkmac',  'macid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `macid` varchar(200) NOT NULL DEFAULT '' COMMENT '设备编号';");
}
if(!pdo_fieldexists('wx_school_checkmac',  'banner')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `banner` varchar(2000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_checkmac',  'is_on')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `is_on` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1启用2不启用';");
}
if(!pdo_fieldexists('wx_school_checkmac',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_checkmac',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_checkmac')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1进校2离校';");
}
if(!pdo_fieldexists('wx_school_classify',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `sid` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_classify',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id';");
}
if(!pdo_fieldexists('wx_school_classify',  'sname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `sname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_classify',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `ssort` int(5) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_classify',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_classify',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `type` char(20) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_classify',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('wx_school_classify',  'erwei')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `erwei` varchar(200) NOT NULL DEFAULT '' COMMENT '群二维码';");
}
if(!pdo_fieldexists('wx_school_classify',  'qun')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `qun` varchar(200) NOT NULL DEFAULT '' COMMENT 'Q群链接';");
}
if(!pdo_fieldexists('wx_school_classify',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `tid` int(11) unsigned NOT NULL COMMENT '班级主任userid';");
}
if(!pdo_fieldexists('wx_school_classify',  'video')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `video` varchar(1000) NOT NULL DEFAULT '' COMMENT '教室监控地址';");
}
if(!pdo_fieldexists('wx_school_classify',  'video1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `video1` varchar(1000) NOT NULL DEFAULT '' COMMENT '教室监控地址1';");
}
if(!pdo_fieldexists('wx_school_classify',  'videostart')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `videostart` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_classify',  'videoend')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `videoend` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_classify',  'cost')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `cost` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_classify',  'pname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `pname` varchar(50) NOT NULL DEFAULT '' COMMENT '称谓';");
}
if(!pdo_fieldexists('wx_school_classify',  'carmeraid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `carmeraid` text COMMENT '说明';");
}
if(!pdo_fieldexists('wx_school_classify',  'videoclick')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `videoclick` int(11) unsigned NOT NULL COMMENT '视频点击量';");
}
if(!pdo_fieldexists('wx_school_classify',  'allowpy')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `allowpy` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1允许2拒绝';");
}
if(!pdo_fieldexists('wx_school_classify',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_classify')." ADD `icon` varchar(500) DEFAULT '' COMMENT '图标';");
}
if(!pdo_fieldexists('wx_school_cookbook',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id';");
}
if(!pdo_fieldexists('wx_school_cookbook',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'keyword')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `keyword` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'begintime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `begintime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `endtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'monday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `monday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'tuesday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `tuesday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'wednesday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `wednesday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'thursday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `thursday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'friday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `friday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'saturday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `saturday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'sunday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `sunday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cookbook',  'ishow')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `ishow` int(1) NOT NULL DEFAULT '1' COMMENT '1:显示,2隐藏,默认1';");
}
if(!pdo_fieldexists('wx_school_cookbook',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `sort` int(11) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('wx_school_cookbook',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `type` varchar(15) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_cookbook',  'headpic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `headpic` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_cookbook',  'infos')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cookbook')." ADD `infos` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_cost',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'cost')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `cost` decimal(18,2) NOT NULL DEFAULT '0.00';");
}
if(!pdo_fieldexists('wx_school_cost',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `bj_id` text COMMENT '关联班级组';");
}
if(!pdo_fieldexists('wx_school_cost',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `name` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `icon` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'description')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `description` text NOT NULL COMMENT '缴费说明';");
}
if(!pdo_fieldexists('wx_school_cost',  'about')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `about` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `displayorder` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'is_sys')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `is_sys` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1关联缴费，2不关联';");
}
if(!pdo_fieldexists('wx_school_cost',  'is_time')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `is_time` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1有时间限制，2不限制';");
}
if(!pdo_fieldexists('wx_school_cost',  'is_on')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `is_on` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1启用，2不启用';");
}
if(!pdo_fieldexists('wx_school_cost',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `starttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `endtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'dataline')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `dataline` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_cost',  'payweid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_cost')." ADD `payweid` int(10) unsigned NOT NULL COMMENT '支付公众号';");
}
if(!pdo_fieldexists('wx_school_coursetable',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_coursetable',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id';");
}
if(!pdo_fieldexists('wx_school_coursetable',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_coursetable',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_coursetable',  'ishow')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `ishow` int(1) NOT NULL DEFAULT '1' COMMENT '1:显示,2隐藏,默认1';");
}
if(!pdo_fieldexists('wx_school_coursetable',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `sort` int(11) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('wx_school_coursetable',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `type` varchar(15) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_coursetable',  'headpic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `headpic` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_coursetable',  'infos')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `infos` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_coursetable',  'xq_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `xq_id` int(11) NOT NULL COMMENT '学期id';");
}
if(!pdo_fieldexists('wx_school_coursetable',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_coursetable')." ADD `bj_id` int(11) NOT NULL COMMENT '班级id';");
}
if(!pdo_fieldexists('wx_school_dianzan',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_dianzan',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_dianzan',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID';");
}
if(!pdo_fieldexists('wx_school_dianzan',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `uid` int(10) unsigned NOT NULL COMMENT '发布者UID';");
}
if(!pdo_fieldexists('wx_school_dianzan',  'sherid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `sherid` int(10) unsigned NOT NULL COMMENT '所属图文id';");
}
if(!pdo_fieldexists('wx_school_dianzan',  'zname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `zname` varchar(50) DEFAULT '' COMMENT '点赞人名字';");
}
if(!pdo_fieldexists('wx_school_dianzan',  'order')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `order` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_dianzan',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('wx_school_dianzan',  'com')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '图片路径';");
}
if(!pdo_fieldexists('wx_school_dianzan',  'qxwh')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `qxwh` varchar(30) NOT NULL DEFAULT '0' COMMENT '图片路径';");
}
if(!pdo_fieldexists('wx_school_dianzan',  'xbtx')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_dianzan')." ADD `xbtx` varchar(30) NOT NULL DEFAULT '0' COMMENT '图片路径';");
}
if(!pdo_fieldexists('wx_school_email',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_email',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_email',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_email',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `sid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_email',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `userid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_email',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `bj_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_email',  'pard')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `pard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他';");
}
if(!pdo_fieldexists('wx_school_email',  'suggesd')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `suggesd` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_email',  'emailid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `emailid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_email',  'isread')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `isread` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_email',  'is_how')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `is_how` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_email',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_email',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_email')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_fans_group',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_fans_group',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_fans_group',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_fans_group',  'count')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `count` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_fans_group',  'group_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `group_id` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_fans_group',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `name` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_fans_group',  'group_desc')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `group_desc` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_fans_group',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_fans_group',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `type` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '二维码状态';");
}
if(!pdo_fieldexists('wx_school_fans_group',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生成时间';");
}
if(!pdo_fieldexists('wx_school_fans_group',  'is_zhu')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_fans_group')." ADD `is_zhu` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否本校主二维码';");
}
if(!pdo_fieldexists('wx_school_icon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_icon',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号';");
}
if(!pdo_fieldexists('wx_school_icon',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id';");
}
if(!pdo_fieldexists('wx_school_icon',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `name` varchar(50) NOT NULL COMMENT '按钮名称';");
}
if(!pdo_fieldexists('wx_school_icon',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `icon` varchar(1000) NOT NULL COMMENT '按钮图标';");
}
if(!pdo_fieldexists('wx_school_icon',  'url')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `url` varchar(1000) NOT NULL COMMENT '链接url';");
}
if(!pdo_fieldexists('wx_school_icon',  'place')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `place` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1首页菜单2底部菜单';");
}
if(!pdo_fieldexists('wx_school_icon',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `ssort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_icon',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '显示状态';");
}
if(!pdo_fieldexists('wx_school_icon',  'beizhu')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `beizhu` varchar(50) NOT NULL COMMENT '备注或小字';");
}
if(!pdo_fieldexists('wx_school_icon',  'color')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_icon')." ADD `color` varchar(50) NOT NULL COMMENT '颜色';");
}
if(!pdo_fieldexists('wx_school_idcard',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_idcard',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `sid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `tid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `bj_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'pname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `pname` varchar(200) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'idcard')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `idcard` varchar(200) NOT NULL DEFAULT '' COMMENT '卡号';");
}
if(!pdo_fieldexists('wx_school_idcard',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'spic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `spic` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'tpic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `tpic` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'pard')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `pard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1本人2母亲3父亲4爷爷5奶奶6外公7外婆8叔叔9阿姨10其他';");
}
if(!pdo_fieldexists('wx_school_idcard',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'severend')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `severend` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_idcard',  'is_on')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `is_on` int(1) NOT NULL DEFAULT '0' COMMENT '1:使用,2未用,默认0';");
}
if(!pdo_fieldexists('wx_school_idcard',  'usertype')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `usertype` int(1) NOT NULL DEFAULT '0' COMMENT '1:老师,学生0';");
}
if(!pdo_fieldexists('wx_school_idcard',  'is_frist')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_idcard')." ADD `is_frist` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:首次,2非首次';");
}
if(!pdo_fieldexists('wx_school_index',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_index',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `weid` int(10) NOT NULL DEFAULT '0' COMMENT '公众号id';");
}
if(!pdo_fieldexists('wx_school_index',  'areaid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `areaid` int(10) NOT NULL DEFAULT '0' COMMENT '区域id';");
}
if(!pdo_fieldexists('wx_school_index',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `title` varchar(50) NOT NULL DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists('wx_school_index',  'logo')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `logo` varchar(200) NOT NULL DEFAULT '' COMMENT '学校logo';");
}
if(!pdo_fieldexists('wx_school_index',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `thumb` varchar(200) NOT NULL DEFAULT '' COMMENT '图文消息缩略图';");
}
if(!pdo_fieldexists('wx_school_index',  'info')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `info` varchar(1000) NOT NULL DEFAULT '' COMMENT '简短描述';");
}
if(!pdo_fieldexists('wx_school_index',  'content')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `content` text NOT NULL COMMENT '简介';");
}
if(!pdo_fieldexists('wx_school_index',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('wx_school_index',  'location_p')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `location_p` varchar(100) NOT NULL DEFAULT '' COMMENT '省';");
}
if(!pdo_fieldexists('wx_school_index',  'location_c')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `location_c` varchar(100) NOT NULL DEFAULT '' COMMENT '市';");
}
if(!pdo_fieldexists('wx_school_index',  'location_a')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `location_a` varchar(100) NOT NULL DEFAULT '' COMMENT '区';");
}
if(!pdo_fieldexists('wx_school_index',  'address')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `address` varchar(200) NOT NULL COMMENT '地址';");
}
if(!pdo_fieldexists('wx_school_index',  'place')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `place` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'lat')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lat` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '经度';");
}
if(!pdo_fieldexists('wx_school_index',  'lng')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lng` decimal(18,10) NOT NULL DEFAULT '0.0000000000' COMMENT '纬度';");
}
if(!pdo_fieldexists('wx_school_index',  'password')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `password` varchar(20) NOT NULL DEFAULT '' COMMENT '登录密码';");
}
if(!pdo_fieldexists('wx_school_index',  'hours')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `hours` varchar(200) NOT NULL DEFAULT '' COMMENT '营业时间';");
}
if(!pdo_fieldexists('wx_school_index',  'recharging_password')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `recharging_password` varchar(20) NOT NULL DEFAULT '' COMMENT '充值密码';");
}
if(!pdo_fieldexists('wx_school_index',  'thumb_url')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `thumb_url` varchar(1000) DEFAULT NULL;");
}
if(!pdo_fieldexists('wx_school_index',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否在手机端显示';");
}
if(!pdo_fieldexists('wx_school_index',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `ssort` tinyint(3) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_index',  'is_sms')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_sms` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_index',  'dateline')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `dateline` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_index',  'is_hot')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '搜索页显示';");
}
if(!pdo_fieldexists('wx_school_index',  'gonggao')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `gonggao` varchar(1000) NOT NULL DEFAULT '' COMMENT '通知';");
}
if(!pdo_fieldexists('wx_school_index',  'is_rest')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_rest` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_index',  'typeid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `typeid` int(10) NOT NULL DEFAULT '0' COMMENT '学校类型';");
}
if(!pdo_fieldexists('wx_school_index',  'style1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `style1` varchar(200) NOT NULL DEFAULT '' COMMENT '模版名称';");
}
if(!pdo_fieldexists('wx_school_index',  'isopen')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0显示1否';");
}
if(!pdo_fieldexists('wx_school_index',  'qroce')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `qroce` varchar(200) NOT NULL DEFAULT '' COMMENT '二维码';");
}
if(!pdo_fieldexists('wx_school_index',  'issale')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `issale` tinyint(1) NOT NULL DEFAULT '5' COMMENT '5种状态';");
}
if(!pdo_fieldexists('wx_school_index',  'zhaosheng')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `zhaosheng` text NOT NULL COMMENT '招生简章';");
}
if(!pdo_fieldexists('wx_school_index',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `uid` int(10) NOT NULL DEFAULT '0' COMMENT '账户ID';");
}
if(!pdo_fieldexists('wx_school_index',  'style2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `style2` varchar(200) NOT NULL DEFAULT '' COMMENT '模版名称2';");
}
if(!pdo_fieldexists('wx_school_index',  'style3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `style3` varchar(200) NOT NULL DEFAULT '' COMMENT '模版名称3';");
}
if(!pdo_fieldexists('wx_school_index',  'is_sign')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_sign` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1启用2不启用';");
}
if(!pdo_fieldexists('wx_school_index',  'manger')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `manger` varchar(200) NOT NULL DEFAULT '' COMMENT '信息审核员';");
}
if(!pdo_fieldexists('wx_school_index',  'signset')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `signset` varchar(200) NOT NULL COMMENT '报名设置';");
}
if(!pdo_fieldexists('wx_school_index',  'is_cost')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_cost` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用';");
}
if(!pdo_fieldexists('wx_school_index',  'is_video')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_video` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用';");
}
if(!pdo_fieldexists('wx_school_index',  'is_recordmac')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_recordmac` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用';");
}
if(!pdo_fieldexists('wx_school_index',  'spic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `spic` varchar(200) NOT NULL DEFAULT '' COMMENT '默认学生头像';");
}
if(!pdo_fieldexists('wx_school_index',  'tpic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `tpic` varchar(200) NOT NULL DEFAULT '' COMMENT '默认教师头像';");
}
if(!pdo_fieldexists('wx_school_index',  'jxstart')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxstart` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'jxend')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxend` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'lxstart')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxstart` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'lxend')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxend` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'jxstart1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxstart1` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'jxend1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxend1` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'lxstart1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxstart1` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'lxend1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxend1` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'jxstart2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxstart2` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'jxend2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `jxend2` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'lxstart2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxstart2` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'lxend2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `lxend2` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_index',  'is_cardpay')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_cardpay` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用';");
}
if(!pdo_fieldexists('wx_school_index',  'is_cardlist')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_cardlist` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用';");
}
if(!pdo_fieldexists('wx_school_index',  'cardset')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `cardset` varchar(500) NOT NULL COMMENT '刷卡设置';");
}
if(!pdo_fieldexists('wx_school_index',  'is_openht')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_openht` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1启用2不启用独立后台';");
}
if(!pdo_fieldexists('wx_school_index',  'is_showew')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_showew` tinyint(1) NOT NULL DEFAULT '1' COMMENT '2显示1否';");
}
if(!pdo_fieldexists('wx_school_index',  'wqgroupid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `wqgroupid` int(10) NOT NULL DEFAULT '0' COMMENT '微赞默认用户组';");
}
if(!pdo_fieldexists('wx_school_index',  'cityid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `cityid` int(10) NOT NULL DEFAULT '0' COMMENT '城市ID';");
}
if(!pdo_fieldexists('wx_school_index',  'shoucename')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `shoucename` varchar(200) NOT NULL DEFAULT '' COMMENT '手册名称';");
}
if(!pdo_fieldexists('wx_school_index',  'videoname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `videoname` varchar(200) NOT NULL DEFAULT '' COMMENT '监控名称';");
}
if(!pdo_fieldexists('wx_school_index',  'videopic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `videopic` varchar(1000) NOT NULL DEFAULT '' COMMENT '监控封面';");
}
if(!pdo_fieldexists('wx_school_index',  'is_zjh')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_zjh` tinyint(1) NOT NULL DEFAULT '1' COMMENT '2显示1否';");
}
if(!pdo_fieldexists('wx_school_index',  'is_showad')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_showad` int(10) NOT NULL DEFAULT '0' COMMENT '广告ID';");
}
if(!pdo_fieldexists('wx_school_index',  'is_comload')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_comload` int(10) NOT NULL DEFAULT '0' COMMENT '广告ID';");
}
if(!pdo_fieldexists('wx_school_index',  'is_wxsign')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_wxsign` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1启用2不启用微信签到';");
}
if(!pdo_fieldexists('wx_school_index',  'is_signneedcomfim')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_signneedcomfim` tinyint(1) NOT NULL DEFAULT '1' COMMENT '手机签到是否需确认1是2否';");
}
if(!pdo_fieldexists('wx_school_index',  'userstyle')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `userstyle` varchar(50) NOT NULL DEFAULT 'user' COMMENT '家长学生中心模板';");
}
if(!pdo_fieldexists('wx_school_index',  'xbtx')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `xbtx` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('wx_school_index',  'is_kb')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_kb` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1启用2不启公立课表';");
}
if(!pdo_fieldexists('wx_school_index',  'is_fbvocie')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_fbvocie` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启语音';");
}
if(!pdo_fieldexists('wx_school_index',  'is_fbnew')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `is_fbnew` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1启用2不启用语音和视频';");
}
if(!pdo_fieldexists('wx_school_index',  'txid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `txid` varchar(100) NOT NULL DEFAULT '' COMMENT '腾讯云APPID';");
}
if(!pdo_fieldexists('wx_school_index',  'txms')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `txms` varchar(100) NOT NULL DEFAULT '0' COMMENT '腾讯云密钥';");
}
if(!pdo_fieldexists('wx_school_index',  'bjqstyle')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_index')." ADD `bjqstyle` varchar(50) NOT NULL DEFAULT 'old' COMMENT '班级圈模板';");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id';");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `tid` int(11) NOT NULL COMMENT '所属教师ID';");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'kcid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `kcid` int(11) NOT NULL COMMENT '所属课程ID';");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'nub')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `nub` int(11) NOT NULL COMMENT '第几堂课或第几讲';");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `bj_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'km_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `km_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'xq_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `xq_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'sd_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `sd_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'date')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `date` int(10) unsigned NOT NULL COMMENT '开课日期';");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'isxiangqing')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `isxiangqing` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0显示1否';");
}
if(!pdo_fieldexists('wx_school_kcbiao',  'content')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_kcbiao')." ADD `content` text NOT NULL COMMENT '课程内容';");
}
if(!pdo_fieldexists('wx_school_leave',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_leave',  'leaveid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `leaveid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_leave',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_leave',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID';");
}
if(!pdo_fieldexists('wx_school_leave',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `uid` int(10) unsigned NOT NULL COMMENT '微赞UID';");
}
if(!pdo_fieldexists('wx_school_leave',  'tuid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `tuid` int(10) unsigned NOT NULL COMMENT '老师微赞UID';");
}
if(!pdo_fieldexists('wx_school_leave',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `openid` varchar(200) DEFAULT '' COMMENT 'openid';");
}
if(!pdo_fieldexists('wx_school_leave',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生ID';");
}
if(!pdo_fieldexists('wx_school_leave',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `tid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '教师ID';");
}
if(!pdo_fieldexists('wx_school_leave',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `type` varchar(10) DEFAULT '' COMMENT '请假类型';");
}
if(!pdo_fieldexists('wx_school_leave',  'startime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `startime` varchar(200) DEFAULT '' COMMENT '开始时间';");
}
if(!pdo_fieldexists('wx_school_leave',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `endtime` varchar(200) DEFAULT '' COMMENT '结束时间';");
}
if(!pdo_fieldexists('wx_school_leave',  'conet')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `conet` varchar(200) DEFAULT '' COMMENT '详细内容';");
}
if(!pdo_fieldexists('wx_school_leave',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('wx_school_leave',  'cltime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `cltime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '处理时间';");
}
if(!pdo_fieldexists('wx_school_leave',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核状态';");
}
if(!pdo_fieldexists('wx_school_leave',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `bj_id` int(10) unsigned NOT NULL COMMENT '班级ID';");
}
if(!pdo_fieldexists('wx_school_leave',  'isliuyan')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `isliuyan` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否留言';");
}
if(!pdo_fieldexists('wx_school_leave',  'teacherid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `teacherid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('wx_school_leave',  'isfrist')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `isfrist` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1是0否';");
}
if(!pdo_fieldexists('wx_school_leave',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `userid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('wx_school_leave',  'touserid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `touserid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('wx_school_leave',  'startime1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `startime1` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('wx_school_leave',  'endtime1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `endtime1` int(10) DEFAULT NULL;");
}
if(!pdo_fieldexists('wx_school_leave',  'cltid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `cltid` int(10) unsigned NOT NULL COMMENT '老师id';");
}
if(!pdo_fieldexists('wx_school_leave',  'isread')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `isread` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1是2否';");
}
if(!pdo_fieldexists('wx_school_leave',  'reconet')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_leave')." ADD `reconet` varchar(200) DEFAULT '' COMMENT '教师回复';");
}
if(!pdo_fieldexists('wx_school_media',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_media',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_media',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID';");
}
if(!pdo_fieldexists('wx_school_media',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `uid` int(10) unsigned NOT NULL COMMENT '发布者UID';");
}
if(!pdo_fieldexists('wx_school_media',  'picurl')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `picurl` varchar(255) DEFAULT '' COMMENT '图片';");
}
if(!pdo_fieldexists('wx_school_media',  'bj_id1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `bj_id1` int(10) unsigned NOT NULL COMMENT '班级ID1';");
}
if(!pdo_fieldexists('wx_school_media',  'bj_id2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `bj_id2` int(10) unsigned NOT NULL COMMENT '班级ID2';");
}
if(!pdo_fieldexists('wx_school_media',  'bj_id3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `bj_id3` int(10) unsigned NOT NULL COMMENT '班级ID3';");
}
if(!pdo_fieldexists('wx_school_media',  'order')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `order` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_media',  'sherid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `sherid` int(10) unsigned NOT NULL COMMENT '所属图文id';");
}
if(!pdo_fieldexists('wx_school_media',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('wx_school_media',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `sid` int(10) unsigned NOT NULL COMMENT '学生SID';");
}
if(!pdo_fieldexists('wx_school_media',  'fmpicurl')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `fmpicurl` varchar(255) DEFAULT '' COMMENT '封面图片';");
}
if(!pdo_fieldexists('wx_school_media',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0班级圈1相册';");
}
if(!pdo_fieldexists('wx_school_media',  'isfm')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_media')." ADD `isfm` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0否1是';");
}
if(!pdo_fieldexists('wx_school_news',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_news',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'cateid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `cateid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `type` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'content')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `content` mediumtext NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'author')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `author` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'picarr')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `picarr` text COMMENT '图片组';");
}
if(!pdo_fieldexists('wx_school_news',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `displayorder` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_news',  'description')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `description` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'is_display')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `is_display` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'is_show_home')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `is_show_home` tinyint(3) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'click')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `click` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_news',  'dianzan')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_news')." ADD `dianzan` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_notice',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_notice',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_notice',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID';");
}
if(!pdo_fieldexists('wx_school_notice',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `tid` int(10) unsigned NOT NULL COMMENT '教师ID';");
}
if(!pdo_fieldexists('wx_school_notice',  'tname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `tname` varchar(10) DEFAULT '' COMMENT '发布老师名字';");
}
if(!pdo_fieldexists('wx_school_notice',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `title` varchar(50) DEFAULT '' COMMENT '文章名称';");
}
if(!pdo_fieldexists('wx_school_notice',  'content')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `content` text NOT NULL COMMENT '详细内容';");
}
if(!pdo_fieldexists('wx_school_notice',  'picarr')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `picarr` text COMMENT '用户信息';");
}
if(!pdo_fieldexists('wx_school_notice',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('wx_school_notice',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `bj_id` int(10) unsigned NOT NULL COMMENT '班级ID';");
}
if(!pdo_fieldexists('wx_school_notice',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否班级通知';");
}
if(!pdo_fieldexists('wx_school_notice',  'groupid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `groupid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为全体师生2为全体教师3为全体家长和学生';");
}
if(!pdo_fieldexists('wx_school_notice',  'km_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `km_id` int(10) unsigned NOT NULL COMMENT '科目ID';");
}
if(!pdo_fieldexists('wx_school_notice',  'ismobile')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `ismobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0手机1电脑';");
}
if(!pdo_fieldexists('wx_school_notice',  'outurl')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `outurl` varchar(500) DEFAULT '' COMMENT '外部链接';");
}
if(!pdo_fieldexists('wx_school_notice',  'video')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `video` varchar(100) DEFAULT '' COMMENT '视频画面';");
}
if(!pdo_fieldexists('wx_school_notice',  'videopic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `videopic` varchar(100) DEFAULT '' COMMENT '视频封面';");
}
if(!pdo_fieldexists('wx_school_notice',  'audio')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `audio` varchar(100) DEFAULT '' COMMENT '音频';");
}
if(!pdo_fieldexists('wx_school_notice',  'audiotime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_notice')." ADD `audiotime` int(10) unsigned NOT NULL COMMENT '音频时长';");
}
if(!pdo_fieldexists('wx_school_object',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_object')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_object',  'item')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_object')." ADD `item` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_object',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_object')." ADD `type` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_object',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_object')." ADD `displayorder` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_order',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID';");
}
if(!pdo_fieldexists('wx_school_order',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `orderid` int(10) unsigned NOT NULL COMMENT '订单ID';");
}
if(!pdo_fieldexists('wx_school_order',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `userid` int(10) unsigned NOT NULL COMMENT '发布者UID';");
}
if(!pdo_fieldexists('wx_school_order',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `uid` int(10) unsigned NOT NULL COMMENT '发布者UID';");
}
if(!pdo_fieldexists('wx_school_order',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `sid` int(10) unsigned NOT NULL COMMENT '所属图文id';");
}
if(!pdo_fieldexists('wx_school_order',  'kcid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `kcid` int(10) unsigned NOT NULL COMMENT '课程ID';");
}
if(!pdo_fieldexists('wx_school_order',  'obid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `obid` int(10) unsigned NOT NULL COMMENT '项目ID';");
}
if(!pdo_fieldexists('wx_school_order',  'cose')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `cose` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '价格';");
}
if(!pdo_fieldexists('wx_school_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1未支付2为未支付3为已退款';");
}
if(!pdo_fieldexists('wx_school_order',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1课程2项目3功能';");
}
if(!pdo_fieldexists('wx_school_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('wx_school_order',  'com')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '支付LOGO';");
}
if(!pdo_fieldexists('wx_school_order',  'pay_type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `pay_type` varchar(100) DEFAULT '' COMMENT '支付方式';");
}
if(!pdo_fieldexists('wx_school_order',  'xufeitype')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `xufeitype` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1已续费2未续费';");
}
if(!pdo_fieldexists('wx_school_order',  'lastorderid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `lastorderid` int(10) unsigned NOT NULL COMMENT '继承订单,用于续费';");
}
if(!pdo_fieldexists('wx_school_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `paytime` int(10) unsigned NOT NULL COMMENT '支付时间';");
}
if(!pdo_fieldexists('wx_school_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `paytype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1线上2现金';");
}
if(!pdo_fieldexists('wx_school_order',  'tuitime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `tuitime` int(10) unsigned NOT NULL COMMENT '退款时间';");
}
if(!pdo_fieldexists('wx_school_order',  'costid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `costid` int(10) unsigned NOT NULL COMMENT '项目ID';");
}
if(!pdo_fieldexists('wx_school_order',  'signid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `signid` int(10) unsigned NOT NULL COMMENT '报名ID';");
}
if(!pdo_fieldexists('wx_school_order',  'bdcardid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `bdcardid` int(10) unsigned NOT NULL COMMENT '帮卡ID';");
}
if(!pdo_fieldexists('wx_school_order',  'payweid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `payweid` int(10) unsigned NOT NULL COMMENT '支付公众号';");
}
if(!pdo_fieldexists('wx_school_order',  'xbtx')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_order')." ADD `xbtx` varchar(30) NOT NULL DEFAULT '0' COMMENT '支付LOGO';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'qrcid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `qrcid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二维码场景ID';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'gpid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `gpid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `name` varchar(50) NOT NULL DEFAULT '' COMMENT '场景名称';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'keyword')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `keyword` varchar(100) NOT NULL COMMENT '关联关键字';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'model')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `model` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '模式，1临时，2为永久';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `ticket` varchar(250) NOT NULL DEFAULT '' COMMENT '标识';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'show_url')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `show_url` varchar(550) NOT NULL DEFAULT '' COMMENT '图片地址';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'expire')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'subnum')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `subnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '关注扫描次数';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生成时间';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0为未启用，1为启用';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'group_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `group_id` int(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_info',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD `rid` int(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('wx_school_qrcode_info',  'idx_qrcid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_info')." ADD KEY `idx_qrcid` (`qrcid`);");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'bg')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `bg` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'qrleft')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `qrleft` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'qrtop')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `qrtop` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'qrwidth')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `qrwidth` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'qrheight')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `qrheight` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'model')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `model` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'logoheight')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `logoheight` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'logowidth')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `logowidth` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'logoqrheight')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `logoqrheight` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_set',  'logoqrwidth')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_set')." ADD `logoqrwidth` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'qid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `qid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `openid` varchar(150) NOT NULL DEFAULT '' COMMENT '用户的唯一身份ID';");
}
if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否发生在订阅时';");
}
if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'qrcid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `qrcid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '二维码场景ID';");
}
if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `name` varchar(50) NOT NULL DEFAULT '' COMMENT '场景名称';");
}
if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生成时间';");
}
if(!pdo_fieldexists('wx_school_qrcode_statinfo',  'group_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_qrcode_statinfo')." ADD `group_id` int(3) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_record',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_record',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_record',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_record',  'noticeid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `noticeid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_record',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `userid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_record',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `tid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_record',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `sid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_record',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `openid` varchar(30) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('wx_school_record',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_record',  'readtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `readtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_record',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_record')." ADD `type` int(1) unsigned NOT NULL COMMENT '类型1通知2作业';");
}
if(!pdo_fieldexists('wx_school_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_reply',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_reply')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('wx_school_reply',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_reply')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_fieldexists('wx_school_scforxs',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_scforxs',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_scforxs',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_scforxs',  'scid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `scid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_scforxs',  'setid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `setid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_scforxs',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `tid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_scforxs',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `sid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_scforxs',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `userid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_scforxs',  'iconsetid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `iconsetid` int(10) unsigned NOT NULL COMMENT '评价id';");
}
if(!pdo_fieldexists('wx_school_scforxs',  'iconlevel')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `iconlevel` int(10) unsigned NOT NULL COMMENT '本评价等级';");
}
if(!pdo_fieldexists('wx_school_scforxs',  'tword')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `tword` varchar(1000) DEFAULT '' COMMENT '老师评语';");
}
if(!pdo_fieldexists('wx_school_scforxs',  'jzword')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `jzword` varchar(1000) DEFAULT '' COMMENT '家长评语';");
}
if(!pdo_fieldexists('wx_school_scforxs',  'dianzan')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `dianzan` varchar(1000) DEFAULT '' COMMENT '点赞数';");
}
if(!pdo_fieldexists('wx_school_scforxs',  'dianzopenid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `dianzopenid` varchar(500) DEFAULT '' COMMENT '点赞人openid';");
}
if(!pdo_fieldexists('wx_school_scforxs',  'fromto')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `fromto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1来自老师2来自家长';");
}
if(!pdo_fieldexists('wx_school_scforxs',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1文字2表现评价3点赞';");
}
if(!pdo_fieldexists('wx_school_scforxs',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_scforxs',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_scforxs')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_score',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_score',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_score',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id';");
}
if(!pdo_fieldexists('wx_school_score',  'xq_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `xq_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_score',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `bj_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_score',  'qh_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `qh_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_score',  'km_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `km_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_score',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `sid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_score',  'my_score')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `my_score` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_score',  'info')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `info` varchar(1000) NOT NULL DEFAULT '' COMMENT '教师评价';");
}
if(!pdo_fieldexists('wx_school_score',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_score')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_set',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_set',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_set',  'istplnotice')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `istplnotice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否模版通知';");
}
if(!pdo_fieldexists('wx_school_set',  'xsqingjia')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `xsqingjia` varchar(200) DEFAULT '' COMMENT '学生请假申请ID';");
}
if(!pdo_fieldexists('wx_school_set',  'xsqjsh')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `xsqjsh` varchar(200) DEFAULT '' COMMENT '学生请假审核通知ID';");
}
if(!pdo_fieldexists('wx_school_set',  'jsqingjia')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `jsqingjia` varchar(200) DEFAULT '' COMMENT '教员请假申请体提醒ID';");
}
if(!pdo_fieldexists('wx_school_set',  'jsqjsh')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `jsqjsh` varchar(200) DEFAULT '' COMMENT '教员请假审核通知ID';");
}
if(!pdo_fieldexists('wx_school_set',  'xxtongzhi')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `xxtongzhi` varchar(200) DEFAULT '' COMMENT '学校通知ID';");
}
if(!pdo_fieldexists('wx_school_set',  'liuyan')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `liuyan` varchar(200) DEFAULT '' COMMENT '家长留言ID';");
}
if(!pdo_fieldexists('wx_school_set',  'liuyanhf')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `liuyanhf` varchar(200) DEFAULT '' COMMENT '教师回复家长留言ID';");
}
if(!pdo_fieldexists('wx_school_set',  'bjtz')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bjtz` varchar(200) DEFAULT '' COMMENT '班级通知ID';");
}
if(!pdo_fieldexists('wx_school_set',  'zuoye')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `zuoye` varchar(200) DEFAULT '' COMMENT '发布作业提醒ID';");
}
if(!pdo_fieldexists('wx_school_set',  'bjqshjg')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bjqshjg` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_set',  'bjqshtz')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bjqshtz` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_set',  'guanli')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `guanli` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理方式';");
}
if(!pdo_fieldexists('wx_school_set',  'jxlxtx')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `jxlxtx` varchar(200) DEFAULT '' COMMENT '进校提醒';");
}
if(!pdo_fieldexists('wx_school_set',  'jfjgtz')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `jfjgtz` varchar(200) DEFAULT '' COMMENT '缴费结果通知';");
}
if(!pdo_fieldexists('wx_school_set',  'jxtx')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `jxtx` varchar(200) DEFAULT '' COMMENT '进校提醒';");
}
if(!pdo_fieldexists('wx_school_set',  'htname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `htname` varchar(200) DEFAULT '' COMMENT '后台系统名称';");
}
if(!pdo_fieldexists('wx_school_set',  'bgcolor')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `bgcolor` varchar(20) DEFAULT '' COMMENT '后台系统背景颜色';");
}
if(!pdo_fieldexists('wx_school_set',  'banner1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `banner1` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_set',  'banner2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `banner2` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_set',  'banner3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `banner3` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_set',  'banner4')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_set')." ADD `banner4` varchar(200) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouce',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_shouce',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouce',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouce',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `bj_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouce',  'xq_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `xq_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouce',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `tid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouce',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `title` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouce',  'setid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `setid` int(10) unsigned NOT NULL COMMENT '设置ID';");
}
if(!pdo_fieldexists('wx_school_shouce',  'kcid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `kcid` int(10) unsigned NOT NULL COMMENT '课程ID';");
}
if(!pdo_fieldexists('wx_school_shouce',  'ksid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `ksid` int(10) unsigned NOT NULL COMMENT '课时ID';");
}
if(!pdo_fieldexists('wx_school_shouce',  'starttime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `starttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouce',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `endtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouce',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouce',  'sendtype')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `sendtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1未发送2部分发送3全部发送';");
}
if(!pdo_fieldexists('wx_school_shouce',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouce')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_shoucepyk',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_shoucepyk',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shoucepyk',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shoucepyk',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `bj_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shoucepyk',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `tid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shoucepyk',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `title` text COMMENT '内容';");
}
if(!pdo_fieldexists('wx_school_shoucepyk',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shoucepyk',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shoucepyk')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_shouceset',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouceset',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouceset',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `title` varchar(7) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'bottext')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bottext` varchar(7) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'boturl')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `boturl` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'lasttxet')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `lasttxet` varchar(7) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'nj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `nj_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouceset',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `icon` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'bg1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg1` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'bg2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg2` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'bg3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg3` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'bg4')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg4` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'bg5')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg5` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'bg6')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bg6` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'bgm')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `bgm` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'top1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top1` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'top2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top2` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'top3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top3` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'top4')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top4` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'top5')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `top5` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'guidword1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `guidword1` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'guidword2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `guidword2` varchar(20) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'guidurl')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `guidurl` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouceset',  'allowshare')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `allowshare` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1允许2禁止';");
}
if(!pdo_fieldexists('wx_school_shouceset',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceset')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'setid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `setid` int(10) unsigned NOT NULL COMMENT '设置ID';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `title` varchar(7) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon1title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon1title` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon2title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon2title` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon3title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon3title` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon4title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon4title` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon5title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon5title` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon1` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon2` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon3` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon4')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon4` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'icon5')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `icon5` varchar(1000) DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1教师使用2家长';");
}
if(!pdo_fieldexists('wx_school_shouceseticon',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_shouceseticon')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_signup',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_signup',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `icon` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'numberid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `numberid` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `sex` int(1) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `mobile` char(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'nj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `nj_id` int(10) unsigned NOT NULL COMMENT '年级ID';");
}
if(!pdo_fieldexists('wx_school_signup',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `bj_id` int(10) unsigned NOT NULL COMMENT '班级ID';");
}
if(!pdo_fieldexists('wx_school_signup',  'idcard')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `idcard` varchar(18) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'cost')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `cost` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'birthday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `birthday` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'passtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `passtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `lasttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `uid` int(10) unsigned NOT NULL COMMENT '发布者UID';");
}
if(!pdo_fieldexists('wx_school_signup',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `orderid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_signup',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `openid` varchar(30) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('wx_school_signup',  'pard')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `pard` tinyint(1) unsigned NOT NULL COMMENT '关系';");
}
if(!pdo_fieldexists('wx_school_signup',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_signup')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1审核中2审核通过3不通过';");
}
if(!pdo_fieldexists('wx_school_students',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_students',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id';");
}
if(!pdo_fieldexists('wx_school_students',  'xq_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `xq_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'area_addr')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `area_addr` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_students',  'ck_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `ck_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `bj_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'birthdate')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `birthdate` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `sex` int(1) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'createdate')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `createdate` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'seffectivetime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `seffectivetime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `weixin` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('wx_school_students',  'stheendtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `stheendtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'jf_statu')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `jf_statu` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `mobile` char(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'homephone')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `homephone` char(16) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  's_name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `s_name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'localdate_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `localdate_id` char(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_students',  'note')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `note` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_students',  'amount')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `amount` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'area')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `area` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'own')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `own` varchar(30) NOT NULL DEFAULT '0' COMMENT '本人微信info';");
}
if(!pdo_fieldexists('wx_school_students',  'xjid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `xjid` int(11) unsigned NOT NULL COMMENT '学籍信息';");
}
if(!pdo_fieldexists('wx_school_students',  'mom')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `mom` varchar(30) NOT NULL DEFAULT '0' COMMENT '母亲微信info';");
}
if(!pdo_fieldexists('wx_school_students',  'dad')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `dad` varchar(30) NOT NULL DEFAULT '0' COMMENT '父亲微信info';");
}
if(!pdo_fieldexists('wx_school_students',  'ouid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `ouid` int(10) unsigned NOT NULL COMMENT '系统memberID';");
}
if(!pdo_fieldexists('wx_school_students',  'muid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `muid` int(10) unsigned NOT NULL COMMENT '系统memberID';");
}
if(!pdo_fieldexists('wx_school_students',  'duid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `duid` int(10) unsigned NOT NULL COMMENT '系统memberID';");
}
if(!pdo_fieldexists('wx_school_students',  'ouserid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `ouserid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'muserid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `muserid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'duserid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `duserid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'otheruserid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `otheruserid` int(11) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_students',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `icon` varchar(255) DEFAULT '' COMMENT '头像';");
}
if(!pdo_fieldexists('wx_school_students',  'numberid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `numberid` varchar(18) DEFAULT NULL COMMENT '学号';");
}
if(!pdo_fieldexists('wx_school_students',  'other')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `other` varchar(30) DEFAULT '0' COMMENT '家长';");
}
if(!pdo_fieldexists('wx_school_students',  'otheruid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `otheruid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID';");
}
if(!pdo_fieldexists('wx_school_students',  'www')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_students')." ADD `www` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_tcourse',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_tcourse',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `tid` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_tcourse',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `name` varchar(50) NOT NULL DEFAULT '' COMMENT '课程名称';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'dagang')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `dagang` text NOT NULL COMMENT '课程大纲';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'start')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `start` int(10) unsigned NOT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'end')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `end` int(10) unsigned NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'minge')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `minge` int(11) NOT NULL COMMENT '名额限制';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'adrr')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `adrr` varchar(100) NOT NULL DEFAULT '' COMMENT '授课地址或教室';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'km_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `km_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_tcourse',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `bj_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_tcourse',  'xq_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `xq_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_tcourse',  'sd_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `sd_id` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_tcourse',  'is_hot')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `is_hot` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'yibao')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `yibao` int(11) NOT NULL COMMENT '已报人数';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'cose')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `cose` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '价格';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1显示,2否';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `ssort` tinyint(5) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_tcourse',  'payweid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_tcourse')." ADD `payweid` int(10) unsigned NOT NULL COMMENT '支付公众号';");
}
if(!pdo_fieldexists('wx_school_teachers',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_teachers',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分校id';");
}
if(!pdo_fieldexists('wx_school_teachers',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_teachers',  'tname')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `tname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_teachers',  'birthdate')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `birthdate` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_teachers',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `tel` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_teachers',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `mobile` char(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_teachers',  'email')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `email` char(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_teachers',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `sex` int(1) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_teachers',  'km_id1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `km_id1` int(11) NOT NULL COMMENT '授课科目1';");
}
if(!pdo_fieldexists('wx_school_teachers',  'km_id2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `km_id2` int(11) NOT NULL COMMENT '授课科目2';");
}
if(!pdo_fieldexists('wx_school_teachers',  'bj_id1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `bj_id1` int(11) NOT NULL COMMENT '授课班级1';");
}
if(!pdo_fieldexists('wx_school_teachers',  'bj_id2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `bj_id2` int(11) NOT NULL COMMENT '授课班级2';");
}
if(!pdo_fieldexists('wx_school_teachers',  'bj_id3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `bj_id3` int(11) NOT NULL COMMENT '授课班级3';");
}
if(!pdo_fieldexists('wx_school_teachers',  'xq_id1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `xq_id1` int(11) NOT NULL COMMENT '授课年级1';");
}
if(!pdo_fieldexists('wx_school_teachers',  'xq_id2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `xq_id2` int(11) NOT NULL COMMENT '授课年级2';");
}
if(!pdo_fieldexists('wx_school_teachers',  'xq_id3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `xq_id3` int(11) NOT NULL COMMENT '授课年级3';");
}
if(!pdo_fieldexists('wx_school_teachers',  'com')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('wx_school_teachers',  'jiontime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `jiontime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_teachers',  'info')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `info` text NOT NULL COMMENT '教学成果';");
}
if(!pdo_fieldexists('wx_school_teachers',  'jinyan')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `jinyan` text NOT NULL COMMENT '教学经验';");
}
if(!pdo_fieldexists('wx_school_teachers',  'headinfo')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `headinfo` text NOT NULL COMMENT '教学特点';");
}
if(!pdo_fieldexists('wx_school_teachers',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `thumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_teachers',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('wx_school_teachers',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `sort` int(11) DEFAULT NULL;");
}
if(!pdo_fieldexists('wx_school_teachers',  'code')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `code` int(11) unsigned NOT NULL COMMENT '绑定码';");
}
if(!pdo_fieldexists('wx_school_teachers',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `openid` varchar(30) NOT NULL DEFAULT '0' COMMENT '老师微信';");
}
if(!pdo_fieldexists('wx_school_teachers',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `uid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID';");
}
if(!pdo_fieldexists('wx_school_teachers',  'is_show')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示';");
}
if(!pdo_fieldexists('wx_school_teachers',  'km_id3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `km_id3` int(11) NOT NULL COMMENT '授课科目3';");
}
if(!pdo_fieldexists('wx_school_teachers',  'userid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `userid` int(11) NOT NULL COMMENT '用户ID';");
}
if(!pdo_fieldexists('wx_school_teachers',  'fz_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `fz_id` int(11) NOT NULL COMMENT '分组ID';");
}
if(!pdo_fieldexists('wx_school_teachers',  'cn')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_teachers')." ADD `cn` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('wx_school_timetable',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_timetable',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `schoolid` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('wx_school_timetable',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `bj_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'title')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'begintime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `begintime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'endtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `endtime` int(11) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'monday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `monday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'tuesday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `tuesday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'wednesday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `wednesday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'thursday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `thursday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'friday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `friday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'saturday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `saturday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'sunday')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `sunday` text NOT NULL;");
}
if(!pdo_fieldexists('wx_school_timetable',  'ishow')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `ishow` int(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('wx_school_timetable',  'sort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `sort` int(11) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('wx_school_timetable',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `type` varchar(15) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_timetable',  'headpic')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_timetable')." ADD `headpic` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('wx_school_type',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_type',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号';");
}
if(!pdo_fieldexists('wx_school_type',  'name')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `name` varchar(50) NOT NULL COMMENT '类型名称';");
}
if(!pdo_fieldexists('wx_school_type',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类ID,0为第一级';");
}
if(!pdo_fieldexists('wx_school_type',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `ssort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_type',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_type')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '显示状态';");
}
if(!pdo_fieldexists('wx_school_user',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_user',  'sid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `sid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学生ID';");
}
if(!pdo_fieldexists('wx_school_user',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `tid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '老师ID';");
}
if(!pdo_fieldexists('wx_school_user',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('wx_school_user',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID';");
}
if(!pdo_fieldexists('wx_school_user',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `uid` int(10) unsigned NOT NULL COMMENT '微赞系统memberID';");
}
if(!pdo_fieldexists('wx_school_user',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `openid` varchar(30) NOT NULL COMMENT 'openid';");
}
if(!pdo_fieldexists('wx_school_user',  'userinfo')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `userinfo` text COMMENT '用户信息';");
}
if(!pdo_fieldexists('wx_school_user',  'pard')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `pard` int(1) unsigned NOT NULL COMMENT '关系';");
}
if(!pdo_fieldexists('wx_school_user',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户状态';");
}
if(!pdo_fieldexists('wx_school_user',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间';");
}
if(!pdo_fieldexists('wx_school_user',  'is_allowmsg')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_user')." ADD `is_allowmsg` tinyint(1) NOT NULL DEFAULT '1' COMMENT '私聊信息接收语法';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `id` int(10) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_wxpay',  'com')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '订单ID';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_wxpay',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `schoolid` int(10) unsigned NOT NULL COMMENT '学校ID';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `orderid` int(10) unsigned NOT NULL COMMENT '返回订单ID';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'od1')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od1` int(10) unsigned NOT NULL COMMENT '1';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'od2')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od2` int(10) unsigned NOT NULL COMMENT '2';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'od3')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od3` int(10) unsigned NOT NULL COMMENT '3';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'od4')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od4` int(10) unsigned NOT NULL COMMENT '4';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'od5')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `od5` int(10) unsigned NOT NULL COMMENT '5';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'cose')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `cose` decimal(18,2) NOT NULL DEFAULT '0.00' COMMENT '价格';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'status')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1未支付2为未支付3为已退款';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `openid` varchar(30) NOT NULL DEFAULT '' COMMENT 'openid';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'payweid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `payweid` int(10) unsigned NOT NULL COMMENT '支付公众号';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'qxwh')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `qxwh` varchar(30) NOT NULL DEFAULT '0' COMMENT '订单ID';");
}
if(!pdo_fieldexists('wx_school_wxpay',  'xbtx')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_wxpay')." ADD `xbtx` varchar(30) NOT NULL DEFAULT '0' COMMENT '订单ID';");
}
if(!pdo_fieldexists('wx_school_zjh',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_zjh',  'is_on')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `is_on` tinyint(1) NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('wx_school_zjh',  'picrul')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `picrul` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjh',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjh',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjh',  'planuid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `planuid` varchar(37) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjh',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `tid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjh',  'bj_id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `bj_id` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjh',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1图片2文字';");
}
if(!pdo_fieldexists('wx_school_zjh',  'start')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `start` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjh',  'end')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `end` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjh',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_zjh',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjh')." ADD `createtime` int(10) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'planuid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `planuid` varchar(37) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'curactivename')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `curactivename` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'detailuid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `detailuid` varchar(37) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'curactiveid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `curactiveid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'activedesc')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `activedesc` text COMMENT '内容';");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'week')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `week` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1-5';");
}
if(!pdo_fieldexists('wx_school_zjhdetail',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhdetail')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}
if(!pdo_fieldexists('wx_school_zjhset',  'id')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('wx_school_zjhset',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhset',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `schoolid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhset',  'planuid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `planuid` varchar(37) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhset',  'activetypeid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `activetypeid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhset',  'curactiveid')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `curactiveid` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('wx_school_zjhset',  'activetypename')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `activetypename` varchar(30) DEFAULT '' COMMENT '名称';");
}
if(!pdo_fieldexists('wx_school_zjhset',  'type')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `type` varchar(2) DEFAULT '' COMMENT 'AM,PM';");
}
if(!pdo_fieldexists('wx_school_zjhset',  'ssort')) {
	pdo_query("ALTER TABLE ".tablename('wx_school_zjhset')." ADD `ssort` int(10) unsigned NOT NULL COMMENT '排序';");
}

?>