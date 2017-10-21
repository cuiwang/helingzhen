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
	file_put_contents(WELIAM_INDIANA."/params20.log", var_export($openid, true).PHP_EOL, FILE_APPEND);
	\Pingpp\Pingpp::setApiKey($api_key);
	try {
	    $tr = \Pingpp\Transfer::create(
	        array(
	            'amount'    => 100,
	            'order_no'  => '123456d7890',
	            'currency'  => 'cny',
	            'channel'   => 'wx_pub',
	            'app'       => array('id' => $app_id),
	            'type'      => 'b2c',
	            'recipient' => $openid,
	            'description' =>'testing',
	            'extra' => array('user_name' => 'User Name', 'force_check' => false)
	        )
	    );
	    echo $tr;
	} catch (\Pingpp\Error\Base $e) {
	    header('Status: ' . $e->getHttpStatus());
	    echo($e->getHttpBody());
	}
