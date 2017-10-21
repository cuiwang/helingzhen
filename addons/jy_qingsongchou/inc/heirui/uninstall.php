<?php

$sql =<<<EOF
DROP TABLE IF EXISTS `ims_tim_welfare_adv`;
DROP TABLE IF EXISTS `ims_tim_welfare_class`;
DROP TABLE IF EXISTS `ims_tim_welfare_fund`;
DROP TABLE IF EXISTS `ims_tim_welfare_good`;
DROP TABLE IF EXISTS `ims_tim_welfare_mall`;
DROP TABLE IF EXISTS `ims_tim_welfare_member`;
DROP TABLE IF EXISTS `ims_tim_welfare_order`;
DROP TABLE IF EXISTS `ims_tim_welfare_project`;
DROP TABLE IF EXISTS `ims_tim_welfare_set`;
DROP TABLE IF EXISTS `ims_tim_welfare_slide`;
DROP TABLE IF EXISTS `ims_tim_welfare_uadd`;
EOF;
pdo_run($sql);