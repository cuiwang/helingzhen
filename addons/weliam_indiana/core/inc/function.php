<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
} 
require_once WELIAM_INDIANA_INC.'pdo.func.php'; 
/*
 * 返回以$name为名字的类对象
 * */
function m($name = '') {
	static $_modules = array();
	if (isset($_modules[$name])) {
		return $_modules[$name];
	} 
	$model = WELIAM_INDIANA_CORE."model/" . strtolower($name) . '.php';
	if (!is_file($model)) {
		die(' Model ' . $name . ' Not Found!');
	} 
	require $model;
	$class_name = 'Welian_Indiana_' . ucfirst($name);//调用该类
	$_modules[$name] = new $class_name();
	return $_modules[$name];
} 

function is_array2($array) {
	if (is_array($array)) {
		foreach ($array as $k => $v) {
			return is_array($v);
		} 
		return false;
	} 
	return false;
} 
function object_array($array) {  
    if(is_object($array)) {  
        $array = (array)$array;  
    } if(is_array($array)) {  
        foreach($array as $key=>$value) {  
            $array[$key] = object_array($value);  
        }  
    }  
    return $array;  
}
function removeEmoji($text) {
    $clean_text = "";
    $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
    $clean_text = preg_replace($regexEmoticons, '', $text);
    $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
    $clean_text = preg_replace($regexSymbols, '', $clean_text);
    $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
    $clean_text = preg_replace($regexTransport, '', $clean_text);
    $regexMisc = '/[\x{2600}-\x{26FF}]/u';
    $clean_text = preg_replace($regexMisc, '', $clean_text);
    $regexDingbats = '/[\x{2700}-\x{27BF}]/u';
    $clean_text = preg_replace($regexDingbats, '', $clean_text);

    return $clean_text;
}

function wl_json($status = 1, $return = null) {
	$ret = array('status' => $status);
	if ($return) {
		$ret['result'] = $return;
	} 
	die(json_encode($ret));
}

function check_appcon($name,$dir) {
	if(empty($name)){
		return;
	}
	$mobile_goods  = array('allgoods', 'cart', 'detail', 'index');
	$mobile_invite = array('credit1_credit2', 'invite');
	$mobile_member = array('addaddress', 'address', 'changeaddress', 'myshare', 'mysharestore', 'otherpersonal', 'person', 'profile', 'upshare', 'winner' ,'login','appLogin','dataCombine');
	$mobile_order  = array('consume', 'order', 'recharge', 'recharge_records', 'order_get');
	$mobile_other  = array('allshare', 'goodsshare', 'past', 'postorder', 'result', 'rule', 'share_detail','jump','codeajax','notice','speech');
	$mobile_pay    = array('cash', 'pay', 'pay_ping', 'payto', 'transfer', 'transfer_ping', 'webhooks', 'endbuy', 'wechat_cash' , 'wechat_payment', 'wechat_pay','aibeipay','aibeibase','alipayconfig');
	
	$fun = strtolower(substr($name, 8));
	if(in_array($fun, $mobile_goods)){
		$dir .= 'mobile/goods/';
	}
	if(in_array($fun, $mobile_invite)){
		$dir .= 'mobile/invite/';
	}
	if(in_array($fun, $mobile_member)){
		$dir .= 'mobile/member/';
	}
	if(in_array($fun, $mobile_order)){
		$dir .= 'mobile/order/';
	}
	if(in_array($fun, $mobile_other)){
		$dir .= 'mobile/other/';
	}
	if(in_array($fun, $mobile_pay)){
		$dir .= 'mobile/pay/';
	}
	return array('dir' => $dir,'fun' => $fun);
}

function check_webcon($name,$dir) {
	if(empty($name)){
		return;
	}
	$web_goods = array('goods', 'period', 'showperiod', 'showrecords', 'category', 'shujuhuifu', 'srecords');
	$web_store = array('adv', 'navi','setting','notice','entry','cart');
	$web_member = array('member', 'showorder');
	$web_order = array('commentsave', 'order', 'sendprize');
	$web_app = array('machine', 'machine_async','plug_list','sms');
	$web_data = array('record');
	$web_sys = array('auth');
	
	$topnav = 'store';
	$fun = strtolower(substr($name, 5));
	if(in_array($fun, $web_goods)){
		$dir .= 'web/goods/';
		$topnav = 'goods';
	}
	if(in_array($fun, $web_store)){
		$dir .= 'web/store/';
		$topnav = 'store';
	}
	if(in_array($fun, $web_member)){
		$dir .= 'web/member/';
		$topnav = 'member';
	}
	if(in_array($fun, $web_order)){
		$dir .= 'web/order/';
		$topnav = 'order';
	}
	if(in_array($fun, $web_app)){
		$dir .= 'web/app/';
		$topnav = 'app';
	}
	if(in_array($fun, $web_data)){
		$dir .= 'web/data/';
		$topnav = 'data';
	}
	if(in_array($fun, $web_sys)){
		$dir .= 'web/sys/';
		$topnav = 'sys';
	}
	return array('dir' => $dir,'fun' => $fun,'nav' => $topnav);
}

/********************web页面跳转************************/
function web_url($segment, $params = array()) {
	global $_W;
	list($do, $op) = explode('/', $segment);
	$url = $_W['siteroot'] . 'web/index.php?c=site&a=entry&m=weliam_indiana&';
	if(!empty($do)) {
		$url .= "do={$do}&";
	}
	if(!empty($op)) {
		$url .= "op={$op}&";
	}
	if(!empty($params)) {
		$queryString = http_build_query($params, '', '&');
		$url .= $queryString;
	}
	return $url;
}

function app_url($segment, $params = array()) {
	global $_W;
	list($do, $op) = explode('/', $segment);
	$url = $_W['siteroot'] . 'app/index.php?i='.$_W['uniacid'].'&c=entry&m=weliam_indiana&';
	if(!empty($do)) {
		$url .= "do={$do}&";
	}
	if(!empty($op)) {
		$url .= "op={$op}&";
	}
	if(!empty($params)) {
		$queryString = http_build_query($params, '', '&');
		$url .= $queryString;
	}
	return $url;
}

function wl_debug($value) {
	echo "<br><pre>";
	print_r($value);
	echo "</pre>";
	exit ;
}

function saveSettings($settings) {
	global $_W;
	$pars = array('module' => 'weliam_indiana', 'uniacid' => $_W['uniacid']);
	$row = array();
	$row['settings'] = iserializer($settings);
	cache_build_account_modules();
	if (pdo_fetchcolumn("SELECT module FROM ".tablename('uni_account_modules')." WHERE module = :module AND uniacid = :uniacid", array(':module' => 'weliam_indiana', ':uniacid' => $_W['uniacid']))) {
		return pdo_update('uni_account_modules', $row, $pars) !== false;
	} else {
		return pdo_insert('uni_account_modules', array('settings' => iserializer($settings), 'module' => 'weliam_indiana' ,'uniacid' => $_W['uniacid'], 'enabled' => 1)) !== false;
	}
}

function keyExist($key = ''){
	global $_W;

	if (empty($key)) {
		return NULL;
	}

	$keyword = pdo_fetch('SELECT rid FROM ' . tablename('rule_keyword') . ' WHERE content=:content and uniacid=:uniacid limit 1 ', array(':content' => trim($key), ':uniacid' => $_W['uniacid']));

	if (!empty($keyword)) {
		$rule = pdo_fetch('SELECT * FROM ' . tablename('rule') . ' WHERE id=:id and uniacid=:uniacid limit 1 ', array(':id' => $keyword['rid'], ':uniacid' => $_W['uniacid']));

		if (!empty($rule)) {
			return $rule;
		}
	}
}

//参数设置
function setting_get_list(){
	global $_W;
	$settings=pdo_fetchall("select * from".tablename('weliam_indiana_setting')."where uniacid = {$_W['uniacid']}");
	foreach($settings as$key=>&$value){
		$value['value'] = unserialize($value['value']);
	}
	return $settings;
}

function wlsetting_load() {
	global $_W;
	$_W['wlsetting'] = cache_load('weliam_indiana_setting'.$_W['uniacid']);
	if (empty($_W['wlsetting'])) {
		$settings = pdo_fetch_many('weliam_indiana_setting', array('uniacid' => $_W['uniacid']), array('key', 'value'), 'key');
		if (is_array($settings)) {
			foreach ($settings as $k => &$v) {
				$settings[$k] = iunserializer($v['value']);
			}
		} else {
			$settings = array();
		}
		$_W['wlsetting'] = $settings;
		cache_write('weliam_indiana_setting'.$_W['uniacid'], $settings);
	}
	return $_W['wlsetting'];
}

function wlsetting_save($data, $key) {
	global $_W;
	if (empty($key)) {
		return FALSE;
	}
	
	$record = array();
	$record['value'] = iserializer($data);
	$exists = pdo_select_count('weliam_indiana_setting', array('key'=>$key,'uniacid' => $_W['uniacid']));
	if ($exists) {
		$return = pdo_update('weliam_indiana_setting', $record, array('key' => $key,'uniacid' => $_W['uniacid']));
	} else {
		$record['key'] = $key;
		$record['uniacid'] = $_W['uniacid'];
		$return = pdo_insert('weliam_indiana_setting', $record);
	}
	cache_write('weliam_indiana_setting'.$_W['uniacid'], '');
	
	return $return;
}

function wlsetting_read($key){
	global $_W;
	$settings = pdo_fetch_one('weliam_indiana_setting', array('key'=>$key,'uniacid' => $_W['uniacid']), array('value'));
	if (is_array($settings)) {
		$settings = iunserializer($settings['value']);
	} else {
		$settings = array();
	}
	return $settings;
}