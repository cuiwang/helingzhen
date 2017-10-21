<?php

function print_feie_url($deviceno) {
	$number = substr($deviceno, 2, 1);
	$data = array(
		'5' => 'http://dzp.feieyun.com',
		'6' => 'http://api163.feieyun.com',
		'7' => 'http://api174.feieyun.com'
	);
	return $data[$number];
}

class wprint {
	public $client;
	function StrPrint($printer_sn, $key, $orderinfo, $times = 1){
		load()->func('communication');
		$content = array(
			'sn' => $printer_sn,  
			'printContent' => $orderinfo,
			'key' => $key,
			'times' => $times
		);
		$http = print_feie_url($printer_sn);
		$posturl = $http . '/FeieServer/printOrderAction';

		$response = ihttp_post($posturl, $content);
		if(is_error($response)) {
			return error(-1, "错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if($result['responseCode'] == 0) {
			return $result['orderindex'];
		} else {
			$error = array(
				'服务器接收订单成功',
				'打印机编号错误',
				'服务器处理订单失败',
				'打印内容太长',
				'请求参数错误'
			);
			return error(-1, $error[$result['responseCode']]);
		}
	}

	function QueryOrderState($printer_sn, $key, $index){
		load()->func('communication');
		$msgInfo = array(
			'sn' => $printer_sn,  
			'key' => $key,
			'index' => $index
		);
		$http = print_feie_url($printer_sn);
		$posturl = $http . '/FeieServer/queryOrderStateAction';
		$response = ihttp_post($posturl, $msgInfo);
		if(is_error($response)) {
			return error(-1, "错误: {$response['message']}");
		}

		$result = @json_decode($response['content'], true);
		$status = 2;
		if($result['responseCode'] == 0) {
			$status = ($result['msg'] == '已打印' ? 1 : 2);
		}
		return $status;
	}

	function QueryPrinterStatus($printer_sn, $key){
		load()->func('communication');
		$msgInfo = array(
			'sn' => $printer_sn,  
			'key' => $key,
		);
		$http = print_feie_url($printer_sn);
		$posturl = $http . '/FeieServer/queryPrinterStatusAction';
		$response = ihttp_post($posturl, $msgInfo);
		if(is_error($response)) {
			return error(-1, "错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		return $result['msg'];
	}
}
?>