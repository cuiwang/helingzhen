<?php

$sql =<<<EOF
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_area`;
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_category`;
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_city`;
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_feedback`;
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_news`;
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_product`;
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_setting`;
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_slide`;
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_stores`;
DROP TABLE IF EXISTS `ims_weisrc_businesscenter_template`;
EOF;
pdo_run($sql);