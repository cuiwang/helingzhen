<?php

$sql =<<<EOF
DROP TABLE IF EXISTS `ims_han_hongbao_checklist`;
DROP TABLE IF EXISTS `ims_han_hongbao_history`;
DROP TABLE IF EXISTS `ims_han_hongbao_klfans`;
DROP TABLE IF EXISTS `ims_han_hongbao_klrecord`;
DROP TABLE IF EXISTS `ims_han_hongbao_klset`;
DROP TABLE IF EXISTS `ims_han_hongbao_kouling`;
DROP TABLE IF EXISTS `ims_han_hongbao_putong`;
DROP TABLE IF EXISTS `ims_han_hongbao_recordlist`;
DROP TABLE IF EXISTS `ims_han_hongbao_sdhb`;
DROP TABLE IF EXISTS `ims_han_hongbao_sdhb_history`;
DROP TABLE IF EXISTS `ims_han_hongbao_time`;
DROP TABLE IF EXISTS `ims_han_hongbao_yedh`;
EOF;
pdo_run($sql);