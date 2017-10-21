<?php
	$orderno = $_GPC['orderno'];	 //订单现在的状态
	$openid = $_W['openid'];	//用户的openid
	
	
	//取消订单的操作
	if (!empty($orderno)) {
	
			$sql = 'SELECT * FROM '.tablename('tg_order').' WHERE orderno=:id ';
			$params = array(':id'=>$orderno);
			$order = pdo_fetch($sql, $params);
			if(empty($order)){
				message('未找到指定订单.'.$orderno, $this->createMobileUrl('myorder'));
				$tip = 888;
		
		}else{
			$sql2 = 'SELECT * FROM '.tablename('tg_goods').' WHERE id=:gid ';			$params2 = array(':gid'=>$order['g_id']);			$goods = pdo_fetch($sql2, $params2);			$sql3 = 'SELECT * FROM '.tablename('tg_address').' WHERE id=:aid ';			$params3 = array(':aid'=>$order['addressid']);			$address = pdo_fetch($sql3, $params3);			$add = $address['province '].$address['city '].$address['county'].$address['detailed_address'];
		$ret = pdo_update('tg_order', array('status'=>9), array('orderno'=>$orderno));		$tip = 9999;				$content = "取消订单通知";			load()->func('communication');			load()->model('account');			$access_token = WeAccount::token();			$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token."";						$url2="";//点击模板详情跳转的地址url2			$time = date("Y-m-d H:i:s",time());			$data['touser'] = trim($_W['openid']);			$openid = trim($_W['openid']);			$msg_json= '{                       "touser":"'.$openid.'",                       "template_id":"f3EMOh59tLWHGUGwSrpl_HYTlQCHjuKZVGOuLGTSlH8",                       "url":"'.$url2.'",                       "topcolor":"#FF0000",                       "data":{                           "first":{                               "value":"\n'.$content.'\n\n",                               "color":"#000000"                           },                           "orderProductPrice":{								"value":"'.$order['price'].'\n\n",                           		"color":"#000000"							},                           "orderProductName":{								 "value":"'.$goods['gname'].'\n\n",                           	     "color":"#000000"							},							 "orderAddress":{								 "value":"'.$add.'\n\n",                           	     "color":"#000000"							},							 "orderName":{								 "value":"'.$order['orderno '].'\n\n",                           	     "color":"#000000"							},                           "remark":{                               "value":"\n\n您的订单已取消！",                               "color":"#0099FF"                           }                       }                   }' ;				   include './message.php';				   $sendmessage = new WX_message();				   $res=$sendmessage->WX_request($url,$msg_json);
		
		}
	}
	
	
	$this->doMobileMyOrder();
?>