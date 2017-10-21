<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
!defined('TYZM_MODEL') && define('TYZM_MODEL', IA_ROOT . '/addons/tyzm_diamondvote/');
!defined('TYZM_MODEL_CORE') && define('TYZM_MODEL_CORE', TYZM_MODEL . 'core/');
!defined('TYZM_MODEL_FUNC') && define('TYZM_MODEL_FUNC', TYZM_MODEL_CORE . 'func/');
!defined('TYZM_MODEL_MODEL') && define('TYZM_MODEL_MODEL', TYZM_MODEL_CORE . 'model/');
!defined('TYZM_MODEL_URL') && define('TYZM_MODEL_URL', $_W['siteroot'] . 'addons/tyzm_diamondvote/');
!defined('TYZM_MODEL_STATIC') && define('TYZM_MODEL_STATIC', TYZM_MODEL_URL . 'template/static/');
!defined('TYZM_MODEL_VERSION') && define('TYZM_MODEL_VERSION','online');
