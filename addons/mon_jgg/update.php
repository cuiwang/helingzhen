<?php

pdo_query("CREATE TABLE IF NOT EXISTS `ims_mon_jgg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `rule` varchar(1000) DEFAULT NULL,
  `join_intro` varchar(1000) DEFAULT NULL,
  `day_play_count` int(3) DEFAULT '0',
  `follow_btn` varchar(50) DEFAULT '点击参加抽奖活动',
  `follow_welbtn` varchar(50) DEFAULT '欢迎参加微信九宫格',
  `follow_url` varchar(200) DEFAULT NULL,
  `copyright` varchar(100) NOT NULL,
  `prize_level_0` varchar(100) DEFAULT '没有中奖',
  `prize_name_0` varchar(100) NOT NULL,
  `prize_img_0` varchar(200) NOT NULL,
  `prize_p_0` int(3) NOT NULL,
  `prize_level_1` varchar(100) DEFAULT '一等奖',
  `prize_name_1` varchar(100) NOT NULL,
  `prize_img_1` varchar(200) NOT NULL,
  `prize_p_1` int(3) NOT NULL DEFAULT '0',
  `prize_num_1` int(10) NOT NULL,
  `prize_level_2` varchar(100) DEFAULT '二等奖',
  `prize_name_2` varchar(100) NOT NULL,
  `prize_img_2` varchar(200) NOT NULL,
  `prize_p_2` int(3) NOT NULL,
  `prize_num_2` int(10) NOT NULL DEFAULT '0',
  `prize_level_3` varchar(100) DEFAULT '三等奖',
  `prize_name_3` varchar(100) NOT NULL,
  `prize_img_3` varchar(200) NOT NULL,
  `prize_p_3` int(3) NOT NULL,
  `prize_num_3` int(10) NOT NULL DEFAULT '0',
  `prize_level_4` varchar(100) DEFAULT '四等奖',
  `prize_name_4` varchar(100) NOT NULL,
  `prize_img_4` varchar(200) NOT NULL,
  `prize_p_4` int(3) NOT NULL,
  `prize_num_4` int(10) NOT NULL DEFAULT '0',
  `prize_level_5` varchar(100) DEFAULT '五等奖',
  `prize_name_5` varchar(100) NOT NULL,
  `prize_img_5` varchar(200) NOT NULL,
  `prize_p_5` int(3) NOT NULL,
  `prize_num_5` int(10) NOT NULL DEFAULT '0',
  `prize_level_6` varchar(100) DEFAULT '六等奖',
  `prize_name_6` varchar(100) NOT NULL,
  `prize_img_6` varchar(200) NOT NULL,
  `prize_p_6` int(3) NOT NULL,
  `prize_num_6` int(10) NOT NULL DEFAULT '0',
  `prize_level_7` varchar(100) DEFAULT '七等奖',
  `prize_name_7` varchar(100) NOT NULL,
  `prize_img_7` varchar(200) NOT NULL,
  `prize_p_7` int(3) NOT NULL,
  `prize_num_7` int(10) NOT NULL DEFAULT '0',
  `award_count` int(11) DEFAULT '0',
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `day_award_count` int(10) DEFAULT '0',
  `bg` varchar(1000) DEFAULT NULL,
  `bgcolor` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_jgg_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jid` int(10) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_jgg_user_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jid` int(10) NOT NULL,
  `uid` varchar(200) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `award_name` varchar(200) NOT NULL,
  `award_level` varchar(200) NOT NULL,
  `level` int(3) DEFAULT '0',
  `status` int(11) DEFAULT '0',
  `remark` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_mon_jgg_user_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `jid` int(10) NOT NULL,
  `uid` varchar(200) NOT NULL,
  `openid` varchar(200) NOT NULL,
  `award_name` varchar(200) NOT NULL,
  `award_level` varchar(200) NOT NULL,
  `level` int(3) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
");
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'rid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `rid` int(10) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'weid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `weid` int(11) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'title')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `title` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'starttime')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `starttime` int(10)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'endtime')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `endtime` int(10)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'rule')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `rule` varchar(1000)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'join_intro')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `join_intro` varchar(1000)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'day_play_count')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `day_play_count` int(3)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'follow_btn')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `follow_btn` varchar(50)   DEFAULT 点击参加抽奖活动 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'follow_welbtn')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `follow_welbtn` varchar(50)   DEFAULT 欢迎参加微信九宫格 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'follow_url')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `follow_url` varchar(200)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'copyright')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `copyright` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_level_0')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_level_0` varchar(100)   DEFAULT 没有中奖 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_name_0')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_name_0` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_img_0')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_img_0` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_p_0')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_p_0` int(3) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_level_1')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_level_1` varchar(100)   DEFAULT 一等奖 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_name_1')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_name_1` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_img_1')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_img_1` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_p_1')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_p_1` int(3) NOT NULL  DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_num_1')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_num_1` int(10) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_level_2')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_level_2` varchar(100)   DEFAULT 二等奖 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_name_2')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_name_2` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_img_2')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_img_2` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_p_2')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_p_2` int(3) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_num_2')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_num_2` int(10) NOT NULL  DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_level_3')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_level_3` varchar(100)   DEFAULT 三等奖 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_name_3')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_name_3` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_img_3')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_img_3` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_p_3')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_p_3` int(3) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_num_3')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_num_3` int(10) NOT NULL  DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_level_4')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_level_4` varchar(100)   DEFAULT 四等奖 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_name_4')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_name_4` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_img_4')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_img_4` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_p_4')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_p_4` int(3) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_num_4')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_num_4` int(10) NOT NULL  DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_level_5')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_level_5` varchar(100)   DEFAULT 五等奖 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_name_5')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_name_5` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_img_5')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_img_5` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_p_5')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_p_5` int(3) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_num_5')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_num_5` int(10) NOT NULL  DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_level_6')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_level_6` varchar(100)   DEFAULT 六等奖 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_name_6')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_name_6` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_img_6')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_img_6` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_p_6')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_p_6` int(3) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_num_6')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_num_6` int(10) NOT NULL  DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_level_7')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_level_7` varchar(100)   DEFAULT 七等奖 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_name_7')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_name_7` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_img_7')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_img_7` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_p_7')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_p_7` int(3) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'prize_num_7')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `prize_num_7` int(10) NOT NULL  DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'award_count')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `award_count` int(11)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'new_title')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `new_title` varchar(200)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'new_icon')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `new_icon` varchar(200)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'new_content')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `new_content` varchar(200)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'share_title')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `share_title` varchar(200)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'share_icon')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `share_icon` varchar(200)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'share_content')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `share_content` varchar(200)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `createtime` int(10)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'day_award_count')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `day_award_count` int(10)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'bg')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `bg` varchar(1000)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg')) {
    if (!pdo_fieldexists('mon_jgg', 'bgcolor')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg') . " ADD `bgcolor` varchar(40)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user')) {
    if (!pdo_fieldexists('mon_jgg_user', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user') . " ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user')) {
    if (!pdo_fieldexists('mon_jgg_user', 'jid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user') . " ADD `jid` int(10) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user')) {
    if (!pdo_fieldexists('mon_jgg_user', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user') . " ADD `openid` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user')) {
    if (!pdo_fieldexists('mon_jgg_user', 'nickname')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user') . " ADD `nickname` varchar(100) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user')) {
    if (!pdo_fieldexists('mon_jgg_user', 'headimgurl')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user') . " ADD `headimgurl` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user')) {
    if (!pdo_fieldexists('mon_jgg_user', 'tel')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user') . " ADD `tel` varchar(20) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user')) {
    if (!pdo_fieldexists('mon_jgg_user', 'uname')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user') . " ADD `uname` varchar(20) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user')) {
    if (!pdo_fieldexists('mon_jgg_user', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user') . " ADD `createtime` int(10)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'jid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `jid` int(10) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'uid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `uid` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `openid` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'award_name')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `award_name` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'award_level')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `award_level` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'level')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `level` int(3)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'status')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `status` int(11)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'remark')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `remark` varchar(200)    COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_award')) {
    if (!pdo_fieldexists('mon_jgg_user_award', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_award') . " ADD `createtime` int(10)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_record')) {
    if (!pdo_fieldexists('mon_jgg_user_record', 'id')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_record') . " ADD `id` int(10) unsigned NOT NULL auto_increment  COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_record')) {
    if (!pdo_fieldexists('mon_jgg_user_record', 'jid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_record') . " ADD `jid` int(10) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_record')) {
    if (!pdo_fieldexists('mon_jgg_user_record', 'uid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_record') . " ADD `uid` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_record')) {
    if (!pdo_fieldexists('mon_jgg_user_record', 'openid')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_record') . " ADD `openid` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_record')) {
    if (!pdo_fieldexists('mon_jgg_user_record', 'award_name')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_record') . " ADD `award_name` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_record')) {
    if (!pdo_fieldexists('mon_jgg_user_record', 'award_level')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_record') . " ADD `award_level` varchar(200) NOT NULL   COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_record')) {
    if (!pdo_fieldexists('mon_jgg_user_record', 'level')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_record') . " ADD `level` int(3)   DEFAULT 0 COMMENT '';");
    }
}
if (pdo_tableexists('mon_jgg_user_record')) {
    if (!pdo_fieldexists('mon_jgg_user_record', 'createtime')) {
        pdo_query('ALTER TABLE ' . tablename('mon_jgg_user_record') . " ADD `createtime` int(10)   DEFAULT 0 COMMENT '';");
    }
}