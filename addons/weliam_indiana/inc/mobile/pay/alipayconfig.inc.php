<?php
error_reporting(0);
define('IN_MOBILE', true);
if(!empty($_POST)) {
	$alipay = var_export($_POST, true).PHP_EOL;
	m('log')->WL_log('pay','支付情况',$alipay,2);
}
exit('fail');
?>