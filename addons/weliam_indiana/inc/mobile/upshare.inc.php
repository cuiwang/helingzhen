<?php
	global $_W,$_GPC;
	$openid = m('user') -> getOpenid();
	
	$period_number = $_GPC['period_number'];
	
	$result = pdo_fetch("select * from".tablename('weliam_indiana_period')."where period_number='{$period_number}' and uniacid = '{$_W['uniacid']}'");
	
	if(empty($result)){
		message("参数错误！！","","error");
	}else{
		include $this->template("upshare");
	}


 ?>