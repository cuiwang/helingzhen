<?php
	global $_W,$_GPC;
	$money = $_GPC['money'];
	if(empty($_W['uniacid'])){
		require'../../../../../framework/bootstrap.inc.php';
		global $_W,$_GPC;
		$uniacid = json_decode(base64_decode($_GPC['_cookie_uniacid']), true);

		header("Location: ".'http://'.$_SERVER['HTTP_HOST']."/app/index.php?i=".$uniacid."&c=entry&do=endbuy&m=weliam_indiana"); 
	}else{
		include $this->template("pay/endbuy");
	}
	?>