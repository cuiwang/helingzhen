<?php
/**
 *易福源码网 http://www.efwww.com
 *  *易福源码网 http://www.efwww.com
 */

class XKPayNotify
{
	public static function notify($input)
	{


		WeUtility::logging('info', "美丽微砍价异步通知数据" . $input);

//WeUtility::logging('info',"商户key数据".$kjsetting);
		$notify = new Notify_pub();
		$notify->saveData($input);
		$data = $notify->getData();
		$xkkjsetting = DBUtil::findUnique(DBUtil::$TABLE_XKWKJ_SETTING, array(":appid" => $data['appid']));
		if (empty($data)) {
			$notify->setReturnParameter("return_code", "FAIL");
			$notify->setReturnParameter("return_msg", "参数格式校验错误");
			WeUtility::logging('info', "美丽微砍价回复参数格式校验错误");
			exit($notify->createXml());
		}

		if ($data['result_code'] != 'SUCCESS' || $data['return_code'] != 'SUCCESS') {
			$notify->setReturnParameter("return_code", "FAIL");
			$notify->setReturnParameter("return_msg", "参数格式校验错误");
			WeUtility::logging('info', "美丽微砍价回复参数格式校验错误");
			exit($notify->createXml());
		}
//更新表订单信息

		WeUtility::logging('info', "炫酷通知订单更新");
		if ($notify->checkSign($xkkjsetting['shkey'])) {
			DBUtil::update(DBUtil::$TABLE_XKWJK_ORDER, array("status" => 4, 'notifytime' => TIMESTAMP, 'wxnotify' => $data, 'wxorder_no' => $data['transaction_id']), array("outno" => $data['out_trade_no']));
			$notify->setReturnParameter("return_code", "SUCCESS");
			$notify->setReturnParameter("return_msg", "OK");
			exit($notify->createXml());
		} else {
			$notify->setReturnParameter("return_code", "FAIL");
			$notify->setReturnParameter("return_msg", "签名校验错误");
			WeUtility::logging('info', "签名校验错误");
			exit($notify->createXml());
		}

		WeUtility::logging('info', "微砍价回复数据" . $data);


	}

}



