<?php
	$orderid = $_GPC['orderid'];
	if (empty($orderid)) {
        message('抱歉，参数错误！', '', 'error');
    }else{
    	if(!empty($_GPC['topay'])){
	        $orderone = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE id = {$orderid}");
			$data = array(
				'uniacid' => $orderone['uniacid'],
				'gnum' => 1,
				'openid' => $orderone['openid'],
	            'ptime' =>'',//支付成功时间
				'orderno' => date('md') . random(4, 1),
				'price' => $orderone['price'],
				'status' => 0,//订单状态，-1取消状态，0普通状态，1为已付款，2为已发货，3为成功
				'addressid' => $orderone['addressid'],
				'g_id' => $orderone['g_id'],
				'tuan_id'=>$orderone['tuan_id'],
				'is_tuan'=>$orderone['is_tuan'],
				'tuan_first' => $orderone['tuan_first'],
				'endtime'=>$orderone['endtime'],
				'createtime' => TIMESTAMP
			);
			pdo_insert('tg_order', $data);
			$id = pdo_insertid();
			$order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE id ={$id}");
			$params['tid'] = $order['orderno'];
			$params['user'] = $_W['fans']['from_user'];
			$params['fee'] = $order['price'];
			$params['title'] = $_W['account']['name'];
			$params['ordersn'] = $order['orderno'];
			//删除相同用户相同tuan_id下未支付的订单
			pdo_delete('tg_order', array('id'=>$orderid));
			pdo_delete('tg_order', array('openid'=>''));
    	}else{
    		$order = pdo_fetch("SELECT * FROM " . tablename('tg_order') . " WHERE id =$orderid");
			//判断是否人数已够
			$goods = pdo_fetch("SELECT * FROM".tablename('tg_goods')."WHERE id = {$order['g_id']}");
			$sql= "SELECT * FROM".tablename('tg_order')."where tuan_id=:tuan_id and status = 1 and pay_type <> 0";
   		    $params= array(':tuan_id'=>$order['tuan_id']);
    		$alltuan = pdo_fetchall($sql, $params);
    		$item = array();
    		foreach ($alltuan as $num => $all) {
    			$item[$num] = $all['id'];
   			}
			$n = $goods['groupnum'] - count($item);
			if($n==0){
				echo "<script>alert('该团已完成');location.href='".$this->createMobileUrl('index')."';</script>";
				exit;
			}  
    		$params['tid'] = $order['orderno'];
    		$params['user'] = $_W['fans']['from_user'];
    		$params['fee'] = $order['price'];
    		$params['title'] = $_W['account']['name'];
    		$params['ordersn'] = $order['orderno'];
    	}
    }
	$params['module'] = "feng_fightgroups";
	if($this->module['config']['status'] == 1) {
		include $this->template('pay');
	}else{
		include $this->template('ptpay');
	}
?>