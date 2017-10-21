<?php
if($weid == $_W['uniacid']){
	if (! empty ( $_W ['openid'] ) && intval ( $_W ['account'] ['level'] ) >= 3) {
		$accObj = WeiXinAccount::create ($_W['account']);
 		$userinfo = $accObj->fansQueryInfo ($_W['openid']);
 		$issub = $userinfo ['subscribe'];
	}else{
		$issub = 0;
	}
}else{
	$issub = intval($_GPC['issub']);
}

$replyid = intval ( $_GPC ['id'] );
$reply = pdo_fetch ( "SELECT * FROM " . tablename ($this->table_reply) . " WHERE id = :replyid ORDER BY `id` DESC", array (
		':replyid' => $replyid 
));
$counts = pdo_fetch( "select count(id) as a from ".tablename($this->table_user)." where replyid='{$replyid}' and uniacid='{$_W ['uniacid']}'");
//echo "select count(id) as a from ".tablename($this->table_user)." where replyid='{$replyid}' and uniacid='{$_W ['uniacid']}'";
if (!empty($reply )) {
	if ($reply ['starttime'] > time ()) {
		$error = '本次活动尚未开始,敬请期待！';
		include $this->template ( 'no' );
		exit ();
	} elseif ($reply ['endtime'] < time () || $reply ['status'] == 0) {
		$error = '本次活动已经结束，请关注我们后续的活动！';
		include $this->template ( 'no' );
		exit ();
	} elseif ($reply ['status'] == 2) {
		$error = '本次活动暂停中';
		include $this->template ( 'no' );
		exit ();
	} else {
		if (! empty ( $_GPC ['popenid'] )) {
			$log = pdo_fetch ( "SELECT * FROM " . tablename ($this->table_log) . " WHERE replyid = :replyid and popenid = :popenid and sopenid = :sopenid", array (
					':replyid' => $replyid,
					':popenid' => $_GPC['popenid'],
					':sopenid' => $_W['openid'] 
			) );
			if (empty ( $log ) && $_GPC ['popenid'] != $_W ['openid']) {

				// 互相采集
				$temp1 = pdo_fetch ('SELECT * FROM ' . tablename ( $this->table_log ) . ' WHERE popenid=:popenid AND sopenid=:sopenid AND replyid=:replyid AND uniacid=:uniacid', array (
						':sopenid' => $_GPC ['popenid'],
						':popenid' => $_W ['openid'],
						':replyid' => $replyid,
						':uniacid' => $_W ['uniacid'] 
				));

				// 只能提供一次
				$temp2 = pdo_fetchColumn('SELECT COUNT(1) FROM '.tablename($this->table_log). ' WHERE sopenid=:sopenid AND replyid=:replyid AND uniacid=:uniacid',array(
						':sopenid' => $_W ['openid'],
						':replyid' => $replyid,
						':uniacid' => $_W ['uniacid'] 
				));

				$data = array (
						'uniacid' => $_W ['uniacid'],
						'replyid' => $replyid,
						'popenid' => $_GPC ['popenid'],
						'sopenid' => $_W ['openid'] 
				);
				// exit($reply['mutual']);
				if( $reply['mutual'] == '3'){
					
					if((!$temp1) && ($temp2 < 1)){
						pdo_query ( "UPDATE " . tablename ( $this->table_user ) . " set hits=hits+1 where openid=:openid and replyid=:replyid ", array (
								':openid' => $_GPC ['popenid'],
								':replyid' => $replyid 
						) );
						pdo_insert ( $this->table_log, $data );

					}
				} else if ($reply ['mutual'] == '1') {
					if(!$temp1){
						pdo_query ( "UPDATE " . tablename ( $this->table_user ) . " set hits=hits+1 where openid=:openid and replyid=:replyid ", array (
								':openid' => $_GPC ['popenid'],
								':replyid' => $replyid 
						) );
						pdo_insert ( $this->table_log, $data );

					}
				} else if ($reply ['mutual'] == '2') {
					if($temp2 < 1){
						pdo_query ( "UPDATE " . tablename ( $this->table_user ) . " set hits=hits+1 where openid=:openid and replyid=:replyid ", array (
								':openid' => $_GPC ['popenid'],
								':replyid' => $replyid 
						) );
						pdo_insert ( $this->table_log, $data );

					}
				} else {
					pdo_query ( "UPDATE " . tablename ( $this->table_user ) . " set hits=hits+1 where openid=:openid and replyid=:replyid ", array (
							':openid' => $_GPC ['popenid'],
							':replyid' => $replyid 
					));
					pdo_insert ( $this->table_log, $data );

				}

				
			}
		}
		$user = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_user ) . " WHERE replyid = :replyid and openid = :openid", array (
				':replyid' => $replyid,
				':openid' => $_W ['openid'] 
		) );
		if (empty ( $user )) {
			$data = array (
					'uid' => $fan ['uid'],
					'uniacid' => $_W ['uniacid'],
					'replyid' => $replyid,
					'openid' => $_W ['openid'],
					'status' => '1' 
			);
			pdo_insert($this->table_user, $data);
			$user = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_user ) . " WHERE replyid = :replyid and openid = :openid", array (
					':replyid' => $replyid,
					':openid' => $_W ['openid'] 
			) );
			$isfirst = 1;
		} else {
			$isfirst = 0;
		}
		
		$prizes_temp = pdo_fetchall ( "SELECT * FROM " . tablename ( $this->table_prize ) . " WHERE rid = :rid AND status = :status ORDER BY `displayorder` desc,`id` DESC", array (
				':rid' => $reply ['rid'] ,
				':status' => 1
		) );
		$prizes = array ();
		foreach ( $prizes_temp as $item ) {
			$prizes [$item ['id']] = array (
					'id' => $item ['id'],
					'prizename' => $item ['prizename'],
					'prizeurl' => $item ['prizeurl'],
					'prizethumb' => $item ['prizethumb'],
					'prizecount' => $item ['prizecount'],
					'prizerest' => $item ['prizerest'],
					'prizeneed' => $item ['prizeneed'],
					'share' => $item ['share'] 
			);
		}
		$sytime = $this->getEndTime ( $reply ['endtime'] );

		// 添加
		$IPaddress='';
		if (isset($_SERVER)){
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
				$IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
			} else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$IPaddress = $_SERVER["HTTP_CLIENT_IP"];
			} else {
				$IPaddress = $_SERVER["REMOTE_ADDR"];
			}
		} else {
			if (getenv("HTTP_X_FORWARDED_FOR")){
				$IPaddress = getenv("HTTP_X_FORWARDED_FOR");
			} else if (getenv("HTTP_CLIENT_IP")) {
				$IPaddress = getenv("HTTP_CLIENT_IP");
			} else {
				$IPaddress = getenv("REMOTE_ADDR");
			}
		}

		$cityid = $this->getCityId($IPaddress);
		
		// 加入获取红包==================
		if($cityid==false){
			// 获取不到地址，只能找所有没有地区限制的
			$bonus = pdo_fetchAll('SELECT * FROM '.tablename($this->table_bonus).' WHERE location=:location AND uniacid=:uniacid AND status=1',array(':location'=>0,':uniacid'=>$_W['uniacid']));
		}else{
			// 获取所有红包
			$bonus = pdo_fetchAll('SELECT * FROM '.tablename($this->table_bonus).' WHERE uniacid=:uniacid AND status=1',array(':uniacid'=>$_W['uniacid']));
		}

		// 添加地区筛选
		foreach ($bonus as $key => $item) {
			// 区域限制
			if($item['location']==1){
				if(!empty($item['area'])){
					$temp = strstr($item['area'],$cityid);
					if(!$temp){
						unset($bonus[$key]);
						continue;
					}
				}else{
					unset($bonus[$key]);
					continue;
				}
			}
			// 限制活动
			if($item['islimit']==1){
				if(!empty($item['limit'])||$item['limit']!=null){
					if(!in_array($replyid,json_decode('['.$item['limit'].']'))){
						unset($bonus[$key]);
						continue;
					}
				}else{
					unset($bonus[$key]);
					continue;
				}
			}
		}

		// 如果领取的是红包
		if(!empty($user['sn']) && strstr($user['sn'],'bonus')){
			$user['bonus'] = pdo_fetch('SELECT * FROM '.tablename($this->table_bonus).' WHERE id=:bonusid AND uniacid=:uniacid',array(':bonusid'=>$user['prizeid'],':uniacid'=>$_W['uniacid']));
		}
		//========================

		// 加入获取卡券==================
		$sql = "SELECT * FROM ".tablename('uni_settings')." WHERE `uniacid`=:uniacid";
    	$unisetting  =  pdo_fetch($sql,array('uniacid'=>$_W['uniacid']));

    	// 获取粉丝公众号ID
    	if(!empty($unisetting['oauth'])) {
        	$temp = unserialize($unisetting['oauth']);
        	$weid = empty($temp['account']) ? 0 : $temp['account'];
    	}
    	if($weid == $_W['uniacid']){

			if($cityid==false){
				// 获取不到地址，只能找所有没有地区限制的
				$coupon = pdo_fetchAll('SELECT * FROM '.tablename($this->table_coupon).' WHERE location=:location AND uniacid=:uniacid AND status=1',array(':location'=>0,':uniacid'=>$_W['uniacid']));
			}else{
				// 获取所有卡券
				$coupon = pdo_fetchAll('SELECT * FROM '.tablename($this->table_coupon).' WHERE uniacid=:uniacid AND status=1',array(':uniacid'=>$_W['uniacid']));
			}

			// 添加地区筛选
			foreach ($coupon as $key => $item) {
				// 区域限制
				if($item['location']==1){
					if(!empty($item['area'])){
						$temp = strstr($item['area'],$cityid);
						if(!$temp){
							unset($coupon[$key]);
							break;
						}
					}else{
						unset($coupon[$key]);
						break;
					}
				}
				// 限制活动
				if($item['islimit']==1){
					if(!empty($item['limit'])||$item['limit']!=null){
						if(!in_array($replyid,json_decode('['.$item['limit'].']'))){
							unset($coupon[$key]);
							break;
						}
					}else{
						unset($coupon[$key]);
						break;
					}
				}
			}
		}


		// 如果领取的是卡券
		if(!empty($user['sn']) && strstr($user['sn'],'coupon')){
			$user['coupon'] = pdo_fetch('SELECT * FROM '.tablename($this->table_coupon).' WHERE id=:couponid AND uniacid=:uniacid',array(':couponid'=>$user['prizeid'],':uniacid'=>$_W['uniacid']));
		}


		//========================

		// 添加区域限制==========
		if($reply['location']=='1' && empty($reply['locationtype'])){
			$temp = strstr($reply['area'],$cityid);
			if(empty($temp)){
			    if(!empty($reply['zdy_url'])){
			      header("location:".$reply['zdy_url']);
			      exit();
			    }
				$error = '当前地区不支持该活动';
				include $this->template ('no');
				exit();
			}
		}
		// =====================

		if($reply['share'] == 1){
			// 分享信息配置
			$mark = PHP_INT_MAX;
			$markname = '';
			foreach ($bonus as $value) {
				if($mark > $value['bonusneed'] && $value['bonusneed'] > $user['hits'] && $value['bonusrest']!=0 && $value['share']==1){
					$mark = $value['bonusneed'];
					$markname = $value['bonusname'];
				}
			}
			foreach ($coupon as $value) {
				if($mark > $value['couponneed'] && $value['couponneed'] > $user['hits'] && $value['couponrest']!=0 && $value['share']==1){
					$mark = $value['couponneed'];
					$markname = $value['couponname'];
				}
			}
			
			foreach ($prizes as $value) {
				if($mark > $value['prizeneed'] && $value['prizeneed'] > $user['hits'] && $value['prizerest']!=0 && $value['share']==1){
					$mark = $value['prizeneed'];
					$markname = $value['prizename'];
				}
			}

			if(!empty($markname)){
				$reply['share_description'] = '还需集'.($mark-$user['hits']).'个阅读，即可获得'.$markname;
			}

		}

		include $this->template ( 'index' );
	}
} else {
	$error = '活动不存在';
	include $this->template ('no');
}
?>