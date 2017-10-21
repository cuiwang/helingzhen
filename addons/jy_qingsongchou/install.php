<?php



if(!pdo_tableexists('jy_qsc_address')){
      $sql ="CREATE TABLE ".tablename('jy_qsc_address')." (
        `id` int(111) NOT NULL AUTO_INCREMENT,
        `weid` int(11) DEFAULT NULL,
        `mid` int(11) DEFAULT NULL,
        `province` varchar(255) DEFAULT NULL,
        `city` varchar(255) DEFAULT NULL,
        `area` varchar(255) DEFAULT NULL,
        `address` varchar(255) DEFAULT NULL,
        `name` varchar(32) DEFAULT NULL,
        `tel` varchar(32) DEFAULT NULL,
        `youbian` varchar(32) DEFAULT NULL,
        `is_def` int(1) NOT NULL DEFAULT '0',
        `upbdate` varchar(32) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
      pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_fabu')){
    $sql ="CREATE TABLE ".tablename('jy_qsc_fabu')." (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `pid` int(11) DEFAULT NULL COMMENT '项目ID',
      `openid` varchar(233) DEFAULT NULL,
      `tar_monet` int(11) DEFAULT NULL COMMENT '目标金额',
      `use` varchar(233) DEFAULT NULL COMMENT '用途',
      `name` varchar(255) DEFAULT NULL COMMENT '筹款标题',
      `detail` text COMMENT '说明',
      `thumb` text COMMENT '图片列表',
      `upbdate` varchar(32) DEFAULT NULL,
      `status` int(1) NOT NULL DEFAULT '0' COMMENT '0 编辑中\n1 进行中\n2 成功\n3 失败或过期',
      `rand_time` varchar(32) DEFAULT NULL,
      `rand_day` varchar(32) DEFAULT NULL,
      `project_texdesc` text,
      `cur_day` varchar(32) DEFAULT NULL,
      `cover_thumb` text,
      `has_monet` float NOT NULL DEFAULT '0',
      `is_sup` int(11) NOT NULL DEFAULT '0',
      `is_admin` int(1) NOT NULL DEFAULT '0' COMMENT '0 用户 \n1 后台',
      `mid` int(11) DEFAULT NULL COMMENT '模块用户ID',
      `uid` int(11) DEFAULT NULL COMMENT '平台用户id',
      `shouc` int(11) DEFAULT '0' COMMENT '收藏数',
      `reward` text COMMENT '回报',
      `is_secret` int(1) DEFAULT '0' COMMENT '是否隐私',
      `has_sh` int(1) DEFAULT '0' COMMENT '是否需要收货人',
      `yunfei` varchar(221) DEFAULT NULL COMMENT '运费',
      `deliveryTime` varchar(221) DEFAULT NULL COMMENT '收货时间',
      `has_money` float(255,2) DEFAULT '0.00',
      `is_share` int(11) DEFAULT '0' COMMENT '分享次数',
      `fhsj` varchar(221) DEFAULT NULL COMMENT '收货时间',
      `views` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}


if(!pdo_tableexists('jy_qsc_jubao')){
    $sql ="CREATE TABLE  ".tablename('jy_qsc_jubao')."  (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `fid` int(11) DEFAULT NULL,
      `mid` int(111) DEFAULT NULL,
      `report_reason` text,
      `thumb` text,
      `upbdate` varchar(32) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_member')){
    $sql ="CREATE TABLE  ".tablename('jy_qsc_member')."  (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `openid` varchar(233) DEFAULT NULL,
      `nickname` varchar(233) DEFAULT NULL,
      `headimgurl` varchar(233) DEFAULT NULL,
      `is_roob` int(1) DEFAULT '0',
      `is_shouc` text COMMENT '收藏发布项目',
      `tel` varchar(32) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_message')){
    $sql ="CREATE TABLE ".tablename('jy_qsc_message')." (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `payid` int(11) DEFAULT NULL,
      `mid` int(11) DEFAULT NULL,
      `fid` int(11) DEFAULT NULL,
      `content` varchar(255) DEFAULT NULL,
      `upbdate` varchar(32) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_msgcode')){
    $sql ="CREATE TABLE ".tablename('jy_qsc_msgcode')." (
      `id` int(111) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `mid` int(11) DEFAULT NULL,
      `token` varchar(233) DEFAULT NULL,
      `upbdate` varchar(32) DEFAULT NULL,
      `code` varchar(32) DEFAULT NULL,
      `status` int(1) DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_oques')){
    $sql ="CREATE TABLE ".tablename('jy_qsc_oques')." (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `level` int(1) NOT NULL DEFAULT '1',
      `title` varchar(32) DEFAULT NULL,
      `rank` int(11) NOT NULL DEFAULT '50',
      `type` varchar(32) DEFAULT NULL,
      `pre_id` int(11) DEFAULT NULL,
      `content` text,
      `upbdate` varchar(32) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_paylog')){
      $sql ="CREATE TABLE ".tablename('jy_qsc_paylog')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `weid` int(11) DEFAULT NULL,
        `uid` int(11) DEFAULT NULL,
        `fid` int(11) DEFAULT NULL,
        `avatar` varchar(255) DEFAULT NULL,
        `upbdate` varchar(32) DEFAULT NULL,
        `status` int(1) NOT NULL DEFAULT '0',
        `tid` varchar(255) DEFAULT NULL,
        `fee` float(255,2) DEFAULT NULL,
        `msg` varchar(255) NOT NULL DEFAULT '支持',
        `mid` int(11) DEFAULT NULL,
        `address_id` int(11) DEFAULT NULL,
        `reid` varchar(255) DEFAULT NULL,
        `type` int(1) NOT NULL DEFAULT '0' COMMENT '0 =>支持\n1 =>提现\n2 =>充值\n3 =>消费',
        PRIMARY KEY (`id`)
      ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
      pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_project')){
    $sql ="CREATE TABLE ".tablename('jy_qsc_project')." (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `project_name` varchar(32) DEFAULT NULL,
      `project_logo` varchar(233) DEFAULT NULL,
      `project_plus1` int(1) NOT NULL DEFAULT '0',
      `project_plus2` int(1) NOT NULL DEFAULT '0',
      `project_plus3` int(1) NOT NULL DEFAULT '0',
      `project_plus4` int(1) NOT NULL DEFAULT '0',
      `project_desc` text,
      `project_shuoming` text,
      `upbdate` varchar(32) DEFAULT NULL,
      `project_min` int(11) DEFAULT NULL,
      `project_max` int(11) DEFAULT NULL,
      `project_moren` int(11) DEFAULT NULL,
      `project_texdesc` varchar(255) DEFAULT NULL,
      `project_mstips` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_ques')){
    $sql ="CREATE TABLE ".tablename('jy_qsc_ques')." (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `pid` int(11) DEFAULT NULL,
      `title` varchar(233) DEFAULT NULL,
      `content` text,
      `upbdate` varchar(32) DEFAULT NULL,
      `rank` int(11) NOT NULL DEFAULT '50',
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_setting')){
      $sql ="CREATE TABLE ".tablename('jy_qsc_setting')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `weid` int(11) DEFAULT NULL,
        `sitename` varchar(255) DEFAULT NULL,
        `sitebanner` varchar(255) DEFAULT NULL,
        `share_title` varchar(255) DEFAULT NULL,
        `share_desc` varchar(255) DEFAULT NULL,
        `share_img` varchar(255) DEFAULT NULL,
        `upbdate` varchar(255) DEFAULT NULL,
        `head_title1` varchar(32) DEFAULT NULL,
        `project_fqtk` text,
        `file_type` int(1) DEFAULT '0' COMMENT '文件存储方式',
        `pay_type` int(1) DEFAULT '0' COMMENT '支付方式',
        `qniu_access` varchar(255) DEFAULT '0' COMMENT '七牛公钥',
        `qniu_secret` varchar(255) DEFAULT '0' COMMENT '七牛密钥',
        `qniu_bucket` varchar(255) DEFAULT '0' COMMENT '七牛bucket',
        `qniu_url` varchar(255) DEFAULT '0' COMMENT '七牛域名',
        `oss_access` varchar(255) DEFAULT '0' COMMENT 'oss公钥',
        `oss_secret` varchar(255) DEFAULT '0' COMMENT 'oss密钥',
        `oss_bucket` varchar(255) DEFAULT '0' COMMENT 'ossbucket',
        `pay_appid` varchar(255) DEFAULT '0' COMMENT '公众号APPID',
        `pay_appsecret` varchar(255) DEFAULT '0' COMMENT '商户密钥',
        `pay_miyao` varchar(255) DEFAULT '0' COMMENT '应用密钥',
        `oss_url` varchar(255) DEFAULT '0' COMMENT 'oss_url',
        `oss_endpoint` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `weibo_content` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `weibo_pic` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `weibo_url` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `qqzon_content` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `qqzon_pic` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `qqzon_url` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `qqweibo_content` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `qqweibo_pic` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `qqweibo_url` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `qq_content` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `qq_pic` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `qq_url` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `pay_number` varchar(255) DEFAULT '0' COMMENT 'oss_endpoint',
        `about` text COMMENT '关于轻松筹',
        `tel` varchar(32) DEFAULT NULL COMMENT '电话',
        `dayu_appkey` varchar(32) DEFAULT NULL,
        `dayu_secretkey` varchar(233) DEFAULT NULL,
        `dayu_sign` varchar(233) DEFAULT NULL,
        `dayu_temp` varchar(233) DEFAULT NULL,
        `logo` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
      pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_shiming')){
    $sql ="CREATE TABLE ".tablename('jy_qsc_shiming')." (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `mid` int(11) DEFAULT NULL,
      `real_name` varchar(32) DEFAULT NULL,
      `cert_no` varchar(32) DEFAULT NULL,
      `upbdate` varchar(32) DEFAULT NULL,
      `status` int(1) NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_sysmsg')){
    $sql ="CREATE TABLE ".tablename('jy_qsc_sysmsg')." (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `weid` int(11) DEFAULT NULL,
      `ms_title` varchar(255) DEFAULT NULL,
      `ms_content` text,
      `upbdate` varchar(32) DEFAULT NULL,
      `status` int(1) NOT NULL DEFAULT '0',
      `thumb` varchar(233) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
    pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_update')){
      $sql ="CREATE TABLE ".tablename('jy_qsc_update')." (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `weid` int(11) DEFAULT NULL,
        `mid` int(11) DEFAULT NULL,
        `fid` int(11) DEFAULT NULL,
        `content` varchar(32) DEFAULT NULL,
        `thumb` text,
        `upbdate` varchar(32) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=myisam  DEFAULT CHARSET=utf8;";
      pdo_query($sql);
}

if(!pdo_tableexists('jy_qsc_withdraw')){
      $sql ="CREATE TABLE ".tablename('jy_qsc_withdraw')." (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`weid` int(11) NULL,
				`mid` int(11) NULL,
				`uid` int(11) NULL,
				`money` float(11,2)  NOT NULL DEFAULT 0,
				`upbdate` varchar(255) NULL,
				`status` int(1) NOT NULL COMMENT '0 审核中 1 已审核2 审核不通过',
			  'type' int(1)  NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			)ENGINE=myisam  DEFAULT CHARSET=utf8;";
      pdo_query($sql);
}
 ?>
