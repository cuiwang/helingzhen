<?php
class TopClient
{
	public $appkey;

	public $secretKey;

	public $gatewayUrl = "http://gw.api.taobao.com/router/rest";

	public $format = "xml";

	public $connectTimeout;

	public $readTimeout;

	/** 是否打开入参check**/
	public $checkRequest = true;

	protected $signMethod = "md5";

	protected $apiVersion = "2.0";

	protected $sdkVersion = "top-sdk-php-20151012";

	public function __construct($appkey = "",$secretKey = ""){
		$this->appkey = $appkey;
		$this->secretKey = $secretKey ;
	}

	protected function generateSign($params)
	{
		ksort($params);

		$stringToBeSigned = $this->secretKey;
		foreach ($params as $k => $v)
		{
			if(is_string($v) && "@" != substr($v, 0, 1))
			{
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		$stringToBeSigned .= $this->secretKey;

		return strtoupper(md5($stringToBeSigned));
	}

	public function curl($url, $postFields = null)
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
		curl_setopt ( $ch, CURLOPT_USERAGENT, "top-sdk-php" );
		//https 请求
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		if (is_array($postFields) && 0 < count($postFields))
		{
			$postBodyString = "";
			$postMultipart = false;
			foreach ($postFields as $k => $v)
			{
				if(!is_string($v))
					continue ;

				if("@" != substr($v, 0, 1))//判断是不是文件上传
				{
					$postBodyString .= "$k=" . urlencode($v) . "&"; 
				}
				else//文件上传用multipart/form-data，否则用www-form-urlencoded
				{
					$postMultipart = true;
					if(class_exists('\CURLFile')){
						$postFields[$k] = new \CURLFile(substr($v, 1));
					}
				}
			}
			unset($k, $v);
			curl_setopt($ch, CURLOPT_POST, true);
			if ($postMultipart)
			{
				if (class_exists('\CURLFile')) {
				    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
				} else {
				    if (defined('CURLOPT_SAFE_UPLOAD')) {
				        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
				    }
				}
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			}
			else
			{
				$header = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
				curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
				curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
			}
		}
		$reponse = curl_exec($ch);
		
		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch),0);
		}
		else
		{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode)
			{
				throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}
	public function curl_with_memory_file($url, $postFields = null, $fileFields = null)
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
		curl_setopt ( $ch, CURLOPT_USERAGENT, "top-sdk-php" );
		//https 请求
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		//生成分隔符
		$delimiter = '-------------' . uniqid();
		//先将post的普通数据生成主体字符串
		$data = '';
		if($postFields != null){
			foreach ($postFields as $name => $content) {
			    $data .= "--" . $delimiter . "\r\n";
			    $data .= 'Content-Disposition: form-data; name="' . $name . '"';
			    //multipart/form-data 不需要urlencode，参见 http:stackoverflow.com/questions/6603928/should-i-url-encode-post-data
			    $data .= "\r\n\r\n" . $content . "\r\n";
			}
			unset($name,$content);
		}

		//将上传的文件生成主体字符串
		if($fileFields != null){
			foreach ($fileFields as $name => $file) {
			    $data .= "--" . $delimiter . "\r\n";
			    $data .= 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $file['name'] . "\" \r\n";
			    $data .= 'Content-Type: ' . $file['type'] . "\r\n\r\n";//多了个文档类型

			    $data .= $file['content'] . "\r\n";
			}
			unset($name,$file);
		}
		//主体结束的分隔符
		$data .= "--" . $delimiter . "--";

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER , array(
		    'Content-Type: multipart/form-data; boundary=' . $delimiter,
		    'Content-Length: ' . strlen($data))
		); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$reponse = curl_exec($ch);
		unset($data);

		if (curl_errno($ch))
		{
			throw new Exception(curl_error($ch),0);
		}
		else
		{
			$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if (200 !== $httpStatusCode)
			{
				throw new Exception($reponse,$httpStatusCode);
			}
		}
		curl_close($ch);
		return $reponse;
	}

	protected function logCommunicationError($apiName, $requestUrl, $errorCode, $responseTxt)
	{
		$localIp = isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : "CLI";
		$logger = new TopLogger;
		$logger->conf["log_file"] = rtrim(TOP_SDK_WORK_DIR, '\\/') . '/' . "logs/top_comm_err_" . $this->appkey . "_" . date("Y-m-d") . ".log";
		$logger->conf["separator"] = "^_^";
		$logData = array(
		date("Y-m-d H:i:s"),
		$apiName,
		$this->appkey,
		$localIp,
		PHP_OS,
		$this->sdkVersion,
		$requestUrl,
		$errorCode,
		str_replace("\n","",$responseTxt)
		);
		$logger->log($logData);
	}

	public function execute($request, $session = null,$bestUrl = null)
	{
		$result =  new ResultSet(); 
		if($this->checkRequest) {
			try {
				$request->check();
			} catch (Exception $e) {

				$result->code = $e->getCode();
				$result->msg = $e->getMessage();
				return $result;
			}
		}
		//组装系统参数
		$sysParams["app_key"] = $this->appkey;
		$sysParams["v"] = $this->apiVersion;
		$sysParams["format"] = $this->format;
		$sysParams["sign_method"] = $this->signMethod;
		$sysParams["method"] = $request->getApiMethodName();
		$sysParams["timestamp"] = date("Y-m-d H:i:s");
		if (null != $session)
		{
			$sysParams["session"] = $session;
		}
		$apiParams = array();
		//获取业务参数
		$apiParams = $request->getApiParas();


		//系统参数放入GET请求串
		if($bestUrl){
			$requestUrl = $bestUrl."?";
			$sysParams["partner_id"] = $this->getClusterTag();
		}else{
			$requestUrl = $this->gatewayUrl."?";
			$sysParams["partner_id"] = $this->sdkVersion;
		}
		//签名
		$sysParams["sign"] = $this->generateSign(array_merge($apiParams, $sysParams));

		foreach ($sysParams as $sysParamKey => $sysParamValue)
		{
			// if(strcmp($sysParamKey,"timestamp") != 0)
			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
		}

		$fileFields = array();
		foreach ($apiParams as $key => $value) {
			if(is_array($value) && array_key_exists('type',$value) && array_key_exists('content',$value) ){
				$value['name'] = $key;
				$fileFields[$key] = $value;
				unset($apiParams[$key]);
			}
		}

		// $requestUrl .= "timestamp=" . urlencode($sysParams["timestamp"]) . "&";
		$requestUrl = substr($requestUrl, 0, -1);

		//发起HTTP请求
		try
		{
			if(count($fileFields) > 0){
				$resp = $this->curl_with_memory_file($requestUrl, $apiParams, $fileFields);
			}else{
				$resp = $this->curl($requestUrl, $apiParams);
			}
		}
		catch (Exception $e)
		{
			$this->logCommunicationError($sysParams["method"],$requestUrl,"HTTP_ERROR_" . $e->getCode(),$e->getMessage());
			$result->code = $e->getCode();
			$result->msg = $e->getMessage();
			return $result;
		}

		unset($apiParams);
		unset($fileFields);
		//解析TOP返回结果
		$respWellFormed = false;
		if ("json" == $this->format)
		{
			$respObject = json_decode($resp);
			if (null !== $respObject)
			{
				$respWellFormed = true;
				foreach ($respObject as $propKey => $propValue)
				{
					$respObject = $propValue;
				}
			}
		}
		else if("xml" == $this->format)
		{
			$respObject = @simplexml_load_string($resp);
			if (false !== $respObject)
			{
				$respWellFormed = true;
			}
		}

		//返回的HTTP文本不是标准JSON或者XML，记下错误日志
		if (false === $respWellFormed)
		{
			$this->logCommunicationError($sysParams["method"],$requestUrl,"HTTP_RESPONSE_NOT_WELL_FORMED",$resp);
			$result->code = 0;
			$result->msg = "HTTP_RESPONSE_NOT_WELL_FORMED";
			return $result;
		}

		//如果TOP返回了错误码，记录到业务错误日志中
		if (isset($respObject->code))
		{
			$logger = new TopLogger;
			$logger->conf["log_file"] = rtrim(TOP_SDK_WORK_DIR, '\\/') . '/' . "logs/top_biz_err_" . $this->appkey . "_" . date("Y-m-d") . ".log";
			$logger->log(array(
				date("Y-m-d H:i:s"),
				$resp
			));
		}
		return $respObject;
	}

	public function exec($paramsArray)
	{
		if (!isset($paramsArray["method"]))
		{
			trigger_error("No api name passed");
		}
		$inflector = new LtInflector;
		$inflector->conf["separator"] = ".";
		$requestClassName = ucfirst($inflector->camelize(substr($paramsArray["method"], 7))) . "Request";
		if (!class_exists($requestClassName))
		{
			trigger_error("No such api: " . $paramsArray["method"]);
		}

		$session = isset($paramsArray["session"]) ? $paramsArray["session"] : null;

		$req = new $requestClassName;
		foreach($paramsArray as $paraKey => $paraValue)
		{
			$inflector->conf["separator"] = "_";
			$setterMethodName = $inflector->camelize($paraKey);
			$inflector->conf["separator"] = ".";
			$setterMethodName = "set" . $inflector->camelize($setterMethodName);
			if (method_exists($req, $setterMethodName))
			{
				$req->$setterMethodName($paraValue);
			}
		}
		return $this->execute($req, $session);
	}

	private function getClusterTag()
    {
	    return substr($this->sdkVersion,0,11)."-cluster".substr($this->sdkVersion,11);
    }
}

/**
 * TOP API: alibaba.aliqin.fc.sms.num.send request
 * 
 * @author auto create
 * @since 1.0, 2016.05.24
 */
class AlibabaAliqinFcSmsNumSendRequest
{
	/** 
	 * 公共回传参数，在“消息返回”中会透传回该参数；举例：用户可以传入自己下级的会员ID，在消息返回时，该会员ID会包含在内，用户可以根据该会员ID识别是哪位会员使用了你的应用
	 **/
	private $extend;
	
	/** 
	 * 短信接收号码。支持单个或多个手机号码，传入号码为11位手机号码，不能加0或+86。群发短信需传入多个号码，以英文逗号分隔，一次调用最多传入200个号码。示例：18600000000,13911111111,13322222222
	 **/
	private $recNum;
	
	/** 
	 * 短信签名，传入的短信签名必须是在阿里大鱼“管理中心-短信签名管理”中的可用签名。如“阿里大鱼”已在短信签名管理中通过审核，则可传入”阿里大鱼“（传参时去掉引号）作为短信签名。短信效果示例：【阿里大鱼】欢迎使用阿里大鱼服务。
	 **/
	private $smsFreeSignName;
	
	/** 
	 * 短信模板变量，传参规则{"key":"value"}，key的名字须和申请模板中的变量名一致，多个变量之间以逗号隔开。示例：针对模板“验证码${code}，您正在进行${product}身份验证，打死不要告诉别人哦！”，传参时需传入{"code":"1234","product":"alidayu"}
	 **/
	private $smsParam;
	
	/** 
	 * 短信模板ID，传入的模板必须是在阿里大鱼“管理中心-短信模板管理”中的可用模板。示例：SMS_585014
	 **/
	private $smsTemplateCode;
	
	/** 
	 * 短信类型，传入值请填写normal
	 **/
	private $smsType;
	
	private $apiParas = array();
	
	public function setExtend($extend)
	{
		$this->extend = $extend;
		$this->apiParas["extend"] = $extend;
	}

	public function getExtend()
	{
		return $this->extend;
	}

	public function setRecNum($recNum)
	{
		$this->recNum = $recNum;
		$this->apiParas["rec_num"] = $recNum;
	}

	public function getRecNum()
	{
		return $this->recNum;
	}

	public function setSmsFreeSignName($smsFreeSignName)
	{
		$this->smsFreeSignName = $smsFreeSignName;
		$this->apiParas["sms_free_sign_name"] = $smsFreeSignName;
	}

	public function getSmsFreeSignName()
	{
		return $this->smsFreeSignName;
	}

	public function setSmsParam($smsParam)
	{
		$this->smsParam = $smsParam;
		$this->apiParas["sms_param"] = $smsParam;
	}

	public function getSmsParam()
	{
		return $this->smsParam;
	}

	public function setSmsTemplateCode($smsTemplateCode)
	{
		$this->smsTemplateCode = $smsTemplateCode;
		$this->apiParas["sms_template_code"] = $smsTemplateCode;
	}

	public function getSmsTemplateCode()
	{
		return $this->smsTemplateCode;
	}

	public function setSmsType($smsType)
	{
		$this->smsType = $smsType;
		$this->apiParas["sms_type"] = $smsType;
	}

	public function getSmsType()
	{
		return $this->smsType;
	}

	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.sms.num.send";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->recNum,"recNum");
		RequestCheckUtil::checkNotNull($this->smsFreeSignName,"smsFreeSignName");
		RequestCheckUtil::checkNotNull($this->smsTemplateCode,"smsTemplateCode");
		RequestCheckUtil::checkNotNull($this->smsType,"smsType");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
class ResultSet
{
	
	/** 
	 * 返回的错误码
	 **/
	public $code;
	
	/** 
	 * 返回的错误信息
	 **/
	public $msg;
	
}
class RequestCheckUtil
{
	/**
	 * 校验字段 fieldName 的值$value非空
	 *
	 **/
	public static function checkNotNull($value,$fieldName) {
		
		if(self::checkEmpty($value)){
			throw new Exception("client-check-error:Missing Required Arguments: " .$fieldName , 40);
		}
	}

	/**
	 * 检验字段fieldName的值value 的长度
	 *
	 **/
	public static function checkMaxLength($value,$maxLength,$fieldName){		
		if(!self::checkEmpty($value) && mb_strlen($value , "UTF-8") > $maxLength){
			throw new Exception("client-check-error:Invalid Arguments:the length of " .$fieldName . " can not be larger than " . $maxLength . "." , 41);
		}
	}

	/**
	 * 检验字段fieldName的值value的最大列表长度
	 *
	 **/
	public static function checkMaxListSize($value,$maxSize,$fieldName) {	

		if(self::checkEmpty($value))
			return ;

		$list=preg_split("/,/",$value);
		if(count($list) > $maxSize){
				throw new Exception("client-check-error:Invalid Arguments:the listsize(the string split by \",\") of ". $fieldName . " must be less than " . $maxSize . " ." , 41);
		}
	}

	/**
	 * 检验字段fieldName的值value 的最大值
	 *
	 **/
	public static function checkMaxValue($value,$maxValue,$fieldName){	

		if(self::checkEmpty($value))
			return ;

		self::checkNumeric($value,$fieldName);

		if($value > $maxValue){
				throw new Exception("client-check-error:Invalid Arguments:the value of " . $fieldName . " can not be larger than " . $maxValue ." ." , 41);
		}
	}

	/**
	 * 检验字段fieldName的值value 的最小值
	 *
	 **/
	public static function checkMinValue($value,$minValue,$fieldName) {
		
		if(self::checkEmpty($value))
			return ;

		self::checkNumeric($value,$fieldName);
		
		if($value < $minValue){
				throw new Exception("client-check-error:Invalid Arguments:the value of " . $fieldName . " can not be less than " . $minValue . " ." , 41);
		}
	}

	/**
	 * 检验字段fieldName的值value是否是number
	 *
	 **/
	protected static function checkNumeric($value,$fieldName) {
		if(!is_numeric($value))
			throw new Exception("client-check-error:Invalid Arguments:the value of " . $fieldName . " is not number : " . $value . " ." , 41);
	}

	/**
	 * 校验$value是否非空
	 *  if not set ,return true;
	 *	if is null , return true;
	 *	
	 *
	 **/
	public static function checkEmpty($value) {
		if(!isset($value))
			return true ;
		if($value === null )
			return true;
		if(is_array($value) && count($value) == 0)
			return true;
		if(is_string($value) &&trim($value) === "")
			return true;
		
		return false;
	}

}
class TopLogger
{
	public $conf = array(
		"separator" => "\t",
		"log_file" => ""
	);

	private $fileHandle;

	protected function getFileHandle()
	{
		if (null === $this->fileHandle)
		{
			if (empty($this->conf["log_file"]))
			{
				trigger_error("no log file spcified.");
			}
			$logDir = dirname($this->conf["log_file"]);
			if (!is_dir($logDir))
			{
				mkdir($logDir, 0777, true);
			}
			$this->fileHandle = fopen($this->conf["log_file"], "a");
		}
		return $this->fileHandle;
	}

	public function log($logData)
	{
		if ("" == $logData || array() == $logData)
		{
			return false;
		}
		if (is_array($logData))
		{
			$logData = implode($this->conf["separator"], $logData);
		}
		$logData = $logData. "\n";
		fwrite($this->getFileHandle(), $logData);
	}
}
class SpiUtils{
	private static $top_sign_list = "HTTP_TOP_SIGN_LIST";
	private static $timestamp = "timestamp";
	private static $header_real_ip = array("X_Real_IP", "X_Forwarded_For", "Proxy_Client_IP",
											"WL_Proxy_Client_IP", "HTTP_CLIENT_IP", "HTTP_X_FORWARDED_FOR");
	/**
	 * 校验SPI请求签名，适用于所有GET请求，及不包含文件参数的POST请求。
	 * 
	 * @param request 请求对象
	 * @param secret app对应的secret
	 * @return true：校验通过；false：校验不通过
	 */
	public static function checkSign4FormRequest($secret){
		return self::checkSign(null,null,$secret);
	}

	/**
	 * 校验SPI请求签名，适用于请求体是xml/json等可用文本表示的POST请求。
	 * 
	 * @param request 请求对象
	 * @param body 请求体的文本内容
	 * @param secret app对应的secret
	 * @return true：校验通过；false：校验不通过
	 */
	public static function checkSign4TextRequest($body,$secret){
		return self::checkSign(null,$body,$secret);
	}
	
	/**
	 * 校验SPI请求签名，适用于带文件上传的POST请求。
	 * 
	 * @param request 请求对象
	 * @param form 除了文件参数以外的所有普通文本参数的map集合
	 * @param secret app对应的secret
	 * @return true：校验通过；false：校验不通过
	 */
	public static function checkSign4FileRequest($form, $secret){
		return self::checkSign($form, null, $secret);
	}

	private static function checkSign($form, $body, $secret) {
		$params = array();
		// 1. 获取header参数
		$headerMap = self::getHeaderMap();
		foreach ($headerMap as $k => $v){
			$params[$k] = $v ;
		}

		// 2. 获取url参数
		$queryMap = self::getQueryMap();
		foreach ($queryMap as $k => $v){
			$params[$k] = $v ;
		}

		// 3. 获取form参数
		if ($form == null && $body == null) {
			$formMap = self::getFormMap();
			foreach ($formMap as $k => $v){
				$params[$k] = $v ;
			}
		} else if ($form != null) {
			foreach ($form as $k => $v){
				$params[$k] = $v ;
			}
		}

		if($body == null){
			$body = file_get_contents('php://input');
		}

		$remoteSign = $queryMap["sign"];
		$localSign = self::sign($params, $body, $secret);
		if (strcmp($remoteSign, $localSign) == 0) {
			return true;
		} else {
			$paramStr = self::getParamStrFromMap($params);
			self::logCommunicationError($remoteSign,$localSign,$paramStr,$body);
			return false;
		}
	}

	private static function getHeaderMap() {
		$headerMap = array();
		$signList = $_SERVER['HTTP_TOP_SIGN_LIST']; // 只获取参与签名的头部字段
		$signList = trim($signList);
		if (strlen($signList) > 0){
			$params = split(",", $signList);
			foreach ($_SERVER as $k => $v){
				if (substr($k, 0, 5) == 'HTTP_'){
					foreach($params as $kk){
						$upperkey = strtoupper($kk);
						if(self::endWith($k,$upperkey)){
							$headerMap[$kk] = $v;
						}
					}
				}
			}
		}
		return $headerMap;
	}

	private static function getQueryMap(){
		$queryStr = $_SERVER["QUERY_STRING"];
		$resultArray = array();
		foreach (explode('&', $queryStr) as $pair) {
		    list($key, $value) = explode('=', $pair);
		    if (strpos($key, '.') !== false) {
		        list($subKey, $subVal) = explode('.', $key);

		        if (preg_match('/(?P<name>\w+)\[(?P<index>\w+)\]/', $subKey, $matches)) {
		            $resultArray[$matches['name']][$matches['index']][$subVal] = $value;
		        } else {
		            $resultArray[$subKey][$subVal] = urldecode($value);
		        }
		    } else {
		        $resultArray[$key] = urldecode($value);
		    }
		}
		return $resultArray;
	}

	private static function checkRemoteIp(){
		$remoteIp = $_SERVER["REMOTE_ADDR"];
		foreach ($header_real_ip as $k){
			$realIp = $_SERVER[$k];
			$realIp = trim($realIp);
			if(strlen($realIp) > 0 && strcasecmp("unknown",$realIp)){
				$remoteIp = $realIp;
				break;
			}
		}
		return self::startsWith($remoteIp,"140.205.144.") || $this->startsWith($remoteIp,"40.205.145.");
	}

	private static function getFormMap(){
		$resultArray = array();
		foreach($_POST as $key=>$v) { 
			$resultArray[$k] = $v ;
		}
		return $resultArray ;	
	}

	private static function startsWith($haystack, $needle) {
    	return $needle === "" || strpos($haystack, $needle) === 0;
	}

	private static function endWith($haystack, $needle) {   
	    $length = strlen($needle);  
	    if($length == 0)
	    {    
	        return true;  
	    }  
	    return (substr($haystack, -$length) === $needle);
 	}

	private static function checkTimestamp(){
		$ts = $_POST['timestamp'];
		if($ts){
			$clientTimestamp = strtotime($ts);
			$current = $_SERVER['REQUEST_TIME'];
			return ($current - $clientTimestamp) <= 5*60*1000;
		}else{
			return false;
		}
	}

	private static function getParamStrFromMap($params){
		ksort($params);
		$stringToBeSigned = "";
		foreach ($params as $k => $v)
		{
			if(strcmp("sign", $k) != 0)
			{
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		return $stringToBeSigned;
	}

	private static function sign($params,$body,$secret){
		ksort($params);

		$stringToBeSigned = $secret;
		$stringToBeSigned .= self::getParamStrFromMap($params);

		if($body)
			$stringToBeSigned .= $body;
		$stringToBeSigned .= $secret;
		return strtoupper(md5($stringToBeSigned));
	}

	protected static function logCommunicationError($remoteSign, $localSign, $paramStr, $body)
	{
		$localIp = isset($_SERVER["SERVER_ADDR"]) ? $_SERVER["SERVER_ADDR"] : "CLI";
		$logger = new TopLogger;
		$logger->conf["log_file"] = rtrim(TOP_SDK_WORK_DIR, '\\/') . '/' . "logs/top_comm_err_". date("Y-m-d") . ".log";
		$logger->conf["separator"] = "^_^";
		$logData = array(
		"checkTopSign error" ,
		"remoteSign=".$remoteSign ,
		"localSign=".$localSign ,
		"paramStr=".$paramStr ,
		"body=".$body
		);
		$logger->log($logData);
	}
	private static function clear_blank($str, $glue='')
	{
		$replace = array(" ", "\r", "\n", "\t"); return str_replace($replace, $glue, $str);
	}
}


