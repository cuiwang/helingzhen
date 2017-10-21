<?php 
	global $_W,$_GPC;
	//引入ping++SDK的init.php
	require IA_ROOT. '/addons/weliam_indiana/pay/init.php';
	$api_key = $this->module['config']['Secret_Key'];
	$app_id = $this->module['config']['App_ID'];
	$success = $_GPC['success'];
	isetcookie('_cookie_uniacid', base64_encode(json_encode($_W['uniacid'])), 600, true);
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

		$success_url = $_W['siteroot']."/addons/weliam_indiana/payment/pingxx/return.php";
		
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
	            // success_url 和 cancel_url 在本地测试不要写 localhost ，请写 127.0.0.1。URL 后面不要加自定义参数
	            'success_url' => $success_url,
	            'cancel_url' => $success_url
	        );
	        break;
	    case 'bfb_wap':
	        $extra = array(
	            'result_url' => $success_url,// 百度钱包同步回调地址
	            'bfb_login' => true// 是否需要登录百度钱包来进行支付
	        );
	        break;
		    case 'wx_pub':
		        $extra = array(
		            'open_id' => $openid
		        );
		        break;
		    case 'jdpay_wap':
		        $extra = array(
		            'success_url' => $success_url,// 支付成功页面跳转路径
		            'fail_url'=> $success_url,// 支付失败页面跳转路径
		            /**
		            *token 为用户交易令牌，用于识别用户信息，支付成功后会调用 success_url 返回给商户。
		            *商户可以记录这个 token 值，当用户再次支付的时候传入该 token，用户无需再次输入银行卡信息
		            */
		            'token' => '' // 选填
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