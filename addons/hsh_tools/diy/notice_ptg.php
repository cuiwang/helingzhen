<?php

/*
 * 给客服中心发送短信
 * 
 */

function sendMsgForService($sendData, $noticeSetting) {
	$noticeList = json_decode(htmlspecialchars_decode($noticeSetting['notice_list']), true);
	$noticeOption = json_decode(htmlspecialchars_decode($noticeSetting['notice_option']), true);
	$noticeFieldSetting = json_decode(htmlspecialchars_decode($noticeSetting['field_setting']), true);
	$smsHelper = new SmsHelper();
	$hshApi = new WxHelper();
	/* 生成订单描述字符串--start */
	$valueData = $sendData;
	$fieldValueStr = "";
	foreach ($noticeFieldSetting as $field => $setting) {
		if ($setting['list_show'] == "1") {
			if ($fieldValueStr == "") {
				$fieldValueStr .= "【" . $setting['prompt'] . "】" . $valueData[$field];
			} else {
				$fieldValueStr .= "，【" . $setting['prompt'] . "】" . $valueData[$field];
			}
		}
	}
	/* 生成订单描述字符串--end */
	$wechatTemplateData = array(
		'first' => "呼叫客服中心，有新的订单",
		'tradeDateTime' => date("Y-m-d H:i:s", time()),
		'orderType' => "预约",
		'customerInfo' => "客户电话：" . $sendData['tel'],
		'orderItemName' => "描述",
		'orderItemData' => $fieldValueStr,
		'remark' => "自己人，废话不多说！",
	);
	$smsTemplateData = array();
	$smsTemplateData[0] = $fieldSetting['title'];
	$smsTemplateData[1] = $fieldValueStr;
	/* foreach($sendData as $val){
	  $smsTemplateData[]=$val;
	  } */


	foreach ($noticeList as $key) {
		if ($noticeOption[$key]) {
			if ($sendData['tel']) {
				$telDialUrl = "http://api.hshcs.com/dial.php?tel=" . $sendData['tel'];
			} else {
				$telDialUrl = "";
			}
			$sendResult = array();
			switch ($noticeOption[$key]['type']) {
				case "1" :
					$sendResult['tel'] = $smsHelper->sendTemplateSMS($noticeOption[$key]['tel'], $smsTemplateData, intval($noticeSetting['sms_template_id']));
					break;
				case "2" :
					$sendResult['wechat'] = json_decode($hshApi->sendTempletMSG($noticeOption[$key]['openid'], "fMEwMMty0lCMi5zFy-b447ZEIUmk9tRxJfazOD1tO6o", $wechatTemplateData, $telDialUrl), true);
					break;
				case "3" :
					$sendResult['tel'] = $smsHelper->sendTemplateSMS($noticeOption[$key]['tel'], $smsTemplateData, intval($noticeSetting['sms_template_id']));
					$sendResult['wechat'] = json_decode($hshApi->sendTempletMSG($noticeOption[$key]['openid'], "fMEwMMty0lCMi5zFy-b447ZEIUmk9tRxJfazOD1tO6o", $wechatTemplateData, $telDialUrl), true);
					break;
			}
		}
	}
}

function sendMsgForClient($openid, $sendData, $noticeSetting) {
	$noticeFieldSetting = json_decode(htmlspecialchars_decode($noticeSetting['field_setting']), true);
	$hshApi = new WxHelper();
	$fieldValueStr = "";
	foreach ($noticeFieldSetting as $field => $setting) {
		if ($setting['list_show'] == "1") {
			if ($fieldValueStr == "") {
				$fieldValueStr .= "【" . $setting['prompt'] . "】" . $valueData[$field];
			} else {
				$fieldValueStr .= "，【" . $setting['prompt'] . "】" . $valueData[$field];
			}
		}
	}
	$wechatTemplateData = array(
		'first' => "恭喜您，已经下单成功，稍候撇脱哥会与您确认订单信息，请耐心等待！",
		'tradeDateTime' => date("Y-m-d H:i:s", time()),
		'orderType' => "预约",
		'customerInfo' => "您的电话：" . $sendData['tel']."请仔细核对该电话,否则撇脱哥联系不到您哦~",
		'orderItemName' => "详细内容",
		'orderItemData' => $sendData['describe'],
		'remark' => "再次感谢您使用赤水好生活如有疑问，请与本平台联系，或致电（0851-22853123），祝您愉快",
	);
	$sendResult['wechat'] = json_decode($hshApi->sendTempletMSG($openid, "fMEwMMty0lCMi5zFy-b447ZEIUmk9tRxJfazOD1tO6o", $wechatTemplateData, "http://m.hshcs.com/"), true);
}
