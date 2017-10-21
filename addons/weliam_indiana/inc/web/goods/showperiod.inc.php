<?php
	global $_W,$_GPC;
	$goodsid = $_GPC['gid'];
	$goods = m('goods')->getGoods($goodsid);
	$merchant = pdo_fetch("SELECT name FROM ".tablename('weliam_indiana_merchant')." WHERE uniacid = {$_W['uniacid']} and id={$goods['merchant_id']} "); 
	$periods = pdo_fetchall("SELECT * FROM ".tablename('weliam_indiana_period')." WHERE uniacid = '{$_W['uniacid']}' and goodsid = {$goodsid}  ORDER BY id asc" );
	include $this->template('showperiod');
?>