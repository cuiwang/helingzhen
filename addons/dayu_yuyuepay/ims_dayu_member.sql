/*
Navicat MySQL Data Transfer

Source Server         : 云数据库
Source Server Version : 50628
Source Host           : 587cb0ed62439.sh.cdb.myqcloud.com:8133
Source Database       : weixinwe7

Target Server Type    : MYSQL
Target Server Version : 50628
File Encoding         : 65001

Date: 2017-03-01 10:26:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_dayu_member
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_member`;
CREATE TABLE `ims_dayu_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0',
  `uid` int(10) NOT NULL DEFAULT '0',
  `openid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `realname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `groupid` int(10) NOT NULL DEFAULT '0',
  `gender` tinyint(1) NOT NULL DEFAULT '1',
  `province` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `dist` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `realaddress` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `qq` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `alipay` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `idcard` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `frontid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `backid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fdriving` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bdriving` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `modules` int(2) NOT NULL DEFAULT '0',
  `updatetime` int(10) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `weid` (`weid`),
  KEY `openid` (`openid`),
  KEY `idcard` (`idcard`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='会员信息表';

-- ----------------------------
-- Records of ims_dayu_member
-- ----------------------------

-- ----------------------------
-- Table structure for ims_dayu_yuyuepay
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_yuyuepay`;
CREATE TABLE `ims_dayu_yuyuepay` (
  `reid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `subtitle` varchar(20) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `information` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `icon` varchar(200) NOT NULL DEFAULT '',
  `inhome` tinyint(4) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `pretotal` int(10) unsigned NOT NULL DEFAULT '0',
  `pay` int(1) unsigned NOT NULL DEFAULT '1',
  `xmshow` int(10) unsigned NOT NULL DEFAULT '0',
  `xmname` varchar(50) NOT NULL DEFAULT '',
  `numname` varchar(50) NOT NULL DEFAULT '数量',
  `yuyuename` varchar(50) NOT NULL DEFAULT '',
  `noticeemail` varchar(50) NOT NULL DEFAULT '',
  `k_templateid` varchar(50) NOT NULL DEFAULT '',
  `kfid` varchar(50) NOT NULL DEFAULT '',
  `m_templateid` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `mname` varchar(10) NOT NULL DEFAULT '',
  `skins` varchar(20) NOT NULL DEFAULT 'weui',
  `code` tinyint(1) DEFAULT '0',
  `kfirst` varchar(100) NOT NULL COMMENT '客服模板页头',
  `kfoot` varchar(100) NOT NULL COMMENT '客服模板页尾',
  `mfirst` varchar(100) NOT NULL COMMENT '客户模板页头',
  `mfoot` varchar(100) NOT NULL COMMENT '客户模板页尾',
  `remove` varchar(100) NOT NULL,
  `share_url` varchar(100) NOT NULL DEFAULT '',
  `state1` varchar(20) NOT NULL DEFAULT '待受理',
  `state2` varchar(20) NOT NULL DEFAULT '受理中',
  `state3` varchar(20) NOT NULL DEFAULT '已完成',
  `state4` varchar(20) NOT NULL DEFAULT '拒绝受理',
  `state5` varchar(20) NOT NULL DEFAULT '已取消',
  `isthumb` tinyint(1) NOT NULL DEFAULT '0',
  `outlink` varchar(200) NOT NULL,
  `isdel` tinyint(1) NOT NULL DEFAULT '0',
  `follow` tinyint(1) DEFAULT '1',
  `is_num` tinyint(1) DEFAULT '0',
  `is_time` tinyint(1) DEFAULT '0',
  `is_addr` tinyint(1) DEFAULT '1',
  `is_list` tinyint(1) NOT NULL DEFAULT '1',
  `iscard` tinyint(1) NOT NULL DEFAULT '0',
  `timelist` tinyint(1) NOT NULL DEFAULT '0',
  `day` int(10) unsigned NOT NULL DEFAULT '8',
  `srvtime` text NOT NULL,
  `out1` varchar(100) NOT NULL,
  `out2` varchar(100) NOT NULL,
  `out3` varchar(100) NOT NULL,
  `out4` varchar(100) NOT NULL,
  `out5` varchar(100) NOT NULL,
  `out6` varchar(100) NOT NULL,
  `out7` varchar(100) NOT NULL,
  `score_total` int(11) NOT NULL DEFAULT '0' COMMENT '总分',
  `score_vr` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟分',
  `score_num` int(11) NOT NULL DEFAULT '0' COMMENT '人数',
  `daynum` int(11) NOT NULL DEFAULT '0',
  `smsid` int(11) NOT NULL DEFAULT '0',
  `smstype` int(1) NOT NULL DEFAULT '0',
  `displayorder` int(3) NOT NULL DEFAULT '0',
  `submit` varchar(20) NOT NULL DEFAULT '立 即 提 交',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `restrict` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`reid`),
  KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_dayu_yuyuepay
-- ----------------------------

-- ----------------------------
-- Table structure for ims_dayu_yuyuepay_data
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_yuyuepay_data`;
CREATE TABLE `ims_dayu_yuyuepay_data` (
  `redid` bigint(20) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `rerid` int(11) NOT NULL,
  `refid` int(11) NOT NULL,
  `data` varchar(800) NOT NULL,
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`redid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_dayu_yuyuepay_data
-- ----------------------------

-- ----------------------------
-- Table structure for ims_dayu_yuyuepay_fields
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_yuyuepay_fields`;
CREATE TABLE `ims_dayu_yuyuepay_fields` (
  `refid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `essential` tinyint(4) NOT NULL DEFAULT '0',
  `bind` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(300) NOT NULL DEFAULT '',
  `image` varchar(250) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL,
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`refid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_dayu_yuyuepay_fields
-- ----------------------------

-- ----------------------------
-- Table structure for ims_dayu_yuyuepay_group
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_yuyuepay_group`;
CREATE TABLE `ims_dayu_yuyuepay_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  `groupid` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_dayu_yuyuepay_group
-- ----------------------------

-- ----------------------------
-- Table structure for ims_dayu_yuyuepay_info
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_yuyuepay_info`;
CREATE TABLE `ims_dayu_yuyuepay_info` (
  `rerid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `member` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `address` varchar(1024) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `xmid` int(11) NOT NULL,
  `num` int(3) unsigned NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `transid` varchar(30) NOT NULL COMMENT '微信订单号',
  `paystatus` tinyint(4) NOT NULL COMMENT '付款状态',
  `paytype` tinyint(4) NOT NULL COMMENT '付款方式',
  `yuyuetime` int(10) NOT NULL DEFAULT '0' COMMENT '客服确认时间',
  `restime` varchar(50) NOT NULL,
  `thumb` text NOT NULL,
  `kfinfo` varchar(100) NOT NULL COMMENT '客服信息',
  `paydetail` varchar(100) NOT NULL,
  `remit` varchar(250) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  `kf` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`rerid`),
  KEY `reid` (`reid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_dayu_yuyuepay_info
-- ----------------------------

-- ----------------------------
-- Table structure for ims_dayu_yuyuepay_reply
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_yuyuepay_reply`;
CREATE TABLE `ims_dayu_yuyuepay_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_dayu_yuyuepay_reply
-- ----------------------------

-- ----------------------------
-- Table structure for ims_dayu_yuyuepay_slide
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_yuyuepay_slide`;
CREATE TABLE `ims_dayu_yuyuepay_slide` (
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_dayu_yuyuepay_slide
-- ----------------------------

-- ----------------------------
-- Table structure for ims_dayu_yuyuepay_staff
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_yuyuepay_staff`;
CREATE TABLE `ims_dayu_yuyuepay_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL DEFAULT '0',
  `reid` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_dayu_yuyuepay_staff
-- ----------------------------

-- ----------------------------
-- Table structure for ims_dayu_yuyuepay_xiangmu
-- ----------------------------
DROP TABLE IF EXISTS `ims_dayu_yuyuepay_xiangmu`;
CREATE TABLE `ims_dayu_yuyuepay_xiangmu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `daynum` int(4) NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prices` decimal(10,2) NOT NULL DEFAULT '0.00',
  `displayorder` tinyint(3) NOT NULL DEFAULT '0',
  `isshow` tinyint(1) NOT NULL DEFAULT '0',
  `isc` tinyint(1) DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `weid` (`weid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ims_dayu_yuyuepay_xiangmu
-- ----------------------------
