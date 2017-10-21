<?php
global $_W, $_GPC;
$member = $this->_checkMember($_W);
include $this->template('profile');
?>