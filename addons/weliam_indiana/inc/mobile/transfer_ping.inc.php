<?php
// 
//  transfer.inc.php
//  <project>
//  余额提现
//  Created by haoran on 2016-01-20.
//  Copyright 2016 haoran. All rights reserved.
// 
	global $_W,$_GPC;
	require IA_ROOT. '/addons/weliam_indiana/pay/init.php';
	// api_key、app_id 请从 [Dashboard](https://dashboard.pingxx.com) 获取
	$api_key = $this->module['config']['Secret_Key'];
	$app_id = $this->module['config']['App_ID'];
	$openid = m('user') -> getOpenid();
	$num = $_GPC['num'];
	if(empty($openid)){
		echo '1'; //非法登陆
		exit;
	}
	$reply = m('member') -> getInfoByOpenid($openid);
	if($num > $reply['credit2']){
		echo '2'; //余额不足
		exit;
	}
	//订单号
	$order_no = 'TX'.date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
	\Pingpp\Pingpp::setApiKey($api_key);
	try {
	    $tr = \Pingpp\Transfer::create(
	        array(
	            'amount'    => $num*100,
	            'order_no'  => $order_no,
	            'currency'  => 'cny',
	            'channel'   => 'wx_pub',
	            'app'       => array('id' => $app_id),
	            'type'      => 'b2c',
	            'recipient' => $openid,
	            'description' =>'夺宝币提现',
	            'extra' => array('user_name' => 'User Name', 'force_check' => false)
	        )
	    );
		//修改余额
		$left = 0 - $num;
		m('credit')->updateCredit2($openid,$_W['uniacid'],$left);
		//创建记录
		$data = array(
			'uniacid' => $_W['uniacid'],
			'openid' => $openid,
			'createtime' => time(),
			'number' => $num,
			'status' => 1,
			'type' => 2,
			'order_no' => $order_no
		);
		pdo_insert('weliam_indiana_withdraw',$data);
		file_put_contents(WELIAM_INDIANA."/params20.log", var_export($left, true).PHP_EOL, FILE_APPEND);
	    echo $tr;
	} catch (\Pingpp\Error\Base $e) {
	    header('Status: ' . $e->getHttpStatus());
	    echo($e->getHttpBody());
	}
