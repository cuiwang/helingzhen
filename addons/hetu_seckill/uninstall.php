<?php

$sql =<<<EOF
DROP TABLE IF EXISTS `hetu_seckill_goods`;
DROP TABLE IF EXISTS `hetu_seckill_order`;
DROP TABLE IF EXISTS `hetu_seckill_peis`;
DROP TABLE IF EXISTS `hetu_seckill_remind`;
DROP TABLE IF EXISTS `hetu_seckill_stage`;
DROP TABLE IF EXISTS `hetu_seckill_user`;
EOF;
pdo_run($sql);