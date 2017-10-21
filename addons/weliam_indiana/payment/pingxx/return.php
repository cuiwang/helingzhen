<?php
	require'../../../../framework/bootstrap.inc.php';
	global $_W,$_GPC;
	$uniacid = json_decode(base64_decode($_GPC['_cookie_uniacid']), true);
	$root = explode('addons/', $_W['siteroot']);
	header("Location: ".$root[0]."app/index.php?i=".$uniacid."&c=entry&do=endbuy&m=weliam_indiana"); 
	exit;
?>