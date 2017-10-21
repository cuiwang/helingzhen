<?php
//客服
//114.215.85.35  8080

//kfai
//115.28.225.82
//60808888  x2Vt247f

define('IP','115.28.225.82');
define('PORT','80');
define('HOSTNAME','/FeieServer/');
//以下2项是平台相关的设置，您不需要更改
define('FEYIN_HOST','my.feyin.net');
define('FEYIN_PORT', 80);
include 'HttpClient.class.php';

class wprint {
	public $client;
	function __construct() {
		$this->client = new HttpClient(IP, PORT);
	}

	function StrPrint($printer_sn, $key, $orderinfo, $times = 1){
		$content = array(
			'sn' => $printer_sn,  
			'printContent' => $orderinfo,
			//'apitype' => 'php',
			'key' => $key,
			'times' => $times
		);
		if(!$this->client->post(HOSTNAME . 'printOrderAction', $content)){
			return error(-1, '链接服务器失败');
		}
		else{
			$result = $this->client->getContent();
			$result = @json_decode($result, true);
			$error = array(
					'服务器接收订单成功',
					'打印机编号错误',
					'服务器处理订单失败',
					'打印内容太长',
					'请求参数错误'
				);
			if($result['responseCode'] == 0) {
				return $result['orderindex'];
			} else {
				return error(-1, $error[$result['responseCode']]);
			}
		}
	}

	function testSendFreeMessage($member_code, $printer_sn, $key, $orderinfo){
		$msgNo = time()+1;
		/*
		 自由格式的打印内容
		*/
		$freeMessage = array(
			'memberCode'=>$member_code, 
			'msgDetail'=>$orderinfo,
			'deviceNo'=>$printer_sn, 
			'msgNo'=>$msgNo,
			'key'=>$key,
		);
		$this->sendFreeMessage($freeMessage);
		return $msgNo;
	}

//----------------------以下是接口定义实现，第三方应用可根据具体情况直接修改----------------------------

	function sendFreeMessage($msg) {
		$msg['reqTime'] = number_format(1000*time(), 0, '', '');
		$content = $msg['memberCode'].$msg['msgDetail'].$msg['deviceNo'].$msg['msgNo'].$msg['reqTime'].$msg['key'];
		$msg['securityCode'] = md5($content);
		$msg['mode']=2;

		return $this->sendMessage($msg);
	}

	function sendMessage($msgInfo) {
		$clientt = new HttpClient(FEYIN_HOST,FEYIN_PORT);
		if(!$clientt->post('/api/sendMsg',$msgInfo)){ //提交失败
			return 'faild';
		}else{
			return $clientt->getContent();
		}
	}
/*
 *  方法2
	根据订单索引,去查询订单是否打印成功,订单索引由方法1返回
*/
	function QueryOrderState($printer_sn, $key, $index){
		$msgInfo = array(
			'sn' => $printer_sn,  
			'key' => $key,
			'index' => $index
		);
	
		if(!$this->client->post(HOSTNAME . 'queryOrderStateAction', $msgInfo)){
			return error(-1, '链接服务器失败');
		} else {
			$result = $this->client->getContent();
			$result = @json_decode($result, true);
			$error = array(
					'已打印/未打印',
					'请求参数错误',
					'服务器处理订单失败',
					'没有找到该索引的订单',
				);
			if($result['responseCode'] == 0) {
				$status = ($result['msg'] == '已打印' ? 1 : 2);
				return $status;
			} else {
				return error(-1, $error[$result['responseCode']]);
			}
		}
	}
/*
 *  方法4
	查询打印机的状态
*/
	function QueryPrinterStatus($printer_sn, $key){
		$msgInfo = array(
			'sn' => $printer_sn,  
			'key' => $key,
		);

		if(!$this->client->post(HOSTNAME . 'queryPrinterStatusAction', $msgInfo)){
			return error(-1, '链接服务器失败');
		} else {
			$result = $this->client->getContent();
			$result = @json_decode($result, true);
			return $result['msg'];
		}
	}
}
?>