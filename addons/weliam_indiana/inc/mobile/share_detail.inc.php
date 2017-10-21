<?php 
	global $_W,$_GPC;
	$id = $_GPC['id'];
	$openid = m('user') -> getOpenid();
	$result = pdo_fetch("select * from".tablename('weliam_indiana_showprize')."where uniacid = '{$_W['uniacid']}' and id='{$id}'");
	$thumbs = unserialize($result['thumbs']);
	$goodstitle = pdo_fetch("select title from".tablename('weliam_indiana_goodslist')."where uniacid = '{$_W['uniacid']}' and id='{$result['goodsid']}'");
	$list = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$result['period_number']}' ");
	include $this->template('share_detail');
	?>