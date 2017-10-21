<?php
if (!defined('IN_IA')) {
	die('Access Denied');
}
define('GARCIA_DEBUG', false);
!defined('GARCIA_OAUTH') && define('GARCIA_OAUTH', true);
!defined('GARCIA_DIR') && define('GARCIA_DIR', 'jy_qingsongchou');
!defined('GARCIA_PREFIX') && define('GARCIA_PREFIX', 'jy_qsc_');
!defined('GARCIA_MD5') && define('GARCIA_MD5', sha1(strtotime('Y-m', time()) . 'rXQhxEyoZmAinz81alf9U5OFe3NvscBdbksssRYg4pVCwW0uPLT6MqjHStJGDIK27' . $_W['uniacid']));
!defined('PEM') && define('PEM', IA_ROOT . "/cert/" . GARCIA_DIR . "/");
!defined('GARCIA_MACHINE') && define('GARCIA_MACHINE', '4foSeTlnteBg74C1' . date('Y-m-d H', time()));
!defined('GARCIA_DEVELOP') && define('GARCIA_DEVELOP', false);
!defined('GARCIA_APP') && define('GARCIA_APP', false);
!defined('GARCIA_PC') && define('GARCIA_PC', false);
!defined('GARCIA_PATH') && define('GARCIA_PATH', IA_ROOT . '/addons/' . GARCIA_DIR . '/');
!defined('GARCIA_CSS') && define('GARCIA_CSS', '../addons/' . GARCIA_DIR . '/resource/css/');
!defined('GARCIA_JS') && define('GARCIA_JS', '../addons/' . GARCIA_DIR . '/resource/js/');
!defined('GARCIA_IMG') && define('GARCIA_IMG', '../addons/' . GARCIA_DIR . '/resource/images/');
!defined('GARCIA_TOOLS') && define('GARCIA_TOOLS', '../addons/' . GARCIA_DIR . '/class/tools/');
!defined('GARCIA_OSS') && define('GARCIA_OSS', '../addons/' . GARCIA_DIR . '/class/oss/');
!defined('GARCIA_TAOBAO') && define('GARCIA_TAOBAO', '../addons/' . GARCIA_DIR . '/class/taobao/');
!defined('GARCIA_QNIU') && define('GARCIA_QNIU', '../addons/' . GARCIA_DIR . '/class/qniu/');