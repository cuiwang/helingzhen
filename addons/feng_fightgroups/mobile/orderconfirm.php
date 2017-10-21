<?php
    load()->func('communication');
	$openid = $_W['openid'];
	$groupnum=intval($_GPC['groupnum']);//团购人数	
	$id = intval($_GPC['id']);//商品id
	$tuan_id = intval($_GPC['tuan_id']);
	$all = array(
		'groupnum' =>$groupnum, 
		'id'=> $id
	);
	if (!empty($id)) {
		$goods = pdo_fetch("select * from".tablename('tg_goods')."where id = $id");
		//地址
		$adress = pdo_fetch("select * from ".tablename('tg_address')."where openid='$openid' and status=1");
      	if(!empty($groupnum)){
      		if($groupnum>1){
      			$price = $goods['gprice'];
      			$is_tuan=1;
      			if(empty($tuan_id)){
      				$tuan_first = 1;
      			}else{
      				$tuan_first = 0;
      			}
				$success = 1;
      		}else{
      			$price = $goods['oprice'];
      			$is_tuan=0;
      			$tuan_first = 0;
				$success = 0;
      		}
      	}
    }
	if (checksubmit('submit')) {
		$data = array(
			'uniacid' => $_W['uniacid'],
			'gnum' => 1,
			'openid' => $openid,
            'ptime' =>'',//支付成功时间
			'orderno' => date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99)),
			'price' => $price+$goods['freight'],
			'status' => 0,//订单状态，-1取消状态，0普通状态，1为已付款，2为已发货，3为成功
			'addressid' => $adress['id'],
			'addname' => $adress['cname'],
			'mobile' => $adress['tel'],
			'address' => $adress['province'].$adress['city'].$adress['county'].$adress['detailed_address'],
			'g_id' => $id,
			'tuan_id'=>$tuan_id,
			'is_tuan'=>$is_tuan,
			'tuan_first' => $tuan_first,
			'starttime'=>TIMESTAMP,
			'endtime'=>$goods['endtime'],
			'success'=>$success,
			'createtime' => TIMESTAMP
		);
		pdo_insert('tg_order', $data);
		$orderid = pdo_insertid();
		if(empty($tuan_id)){
			pdo_update('tg_order',array('tuan_id' => $orderid), array('id' => $orderid));
		}
		header("location: " .  $this->createMobileUrl('pay', array('orderid' => $orderid)));	
	}
	include $this->template('confirm');