<?php

class RedirectUrl {

	var $WxHelper;
	var $gourl;
	var $backurl;
	var $targetURL;
	var $redirectInfo;
	var $package;
	var $tableName = 'hsh_tools_url_redirect';
	var $redirectID;

	public function __construct() {
		global $_W, $_GPC;
		$redirectID = !empty($_GPC['rid']) ? $_GPC['rid'] : '-1';
		$this->redirectID = $redirectID;
		$package = json_decode(stripslashes(htmlspecialchars_decode($_GPC["package"])), true); //获取附加package包(JSON格式)
		$this->package = $package;
		if ($redirectID != "") {
			$this->redirectInfo = pdo_fetch("SELECT * FROM " . tablename($this->tableName) . " WHERE id= :rid and state = 1", array(':rid' => $redirectID));
			if (!empty($this->redirectInfo)) {
				$this->gourl = $this->redirectInfo['go_url'];
				$this->backurl = $this->redirectInfo['back_url'];
			}
		}
		if ($this->backurl == "" || $this->backurl == DEFAULT_GO_URL) {
			$this->backurl = NOT_SUBSCRIBE_BACK_URL;
		}
		if(empty($_W['uniacid'])) {
			if($_GPC['i'] != ''){
				$_W['uniacid'] = $_GPC['i'];
			} else {
				$_W['uniacid'] = $this->redirectInfo['weid'];
			}
		}
		$this->WxHelper = new WxHelper(); //先调整参数.最后在初始化微信处理类
	}

	function Index() {
		global $_W, $_GPC;
		if(!empty($this->redirectInfo)) {
			pdo_update($this->tableName, array('count' => $this->redirectInfo['count'] + 1), $this->redirectInfo);
		}
		$code = !empty($_GPC['code']) ? $_GPC['code'] : '';
		$state = !empty($_GPC['state']) ? $_GPC['state'] : '';
		$getType = $this->redirectInfo['redirect_type'];
		$testMode = $this->redirectInfo["test_mode"];
		switch ($getType) {
			case "redirect":
				$this->redirect();
				break;
			case "getOpenID":
				$this->getOpenID($code);
				break;
			case "checkSubscribe":
				$this->checkSubscribe($code);
				break;
			case "transpondCode":
				$this->transpondCode($code);
				break;
			default :
				$this->redirect();
				break;
		}
		$urlArgs = array();
		if ($this->redirectID != "" && $getType != 'redirect') {
			$urlArgs['rid'] = $this->redirectID;
		}
		if ($state != "") {
			$urlArgs['state'] = $state;
		}
		$this->targetURL = $this->createURL($this->targetURL, $urlArgs);
		$this->targetURL = $this->createURL($this->targetURL, $this->package['args']);
		if ($testMode == "1") {
			die($this->targetURL);
		}
		if ($this->targetURL != "") {
			header("Location:" . $this->targetURL);
		}
	}

	/* 获取授权连接 
	 * $targetUrl  接口跳转地址
	 * $type 调用接口类型
	 * $state 接口携带参数
	 */

	public function getOAuthUrl($targetUrl, $type = "snsapi_base", $state = 1) {
		if ($type != "snsapi_base" && $type != "") {
			$type = "snsapi_userinfo";
		} else {
			$type = "snsapi_base";
		}
		$returnUrl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->WxHelper->appid . "&redirect_uri=" . urlencode($targetUrl) . "&response_type=code&scope=" . $type . "&state=" . $state . "#wechat_redirect";
		return $returnUrl;
	}

	/* 直接转发从微信处获取的CODE */

	private function redirect() {
		$this->targetURL = $this->createURL($this->gourl, array());
		return;
	}

	/* 直接转发从微信处获取的CODE */

	private function transpondCode($code) {
		$this->targetURL = $this->createURL($this->gourl, array("code" => $code));
		return;
	}

	/* 获取用户OpenID，将次OpenID添加至gourl，并跳转 */

	private function getOpenID($code) {
		$paramName = "openid";
		if ($this->package['ParamName'] != "") {
			$paramName = $this->redirectInfo['param_name'];
		}
		$returnSTR = $this->WxHelper->getUserInfo($code);
		$UserInfoArray = json_decode($returnSTR, true);
		if (!empty($UserInfoArray) && isset($UserInfoArray['openid'])) {
			$this->targetURL = $this->createURL($this->gourl, array($paramName => $UserInfoArray['openid']));
			return;
		}
		if ($this->package['show_error'] == "1") {
			echo $returnSTR;
			return;
		}
		$this->targetURL = $this->gourl;
		return;
	}

	/* 判断用户是否关注（分支， gourl-已关注跳转链接  back-未关注跳转链接） */

	private function checkSubscribe($code) {
		$paramName = "openid";
		if ($this->package['ParamName'] != "") {
			$paramName = $this->package['ParamName'];
		}
		if ($code == "") {
			$this->targetURL = $this->backurl;
			return;
		}
		$returnSTR = $this->WxHelper->getUserInfo($code);
		$UserInfoArray = json_decode($returnSTR, true);
		if (empty($UserInfoArray) || !isset($UserInfoArray['openid'])) {
			return;
		}
		$userinfo = $this->getUserInfo($UserInfoArray['openid']);
		if ($userinfo['subscribe'] == 1) {
			$this->targetURL = $this->createURL($this->gourl, array($paramName => $UserInfoArray['openid']));
		} else {
			$this->targetURL = $this->backurl;
		}
		return $this->targetURL; //返回带openid的链接
	}

	/* 通过传递OAuth获取用户信息 */

	private function getUserInfo($openid) {
		$get_access_token_args = array(
			'appid' => $this->package['appid'] != "" ? $this->package['appid'] : $this->WxHelper->appid,
			'secret' => $this->package['appsecret'] != "" ? $this->package['appsecret'] : $this->WxHelper->secret,
		);
		$access_token_json = $this->WxHelper->wx_API("client_credential", $get_access_token_args);
		$access_token = json_decode($access_token_json)->access_token;
		$get_user_info_args = array(
			'access_token' => $access_token,
			'openid' => $openid,
		);
		$userinfo_json = $this->WxHelper->wx_API('userBaseinfo', $get_user_info_args);
		$userinfo = json_decode($userinfo_json, true);
		return $userinfo;
	}

	/* 以下是通用函数 */

	private function createURL($url, $args) {
		if ($url == "") {
			$url = DEFAULT_GO_URL;
		}
		if (is_array($args)) {
			foreach ($args as $key => $val) {
				if (strstr($url, "?")) {
					$url.="&" . $key . "=" . $val;
				} else {
					$url.="?" . $key . "=" . $val;
				}
			}
		}
		return $url;
	}

}
