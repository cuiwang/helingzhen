<?php
/**
 * 微信红包类
 * @author 木兰东 http://mulandong.duapp.com/
 * 本代码出自一位前辈之手，由于作者被封，无法与其取得联系，如有侵权，请联系本人 QQ 1214512299
 */
include_once("CommonUtil.php");
include_once("SDKRuntimeException.class.php");
include_once("MD5SignUtil.php");
include_once("WxHongBaoException.php");
class WxHongBaoHelper
{
	var $parameters; //cft 参数
	function __construct()
	{
		
	}
	function setParameter($parameter, $parameterValue) {
		$this->parameters[CommonUtil::trimString($parameter)] = CommonUtil::trimString($parameterValue);
	}
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	protected function create_noncestr( $length = 16 ) {  
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
		$str ="";  
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
			//$str .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
		}  
		return $str;  
	}
	function check_sign_parameters(){
		if($this->parameters["nonce_str"] == null || 
			$this->parameters["mch_billno"] == null || 
			$this->parameters["mch_id"] == null || 
			$this->parameters["wxappid"] == null || 
			$this->parameters["nick_name"] == null || 
			$this->parameters["send_name"] == null ||
			$this->parameters["re_openid"] == null || 
			$this->parameters["total_amount"] == null || 
			$this->parameters["max_value"] == null ||
			$this->parameters["total_num"] == null || 
			$this->parameters["wishing"] == null || 
			$this->parameters["client_ip"] == null || 
			$this->parameters["act_name"] == null ||
			$this->parameters["remark"] == null ||
			$this->parameters["min_value"] == null
			)
		{
			return false;
		}
		return true;

	}
	/**
	  例如：
	 	appid：    wxd930ea5d5a258f4f
		mch_id：    10000100
		device_info：  1000
		Body：    test
		nonce_str：  ibuaiVcKdpRxkhJA
		第一步：对参数按照 key=value 的格式，并按照参数名 ASCII 字典序排序如下：
		stringA="appid=wxd930ea5d5a258f4f&body=test&device_info=1000&mch_i
		d=10000100&nonce_str=ibuaiVcKdpRxkhJA";
		第二步：拼接支付密钥：
		stringSignTemp="stringA&key=192006250b4c09247ec02edce69f6a2d"
		sign=MD5(stringSignTemp).toUpperCase()="9A0A8659F005D6984697E2CA0A
		9CF3B7"
	 */
	protected function get_sign(){
		try {
			if (null == PARTNERKEY || "" == PARTNERKEY ) {
				throw new SDKRuntimeException("密钥不能为空!");
			}
			if($this->check_sign_parameters() == false) {   //检查生成签名参数
			   throw new SDKRuntimeException("生成签名参数缺失!");
		    }
			$commonUtil = new CommonUtil();
			ksort($this->parameters);
			$unSignParaString = $commonUtil->formatQueryParaMap($this->parameters, false);

			$md5SignUtil = new MD5SignUtil();
			return $md5SignUtil->sign($unSignParaString,$commonUtil->trimString(PARTNERKEY));
		}catch (SDKRuntimeException $e)
		{
			throw new WxHongBaoException($e->errorMessage(),10302);
		}

	}
	
	//生成红包接口XML信息
	/*
	<xml>
		<sign>![CDATA[E1EE61A91C8E90F299DE6AE075D60A2D]]</sign>
		<mch_billno>![CDATA[0010010404201411170000046545]]</mch_billno>
		<mch_id>![CDATA[888]]</mch_id>
		<wxappid>![CDATA[wxcbda96de0b165486]]</wxappid>
		<nick_name>![CDATA[nick_name]]</nick_name>
		<send_name>![CDATA[send_name]]</send_name>
		<re_openid>![CDATA[onqOjjmM1tad-3ROpncN-yUfa6uI]]</re_openid>
		<total_amount>![CDATA[200]]</total_amount>
		<min_value>![CDATA[200]]</min_value>
		<max_value>![CDATA[200]]</max_value> 
		<total_num>![CDATA[1]]</total_num>
		<wishing>![CDATA[恭喜发财]]</wishing>
		<client_ip>![CDATA[127.0.0.1]]</client_ip>
		<act_name>![CDATA[新年红包]]</act_name>
		<act_id>![CDATA[act_id]]</act_id>
		<remark>![CDATA[新年红包]]</remark>
		<logo_imgurl>![CDATA[https://xx/img/wxpaylogo.png]]</logo_imgurl>
		<share_content>![CDATA[share_content]]</share_content>
		<share_url>![CDATA[https://xx/img/wxpaylogo.png]]</share_url>
		<share_imgurl>![CDATA[https:/xx/img/wxpaylogo.png]]</share_imgurl>
		<nonce_str>![CDATA[50780e0cca98c8c8e814883e5caa672e]]</nonce_str>
	</xml>
	*/
	function create_hongbao_xml($retcode = 0, $reterrmsg = "ok"){
		 try {
		    //var_dump($this->parameters);
		    $this->setParameter('sign', $this->get_sign());
		    //var_dump($this->parameters);
		    $commonUtil = new CommonUtil();
		    return  $commonUtil->arrayToXml($this->parameters);
		   
		}catch (SDKRuntimeException $e)
		{
			throw new WxHongBaoException($e->errorMessage(),10301);
		}		

	}
	
	function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//这里设置代理，如果有的话
		//curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
		//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		
		//以下两种方式需选择一种
		//第一种方法，cert 与 key 分别属于两个.pem文件

		curl_setopt($ch,CURLOPT_SSLCERT,ATTACHMENT_ROOT.'/certs/'.APPID.'apiclient_cert.pem');
 		curl_setopt($ch,CURLOPT_SSLKEY,ATTACHMENT_ROOT.'/certs/'.APPID.'apiclient_key.pem');
		curl_setopt($ch,CURLOPT_CAINFO,ATTACHMENT_ROOT.'/certs/'.APPID.'rootca.pem');
		
		
		//curl_setopt($ch,CURLOPT_SSLCERT,ROOT_PATH.DS.'pay'.DS.'apiclient_cert.pem');
 		//curl_setopt($ch,CURLOPT_SSLKEY,ROOT_PATH.DS.'pay'.DS.'apiclient_key.pem');
 		//curl_setopt($ch,CURLOPT_CAINFO,ROOT_PATH.DS.'pay'.DS.'rootca.pem');
		
		//第二种方式，两个文件合成一个.pem文件
		//curl_setopt($ch,CURLOPT_SSLCERT,getcwd().'/all.pem');
	 
		if( count($aHeader) >= 1 ){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
	 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		}
		else { 
			$error = curl_errno($ch);
			//echo "call faild, errorCode:$error\n"; 
			curl_close($ch);
			return false;
		}
	}


}

?>