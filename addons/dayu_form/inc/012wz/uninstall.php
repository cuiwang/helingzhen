<?php

$sql =<<<EOF
DROP TABLE IF EXISTS `ims_dayu_form`;
DROP TABLE IF EXISTS `ims_dayu_form_custom`;
DROP TABLE IF EXISTS `ims_dayu_form_data`;
DROP TABLE IF EXISTS `ims_dayu_form_fields`;
DROP TABLE IF EXISTS `ims_dayu_form_info`;
DROP TABLE IF EXISTS `ims_dayu_form_linkage`;
DROP TABLE IF EXISTS `ims_dayu_form_loc`;
DROP TABLE IF EXISTS `ims_dayu_form_reply`;
DROP TABLE IF EXISTS `ims_dayu_form_role`;
DROP TABLE IF EXISTS `ims_dayu_form_staff`;
EOF;
pdo_run($sql);