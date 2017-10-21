<?php
/**
 * [WECHAT 2017]
 
 */

defined('IN_IA') or exit('Access Denied');
if ($action == 'mymodule' || $action == 'mrecord' || $action == 'morder' ) {
	define('FRAME', 'account');
}else{
	define('FRAME', 'system');
}