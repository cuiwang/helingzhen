<?php
global $_W,$_GPC;
	$uniacid=$_W['uniacid'];
	$condition = '';
	$goodses = pdo_fetchall("SELECT * FROM ".tablename('weliam_indiana_goodslist')." WHERE uniacid = '{$uniacid}' and status =1 and sid = '{$_GPC['sid']}' $condition ORDER BY id DESC" );

	include $this->template('showperiod');
?>