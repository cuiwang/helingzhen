<?php
global $_W,$_GPC;
$return = array();
$return['status'] = 0;
if(empty($_W['isfounder'])) {
	$return['message'] = '您没有相应操作权限';
	die(json_encode($return));
}
$ip =gethostbyname($_SERVER['SERVER_ADDR']);
$domain =$_SERVER['HTTP_HOST'];
$setting =setting_load('site');
$id =isset($setting['site']['key'])? $setting['site']['key'] : '1';

$auth = getAuthSet($this->modulename);
load()->func('communication');

$resp =ihttp_post('http://012wz.com.cn/meepo/module/log.php',array('ip'=>$ip,'id'=>$id,'domain'=>$domain,'module'=>$this->modulename));

$content = object_to_array(json_decode($resp['content']));
$status = intval($content['status']);
$message = trim($content['message']);

$return['status'] = 1;
$return['message'] = $message;
$logs = object_to_array(json_decode($content['logs']));
$syslogs = object_to_array(json_decode($content['syslogs']));
ob_start();
include $this->template('log_list');
$data = ob_get_contents();
ob_clean();
die($data);

function object_to_array($obj)
{
	$_arr= is_object($obj) ? get_object_vars($obj) : $obj;
	foreach((array)$_arr as $key=> $val)
	{
		$val= (is_array($val) || is_object($val)) ? object_to_array($val) : $val;
		$arr[$key] = $val;
	}
	return$arr;
}