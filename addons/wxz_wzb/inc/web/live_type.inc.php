<?php

global $_W,$_GPC;
load()->func('tpl');
$rid = intval($_GPC['rid']);

include $this->template('live_type');