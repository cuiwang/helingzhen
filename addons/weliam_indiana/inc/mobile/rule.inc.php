<?php
global $_W, $_GPC;
$openid = m('user') -> getOpenid();
$share_data = $this->module['config'];
include $this->template('rule');