<?php
		global $_W, $_GPC;
		$this->getuserinfo();
		$this->checkpay($_W['openid']);
	  	$url=$_W['siteurl'];
	  	$tuan_id = intval($_GPC['tuan_id']);
		load()->model('mc');
		$payreslut = $_GPC['payreslut'];//支付成功弹出框
	  	if(!empty($tuan_id)){
		    //取得该团所有订单
		    $orders = pdo_fetchall("SELECT * FROM " . tablename('wz_tuan_order') . " WHERE uniacid ='{$_W['uniacid']}' and tuan_id = {$tuan_id} and is_tuan = 1 and status in(1,2,3,4,6,7) ORDER BY starttime asc");
		    foreach($orders as$key =>$value){
		    	if($value['address']=='虚拟'){
		    		$orders[$key]['avatar'] = $value['openid'];
		    	}else{
					$fans =  $this->getfansinfo($value['openid']);
					if (!empty($fans)) {
						$avatar = $fans['avatar'];
						$nickname=$fans['nickname'];
					}
		    		$orders[$key]['avatar'] = $avatar;
		    	}
		    }
		    //取团长订单$order
		    $order = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_order') . " WHERE  tuan_id = {$tuan_id} and tuan_first=1 and uniacid={$_W['uniacid']}");
		   //自己的订单，若没有参团则$myorder为空
		    $myorder = pdo_fetch("SELECT * FROM " . tablename('wz_tuan_order') . " WHERE openid = '{$_W['openid']}' and tuan_id = {$tuan_id} and status in(1,2,3,4,6,7)");
		  	//团状态
		  	$tuaninfo = pdo_fetch("SELECT * FROM".tablename('wz_tuan_group')."WHERE groupnumber = {$tuan_id}");
		  	if (empty($order['g_id'])) {
		  		echo "<script>alert('组团信息不存在！');location.href='".$this->createMobileUrl('index')."';</script>";
		  		exit;
		  	}else{
		  		$goods = pdo_fetch("SELECT * FROM".tablename('wz_tuan_goods')."WHERE id = {$order['g_id']}");
			    $endtime = $tuaninfo['endtime'];
			    $time=time(); /*当前时间*/
			    $lasttime2 = $endtime - $time;//剩余时间（秒数）
			    $lasttime = $goods['endtime'];
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
			session_start();
			$_SESSION['goodsid']=$goods['id'];
			$_SESSION['tuan_id']=$tuan_id;
			$_SESSION['groupnum']=$tuaninfo['neednum'];
		  	include $this->template('group');
	  	}else{
	  		echo "<script>alert('参数错误');location.href='".$this->createMobileUrl('index')."';</script>";
	  	}
?>