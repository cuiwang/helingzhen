<?php
/**
 * [WeEngine System] Copyright (c) 2014 weizancms.com
 * WeEngine is NOT a free software, it under the license terms, visited http://www.weizancms.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
isetcookie('__session', '', -10000);

$forward = $_GPC['forward'];
if(empty($forward)) {
	$forward = './?refersh';
}
header('Location:' . url('account/welcome'));
