<!--确认收货的函数-->
<?php
	$orderno = $_GPC['orderno'];	 //订单编号
	$openid = $_W['openid'];	//用户的openid
	//取消订单的操作
	if (!empty($orderno)) {
		$sql = 'SELECT * FROM '.tablename('tg_order').' WHERE orderno=:orderno ';
		$params = array(':orderno'=>$orderno);
		$order = pdo_fetch($sql, $params);
		if(empty($order)){
			message('未找到指定订单', $this->createMobileUrl('orderdetails', array('id'=>$orderno)));
			$tip = "failure";
		}else{
			$ret = pdo_update('tg_order', array('status'=>3), array('orderno'=>$orderno));
			$tip = "sucess";	
		}
	}
	$url_9080 = $this->createMobileUrl('orderdetails', array('id'=>$orderno));  
    Header("Location: $url_9080");  
?>