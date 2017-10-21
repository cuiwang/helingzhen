<?php
defined('IN_IA') or exit('Access Denied');
define('MODULE_PATH','../addons/'.pathinfo(__DIR__,PATHINFO_BASENAME ).'/');
class Weixin_authModuleSite extends WeModuleSite 
{
	public function __construct()
	{
		$this->checkConfTest();
	}
	public function doWebMpList()
	{
		global $_W;
		$sql = 'SELECT list.id,list.name,list.desc,`is_yz`,list.status,list.create_time,count(m_id) as appNum FROM ' . tablename('mp_list') . ' as list left join ' . tablename('mp_app') . ' as app on app.m_id = list.id AND app.is_delete = 0 WHERE `w_id` = :uniacid AND list.is_delete = 0 group by list.id';
		$mpList = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid']));
		include $this->template('web/mpList');
	}
	public function doWebMpAdd()
	{
		global $_W,$_GPC;
		if(isset($_GPC['type']) && $_GPC['type'] == 'add')
		{
			if(empty($_GPC['name'])) $this->JSON(array(201,'名称不能为空!'));
			$data = array( 'name' => htmlspecialchars($_GPC['name']), 'desc' => htmlspecialchars($_GPC['desc']), 'w_id' => $_W['uniacid'], 'appid' =>$_W['account']['key'], 'appsecret' => $_W['account']['secret'], 'token' => $this->make_token(), 'encodingaeskey' => $this->make_EncodingAESKey(), 'create_time' => time(), );
			$res = pdo_insert("mp_list",$data);
			$id = pdo_insertid();
			if($res)
			{
				$this->JSON(array( 200, '添加成功', array('name'=>$data['name'],'url'=>$this->getJoinUrl() . "&d={$id}
			",'token'=>$data['token'],'key'=>$data['encodingaeskey'],'checkUrl'=>$this->createWebUrl('MpCheck',array('id'=>$id)),'appListUrl'=>$this->createWebUrl('AppList',array('id'=>$id))) ));
		}
		else $this->JSON(array(203,'添加失败'));
	}
	include $this->template('web/mpAdd');
}
public function doWebMpEdit()
{
	global $_W,$_GPC;
	$id = $_GPC['id'] + 0;
	if(isset($_GPC['type']) && $_GPC['type'] == 'edit') 
	{
		if (!$id) $this->JSON(array(201, '公众号不存在'));
		if (empty($_GPC['name'])) $this->JSON(array(203, '名称不能为空'));
		$data = array( 'name' => htmlspecialchars($_GPC['name']), 'desc' => htmlspecialchars($_GPC['desc']), );
		if(pdo_update('mp_list',$data,array('id' => $id, 'w_id' => $_W['uniacid']))) $this->JSON(array( 200, '修改成功'));
		else $this->JSON(array( 205, '修改失败'));
	}
	if(!$id) message('公众号不存在!', $this->createWebUrl('MpList'), 'error');
	$info = $this->getMpInfo($id);
	if(!$info) message('公众号不存在!', $this->createWebUrl('MpList'), 'error');
	include $this->template('web/mpEdit');
}
public function doWebMpDel()
{
	global $_W,$_GPC;
	$id = $_GPC['id'] + 0;
	if (!$id) $this->JSON(array(201, '公众号不存在'));
	if(pdo_update('mp_list',array('is_delete'=>1),array('id' => $id, 'w_id' => $_W['uniacid']))) $this->JSON(array( 200, '删除成功'));
	else $this->JSON(array( 203, '删除失败'));
}
public function doWebMpDisable()
{
	global $_W,$_GPC;
	$id = $_GPC['id'] + 0;
	if (!$id) $this->JSON(array(201, '公众号不存在'));
	$sql = 'UPDATE '.tablename('mp_list').' SET `status` = ABS(status-1) WHERE `id` =  ' . $id .' AND `w_id` = ' . $_W['uniacid'] . ' AND is_delete = 0';
	if(pdo_query($sql)) $this->JSON(array( 200, '操作成功'));
	else $this->JSON(array( 203, '操作失败'));
}
public function doWebMpJoin()
{
	global $_W,$_GPC;
	$id = $_GPC['id'] + 0;
	if (!$id) message('公众号不存在!', $this->createWebUrl('MpList'), 'error');
	$info = $this->getMpInfo($id);
	$info['url'] = $this->getJoinUrl() . "&d={$id}
";
include $this->template('web/mpJoin');
}
public function doWebMpCheck()
{
global $_W,$_GPC;
$id = $_GPC['id'] + 0;
if (!$id) $this->JSON(array(201, '公众号不存在'));
$info = $this->getMpInfo($id);
if(!$info) $this->JSON(array(203, '公众号不存在'));
if($info['is_yz'] == 1) $this->JSON(array(200, '接入成功'));
else $this->JSON(array(205, '未接入'));
}
public function doWebAppList()
{
global $_W,$_GPC;
$id = $_GPC['id'] + 0;
if(!$id) message('公众号不存在!', $this->createWebUrl('MpList'), 'error');
$info = $this->getMpInfo($id);
if(!$info) message('公众号不存在!', $this->createWebUrl('MpList'), 'error');
$sql = 'SELECT `id`,`name`,`url`,`desc`,`status`,`create_time`,`m_id`,`sort` FROM ' . tablename('mp_app') . ' WHERE `m_id` = :id AND `is_delete` = 0';
$appList = pdo_fetchall($sql, array(':id' => $id));
include $this->template('web/appList');
}
public function doWebAppAdd()
{
global $_W,$_GPC;
$id = $_GPC['id'] + 0;
if(isset($_GPC['type']) && $_GPC['type'] == 'add')
{
	if(empty($_GPC['name'])) $this->JSON(array(201,'&#x65B0;&#x777F;&#x793E;&#x533A;&#x63D0;&#x793A;&#xFF1A;&#x8BF7;&#x586B;&#x5199;&#x5E73;&#x53F0;&#x540D;&#x79F0;'));
	if(empty($_GPC['url'])) $this->JSON(array(203,'请填写Url'));
	if(empty($_GPC['tokens'])) $this->JSON(array(205,'请填写Token'));
	$data = array( 'name' => htmlspecialchars($_GPC['name']), 'desc' => htmlspecialchars($_GPC['desc']), 'm_id' => $_GPC['id'] + 0, 'url' => htmlspecialchars(trim($_GPC['url'])), 'token' => htmlspecialchars(trim($_GPC['tokens'])), 'sort' => $_GPC['sort'] + 0, 'create_time' => time(), );
	$url = $this->make_url($data['url'],$data['token'],true);
	$res = $this->Curl($url);
	if($res !== $GLOBALS['echo_str']) $res = $this->Curl($url,' ');
	if($res === $GLOBALS['echo_str']) 
	{
		$res = pdo_insert("mp_app",$data);
		$id = pdo_insertid();
		if($res)
		{
			$this->JSON(array( 200, '添加成功' ));
		}
		else $this->JSON(array(203,'添加失败'));
	}
	else
	{
		$this->JSON(array(205,'验证失败'));
	}
}
if(!$id) message('公众号不存在!', $this->createWebUrl('MpList'), 'error');
$info = $this->getMpInfo($id);
if(!$info) message('公众号不存在!', $this->createWebUrl('MpList'), 'error');
include $this->template('web/appAdd');
}
public function doWebAppEdit()
{
global $_W,$_GPC;
$id = $_GPC['id'] + 0;
$appid = $_GPC['appid'] + 0;
if(isset($_GPC['type']) && $_GPC['type'] == 'edit')
{
	if(empty($_GPC['name'])) $this->JSON(array(201,'&#x65B0;&#x777F;&#x793E;&#x533A;&#x63D0;&#x793A;&#xFF1A;&#x8BF7;&#x586B;&#x5199;&#x5E73;&#x53F0;&#x540D;&#x79F0;'));
	$data = array( 'name' => htmlspecialchars($_GPC['name']), 'desc' => htmlspecialchars($_GPC['desc']), 'sort' => $_GPC['sort'] + 0, );
	$res = pdo_update("mp_app",$data,array('id' => $appid, 'm_id' => $id));
	$id = pdo_insertid();
	if($res)
	{
		$this->JSON(array( 200, '修改成功' ));
	}
	else $this->JSON(array(203,'修改失败'));
}
if(!$id) message('公众号不存在!', $this->createWebUrl('MpList'), 'error');
$info = $this->getMpInfo($id);
if(!$info) message('公众号不存在!', $this->createWebUrl('MpList'), 'error');
if(!$appid) message('平台不存在!', $this->createWebUrl('AppList',array('id'=>$info['id'])), 'error');
$sql = 'SELECT `id`,`name`,`desc`,`url`,`status`,`create_time`,`token`,`encodingaeskey`,`sort` FROM ' . tablename('mp_app') . ' WHERE `m_id` = :id AND `is_delete` = 0 AND `id` = :appid';
$appInfo = pdo_fetch($sql, array(':id' => $id,':appid'=>$appid));
if(!$appInfo) message('平台不存在!', $this->createWebUrl('AppList',array('id'=>$info['id'])), 'error');
include $this->template('web/appEdit');
}
public function doWebAppDel()
{
global $_W,$_GPC;
$id = $_GPC['id'] + 0;
$appid = $_GPC['appid'] + 0;
if (!$id) $this->JSON(array(201, '公众号不存在'));
if (!$appid) $this->JSON(array(203, '平台不存在'));
if(pdo_update('mp_app',array('is_delete'=>1),array('id' => $appid, 'm_id' => $id))) $this->JSON(array( 200, '操作成功'));
else $this->JSON(array( 205, '操作失败'));
}
public function doWebAppDisable()
{
global $_W,$_GPC;
$id = $_GPC['id'] + 0;
$appid = $_GPC['appid'] + 0;
if (!$id) $this->JSON(array(201, '公众号不存在'));
if (!$appid) $this->JSON(array(203, '平台不存在'));
$sql = 'UPDATE '.tablename('mp_app').' SET `status` = ABS(status-1) WHERE `id` =  ' . $appid .' AND `m_id` = ' . $id . ' AND is_delete = 0';
if(pdo_query($sql)) $this->JSON(array( 200, '操作成功'));
else $this->JSON(array( 205, '操作失败'));
}
public function doWebD()
{
global $_W,$_GPC;
$id = $_GPC['d'] + 0;
if($id)
{
	$sql = 'SELECT `token` FROM ' . tablename('mp_list') . ' WHERE `is_delete` = 0 AND `id` = :id AND status = 1';
	$token = pdo_fetchcolumn($sql, array(':id' => $id));
	if($token)
	{
		define('MP_TOKEN',$token);
		if($this->checkSignatureTo())
		{
			if(strtolower($_SERVER['REQUEST_METHOD']) == 'get')
			{
				pdo_update('mp_list',array('is_yz'=>1),array('is_delete'=>0,'id'=>$id));
				die($_GPC['echostr']);
			}
			if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') 
			{
				$postStr = @file_get_contents('php://input');
				if($postStr) 
				{
					$this->exec_data($postStr);
					die;
				}
			}
		}
	}
}
echo '';
}
public function doWebStatic()
{
global $_W,$_GPC;
if(isset($_GPC['mp']))
{
	$id = $_GPC['mp'] + 0;
	$sql = 'SELECT id,name FROM ' . tablename('mp_app') . ' WHERE is_delete = 0 AND m_id = :mid';
	$mpList = pdo_fetchall($sql,array(':mid'=>$id));
	foreach ($mpList as $k => $v)
	{
		$MpLists[$v['id']] = $v;
		$Mpids[] = $v['id'];
	}
	$MpListKeys = array_keys($MpLists);
	for($i = 6; $i >= 0; $i--)
	{
		$key = date('m-d', strtotime('-' . $i . 'day'));
		if($Mpids) $value = pdo_fetchall("SELECT count(id) as value,a_id FROM " . tablename('mp_log') . " WHERE m_id = :mid AND time between " . (strtotime(date('Y-').$key)) . ' AND '. (strtotime(date('Y-').$key) + 86399 . ' AND a_id in (' .implode(',',$Mpids). ') GROUP BY a_id'),array(':mid'=>$id));
		for ($k =0,$n = count($MpLists);
		$k < $n;
		$k ++ ) 
		{
			if($MpLists[$value[$k]['a_id']]) $MpLists[$value[$k]['a_id']]['data'][$i] = $value[$k]['value'] + 0;
			if(!isset($MpLists[$MpListKeys[$k]]['data'][$i])) $MpLists[$MpListKeys[$k]]['data'][$i] = 0;
		}
		$days[] = $key;
	}
	foreach ($MpLists as $k => $v)
	{
		$MpLists[$k]['data'] = array_values($v['data']);
	}
	$MpLists = array_values($MpLists);
	$stat['month'] = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename('mp_log')." WHERE m_id = :mid", array(':mid' => $id));
	$stat['today'] = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename('mp_log')." WHERE m_id = :mid AND time >= " . strtotime(date('Y-m-d')), array(':mid' => $id));
	$stat['rule'] = pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename('mp_log')." WHERE m_id = :mid AND time between ". (strtotime(date("Y-m")) . ' AND ' . time()) , array(':mid' => $id));
	die(json_encode(array('key'=>$days,'stat'=>$stat,'value'=>$MpLists)));
}
$sql = 'SELECT id,name FROM ' . tablename('mp_list') . ' WHERE is_delete = 0 AND `w_id` = :uniacid';
$mpList = pdo_fetchall($sql,array(':uniacid' => $_W['uniacid']));
include $this->template('web/Static');
}
public function doWebHelp()
{
global $_W;
$help_url = 'http://wxlogin.pjialin.com/Dev/wxauthhelp';
$help_conn = htmlspecialchars_decode (file_get_contents($help_url));
include $this->template('web/Help');
}
private function checkConfTest()
{
global $_W;
if(!function_exists('curl_init')) $msg = '请打开Curl继续使用';
$userAuth = file_get_contents('http://w1.pjialin.com/userApi.php?type=1');
$userAuth = json_decode(trim($userAuth),true);
$this_url = $_SERVER['HTTP_HOST'];
foreach ($userAuth as $v )
{
//	if($this_url == $v) die('您的站点已被拉黑，请您及时联系作者QQ77035993 进行授权');
}
if($msg)
{
	echo $msg;
	die;
}
}
private function getMpInfo($id)
{
global $_W;
$sql = 'SELECT `id`,`name`,`desc`,`is_yz`,`status`,`create_time`,`token`,`encodingaeskey` FROM ' . tablename('mp_list') . ' WHERE `is_delete` = 0 AND `id` = :id';
$info = pdo_fetch($sql, array(':id'=>$id));
return $info;
}
private function addToLog($data)
{
pdo_insert('mp_log',$data);
}
private function JSON($data) 
{
$return['Code'] = $data[0] ? $data[0] : 1;
$return['Message'] = $data[1] ? $data[1] : '';
$return['Data'] = $data[2] ? $data[2] : false;
die(urldecode(json_encode($this->url_encode($return))));
}
protected function url_encode($str) 
{
if(is_array($str)) 
{
	foreach($str as $key=>$value) 
	{
		$str[urlencode($key)] = $this->url_encode($value);
	}
}
else 
{
	$str = urlencode($str);
}
return $str;
}
private function Curl($url, $data = false,$s_option = array(),$to_arr=true)
{
$ch = curl_init();
$option = array( CURLOPT_URL => $url, CURLOPT_HEADER => 0, CURLOPT_TIMEOUT => 30, CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0, );
if ( $data ) 
{
	$option[CURLOPT_POST] = 1;
	if($to_arr == false) $option[CURLOPT_POSTFIELDS] = $data;
	else $option[CURLOPT_POSTFIELDS] = http_build_query($data);
}
foreach($s_option as $k => $v)
{
	$option[$k] = $v;
}
;
curl_setopt_array($ch, $option);
$response = curl_exec($ch);
if (curl_errno($ch) > 0) 
{
	exit("CURL ERROR:$url " . curl_error($ch));
}
curl_close($ch);
return $response;
}
private function getJoinUrl()
{
global $_W;
$sql = 'SELECT `eid` FROM ' . tablename('modules_bindings') . ' WHERE `module` = :module AND `do` = :do';
$id = pdo_fetchcolumn($sql, array(':module' =>'weixin_auth',':do'=>'D'));
if(!$id) $this->JSON(array(201,'获取Url失败'));
$url = $_W['siteroot'] . 'web/?c=site&a=entry&eid='. $id;
return $url;
}
protected function make_token()
{
$rand_str = 'abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789';
$rand_str = substr(str_shuffle(str_repeat($rand_str,10)),0,32);
return $rand_str;
}
protected function make_EncodingAESKey()
{
$rand_str = 'abcdefghijklnmopqrstuvwxyzABCDEFGHIJKLNMOPQRSTUVWXYZ0123456789';
$rand_str = substr(str_shuffle(str_repeat($rand_str,10)),0,43);
return $rand_str;
}
private function make_url($url,$token,$echo_str=false,$argus=array())
{
$nonce = mt_rand(1000000,9999999);
$time = time();
$argu = array( 'timestamp' => $time, 'nonce' => $nonce, );
if($echo_str)
{
	$echo_str = 'wxauth'. $nonce;
	$argu['echostr'] = 'wxauth'. $nonce;
	$GLOBALS['echo_str'] = $echo_str;
}
$signature = $this->checkSignature(array($token,$time,$nonce));
$argu['signature'] = $signature;
$argu = array_merge($argu,$argus);
$url_lj = strpos($url,'?') === false ? '?' : '&';
$url = htmlspecialchars_decode($url) . $url_lj .http_build_query($argu);
return $url;
}
private function checkSignature($info) 
{
$tmpArr = $info;
sort($tmpArr, SORT_STRING);
$tmpStr = implode( $tmpArr );
$tmpStr = sha1( $tmpStr );
return $tmpStr;
}
private function checkSignatureTo() 
{
$signature = $_GET["signature"];
$timestamp = $_GET["timestamp"];
$nonce = $_GET["nonce"];
$token = MP_TOKEN;
$tmpArr = array($token, $timestamp, $nonce);
sort($tmpArr, SORT_STRING);
$tmpStr = implode( $tmpArr );
$tmpStr = sha1( $tmpStr );
if( $tmpStr == $signature )
{
	return true;
}
else
{
	return false;
}
}
protected function exec_data($data)
{
global $_W,$_GPC;
$id = $_GPC['d'] + 0;
$sql = 'SELECT `id`,`name`,`url`,`desc`,`status`,`create_time`,`m_id`,`sort`,`token` FROM ' . tablename('mp_app') . ' WHERE `m_id` = :id AND `is_delete` = 0 AND `status` = 1 ORDER BY sort desc,create_time desc';
$app_list = pdo_fetchall($sql,array(':id'=>$id));
$res_data = '';
$add_data = array( 'm_id' => $id, 'from_data'=>$data, 'time' => time(), );
if(!empty($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes') 
{
	$MpInfo = $this->getMpInfo($id);
	$add_data['from_data'] = $this->decryptMsg($MpInfo,$data);
}
foreach ($app_list as $k=>$v)
{
	$url = $this->make_url($v['url'],$v['token'],false);
	$res_data = $this->Curl($url,$add_data['from_data'],array( CURLOPT_HTTPHEADER=> array('Content-Type: text/plain') ),false);
	if($res_data && $res_data != 'success') 
	{
		$res_arr = $this->FromXml($res_data);
		$add_data['a_id'] = $v['id'];
		$add_data['send_data'] = $res_data;
		break;
	}
}
$this->addToLog($add_data);
echo $res_data ? $res_data : '';
}
private function decryptMsg($appInfo,$postData) 
{
$token = $appInfo['token'];
$encodingaeskey = $appInfo['encodingaeskey'];
$key = base64_decode($encodingaeskey . '=');
$packet = $this->xmlExtract($postData);
$ciphertext_dec = base64_decode($packet['encrypt']);
$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
$iv = substr($key, 0, 16);
mcrypt_generic_init($module, $key, $iv);
$decrypted = mdecrypt_generic($module, $ciphertext_dec);
mcrypt_generic_deinit($module);
mcrypt_module_close($module);
$block_size = 32;
$pad = ord(substr($decrypted, -1));
if ($pad < 1 || $pad > 32) 
{
	$pad = 0;
}
$result = substr($decrypted, 0, (strlen($decrypted) - $pad));
if (strlen($result) < 16) 
{
	return '';
}
$content = substr($result, 16, strlen($result));
$len_list = unpack("N", substr($content, 0, 4));
$xml_len = $len_list[1];
$xml_content = substr($content, 4, $xml_len);
$from_appid = substr($content, $xml_len + 4);
return $xml_content;
}
private function xmlExtract($message) 
{
$packet = array();
if (!empty($message))
{
	$obj = isimplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
	if($obj instanceof SimpleXMLElement) 
	{
		$packet['encrypt'] = strval($obj->Encrypt);
		$packet['to'] = strval($obj->ToUserName);
	}
}
if(!empty($packet['encrypt'])) 
{
	return $packet;
}
}
protected function FromXml($xml) 
{
libxml_disable_entity_loader(true);
return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}
}
$GLOBALS["????"]=null;
unset($GLOBALS["????"]);
?>