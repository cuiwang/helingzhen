<?php
	global $_W,$_GPC;
	$this->getuserinfo();
	load()->func('communication');
	$openid = $_W['openid'];
	$groupnum=intval($_GPC['groupnum']);//团购人数	
	
	session_start();
	//新开一团
	if(!empty($_GPC['newtuan'])){
		$_SESSION['goodsid'] = '';
		$_SESSION['tuan_id'] = '';
		$_SESSION['groupnum']='';
	}
	//新开一团
	if(empty($_SESSION['goodsid'])){
		$_SESSION['goodsid'] = $_GPC['id'];
	}
	if(empty($_SESSION['tuan_id'])){
		$_SESSION['tuan_id'] = $_GPC['tuan_id'];
	}
	if($groupnum){
		$_SESSION['groupnum']=$groupnum;
	}else{
		$groupnum=$_SESSION['groupnum'];
	}
	$id= $_SESSION['goodsid'];
	$tuan_id = $_SESSION['tuan_id'];
	//查询这个团是否支付成功参加
	if($tuan_id){
			$myorder = pdo_fetch("select * from ".tablename('wz_tuan_order')."where openid='{$_W['openid']}' and tuan_id='{$tuan_id}' and status in(1,2,3,4,6,7)");
			if(!empty($myorder)){
				$tuan_id = '';
			}
		}	
	if (!empty($id)) {
		$goods = pdo_fetch("select * from".tablename('wz_tuan_goods')."where id = '{$id}'");
		$adress = pdo_fetch("select * from ".tablename('wz_tuan_address')."where openid='$openid' and status=1");
      	if(!empty($groupnum)){
      		if($groupnum>1){
      			$price = $goods['gprice'];
      			$is_tuan=1;
      			if(empty($tuan_id)){
      				$tuan_first = 1;
      			}else{
      				$tuan_first = 0;
      			}
      		}else{
      			$price = $goods['oprice'];
      			$is_tuan=0;
      			$tuan_first = 0;
      		}
			
      	}
    }
	
	/*判断地区邮费*/
			$freight=0;
			$adress_fee = pdo_fetch("select * from ".tablename('wz_tuan_address')."where openid='$openid' and status=1");
			$carrier = false;
			$carrier_list = array();
			$dispatch = false;
			$dispatch_list = pdo_fetchall("select id,dispatchname,dispatchtype,firstprice,firstweight,secondprice,secondweight,areas,carriers from " . tablename("wz_tuan_dispatch") . " WHERE  uniacid = {$_W['uniacid']} and id='{$goods['yunfei_id']}' order by displayorder desc");
			foreach ($dispatch_list as &$d) {
				$areas = unserialize($d['areas']);
					if (!empty($adress_fee)) {
						if (is_array($areas) && count($areas) > 0) {
							foreach ($areas as $area) {
								$citys = explode(";", $area['citys']);
								if (in_array($adress_fee['city'], $citys)) {
									$dispatch=true;
									$freight = $area['firstprice'];
									break;
								} 
							} 
						} 
					}
					if(!$dispatch){
						$freight = $d['firstprice'];
					}
			} 
			
			unset($d);
		if($goods['is_hexiao']==1){
			$freight=0;
		}
	/*判断地区邮费*/
	if (checksubmit('submit')) {
		$str='';
		$chars = '0123456789';
		for ($i = 0; $i < 8; $i++) {
			$str .= $chars[mt_rand(0, strlen($chars) - 1)];
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'gnum' => 1,
			'openid' => $openid,
            'ptime' =>'',//支付成功时间
			'orderno' => date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99)),
			'pay_price' => $price+$freight,
			'goodsprice'=>$price,
			'freight'=>$freight,
			'status' => 0,//订单状态：0未支,1支付，2待发货，3已发货，4已签收，5已取消，6待退款，7已退款
			'addressid' => $adress['id'],
			'addresstype' => $adress['type'],//1公司2家庭
			'addname' => $adress['cname'],
			'mobile' => $adress['tel'],
			'address' => $adress['province'].$adress['city'].$adress['county'].$adress['detailed_address'],
			'g_id' => $id,
			'tuan_id'=>$tuan_id,
			'is_tuan'=>$is_tuan,
			'tuan_first' => $tuan_first,
			'starttime'=>TIMESTAMP,
			'remark'=>$_GPC['remark'],
			'endtime'=>$goods['endtime'],
			'is_hexiao'=>$goods['is_hexiao'],
			'hexiaoma'=>$str,
			'credits'=>$goods['credits'],
			'is_usecard'=>0,
			'createtime' => TIMESTAMP
		);
		
		pdo_insert('wz_tuan_order', $data);
		if($goods['is_hexiao']==1){
			/*二维码*/
				require_once IA_ROOT . '/addons/wz_tuan/source/qrcode.class.php';
				$createqrcode =  new creat_qrcode();
				$createqrcode->creategroupQrcode($data['orderno']);
				
		}
		$orderid = pdo_insertid();
		if(empty($tuan_id)){
				$groupnumber = $orderid;
				if($data['is_tuan']==1){
				$starttime = time();
				$endtime = $starttime+$goods['endtime']*3600;
				$data2 = array(
					'uniacid' => $_W['uniacid'],
					'groupnumber' => $groupnumber,
					'groupstatus' => 3,//订单状态,1组团失败，2组团成功，3,组团中
					'goodsid' => $goods['id'],
					'goodsname'=>$goods['gname'],
					'neednum' => $goods['groupnum'],
					'lacknum'=>  $goods['groupnum'],
					'starttime'=>$starttime,
					'endtime'=>$endtime
				);
				pdo_insert('wz_tuan_group', $data2);
				pdo_update('wz_tuan_order',array('tuan_id' => $orderid), array('id' => $orderid));
			}
		}
		header("location: " .  $this->createMobileUrl('pay', array('orderid' => $orderid)));exit;	
	}
			$share_data = $this->module['config'];
			if($share_data['share_imagestatus'] == ''){
				$shareimage = $goods['gimg'];
			}
			if($share_data['share_imagestatus'] == 1){
				$shareimage = $goods['gimg'];
			}
			if($share_data['share_imagestatus'] == 2){
				$result = mc_fetch($_W['member']['uid'], array('credit1', 'credit2','avatar','nickname'));
				$shareimage = $result['avatar'];
			}
			if($share_data['share_imagestatus'] == 3){
				$shareimage =$this->module['config']['share_image'];
			}
	include $this->template('orderconfirm');