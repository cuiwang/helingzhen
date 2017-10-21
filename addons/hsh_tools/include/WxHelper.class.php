<?php
require_once IA_ROOT.'/addons/hsh_tools/include/function.class.php';
load()->func('communication');
class WxHelper {
	var $appid;
	var $secret;
	var $wx_mp_api = array(
		'userinfo' => array(
			'url' => "https://api.weixin.qq.com/sns/userinfo?&lang=zh_CN",
			'args' => array('access_token', 'openid'),
		),
		'oauth2' => array(
			'url' => "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code",
			'args' => array('appid', 'secret', 'code'),
		),
		'userBaseinfo' => array(
			'url' => "https://api.weixin.qq.com/cgi-bin/user/info?lang=zh_CN",
			'args' => array('access_token', 'openid'),
		),
	);

	public function __construct() {
		global $_W;
		$toolsModule = pdo_fetch("SELECT * FROM ".tablename('uni_account_modules')." WHERE module = 'hsh_tools' and uniacid= :weid",array(":weid" => $_W['uniacid']));
		$toolsConfig = iunserializer($toolsModule['settings']);
		if($toolsConfig['appid'] != "" && $toolsConfig['secret'] !="") {
			$this->appid=$toolsConfig['appid'];
			$this->secret=$toolsConfig['secret'];
		} else {
			//die('appid与secret,数据错误！['.$_W['uniacid'].']');
		}
		
	}

	function sendTempletMSG($openID, $tplID, $data,$url ="") {
		global $_W;
		$messageTemplate = pdo_fetch("SELECT * FROM ".tablename('hsh_tools_tm')." WHERE id = :tplID OR template_id =:tplID",array(':tplID' => $tplID));
		if($messageTemplate)
		{
			$access_token = WeAccount::token(WeAccount::TYPE_WEIXIN);
			if($access_token =="") {
				die('access_token,获取失败！');
				return ;
			}
			$sendData = array(
				"touser" => $openID,
				"template_id" => $tplID,
				"url" => $messageTemplate['url'],
				"topcolor" => $messageTemplate['topcolor'],
			);
			if($url != "") {
				$sendData['url'] = $url;
			}
			$dataSetting=  json_decode(htmlspecialchars_decode($messageTemplate['data']),true);

			foreach($dataSetting as $key=>$val) {
				$sendData['data'] [$key]['value']=$data[$key];
				$sendData['data'] [$key]['color']=$val['color'];
			}
			$sendData = urlencodeForArray($sendData);
			$sendString = urldecode(json_encode($sendData));
			$sendURL = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $access_token;
			$returnData = ihttp_post($sendURL, $sendString);
			$returnData=$returnData['content'];
			/*发送记录存储*/
			$saveData=array();
			$saveData['weid']=$_W['uniacid'];
			$saveData['template_id']=$messageTemplate['id'];
			$saveData['send_time']=time();
			$saveData['send_data']=$sendString;
			$sendResult=  json_decode($returnData, true);
			if($sendResult['errcode'] == 0){
				$saveData['send_state']=1;
			} else {
				$saveData['send_state']=0;
				$saveData['error_data']=$returnData;
			}
			pdo_insert("hsh_tools_tm_log", $saveData, true);
			return $returnData;
		} else {
			return false;
		}
	}
	
	function wx_API($api_name, $args = array()) {
		$args_str = "";
		foreach ($this->wx_mp_api[$api_name]['args'] as $value) {
			if (isset($args[$value])) {
				$args_str.="&" . $value . "=" . $args[$value];
			}
		}
		$url = $this->wx_mp_api[$api_name]['url'] . $args_str;
		$returnInfo = ihttp_get($url);
		return $returnInfo['content'];
	}
	function getUserInfo($code)
	{
		$access_token_json=$this->getOpenID($code);
		$access_token=json_decode($access_token_json);
		
		if(isset($access_token->access_token)&&$access_token->scope=='snsapi_userinfo')
		{
			//$url="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token->access_token."&openid=".$access_token->openid."&lang=zh_CN";
			$args=array(
				'access_token'=>$access_token->access_token,
				'openid'=>$access_token->openid,
			);
			$userInfoType=1;
			return $this->wx_API('userinfo',$args);
		}
		else
		{
			$userInfoType=0;
			return $access_token_json;
		}
	}
	function getOpenID($code)
	{
		$args=array(
			'appid'=>$this->appid,
			'secret'=>$this->secret,
			'code'=>$code,
		);
		$access_token_json=$this->wx_API('oauth2',$args);
		return $access_token_json;
	}
}