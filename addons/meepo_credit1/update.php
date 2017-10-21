<?php
$sql = "      
CREATE TABLE IF NOT EXISTS `ims_meepo_credit1_paylog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `tid` varchar(64) DEFAULT NULL,
  `fee` float(10,2) DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `setting` varchar(320) DEFAULT NULL,
  `openid` varchar(64) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";
pdo_query($sql);
