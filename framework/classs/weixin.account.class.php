<?php
/**
 * [WEIZAN System] Copyright (c) 2014 012WZ.COM
 * WEIZAN is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

class WeiXinAccount extends WeAccount {
	protected $account = null;
	
	
	public $apis = array();
	public $types = array(
		'view', 'click', 'scancode_push',
		'scancode_waitmsg', 'pic_sysphoto', 'pic_photo_or_album',
		'pic_weixin', 'location_select'
	);
	
	public function __construct($account = array()) {
		if (empty($account)) {
			return true;
		}
		global $_W;
		$this->account = $account;
		if(empty($this->account['acid'])) {
			trigger_error('error uniAccount id, can not construct ' . __CLASS__, E_USER_WARNING);
		}
		$this->apis = array(
			'barcode' => array(
				'post' => 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s',
				'display' => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=%s',
			)
		);
	}
	
	
	public function checkSign() {
		$token = $this->account['token'];
		$signkey = array($token, $_GET['timestamp'], $_GET['nonce']);
		sort($signkey, SORT_STRING);
		$signString = implode($signkey);
		$signString = sha1($signString);
		return $signString == $_GET['signature'];
	}

	
	public function checkSignature($encrypt_msg) {
		$str = $this->buildSignature($encrypt_msg);
		return $str == $_GET['msg_signature'];
	}

	public function local_checkSignature($packet) {
		$token = $this->account['token'];
		$array = array($packet['Encrypt'], $token, $packet['TimeStamp'], $packet['Nonce']);
		sort($array, SORT_STRING);
		$str = implode($array);
		$str = sha1($str);
		return $str == $packet['MsgSignature'];
	}

	
	public function buildSignature($encrypt_msg) {
		$token = $this->account['token'];
		$array = array($encrypt_msg, $token, $_GET['timestamp'], $_GET['nonce']);
		sort($array, SORT_STRING);
		$str = implode($array);
		$str = sha1($str);
		return $str;
	}

	
	public function encryptMsg($text) {
		$token = $this->account['token'];
		$encodingaeskey = $this->account['encodingaeskey'];
		$appid = $this->account['key'];

		$key = base64_decode($encodingaeskey . '=');
		$text = random(16) . pack("N", strlen($text)) . $text . $appid;
		$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		$iv = substr($key, 0, 16);
		$block_size = 32;
		$text_length = strlen($text);
				$amount_to_pad = $block_size - ($text_length % $block_size);
		if ($amount_to_pad == 0) {
			$amount_to_pad = $block_size;
		}
				$pad_chr = chr($amount_to_pad);
		$tmp = '';
		for ($index = 0; $index < $amount_to_pad; $index++) {
			$tmp .= $pad_chr;
		}
		$text = $text . $tmp;
		mcrypt_generic_init($module, $key, $iv);
				$encrypted = mcrypt_generic($module, $text);
		mcrypt_generic_deinit($module);
		mcrypt_module_close($module);
				$encrypt_msg = base64_encode($encrypted);
				$signature = $this->buildSignature($encrypt_msg);
		return array($signature, $encrypt_msg);
	}

	
	function xmlDetract($data) {
				$xml['Encrypt'] = $data[1];
		$xml['MsgSignature'] = $data[0];
		$xml['TimeStamp'] = $_GET['timestamp'];
		$xml['Nonce'] = $_GET['nonce'];
		return array2xml($xml);
	}
	
	
	public function decryptMsg($postData) {
		$token = $this->account['token'];
		$encodingaeskey = $this->account['encodingaeskey'];
		$appid = $this->account['key'];
		$key = base64_decode($encodingaeskey . '=');

		if(strlen($encodingaeskey) != 43) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40004 \n,错误描述为: " . $this->encrypt_error_code('40004'));
		}
				$packet = $this->xmlExtract($postData);
		if(is_error($packet)) {
			return error(-1, $packet['message']);
		}
				$istrue = $this->checkSignature($packet['encrypt']);
		if(!$istrue) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40001 \n,错误描述为: " . $this->encrypt_error_code('40001'));
		}
				$ciphertext_dec = base64_decode($packet['encrypt']);
		$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		$iv = substr($key, 0, 16);
		mcrypt_generic_init($module, $key, $iv);
		$decrypted = mdecrypt_generic($module, $ciphertext_dec);
		mcrypt_generic_deinit($module);
		mcrypt_module_close($module);
		$block_size = 32;

		$pad = ord(substr($decrypted, -1));
		if ($pad < 1 || $pad > 32) {
			$pad = 0;
		}
		$result = substr($decrypted, 0, (strlen($decrypted) - $pad));
		if (strlen($result) < 16) {
			return '';
		}
		$content = substr($result, 16, strlen($result));
		$len_list = unpack("N", substr($content, 0, 4));
		$xml_len = $len_list[1];
		$xml_content = substr($content, 4, $xml_len);
		$from_appid = substr($content, $xml_len + 4);
		if ($from_appid != $appid) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40005 \n,错误描述为: " . $this->encrypt_error_code('40005'));
		}
		return $xml_content;
	}
	
	public function local_decryptMsg($postData) {
		$token = $this->account['token'];
		$encodingaeskey = $this->account['encodingaeskey'];
		$appid = $this->account['key'];

		if(strlen($encodingaeskey) != 43) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40004 \n,错误描述为: " . $this->encrypt_error_code('40004'));
		}
		$key = base64_decode($encodingaeskey . '=');
				$packet = $this->local_xmlExtract($postData);
		if(is_error($packet)) {
			return error(-1, $packet['message']);
		}
				$istrue = $this->local_checkSignature($packet);
		if(!$istrue) {
			 return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40001 \n,错误描述为: " . $this->encrypt_error_code('40001'));
		}
				$ciphertext_dec = base64_decode($packet['Encrypt']);
		$module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		$iv = substr($key, 0, 16);
		mcrypt_generic_init($module, $key, $iv);
		$decrypted = mdecrypt_generic($module, $ciphertext_dec);
		mcrypt_generic_deinit($module);
		mcrypt_module_close($module);
		$block_size = 32;

		$pad = ord(substr($decrypted, -1));
		if ($pad < 1 || $pad > 32) {
			$pad = 0;
		}
		$result = substr($decrypted, 0, (strlen($decrypted) - $pad));
		if (strlen($result) < 16) {
			return '';
		}
		$content = substr($result, 16, strlen($result));
		$len_list = unpack("N", substr($content, 0, 4));
		$xml_len = $len_list[1];
		$xml_content = substr($content, 4, $xml_len);
		$from_appid = substr($content, $xml_len + 4);
		if ($from_appid != $appid) {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40005 \n,错误描述为: " . $this->encrypt_error_code('40005'));
		}
		return $xml_content;
	}

	
	public function xmlExtract($message) {
		$packet = array();
		if (!empty($message)){
			$obj = simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
			if($obj instanceof SimpleXMLElement) {
				$packet['encrypt'] = strval($obj->Encrypt);
				$packet['to'] = strval($obj->ToUserName);
			}
		}
		if(!empty($packet['encrypt'])) {
			return $packet;
		} else {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40002 \n,错误描述为: " . $this->encrypt_error_code('40002'));
		}
	}

	public function local_xmlExtract($message) {
		$packet = array();
		if (!empty($message)){
			$obj = simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);
			if($obj instanceof SimpleXMLElement) {
				$packet['Encrypt'] = strval($obj->Encrypt);
				$packet['MsgSignature'] = strval($obj->MsgSignature);
				$packet['TimeStamp'] = strval($obj->TimeStamp);
				$packet['Nonce'] = strval($obj->Nonce);
			}
		}
		if(!empty($packet)) {
			return $packet;
		} else {
			return error(-1, "微信公众平台返回接口错误. \n错误代码为: 40002 \n,错误描述为: " . $this->encrypt_error_code('40002'));
		}
	}

	
	public function fetchAccountInfo() {
		return $this->account;
	}
	
	public function queryAvailableMessages() {
		$messages = array('text', 'image', 'voice', 'video', 'location', 'link', 'subscribe', 'unsubscribe');
		
		if(!empty($this->account['key']) && !empty($this->account['secret'])) {
			$level = intval($this->account['level']);
			if($level > 1){
				$messages[] = 'click';
				$messages[] = 'view';
			}
			if ($level > 2) {
				$messages[] = 'qr';
				$messages[] = 'trace';
			}
		}
		return $messages;
	}
	
	public function queryAvailablePackets() {
		$packets = array('text', 'music', 'news');
		if(!empty($this->account['key']) && !empty($this->account['secret'])) {
			if (intval($this->account['level']) > 1) {
				$packets[] = 'image';
				$packets[] = 'voice';
				$packets[] = 'video';
			}
		}
		return $packets;
	}

	
	public function isMenuSupported() {
		return 	!empty($this->account['key']) && 
				!empty($this->account['secret']) && 
				(intval($this->account['level']) > 1);
	}

	private function menuResponseParse($content) {
		if(!is_array($content)) {
			return error(-1, '接口调用失败，请重试！' . (is_string($content) ? "微信公众平台返回元数据: {$content}" : ''));
		}
		$dat = $content['content'];
		$result = @json_decode($dat, true);
		if(is_array($result) && $result['errcode'] == '0') {
			return true;
		} else {
			if(is_array($result)) {
				return error(-1, "微信公众平台返回接口错误. \n错误代码为: {$result['errcode']} \n错误信息为: {$result['errmsg']} \n错误描述为: " . $this->error_code($result['errcode']));
			} else {
				return error(-1, '微信公众平台未知错误');
			}
		}
	}
	
	private function menuBuildMenuSet($menu) {
		$set = array();
		$set['button'] = array();
		foreach($menu as $m) {
			$entry = array();
			$entry['name'] = urlencode($m['title']);
			if(!empty($m['subMenus'])) {
				$entry['sub_button'] = array();
				foreach($m['subMenus'] as $s) {
					$e = array();
					if ($s['type'] == 'url') {
						$e['type'] = 'view';
					} elseif (in_array($s['type'], $this->types)) {
						$e['type'] = $s['type'];
					} else {
						$e['type'] = 'click';
					}
					$e['name'] = urlencode($s['title']);
					if($e['type'] == 'view') {
						$e['url'] = urlencode($s['url']);
					} else {
						$e['key'] = urlencode($s['forward']);
					}
					$entry['sub_button'][] = $e;
				}
			} else {
				if ($m['type'] == 'url') {
					$entry['type'] = 'view';
				} elseif (in_array($m['type'], $this->types)) {
					$entry['type'] = $m['type'];
				} else {
					$entry['type'] = 'click';
				}
				if($entry['type'] == 'view') {
					$entry['url'] = urlencode($m['url']);
				} else {
					$entry['key'] = urlencode($m['forward']);
				}
			}
			$set['button'][] = $entry;
		}
		$dat = json_encode($set);
		$dat = urldecode($dat);
		return $dat;
	}
	
	public function menuCreate($menu) {
		$dat = $this->menuBuildMenuSet($menu);
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";
		$content = ihttp_post($url, $dat);
		return $this->menuResponseParse($content);
	}

	public function menuDelete() {
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$token}";
		$content = ihttp_get($url);
		return $this->menuResponseParse($content);
	}

	public function menuModify($menu) {
		return $this->menuCreate($menu);
	}

	public function menuQuery() {
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$token}";
		$content = ihttp_get($url);
		if(!is_array($content)) {
			return error(-1, '接口调用失败，请重试！' . (is_string($content) ? "微信公众平台返回元数据: {$content}" : ''));
		}
		$dat = $content['content'];
		$result = json_decode($dat, true);
		if(is_array($result) && !empty($result['menu'])) {
			$menus = array();
			foreach($result['menu']['button'] as $val) {
				$m = array();
				$m['type'] = in_array($val['type'], $this->types) ? $val['type'] : 'url';
				$m['title'] = $val['name'];
				if($m['type'] != 'view') {
					$m['forward'] = $val['key'];
				} else {
					$m['type'] = 'url';
					$m['url'] = $val['url'];
				}
				$m['subMenus'] = array();
				if(!empty($val['sub_button'])) {
					foreach($val['sub_button'] as $v) {
						$s = array();
						$s['type'] = in_array($v['type'], $this->types) ? $v['type'] : 'url';
						$s['title'] = $v['name'];
						if($s['type'] != 'view') {
							$s['forward'] = $v['key'];
						} else {
							$s['type'] = 'url';
							$s['url'] = $v['url'];
						}
						$m['subMenus'][] = $s;
					}
				}
				$menus[] = $m;
			}
			return $menus;
		} else {
			if(is_array($result)) {
				if($result['errcode'] == '46003') {
					return array();
				}
				return error($result['errcode'], "微信公众平台返回接口错误. \n错误代码为: {$result['errcode']} \n错误信息为: {$result['errmsg']} \n错误描述为: " . $this->error_code($result['errcode']));
			} else {
				return array();
			}
		}
	}
	
	public function fansQueryInfo($uniid, $isOpen = true) {
		if($isOpen) {
			$openid = $uniid;
		} else {
			exit('error');
		}
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token={$token}&openid={$openid}&lang=zh_CN";
		$response = ihttp_get($url);
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}
	
	public function fansAll() {
		global $_GPC;
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $token;
		if(!empty($_GPC['next_openid'])) {
			$url .= '&next_openid=' . $_GPC['next_openid'];
		}
		$response = ihttp_get($url);
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问公众平台接口失败, 错误: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
		}
		$return = array();
		$return['total'] = $result['total'];
		$return['fans'] = $result['data']['openid'];
		$return['next'] = $result['next_openid'];
		return $return;
	}

	public function queryBarCodeActions() {
		return array('barCodeCreateDisposable', 'barCodeCreateFixed');
	}

	public function barCodeCreateDisposable($barcode) {
		$barcode['expire_seconds'] = empty($barcode['expire_seconds']) ? 604800 : $barcode['expire_seconds'];
		if (empty($barcode['action_info']['scene']['scene_id']) || empty($barcode['action_name'])) {
			return error('1', 'Invalid params');
		}
		$token = $this->fetch_token();
		$url = sprintf($this->apis['barcode']['post'], $token);
		$response = ihttp_request($url, json_encode($barcode));
		if (is_error($response)) {
			return $response;
		}
		$content = @json_decode($response['content'], true);
		if(empty($content)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		}
		if (!empty($content['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$content['errcode']}, 错误信息: {$content['errmsg']},错误详情：{$this->error_code($content['errcode'])}");
		}
		return $content;
	}
	
	public function barCodeCreateFixed($barcode) {
		unset($barcode['expire_seconds']);
		if (empty($barcode['action_info']['scene']['scene_id']) || empty($barcode['action_name'])) {
			return error('1', 'Invalid params');
		}
		$token = $this->fetch_token();
		$url = sprintf($this->apis['barcode']['post'], $token);
		$response = ihttp_request($url, json_encode($barcode));
		if (is_error($response)) {
			return $response;
		}
		$content = @json_decode($response['content'], true);
		if(empty($content)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		}
		if(!empty($content['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$content['errcode']}, 错误信息: {$content['errmsg']},错误详情：{$this->error_code($content['errcode'])}");
		}
		return $content;
	}
		private function encrypt_error_code($code) {
		$errors = array(
			'40001' => '签名验证错误',
			'40002' => 'xml解析失败',
			'40003' => 'sha加密生成签名失败',
			'40004' => 'encodingAesKey 非法',
			'40005' => 'appid 校验错误',
			'40006' => 'aes 加密失败',
			'40007' => 'aes 解密失败',
			'40008' => '解密后得到的buffer非法',
			'40009' => 'base64加密失败',
			'40010' => 'base64解密失败',
			'40011' => '生成xml失败',
		);
		if($errors[$code]) {
			return $errors[$code];
		} else {
			return '未知错误';
		}
	}

	public function error_code($code) {
		$errors = array(
			'-1' => '系统繁忙',
			'0' => '请求成功',
			'40001' => '获取access_token时AppSecret错误，或者access_token无效',
			'40002' => '不合法的凭证类型',
			'40003' => '不合法的OpenID',
			'40004' => '不合法的媒体文件类型',
			'40005' => '不合法的文件类型',
			'40006' => '不合法的文件大小',
			'40007' => '不合法的媒体文件id',
			'40008' => '不合法的消息类型',
			'40009' => '不合法的图片文件大小',
			'40010' => '不合法的语音文件大小',
			'40011' => '不合法的视频文件大小',
			'40012' => '不合法的缩略图文件大小',
			'40013' => '不合法的APPID',
			'40014' => '不合法的access_token',
			'40015' => '不合法的菜单类型',
			'40016' => '不合法的按钮个数',
			'40017' => '不合法的按钮个数',
			'40018' => '不合法的按钮名字长度',
			'40019' => '不合法的按钮KEY长度',
			'40020' => '不合法的按钮URL长度',
			'40021' => '不合法的菜单版本号',
			'40022' => '不合法的子菜单级数',
			'40023' => '不合法的子菜单按钮个数',
			'40024' => '不合法的子菜单按钮类型',
			'40025' => '不合法的子菜单按钮名字长度',
			'40026' => '不合法的子菜单按钮KEY长度',
			'40027' => '不合法的子菜单按钮URL长度',
			'40028' => '不合法的自定义菜单使用用户',
			'40029' => '不合法的oauth_code',
			'40030' => '不合法的refresh_token',
			'40031' => '不合法的openid列表',
			'40032' => '不合法的openid列表长度',
			'40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
			'40035' => '不合法的参数',
			'40038' => '不合法的请求格式',
			'40039' => '不合法的URL长度',
			'40050' => '不合法的分组id',
			'40051' => '分组名字不合法',
			'41001' => '缺少access_token参数',
			'41002' => '缺少appid参数',
			'41003' => '缺少refresh_token参数',
			'41004' => '缺少secret参数',
			'41005' => '缺少多媒体文件数据',
			'41006' => '缺少media_id参数',
			'41007' => '缺少子菜单数据',
			'41008' => '缺少oauth code',
			'41009' => '缺少openid',
			'42001' => 'access_token超时',
			'42002' => 'refresh_token超时',
			'42003' => 'oauth_code超时',
			'43001' => '需要GET请求',
			'43002' => '需要POST请求',
			'43003' => '需要HTTPS请求',
			'43004' => '需要接收者关注',
			'43005' => '需要好友关系',
			'44001' => '多媒体文件为空',
			'44002' => 'POST的数据包为空',
			'44003' => '图文消息内容为空',
			'44004' => '文本消息内容为空',
			'45001' => '多媒体文件大小超过限制',
			'45002' => '消息内容超过限制',
			'45003' => '标题字段超过限制',
			'45004' => '描述字段超过限制',
			'45005' => '链接字段超过限制',
			'45006' => '图片链接字段超过限制',
			'45007' => '语音播放时间超过限制',
			'45008' => '图文消息超过限制',
			'45009' => '接口调用超过限制',
			'45010' => '创建菜单个数超过限制',
			'45015' => '回复时间超过限制',
			'45016' => '系统分组，不允许修改',
			'45017' => '分组名字过长',
			'45018' => '分组数量超过上限',
			'46001' => '不存在媒体数据',
			'46002' => '不存在的菜单版本',
			'46003' => '不存在的菜单数据',
			'46004' => '不存在的用户',
			'47001' => '解析JSON/XML内容错误',
			'48001' => 'api功能未授权',
			'50001' => '用户未授权该api',
			'40070' => '基本信息baseinfo中填写的库存信息SKU不合法。',
			'41011' => '必填字段不完整或不合法，参考相应接口。',
			'40056' => '无效code，请确认code长度在20个字符以内，且处于非异常状态（转赠、删除）。',
			'43009' => '无自定义SN权限，请参考开发者必读中的流程开通权限。',
			'43010' => '无储值权限,请参考开发者必读中的流程开通权限。',
			'43011' => '无积分权限,请参考开发者必读中的流程开通权限。',
			'40078' => '无效卡券，未通过审核，已被置为失效。',
			'40079' => '基本信息base_info中填写的date_info不合法或核销卡券未到生效时间。',
			'45021' => '文本字段超过长度限制，请参考相应字段说明。',
			'40080' => '卡券扩展信息cardext不合法。',
			'40097' => '基本信息base_info中填写的url_name_type或promotion_url_name_type不合法。',
			'49004' => '签名错误。',
			'43012' => '无自定义cell跳转外链权限，请参考开发者必读中的申请流程开通权限。',
			'40099' => '该code已被核销。'
		);
		$code = strval($code);
		if($code == '40001' || $code == '42001') {
			$rec = array();
			$rec['access_token'] = '';
			pdo_update('account_wechats', $rec, array('acid' => $this->account['acid']));
			return '微信公众平台授权异常, 系统已修复这个错误, 请刷新页面重试.';
		}
		if($errors[$code]) {
			return $errors[$code];
		} else {
			return '未知错误';
		}
	}
	
	public function changeSend($send) {
		if (empty($send)) {
			return error(-1, 'Invalid params');
		}
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$sendapi = 'https://api.weixin.qq.com/pay/delivernotify?access_token='.$token;
		$response = ihttp_request($sendapi, json_encode($send));
		$response = json_decode($response['content'], true);
		if (empty($response)) {
			return error(-1, '发货失败，请检查您的公众号权限或是公众号AppId和公众号AppSecret！');
		}
		if (!empty($response['errcode'])) {
			return error(-1, $response['errmsg']);
		}
		return true;
	}

	public function fetch_token() {
		load()->func('communication');
		if(!empty($this->account['access_token'])
		&& is_array($this->account['access_token']) 
		&& !empty($this->account['access_token']['token']) 
		&& !empty($this->account['access_token']['expire']) 
		&& $this->account['access_token']['expire'] > TIMESTAMP) {
			return $this->account['access_token']['token'];
		}
		if (empty($this->account['key']) || empty($this->account['secret'])) {
			return error('-1', '未填写公众号的 appid 或 appsecret！');
		}
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->account['key']}&secret={$this->account['secret']}";
		$content = ihttp_get($url);
		if(is_error($content)) {
			message('获取微信公众号授权失败, 请稍后重试！错误详情: ' . $content['message']);
		}
		$token = @json_decode($content['content'], true);
		if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
			$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
			$errorinfo = @json_decode($errorinfo, true);
			message('获取微信公众号授权失败, 请稍后重试！ 公众平台返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg']);
		}
		$record = array();
		$record['token'] = $token['access_token'];
		$record['expire'] = TIMESTAMP + $token['expires_in'] - 200;
		$row = array();
		$row['access_token'] = iserializer($record);
		pdo_update('account_wechats', $row, array('acid' => $this->account['acid']));
		
		$this->account['access_token'] = $record;
		return $record['token'];
	}

	public function fetch_card_ticket(){
		if(!empty($this->account['card_ticket'])
			&& is_array($this->account['card_ticket'])
			&& !empty($this->account['card_ticket']['ticket'])
			&& !empty($this->account['card_ticket']['expire'])
			&& $this->account['card_ticket']['expire'] > TIMESTAMP) {
			return $this->account['card_ticket']['ticket'];
		}

		load()->func('communication');
		$access_token = $this->fetch_token();
		if(is_error($access_token)){
			return $access_token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
		$content = ihttp_get($url);
		if(is_error($content)) {
			return error(-1, '调用接口获取微信公众号 card_ticket 失败, 错误信息: ' . $content['message']);
		}
		$result = @json_decode($content['content'], true);
		if(empty($result) || intval(($result['errcode'])) != 0 || $result['errmsg'] != 'ok') {
			return error(-1, '获取微信公众号 card_ticket 结果错误, 错误信息: ' . $result['errmsg']);
		}

		$record = array();
		$record['ticket'] = $result['ticket'];
		$record['expire'] = TIMESTAMP + $result['expires_in'] - 200;
		$row = array();
		$row['card_ticket'] = iserializer($record);
		pdo_update('account_wechats', $row, array('acid' => $this->account['acid']));

		$this->account['card_ticket'] = $record;
		return $record['ticket'];
	}
	
	public function fetch_available_token() {
				$accounts = pdo_fetchall("SELECT `key`, `secret`, `acid`, `access_token` FROM ".tablename('account_wechats')." WHERE uniacid = :uniacid ORDER BY `level` DESC ", array(':uniacid' => $GLOBALS['_W']['uniacid']));
		if (empty($accounts)) {
			return error(-1, 'no permission');
		}
		foreach ($accounts as $account) {
			if (empty($account['key']) || empty($account['secret'])) {
				continue;
			}
			if (!empty($account['access_token'])) {
				$account['access_token'] = unserialize($account['access_token']);
				if ($account['access_token']['expire'] > TIMESTAMP) {
					$token = $account['access_token']['token'];
					break;
				}
			}
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$account['key']}&secret={$account['secret']}";
			load()->func('communication');
			$content = ihttp_get($url);
			if(is_error($content)) {
				$error = '获取微信公众号授权失败, 请稍后重试！错误详情: ' . $content['message'];
				continue;
			}
			$token = @json_decode($content['content'], true);
			if(empty($token) || !is_array($token) || empty($token['access_token']) || empty($token['expires_in'])) {
				$errorinfo = substr($content['meta'], strpos($content['meta'], '{'));
				$errorinfo = @json_decode($errorinfo, true);
				$error = '获取微信公众号授权失败, 请稍后重试！ 公众平台返回原始数据为: 错误代码-' . $errorinfo['errcode'] . '，错误信息-' . $errorinfo['errmsg'];
				continue;
			}
			$record = array();
			$record['token'] = $token['access_token'];
			$record['expire'] = TIMESTAMP + $token['expires_in'] - 200;
			$row = array();
			$row['access_token'] = iserializer($record);
			pdo_update('account_wechats', $row, array('acid' => $account['acid']));
			$token = $token['access_token'];
			break;
		}
		if (empty($token)) {
			return error(-1, $error);
		}
		return $token;
	}
	
	
	public function long2short($longurl) {
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$token}";
		$send = array();
		$send['action'] = 'long2short';
		$send['long_url'] = $longurl;
		$response = ihttp_request($url, json_encode($send));
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}
	
	
	public function downloadMedia($media) {
		
		$mediatypes = array('image', 'voice', 'thumb');
		if (empty($media) || empty($media['media_id']) || (!empty($media['type']) && !in_array($media['type'], $mediatypes))) {
			return error(-1, '微信下载媒体资源参数错误');
		}
		
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$sendapi = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$token}&media_id={$media['media_id']}";
		$response = ihttp_get($sendapi);
		if(!empty($response['headers']['Content-disposition']) && strexists($response['headers']['Content-disposition'], $media['media_id'])){
			global $_W;
			$filename =str_replace( array('attachment; filename=', '"',' '),'',$response['headers']['Content-disposition']);
			$filename = 'images/'.$_W['uniacid'].'/'.date('Y/m/').$filename;
			load()->func('file');
			file_write($filename, $response['content']);
			return $filename;
		} else {
			$response = json_decode($response['content'], true);
			return error($response['errcode'], $response['errmsg']);
		}
	}

	
	public function fetchChatLog($params = array()) {
		if(empty($params['starttime']) || empty($params['endtime'])) {
			return error(-1, '没有要查询的时间段');
		}
		$starttmp = date('Y-m-d', $params['starttime']);
		$endtmp = date('Y-m-d', $params['endtime']);
		if($starttmp != $endtmp) {
			return error(-1, '时间范围有误，微信公众平台不支持跨日查询');
		}
		if(empty($params['openid'])) {
			return error(-1, '没有要查询的openid');
		}
		if(empty($params['pagesize'])) {
			$params['pagesize'] = 1000;
		}
		if(empty($params['pageindex'])) {
			$params['pageindex'] = 1;
		}
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/customservice/getrecord?access_token={$token}";
		$response = ihttp_request($url, json_encode($params));
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}

	
	function fetchFansGroups() {
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token={$token}";
		$response = ihttp_request($url, json_encode($params));
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}

	
	function editFansGroupname($params = array()) {
		if(in_array($params['id'], array(0, 1, 2))) {
						return '';
		}
		if(empty($params['id']) || empty($params['name'])) {
			return error(-1, '分组信息错误');
		}

		$data = '{"group": {"id": ' . $params['id'] . ', "name": "' . $params['name'] . '"}}';
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/groups/update?access_token={$token}";
		$response = ihttp_request($url, $data);
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}, 错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}

	
	function addFansGroup($name) {
		if(empty($name)) {
			return error(-1, '请填写分组名称');
		}
		$data = '{"group": {"name": "' . $name . '"}}';
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/groups/create?access_token={$token}";
		$response = ihttp_request($url, $data);
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}, 错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}

	
	function fetchFansGroupid($openid) {
		if(empty($openid)) {
			return error(-1, '没有填写openid');
		}
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token={$token}";
		$response = ihttp_request($url, json_encode(array('openid' => $openid)));
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}, 错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}

	
	function updateFansGroupid($openid, $groupid) {
		if(empty($openid)) {
			return error(-1, '没有填写openid');
		}
		$data = array('openid' => $openid, 'to_groupid' => intval($groupid));
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$token}";
		$response = ihttp_request($url, json_encode($data));
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}, 错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}

	
	function sendCustomNotice($data) {
		if(empty($data)) {
			return error(-1, '参数错误');
		}
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}";
		$response = ihttp_request($url, urldecode(json_encode($data)));
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}
	
	
	function uploadVideo($data) {
		if(empty($data)) {
			return error(-1, '参数错误');
		}
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://file.api.weixin.qq.com/cgi-bin/media/uploadvideo?access_token={$token}";
		$response = ihttp_request($url, urldecode(json_encode($data)));
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']}, 错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}

	
	function uploadNews($data) {
		if(empty($data)) {
			return error(-1, '参数错误');
		}
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token={$token}";
		$response = ihttp_request($url, urldecode(json_encode($data)));
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}

	
	function fansSendAll($data) {
		if(empty($data)) {
			return error(-1, '参数错误');
		}
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$data = urldecode(json_encode($data));
		$url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$token}";
		$response = ihttp_request($url, $data);
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
		}
		return $result;
	}

	
	public function changeOrderStatus($send) {
		if (empty($send)) {
			return error(-1, '参数错误');
		}
		$token = $this->fetch_token();
		if(is_error($token)){
			return $token;
		}
		$sendapi = 'https://api.weixin.qq.com/pay/delivernotify?access_token=' . $token;
		$response = ihttp_request($sendapi, json_encode($send));
		$response = json_decode($response['content'], true);
		if (empty($response)) {
			return error(-1, '发货失败，请检查您的公众号权限或是公众号AppId和公众号AppSecret！');
		}
		if (!empty($response['errcode'])) {
			return error(-1, $response['errmsg']);
		}
		return $response;
	}

	
	public function sendTplNotice($touser, $template_id, $postdata, $url = '', $topcolor = '#FF683F') {
		if(empty($this->account['secret']) || empty($this->account['key']) || $this->account['level'] != 4) {
			return error(-1, '你的公众号没有发送模板消息的权限');
		}
		if(empty($touser)) {
			return error(-1, '参数错误,粉丝openid不能为空');
		}
		if(empty($template_id)) {
			return error(-1, '参数错误,模板标示不能为空');
		}
		if(empty($postdata) || !is_array($postdata)) {
			return error(-1, '参数错误,请根据模板规则完善消息内容');
		}
		$token = $this->fetch_token();
		if (is_error($token)) {
			return $token;
		}


		$data = array();
		$data['touser'] = $touser;
		$data['template_id'] = trim($template_id);
		$data['url'] = trim($url);
		$data['topcolor'] = trim($topcolor);
		$data['data'] = $postdata;
		$data = json_encode($data);
		$post_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
		$response = ihttp_request($post_url, $data);
		if(is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if(empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif(!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->error_code($result['errcode'])}");
		}
		return true;
	}
	
	
	public function getJsApiTicket(){
		if(!empty($this->account['jsapi_ticket'])
		&& is_array($this->account['jsapi_ticket'])
		&& !empty($this->account['jsapi_ticket']['ticket'])
		&& !empty($this->account['jsapi_ticket']['expire'])
		&& $this->account['jsapi_ticket']['expire'] > TIMESTAMP) {
			return $this->account['jsapi_ticket']['ticket'];
		}
		
		load()->func('communication');
		
		$access_token = $this->fetch_token();
		if(is_error($access_token)){
			return $access_token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
		$content = ihttp_get($url);
			if(is_error($content)) {
			return error(-1, '调用接口获取微信公众号 jsapi_ticket 失败, 错误信息: ' . $content['message']);
		}
		$result = @json_decode($content['content'], true);
		if(empty($result) || intval(($result['errcode'])) != 0 || $result['errmsg'] != 'ok') {
			return error(-1, '获取微信公众号 jsapi_ticket 结果错误, 错误信息: ' . $result['errmsg']);
		}
		
		$record = array();
		$record['ticket'] = $result['ticket'];
		$record['expire'] = TIMESTAMP + $result['expires_in'] - 200;
		$row = array();
		$row['jsapi_ticket'] = iserializer($record);
		pdo_update('account_wechats', $row, array('acid' => $this->account['acid']));
		
		$this->account['jsapi_ticket'] = $record;
		return $record['ticket'];
	}
	
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	
	
	public function getJssdkConfig(){
		global $_W;
		
		$jsapiTicket = $this->getJsApiTicket();
		if(is_error($jsapiTicket)){
			$jsapiTicket = $jsapiTicket['message'];
		}
		$nonceStr = $this->createNonceStr();
		$timestamp = TIMESTAMP;
		$url = $_W['siteurl'];

		$string1 = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
		$signature = sha1($string1);

		$config = array(
			"appId"		=> $this->account['key'],
			"nonceStr"	=> $nonceStr,
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
}

