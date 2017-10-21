<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');
isetcookie('__session', '', -10000);

$forward = $_GPC['forward'];
if (empty($forward)) {
	$forward = './?refersh';
}
header('Location:' . url('user/login'));