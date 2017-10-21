<?php
defined ('IN_IA') or exit('Access Denied,your ip is:' . $_SERVER['REMOTE_ADDR'] . ',We have recorded the source of attack.');
define('IS_DEBUG', false);
define('IS_PAY', true);
!defined('APP_PATH') && define('APP_PATH', IA_ROOT . '/addons/netbuffer_creditchangered/');
!defined('APP_CLASS_PATH') && define('APP_CLASS_PATH', APP_PATH . 'application/');
!defined('APP_STATIC_PATH') && define('APP_STATIC_PATH', APP_PATH . 'static/');
