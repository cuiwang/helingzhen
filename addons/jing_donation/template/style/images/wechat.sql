-- phpMyAdmin SQL Dump
-- version 4.0.10.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost:/tmp/mysql-generic-5.5.40.sock
-- 生成日期: 2016-02-18 13:58:52
-- 服务器版本: 5.5.40-log
-- PHP 版本: 5.3.28p1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `wechat`
--

-- --------------------------------------------------------

--
-- 表的结构 `ims_jing_donation_adv`
--

DROP TABLE IF EXISTS `ims_jing_donation_adv`;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_enabled` (`enabled`),
  KEY `indx_displayorder` (`displayorder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_jing_donation_adv`
--

INSERT INTO `ims_jing_donation_adv` (`id`, `uniacid`, `advname`, `link`, `thumb`, `displayorder`, `enabled`) VALUES
(1, 109, '幻灯片一', 'http://www.baidu.com/', 'images/109/2016/01/XnWMWuIam3Cvsws0zvSM5M34N4Ln0n.jpg', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ims_jing_donation_dynamic`
--

DROP TABLE IF EXISTS `ims_jing_donation_dynamic`;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_dynamic` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) DEFAULT '0',
  `did` int(10) DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `content` text,
  `createtime` int(10) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_enabled` (`enabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_jing_donation_dynamic`
--

INSERT INTO `ims_jing_donation_dynamic` (`id`, `uniacid`, `did`, `title`, `link`, `thumb`, `description`, `content`, `createtime`, `enabled`) VALUES
(1, 109, 1, '项目动态', '', 'images/109/2016/02/a4fOooLY4Jl8WJwLzVFoEmJMFME0wF.jpg', '项目动态', '<p>项目动态</p><p>项目动态</p><p>项目动态</p><p>项目动态</p><p>项目动态</p>', 1455773626, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ims_jing_donation_invitation`
--

DROP TABLE IF EXISTS `ims_jing_donation_invitation`;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_invitation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `did` int(10) unsigned DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `createtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_jing_donation_invitation`
--

INSERT INTO `ims_jing_donation_invitation` (`id`, `uniacid`, `did`, `openid`, `content`, `status`, `createtime`) VALUES
(1, 109, 1, 'fromUser', '我为这个公益项目发起筹款，为一个善意而美好的世界，我们大家一起来。', NULL, 1455707862),
(2, 109, 1, 'fromUser', '我为这个公益项目发起筹款，为一个善意而美好的世界，我们大家一起来。', 0, 1455760019),
(3, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', '我为这个公益项目发起筹款，为一个善意而美好的世界，我们大家一起来。', 0, 1455760615),
(4, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', '我为这个公益项目发起筹款，为一个善意而美好的世界，我们大家一起来。', 0, 1455761297),
(5, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', '我为这个公益项目发起筹款，为一个善意而美好的世界，我们大家一起来。', 1, 1455762370),
(6, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', '我为这个公益项目发起筹款，为一个善意而美好的世界，我们大家一起来。', 1, 1455762456),
(7, 109, 1, 'fromUser', '分享标语范例一', 0, 1455762512),
(8, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', '我为这个公益项目发起筹款，为一个善意而美好的世界，我们大家一起来。', 0, 1455771975);

-- --------------------------------------------------------

--
-- 表的结构 `ims_jing_donation_order`
--

DROP TABLE IF EXISTS `ims_jing_donation_order`;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `did` int(10) unsigned DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `ordersn` int(10) unsigned DEFAULT NULL,
  `price` decimal(10,2) unsigned DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `paytype` varchar(10) DEFAULT NULL,
  `transid` varchar(255) DEFAULT NULL,
  `createtime` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_jing_donation_order`
--

INSERT INTO `ims_jing_donation_order` (`id`, `uniacid`, `did`, `openid`, `ordersn`, `price`, `status`, `paytype`, `transid`, `createtime`) VALUES
(1, 109, 1, 'fromUser', 2173822, 10.00, 0, NULL, NULL, 1455709376),
(2, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', 2174320, 0.01, 1, '2', '1007560883201602173344430522', 1455709422),
(3, 109, 1, 'fromUser', 2177590, 10.00, 0, NULL, NULL, 1455709834),
(4, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', 2176636, 10.00, 0, NULL, NULL, 1455709894),
(5, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', 2172562, 10.00, 0, NULL, NULL, 1455709901),
(6, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', 2184428, 0.01, 1, '2', '1007560883201602183357112948', 1455770506);

-- --------------------------------------------------------

--
-- 表的结构 `ims_jing_donation_reply`
--

DROP TABLE IF EXISTS `ims_jing_donation_reply`;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `donationid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_jing_donation_reply`
--

INSERT INTO `ims_jing_donation_reply` (`id`, `rid`, `donationid`) VALUES
(10, 381, 1),
(9, 381, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ims_jing_donation_user`
--

DROP TABLE IF EXISTS `ims_jing_donation_user`;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `unionid` varchar(50) DEFAULT NULL,
  `nickname` varchar(20) DEFAULT NULL,
  `sex` tinyint(1) unsigned DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_jing_donation_user`
--

INSERT INTO `ims_jing_donation_user` (`id`, `uniacid`, `openid`, `unionid`, `nickname`, `sex`, `avatar`, `country`, `province`, `city`) VALUES
(1, 109, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', 'o8FI1s_qHtKJfjTpJq4dlOiSFtyM', '刘静', 1, 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLDjLKRfODYgItsY8TiaDu7SCTicnHI0v0FXsGxzlArbFSzJwfe209WYsicRRC0zCnOAep2UIOOxfsiaOg/132', '中国', '陕西', '西安');

-- --------------------------------------------------------

--
-- 表的结构 `ims_jing_donation_yxz`
--

DROP TABLE IF EXISTS `ims_jing_donation_yxz`;
CREATE TABLE IF NOT EXISTS `ims_jing_donation_yxz` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned DEFAULT NULL,
  `did` int(10) unsigned DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `yxz` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ims_jing_donation_yxz`
--

INSERT INTO `ims_jing_donation_yxz` (`id`, `uniacid`, `did`, `openid`, `yxz`) VALUES
(1, 109, 1, 'o10JguNO0o9_Fhe3a-jrlEAzOzo8', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
