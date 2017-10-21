<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
!defined('WELIAM_INDIANA') && define('WELIAM_INDIANA', IA_ROOT . '/addons/weliam_indiana/');
!defined('WELIAM_INDIANA_CORE') && define('WELIAM_INDIANA_CORE', WELIAM_INDIANA . 'core/');
!defined('WELIAM_INDIANA_INC') && define('WELIAM_INDIANA_INC', WELIAM_INDIANA_CORE . 'inc/');
!defined('WELIAM_INDIANA_URL') && define('WELIAM_INDIANA_URL', $_W['siteroot'] . 'addons/weliam_indiana/');
!defined('WELIAM_INDIANA_STATIC') && define('WELIAM_INDIANA_STATIC', WELIAM_INDIANA_URL . 'static/');
!defined('WELIAM_INDIANA_VERSION') && define('WELIAM_INDIANA_VERSION','online');
