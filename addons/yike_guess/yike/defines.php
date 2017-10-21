<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}
define('YIKE_DEBUG', false);
define('YIKE_DEBUG_ID', '2');
!defined('YIKE_TEMPLATE') && define('YIKE_TEMPLATE', 'yike');
!defined('YIKE_PATH') && define('YIKE_PATH', IA_ROOT . '/addons/yike_guess/');
!defined('YIKE_MODULE') && define('YIKE_MODULE', YIKE_PATH . 'yike/');
!defined('YIKE_CORE') && define('YIKE_CORE', YIKE_PATH . 'core/');
!defined('YIKE_PLUGIN') && define('YIKE_PLUGIN', YIKE_PATH . 'plugin/');
!defined('YIKE_INC') && define('YIKE_INC', YIKE_CORE . 'inc/');
!defined('YIKE_URL') && define('YIKE_URL', $_W['siteroot'] . 'addons/yike_guess/');
!defined('YIKE_STATIC') && define('YIKE_STATIC', YIKE_URL . 'static/');
!defined('YIKE_PREFIX') && define('YIKE_PREFIX', 'ewei_shop_');
