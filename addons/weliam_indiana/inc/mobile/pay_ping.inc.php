<?php 
	global $_W,$_GPC;
	//引入ping++SDK的init.php
	require IA_ROOT. '/addons/weliam_indiana/pay/init.php';
	$api_key = $this->module['config']['Secret_Key'];
	$app_id = $this->module['config']['App_ID'];
	$success = $_GPC['success'];
	/*
	 * 支付验证
	 * */
	if(empty($success)){
		// 此处为 Content-Type 是 application/json 时获取 POST 参数的示例
		$input_data = json_decode(file_get_contents('php://input'), true);
		file_put_contents(WELIAM_INDIANA."/filename.log", var_export($input_data, true).PHP_EOL, FILE_APPEND);
		if (empty($input_data['channel']) || empty($input_data['amount'])) {
		    echo 'channel or amount is empty';
		    exit();
		}
		$channel = strtolower($input_data['channel']);
		$amount = $input_data['amount'];
		$orderNo = $input_data['order_no'];
		$cztype =  	$input_data['cztype'];
		$openid = $openid = m('user') -> getOpenid();
		//支付成功传参
		$params = array();
		$params['fee'] = $amount;
		$params['type'] = $channel;
		$params['tid'] = $orderNo;
		if($cztype ==1){
			$success_url = 'http://'.$_SERVER['HTTP_HOST']."/addons/weliam_indiana/template/mobile/person.html";
		}else{
			$success_url = 'http://'.$_SERVER['HTTP_HOST']."/addons/weliam_indiana/template/mobile/default/ailpay_end.html";
		}
		
		
		//判定账号openid是否存在
		if(empty($openid)){
			json_encode('用户openid参数不正确');
			exit;
		}
		/**
		 * $extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array()。
		 * 以下 channel 仅为部分示例，未列出的 channel 请查看文档 https://pingxx.com/document/api#api-c-new
		 */
		$extra = array();
		switch ($channel) {
		    case 'alipay_wap':
		        $extra = array(
		            'success_url' => $success_url,
		            'cancel_url' => 'http://example.com/fail'
		        );
		        break;
		    case 'bfb_wap':
		        $extra = array(
		            'result_url' => $success_url,
		            'bfb_login' => true
		        );
		        break;
		    case 'wx_pub':
		        $extra = array(
		            'open_id' => $openid
		        );
		        break;
		    case 'jdpay_wap':
		        $extra = array(
		            'success_url' => $success_url,
		            'fail_url'=> 'http://example.com/fail',
		            'token' => 'dsafadsfasdfadsjuyhfnhujkijunhaf'
		        );
		        break;
		}
		// 设置 API Key
		\Pingpp\Pingpp::setApiKey($api_key);
		try {
			$ch = \Pingpp\Charge::create(
	        array(
	            'subject'   => '一元夺宝支付',
	            'body'      => $cztype,
	            'amount'    => $amount*100,
	            'order_no'  => $orderNo,
	            'currency'  => 'cny',
	            'extra'     => $extra,
	            'channel'   => $channel,
	            'client_ip' => $_W['clientip'],
	            'app'       => array('id' => $app_id)
	        )
	    );
	    echo $ch;        
		} catch (\Pingpp\Error\Base $e) {
		    header('Status: ' . $e->getHttpStatus());
		    // 捕获报错信息
		    echo $e->getHttpBody();
		}
		
	}else{
		//支付成功
		
	}
	
	?>