<?php
	require'../../../../framework/bootstrap.inc.php';
	global $_W,$_GPC;

	$root = explode('addons/', $_W['siteroot']);
	header("Location: ".$root[0]."app/index.php?i=".$_GPC['uniacid']."&c=entry&do=endbuy&m=weliam_indiana"); 
	exit;
?>