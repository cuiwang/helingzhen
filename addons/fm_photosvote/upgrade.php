<?php
$sql="CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_advs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `description` varchar(350) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `times` int(11) DEFAULT '0',
  `ismiaoxian` int(2) DEFAULT '0',
  `issuiji` int(2) DEFAULT '0',
  `nexttime` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_announce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `content` varchar(150) NOT NULL DEFAULT '' COMMENT '公告',
  `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '公告',
  `url` varchar(200) NOT NULL DEFAULT '' COMMENT '公告链接',
  `createtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(11) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `answer` text,
  `key` varchar(10) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_awarding` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区域ID',
  `shoptitle` varchar(50) NOT NULL DEFAULT '' COMMENT '兑奖店面名称',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '兑奖地址',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `pass` varchar(20) NOT NULL DEFAULT '' COMMENT '兑奖密码',
  `images` varchar(200) NOT NULL DEFAULT '' COMMENT '广告或店面图',
  `carmap` varchar(50) NOT NULL COMMENT '地图导航',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_awardingtype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `quyutitle` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称',
  `orderid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(11) DEFAULT '0',
  `bannername` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_bbsreply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `tid` varchar(125) NOT NULL COMMENT '帖子的ID',
  `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '帖子作者的openid',
  `reply_id` varchar(125) NOT NULL COMMENT '回复评论帖子的ID',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '回复评论帖子的openid',
  `to_reply_id` int(11) NOT NULL DEFAULT '0' COMMENT '回复评论的id',
  `rfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被回复的评论的作者的openid',
  `content` text NOT NULL COMMENT '评论回复内容',
  `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已删除 0-否 1-是',
  `status` tinyint(2) DEFAULT '0' COMMENT '是否审核 0-否 1-是',
  `storey` int(11) NOT NULL DEFAULT '0' COMMENT '绝对楼层',
  `zan` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT '回复IP',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `createtime` int(11) NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_counter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `tfrom_user` varchar(100) NOT NULL,
  `tp_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票次数',
  `gift_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '送礼物次数',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1、总共投票数 2、每天总共投票数 3、同一选手总共投票数 4、同一选手每天总共投票数 5、单公众号总共投票数 6、单公众号每天总共投票数 7、单公众号同一选手总共投票数 8、单公众号同一选手每天总共投票数  9、每天总共送礼物数 10、每天给同一个人总共送礼物数 ',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`),
  KEY `idx_from_user` (`from_user`),
  KEY `idx_tfrom_user` (`tfrom_user`),
  KEY `idx_type` (`type`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `fromuser` varchar(150) NOT NULL DEFAULT '' COMMENT '分享用户openid',
  `from_user` varchar(150) NOT NULL DEFAULT '' COMMENT '当前用户openid',
  `tfrom_user` varchar(150) NOT NULL DEFAULT '' COMMENT '被分享用户openid',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享人UID',
  `isin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否参与',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uid` (`uid`),
  KEY `indx_from_user` (`from_user`),
  KEY `IDX_TFROM_USER` (`tfrom_user`),
  KEY `IDX_FROMUSER` (`fromuser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_gift` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '奖品名称',
  `total` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `total_winning` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖数量',
  `lingjiangtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '奖品库存减少方式0为有资格1为提交2为兑奖',
  `description` text NOT NULL COMMENT '描述',
  `inkind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物',
  `activation_code` varchar(50) NOT NULL COMMENT '激活码',
  `activation_url` varchar(215) NOT NULL COMMENT '激活地址',
  `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要朋友人数',
  `awardpic` varchar(200) NOT NULL COMMENT '奖品图片',
  `awardpass` varchar(20) NOT NULL COMMENT '兑奖密码',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_giftmika` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `giftid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼盒ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `mika` varchar(50) NOT NULL COMMENT '密卡字符串',
  `activationurl` varchar(200) NOT NULL COMMENT '激活地址',
  `typename` varchar(20) NOT NULL DEFAULT '' COMMENT '类型说明',
  `description` varchar(50) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_iplist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `ipadd` varchar(100) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `createtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_iplistlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT 'openid',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  `hitym` varchar(255) NOT NULL DEFAULT '' COMMENT '点击页面',
  `createtime` int(11) NOT NULL COMMENT '初始时间',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_jifen` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `is_open_jifen` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '积分 1开启0关闭',
  `is_open_jifen_sync` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1同步0关闭',
  `is_open_choujiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖 1同步0关闭',
  `jifen_vote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票获得积分',
  `jifen_vote_reg` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票参赛人获得积分',
  `jifen_reg` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名获得积分',
  `jifen_charge` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '充值获得积分',
  `title` varchar(50) NOT NULL COMMENT '奖品名称',
  `total` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `total_winning` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖数量',
  `lingjiangtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '奖品库存减少方式0为有资格1为提交2为兑奖',
  `description` text NOT NULL COMMENT '描述',
  `inkind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物',
  `activation_code` varchar(50) NOT NULL COMMENT '激活码',
  `activation_url` varchar(215) NOT NULL COMMENT '激活地址',
  `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要朋友人数',
  `awardpic` varchar(512) NOT NULL COMMENT '奖品图片',
  `awardpass` varchar(20) NOT NULL COMMENT '兑奖密码',
  `jifen_gift_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每天最多送几次礼物',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_jifen_gift` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `gifttitle` varchar(150) NOT NULL COMMENT '礼物名称',
  `jifen` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `piaoshu` int(11) NOT NULL DEFAULT '0' COMMENT '票数',
  `images` varchar(150) NOT NULL COMMENT '图片',
  `description` varchar(150) NOT NULL COMMENT '描述',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `dhnum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '已兑换数量',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `tfrom_user` varchar(100) NOT NULL,
  `content` text NOT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0、未读 1、已读  2、已删除',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1、投票信息 2、被投票信息  3、报名信息 4、充值信息 5、得奖信息 6、积分变更 7、开通信息',
  `lasttime` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_from_user` (`from_user`),
  KEY `idx_tfrom_user` (`tfrom_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_onlyoauth` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL,
  `fmauthtoken` varchar(255) NOT NULL,
  `modules` varchar(120) NOT NULL,
  `oauthurl` varchar(120) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `visitorsip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_siteid` (`siteid`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `tfrom_user` varchar(50) NOT NULL,
  `ordersn` varchar(50) NOT NULL,
  `price` varchar(10) NOT NULL,
  `vote_times` varchar(10) NOT NULL,
  `realname` varchar(30) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `payyz` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付',
  `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号',
  `remark` varchar(1000) NOT NULL DEFAULT '',
  `paytime` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `fromuser` varchar(100) NOT NULL COMMENT '分享用户',
  `iparr` varchar(200) NOT NULL COMMENT '地址',
  `ip` varchar(100) NOT NULL COMMENT 'ip地址',
  `ispayvote` tinyint(1) unsigned NOT NULL COMMENT '0默认 1为支付后成功投票 2为支付成功后未投票成功',
  `avatar` varchar(200) NOT NULL COMMENT '头像',
  `jifen` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `giftid` int(10) unsigned NOT NULL COMMENT '礼物id',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_from_user` (`from_user`),
  KEY `idx_tfrom_user` (`tfrom_user`),
  KEY `idx_ordersn` (`ordersn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_orderlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `ordersn` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `from_user` varchar(100) NOT NULL,
  `remark` text NOT NULL COMMENT '内容',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0、未读 1、已读  2、已删除',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1、投票信息 2、被投票信息  3、报名信息 4、充值信息 5、得奖信息 6、积分变更 7、开通信息',
  `lasttime` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_from_user` (`from_user`),
  KEY `idx_ordersn` (`ordersn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_pnametag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `title` varchar(350) NOT NULL DEFAULT '' COMMENT '默认口号',
  `createtime` int(10) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_provevote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号',
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `tagid` int(10) unsigned NOT NULL COMMENT '分组id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被投票用户openid',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别，1、男 2、女 0 、未知',
  `photo` varchar(200) NOT NULL DEFAULT '' COMMENT '照片',
  `music` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `mediaid` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐id',
  `timelength` varchar(200) NOT NULL DEFAULT '' COMMENT '时间轴',
  `voice` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `vedio` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  `youkuurl` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  `fmmid` varchar(200) NOT NULL DEFAULT '' COMMENT '识别',
  `picarr` varchar(2000) NOT NULL DEFAULT '' COMMENT '照片组',
  `description` text NOT NULL COMMENT '简介，描述',
  `photoname` varchar(50) NOT NULL DEFAULT '' COMMENT '照片名字',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `job` varchar(20) NOT NULL DEFAULT '' COMMENT '职业',
  `xingqu` varchar(20) NOT NULL DEFAULT '' COMMENT '兴趣',
  `weixin` varchar(255) NOT NULL DEFAULT '' COMMENT '联系微信号',
  `qqhao` varchar(20) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '联系地址',
  `m` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `wikaifa` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `weiyiqibj` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `chinashanfu` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `photosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '票数',
  `xnphotosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟票数',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人气',
  `xnhits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人气',
  `yaoqingnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请量',
  `zans` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `ewm` varchar(200) NOT NULL DEFAULT '' COMMENT '二维码地址',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `isadmin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否设置为管理员',
  `istuijian` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否设置为推荐',
  `limitsd` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '限速',
  `createip` varchar(50) NOT NULL DEFAULT '' COMMENT '创建IP',
  `lastip` varchar(50) NOT NULL DEFAULT '' COMMENT '编辑IP',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'ip地区',
  `lasttime` int(10) unsigned NOT NULL COMMENT '最后编辑时间',
  `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `sharenum` int(10) unsigned NOT NULL COMMENT '最后分享',
  `createtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `unionid` varchar(255) NOT NULL DEFAULT '' COMMENT '统一用户openid',
  `ordersn` varchar(55) NOT NULL DEFAULT '' COMMENT '付款订单号',
  `tagpid` int(10) unsigned NOT NULL COMMENT '分组pid',
  `tagpidtest` int(10) unsigned NOT NULL COMMENT 'tagpidtest',
  `unphotosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '减少的票数',
  `unhits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '减少的人气',
  `haibao` varchar(200) NOT NULL DEFAULT '' COMMENT '海报地址',
  `tagtid` int(10) unsigned NOT NULL COMMENT '分组pid_3',
  `www` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `wx` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `schoolid` int(10) unsigned NOT NULL COMMENT '学校id',
  `sourceid` int(10) unsigned NOT NULL COMMENT '来源id',
  `musictime` int(10) unsigned NOT NULL COMMENT '音乐时间',
  `voicetime` int(10) unsigned NOT NULL COMMENT '录音时间',
  `vediotime` int(10) unsigned NOT NULL COMMENT '视频时间',
  `giftnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收到的礼物数',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`),
  KEY `indx_rid` (`rid`),
  KEY `uk_uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_provevote_name` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `musicname` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `photoname` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_1_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_2_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_3_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_4_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_5_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_6_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_7_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_8_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `musicnamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `voicename` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `voicenamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `vedioname` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  `vedionamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_provevote_picarr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `mid` int(10) unsigned NOT NULL,
  `lastmid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `photos` varchar(200) NOT NULL DEFAULT '' COMMENT '图片',
  `photoname` varchar(200) NOT NULL DEFAULT '' COMMENT '图片名字',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `isfm` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否设置为封面',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `imgpath` varchar(200) NOT NULL DEFAULT '' COMMENT '绝对路径',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_provevote_voice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(555) NOT NULL DEFAULT '' COMMENT 'openid',
  `mediaid` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐id',
  `timelength` varchar(200) NOT NULL DEFAULT '' COMMENT '时间轴',
  `voice` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `fmmid` varchar(200) NOT NULL DEFAULT '' COMMENT '识别',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(11) NOT NULL COMMENT '初始时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`(255)),
  KEY `indx_rid` (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_qrcode` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `from_user` varchar(150) NOT NULL,
  `tfrom_user` varchar(150) NOT NULL,
  `type` varchar(10) NOT NULL,
  `extra` int(10) unsigned NOT NULL,
  `qrcid` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `model` tinyint(1) unsigned NOT NULL,
  `ticket` varchar(250) NOT NULL,
  `url` varchar(256) NOT NULL,
  `imgurl` varchar(256) NOT NULL,
  `expire` int(10) unsigned NOT NULL,
  `subnum` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `scene_str` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_qrcid` (`qrcid`),
  KEY `uniacid` (`uniacid`),
  KEY `ticket` (`ticket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_qrcode_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `qid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `qrcid` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `scene_str` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_qunfa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `type` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `lasttime` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_from_user` (`from_user`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '开关状态',
  `title` varchar(50) NOT NULL COMMENT '规则标题',
  `picture` varchar(225) NOT NULL COMMENT '规则图片',
  `cn` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `tstart_time` int(10) unsigned NOT NULL COMMENT '投票开始时间',
  `tend_time` int(10) unsigned NOT NULL COMMENT '投票结束时间',
  `bstart_time` int(10) unsigned NOT NULL COMMENT '报名开始时间',
  `bend_time` int(10) unsigned NOT NULL COMMENT '报名结束时间',
  `ttipstart` varchar(255) NOT NULL COMMENT '投票开始时间',
  `ttipend` varchar(255) NOT NULL COMMENT '投票结束时间',
  `btipstart` varchar(255) NOT NULL COMMENT '报名开始时间',
  `btipend` varchar(255) NOT NULL COMMENT '报名结束时间',
  `isdaojishi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '倒计时开关',
  `ttipvote` varchar(100) NOT NULL COMMENT '提示',
  `votetime` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '时间',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `stopping` varchar(225) NOT NULL COMMENT 'fx图片',
  `nostart` varchar(225) NOT NULL COMMENT 'fx图片',
  `end` varchar(225) NOT NULL COMMENT 'fx图片',
  `qiniu` varchar(600) NOT NULL COMMENT '七牛',
  `templates` varchar(50) NOT NULL DEFAULT 'default' COMMENT '默认模板',
  `binduniacid` varchar(150) NOT NULL COMMENT '多公众号ID',
  `unimoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '公众号模式',
  `menuid` int(10) unsigned NOT NULL COMMENT '底部导航ID',
  `kftel` varchar(30) NOT NULL COMMENT '客服电话',
  `net` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `isadmin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_reply_body` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `zbgcolor` varchar(10) NOT NULL COMMENT '背景色',
  `zbg` varchar(125) NOT NULL COMMENT '背景图',
  `voicebg` varchar(125) NOT NULL COMMENT '录音室背景',
  `zbgtj` varchar(125) NOT NULL COMMENT '背景图',
  `topbgcolor` varchar(10) NOT NULL COMMENT '背景图',
  `topbg` varchar(125) NOT NULL COMMENT '背景图',
  `topbgtext` varchar(125) NOT NULL COMMENT '背景图',
  `topbgrightcolor` varchar(10) NOT NULL COMMENT '背景图',
  `topbgright` varchar(125) NOT NULL COMMENT '背景图',
  `foobg1` varchar(125) NOT NULL COMMENT '背景图',
  `foobg2` varchar(125) NOT NULL COMMENT '背景图',
  `foobgtextn` varchar(125) NOT NULL COMMENT '背景图',
  `foobgtexty` varchar(125) NOT NULL COMMENT '背景图',
  `foobgtextmore` varchar(125) NOT NULL COMMENT '背景图',
  `foobgmorecolor` varchar(10) NOT NULL COMMENT '背景图',
  `foobgmore` varchar(125) NOT NULL COMMENT '背景图',
  `bodytextcolor` varchar(10) NOT NULL COMMENT '背景图',
  `bodynumcolor` varchar(10) NOT NULL COMMENT '背景图',
  `inputcolor` varchar(10) NOT NULL COMMENT '背景图',
  `bodytscolor` varchar(10) NOT NULL COMMENT '背景图',
  `bodytsbg` varchar(125) NOT NULL COMMENT '背景图',
  `xinbg` varchar(125) NOT NULL COMMENT '背景图',
  `copyrightcolor` varchar(10) NOT NULL COMMENT '背景图',
  `photosvote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy',
  `tuser` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy',
  `paihang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy',
  `reg` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy',
  `des` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy',
  `tags` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy',
  `other` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy',
  `rbody_photosvote` text NOT NULL COMMENT '内容',
  `rbody_tuser` text NOT NULL COMMENT '内容',
  `rbody_paihang` text NOT NULL COMMENT '内容',
  `rbody_reg` text NOT NULL COMMENT '内容',
  `rbody_des` text NOT NULL COMMENT '内容',
  `rbody_tags` text NOT NULL COMMENT '内容',
  `qxbfooter` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_reply_display` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `istopheader` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '顶部导航',
  `ipannounce` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '顶部公告',
  `isbgaudio` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '背景音乐',
  `bgmusic` varchar(125) NOT NULL COMMENT '背景音乐链接',
  `isedes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启首页显示说明',
  `tmoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '首页显示模式',
  `indextpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '首页列表显示数',
  `indexorder` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '首页排序',
  `indexpx` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '活动首页显示,0 按最新排序 1 按人气排序 3 按投票数排序',
  `phbtpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人气榜显示个数',
  `zanzhums` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '赞助商显示',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人数',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟随机数值',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isweixin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入微信号0为不需要1为需要',
  `isqqhao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isjob` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isxingqu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入兴趣0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isindex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页显示0为不需要1为需要',
  `isreg` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否报名页显示0为不需要1为需要',
  `isdes` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否描述页显示0为不需要1为需要',
  `isvotexq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否详情页显示0为不需要1为需要',
  `ispaihang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否排行页显示0为不需要1为需要',
  `lapiao` varchar(5) NOT NULL COMMENT '拉票',
  `sharename` varchar(2) NOT NULL COMMENT '分享名字',
  `tpname` varchar(100) NOT NULL COMMENT '投票名称',
  `tpsname` varchar(100) NOT NULL COMMENT '投票数名称',
  `rqname` varchar(100) NOT NULL COMMENT '人气名称',
  `csrs` varchar(10) NOT NULL COMMENT '参赛作品',
  `ljtp` varchar(10) NOT NULL COMMENT '累计投票',
  `cyrs` varchar(10) NOT NULL COMMENT '参与人数',
  `iscopyright` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0显示公众号版权1为显示自定义版权',
  `copyrighturl` varchar(255) NOT NULL COMMENT '版权链接',
  `copyright` varchar(50) NOT NULL COMMENT '版权',
  `isvoteusers` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示投票人',
  `regtitlearr` text NOT NULL,
  `csrs_total` varchar(10) NOT NULL COMMENT '参赛作品总数',
  `ljtp_total` varchar(10) NOT NULL COMMENT '累计投票总数',
  `xunips` varchar(10) NOT NULL COMMENT '虚拟累计投票总数',
  `cyrs_total` varchar(10) NOT NULL COMMENT '参与人数总数',
  `unphotosnum` varchar(10) NOT NULL COMMENT '取消票数',
  `iscontent` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启首页显示说明',
  `isphotosname` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `isregdes` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `openqr` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启海报二维码',
  `qrset` varchar(255) NOT NULL COMMENT '二维码设置',
  `open_vote_count` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启显示投票数据',
  `open_vote_size` varchar(255) NOT NULL COMMENT '显示前多少人',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_reply_huihua` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `command` varchar(10) NOT NULL COMMENT '报名命令',
  `tcommand` varchar(10) NOT NULL COMMENT '报名命令',
  `ishuodong` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 开启',
  `huodongname` varchar(20) NOT NULL COMMENT '活动名字',
  `huodongdes` varchar(50) NOT NULL COMMENT '活动简介',
  `huodongurl` varchar(125) NOT NULL COMMENT '活动链接',
  `hhhdpicture` varchar(125) NOT NULL COMMENT '活动图片',
  `regmessagetemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知报名成功',
  `messagetemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知',
  `shmessagetemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知投票审核成功',
  `fmqftemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知群发消息',
  `msgtemplate` varchar(50) NOT NULL COMMENT '评论通知消息',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_reply_share` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `subscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否强制需要关注公众号才能参与',
  `shareurl` varchar(255) NOT NULL COMMENT '活动网址',
  `sharetitle` varchar(50) NOT NULL COMMENT '分享标题',
  `sharephoto` varchar(225) NOT NULL COMMENT 'fx图片',
  `sharecontent` varchar(100) NOT NULL COMMENT '分享简介',
  `subscribedes` varchar(200) NOT NULL COMMENT '分享提示语',
  `sharelink` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义分享链接',
  `isopentime` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启限制跳转',
  `open_limittime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '限制时间',
  `open_url` varchar(255) NOT NULL COMMENT '指定跳转链接',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_reply_vote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `iscode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启投票验证码',
  `codekey` varchar(255) NOT NULL COMMENT '验证码key',
  `addpvapp` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '前端是否允许用户报名',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `mediatype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `mediatypem` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `mediatypev` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `voicemoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '语音室模式',
  `moshi` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '展示模式： 1 相册模式  2 详情模式',
  `webinfo` text NOT NULL COMMENT '内容',
  `cqtp` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可重复投票',
  `tpsh` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '投稿是否需审核',
  `isbbsreply` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启评论',
  `tmyushe` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '弹幕评论是否同步到数据库',
  `tmreply` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '弹幕评论是否同步到数据库',
  `isipv` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启IP作弊限制',
  `ipturl` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '存在作弊ip后是否继续允许查看，投票，评论等',
  `ipstopvote` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '存在作弊ip后是否继续允许查看，投票，评论等',
  `iplocallimit` varchar(100) NOT NULL COMMENT '地区限制',
  `iplocaldes` varchar(100) NOT NULL COMMENT '地区限制',
  `tpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投稿照片数限制',
  `autolitpic` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '裁剪大小',
  `autozl` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '裁剪质量',
  `limitip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票ip每天限制数',
  `daytpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日投票数限制',
  `dayonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一选手投票数限制',
  `allonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一选手最高投票数',
  `fansmostvote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最高投票数',
  `userinfo` varchar(200) NOT NULL COMMENT '输入姓名或手机时的提示词',
  `votesuccess` varchar(200) NOT NULL COMMENT '投票成功提示语',
  `limitsd` varchar(20) NOT NULL COMMENT '全体限速',
  `limitsdps` varchar(20) NOT NULL COMMENT '全体限速投机票',
  `unimoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '公众号模式',
  `votepay` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启投票付费',
  `votepaytitle` varchar(50) NOT NULL COMMENT '付费名称',
  `votepaydes` varchar(200) NOT NULL COMMENT '付费描述',
  `votepayfee` varchar(50) NOT NULL COMMENT '付费金额',
  `regpay` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启报名付费',
  `regpaytitle` varchar(50) NOT NULL COMMENT '报名名称',
  `regpaydes` varchar(200) NOT NULL COMMENT '报名描述',
  `regpayfee` varchar(50) NOT NULL COMMENT '报名金额',
  `uni_fansmostvote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '多用户最高投票数',
  `uni_daytpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '多每日投票数限制',
  `uni_allonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '多同一选手最高投票数',
  `uni_dayonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '多同一选手投票数限制',
  `uni_all_users` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看所有用户',
  `votenumpiao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启投票用户自定投票数',
  `voteerinfo` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启投票用户填写信息',
  `usersmostvote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '选手最高得票数',
  `votestarttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `voteendtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `ismediatypem` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `ismediatypev` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `ismediatype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `codekeykey` varchar(255) NOT NULL COMMENT '验证码key',
  `giftvote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启送礼物',
  `open_smart` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启智能刷票模式',
  `open_lbs_localtion` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启定位',
  `open_lbs_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '定位方式 ： 1、H5定位  2、腾讯lbs定位 3、百度lbs定位',
  `open_lbs_key_baidu` varchar(50) NOT NULL COMMENT 'key',
  `open_lbs_key_qq` varchar(50) NOT NULL COMMENT 'key',
  `open_lbs_link` varchar(100) NOT NULL COMMENT 'link',
  `limitsd_voter` varchar(20) NOT NULL COMMENT '全体投票者限速',
  `limitsdps_voter` varchar(20) NOT NULL COMMENT '全体投票者限速投机票',
  `isanswer` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用答题',
  `answer_times` int(10) unsigned NOT NULL COMMENT '答题次数',
  `answer_times_ps` int(10) unsigned NOT NULL COMMENT '答对获取的投票数',
  `bbsreply_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '评论是否审核',
  `opendx` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启多选投票',
  `limittimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最多选择几人',
  `isopengiftlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启送礼物列表',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_school` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `parentid` int(10) unsigned NOT NULL COMMENT 'pid',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `icon` varchar(200) NOT NULL DEFAULT '',
  `images` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `piaoshu` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_source` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `parentid` int(10) unsigned NOT NULL COMMENT 'pid',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `icon` varchar(200) NOT NULL DEFAULT '',
  `images` varchar(255) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `piaoshu` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `title` varchar(100) NOT NULL DEFAULT '',
  `icon` varchar(200) NOT NULL DEFAULT '',
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `parentid` int(10) unsigned NOT NULL COMMENT 'pid',
  `description` varchar(200) NOT NULL DEFAULT '',
  `piaoshu` int(10) unsigned NOT NULL COMMENT 'piaoshu',
  `images` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `name` varchar(30) NOT NULL,
  `title` varchar(30) NOT NULL,
  `version` varchar(64) NOT NULL,
  `author` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `sections` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `stylename` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_templates_designer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `stylename` varchar(30) NOT NULL,
  `pagename` varchar(255) NOT NULL DEFAULT '' COMMENT '页面名称',
  `pagetype` tinyint(3) NOT NULL DEFAULT '0' COMMENT '页面类型',
  `pageinfo` text NOT NULL,
  `keyword` varchar(255) DEFAULT '',
  `savetime` varchar(255) NOT NULL DEFAULT '' COMMENT '页面最后保存时间',
  `setdefault` tinyint(3) NOT NULL DEFAULT '0' COMMENT '默认页面',
  `datas` text NOT NULL COMMENT '数据',
  `createtime` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_pagetype` (`pagetype`),
  KEY `idx_keyword` (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_templates_designer_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `menuname` varchar(255) DEFAULT '',
  `isdefault` tinyint(3) DEFAULT '0',
  `createtime` int(11) DEFAULT '0',
  `menus` text,
  `params` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_isdefault` (`isdefault`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_user_gift` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `giftid` int(10) unsigned NOT NULL COMMENT '礼物id',
  `giftnum` int(10) unsigned NOT NULL COMMENT '礼物数量',
  `from_user` varchar(100) NOT NULL COMMENT '用户',
  `tfrom_user` varchar(100) NOT NULL COMMENT '送给的用户',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1、未使用  2、已使用',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_user_zsgift` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `giftid` int(10) unsigned NOT NULL COMMENT '礼物id',
  `giftnum` int(10) unsigned NOT NULL COMMENT '礼物数量',
  `from_user` varchar(100) NOT NULL COMMENT '用户',
  `tfrom_user` varchar(100) NOT NULL COMMENT '送给的用户',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1、购买 2、积分 3、赠送',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_vote_shuapiao` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(150) NOT NULL,
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip',
  `createtime` int(10) unsigned NOT NULL,
  `ua` varchar(300) NOT NULL DEFAULT '' COMMENT 'ua',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_from_user` (`from_user`),
  KEY `idx_ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_vote_shuapiaolog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(150) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `realname` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_from_user` (`from_user`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_voteer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `sex` tinyint(1) NOT NULL DEFAULT '0',
  `realname` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `createtime` int(10) unsigned NOT NULL,
  `jifen` int(10) unsigned NOT NULL,
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `chance` int(10) unsigned NOT NULL COMMENT '投票机会',
  `is_user_chance` varchar(10) NOT NULL DEFAULT '' COMMENT '是否使用chance',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_from_user` (`from_user`),
  KEY `idx_mobile` (`mobile`),
  KEY `idx_createtime` (`createtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_fm_photosvote_votelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `tptype` int(10) unsigned NOT NULL COMMENT '投票类型 1 微信页面投票  2 微信会话界面',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被投票用户openid',
  `gou98` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `yaj168` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `chinashanfu` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `cn` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `yamishijie` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `afrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '分享用户openid',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '投票IP',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'ip地区',
  `photosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '票数',
  `createtime` int(10) unsigned NOT NULL COMMENT '投票时间',
  `ordersn` varchar(55) NOT NULL DEFAULT '' COMMENT '投票付款订单号',
  `vote_times` int(10) NOT NULL DEFAULT '1' COMMENT '投票次数',
  `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已删除 0-否 1-是',
  `shuapiao` tinyint(1) unsigned NOT NULL,
  `islp` varchar(100) NOT NULL,
  `mobile_info` varchar(200) NOT NULL DEFAULT '' COMMENT '手机信息',
  `epx` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`),
  KEY `IDX_IP_CREATETIME` (`ip`,`createtime`),
  KEY `IDX_TFROM_USER` (`tfrom_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
";
pdo_run($sql);
if(!pdo_fieldexists('fm_photosvote_advs',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'advname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `advname` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `description` varchar(350) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'link')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'times')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `times` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'ismiaoxian')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `ismiaoxian` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'issuiji')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `issuiji` int(2) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'nexttime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `nexttime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_advs',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('fm_photosvote_advs',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('fm_photosvote_advs',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('fm_photosvote_advs',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_advs')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('fm_photosvote_announce',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_announce',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_announce',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_announce',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_announce',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD `content` varchar(150) NOT NULL DEFAULT '' COMMENT '公告';");
}
if(!pdo_fieldexists('fm_photosvote_announce',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '公告';");
}
if(!pdo_fieldexists('fm_photosvote_announce',  'url')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD `url` varchar(200) NOT NULL DEFAULT '' COMMENT '公告链接';");
}
if(!pdo_fieldexists('fm_photosvote_announce',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '时间';");
}
if(!pdo_indexexists('fm_photosvote_announce',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_announce',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_announce')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `title` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'answer')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `answer` text;");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'key')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `key` varchar(10) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_answer',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('fm_photosvote_answer',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_answer',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_answer',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_answer')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'typeid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `typeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区域ID';");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'shoptitle')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `shoptitle` varchar(50) NOT NULL DEFAULT '' COMMENT '兑奖店面名称';");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'address')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `address` varchar(200) NOT NULL DEFAULT '' COMMENT '兑奖地址';");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'tel')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'pass')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `pass` varchar(20) NOT NULL DEFAULT '' COMMENT '兑奖密码';");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'images')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `images` varchar(200) NOT NULL DEFAULT '' COMMENT '广告或店面图';");
}
if(!pdo_fieldexists('fm_photosvote_awarding',  'carmap')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awarding')." ADD `carmap` varchar(50) NOT NULL COMMENT '地图导航';");
}
if(!pdo_fieldexists('fm_photosvote_awardingtype',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awardingtype')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_awardingtype',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awardingtype')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_awardingtype',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awardingtype')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_awardingtype',  'quyutitle')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awardingtype')." ADD `quyutitle` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名称';");
}
if(!pdo_fieldexists('fm_photosvote_awardingtype',  'orderid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_awardingtype')." ADD `orderid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序';");
}
if(!pdo_fieldexists('fm_photosvote_banners',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_banners',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD `weid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_banners',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_banners',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD `rid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_banners',  'bannername')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD `bannername` varchar(50) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_banners',  'link')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD `link` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_banners',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD `thumb` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_banners',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD `displayorder` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_banners',  'enabled')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD `enabled` int(11) DEFAULT '0';");
}
if(!pdo_indexexists('fm_photosvote_banners',  'indx_weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD KEY `indx_weid` (`weid`);");
}
if(!pdo_indexexists('fm_photosvote_banners',  'indx_enabled')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD KEY `indx_enabled` (`enabled`);");
}
if(!pdo_indexexists('fm_photosvote_banners',  'indx_displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_banners')." ADD KEY `indx_displayorder` (`displayorder`);");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'tid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `tid` varchar(125) NOT NULL COMMENT '帖子的ID';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '帖子作者的openid';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'reply_id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `reply_id` varchar(125) NOT NULL COMMENT '回复评论帖子的ID';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '回复评论帖子的openid';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'to_reply_id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `to_reply_id` int(11) NOT NULL DEFAULT '0' COMMENT '回复评论的id';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'rfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `rfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被回复的评论的作者的openid';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `content` text NOT NULL COMMENT '评论回复内容';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'is_del')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已删除 0-否 1-是';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `status` tinyint(2) DEFAULT '0' COMMENT '是否审核 0-否 1-是';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'storey')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `storey` int(11) NOT NULL DEFAULT '0' COMMENT '绝对楼层';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'zan')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `zan` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `ip` varchar(255) NOT NULL DEFAULT '' COMMENT '回复IP';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'iparr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域';");
}
if(!pdo_fieldexists('fm_photosvote_bbsreply',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD `createtime` int(11) NOT NULL COMMENT '回复时间';");
}
if(!pdo_indexexists('fm_photosvote_bbsreply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_bbsreply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_bbsreply',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_indexexists('fm_photosvote_bbsreply',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_bbsreply')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_fieldexists('fm_photosvote_counter',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_counter',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_counter',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_counter',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD `from_user` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_counter',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD `tfrom_user` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_counter',  'tp_times')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD `tp_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票次数';");
}
if(!pdo_fieldexists('fm_photosvote_counter',  'gift_times')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD `gift_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '送礼物次数';");
}
if(!pdo_fieldexists('fm_photosvote_counter',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '1、总共投票数 2、每天总共投票数 3、同一选手总共投票数 4、同一选手每天总共投票数 5、单公众号总共投票数 6、单公众号每天总共投票数 7、单公众号同一选手总共投票数 8、单公众号同一选手每天总共投票数  9、每天总共送礼物数 10、每天给同一个人总共送礼物数 ';");
}
if(!pdo_fieldexists('fm_photosvote_counter',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间';");
}
if(!pdo_indexexists('fm_photosvote_counter',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_counter',  'idx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD KEY `idx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_counter',  'idx_tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD KEY `idx_tfrom_user` (`tfrom_user`);");
}
if(!pdo_indexexists('fm_photosvote_counter',  'idx_type')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD KEY `idx_type` (`type`);");
}
if(!pdo_indexexists('fm_photosvote_counter',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_counter')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_data',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_data',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_data',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `fromuser` varchar(150) NOT NULL DEFAULT '' COMMENT '分享用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `from_user` varchar(150) NOT NULL DEFAULT '' COMMENT '当前用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `tfrom_user` varchar(150) NOT NULL DEFAULT '' COMMENT '被分享用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享人UID';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'isin')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `isin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否参与';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'visitorstime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间';");
}
if(!pdo_fieldexists('fm_photosvote_data',  'viewnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数';");
}
if(!pdo_indexexists('fm_photosvote_data',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_data',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_data',  'indx_uid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD KEY `indx_uid` (`uid`);");
}
if(!pdo_indexexists('fm_photosvote_data',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_data',  'IDX_TFROM_USER')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD KEY `IDX_TFROM_USER` (`tfrom_user`);");
}
if(!pdo_indexexists('fm_photosvote_data',  'IDX_FROMUSER')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_data')." ADD KEY `IDX_FROMUSER` (`fromuser`);");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `title` varchar(50) NOT NULL COMMENT '奖品名称';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'total')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `total` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'total_winning')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `total_winning` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖数量';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'lingjiangtype')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `lingjiangtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '奖品库存减少方式0为有资格1为提交2为兑奖';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `description` text NOT NULL COMMENT '描述';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'inkind')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `inkind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'activation_code')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `activation_code` varchar(50) NOT NULL COMMENT '激活码';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'activation_url')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `activation_url` varchar(215) NOT NULL COMMENT '激活地址';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'break')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要朋友人数';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'awardpic')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `awardpic` varchar(200) NOT NULL COMMENT '奖品图片';");
}
if(!pdo_fieldexists('fm_photosvote_gift',  'awardpass')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD `awardpass` varchar(20) NOT NULL COMMENT '兑奖密码';");
}
if(!pdo_indexexists('fm_photosvote_gift',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_gift')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'giftid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `giftid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼盒ID';");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'mika')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `mika` varchar(50) NOT NULL COMMENT '密卡字符串';");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'activationurl')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `activationurl` varchar(200) NOT NULL COMMENT '激活地址';");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'typename')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `typename` varchar(20) NOT NULL DEFAULT '' COMMENT '类型说明';");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `description` varchar(50) NOT NULL DEFAULT '' COMMENT '描述';");
}
if(!pdo_fieldexists('fm_photosvote_giftmika',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过';");
}
if(!pdo_indexexists('fm_photosvote_giftmika',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_giftmika')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('fm_photosvote_iplist',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_iplist',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_iplist',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_iplist',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_iplist',  'iparr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域';");
}
if(!pdo_fieldexists('fm_photosvote_iplist',  'ipadd')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD `ipadd` varchar(100) NOT NULL DEFAULT '' COMMENT 'IP区域';");
}
if(!pdo_fieldexists('fm_photosvote_iplist',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '时间';");
}
if(!pdo_indexexists('fm_photosvote_iplist',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_iplist',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_iplist',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplist')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT 'openid';");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP';");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'hitym')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `hitym` varchar(255) NOT NULL DEFAULT '' COMMENT '点击页面';");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `createtime` int(11) NOT NULL COMMENT '初始时间';");
}
if(!pdo_fieldexists('fm_photosvote_iplistlog',  'iparr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域';");
}
if(!pdo_indexexists('fm_photosvote_iplistlog',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_iplistlog',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_iplistlog',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_indexexists('fm_photosvote_iplistlog',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_iplistlog')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'is_open_jifen')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `is_open_jifen` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '积分 1开启0关闭';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'is_open_jifen_sync')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `is_open_jifen_sync` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1同步0关闭';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'is_open_choujiang')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `is_open_choujiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖 1同步0关闭';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'jifen_vote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `jifen_vote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票获得积分';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'jifen_vote_reg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `jifen_vote_reg` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票参赛人获得积分';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'jifen_reg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `jifen_reg` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '报名获得积分';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'jifen_charge')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `jifen_charge` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '充值获得积分';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `title` varchar(50) NOT NULL COMMENT '奖品名称';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'total')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `total` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'total_winning')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `total_winning` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖数量';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'lingjiangtype')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `lingjiangtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '奖品库存减少方式0为有资格1为提交2为兑奖';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `description` text NOT NULL COMMENT '描述';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'inkind')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `inkind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'activation_code')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `activation_code` varchar(50) NOT NULL COMMENT '激活码';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'activation_url')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `activation_url` varchar(215) NOT NULL COMMENT '激活地址';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'break')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要朋友人数';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'awardpic')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `awardpic` varchar(512) NOT NULL COMMENT '奖品图片';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'awardpass')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `awardpass` varchar(20) NOT NULL COMMENT '兑奖密码';");
}
if(!pdo_fieldexists('fm_photosvote_jifen',  'jifen_gift_times')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD `jifen_gift_times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每天最多送几次礼物';");
}
if(!pdo_indexexists('fm_photosvote_jifen',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_jifen',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'gifttitle')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `gifttitle` varchar(150) NOT NULL COMMENT '礼物名称';");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'jifen')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `jifen` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分';");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'piaoshu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `piaoshu` int(11) NOT NULL DEFAULT '0' COMMENT '票数';");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'images')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `images` varchar(150) NOT NULL COMMENT '图片';");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `description` varchar(150) NOT NULL COMMENT '描述';");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_fieldexists('fm_photosvote_jifen_gift',  'dhnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD `dhnum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '已兑换数量';");
}
if(!pdo_indexexists('fm_photosvote_jifen_gift',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_jifen_gift',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_jifen_gift')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('fm_photosvote_message',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_message',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_message',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_message',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_message',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_message',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `from_user` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_message',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `tfrom_user` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_message',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `content` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_message',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0、未读 1、已读  2、已删除';");
}
if(!pdo_fieldexists('fm_photosvote_message',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1、投票信息 2、被投票信息  3、报名信息 4、充值信息 5、得奖信息 6、积分变更 7、开通信息';");
}
if(!pdo_fieldexists('fm_photosvote_message',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `lasttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_message',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('fm_photosvote_message',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_message',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_message',  'idx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD KEY `idx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_message',  'idx_tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_message')." ADD KEY `idx_tfrom_user` (`tfrom_user`);");
}
if(!pdo_fieldexists('fm_photosvote_onlyoauth',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_onlyoauth',  'siteid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD `siteid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_onlyoauth',  'fmauthtoken')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD `fmauthtoken` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_onlyoauth',  'modules')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD `modules` varchar(120) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_onlyoauth',  'oauthurl')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD `oauthurl` varchar(120) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_onlyoauth',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_onlyoauth',  'visitorsip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD `visitorsip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip';");
}
if(!pdo_fieldexists('fm_photosvote_onlyoauth',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('fm_photosvote_onlyoauth',  'idx_siteid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD KEY `idx_siteid` (`siteid`);");
}
if(!pdo_indexexists('fm_photosvote_onlyoauth',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_onlyoauth')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_order',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `title` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `tfrom_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `ordersn` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'price')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `price` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'vote_times')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `vote_times` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `realname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `mobile` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'payyz')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `payyz` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'paytype')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额，2为在线，3为到付';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'transid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `transid` varchar(30) NOT NULL DEFAULT '0' COMMENT '微信支付单号';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `remark` varchar(1000) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'paytime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `paytime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_order',  'fromuser')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `fromuser` varchar(100) NOT NULL COMMENT '分享用户';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'iparr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `iparr` varchar(200) NOT NULL COMMENT '地址';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `ip` varchar(100) NOT NULL COMMENT 'ip地址';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'ispayvote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `ispayvote` tinyint(1) unsigned NOT NULL COMMENT '0默认 1为支付后成功投票 2为支付成功后未投票成功';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `avatar` varchar(200) NOT NULL COMMENT '头像';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'jifen')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `jifen` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分';");
}
if(!pdo_fieldexists('fm_photosvote_order',  'giftid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD `giftid` int(10) unsigned NOT NULL COMMENT '礼物id';");
}
if(!pdo_indexexists('fm_photosvote_order',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_order',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_order',  'idx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD KEY `idx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_order',  'idx_tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD KEY `idx_tfrom_user` (`tfrom_user`);");
}
if(!pdo_indexexists('fm_photosvote_order',  'idx_ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_order')." ADD KEY `idx_ordersn` (`ordersn`);");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'num')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `num` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `ordersn` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `title` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `from_user` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'remark')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `remark` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0、未读 1、已读  2、已删除';");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1、投票信息 2、被投票信息  3、报名信息 4、充值信息 5、得奖信息 6、积分变更 7、开通信息';");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `lasttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_orderlog',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('fm_photosvote_orderlog',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_orderlog',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_orderlog',  'idx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD KEY `idx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_orderlog',  'idx_ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_orderlog')." ADD KEY `idx_ordersn` (`ordersn`);");
}
if(!pdo_fieldexists('fm_photosvote_pnametag',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_pnametag')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_pnametag',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_pnametag')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_pnametag',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_pnametag')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_pnametag',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_pnametag')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_pnametag',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_pnametag')." ADD `title` varchar(350) NOT NULL DEFAULT '' COMMENT '默认口号';");
}
if(!pdo_fieldexists('fm_photosvote_pnametag',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_pnametag')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '时间';");
}
if(!pdo_indexexists('fm_photosvote_pnametag',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_pnametag')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_pnametag',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_pnametag')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'uid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'tagid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `tagid` int(10) unsigned NOT NULL COMMENT '分组id';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被投票用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别，1、男 2、女 0 、未知';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'photo')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `photo` varchar(200) NOT NULL DEFAULT '' COMMENT '照片';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'music')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `music` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'mediaid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `mediaid` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐id';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'timelength')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `timelength` varchar(200) NOT NULL DEFAULT '' COMMENT '时间轴';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'voice')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `voice` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'vedio')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `vedio` varchar(200) NOT NULL DEFAULT '' COMMENT '视频';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'youkuurl')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `youkuurl` varchar(200) NOT NULL DEFAULT '' COMMENT '视频';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'fmmid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `fmmid` varchar(200) NOT NULL DEFAULT '' COMMENT '识别';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'picarr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `picarr` varchar(2000) NOT NULL DEFAULT '' COMMENT '照片组';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `description` text NOT NULL COMMENT '简介，描述';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'photoname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `photoname` varchar(50) NOT NULL DEFAULT '' COMMENT '照片名字';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'job')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `job` varchar(20) NOT NULL DEFAULT '' COMMENT '职业';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'xingqu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `xingqu` varchar(20) NOT NULL DEFAULT '' COMMENT '兴趣';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'weixin')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `weixin` varchar(255) NOT NULL DEFAULT '' COMMENT '联系微信号';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'qqhao')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `qqhao` varchar(20) NOT NULL DEFAULT '' COMMENT '联系QQ号码';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'email')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `email` varchar(255) NOT NULL DEFAULT '' COMMENT '联系邮箱';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'address')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `address` varchar(100) NOT NULL DEFAULT '' COMMENT '联系地址';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'm')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `m` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'wikaifa')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `wikaifa` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'weiyiqibj')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `weiyiqibj` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'chinashanfu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `chinashanfu` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'photosnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `photosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '票数';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'xnphotosnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `xnphotosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟票数';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'hits')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人气';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'xnhits')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `xnhits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人气';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'yaoqingnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `yaoqingnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请量';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'zans')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `zans` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'ewm')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `ewm` varchar(200) NOT NULL DEFAULT '' COMMENT '二维码地址';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'isadmin')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `isadmin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否设置为管理员';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'istuijian')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `istuijian` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否设置为推荐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'limitsd')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `limitsd` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '限速';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'createip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `createip` varchar(50) NOT NULL DEFAULT '' COMMENT '创建IP';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'lastip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `lastip` varchar(50) NOT NULL DEFAULT '' COMMENT '编辑IP';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'iparr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'ip地区';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `lasttime` int(10) unsigned NOT NULL COMMENT '最后编辑时间';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'sharetime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'sharenum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `sharenum` int(10) unsigned NOT NULL COMMENT '最后分享';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '注册时间';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'unionid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `unionid` varchar(255) NOT NULL DEFAULT '' COMMENT '统一用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `ordersn` varchar(55) NOT NULL DEFAULT '' COMMENT '付款订单号';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'tagpid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `tagpid` int(10) unsigned NOT NULL COMMENT '分组pid';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'tagpidtest')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `tagpidtest` int(10) unsigned NOT NULL COMMENT 'tagpidtest';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'unphotosnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `unphotosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '减少的票数';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'unhits')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `unhits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '减少的人气';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'haibao')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `haibao` varchar(200) NOT NULL DEFAULT '' COMMENT '海报地址';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'tagtid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `tagtid` int(10) unsigned NOT NULL COMMENT '分组pid_3';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'www')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `www` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'wx')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `wx` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'schoolid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `schoolid` int(10) unsigned NOT NULL COMMENT '学校id';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'sourceid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `sourceid` int(10) unsigned NOT NULL COMMENT '来源id';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'musictime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `musictime` int(10) unsigned NOT NULL COMMENT '音乐时间';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'voicetime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `voicetime` int(10) unsigned NOT NULL COMMENT '录音时间';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'vediotime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `vediotime` int(10) unsigned NOT NULL COMMENT '视频时间';");
}
if(!pdo_fieldexists('fm_photosvote_provevote',  'giftnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD `giftnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收到的礼物数';");
}
if(!pdo_indexexists('fm_photosvote_provevote',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_provevote',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_indexexists('fm_photosvote_provevote',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_provevote',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_provevote',  'uk_uid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote')." ADD KEY `uk_uid` (`uid`);");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'musicname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `musicname` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'photoname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `photoname` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'picarr_1_name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `picarr_1_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'picarr_2_name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `picarr_2_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'picarr_3_name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `picarr_3_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'picarr_4_name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `picarr_4_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'picarr_5_name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `picarr_5_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'picarr_6_name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `picarr_6_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'picarr_7_name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `picarr_7_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'picarr_8_name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `picarr_8_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'musicnamefop')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `musicnamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'voicename')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `voicename` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'voicenamefop')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `voicenamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'vedioname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `vedioname` varchar(200) NOT NULL DEFAULT '' COMMENT '视频';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_name',  'vedionamefop')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD `vedionamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '视频';");
}
if(!pdo_indexexists('fm_photosvote_provevote_name',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_provevote_name',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_provevote_name',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_name')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'mid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `mid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'lastmid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `lastmid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'photos')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `photos` varchar(200) NOT NULL DEFAULT '' COMMENT '图片';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'photoname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `photoname` varchar(200) NOT NULL DEFAULT '' COMMENT '图片名字';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'isfm')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `isfm` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否设置为封面';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_picarr',  'imgpath')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD `imgpath` varchar(200) NOT NULL DEFAULT '' COMMENT '绝对路径';");
}
if(!pdo_indexexists('fm_photosvote_provevote_picarr',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_provevote_picarr',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_provevote_picarr',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_picarr')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `from_user` varchar(555) NOT NULL DEFAULT '' COMMENT 'openid';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'mediaid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `mediaid` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐id';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'timelength')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `timelength` varchar(200) NOT NULL DEFAULT '' COMMENT '时间轴';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'voice')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `voice` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'fmmid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `fmmid` varchar(200) NOT NULL DEFAULT '' COMMENT '识别';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `ip` varchar(255) NOT NULL DEFAULT '' COMMENT 'IP';");
}
if(!pdo_fieldexists('fm_photosvote_provevote_voice',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD `createtime` int(11) NOT NULL COMMENT '初始时间';");
}
if(!pdo_indexexists('fm_photosvote_provevote_voice',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_provevote_voice',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_indexexists('fm_photosvote_provevote_voice',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD KEY `indx_from_user` (`from_user`(255));");
}
if(!pdo_indexexists('fm_photosvote_provevote_voice',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_provevote_voice')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `acid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `from_user` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `tfrom_user` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `type` varchar(10) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'extra')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `extra` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'qrcid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `qrcid` bigint(20) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'keyword')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `keyword` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'model')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `model` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `ticket` varchar(250) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'url')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `url` varchar(256) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'imgurl')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `imgurl` varchar(256) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'expire')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `expire` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'subnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `subnum` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode',  'scene_str')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD `scene_str` varchar(64) NOT NULL;");
}
if(!pdo_indexexists('fm_photosvote_qrcode',  'idx_qrcid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD KEY `idx_qrcid` (`qrcid`);");
}
if(!pdo_indexexists('fm_photosvote_qrcode',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD KEY `uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_qrcode',  'ticket')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode')." ADD KEY `ticket` (`ticket`);");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'acid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `acid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'qid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `qid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'openid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `type` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'qrcid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `qrcid` bigint(20) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `name` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qrcode_stat',  'scene_str')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qrcode_stat')." ADD `scene_str` varchar(64) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `type` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `lasttime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_qunfa',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('fm_photosvote_qunfa',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_qunfa',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_qunfa',  'idx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD KEY `idx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_qunfa',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_qunfa')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '开关状态';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `title` varchar(50) NOT NULL COMMENT '规则标题';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'picture')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `picture` varchar(225) NOT NULL COMMENT '规则图片';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'cn')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `cn` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'com')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'start_time')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `start_time` int(10) unsigned NOT NULL COMMENT '开始时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'end_time')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `end_time` int(10) unsigned NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'tstart_time')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `tstart_time` int(10) unsigned NOT NULL COMMENT '投票开始时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'tend_time')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `tend_time` int(10) unsigned NOT NULL COMMENT '投票结束时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'bstart_time')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `bstart_time` int(10) unsigned NOT NULL COMMENT '报名开始时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'bend_time')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `bend_time` int(10) unsigned NOT NULL COMMENT '报名结束时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'ttipstart')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `ttipstart` varchar(255) NOT NULL COMMENT '投票开始时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'ttipend')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `ttipend` varchar(255) NOT NULL COMMENT '投票结束时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'btipstart')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `btipstart` varchar(255) NOT NULL COMMENT '报名开始时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'btipend')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `btipend` varchar(255) NOT NULL COMMENT '报名结束时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'isdaojishi')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `isdaojishi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '倒计时开关';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'ttipvote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `ttipvote` varchar(100) NOT NULL COMMENT '提示';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'votetime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `votetime` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `description` varchar(255) NOT NULL COMMENT '描述';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'content')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `content` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'stopping')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `stopping` varchar(225) NOT NULL COMMENT 'fx图片';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'nostart')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `nostart` varchar(225) NOT NULL COMMENT 'fx图片';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'end')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `end` varchar(225) NOT NULL COMMENT 'fx图片';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'qiniu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `qiniu` varchar(600) NOT NULL COMMENT '七牛';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'templates')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `templates` varchar(50) NOT NULL DEFAULT 'default' COMMENT '默认模板';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'binduniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `binduniacid` varchar(150) NOT NULL COMMENT '多公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'unimoshi')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `unimoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '公众号模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'menuid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `menuid` int(10) unsigned NOT NULL COMMENT '底部导航ID';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'kftel')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `kftel` varchar(30) NOT NULL COMMENT '客服电话';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'net')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `net` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_reply',  'isadmin')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD `isadmin` tinyint(1) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('fm_photosvote_reply',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_reply',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'zbgcolor')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `zbgcolor` varchar(10) NOT NULL COMMENT '背景色';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'zbg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `zbg` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'voicebg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `voicebg` varchar(125) NOT NULL COMMENT '录音室背景';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'zbgtj')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `zbgtj` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'topbgcolor')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `topbgcolor` varchar(10) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'topbg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `topbg` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'topbgtext')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `topbgtext` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'topbgrightcolor')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `topbgrightcolor` varchar(10) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'topbgright')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `topbgright` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'foobg1')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `foobg1` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'foobg2')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `foobg2` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'foobgtextn')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `foobgtextn` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'foobgtexty')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `foobgtexty` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'foobgtextmore')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `foobgtextmore` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'foobgmorecolor')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `foobgmorecolor` varchar(10) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'foobgmore')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `foobgmore` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'bodytextcolor')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `bodytextcolor` varchar(10) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'bodynumcolor')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `bodynumcolor` varchar(10) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'inputcolor')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `inputcolor` varchar(10) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'bodytscolor')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `bodytscolor` varchar(10) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'bodytsbg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `bodytsbg` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'xinbg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `xinbg` varchar(125) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'copyrightcolor')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `copyrightcolor` varchar(10) NOT NULL COMMENT '背景图';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'photosvote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `photosvote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'tuser')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `tuser` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'paihang')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `paihang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'reg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `reg` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'des')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `des` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'tags')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `tags` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'other')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `other` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启diy';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'rbody_photosvote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `rbody_photosvote` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'rbody_tuser')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `rbody_tuser` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'rbody_paihang')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `rbody_paihang` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'rbody_reg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `rbody_reg` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'rbody_des')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `rbody_des` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'rbody_tags')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `rbody_tags` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_reply_body',  'qxbfooter')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD `qxbfooter` text NOT NULL COMMENT '内容';");
}
if(!pdo_indexexists('fm_photosvote_reply_body',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_body')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'istopheader')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `istopheader` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '顶部导航';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'ipannounce')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `ipannounce` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '顶部公告';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isbgaudio')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isbgaudio` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '背景音乐';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'bgmusic')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `bgmusic` varchar(125) NOT NULL COMMENT '背景音乐链接';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isedes')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isedes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启首页显示说明';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'tmoshi')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `tmoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '首页显示模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'indextpxz')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `indextpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '首页列表显示数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'indexorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `indexorder` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '首页排序';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'indexpx')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `indexpx` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '活动首页显示,0 按最新排序 1 按人气排序 3 按投票数排序';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'phbtpxz')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `phbtpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人气榜显示个数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'zanzhums')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `zanzhums` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '赞助商显示';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'xuninum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `xuninum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'hits')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击量';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'xuninumtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'xuninuminitial')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'xuninumending')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `xuninumending` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '虚拟随机数值2';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'xuninum_time')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟随机数值';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isrealname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入姓名0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'ismobile')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入手机号0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isweixin')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isweixin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入微信号0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isqqhao')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isqqhao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isemail')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isjob')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isjob` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isxingqu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isxingqu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入兴趣0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isaddress')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isindex')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isindex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页显示0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isreg')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isreg` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否报名页显示0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isdes')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isdes` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否描述页显示0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isvotexq')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isvotexq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否详情页显示0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'ispaihang')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `ispaihang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否排行页显示0为不需要1为需要';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'lapiao')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `lapiao` varchar(5) NOT NULL COMMENT '拉票';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'sharename')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `sharename` varchar(2) NOT NULL COMMENT '分享名字';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'tpname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `tpname` varchar(100) NOT NULL COMMENT '投票名称';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'tpsname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `tpsname` varchar(100) NOT NULL COMMENT '投票数名称';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'rqname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `rqname` varchar(100) NOT NULL COMMENT '人气名称';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'csrs')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `csrs` varchar(10) NOT NULL COMMENT '参赛作品';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'ljtp')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `ljtp` varchar(10) NOT NULL COMMENT '累计投票';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'cyrs')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `cyrs` varchar(10) NOT NULL COMMENT '参与人数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'iscopyright')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `iscopyright` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0显示公众号版权1为显示自定义版权';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'copyrighturl')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `copyrighturl` varchar(255) NOT NULL COMMENT '版权链接';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'copyright')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `copyright` varchar(50) NOT NULL COMMENT '版权';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isvoteusers')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isvoteusers` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示投票人';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'regtitlearr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `regtitlearr` text NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'csrs_total')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `csrs_total` varchar(10) NOT NULL COMMENT '参赛作品总数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'ljtp_total')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `ljtp_total` varchar(10) NOT NULL COMMENT '累计投票总数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'xunips')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `xunips` varchar(10) NOT NULL COMMENT '虚拟累计投票总数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'cyrs_total')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `cyrs_total` varchar(10) NOT NULL COMMENT '参与人数总数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'unphotosnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `unphotosnum` varchar(10) NOT NULL COMMENT '取消票数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'iscontent')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `iscontent` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启首页显示说明';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isphotosname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isphotosname` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'isregdes')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `isregdes` tinyint(1) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'openqr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `openqr` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启海报二维码';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'qrset')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `qrset` varchar(255) NOT NULL COMMENT '二维码设置';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'open_vote_count')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `open_vote_count` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启显示投票数据';");
}
if(!pdo_fieldexists('fm_photosvote_reply_display',  'open_vote_size')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD `open_vote_size` varchar(255) NOT NULL COMMENT '显示前多少人';");
}
if(!pdo_indexexists('fm_photosvote_reply_display',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_display')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'command')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `command` varchar(10) NOT NULL COMMENT '报名命令';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'tcommand')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `tcommand` varchar(10) NOT NULL COMMENT '报名命令';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'ishuodong')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `ishuodong` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 开启';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'huodongname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `huodongname` varchar(20) NOT NULL COMMENT '活动名字';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'huodongdes')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `huodongdes` varchar(50) NOT NULL COMMENT '活动简介';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'huodongurl')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `huodongurl` varchar(125) NOT NULL COMMENT '活动链接';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'hhhdpicture')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `hhhdpicture` varchar(125) NOT NULL COMMENT '活动图片';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'regmessagetemplate')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `regmessagetemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知报名成功';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'messagetemplate')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `messagetemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'shmessagetemplate')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `shmessagetemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知投票审核成功';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'fmqftemplate')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `fmqftemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知群发消息';");
}
if(!pdo_fieldexists('fm_photosvote_reply_huihua',  'msgtemplate')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD `msgtemplate` varchar(50) NOT NULL COMMENT '评论通知消息';");
}
if(!pdo_indexexists('fm_photosvote_reply_huihua',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_huihua')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'subscribe')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `subscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否强制需要关注公众号才能参与';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'shareurl')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `shareurl` varchar(255) NOT NULL COMMENT '活动网址';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'sharetitle')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `sharetitle` varchar(50) NOT NULL COMMENT '分享标题';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'sharephoto')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `sharephoto` varchar(225) NOT NULL COMMENT 'fx图片';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'sharecontent')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `sharecontent` varchar(100) NOT NULL COMMENT '分享简介';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'subscribedes')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `subscribedes` varchar(200) NOT NULL COMMENT '分享提示语';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'sharelink')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `sharelink` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义分享链接';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'isopentime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `isopentime` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启限制跳转';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'open_limittime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `open_limittime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '限制时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply_share',  'open_url')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD `open_url` varchar(255) NOT NULL COMMENT '指定跳转链接';");
}
if(!pdo_indexexists('fm_photosvote_reply_share',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_share')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'iscode')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `iscode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启投票验证码';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'codekey')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `codekey` varchar(255) NOT NULL COMMENT '验证码key';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'addpvapp')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `addpvapp` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '前端是否允许用户报名';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'isfans')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'mediatype')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `mediatype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'mediatypem')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `mediatypem` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'mediatypev')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `mediatypev` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'voicemoshi')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `voicemoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '语音室模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'moshi')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `moshi` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '展示模式： 1 相册模式  2 详情模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'webinfo')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `webinfo` text NOT NULL COMMENT '内容';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'cqtp')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `cqtp` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可重复投票';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'tpsh')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `tpsh` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '投稿是否需审核';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'isbbsreply')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `isbbsreply` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启评论';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'tmyushe')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `tmyushe` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '弹幕评论是否同步到数据库';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'tmreply')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `tmreply` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '弹幕评论是否同步到数据库';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'isipv')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `isipv` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启IP作弊限制';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'ipturl')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `ipturl` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '存在作弊ip后是否继续允许查看，投票，评论等';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'ipstopvote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `ipstopvote` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '存在作弊ip后是否继续允许查看，投票，评论等';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'iplocallimit')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `iplocallimit` varchar(100) NOT NULL COMMENT '地区限制';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'iplocaldes')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `iplocaldes` varchar(100) NOT NULL COMMENT '地区限制';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'tpxz')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `tpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投稿照片数限制';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'autolitpic')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `autolitpic` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '裁剪大小';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'autozl')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `autozl` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '裁剪质量';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'limitip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `limitip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票ip每天限制数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'daytpxz')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `daytpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日投票数限制';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'dayonetp')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `dayonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一选手投票数限制';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'allonetp')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `allonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一选手最高投票数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'fansmostvote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `fansmostvote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最高投票数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'userinfo')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `userinfo` varchar(200) NOT NULL COMMENT '输入姓名或手机时的提示词';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'votesuccess')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `votesuccess` varchar(200) NOT NULL COMMENT '投票成功提示语';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'limitsd')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `limitsd` varchar(20) NOT NULL COMMENT '全体限速';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'limitsdps')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `limitsdps` varchar(20) NOT NULL COMMENT '全体限速投机票';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'unimoshi')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `unimoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '公众号模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'votepay')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `votepay` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启投票付费';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'votepaytitle')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `votepaytitle` varchar(50) NOT NULL COMMENT '付费名称';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'votepaydes')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `votepaydes` varchar(200) NOT NULL COMMENT '付费描述';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'votepayfee')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `votepayfee` varchar(50) NOT NULL COMMENT '付费金额';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'regpay')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `regpay` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启报名付费';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'regpaytitle')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `regpaytitle` varchar(50) NOT NULL COMMENT '报名名称';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'regpaydes')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `regpaydes` varchar(200) NOT NULL COMMENT '报名描述';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'regpayfee')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `regpayfee` varchar(50) NOT NULL COMMENT '报名金额';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'uni_fansmostvote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `uni_fansmostvote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '多用户最高投票数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'uni_daytpxz')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `uni_daytpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '多每日投票数限制';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'uni_allonetp')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `uni_allonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '多同一选手最高投票数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'uni_dayonetp')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `uni_dayonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '多同一选手投票数限制';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'uni_all_users')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `uni_all_users` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看所有用户';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'votenumpiao')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `votenumpiao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启投票用户自定投票数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'voteerinfo')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `voteerinfo` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启投票用户填写信息';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'usersmostvote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `usersmostvote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '选手最高得票数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'votestarttime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `votestarttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'voteendtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `voteendtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'ismediatypem')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `ismediatypem` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'ismediatypev')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `ismediatypev` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'ismediatype')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `ismediatype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'codekeykey')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `codekeykey` varchar(255) NOT NULL COMMENT '验证码key';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'giftvote')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `giftvote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启送礼物';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'open_smart')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `open_smart` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启智能刷票模式';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'open_lbs_localtion')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `open_lbs_localtion` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启定位';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'open_lbs_type')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `open_lbs_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '定位方式 ： 1、H5定位  2、腾讯lbs定位 3、百度lbs定位';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'open_lbs_key_baidu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `open_lbs_key_baidu` varchar(50) NOT NULL COMMENT 'key';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'open_lbs_key_qq')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `open_lbs_key_qq` varchar(50) NOT NULL COMMENT 'key';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'open_lbs_link')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `open_lbs_link` varchar(100) NOT NULL COMMENT 'link';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'limitsd_voter')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `limitsd_voter` varchar(20) NOT NULL COMMENT '全体投票者限速';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'limitsdps_voter')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `limitsdps_voter` varchar(20) NOT NULL COMMENT '全体投票者限速投机票';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'isanswer')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `isanswer` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用答题';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'answer_times')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `answer_times` int(10) unsigned NOT NULL COMMENT '答题次数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'answer_times_ps')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `answer_times_ps` int(10) unsigned NOT NULL COMMENT '答对获取的投票数';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'bbsreply_status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `bbsreply_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '评论是否审核';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'opendx')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `opendx` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启多选投票';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'limittimes')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `limittimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最多选择几人';");
}
if(!pdo_fieldexists('fm_photosvote_reply_vote',  'isopengiftlist')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD `isopengiftlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启送礼物列表';");
}
if(!pdo_indexexists('fm_photosvote_reply_vote',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_reply_vote')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_fieldexists('fm_photosvote_school',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_school',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_school',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_school',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_school',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `parentid` int(10) unsigned NOT NULL COMMENT 'pid';");
}
if(!pdo_fieldexists('fm_photosvote_school',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_school',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `description` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_school',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `icon` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_school',  'images')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `images` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_school',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_school',  'piaoshu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `piaoshu` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_school',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('fm_photosvote_school',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_school',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_school')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_source',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_source',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_source',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_source',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_source',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `parentid` int(10) unsigned NOT NULL COMMENT 'pid';");
}
if(!pdo_fieldexists('fm_photosvote_source',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_source',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `description` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_source',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `icon` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_source',  'images')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `images` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_source',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_source',  'piaoshu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `piaoshu` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_source',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_indexexists('fm_photosvote_source',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_source',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_source')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'icon')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `icon` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'displayorder')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `displayorder` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'parentid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `parentid` int(10) unsigned NOT NULL COMMENT 'pid';");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `description` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'piaoshu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `piaoshu` int(10) unsigned NOT NULL COMMENT 'piaoshu';");
}
if(!pdo_fieldexists('fm_photosvote_tags',  'images')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD `images` varchar(255) NOT NULL DEFAULT '';");
}
if(!pdo_indexexists('fm_photosvote_tags',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_tags',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_tags')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'name')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `name` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'title')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `title` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'version')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `version` varchar(64) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'author')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `author` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'description')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `description` varchar(500) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'thumb')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `thumb` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'url')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `url` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'type')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `type` varchar(20) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'sections')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `sections` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_fieldexists('fm_photosvote_templates',  'stylename')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates')." ADD `stylename` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'stylename')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `stylename` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'pagename')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `pagename` varchar(255) NOT NULL DEFAULT '' COMMENT '页面名称';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'pagetype')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `pagetype` tinyint(3) NOT NULL DEFAULT '0' COMMENT '页面类型';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'pageinfo')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `pageinfo` text NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'keyword')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `keyword` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'savetime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `savetime` varchar(255) NOT NULL DEFAULT '' COMMENT '页面最后保存时间';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'setdefault')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `setdefault` tinyint(3) NOT NULL DEFAULT '0' COMMENT '默认页面';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'datas')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `datas` text NOT NULL COMMENT '数据';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '创建时间';");
}
if(!pdo_indexexists('fm_photosvote_templates_designer',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_templates_designer',  'idx_pagetype')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD KEY `idx_pagetype` (`pagetype`);");
}
if(!pdo_indexexists('fm_photosvote_templates_designer',  'idx_keyword')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer')." ADD KEY `idx_keyword` (`keyword`);");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer_menu',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer_menu',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD `uniacid` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer_menu',  'menuname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD `menuname` varchar(255) DEFAULT '';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer_menu',  'isdefault')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD `isdefault` tinyint(3) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer_menu',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD `createtime` int(11) DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer_menu',  'menus')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD `menus` text;");
}
if(!pdo_fieldexists('fm_photosvote_templates_designer_menu',  'params')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD `params` text;");
}
if(!pdo_indexexists('fm_photosvote_templates_designer_menu',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_templates_designer_menu',  'idx_isdefault')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD KEY `idx_isdefault` (`isdefault`);");
}
if(!pdo_indexexists('fm_photosvote_templates_designer_menu',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_templates_designer_menu')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'giftid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `giftid` int(10) unsigned NOT NULL COMMENT '礼物id';");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'giftnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `giftnum` int(10) unsigned NOT NULL COMMENT '礼物数量';");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `from_user` varchar(100) NOT NULL COMMENT '用户';");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `tfrom_user` varchar(100) NOT NULL COMMENT '送给的用户';");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1、未使用  2、已使用';");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间';");
}
if(!pdo_fieldexists('fm_photosvote_user_gift',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_indexexists('fm_photosvote_user_gift',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_user_gift',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_gift')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `uniacid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'giftid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `giftid` int(10) unsigned NOT NULL COMMENT '礼物id';");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'giftnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `giftnum` int(10) unsigned NOT NULL COMMENT '礼物数量';");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `from_user` varchar(100) NOT NULL COMMENT '用户';");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `tfrom_user` varchar(100) NOT NULL COMMENT '送给的用户';");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1、购买 2、积分 3、赠送';");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间';");
}
if(!pdo_fieldexists('fm_photosvote_user_zsgift',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间';");
}
if(!pdo_indexexists('fm_photosvote_user_zsgift',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_user_zsgift',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_user_zsgift')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiao',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiao',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiao',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiao',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiao',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD `from_user` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiao',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD `ip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip';");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiao',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiao',  'ua')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD `ua` varchar(300) NOT NULL DEFAULT '' COMMENT 'ua';");
}
if(!pdo_indexexists('fm_photosvote_vote_shuapiao',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_vote_shuapiao',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_vote_shuapiao',  'idx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD KEY `idx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_vote_shuapiao',  'idx_ip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiao')." ADD KEY `idx_ip` (`ip`);");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `from_user` varchar(150) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `sex` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `realname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `mobile` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `ip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip';");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'iparr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域';");
}
if(!pdo_fieldexists('fm_photosvote_vote_shuapiaolog',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_indexexists('fm_photosvote_vote_shuapiaolog',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_vote_shuapiaolog',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_vote_shuapiaolog',  'idx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD KEY `idx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_vote_shuapiaolog',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_vote_shuapiaolog')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `weid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `rid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `from_user` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `nickname` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `avatar` varchar(255) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'sex')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `sex` tinyint(1) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'realname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `realname` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'mobile')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `mobile` varchar(30) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'status')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `status` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `ip` varchar(100) NOT NULL DEFAULT '' COMMENT 'ip';");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'iparr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域';");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `createtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'jifen')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `jifen` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'lasttime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间';");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'chance')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `chance` int(10) unsigned NOT NULL COMMENT '投票机会';");
}
if(!pdo_fieldexists('fm_photosvote_voteer',  'is_user_chance')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD `is_user_chance` varchar(10) NOT NULL DEFAULT '' COMMENT '是否使用chance';");
}
if(!pdo_indexexists('fm_photosvote_voteer',  'idx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD KEY `idx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_voteer',  'idx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD KEY `idx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_voteer',  'idx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD KEY `idx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_voteer',  'idx_mobile')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD KEY `idx_mobile` (`mobile`);");
}
if(!pdo_indexexists('fm_photosvote_voteer',  'idx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_voteer')." ADD KEY `idx_createtime` (`createtime`);");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'id')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `id` int(10) unsigned NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'weid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `weid` int(10) unsigned NOT NULL COMMENT '公众号ID';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `uniacid` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'tptype')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `tptype` int(10) unsigned NOT NULL COMMENT '投票类型 1 微信页面投票  2 微信会话界面';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'tfrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被投票用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'gou98')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `gou98` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'yaj168')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `yaj168` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'chinashanfu')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `chinashanfu` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'cn')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `cn` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'com')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `com` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'yamishijie')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `yamishijie` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'afrom_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `afrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '分享用户openid';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'avatar')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'nickname')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'ip')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '投票IP';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'iparr')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'ip地区';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'photosnum')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `photosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '票数';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `createtime` int(10) unsigned NOT NULL COMMENT '投票时间';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'ordersn')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `ordersn` varchar(55) NOT NULL DEFAULT '' COMMENT '投票付款订单号';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'vote_times')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `vote_times` int(10) NOT NULL DEFAULT '1' COMMENT '投票次数';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'is_del')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已删除 0-否 1-是';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'shuapiao')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `shuapiao` tinyint(1) unsigned NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'islp')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `islp` varchar(100) NOT NULL;");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'mobile_info')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `mobile_info` varchar(200) NOT NULL DEFAULT '' COMMENT '手机信息';");
}
if(!pdo_fieldexists('fm_photosvote_votelog',  'epx')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD `epx` varchar(30) NOT NULL DEFAULT '0' COMMENT '0';");
}
if(!pdo_indexexists('fm_photosvote_votelog',  'indx_uniacid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD KEY `indx_uniacid` (`uniacid`);");
}
if(!pdo_indexexists('fm_photosvote_votelog',  'indx_rid')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD KEY `indx_rid` (`rid`);");
}
if(!pdo_indexexists('fm_photosvote_votelog',  'indx_createtime')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD KEY `indx_createtime` (`createtime`);");
}
if(!pdo_indexexists('fm_photosvote_votelog',  'indx_from_user')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD KEY `indx_from_user` (`from_user`);");
}
if(!pdo_indexexists('fm_photosvote_votelog',  'IDX_IP_CREATETIME')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD KEY `IDX_IP_CREATETIME` (`ip`,`createtime`);");
}
if(!pdo_indexexists('fm_photosvote_votelog',  'IDX_TFROM_USER')) {
	pdo_query("ALTER TABLE ".tablename('fm_photosvote_votelog')." ADD KEY `IDX_TFROM_USER` (`tfrom_user`);");
}

?>