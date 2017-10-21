<?php
 
function app_error($errcode = 0, $message = '')
{
	exit(json_encode(array('error' => $errcode, 'message' => empty($message) ? AppError::getError($errcode) : $message)));
}

function app_json($result = NULL)
{
	$ret = array();

	if (!is_array($result)) {
		$result = array();
	}

	$ret['error'] = 0;
	exit(json_encode(array_merge($ret, $result)));
}

if (!defined('IN_IA')) {
	exit('Access Denied');
}

require EWEI_SHOPV2_PLUGIN . 'app/core/error_code.php';
class AppMobileLoginPage extends PluginMobilePage
{
	public function __construct()
	{
		parent::__construct();
	}
}

?>
