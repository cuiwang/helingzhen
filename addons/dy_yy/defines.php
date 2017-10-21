<?php

//D
if (!defined('IN_IA')) {
    exit('Access Denied');
}
define('D_DEBUG', false);
!defined('D_PATH') && define('D_PATH', IA_ROOT . '/addons/dy_yy/');
!defined('D_CORE') && define('D_CORE', D_PATH . 'core/');
!defined('D_PLUGIN') && define('D_PLUGIN', D_PATH . 'plugin/');
!defined('D_INC') && define('D_INC', D_CORE . 'inc/');
!defined('D_URL') && define('D_URL', $_W['siteroot'] . 'addons/dy_yy/');
!defined('D_STATIC') && define('D_STATIC', D_URL . 'static/');
!defined('D_PREFIX') && define('D_PREFIX', 'D_');
