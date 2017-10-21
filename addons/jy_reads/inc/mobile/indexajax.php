<?php
$operation = in_array ( $_GPC ['op'], array (
		'default',
		'input',
		'ticket',
		'ticketbonus',
		'ticketcoupon',
		'gethxurl',
		'checking',
		'getcount'
) ) ? $_GPC ['op'] : 'default';


if ($operation == 'location') {

	die (json_encode ( array (
			'result' => false,
			'msg' => '你是傻逼吗？请回答4！'
	)));
}

if ($operation == 'default') {
	die ( json_encode ( array (
			'result' => false,
			'msg' => '你是傻逼吗？请回答4！'
	) ) );
}
// 返回输入框
if ($operation == 'input') {
	
	$prizeid = intval ( $_GPC ['prizeid'] );
	
	if($_GPC['type']=='bonus'){

		$replyid = intval ( $_GPC ['replyid'] );
		$bonus = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_bonus ) . ' WHERE id=:id', array (
			':id' => $prizeid 
		) );
		if ($prizeid && $bonus && $bonus ['bonusrest']) {
		
			$user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE openid=:openid AND replyid=:replyid', array (
					':openid' => $_W ['openid'],
					':replyid' => $replyid 
			) );
			if ($user ['hits'] < $bonus ['bonusneed']) {
				die ( json_encode ( array (
						'result' => false,
						'msg' => '您的阅读数不够哟！' 
				) ) );
			}
			if (empty ( $bonus ['info'] )) {
				die ( json_encode ( array (
						'result' => true,
						'msg' => '' 
				) ) );
			} else {
				$temp = str_replace ( array (
						'[',
						']' 
				), array (
						'(',
						')' 
				), $bonus ['info'] );
				$properties = pdo_fetchall ( 'SELECT * FROM ' . tablename ( $this->table_property ) . ' WHERE propertykey IN ' . $temp );
				$str = '';
				foreach ( $properties as $property ) {
					$str .= '<input class="inputMsg" type="text" name="' . $property ['propertykey'] . '" placeholder="' . $property ['propertyvalue'] . '" data-name="' . $property ['propertyvalue'] . '" />';
				}
				die ( json_encode ( array (
						'result' => true,
						'msg' => $str 
				) ) );
			}
		} else {
			die ( json_encode ( array (
					'result' => false,
					'msg' => '不存在红包或者红包已领完，请刷新页面！' 
			) ) );
		}
	}

	if($_GPC['type']=='coupon'){

		$replyid = intval ( $_GPC ['replyid'] );
		$coupon = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_coupon ) . ' WHERE id=:id', array (
			':id' => $prizeid 
		) );
		if ($prizeid && $coupon && $coupon ['couponrest']) {
		
			$user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE openid=:openid AND replyid=:replyid', array (
					':openid' => $_W ['openid'],
					':replyid' => $replyid 
			) );
			if ($user ['hits'] < $coupon ['couponneed']) {
				die ( json_encode ( array (
						'result' => false,
						'msg' => '您的阅读数不够哟！' 
				) ) );
			}
			if (empty ( $coupon ['info'] )) {
				die ( json_encode ( array (
						'result' => true,
						'msg' => '' 
				) ) );
			} else {
				$temp = str_replace ( array (
						'[',
						']' 
				), array (
						'(',
						')' 
				), $coupon ['info'] );
				$properties = pdo_fetchall ( 'SELECT * FROM ' . tablename ( $this->table_property ) . ' WHERE propertykey IN ' . $temp );
				$str = '';
				foreach ( $properties as $property ) {
					$str .= '<input class="inputMsg" type="text" name="' . $property ['propertykey'] . '" placeholder="' . $property ['propertyvalue'] . '" data-name="' . $property ['propertyvalue'] . '" />';
				}
				die ( json_encode ( array (
						'result' => true,
						'msg' => $str 
				) ) );
			}
		} else {
			die ( json_encode ( array (
					'result' => false,
					'msg' => '不存在卡券或者卡券已领完，请刷新页面！' 
			) ) );
		}
	}

	if($_GPC['type']=='prize'){

		$prize = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_prize ) . ' WHERE id=:id', array (
			':id' => $prizeid 
		) );
		if ($prizeid && $prize && $prize ['prizerest']) {
		
			$user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE openid=:openid AND replyid=:replyid', array (
					':openid' => $_W ['openid'],
					':replyid' => $prize ['replyid'] 
			) );
			if ($user ['hits'] < $prize ['prizeneed']) {
				die ( json_encode ( array (
						'result' => false,
						'msg' => '您的阅读数不够哟！' 
				) ) );
			}
			if (empty ( $prize ['info'] )) {
				die ( json_encode ( array (
						'result' => true,
						'msg' => '' 
				) ) );
			} else {
				$temp = str_replace ( array (
						'[',
						']' 
				), array (
						'(',
						')' 
				), $prize ['info'] );
				$properties = pdo_fetchall ( 'SELECT * FROM ' . tablename ( $this->table_property ) . ' WHERE propertykey IN ' . $temp );
				$str = '';
				foreach ( $properties as $property ) {
					$str .= '<input class="inputMsg" type="text" name="' . $property ['propertykey'] . '" placeholder="' . $property ['propertyvalue'] . '" data-name="' . $property ['propertyvalue'] . '" />';
				}
				die ( json_encode ( array (
						'result' => true,
						'msg' => $str 
				) ) );
			}
		} else {
			die ( json_encode ( array (
					'result' => false,
					'msg' => '不存在该奖项或者奖项已兑完，请刷新页面！' 
			) ) );
		}
	}

}
// 提交个人数据
if ($operation == 'ticket') {
	$data = explode ( '|', $_GPC ['json'] );
	if (! $_GPC ['prizeid'] || ! $_W ['openid']) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '参数错误' 
		) ) );
	}
	$prize = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_prize ) . ' WHERE id=' . $_GPC ['prizeid'] );
	$user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE openid=:openid AND replyid=:replyid', array (
			':openid' => $_W ['openid'],
			':replyid' => $prize ['replyid'] 
	) );
	$reply = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_reply ) . ' WHERE id=' . $prize ['replyid'] );
	if (! $prize || ! $user) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '不存在奖项或用户' 
		) ) );
	} else if (! empty ( $_GPC ['json'] ) && (count ( $data ) % 2) != 0) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '参数错误' 
		) ) );
	} else {
		$sn = date ( 'md' ) . random ( 4, 1 );
		if ($user ['status'] != '1') {
			$data ['result'] = false; // 已领取过奖励
			$data ['msg'] = '抱歉，您已领取过奖励，不能重复领取';
		} elseif ($user ['hits'] < $prize ['prizeneed']) {
			$data ['result'] = false; // 阅读数不够
			$data ['msg'] = '抱歉，您的阅读数不够，不能领取该奖励';
		} elseif ($prize ['prizerest'] < 1) {
			$data ['result'] = false; // 奖品不够不够
			$data ['msg'] = '抱歉，该奖品已经被领取完了';
		} else {
			$temp = array (
					'status' => '2',
					'sn' => $sn,
					'prizeid' => $prize ['id'] 
			);
			if (empty ( $_GPC ['json'] )) {
				$temp ['userinfo'] = json_encode ( $result );
			} else {
				$result = array ();
				for($i = 0; $i < count ( $data ) / 2; $i ++) {
					$result [$data [$i * 2]] = $data [$i * 2 + 1];
				}
				$temp ['userinfo'] = json_encode ( $result );
			}
			pdo_update ( $this->table_user, $temp, array (
					'id' => $user ['id'] 
			) );
			pdo_update ( $this->table_prize, array (
					'prizerest' => $prize ['prizerest'] - 1 
			), array (
					'id' => $prize ['id'] 
			) );
			$media = tomedia ( $prize ['prizethumb'] );
			$data ['result'] = true;
			$data ['msg'] = <<<EOF
<div class="kaquan txtAC" onclick="javascript:hx(this);" data-prize="{$sn}">
	<div class="font18 gray">恭喜您获得{$prize['prizename']}</div>
	<div class="kaquan-img">
		<img src="{$media}"/>
		<span class="kaquan-btn white greenBg">兑换</span>
	</div>
	<div class="font12 gray">兑奖码：{$sn}</div>
</div>
EOF;
		}
		die ( json_encode ( $data ) );
	}
}

// 提交个人数据，获取bonus，false提示出错信息，true跳转到红包页
if ($operation == 'ticketbonus') {

	// 添加时间限制
	$timelimit = strtotime(date('Y-m-d', time())) + 8*60*60 + 10;
	if( time() < $timelimit){
		die ( json_encode ( array (
				'result' => false,
				'msg' => '当前时间段不支持红包领取'
		) ) );
	}

	$replyid = $_GPC['replyid'];
	$reply = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_reply ) . ' WHERE id=' . $replyid );

	$data = explode ( '|', $_GPC ['json'] );
	
	// 没有bonusid 或者不存在openid
	if (! $_GPC ['prizeid'] || ! $_W ['openid']) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '参数错误' 
		) ) );
	}

	// 获取红包
	$bonus = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_bonus ) . ' WHERE id=' . $_GPC ['prizeid'] );

	// 获取用户信息
	$user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE openid=:openid AND replyid=:replyid', array (
			':openid' => $_W ['openid'],
			':replyid' => $replyid 
	) );

	if (! $bonus || ! $user) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '不存在奖项或用户' 
		) ) );
	} else if (! empty ( $_GPC ['json'] ) && (count ( $data ) % 2) != 0) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '参数错误' 
		) ) );
	} else {
		$sn = 'bonus-' . date ( 'md' ) . random ( 4, 1 );
		if ($user ['status'] != '1') {
			$data ['result'] = false; // 已领取过奖励
			$data ['msg'] = '抱歉，您已领取过奖励，不能重复领取';
		} elseif ($user ['hits'] < $bonus ['bonusneed']) {
			$data ['result'] = false; // 阅读数不够
			$data ['msg'] = '抱歉，您的阅读数不够，不能领取该奖励';
		} elseif ($bonus ['bonusrest'] < 1) {
			$data ['result'] = false; // 奖品不够不够
			$data ['msg'] = '抱歉，该奖品已经被领取完了';
		} else {
			// 更新用户表
			$temp = array (
					'status' => '2',
					'sn' => $sn,
					'prizeid' => $bonus ['id'] 
			);
			if (empty ( $_GPC ['json'] )) {
				$temp ['userinfo'] = json_encode ( $result );
			} else {
				$result = array ();
				for($i = 0; $i < count ( $data ) / 2; $i ++) {
					$result [$data [$i * 2]] = $data [$i * 2 + 1];
				}
				$temp ['userinfo'] = json_encode ( $result );
			}
			pdo_update ( $this->table_user, $temp, array (
					'id' => $user ['id'] 
			) );

			// 更新Bonus表
			pdo_update ( $this->table_bonus, array (
					'bonusrest' => $bonus ['bonusrest'] - 1 
			), array (
					'id' => $bonus ['id'] 
			) );
			$data ['result'] = true;
			$data ['msg'] = $_W ['siteroot'] .'app/'. substr( $this->createMobileUrl('bonus',array('sn'=>$sn)),2 ) ;
		}
		die ( json_encode ( $data ) );
	}
}

// 提交个人数据，获取coupon，false提示出错信息，true跳转到卡券页
if ($operation == 'ticketcoupon') {

	$replyid = $_GPC['replyid'];
	$reply = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_reply ) . ' WHERE id=' . $replyid );

	$data = explode ( '|', $_GPC ['json'] );
	
	// 没有couponid 或者不存在openid
	if (! $_GPC ['prizeid'] || ! $_W ['openid']) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '参数错误' 
		) ) );
	}

	// 获取红包
	$coupon = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_coupon ) . ' WHERE id=' . $_GPC ['prizeid'] );

	// 获取用户信息
	$user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE openid=:openid AND replyid=:replyid', array (
			':openid' => $_W ['openid'],
			':replyid' => $replyid 
	) );

	if (! $coupon || ! $user) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '不存在奖项或用户' 
		) ) );
	} else if (! empty ( $_GPC ['json'] ) && (count ( $data ) % 2) != 0) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '参数错误' 
		) ) );
	} else {
		$sn = 'coupon-' . date ( 'md' ) . random ( 4, 1 );
		if ($user ['status'] != '1') {
			$data ['result'] = false; // 已领取过奖励
			$data ['msg'] = '抱歉，您已领取过奖励，不能重复领取';
		} elseif ($user ['hits'] < $coupon ['couponneed']) {
			$data ['result'] = false; // 阅读数不够
			$data ['msg'] = '抱歉，您的阅读数不够，不能领取该奖励';
		} elseif ($coupon ['couponrest'] < 1) {
			$data ['result'] = false; // 奖品不够不够
			$data ['msg'] = '抱歉，该奖品已经被领取完了';
		} else {
			// 更新用户表
			$temp = array (
					'status' => '2',
					'sn' => $sn,
					'prizeid' => $coupon ['id'] 
			);
			if (empty ( $_GPC ['json'] )) {
				$temp ['userinfo'] = json_encode ( $result );
			} else {
				$result = array ();
				for($i = 0; $i < count ( $data ) / 2; $i ++) {
					$result [$data [$i * 2]] = $data [$i * 2 + 1];
				}
				$temp ['userinfo'] = json_encode ( $result );
			}
			pdo_update ( $this->table_user, $temp, array (
					'id' => $user ['id'] 
			) );

			// 更新Bonus表
			pdo_update ( $this->table_coupon, array (
					'couponrest' => $coupon ['couponrest'] - 1 
			), array (
					'id' => $coupon ['id'] 
			) );
			$data ['result'] = true;
			$data ['msg'] = $_W ['siteroot'] .'app/'. substr( $this->createMobileUrl('coupon',array('sn'=>$sn)),2 ) ;
		}
		die ( json_encode ( $data ) );
	}
}


if ($operation == 'gethxurl') {
	//
	$issub = intval ( $_GPC ['issub'] );
	if (empty ( $_GPC )) {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '兑奖二维码将在关注后显示哟 >_<' 
		) ) );
	}
	$sn = $_GPC ['sn'];
	$userid = intval ( $_GPC ['userid'] );
	$user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE id=:id', array (
			':id' => $userid 
	) );
	if ($user) {
		$reply = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_reply ) . ' WHERE id=:id', array (
				':id' => $user ['replyid'] 
		) );
		// 是否关注才可以领奖
		if (empty ( $issub ) && $reply ['follow']) {
			die ( json_encode ( array (
					'result' => false,
					'msg' => '抱歉系统找不到您的信息，您可以尝试关注先该公众号 = =!' 
			) ) );
		}
		$url = $_W ['siteroot'] .'app/'. substr ( $this->createMobileUrl ( 'Verify', array (
				'sn' => $sn,
				'userid' => $userid 
		) ), 2 );
		die ( json_encode ( array (
				'result' => true,
				'msg' => $url 
		) ) );
	} else {
		die ( json_encode ( array (
				'result' => false,
				'msg' => '找不到你了，你可以刷新试试 = =!' 
		) ) );
	}
}
// 轮询查看
if ($operation == 'checking') {
	$status = intval ( $_GPC ['status'] );
	$userid = intval ( $_GPC ['userid'] );
	$user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE id=:id', array (
			':id' => $userid 
	) );
	if (in_array ( $status, array (
			1,
			2 
	) )) {
		if ($user ['status'] == '3') {
			die ( json_encode ( array (
					'result' => true 
			) ) );
		} else {
			die ( json_encode ( array (
					'result' => false,
					'msg' => $user ['status'] . $userid 
			) ) );
		}
	} else {
		die ( json_encode ( array (
				'result' => false,
				'msg' => 'cuole' 
		) ) );
	}
}

// 获取当前用户阅读数
if ($operation == 'getcount') {
	$userid = intval ( $_GPC ['userid'] );
	$user = pdo_fetch ( 'SELECT * FROM ' . tablename ( $this->table_user ) . ' WHERE id=:id', array (
			':id' => $userid 
	) );
	$hits = intval ( $user ['hits'] );
	if (! empty ( $hits )) {
		die ( json_encode ( array (
				'result' => true,
				'msg' => $hits 
		) ) );
	} else {
		die ( json_encode ( array (
				'result' => false, 
				'msg' => 'network error'
		) ) );
	}
}

?>
