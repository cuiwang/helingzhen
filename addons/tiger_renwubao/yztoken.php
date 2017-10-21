<?php
define('IN_SYS', true);
require '../../framework/bootstrap.inc.php';
require '../../web/common/bootstrap.sys.inc.php';
global $_W,$_GPC;
//http://wx.youqi18.com/web/index.php?c=site&code=d9085cbce8073b33b5236a3fdc2c9e1c&state=renwubao
$siteroot = substr($_W['siteroot'], 0, stripos($_W['siteroot'], '/addons'));
$url = $siteroot.'/app/index.php';
$data = array('i'=>$_GPC['i'],'c'=>'entry','do'=>'token','m'=>'tiger_renwubao','code'=>$_GPC['code'],'state'=>$_GPC['state']);
$query = http_build_query($data);
$url .= '?'.$query;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
$output = curl_exec($ch);
curl_close($ch);
print_r($output);
