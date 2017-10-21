<?php
class Message {
	/**
	 *
	 * 模板消息
	 *
	 * 模板消息请求URL
	 *
	 *
	 */
	public function WX_request($data, $url) {
		$curl = curl_init();
		// 启动一个CURL会话
		curl_setopt($curl, CURLOPT_URL, $url);
		// 要访问的地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		// 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		// 从证书中检查SSL加密算法是否存在
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		// 模拟用户使用的浏览器
		if ($data != null) {
			curl_setopt($curl, CURLOPT_POST, 1);
			// 发送一个常规的Post请求
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			// Post提交的数据包
		}
		curl_setopt($curl, CURLOPT_TIMEOUT, 300);
		// 设置超时限制防止死循环
		curl_setopt($curl, CURLOPT_HEADER, 0);
		// 显示返回的Header区域内容
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		// 获取的信息以文件流的形式返回
		$info = curl_exec($curl);
		// 执行操作
		if (curl_errno($curl)) {
			echo 'Errno:' . curl_getinfo($curl);
			//捕抓异常
			//	           dump(curl_getinfo($curl));
		}
		return $info;
	}

	/**
	 *
	 * 模板消息
	 *
	 * 发货提醒模板消息
	 *
	 *
	 */
	public static function send_success($orderno, $openid, $express, $expressn, $the, $url1, $url2) {
		global $_W;
		load() -> func('communication');
		load() -> model('account');
		$m_send = $the['m_send'];
		$send = $the['send'];
		$send_remark = $the['send_remark'];
		$content = "亲，您的商品已发货!!!";
		$msg_json = '{
                       	"touser":"' . $openid . '",
                       	"template_id":"' . $m_send . '",
                       	"url":"' . $url2 . '",
                       	"topcolor":"#173177",
                       	"data":{
                           	"first":{
                               "value":"' . $send . '",
                               "color":"#173177"
                           	},
                           	"keyword1":{
								"value":"' . $orderno . '",
                           		"color":"#173177"
							},
                           	"keyword2":{
								 "value":"' . $express . '",
                           	     "color":"#173177"
							},
							"keyword3":{
								"value":"' . $expressn . '",
                           	    "color":"#173177"
							},
                           	"remark":{
                               "value":"' . $send_remark . '",
                               "color":"#173177"
                           	}
                       	}
                   	}';
		$res = self::WX_request($msg_json, $url1);
		return $res;
	}

	/**
	 *
	 * 模板消息
	 *
	 * 退款模板消息
	 *
	 *
	 */
	public static function refund($openid, $price, $the, $url1, $url2) {
		global $_W;
		$m_ref = $the['m_ref'];
		$ref = $the['ref'];
		$ref_remark = $the['ref_remark'];
		$content = "您已成退款成功";
		$msg_json = '{
                       	"touser":"' . $openid . '",
                       	"template_id":"' . $m_ref . '",
                       	"url":"' . $url2 . '",
                       	"topcolor":"#173177",
                       	"data":{
                           	"first":{
                               "value":"' . $ref . '",
                               "color":"#173177"
                           	},
                           	"reason":{
								"value":"拼团失败",
                           		"color":"#173177"
							},
                           	"refund":{
								"value":"' . $price . '元",
                           	    "color":"#173177"
							},
                           	"remark":{
                               "value":"' . $ref_remark . '",
                               "color":"#173177"
                           	}
                       	}
                   	}';
		$res = self::WX_request($msg_json, $url1);
		return $res;
	}
	public static function part_refund($openid, $price, $the, $url1, $url2) {
		global $_W;
		$m_ref = $the['m_ref'];
		$ref = $the['ref'];
		$ref_remark = $the['ref_remark'];
		$content = "您已成退款成功";
		$msg_json = '{
                       	"touser":"' . $openid . '",
                       	"template_id":"' . $m_ref . '",
                       	"url":"' . $url2 . '",
                       	"topcolor":"#173177",
                       	"data":{
                           	"first":{
                               "value":"团购成功，团长部分返现",
                               "color":"#173177"
                           	},
                           	"reason":{
								"value":"团长优惠,部分退款",
                           		"color":"#173177"
							},
                           	"refund":{
								"value":"' . $price . '元",
                           	    "color":"#173177"
							},
                           	"remark":{
                               "value":"' . $ref_remark . '",
                               "color":"#173177"
                           	}
                       	}
                   	}';
		$res = self::WX_request($msg_json, $url1);
		return $res;
	}

}
?>