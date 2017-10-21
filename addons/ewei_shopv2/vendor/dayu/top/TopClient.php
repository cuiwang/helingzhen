<?php
class TopClient
{
	public $appkey;
	public $secretKey;
	public $gatewayUrl = 'http://gw.api.taobao.com/router/rest';
	public $format = 'xml';
	public $connectTimeout;
	public $readTimeout;
	/** 是否打开入参check**/
	public $checkRequest = true;
	protected $signMethod = 'md5';
	protected $apiVersion = '2.0';
	protected $sdkVersion = 'top-sdk-php-20151012';

	public function __construct($appkey = '', $secretKey = '')
	{
		$this->appkey = $appkey;
		$this->secretKey = $secretKey;
	}

	protected function generateSign($params)
	{
		ksort($params);
		$stringToBeSigned = $this->secretKey;

		foreach ($params as $k => $v ) {
			if (is_string($v) && ('@' != substr($v, 0, 1))) {
				$stringToBeSigned .= $k . $v;
			}

		}

		unset($k);
		unset($v);
		$stringToBeSigned .= $this->secretKey;
		return strtoupper(md5($stringToBeSigned));
	}

	public function curl($url, $postFields = NULL)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if ($this->readTimeout) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
		}


		if ($this->connectTimeout) {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
		}


		curl_setopt($ch, CURLOPT_USERAGENT, 'top-sdk-php');

		if ((5 < strlen($url)) && (strtolower(substr($url, 0, 5)) == 'https')) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}


		if (is_array($postFields) && (0 < count($postFields))) {
			$postBodyString = '';
			$postMultipart = false;

			foreach ($postFields as $k => $v ) {
				if (!(is_string($v))) {
					continue;
				}


				if ('@' != substr($v, 0, 1)) {
					$postBodyString .= $k . '=' . urlencode($v) . '&';
				}
				 else {
					$postMultipart = true;

					if (class_exists('\\CURLFile')) {
						$postFields[$k] = new CURLFile(substr($v, 1));
					}

				}
			}

			unset($k);
			unset($v);
			curl_setopt($ch, CURLOPT_POST, true);

			if ($postMultipart) {
				if (class_exists('\\CURLFile')) {
					curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
				}
				 else if (defined('CURLOPT_SAFE_UPLOAD')) {
					curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
				}


				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}
			 else {
				$header = array('content-type: application/x-www-form-urlencoded; charset=UTF-8');
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
			}
		}


		$reponse = curl_exec($ch);

		if (curl_errno($ch)) {
			throw new Exception(curl_error($ch), 0);
		}
		 else {
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if (200 !== $httpStatusCode) {
				throw new Exception($reponse, $httpStatusCode);
			}

		}

		curl_close($ch);
		return $reponse;
	}

	public function curl_with_memory_file($url, $postFields = NULL, $fileFields = NULL)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if ($this->readTimeout) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
		}


		if ($this->connectTimeout) {
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
		}


		curl_setopt($ch, CURLOPT_USERAGENT, 'top-sdk-php');

		if ((5 < strlen($url)) && (strtolower(substr($url, 0, 5)) == 'https')) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}


		$delimiter = '-------------' . uniqid();
		$data = '';

		if ($postFields != NULL) {
			foreach ($postFields as $name => $content ) {
				$data .= '--' . $delimiter . "\r\n";
				$data .= 'Content-Disposition: form-data; name="' . $name . '"';
				$data .= "\r\n\r\n" . $content . "\r\n";
			}

			unset($name);
			unset($content);
		}


		if ($fileFields != NULL) {
			foreach ($fileFields as $name => $file ) {
				$data .= '--' . $delimiter . "\r\n";
				$data .= 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $file['name'] . '" ' . "\r\n";
				$data .= 'Content-Type: ' . $file['type'] . "\r\n\r\n";
				$data .= $file['content'] . "\r\n";
			}

			unset($name);
			unset($file);
		}


		$data .= '--' . $delimiter . '--';
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data; boundary=' . $delimiter, 'Content-Length: ' . strlen($data)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$reponse = curl_exec($ch);
		unset($data);

		if (curl_errno($ch)) {
			throw new Exception(curl_error($ch), 0);
		}
		 else {
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if (200 !== $httpStatusCode) {
				throw new Exception($reponse, $httpStatusCode);
			}

		}

		curl_close($ch);
		return $reponse;
	}

	protected function logCommunicationError($apiName, $requestUrl, $errorCode, $responseTxt)
	{
		$localIp = ((isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'CLI'));
		$logger = new TopLogger();
		$logger->conf['log_file'] = rtrim(TOP_SDK_WORK_DIR, '\\/') . '/' . 'logs/top_comm_err_' . $this->appkey . '_' . date('Y-m-d') . '.log';
		$logger->conf['separator'] = '^_^';
		$logData = array(date('Y-m-d H:i:s'), $apiName, $this->appkey, $localIp, PHP_OS, $this->sdkVersion, $requestUrl, $errorCode, str_replace("\n", '', $responseTxt));
		$logger->log($logData);
	}

	public function execute($request, $session = NULL, $bestUrl = NULL)
	{
		$result = new ResultSet();

		if ($this->checkRequest) {
			try {
				$request->check();
			}
			catch (Exception $e) {
				$result->code = $e->getCode();
				$result->msg = $e->getMessage();
				return $result;
			}
		}


		$sysParams['app_key'] = $this->appkey;
		$sysParams['v'] = $this->apiVersion;
		$sysParams['format'] = $this->format;
		$sysParams['sign_method'] = $this->signMethod;
		$sysParams['method'] = $request->getApiMethodName();
		$sysParams['timestamp'] = date('Y-m-d H:i:s');

		if (NULL != $session) {
			$sysParams['session'] = $session;
		}


		$apiParams = array();
		$apiParams = $request->getApiParas();

		if ($bestUrl) {
			$requestUrl = $bestUrl . '?';
			$sysParams['partner_id'] = $this->getClusterTag();
		}
		 else {
			$requestUrl = $this->gatewayUrl . '?';
			$sysParams['partner_id'] = $this->sdkVersion;
		}

		$sysParams['sign'] = $this->generateSign(array_merge($apiParams, $sysParams));

		foreach ($sysParams as $sysParamKey => $sysParamValue ) {
			$requestUrl .= $sysParamKey . '=' . urlencode($sysParamValue) . '&';
		}

		$fileFields = array();

		foreach ($apiParams as $key => $value ) {
			if (is_array($value) && array_key_exists('type', $value) && array_key_exists('content', $value)) {
				$value['name'] = $key;
				$fileFields[$key] = $value;
				unset($apiParams[$key]);
			}

		}

		$requestUrl = substr($requestUrl, 0, -1);

		try {
			if (0 < count($fileFields)) {
				$resp = $this->curl_with_memory_file($requestUrl, $apiParams, $fileFields);
			}
			 else {
				$resp = $this->curl($requestUrl, $apiParams);
			}
		}
		catch (Exception $e) {
			$this->logCommunicationError($sysParams['method'], $requestUrl, 'HTTP_ERROR_' . $e->getCode(), $e->getMessage());
			$result->code = $e->getCode();
			$result->msg = $e->getMessage();
			return $result;
		}

		unset($apiParams);
		unset($fileFields);
		$respWellFormed = false;

		if ('json' == $this->format) {
			$respObject = json_decode($resp);

			if (NULL !== $respObject) {
				$respWellFormed = true;

				foreach ($respObject as $propKey => $propValue ) {
					$respObject = $propValue;
				}
			}

		}
		 else if ('xml' == $this->format) {
			$respObject = @simplexml_load_string($resp);

			if (false !== $respObject) {
				$respWellFormed = true;
			}

		}


		if (false === $respWellFormed) {
			$this->logCommunicationError($sysParams['method'], $requestUrl, 'HTTP_RESPONSE_NOT_WELL_FORMED', $resp);
			$result->code = 0;
			$result->msg = 'HTTP_RESPONSE_NOT_WELL_FORMED';
			return $result;
		}


		if (isset($respObject->code)) {
			$logger = new TopLogger();
			$logger->conf['log_file'] = rtrim(TOP_SDK_WORK_DIR, '\\/') . '/' . 'logs/top_biz_err_' . $this->appkey . '_' . date('Y-m-d') . '.log';
			$logger->log(array(date('Y-m-d H:i:s'), $resp));
		}


		return $respObject;
	}

	public function exec($paramsArray)
	{
		if (!(isset($paramsArray['method']))) {
			trigger_error('No api name passed');
		}


		$inflector = new LtInflector();
		$inflector->conf['separator'] = '.';
		$requestClassName = ucfirst($inflector->camelize(substr($paramsArray['method'], 7))) . 'Request';

		if (!(class_exists($requestClassName))) {
			trigger_error('No such api: ' . $paramsArray['method']);
		}


		$session = ((isset($paramsArray['session']) ? $paramsArray['session'] : NULL));
		$req = new $requestClassName();

		foreach ($paramsArray as $paraKey => $paraValue ) {
			$inflector->conf['separator'] = '_';
			$setterMethodName = $inflector->camelize($paraKey);
			$inflector->conf['separator'] = '.';
			$setterMethodName = 'set' . $inflector->camelize($setterMethodName);

			if (method_exists($req, $setterMethodName)) {
				$req->$setterMethodName($paraValue);
			}

		}

		return $this->execute($req, $session);
	}

	private function getClusterTag()
	{
		return substr($this->sdkVersion, 0, 11) . '-cluster' . substr($this->sdkVersion, 11);
	}
}


?>