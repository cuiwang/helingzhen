<?php

/**
 *
 *  garcia
 */

class WechatAPI
{



	public function getAccessToken($file) {
		global $_W,$_GPC;
		$access_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$_W['account']['key']."&secret=".$_W['account']['secret'];
		if(is_file($file)){
			$myfile = fopen($file, "r") ;
			$json  = fread($myfile,filesize($file));
			fclose($myfile);
			$json = json_decode($json,true);
			$access_token = $json['access_token'];
			$expires_in = $json['expires_in'];
			if($expires_in<time()){
				$j1= $this->https_url($access_url);
				$d1 = json_decode($j1,true);
				$d1['expires_in'] = time()+360;
				$access_token = $d1['access_token'];
				$json2 = json_encode($d1);
				$myfile = fopen($file, "w");
				fwrite($myfile, $json2);
				fclose($myfile);
			}else{
				$myfile = fopen($file, "r") ;
				$json  = fread($myfile,filesize($file));
				fclose($myfile);
				$json = json_decode($json,true);
				$access_token = $json['access_token'];
				$expires_in = $json['expires_in'];
			}
		}else{
			$j1= $this->https_url($access_url);
			$d1 = json_decode($j1,true);
			$d1['expires_in'] = time()+360;
			$json = json_encode($d1);
			$myfile = fopen($file, "w");
			fwrite($myfile, $json);
			fclose($myfile);
			$access_token = $d1['access_token'];
		}
		return $access_token;

	}


	public function getIPaddress()

	{

    $IPaddress='';

    if (isset($_SERVER)){

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){

            $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];

        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {

            $IPaddress = $_SERVER["HTTP_CLIENT_IP"];

        } else {

            $IPaddress = $_SERVER["REMOTE_ADDR"];

        }

    } else {

        if (getenv("HTTP_X_FORWARDED_FOR")){

            $IPaddress = getenv("HTTP_X_FORWARDED_FOR");

        } else if (getenv("HTTP_CLIENT_IP")) {

            $IPaddress = getenv("HTTP_CLIENT_IP");

        } else {

            $IPaddress = getenv("REMOTE_ADDR");

        }

    }

    return $IPaddress;

}


/**
 * 多功能远程获取
 */
function https_url($url,$params=false,$ispost=false,$header=false,$h_info=false,$is_pay=false){
    $httpInfo = array();
    $ch = curl_init();

    $agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 MicroMessenger/6.5.2.501 NetType/WIFI WindowsWechat';
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT ,$agent);
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
    if($header){
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $h_info);
    }
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //是否带证书 微信支付用
    if($is_pay){
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // 只信任CA颁布的证书
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配
       curl_setopt($ch, CURLOPT_SSLCERT,INC.'/apiclient_cert.pem');
       curl_setopt($ch, CURLOPT_SSLKEY,INC.'/apiclient_key.pem');
       curl_setopt($ch, CURLOPT_CAINFO, INC.'/rootca.pem'); // CA根证书（用来验证的网站证书是否是CA颁布）
    }
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            $params = http_build_query($params);
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}



 /**
  * 发送客服信息
  */
  public function sendText($openid,$text,$token) {

	$data = array(
	  "touser"=>$openid,
	  "msgtype"=>"text",
	  "text"=>array("content"=>$text));

	$ret = $this->sendmessage($data,$token);
	return $ret;
  }


 /**
  * 客服消息
  */
 public function sendmessage($data,$token) {
	global $_W,$_GPC;
	if(empty($data)) {

		return error(-1, '参数错误');
	}


	if(is_error($token)){

		return $token;
	}

	 $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}";
	 return $response = $this->https_url($url, urldecode(json_encode($data)),true);

	if(is_error($response)) {
		return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
	}
	return $result = @json_decode($response['content'], true);
	if(empty($result)) {
		return error(-1, "接口调用失败, 元数据: {$response['meta']}");
	} elseif(!empty($result['errcode'])) {
		return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->error_code($result['errcode'])}");
	}
	return $result;
}
  // 发送红包
  public function sendhongbaoto($data,$mckey){

 		 $url ="https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
 		 $data['sign'] = $this->getSign($data,$mckey);

 		 $xml = $this->arrayToXml($data);
 		 $re = $this->https_url($url,$xml,true,false,false,true);
 		 $rearr = $this->xmlToArray($re);
 		 return $rearr;
 		 // var_dump($mckey);
  }
  	// 发送卡券
	public function getcupon($jsonfile,$file,$openid,$card_id)
	{

		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=ACCESS_TOKEN&type=wx_card";
		$token = $this->getAccessToken($jsonfile);
		$url = str_replace("ACCESS_TOKEN", $token, $url);
		// $re = $this->https_url($url);
		// $re =json_decode($re,true);
		// $ticket = $re['ticket'];

		if(is_file($file)){
			$myfile = fopen($file, "r") ;
			$json  = fread($myfile,filesize($file));
			fclose($myfile);
			$json = json_decode($json,true);
			$ticket = $json['ticket'];
			$timestamp = $json['timestamp'];
			if($timestamp<time()){
				$j1= $this->https_url($access_url);
				$d1 = json_decode($j1,true);
				$d1['timestamp'] = time()+7000;
				$ticket = $d1['ticket'];
				$json2 = json_encode($d1);
				$myfile = fopen($file, "w");
				fwrite($myfile, $json2);
				fclose($myfile);
			}else{
				$j1= $this->https_url($url);
				$d1 = json_decode($j1,true);
				$d1['timestamp'] = time()+7000;
				$json = json_encode($d1);
				$myfile = fopen($file, "w");
				fwrite($myfile, $json);
				fclose($myfile);
				$ticket = $d1['ticket'];
			}
		}else{
			$j1= $this->https_url($url);
			$d1 = json_decode($j1,true);
			$d1['timestamp'] = time()+7000;
			$json = json_encode($d1);
			$myfile = fopen($file, "w");
			fwrite($myfile, $json);
			fclose($myfile);
			$ticket = $d1['ticket'];
		}

		$nonceStr = $this->createNoncestr();
		 $timestamp=time();
		 $data['api_ticket']=$ticket;
		 $data['card_id'] = $card_id;
		 $data['nonce_str']=$nonceStr;
		 $string = $timestamp;
		 foreach ($data as $key => $value) {
		 	  $temp[substr($value, 0,1)] = $value;
		 }
		 ksort($temp);
		 foreach ($temp as $key => $value) {
		 	$string.=$value;
		 }

	     $signature = sha1($string);
		$_reurn = array(
			   'ticket'=>$ticket,
			   'noncestr'=>$nonceStr,
			   'card_id'=>$card_id,
			   'timestamp'=>$timestamp,
			   'json'=>json_encode(array(
			   'timestamp'=>$timestamp,
			   'signature'=>$signature,
			   // 'openid'=>$openid,
			   'card_id'=>$card_id,
			   'nonce_str'=>$nonceStr,
			   'api_ticket'=>$data['api_ticket'],
			   	))
			);
		return $_reurn;
	}

    /**
     * 签名
     */
	public function getSign($Obj,$mckey,$is_sha1=false,$is_noh=false,$sort = true)
	{
		if($sort){
			foreach ($Obj as $k => $v)
			{
				$Parameters[$k] = $v;
			}
			ksort($Parameters);
		}else{
			$Parameters = $Obj;
			// var_dump($Parameters);
		}

		$String = $this->formatBizQueryParaMap($Parameters, false,$is_noh,$sort);
		if(!$is_sha1){
		    $String = $String."&key=".$mckey;
			$String = md5($String);
		}else{
			$String = sha1($String);
		}
		$result_ = strtoupper($String);
		return $result_;
	}

	/**
	 * 格式处理
	 */
	public function formatBizQueryParaMap($paraMap, $urlencode,$is_noh)
	{
		$buff = "";
		if($sort){
			ksort($paraMap);
		}

		foreach ($paraMap as $k => $v)
		{
			if($urlencode)
			{
				$v = urlencode($v);
			}
			if(!$is_noh){
				$buff .= $k . "=" . $v . "&";
			}else{
				$buff .= $v;
			}

		}
		 $reqPar;
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}

		// var_dump($reqPar);
		return $reqPar;
	}
   // xml 转 array
	public function xmlToArray($xml)
	{
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}

		public function arrayToXml($arr)
	{
		$xml = "<xml>";
		foreach ($arr as $key=>$val)
		{
			if (is_numeric($val))
			{
				$xml.="<".$key.">".$val."</".$key.">";
			}
			else $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
		}
		$xml.="</xml>";
		return $xml;
	}



    /**
     * 随机字符
     */
		public function createNoncestr( $length = 32 )
	{
		$chars = "abcdefghijklmnopqrstuvwxyz";
		$str ="";
		for ( $i = 0; $i < $length; $i++ )
		{
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		return $str;
	}

	// 推送模板信息

	public function sendTemplate($touser,$template_id,$url,$token,$data){
		$_tdata['touser']=$touser;
		$_tdata['template_id']=$template_id;
		$_tdata['url']=$url;
		$_tdata['data']=$data;
		$_tdata = json_encode($_tdata);
		$_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=~token~";
		$_url = str_replace('~token~', $token, $_url);
		return $this->https_url($_url,$_tdata,true);
	}
}
