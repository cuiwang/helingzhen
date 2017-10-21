<?php
/**
 * [WECHAT 2017]
 * [WECHAT  a free software]
 */
defined('IN_IA') or exit('Access Denied');

define('ACCOUNT_PLATFORM_API_ACCESSTOKEN', 'https://api.weixin.qq.com/cgi-bin/component/api_component_token');
define('ACCOUNT_PLATFORM_API_PREAUTHCODE', 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=');
define('ACCOUNT_PLATFORM_API_LOGIN', 'https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid=%s&pre_auth_code=%s&redirect_uri=%s');
define('ACCOUNT_PLATFORM_API_QUERY_AUTH_INFO', 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=');
define('ACCOUNT_PLATFORM_API_ACCOUNT_INFO', 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=');
define('ACCOUNT_PLATFORM_API_REFRESH_AUTH_ACCESSTOKEN', 'https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=');
define('ACCOUNT_PLATFORM_API_OAUTH_CODE', 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&component_appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%s#wechat_redirect');
define('ACCOUNT_PLATFORM_API_OAUTH_USERINFO', 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=%s&component_appid=%s#wechat_redirect');
define('ACCOUNT_PLATFORM_API_OAUTH_INFO', 'https://api.weixin.qq.com/sns/oauth2/component/access_token?appid=%s&component_appid=%s&code=%s&grant_type=authorization_code&component_access_token=');

load()->classs('weixin.account');
load()->func('communication');

class WeiXinPlatform extends WeiXinAccount {
	public $appid;
	public $appsecret;
	public $encodingaeskey;
	public $token;
	public $refreshtoken;
	public $account;

	function __construct($account = array()) {
		$setting = setting_load('platform');
		$this->appid = $setting['platform']['appid'];
		$this->appsecret = $setting['platform']['appsecret'];
		$this->token = $setting['platform']['token'];
		$this->encodingaeskey = $setting['platform']['encodingaeskey'];
		$this->account = $account;
		if ($this->account['key'] == 'wx570bc396a51b8ff8') {
			$this->account['key'] = $this->appid;
			$this->openPlatformTestCase();
		}
		$this->account['account_appid'] = $this->account['key'];
		$this->account['key'] = $this->appid;
	}

	function getComponentAccesstoken() {
		$accesstoken = cache_load('account:component:assesstoken');
		if (empty($accesstoken) || empty($accesstoken['value']) || $accesstoken['expire'] < TIMESTAMP) {
			$ticket = cache_load('account:ticket');
			if (empty($ticket)) {
				return error(1, '缺少接入平台关键数据，等待微信开放平台推送数据，请十分钟后再试或是检查“授权事件接收URL”是否写错（index.php?c=account&amp;a=auth&amp;do=ticket地址中的&amp;符号容易被替换成&amp;amp;）');
			}
			$data = array(
				'component_appid' => $this->appid,
				'component_appsecret' => $this->appsecret,
				'component_verify_ticket' => $ticket,
			);
			$response = $this->request(ACCOUNT_PLATFORM_API_ACCESSTOKEN, $data);
			if (is_error($response)) {
				$errormsg = $this->error_code($response['errno'], $response['message']);
				return error($response['errno'], $errormsg);
			}
			$accesstoken = array(
				'value' => $response['component_access_token'],
				'expire' => TIMESTAMP + intval($response['expires_in']),
			);
			cache_write('account:component:assesstoken', $accesstoken);
		}
		return $accesstoken['value'];
	}

	function getPreauthCode() {
		$preauthcode = cache_load('account:preauthcode');
		if (true || empty($preauthcode) || empty($preauthcode['value']) || $preauthcode['expire'] < TIMESTAMP) {
			$component_accesstoken = $this->getComponentAccesstoken();
			if (is_error($component_accesstoken)) {
				return $component_accesstoken;
			}
			$data = array(
				'component_appid' => $this->appid
			);
			$response = $this->request(ACCOUNT_PLATFORM_API_PREAUTHCODE . $component_accesstoken, $data);
			if (is_error($response)) {
				return $response;
			}
			$preauthcode = array(
				'value' => $response['pre_auth_code'],
				'expire' => TIMESTAMP + intval($response['expires_in']),
			);
			cache_write('account:preauthcode', $preauthcode);
		}
		return $preauthcode['value'];
	}

	public function getAuthInfo($code) {
		$component_accesstoken = $this->getComponentAccesstoken();
		if (is_error($component_accesstoken)) {
			return $component_accesstoken;
		}
		$post = array(
			'component_appid' => $this->appid,
			'authorization_code' => $code,
		);
		$response = $this->request(ACCOUNT_PLATFORM_API_QUERY_AUTH_INFO . $component_accesstoken, $post);
		if (is_error($response)) {
			return $response;
		}
		$this->setAuthRefreshToken($response['authorization_info']['authorizer_refresh_token']);
		return $response;
	}

	public function getAccountInfo($appid = '') {
		$component_accesstoken = $this->getComponentAccesstoken();
		if (is_error($component_accesstoken)) {
			return $component_accesstoken;
		}
		$appid = !empty($appid) ? $appid : $this->account['account_appid'];
		$post = array(
			'component_appid' => $this->appid,
			'authorizer_appid' => $appid,
		);
		$response = $this->request(ACCOUNT_PLATFORM_API_ACCOUNT_INFO . $component_accesstoken, $post);
		if (is_error($response)) {
			return $response;
		}
		return $response;
	}

	public function getAccessToken() {
		$cachename = 'account:auth:accesstoken:'.$this->account['account_appid'];
		$auth_accesstoken = cache_load($cachename);
		if (empty($auth_accesstoken) || empty($auth_accesstoken['value']) || $auth_accesstoken['expire'] < TIMESTAMP) {
			$component_accesstoken = $this->getComponentAccesstoken();
			if (is_error($component_accesstoken)) {
				return $component_accesstoken;
			}
			$this->refreshtoken = $this->getAuthRefreshToken();
			$data = array(
				'component_appid' => $this->appid,
				'authorizer_appid' => $this->account['account_appid'],
				'authorizer_refresh_token' => $this->refreshtoken,
			);
			$response = $this->request(ACCOUNT_PLATFORM_API_REFRESH_AUTH_ACCESSTOKEN . $component_accesstoken, $data);
			if (is_error($response)) {
				return $response;
			}
			if ($response['authorizer_refresh_token'] != $this->refreshtoken) {
				$this->setAuthRefreshToken($response['authorizer_refresh_token']);
			}
			$auth_accesstoken = array(
				'value' => $response['authorizer_access_token'],
				'expire' => TIMESTAMP + intval($response['expires_in']),
			);
			cache_write($cachename, $auth_accesstoken);
		}
		return $auth_accesstoken['value'];
	}

	public function fetch_token() {
		return $this->getAccessToken();
	}
	
	public function getAuthLoginUrl($type=0) {
		$preauthcode = $this->getPreauthCode();
		if (is_error($preauthcode)) {
			$authurl = "javascript:alert('{$preauthcode['message']}');";
		} else {
			if($type){
				$authurl = sprintf(ACCOUNT_PLATFORM_API_LOGIN, $this->appid, $preauthcode, urlencode($GLOBALS['_W']['siteroot'] . 'index.php?c=account&a=auth&do=forward&type='.$type));
			}else{
				$authurl = sprintf(ACCOUNT_PLATFORM_API_LOGIN, $this->appid, $preauthcode, urlencode($GLOBALS['_W']['siteroot'] . 'index.php?c=account&a=auth&do=forward'));
			}
		}
		return $authurl;
	}

	public function getOauthCodeUrl($callback, $state = '') {
		return sprintf(ACCOUNT_PLATFORM_API_OAUTH_CODE, $this->account['account_appid'], $this->appid, $callback, $state);
	}

	public function getOauthUserInfoUrl($callback, $state = '') {
		return sprintf(ACCOUNT_PLATFORM_API_OAUTH_USERINFO, $this->account['account_appid'], $callback, $state, $this->appid);
	}

	public function getOauthInfo($code = '') {
		$component_accesstoken = $this->getComponentAccesstoken();
		if (is_error($component_accesstoken)) {
			return $component_accesstoken;
		}
		$apiurl = sprintf(ACCOUNT_PLATFORM_API_OAUTH_INFO . $component_accesstoken, $this->account['account_appid'], $this->appid, $code);
		$response = $this->request($apiurl);
		if (is_error($response)) {
			return $response;
		}
		cache_write('account:oauth:refreshtoken:'.$this->account['account_appid'], $response['refresh_token']);
		return $response;
	}
	
	public function getJsApiTicket(){
		$cachekey = "jsticket:{$this->account['acid']}";
		$js_ticket = cache_load($cachekey);
		if (empty($js_ticket) || empty($js_ticket['value']) || $js_ticket['expire'] < TIMESTAMP) {
			$access_token = $this->getAccessToken();
			if(is_error($access_token)){
				return $access_token;
			}
			$apiurl = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
			$response = $this->request($apiurl);
			$js_ticket = array(
				'value' => $response['ticket'],
				'expire' => TIMESTAMP + $response['expires_in'] - 200,
			);
			cache_write($cachekey, $js_ticket);
		}
		$this->account['jsapi_ticket'] = $js_ticket;
		return $js_ticket['value'];
	}
	
	public function getJssdkConfig($url = ''){
		global $_W;
		$jsapiTicket = $this->getJsApiTicket();
		if(is_error($jsapiTicket)){
			$jsapiTicket = $jsapiTicket['message'];
		}
		$nonceStr = random(16);
		$timestamp = TIMESTAMP;
		$url = empty($url) ? $_W['siteurl'] : $url;
		$string1 = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
		$signature = sha1($string1);
		$config = array(
			"appId" => $this->account['account_appid'],
			"nonceStr" => $nonceStr,
			"timestamp" => "$timestamp",
			"signature" => $signature,
		);
		if(DEVELOPMENT) {
			$config['url'] = $url;
			$config['string1'] = $string1;
			$config['name'] = $this->account['name'];
		}
		return $config;
	}

	public function openPlatformTestCase() {
		global $_GPC;
		$post = file_get_contents('php://input');
		WeUtility::logging('platform-test-message', $post);
		$encode_message = $this->xmlExtract($post);
		$message = aes_decode($encode_message['encrypt'], $this->encodingaeskey);
		$message = $this->parse($message);
		$response = array(
			'ToUserName' => $message['from'],
			'FromUserName' => $message['to'],
			'CreateTime' => TIMESTAMP,
			'MsgId' => TIMESTAMP,
			'MsgType' => 'text',
		);
		if ($message['content'] == 'TESTCOMPONENT_MSG_TYPE_TEXT') {
			$response['Content'] = 'TESTCOMPONENT_MSG_TYPE_TEXT_callback';
		}
		if ($message['msgtype'] == 'event') {
			$response['Content'] = $message['event'] . 'from_callback';
		}
		if (strexists($message['content'], 'QUERY_AUTH_CODE')) {
			list($sufixx, $authcode) = explode(':', $message['content']);
			$auth_info = $this->getAuthInfo($authcode);
			WeUtility::logging('platform-test-send-message', var_export($auth_info, true));
			$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=". $auth_info['authorization_info']['authorizer_access_token'];
			$data = array(
				'touser' => $message['from'],
				'msgtype' => 'text',
				'text' => array('content' => $authcode.'_from_api'),
			);
			$response = ihttp_request($url, urldecode(json_encode($data)));
			exit('');
		}
		$xml = array(
			'Nonce' => $_GPC['nonce'],
			'TimeStamp' => $_GPC['timestamp'],
			'Encrypt' => aes_encode(array2xml($response), $this->encodingaeskey, $this->appid),
		);
		$signature = array($xml['Encrypt'], $this->token, $_GPC['timestamp'], $_GPC['nonce']);
		sort($signature, SORT_STRING);
		$signature = implode($signature);
		$xml['MsgSignature'] = sha1($signature);
		exit(array2xml($xml));
	}

	private function request($url, $post = array()) {
		$response = ihttp_request($url, json_encode($post));
		$response = json_decode($response['content'], true);
		if (empty($response) || !empty($response['errcode'])) {
			return error($response['errcode'], $this->error_code($response['errcode'], $response['errmsg']));
		}
		return $response;
	}

	private function getAuthRefreshToken() {
		$auth_refresh_token = cache_load('account:auth:refreshtoken:'.$this->account['acid']);
		if (empty($auth_refresh_token)) {
			$auth_refresh_token = $this->account['auth_refresh_token'];
			cache_write('account:auth:refreshtoken:'.$this->account['acid'], $auth_refresh_token);
		}
		return $auth_refresh_token;
	}

	private function setAuthRefreshToken($token) {
		pdo_update('account_wechats', array('auth_refresh_token' => $token), array('acid' => $this->account['acid']));
		cache_write('account:auth:refreshtoken:'.$this->account['acid'], $token);
	}
	//以下为小程序一键发布代码
	public function build($appid,$version=''){
		
		//提交代码
		global $_W;
		$wxapp=pdo_get('account_wxapp',array('key'=>$appid));
		$version=$version?$version:$wxapp['version'];
		$url='https://api.weixin.qq.com/wxa/commit?access_token='.$wxapp['access_token'];
		$module=pdo_get('modules',array('name'=>$wxapp['plugin']),array('template_id','item_list'));
		if(!$module['template_id']){
			itoast('上传小程序代码出错：模块小程序ID为空', url('account/manage'), 'error');
		}
		$ext=array(
			'uniacid'=>$wxapp['uniacid'],
			'acid'=>$wxapp['acid'],
			'wxa_uniacid'=>$wxapp['uniacid'],
			'version'=>$version,
			'siteroot' => $_W['siteroot'].'app/index.php',
			'attachurl' => $_W['siteroot'].'attachment/',
			'multiid'=>'0',
			'design_method'=>'3'
		);
		$post=array(
			'template_id'=>$module['template_id'],
			'user_version'=>$version,
			'user_desc'=>$wxapp['description'],
		);
		$post['ext_json']=json_encode(array('extAppid'=>$wxapp['key'],'ext'=>$ext),JSON_UNESCAPED_UNICODE);
		$post=json_encode($post,JSON_UNESCAPED_UNICODE);
		//print_r($post);exit;
		load()->func('communication');
		$result=ihttp_request($url,$post);
		$result=$result['content'];
		$result=json_decode($result,true);
		if($result['errcode']){
			itoast('上传小程序代码出错：'.$result['errcode'].$result['errmsg'], url('account/manage'), 'error');
		}
		//读取可用分类
		$url='https://api.weixin.qq.com/wxa/get_category?access_token='.$wxapp['access_token'];
		$result=ihttp_get($url);
		$result=$result['content'];
		$result=json_decode($result,true);
		$category_list=$result['category_list'];
		//print_r($category_list);exit;
		//提交审核
		$url='https://api.weixin.qq.com/wxa/submit_audit?access_token='.$wxapp['access_token'];
		$module['item_list']=array('0'=>array(
			'address'=>'pages/index/index',
			'tag'=>'微官网',
			'title'=>'首页',
			'first_class'=>$category_list['0']['first_class'],
			'second_class'=>$category_list['0']['second_class'],
			'third_class'=>$category_list['0']['third_class'],
			'first_id'=>$category_list['0']['first_id'],
			'second_id'=>$category_list['0']['second_id'],
			'third_id'=>$category_list['0']['third_id'],
		));
		//print_r($module['item_list']);exit;
		$post=array('item_list'=>$module['item_list']);
		$post=json_encode($post,JSON_UNESCAPED_UNICODE);
		$result=ihttp_request($url,$post);
		$result=$result['content'];
		$result=json_decode($result,true);
		if($result['errcode']){
			itoast('提交审核小程序代码出错：'.$result['errcode'].$result['errmsg'], url('account/manage'), 'error');
		}
		pdo_update('account_wxapp',array('status'=>1,'fail_reason'=>$result['auditid']),array('key'=>$appid));
		//提交成功，等待审核结果
		return true;
	}
	public function check($appid){
		//查询审核结果
		global $_W;
		$wxapp=pdo_get('account_wxapp',array('key'=>$appid));
		if($wxapp['status']==2){
			itoast('审核成功！请点击一键发布按钮发布！', url('account/manage'), 'success');
		}elseif($wxapp['status']==3){
			itoast('审核失败！具体原因为：'.$wxapp['fail_reason'].'。请优化后重新提交！', url('account/manage'), 'success');
		}elseif($wxapp['status']==4){
			itoast('小程序已发布成功！无需重复审核！', '', '');
		}
		$this->check_access_token($wxapp);
		//if(intval($wxapp['fail_reason'])){
		//	$url='https://api.weixin.qq.com/wxa/get_auditstatus?access_token='.$wxapp['access_token'];
		//	$post=array('auditid'=>$wxapp['fail_reason']);
		//	$post=json_encode($post,JSON_UNESCAPED_UNICODE);
		//	$result=ihttp_request($url,$post);
		//}else{
			$url='https://api.weixin.qq.com/wxa/get_latest_auditstatus?access_token='.$wxapp['access_token'];
			$result=ihttp_get($url);
		//}
		$result=$result['content'];
		$result=json_decode($result,true);
		if($result['errcode']){
			itoast('提交审核小程序代码出错：'.$result['errcode'].$result['errmsg'], url('account/manage'), 'error');
		}
		if(!$result['status']){
			pdo_update('account_wxapp',array('status'=>2,'fail_reason'=>''),array('key'=>$appid));
			itoast('审核成功！请点击一键发布按钮发布！', url('account/manage'), 'success');
		}elseif($result['status']==1){
			pdo_update('account_wxapp',array('status'=>3,'fail_reason'=>$result['Reason']),array('key'=>$appid));
			itoast('审核失败！具体原因为：'.$result['Reason'].'。请优化后重新提交！', url('account/manage'), 'success');
		}else{
			itoast('该小程序还在审核中！请耐心等待！', url('account/manage'), 'success');
		}
	}
	public function check_access_token(&$wxapp){
		if($wxapp['expirein']<TIMESTAMP){
			$component_access_token=$this->getComponentAccesstoken();
			$url='https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token='.$component_access_token;
			$post=array(
				'component_appid'=>$this->appid,
				'authorizer_appid'=>$wxapp['key'],
				'authorizer_refresh_token'=>$wxapp['refresh_token']
			);
			$post=json_encode($post,JSON_UNESCAPED_UNICODE);
			$result=ihttp_request($url,$post);
			$result=$result['content'];
			$result=json_decode($result,true);
			if(!$result['authorizer_access_token']){
				itoast('刷新access_token出错：'.$result['errmsg'], url('account/manage'), 'error');
			}
			$update=array(
				'access_token'=>$result['authorizer_access_token'],
				'expirein'=>TIMESTAMP+$result['expires_in']-200,
				'refresh_token'=>$result['authorizer_refresh_token']
			);
			pdo_update('account_wxapp',$update,array('key'=>$wxapp['key']));
			$wxapp['access_token']=$update['access_token'];
		}
	}

	public function release($appid){
		$wxapp=pdo_get('account_wxapp',array('key'=>$appid));
		if(!$wxapp['status']){
			itoast('请先上传审核小程序！', '', '');
		}elseif($wxapp['status']==1){
			itoast('小程序审核中，请审核成功后再发布！', '', '');
		}elseif($wxapp['status']==3){
			itoast('小程序审核失败，无法发布！请调整小程序设置！失败原因：'.$wxapp['fail_reason'], '', '');
		}elseif($wxapp['status']==4){
			itoast('小程序已发布成功！无需重复发布！', '', '');
		}
		if($wxapp['expirein']<TIMESTAMP){
			$component_access_token=$this->getComponentAccesstoken();
			$url='https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token='.$component_access_token;
			$post=array(
				'component_appid'=>$this->appid,
				'authorizer_appid'=>$wxapp['key'],
				'authorizer_refresh_token'=>$wxapp['refresh_token']
			);
			$post=json_encode($post,JSON_UNESCAPED_UNICODE);
			$result=ihttp_request($url,$post);
			$result=$result['content'];
			$result=json_decode($result,true);
			if(!$result['authorizer_access_token']){
				itoast('刷新access_token出错：'.$result['errmsg'], url('account/manage'), 'error');
			}
			$update=array(
				'access_token'=>$result['authorizer_access_token'],
				'expirein'=>TIMESTAMP+$result['expires_in']-200,
				'refresh_token'=>$result['authorizer_refresh_token']
			);
			pdo_update('account_wxapp',$update,array('key'=>$wxapp['key']));
			$wxapp['access_token']=$update['access_token'];
		}
		$url='https://api.weixin.qq.com/wxa/release?access_token='.$wxapp['access_token'];
		$post='{}';
		//$post=json_encode($post,JSON_UNESCAPED_UNICODE);
		$result=ihttp_request($url,$post);
		$result=$result['content'];
		$result=json_decode($result,true);
		if($result['errcode']){
			itoast('发布小程序出错：'.$result['errmsg'], url('account/manage'), 'error');
		}
		return true;
	}
}