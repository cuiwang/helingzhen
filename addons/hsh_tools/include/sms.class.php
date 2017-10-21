<?php

include_once("sdk/CCPRestSDK.php");

class SmsHelper {

	//主帐号
	var $accountSid = '';
	//主帐号Token
	var $accountToken = '';
	//应用Id
	var $appId = '';
	//请求地址，格式如下，不需要写https://
	var $serverIP = 'app.cloopen.com';
	//请求端口 
	var $serverPort = '8883';
	//REST版本号
	var $softVersion = '2013-12-26';
	
	var $configCheck = false;
	
	function __construct($accountSid = "", $accountToken = "", $appId = "") {
		global $_W;
		$toolsModule = pdo_fetch("SELECT * FROM " . tablename('uni_account_modules') . " WHERE module = 'hsh_tools' and uniacid= :weid", array(":weid" => $_W['uniacid']));
		$toolsConfig = iunserializer($toolsModule['settings']);
		if ($toolsConfig['smsAccountSid'] != "" && $toolsConfig['smsAuthToken'] != "" && $toolsConfig['smsAppId'] != "") {
			$this->accountSid = $toolsConfig['smsAccountSid'];
			$this->accountToken = $toolsConfig['smsAuthToken'];
			$this->appId = $toolsConfig['smsAppId'];
			$this->configCheck = true;
		} else {
			//die('短信平台配置数据错误！[' . $_W['uniacid'] . ']');
		}
	}

	public function sendTemplateSMS($to, $datas, $tempId) {
		$returnArray = array();
		if($this->configCheck){
			// 初始化REST SDK
			$rest = new REST($this->serverIP, $this->serverPort, $this->softVersion);
			$rest->setAccount($this->accountSid, $this->accountToken);
			$rest->setAppId($this->appId);
			// 发送模板短信

			$result = $rest->sendTemplateSMS($to, $datas, $tempId);
			if ($result == NULL) {
				$returnArray['state'] = 0;
				$returnArray['msg'] = "result error!";
				returnJSON($returnArray, "none", FALSE);
				return;
			}
			if ($result->statusCode != 0) {
				$returnArray['state'] = 0;
				$returnArray['msg'] = $result->statusMsg . "";
				$returnArray['error_code'] = $result->statusCode . "";
				//TODO 添加错误处理逻辑
			} else {
				// 获取返回信息
				$smsmessage = $result->TemplateSMS;
				$returnArray['state'] = 1;
				$returnArray['msg'] = "Sendind TemplateSMS success!";
				$returnArray['dateCreated'] = $smsmessage->dateCreated . "";
				$returnArray['smsMessageSid'] = $smsmessage->smsMessageSid . "";
				//TODO 添加成功处理逻辑
			}
		} else {
			$returnArray['state'] = 0;
			$returnArray['msg'] = "config error!";
		}
                
		return returnJSON($returnArray, "none", FALSE);
	}
}
