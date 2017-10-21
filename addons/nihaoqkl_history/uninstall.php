<?php

$sql =<<<EOF
DROP TABLE IF EXISTS `ims_addons_history`;
DROP TABLE IF EXISTS `ims_addons_history_cate`;
DROP TABLE IF EXISTS `ims_addons_history_mode`;
EOF;
pdo_run($sql);