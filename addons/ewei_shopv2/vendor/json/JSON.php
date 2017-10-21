<?php
define('SERVICES_JSON_SLICE', 1);
define('SERVICES_JSON_IN_STR', 2);
define('SERVICES_JSON_IN_ARR', 3);
define('SERVICES_JSON_IN_OBJ', 4);
define('SERVICES_JSON_IN_CMT', 5);
define('SERVICES_JSON_LOOSE_TYPE', 16);
define('SERVICES_JSON_SUPPRESS_ERRORS', 32);

if (class_exists('PEAR_Error')) {
	class Services_JSON_Error extends PEAR_Error
	{
		public function Services_JSON_Error($message = 'unknown error', $code = NULL, $mode = NULL, $options = NULL, $userinfo = NULL)
		{
			parent::PEAR_Error($message, $code, $mode, $options, $userinfo);
		}
	}

	return 1;
}


?>