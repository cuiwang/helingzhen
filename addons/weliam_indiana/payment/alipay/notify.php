<?php
	// +----------------------------------------------------------------------
	// | Copyright (c) 2015-2020 http://www.startingline.com.cn All rights reserved.
	// +----------------------------------------------------------------------
	// | Describe: 支付回调
	// +----------------------------------------------------------------------
	// +----------------------------------------------------------------------
	require '../../../../framework/bootstrap.inc.php';
	require IA_ROOT. '/addons/weliam_indiana/defines.php';
	require WELIAM_INDIANA_INC.'function.php';
	load()->func('communication');
	
	global $_W;
	if (!empty($_POST)) {
		$out_trade_no = $_POST['out_trade_no'];
	    $trade_no = $_REQUEST['trade_no'];				//支付宝交易号
	    $trade_status = $_POST['trade_status'];		//交易状态
	    $subject = $_POST["subject"];				//商品名称
	    $gmt_create = $_POST["gmt_create"];			//交易创建时间
	    $gmt_payment = $_POST["gmt_payment"];		//交易付款时间
	    $buyer_email = $_POST["buyer_email"];		//卖家Email
	    $seller_email = $_POST["seller_email"];		//买家Email
	    $price = $_POST["price"];					//商品单价
	    $quantity = $_POST["quantity"];				//购买数量
	    $total_fee = $_POST["total_fee"];			//交易金额
	    $body = $_POST["body"];						//商品描述
	    $notify_time = $_POST["notify_time"];		//通知时间
	    if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
	    	$bodys = explode('|', $body);
	    	$uniacid = $bodys[1];
			$openid = $bodys[0];
			$change = array(
				'fee'=>$total_fee,
				'status'=>'1'
			);
			pdo_update("core_paylog",$change,array('tid'=>$out_trade_no,'uniacid'=>$uniacid,'openid'=>$openid));
			m('credit')->updateCredit2($openid,$uniacid,$total_fee,'支付宝支付操作');
			$data['num'] = $total_fee;
			$data['type'] = $bodys[2];
			$data['status'] = 1;
			pdo_update('weliam_indiana_rechargerecord', $data, array('ordersn' => $out_trade_no));
			if($data['type'] == 0){
				ihttp_request($_W["siteroot"].'core/api/buycode.api.php', array('uniacid' => $uniacid,'tid'=>$out_trade_no,'openid'=>$openid),array('Content-Type' => 'application/x-www-form-urlencoded'),1);
			}
   		}
    	echo "success"; // 请不要修改或删除
	}else{
		echo "error"; // 请不要修改或删除
	}
?>