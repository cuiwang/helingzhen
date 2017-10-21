<?php
	global $_W,$_GPC;
	$openid = m('user') -> getOpenid();
	include $this->template('moneyre');