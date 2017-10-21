<?php
defined ( 'IN_IA' ) or exit ( 'Access Denied,your ip is:' . $_SERVER ['REMOTE_ADDR'] . ',We have recorded the source of attack.' );
require_once APP_CLASS_PATH . '/lib/payutil/WxPayMicropayHelper.php';
require_once APP_CLASS_PATH . '/lib/payutil/WxPay.Micropay.config.php';
$changenum = $_GPC ['changenum'];
$uid = mc_openid2uid ( $_W ['fans'] ['from_user'] );
$credit = mc_credit_fetch ( $uid );
$originCredit = $credit ['credit1'];
$result = array (
		"status" => 1,
		"info" => ""
);

if (IS_DEBUG) {
	logging_run ( "[" . $_W ['fans'] ['from_user'] . "]-原始积分:" . var_export ( $credit, true )."--originCredit:".var_export($originCredit,true), 'info', date ( 'Ymd' ) );
	logging_run ( "判断原始积分:" . var_export ( $originCredit <= 0, true ), 'info', date ( 'Ymd' ) );
}

if ($originCredit <= 0) {
	exit ( json_encode ( array (
			"status" => 2,
			"info" => "原有积分不足哦，快去赚取积分吧"
	) ) );
} else if ($originCredit < $changenum) {
	exit ( json_encode ( array (
			"status" => 3,
			"info" => "兑换积分不能大于原有积分哦"
	) ) );
}
if(!IS_PAY){
	exit(json_encode ( array (
			"status" => 8,
			"info" => "兑换测试完成，请查看日志文件"
	) ));
}
load ()->model ( 'account' );
$account = uni_setting ( $_W ['uniacid'] );
if (! empty ( $account ) && ! empty ( $account ['payment'] ['wechat'] )) {
	$mchid = $account ['payment'] ['wechat'] ['mchid'];
	$apikey = $account ['payment'] ['wechat'] ['apikey'];
	$cfg = $this->module ['config'];
	if (empty ( $cfg ['nbfwxpaypath'] )) {
		exit ( json_encode ( array (
				"status" => 4,
				"info" => "公众号未开启支付，请联系管理员处理"
		) ) );
	}
	$cert = json_decode ( $cfg ['nbfwxpaypath'] );
	$dtotal_amount = $changenum * $cfg ['nbfchangemoney'];
	$wpayconfig = new WxPayConf_micropay ( $_W ['account'] ['key'], $mchid, $apikey, $cert->certpath, $cert->keypath );
	$redpk = new Entpayment_redpack ( $wpayconfig );
	$mch_billno = $wpayconfig->mchid . date ( 'Ymd', time () ) . time ();
	$redpk->setParameter ( "mch_billno", $mch_billno ); // 商户订单号，需保证唯一,mch_id+yyyymmdd+10位一天内不能重复的数字
	$redpk->setParameter ( "re_openid", $_W ['fans'] ['from_user'] ); // 用户openid
	$redpk->setParameter ( "send_name", $_W ['account'] ['name'] ); // 发送者名称
	$redpk->setParameter ( "total_amount", $dtotal_amount ); // 发红包，每个红包金额必须大于1元，小于200元
	$redpk->setParameter ( "total_num", 1 );
	$redpk->setParameter ( "wishing", "积分兑换成功啦" );
	$redpk->setParameter ( "act_name", "积分兑换红包" );
	$redpk->setParameter ( "remark", "来自" . $_W ['account'] ['name'] . "的红包" );
	try {
		$dissuccess = 0;
		$dresult = "";
		$redpkresult = $redpk->getResult ();
		if (IS_DEBUG) {
			logging_run ( "给用户:[" . $_W ['fans'] ['from_user'] . "]发送红包操作，发送结果:" . var_export ( $redpkresult, true ), 'info', date ( 'Ymd' ) );
		}
		if ($redpkresult ["return_code"] == "SUCCESS") {
			if (IS_DEBUG) {
				logging_run ( "给用户:[" . $_W ['fans'] ['from_user'] . "]发送红包操作1->调用成功!", 'info', date ( 'Ymd' ) );
			}
			if ($redpkresult ["result_code"] == "FAIL") {
				// 失败
				$dissuccess = 0;
				if ($redpkresult ['err_code'] == "NO_AUTH") {
				} else if ($redpkresult ['err_code'] == "NOTENOUGH") {
				} else if ($redpkresult ['err_code'] == "MONEY_LIMIT") {
				} else if ($redpkresult ['err_code'] == "TIME_LIMITED") {
				} else if ($redpkresult ['err_code'] == "SEND_FAILED") {
				} else if ($redpkresult ['err_code'] == "FATAL_ERROR") {
				} else if ($redpkresult ['err_code'] == "CA_ERROR") {
				} else if ($redpkresult ['err_code'] == "SIGN_ERROR") {
				} else if ($redpkresult ['err_code'] == "SYSTEMERROR") {
				} else if ($redpkresult ['err_code'] == "XML_ERROR") {
				} else if ($redpkresult ['err_code'] == "FREQ_LIMIT") {
				} else if ($redpkresult ['err_code'] == "OPENID_ERROR") {
				} else if ($redpkresult ['err_code'] == "PARAM_ERROR") {
				}
				$dresult = $redpkresult ['err_code'];
				if (IS_DEBUG) {
					logging_run ( "给用户:[" . $_W ['fans'] ['from_user'] . "]发送红包操作2->交易失败!", 'info', date ( 'Ymd' ) );
				}
			} else if ($redpkresult ["result_code"] == "SUCCESS") {
				$dissuccess = 1;
				mc_credit_update ( $uid, 'credit1', - $changenum );
				if (IS_DEBUG) {
					logging_run ( "给用户:[" . $_W ['fans'] ['from_user'] . "]发送红包操作3->交易成功啦!", 'info', date ( 'Ymd' ) );
				}
			}
		} else if ($redpkresult ["return_code"] == "FAIL") {
			$dresult = $redpkresult ['return_msg'];
			if (IS_DEBUG) {
				logging_run ( "给用户:[" . $_W ['fans'] ['from_user'] . "]发送红包操作4->调用接口失败!", 'info', date ( 'Ymd' ) );
			}
		}
		pdo_insert ( $this->paylogtable, array (
				"uniacid" => $_W ["uniacid"],
				"dwnick" => $_W ['fans'] ['tag'] ['nickname'],
				"dopenid" => $_W ['fans'] ['from_user'],
				"dtime" => time (),
				"dcredit" => $changenum,
				"dtotal_amount" => $dtotal_amount,
				"dmch_billno" => $mch_billno,
				"dissuccess" => $dissuccess,
				"dresult" => $dresult
		) );
		if($dissuccess==0){
			exit ( json_encode ( array (
					"status" => 5,
					"info" => "支付出错:".$dresult
			) ) );
		}
		if (IS_DEBUG) {
			logging_run ( "给用户:[" . $_W ['fans'] ['from_user'] . "],昵称：" . $_W ['fans'] ['tag'] ['nickname'] . "发送红包操作5，插入数据库并提交事务", 'info', date ( 'Ymd' ) );
		}
	} catch ( Exception $e ) {
		exit ( json_encode ( array (
				"status" => 5,
				"info" => $e->getMessage ()
		) ) );
	}
} else {
	exit ( json_encode ( array (
			"status" => 4,
			"info" => "公众号未开启支付，请联系管理员"
	) ) );
}
exit ( json_encode ( $result ) );