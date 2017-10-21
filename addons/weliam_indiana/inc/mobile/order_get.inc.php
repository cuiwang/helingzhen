<?php
	global $_GPC,$_W;
	session_start();
	$id2 = $_GPC['id2']; 
	$openid = m('user') -> getOpenid();
	$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'list';
	if ($operation == 'list') {
		$_SESSION['pid'] = '';
		if(empty($id2)){
			$period = pdo_fetchall("select id,nickname,goodsid,periods,code,endtime,zong_codes,status,partakes,period_number from".tablename('weliam_indiana_period')."where uniacid={$_W['uniacid']} and openid = '{$openid}' and status>=3 order by id desc limit 0,5");
			if($period){
				foreach ($period as $key => $value) {
					$goods = m('goods')->getGoods($value['goodsid']);
					$period[$key]['title'] = $goods['title'];
					$period[$key]['picarr'] = $goods['picarr'];
				}
		}
		if(!empty($period[4]['id'])){
			$id2 = $period[4]['id'];
		}else{
			$id2 = '-1';
		}
			include $this->template('order_get');
			exit;
		}else{
			$period = pdo_fetchall("select id,nickname,goodsid,periods,code,endtime,zong_codes,status,partakes,period_number from".tablename('weliam_indiana_period')."where uniacid={$_W['uniacid']} and openid = '{$openid}' and status>=3 and id<'{$id2}' order by id desc limit 0,5");
			if($period){
				foreach ($period as $key => $value) {
					$goods = m('goods')->getGoods($value['goodsid']);
					$period[$key]['title'] = $goods['title'];
					$period[$key]['picarr'] = $goods['picarr'];
					
					$row = array(
						'id' => $period[$key]['id'],
						'nickname' => $period[$key]['nickname'],
						'goodsid' => $period[$key]['goodsid'],
						'periods' => $period[$key]['periods'],
						'code' => $period[$key]['code'],
						'endtime' => date('Y-m-d H:i:s',$period[$key]['endtime']),
						'zong_codes' => $period[$key]['zong_codes'],
						'status' => $period[$key]['status'],
						'partakes' => $period[$key]['partakes'],
						'period_number' => $period[$key]['period_number'],
						'title' => $period[$key]['title'],
						'picarr' => tomedia($period[$key]['picarr']),
					);
					$info[] = $row;
				}	
		}
						
		for( $i=5;empty($period[$i]['id'])&&$i>-1;$i--){
			$id2 = $period[$i-1]['id'];
		}
		if(empty($id2)){
			$id2 = -1;
		}
		$data = array(
			'success'=>$id2,
			'list'=>$info,	
				);
		
		echo json_encode($data);
		}
	}elseif ($operation == 'detail') {
		$_SESSION['pid'] = '';
		$id = $_GPC['id'];
		$period = pdo_fetch("select id,goodsid,periods,nickname,avatar,openid,partakes,code,endtime,confirmtime,taketime,realname,mobile,address,express,expressn,sendtime,zong_codes,period_number,canyurenshu,status,scale,recordid from".tablename('weliam_indiana_period')."where uniacid={$_W['uniacid']} and id = '{$id}'");
		$goods = pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " WHERE uniacid = '{$_W['uniacid']}' and id ='{$period['goodsid']}'");
		if(empty($_GPC['aid'])){
			$address = m('address')->getAddress($openid);
		}else{
			$sql = "SELECT * FROM " . tablename('weliam_indiana_address') . " where id='{$_GPC['aid']}' ";
			$address = pdo_fetch($sql);
		}
			include $this->template('order_get');
			exit;
	}elseif ($operation == 'confirm') {
		$aid = $_GPC['aid'];
		$pid = $_GPC['pid'];
		$remark = $_GPC['remark'];
		$sql = "SELECT * FROM " . tablename('weliam_indiana_address') . " where id='{$aid}' ";
		$address = pdo_fetch($sql);
		$data = array(
			'status'=> 4,
			'confirmtime'=> TIMESTAMP,
			'realname'=> $address['username'],
			'mobile'=> $address['mobile'],
			'address'=> $address['province'].$address['city'].$address['district'].$address['address'],
			'comment' => $remark
		);
		if(pdo_update('weliam_indiana_period', $data, array('id'=>$pid))){
			die(json_encode('true'));
		}else{
			die(json_encode('false'));
		}
		exit;
	}elseif ($operation == 'confirmm') {
		$pid = $_GPC['pid'];
		$data = array(
			'status'=> 6,
			'taketime'=> TIMESTAMP
		);
		if(pdo_update('weliam_indiana_period', $data, array('id'=>$pid))){
			message('确认收货成功！', $this -> createMobileUrl('order_get', array('op' => 'detail','id' => $pid)), 'success');
		}else{
			message('确认收货失败！', $this -> createMobileUrl('order_get', array('op' => 'detail','id' => $pid)), 'success');
		}
		exit;
	}
?>