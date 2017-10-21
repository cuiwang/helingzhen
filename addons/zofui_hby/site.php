<?php
/**
 * 红包雨模块微站定义
 *
 * @author 众惠科技
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('HBY_ROOT', IA_ROOT . '/addons/zofui_hby');
class Zofui_hbyModuleSite extends WeModuleSite {



	//获取区域
	function getarea($ip = ''){
		$res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);  
		if(empty($res)){ return false; }  
		$jsonMatches = array();  
		preg_match('#\{.+?\}#', $res, $jsonMatches);  
		if(!isset($jsonMatches[0])){ return false; }  
		$json = json_decode($jsonMatches[0], true);  
		if(isset($json['ret']) && $json['ret'] == 1){  
			$json['ip'] = $ip;  
			unset($json['ret']);  
		}else{  
			return false;  
		}  
		return $json;
	}


	//获取客户端IP
	function getClientIp() {
		$ip = "";
		if (!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		}
		if (!empty($_SERVER["REMOTE_ADDR"])){
			$ip = $_SERVER["REMOTE_ADDR"];
		}
			
		return $ip;
	}

  	function getUserInfo(){
        global $_W;
		load()->model('mc');		
/* 		//测试
		$_W['openid'] = 111111; */
		
		//活动信息
		$asql = "SELECT * FROM " . tablename('zofui_hby_activity') . " WHERE `uniacid` = :uniacid ORDER BY `id` DESC";
		$actinfo = pdo_fetch($asql , array(
			':uniacid' => $_W['uniacid']			
		));

		//用户信息
		$usql = "SELECT * FROM " . tablename('zofui_hby_user') . " WHERE `uniacid` = :uniacid AND `actid` = :actid AND `openid` = :openid";
		$userinfo = pdo_fetch($usql , array(
			':uniacid' => $_W['uniacid'],
			':actid' => $actinfo['id'],
			':openid' => $_W['openid']
		));
		if(!empty($userinfo)){
			if(empty($userinfo['headimgurl'])){
				$userinfo = mc_oauth_userinfo($_W['uniacid']);
				$data = array(
					'nickname' => stripslashes($userinfo['nickname']),
					'headimgurl' => $userinfo['headimgurl']			
				);				
				pdo_update("zofui_hby_user",$data, array('actid' => $actinfo['id'],'openid' => $_W['openid']));				
				return $userinfo;
			}else{
				return $userinfo;			
			}
		}else{
			$userinfo = mc_oauth_userinfo($_W['uniacid']);
			$data = array(
				'uniacid' => $_W['uniacid'],
				'actid' => $actinfo['id'],
				'openid' => $userinfo['openid'],	
				'nickname' => stripslashes($userinfo['nickname']),				
				'headimgurl' => $userinfo['headimgurl'],
				'times' => $this->module['config']['times'],				
				'subscribe' => $userinfo['subscribe'],				
				'time' => time()
			);
			$res = pdo_insert('zofui_hby_user',$data);
			$userinfo['id'] = pdo_insertid();
			$userinfo['times'] = $this->module['config']['times'];			
			if($res){
				return $userinfo;				
			}else{
				return false;
			}
		}
		 
	}
	
	//格式化时间
	function timeFormat($time){
		if($time >= time()-60){
			return time() - $time . '秒前';
		}elseif($time >= time()-120){
			return '1分钟前';
		}elseif($time >= time()-180){
			return '2分钟前';
		}elseif($time >= time()-240){
			return '3分钟前';
		}elseif($time >= time()-300){
			return '4分钟前';
		}elseif($time >= time()-360){
			return '5分钟前';
		}elseif($time >= time()-480){
			return '6分钟前';
		}else{
			return '10分钟前';			
		}
		
	}
	
 	public function sendhongbaoto($arr) {
		global $_W,$_GPC;
		$data['mch_id'] = $this->module['config']['mchid'];
		$data['mch_billno'] = $data['mch_id'].date("Ymd",time()).date("His",time()).rand(1111,9999);
		$data['nonce_str'] = $this->createNoncestr();
		$data['re_openid'] = $arr['openid'];
		$data['wxappid'] = $_W['account']['oauth']['key'];
		$data['nick_name'] = $arr['hbname'];
		$data['send_name'] = $arr['hbname'];
		$data['total_amount'] = $arr['fee']*100;
		$data['min_value'] = $arr['fee']*100;
		$data['max_value'] = $arr['fee']*100;
		$data['total_num'] = 1;
		$data['client_ip'] = $_SERVER['REMOTE_ADDR'];
		$data['act_name'] = '红包雨';
		$data['remark'] = '感谢您对我们的支持。';
		$data['wishing'] = $arr['body'];
		if(!$data['re_openid']) 
		{
			$rearr['return_msg']='缺少用户openid';
			return $rearr;
		}
		$data['sign'] = $this->getSign($data);
		$xml = $this->arrayToXml($data);
		$url ="https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack";
		$re = $this->wxHttpsRequestPem($xml,$url);
		$rearr = $this->xmlToArray($re);
		return $rearr;
	}
	
	function trimString($value) {
		$ret = null;
		if (null != $value) 
		{
			$ret = $value;
			if (strlen($ret) == 0) 
			{
				$ret = null;
			}
		}
		return $ret;
	}
	
	public function createNoncestr( $length = 32 ) {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		$str ="";
		for ( $i = 0; $i < $length; $i++ ) 
		{
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
		}
		return $str;
	}
	
	function formatBizQueryParaMap($paraMap, $urlencode) {
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v) 
		{
			if($urlencode) 
			{
				$v = urlencode($v);
			}
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	public function getSign($Obj) {
		foreach ($Obj as $k => $v) 
		{
			$Parameters[$k] = $v;
		}
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
		$String = $String."&key=".$this->module['config']['apikey'];
		$String = md5($String);
		$result_ = strtoupper($String);
		return $result_;
	}
	
	public function arrayToXml($arr) {
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
	public function xmlToArray($xml) {
		$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return $array_data;
	}
	
	public function wxHttpsRequestPem( $vars,$url, $second=30,$aHeader=array()) {
		global $_W;
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLCERT,HBY_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_cert.pem');
		curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
		curl_setopt($ch,CURLOPT_SSLKEY,HBY_ROOT.'/cert/'.$_W['uniacid'].'/apiclient_key.pem');
		curl_setopt($ch,CURLOPT_CAINFO,'PEM');
		curl_setopt($ch,CURLOPT_CAINFO,HBY_ROOT.'/cert/'.$_W['uniacid'].'/rootca.pem');
		if( count($aHeader) >= 1 )
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data)
		{
			curl_close($ch);
			return $data;
		}
		else 
		{
			$error = curl_errno($ch);
			echo "call faild, errorCode:$error\n";
			curl_close($ch);
			return false;
		}
	} 


}