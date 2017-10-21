<?php
$sql = "
CREATE TABLE IF NOT EXISTS `ims_sds_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shop` varchar(32) NOT NULL,
  `address` varchar(255) NOT NULL,
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `ims_sds_info` (
  `title` varchar(32) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `adimg` varchar(255) DEFAULT NULL,
  `shareicon` varchar(255) DEFAULT NULL,
  `sharetitle` varchar(100) DEFAULT NULL,
  `sharecontent` varchar(255) DEFAULT NULL,
  `copyright` varchar(64) DEFAULT NULL,
  `uniacid` varchar(32) NOT NULL,
  `introduce` text,
  PRIMARY KEY (`uniacid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `ims_sds_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) NOT NULL,
  `name` varchar(32) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `yingy` int(11) NOT NULL DEFAULT '0',
  `water` int(11) NOT NULL DEFAULT '0',
  `kinds` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `address` varchar(32) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `helper` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `ims_sds_seeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruit` varchar(32) NOT NULL,
  `yy` int(11) NOT NULL,
  `water` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `ims_sds_seeds` (`id`, `fruit`, `yy`, `water`) VALUES
(1, '红色果实', '68', '108'),
(2, '绿色果实', '78', '118'),
(3, '桔色果实', '88', '128'),
(4, '柠檬色果实', '98', '138');
";
pdo_run($sql);
