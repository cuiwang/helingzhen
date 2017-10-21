<?php

$sql =<<<EOF
DROP TABLE IF EXISTS `ims_hetu_halfoff_activation`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_agent`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_business`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_cardtype`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_category`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_circle`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_city`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_collection`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_confirm`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_coupon`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_couponrecord`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_getcard`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_module`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_news`;
DROP TABLE IF EXISTS `ims_hetu_halfoff_newslog`;
EOF;
pdo_run($sql);