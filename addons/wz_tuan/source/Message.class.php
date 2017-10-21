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
	 * 支付成功模板消息
	 *
	 *
	 */
	public function pay_success($openid, $orderno, $goodsname, $the, $url1, $url2) {
		global $_W;
		load() -> func('communication');
		load() -> model('account');
		$tuan_id = pdo_fetch("select * from" . tablename('wz_tuan_order') . "where orderno = :orderno", array(':orderno' => $orderno));
		$pay_suc = $the -> module['config']['pay_suc'];
		$pay_remark = $the -> module['config']['pay_remark'];
		$m_pay = $the -> module['config']['m_pay'];
		//支付成功模板消息提醒
		$content = "";
		if ($tuan_id['tuan_first'] == 1) {
			$content .= $pay_suc;
		} elseif ($tuan_id['tuan_first'] != 1 && $tuan_id['is_tuan'] == 1) {
			$content .= "您已成功付款参团，组团成功才会享受优惠哦";
		} else {
			$content .= "您已成功付款.";
		}

		$msg_json = '{
               	"touser":"' . $openid . '",
               	"template_id":"' . $m_pay . '",
               	"url":"' . $url2 . '",
               	"topcolor":"#173177",
               	"data":{
                   	"first":{
                       "value":"' . $content . '",
                       "color":"#173177"
                   	},
                   	"orderProductName":{
						"value":"' . $goodsname . '",
                   		"color":"#173177"
					},
                   	"orderMoneySum":{
						"value":"' . $tuan_id['price'] . '",
                   	    "color":"#173177"
					},
                   	"remark":{
                       "value":"\n点击查看订单详情",
                       "color":"#173177"
                   	}
               	}
           	}';
		$res = self::WX_request($msg_json, $url1);
	}

	/**
	 *
	 * 模板消息
	 *
	 * 组团成功模板消息
	 *
	 *
	 */
	public static function group_success($tuan_id, $the, $url1, $url2) {
		global $_W;
		load() -> func('communication');
		load() -> model('account');
		load() -> model('mc');
		$m_tuan = $the -> module['config']['m_tuan'];
		$tuan_suc = $the -> module['config']['tuan_suc'];
		$tuan_remark = $the -> module['config']['tuan_remark'];
		$content = "组团成功!!!";
		$alltuan = pdo_fetchall("select * from" . tablename('wz_tuan_order') . "where tuan_id = '{$tuan_id}' and status in(1,2,3,4)");
		foreach ($alltuan as $num => $all) {
			$order = pdo_fetch("select * from" . tablename('wz_tuan_order') . "where orderno = '{$all['orderno']}'");
			$goods = pdo_fetch("select * from" . tablename('wz_tuan_goods') . "where id = '{$order['g_id']}'");
			$tuan_first_order = pdo_fetch("select * from" . tablename('wz_tuan_order') . "where tuan_first=1 and tuan_id='{$tuan_id}'");
			$profile = pdo_fetch("select * from" . tablename('mc_mapping_fans') . "where openid = '{$tuan_first_order['openid']}'");
			$msg_json = '{
	                   	"touser":"' . $order['openid'] . '",
	                   	"template_id":"' . $m_tuan . '",
	                   	"url":"' . $url2 . '",
	                   	"topcolor":"#173177",
	                   	"data":{
	                       	"first":{
	                           "value":"' . $tuan_suc . '",
	                           "color":"#173177"
	                       	},
	                       	"Pingou_ProductName":{
								"value":"' . $goods['gname'] . '",
	                       		"color":"#173177"
							},
	                       	"Weixin_ID":{
								"value":"' . $profile['nickname'] . '",
	                       	    "color":"#173177"
							},
	                       	"remark":{
	                           "value":"\n' . $tuan_remark . '",
	                           "color":"#173177"
	                       	}
	                   	}
	               	}';
			$res = self::WX_request($msg_json, $url1);
		}
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
		$m_send = $the -> module['config']['m_send'];
		$send = $the -> module['config']['send'];
		$send_remark = $the -> module['config']['send_remark'];
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
		$m_ref = $the -> module['config']['m_ref'];
		$ref = $the -> module['config']['ref'];
		$ref_remark = $the -> module['config']['ref_remark'];
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
								"value":"购买失败",
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
		$m_ref = $the -> module['config']['m_ref'];
		$ref = $the -> module['config']['ref'];
		$ref_remark = $the -> module['config']['ref_remark'];
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
	public static function cancelorder($openid, $price, $goodsname, $orderno, $the, $url1, $url2) {
		global $_W;
		$m_cancle = $the -> module['config']['m_cancle'];
		$cancle = $the -> module['config']['cancle'];
		$cancle_remark = $the -> module['config']['cancle_remark'];
		$content = "取消订单通知";
		load() -> func('communication');
		load() -> model('account');
		$msg_json = '{
                   "touser":"' . $openid . '",
                   "template_id":"' . $m_cancle . '",
                   "url":"' . $url2 . '",
                   "topcolor":"#173177",
                   "data":{
                       "first":{
                           "value":"' . $cancle . '",
                           "color":"#173177"
                       },
                       "keyword5":{
							"value":"' . $price . '元",
                       		"color":"#173177"
						},
                       "keyword3":{
							 "value":"' . $goodsname . '",
                       	     "color":"#173177"
						},
						 "keyword2":{
							 "value":"' . $_W['uniaccount']['name'] . '",
                       	     "color":"#173177"
						},
						 "keyword1":{
							 "value":"' . $orderno . '",
                       	     "color":"#173177"
						},
						 "keyword4":{
							 "value":"1",
                       	     "color":"#173177"
						},
                       "remark":{
                           "value":"' . $cancle_remark . '",
                           "color":"#173177"
                       }
                   }
               }';
		$res = self::WX_request($msg_json, $url1);
		return $res;
	}

}
?>