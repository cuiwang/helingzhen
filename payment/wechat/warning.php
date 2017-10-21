<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
require '../../source/bootstrap.inc.php';
$input = file_get_contents('php://input');

if (preg_match('/(\<\!DOCTYPE|\<\!ENTITY)/i', $input)) {
	exit('fail');
}
libxml_disable_entity_loader(true);
$obj = simplexml_load_string($input, 'SimpleXMLElement', LIBXML_NOCDATA);
if($obj instanceof SimpleXMLElement && !empty($obj->FeedBackId)) {
	$data = array(
		'appid' => trim($obj->AppId),
		'timestamp' => trim($obj->TimeStamp),
		'errortype' => trim($obj->ErrorType),
		'description' => trim($obj->Description),
		'alarmcontent' => trim($obj->AlarmContent),
		'appsignature' => trim($obj->AppSignature),
		'signmethod' => trim($obj->SignMethod),
	);
	require '../../framework/bootstrap.inc.php';
	WeUtility::logging('pay-warning', $input);
}
exit('success');
