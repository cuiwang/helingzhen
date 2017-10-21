<?php

pdo_query("CREATE TABLE IF NOT EXISTS `ims_yike_complete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `fenxiang_1` int(11) NOT NULL COMMENT '第一次分享1.完成0.未完成',
  `fenxiang_2` int(11) NOT NULL COMMENT '第二次分享1.完成0.未完成',
  `fenxiang_3` int(11) NOT NULL COMMENT '第三次分享1.完成0.未完成',
  `jingcai` int(11) NOT NULL COMMENT '第一次竞猜1.完成0.未完成',
  `tine` varchar(225) NOT NULL COMMENT '任务完成时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='已完成的任务';
CREATE TABLE IF NOT EXISTS `ims_yike_guess_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '明细 ',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `money` varchar(255) DEFAULT NULL COMMENT '收支金额',
  `type` int(11) DEFAULT NULL COMMENT '1 收入 2 支出',
  `balance` varchar(255) DEFAULT NULL COMMENT '余额',
  `create_time` varchar(255) DEFAULT NULL COMMENT '生成时间',
  `name` varchar(255) NOT NULL COMMENT '明细名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=765 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_guess_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '幻灯片id',
  `name` varchar(255) DEFAULT NULL COMMENT '幻灯片标题',
  `image` varchar(255) DEFAULT NULL COMMENT '幻灯片图',
  `href` varchar(255) DEFAULT NULL COMMENT '幻灯片链接',
  `is_show` int(1) DEFAULT '0' COMMENT '是否显示',
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `sort` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_guess_classify` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `parents_id` int(11) DEFAULT '0' COMMENT '上级分类id',
  `name` varchar(255) DEFAULT NULL COMMENT '分类名称',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  `link` varchar(255) DEFAULT NULL COMMENT '分类链接',
  `image` varchar(255) DEFAULT NULL COMMENT '分类图标',
  `is_show` int(2) DEFAULT NULL COMMENT '是否在首页显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_guess_guess` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '竞猜项目id',
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `name` varchar(255) DEFAULT NULL COMMENT '名称',
  `classify_id` int(11) DEFAULT NULL COMMENT '分类id',
  `type_id` int(11) DEFAULT NULL COMMENT '类型id 1 : 让球',
  `start_time` varchar(255) DEFAULT NULL COMMENT '竞猜开始时间',
  `end_time` varchar(255) DEFAULT NULL COMMENT '竞猜结束时间',
  `home_team` varchar(255) DEFAULT NULL COMMENT '主队',
  `guest_team` varchar(255) DEFAULT NULL COMMENT '客队',
  `home_image` varchar(255) DEFAULT NULL COMMENT '主队图标',
  `guest_iamge` varchar(255) DEFAULT NULL COMMENT '客队图标',
  `match_time` varchar(255) DEFAULT NULL COMMENT '比赛时间',
  `win` varchar(255) DEFAULT '0' COMMENT '胜',
  `flat` varchar(255) DEFAULT '0' COMMENT '平',
  `transport` varchar(255) DEFAULT '0' COMMENT '负',
  `describe` text COMMENT '描述',
  `concede_num` int(11) DEFAULT '0' COMMENT '让球数',
  `concede` varchar(255) DEFAULT NULL COMMENT '让球方式： 1 主队让客队 2 客队让主队',
  `lottery` varchar(255) DEFAULT NULL COMMENT '开奖号码',
  `is_open` int(11) DEFAULT '0' COMMENT '是否开奖',
  `result` int(11) DEFAULT NULL COMMENT '结果 1 胜 2 平 3 负',
  `image` varchar(255) DEFAULT NULL COMMENT '封面图',
  `is_show` int(11) DEFAULT NULL COMMENT '是否热门',
  `nature` varchar(255) DEFAULT NULL COMMENT '比赛性质',
  `sold_out` int(2) DEFAULT '0' COMMENT '是否下架',
  `play_id` int(11) DEFAULT '1' COMMENT '玩法id',
  `contest` text COMMENT '猜冠军队伍情况',
  `upper` decimal(10,2) DEFAULT NULL COMMENT '上限',
  `lower` decimal(10,2) DEFAULT NULL COMMENT '下限',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_guess_lottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '开奖记录id',
  `periods_num` int(11) DEFAULT NULL COMMENT '期数',
  `lottery` varchar(255) DEFAULT NULL COMMENT '开奖号码',
  `create_time` varchar(255) DEFAULT NULL COMMENT '开奖时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_guess_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单id',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
  `guess_id` int(11) DEFAULT NULL COMMENT '项目id',
  `bet` int(11) DEFAULT NULL COMMENT '下注内容 1：胜 2：平 3：负',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '下注金额',
  `bonus` decimal(10,2) DEFAULT '0.00' COMMENT '奖金',
  `is_win` int(2) DEFAULT NULL COMMENT '是否中奖',
  `lottery` varchar(255) DEFAULT NULL COMMENT '开奖号码',
  `buy_time` varchar(255) DEFAULT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=403 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_guess_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `sets` text,
  `plugins` text,
  `sec` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_guess_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '任务id',
  `content` varchar(255) DEFAULT NULL COMMENT '任务内容',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_members` (
  `uid` int(10) NOT NULL COMMENT '用户id',
  `uniacid` int(10) unsigned DEFAULT NULL,
  `blacklist` int(255) DEFAULT NULL COMMENT '是否拉黑',
  `sign_num` int(11) DEFAULT NULL COMMENT '连续签到次数',
  `sign_time` varchar(255) DEFAULT NULL COMMENT '上次签到时间',
  `bet_time` varchar(255) DEFAULT NULL COMMENT '最后竞猜时间',
  `is_one` int(11) DEFAULT NULL COMMENT '是否初次使用',
  `share_num` int(11) DEFAULT '0' COMMENT '分享次数',
  `add_money` decimal(10,2) DEFAULT '0.00' COMMENT '当月获得积分',
  `share_time` varchar(255) DEFAULT NULL COMMENT '上次分享时间',
  `ok_task` varchar(255) DEFAULT NULL COMMENT '已完成的任务',
  `login_time` varchar(255) DEFAULT NULL COMMENT '上次登录时间',
  PRIMARY KEY (`uid`),
  KEY `uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
CREATE TABLE IF NOT EXISTS `ims_yike_signin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `sets` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='签到积分设置';
CREATE TABLE IF NOT EXISTS `ims_yike_vouchers_template_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `template_id` varchar(256) NOT NULL DEFAULT '',
  `first_data` varchar(256) NOT NULL DEFAULT '',
  `remark_data` varchar(256) NOT NULL DEFAULT '',
  `type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
");
if (pdo_tableexists('yike_complete')) {
    if (!pdo_fieldexists('yike_complete', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_complete') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '';");
    }
}
if (pdo_tableexists('yike_complete')) {
    if (!pdo_fieldexists('yike_complete', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_complete') . " ADD `uniacid` int(11)    COMMENT '公众号id';");
    }
}
if (pdo_tableexists('yike_complete')) {
    if (!pdo_fieldexists('yike_complete', 'user_id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_complete') . " ADD `user_id` int(11)    COMMENT '用户id';");
    }
}
if (pdo_tableexists('yike_complete')) {
    if (!pdo_fieldexists('yike_complete', 'fenxiang_1')) {
        pdo_query('ALTER TABLE ' . tablename('yike_complete') . " ADD `fenxiang_1` int(11) NOT NULL   COMMENT '第一次分享1.完成0.未完成';");
    }
}
if (pdo_tableexists('yike_complete')) {
    if (!pdo_fieldexists('yike_complete', 'fenxiang_2')) {
        pdo_query('ALTER TABLE ' . tablename('yike_complete') . " ADD `fenxiang_2` int(11) NOT NULL   COMMENT '第二次分享1.完成0.未完成';");
    }
}
if (pdo_tableexists('yike_complete')) {
    if (!pdo_fieldexists('yike_complete', 'fenxiang_3')) {
        pdo_query('ALTER TABLE ' . tablename('yike_complete') . " ADD `fenxiang_3` int(11) NOT NULL   COMMENT '第三次分享1.完成0.未完成';");
    }
}
if (pdo_tableexists('yike_complete')) {
    if (!pdo_fieldexists('yike_complete', 'jingcai')) {
        pdo_query('ALTER TABLE ' . tablename('yike_complete') . " ADD `jingcai` int(11) NOT NULL   COMMENT '第一次竞猜1.完成0.未完成';");
    }
}
if (pdo_tableexists('yike_complete')) {
    if (!pdo_fieldexists('yike_complete', 'tine')) {
        pdo_query('ALTER TABLE ' . tablename('yike_complete') . " ADD `tine` varchar(225) NOT NULL   COMMENT '任务完成时间';");
    }
}
if (pdo_tableexists('yike_guess_balance')) {
    if (!pdo_fieldexists('yike_guess_balance', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_balance') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '明细 ';");
    }
}
if (pdo_tableexists('yike_guess_balance')) {
    if (!pdo_fieldexists('yike_guess_balance', 'uid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_balance') . " ADD `uid` int(11)    COMMENT '用户id';");
    }
}
if (pdo_tableexists('yike_guess_balance')) {
    if (!pdo_fieldexists('yike_guess_balance', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_balance') . " ADD `uniacid` int(11)    COMMENT '公众号id';");
    }
}
if (pdo_tableexists('yike_guess_balance')) {
    if (!pdo_fieldexists('yike_guess_balance', 'money')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_balance') . " ADD `money` varchar(255)    COMMENT '收支金额';");
    }
}
if (pdo_tableexists('yike_guess_balance')) {
    if (!pdo_fieldexists('yike_guess_balance', 'type')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_balance') . " ADD `type` int(11)    COMMENT '1 收入 2 支出';");
    }
}
if (pdo_tableexists('yike_guess_balance')) {
    if (!pdo_fieldexists('yike_guess_balance', 'balance')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_balance') . " ADD `balance` varchar(255)    COMMENT '余额';");
    }
}
if (pdo_tableexists('yike_guess_balance')) {
    if (!pdo_fieldexists('yike_guess_balance', 'create_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_balance') . " ADD `create_time` varchar(255)    COMMENT '生成时间';");
    }
}
if (pdo_tableexists('yike_guess_balance')) {
    if (!pdo_fieldexists('yike_guess_balance', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_balance') . " ADD `name` varchar(255) NOT NULL   COMMENT '明细名';");
    }
}
if (pdo_tableexists('yike_guess_banner')) {
    if (!pdo_fieldexists('yike_guess_banner', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_banner') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '幻灯片id';");
    }
}
if (pdo_tableexists('yike_guess_banner')) {
    if (!pdo_fieldexists('yike_guess_banner', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_banner') . " ADD `name` varchar(255)    COMMENT '幻灯片标题';");
    }
}
if (pdo_tableexists('yike_guess_banner')) {
    if (!pdo_fieldexists('yike_guess_banner', 'image')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_banner') . " ADD `image` varchar(255)    COMMENT '幻灯片图';");
    }
}
if (pdo_tableexists('yike_guess_banner')) {
    if (!pdo_fieldexists('yike_guess_banner', 'href')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_banner') . " ADD `href` varchar(255)    COMMENT '幻灯片链接';");
    }
}
if (pdo_tableexists('yike_guess_banner')) {
    if (!pdo_fieldexists('yike_guess_banner', 'is_show')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_banner') . " ADD `is_show` int(1)   DEFAULT 0 COMMENT '是否显示';");
    }
}
if (pdo_tableexists('yike_guess_banner')) {
    if (!pdo_fieldexists('yike_guess_banner', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_banner') . " ADD `uniacid` int(11)    COMMENT '公众号id';");
    }
}
if (pdo_tableexists('yike_guess_banner')) {
    if (!pdo_fieldexists('yike_guess_banner', 'sort')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_banner') . " ADD `sort` varchar(255)    COMMENT '';");
    }
}
if (pdo_tableexists('yike_guess_classify')) {
    if (!pdo_fieldexists('yike_guess_classify', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_classify') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '分类id';");
    }
}
if (pdo_tableexists('yike_guess_classify')) {
    if (!pdo_fieldexists('yike_guess_classify', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_classify') . " ADD `uniacid` int(11)    COMMENT '公众号id';");
    }
}
if (pdo_tableexists('yike_guess_classify')) {
    if (!pdo_fieldexists('yike_guess_classify', 'parents_id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_classify') . " ADD `parents_id` int(11)   DEFAULT 0 COMMENT '上级分类id';");
    }
}
if (pdo_tableexists('yike_guess_classify')) {
    if (!pdo_fieldexists('yike_guess_classify', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_classify') . " ADD `name` varchar(255)    COMMENT '分类名称';");
    }
}
if (pdo_tableexists('yike_guess_classify')) {
    if (!pdo_fieldexists('yike_guess_classify', 'sort')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_classify') . " ADD `sort` int(11)    COMMENT '排序';");
    }
}
if (pdo_tableexists('yike_guess_classify')) {
    if (!pdo_fieldexists('yike_guess_classify', 'link')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_classify') . " ADD `link` varchar(255)    COMMENT '分类链接';");
    }
}
if (pdo_tableexists('yike_guess_classify')) {
    if (!pdo_fieldexists('yike_guess_classify', 'image')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_classify') . " ADD `image` varchar(255)    COMMENT '分类图标';");
    }
}
if (pdo_tableexists('yike_guess_classify')) {
    if (!pdo_fieldexists('yike_guess_classify', 'is_show')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_classify') . " ADD `is_show` int(2)    COMMENT '是否在首页显示';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '竞猜项目id';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `uniacid` int(11)    COMMENT '公众号id';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'sort')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `sort` int(11)   DEFAULT 0 COMMENT '排序';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'name')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `name` varchar(255)    COMMENT '名称';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'classify_id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `classify_id` int(11)    COMMENT '分类id';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'type_id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `type_id` int(11)    COMMENT '类型id 1 : 让球';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'start_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `start_time` varchar(255)    COMMENT '竞猜开始时间';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'end_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `end_time` varchar(255)    COMMENT '竞猜结束时间';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'home_team')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `home_team` varchar(255)    COMMENT '主队';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'guest_team')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `guest_team` varchar(255)    COMMENT '客队';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'home_image')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `home_image` varchar(255)    COMMENT '主队图标';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'guest_iamge')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `guest_iamge` varchar(255)    COMMENT '客队图标';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'match_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `match_time` varchar(255)    COMMENT '比赛时间';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'win')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `win` varchar(255)   DEFAULT 0 COMMENT '胜';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'flat')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `flat` varchar(255)   DEFAULT 0 COMMENT '平';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'transport')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `transport` varchar(255)   DEFAULT 0 COMMENT '负';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'describe')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `describe` text    COMMENT '描述';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'concede_num')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `concede_num` int(11)   DEFAULT 0 COMMENT '让球数';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'concede')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `concede` varchar(255)    COMMENT '让球方式： 1 主队让客队 2 客队让主队';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'lottery')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `lottery` varchar(255)    COMMENT '开奖号码';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'is_open')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `is_open` int(11)   DEFAULT 0 COMMENT '是否开奖';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'result')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `result` int(11)    COMMENT '结果 1 胜 2 平 3 负';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'image')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `image` varchar(255)    COMMENT '封面图';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'is_show')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `is_show` int(11)    COMMENT '是否热门';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'nature')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `nature` varchar(255)    COMMENT '比赛性质';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'sold_out')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `sold_out` int(2)   DEFAULT 0 COMMENT '是否下架';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'play_id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `play_id` int(11)   DEFAULT 1 COMMENT '玩法id';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'contest')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `contest` text    COMMENT '猜冠军队伍情况';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'upper')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `upper` decimal(10,2)    COMMENT '上限';");
    }
}
if (pdo_tableexists('yike_guess_guess')) {
    if (!pdo_fieldexists('yike_guess_guess', 'lower')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_guess') . " ADD `lower` decimal(10,2)    COMMENT '下限';");
    }
}
if (pdo_tableexists('yike_guess_lottery')) {
    if (!pdo_fieldexists('yike_guess_lottery', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_lottery') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '开奖记录id';");
    }
}
if (pdo_tableexists('yike_guess_lottery')) {
    if (!pdo_fieldexists('yike_guess_lottery', 'periods_num')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_lottery') . " ADD `periods_num` int(11)    COMMENT '期数';");
    }
}
if (pdo_tableexists('yike_guess_lottery')) {
    if (!pdo_fieldexists('yike_guess_lottery', 'lottery')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_lottery') . " ADD `lottery` varchar(255)    COMMENT '开奖号码';");
    }
}
if (pdo_tableexists('yike_guess_lottery')) {
    if (!pdo_fieldexists('yike_guess_lottery', 'create_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_lottery') . " ADD `create_time` varchar(255)    COMMENT '开奖时间';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '订单id';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'user_id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `user_id` int(11)    COMMENT '用户id';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `uniacid` int(11)    COMMENT '公众号id';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'guess_id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `guess_id` int(11)    COMMENT '项目id';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'bet')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `bet` int(11)    COMMENT '下注内容 1：胜 2：平 3：负';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'money')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `money` decimal(10,2)   DEFAULT 0.00 COMMENT '下注金额';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'bonus')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `bonus` decimal(10,2)   DEFAULT 0.00 COMMENT '奖金';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'is_win')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `is_win` int(2)    COMMENT '是否中奖';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'lottery')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `lottery` varchar(255)    COMMENT '开奖号码';");
    }
}
if (pdo_tableexists('yike_guess_order')) {
    if (!pdo_fieldexists('yike_guess_order', 'buy_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_order') . " ADD `buy_time` varchar(255)    COMMENT '购买时间';");
    }
}
if (pdo_tableexists('yike_guess_sysset')) {
    if (!pdo_fieldexists('yike_guess_sysset', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_sysset') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '';");
    }
}
if (pdo_tableexists('yike_guess_sysset')) {
    if (!pdo_fieldexists('yike_guess_sysset', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_sysset') . " ADD `uniacid` int(11)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('yike_guess_sysset')) {
    if (!pdo_fieldexists('yike_guess_sysset', 'sets')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_sysset') . " ADD `sets` text    COMMENT '';");
    }
}
if (pdo_tableexists('yike_guess_sysset')) {
    if (!pdo_fieldexists('yike_guess_sysset', 'plugins')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_sysset') . " ADD `plugins` text    COMMENT '';");
    }
}
if (pdo_tableexists('yike_guess_sysset')) {
    if (!pdo_fieldexists('yike_guess_sysset', 'sec')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_sysset') . " ADD `sec` text    COMMENT '';");
    }
}
if (pdo_tableexists('yike_guess_task')) {
    if (!pdo_fieldexists('yike_guess_task', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_task') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '任务id';");
    }
}
if (pdo_tableexists('yike_guess_task')) {
    if (!pdo_fieldexists('yike_guess_task', 'content')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_task') . " ADD `content` varchar(255)    COMMENT '任务内容';");
    }
}
if (pdo_tableexists('yike_guess_task')) {
    if (!pdo_fieldexists('yike_guess_task', 'sort')) {
        pdo_query('ALTER TABLE ' . tablename('yike_guess_task') . " ADD `sort` int(11)    COMMENT '排序';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'uid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `uid` int(10) NOT NULL   COMMENT '用户id';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `uniacid` int(10) unsigned    COMMENT '';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'blacklist')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `blacklist` int(255)    COMMENT '是否拉黑';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'sign_num')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `sign_num` int(11)    COMMENT '连续签到次数';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'sign_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `sign_time` varchar(255)    COMMENT '上次签到时间';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'bet_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `bet_time` varchar(255)    COMMENT '最后竞猜时间';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'is_one')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `is_one` int(11)    COMMENT '是否初次使用';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'share_num')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `share_num` int(11)   DEFAULT 0 COMMENT '分享次数';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'add_money')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `add_money` decimal(10,2)   DEFAULT 0.00 COMMENT '当月获得积分';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'share_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `share_time` varchar(255)    COMMENT '上次分享时间';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'ok_task')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `ok_task` varchar(255)    COMMENT '已完成的任务';");
    }
}
if (pdo_tableexists('yike_members')) {
    if (!pdo_fieldexists('yike_members', 'login_time')) {
        pdo_query('ALTER TABLE ' . tablename('yike_members') . " ADD `login_time` varchar(255)    COMMENT '上次登录时间';");
    }
}
if (pdo_tableexists('yike_signin')) {
    if (!pdo_fieldexists('yike_signin', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('yike_signin') . " ADD `id` int(11) NOT NULL auto_increment  COMMENT '';");
    }
}
if (pdo_tableexists('yike_signin')) {
    if (!pdo_fieldexists('yike_signin', 'uniacid')) {
        pdo_query('ALTER TABLE ' . tablename('yike_signin') . " ADD `uniacid` int(11)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('yike_signin')) {
    if (!pdo_fieldexists('yike_signin', 'sets')) {
        pdo_query('ALTER TABLE ' . tablename('yike_signin') . " ADD `sets` text    COMMENT '';");
    }
}