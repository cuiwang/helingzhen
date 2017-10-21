<?php
	global $_W,$_GPC;
	$openid = m('user') -> getOpenid();
	$reply = m('member') -> getInfoByOpenid($openid);
    include $this->template('profile');
?>