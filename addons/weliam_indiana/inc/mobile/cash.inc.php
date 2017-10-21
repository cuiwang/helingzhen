<?php 
defined('IN_IA') or exit('Access Denied');
global $_GPC,$_W;
$moduels = uni_modules();
$type = trim($_GPC['type']);
$params = @json_decode(base64_decode($_GPC['params']), true);
/*echo '<pre>';
print_r($params);
print_r($type);
exit;*/
$rechargerecordnum = pdo_fetchcolumn("select num from".tablename('weliam_indiana_rechargerecord')."where uniacid = :uniacid and ordersn = :ordersn",array(':uniacid' => $_W['uniacid'],':ordersn' => $params['tid']));
$params['fee'] = $rechargerecordnum;
if($params['tid'] == '' || $params['fee'] < 1){
	echo '非法操作,如果此操作对我方造成重大影响，我方将追究你的责任';
	exit;
}
/*echo "<pre>";print_r($params);exit;*/
if(empty($params) || !array_key_exists($params['module'], $moduels)) {
	message('访问错误.');
}
$setting = uni_setting($_W['uniacid'], 'payment');
$dos = array();
///////////////////二次开发/////////////////////////////////////
if(!empty($setting['payment']['yunpay']['switch'])) {
	$dos[] = 'yunpay';
}
//////////////////////////////////////////////////////////
$do = $_GET['do'];
$type = in_array($type, $dos) ? $type : '';
if(empty($type)) {
	message('支付方式错误,请联系商家', '', 'error');
}
if(!empty($type)) {
	$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `uniacid`=:uniacid AND `module`=:module AND `tid`=:tid';
	$pars  = array();
	$pars[':uniacid'] = $_W['uniacid'];
	$pars[':module'] = $params['module'];
	$pars[':tid'] = $params['tid'];
	$log = pdo_fetch($sql, $pars);
	if(!empty($log) && $log['status'] != '0') {
		message('这个订单已经支付成功, 不需要重复支付.');
	}
	if(!empty($log) && $log['status'] == '0') {
		pdo_delete('core_paylog', array('plid' => $log['plid']));
		$log = null;
	}
	if(empty($log)) {
		$moduleid = pdo_fetchcolumn("SELECT mid FROM ".tablename('modules')." WHERE name = :name", array(':name' => $params['module']));
		$moduleid = empty($moduleid) ? '000000' : sprintf("%06d", $moduleid);
		$fee = $params['fee'];
		$record = array();
		$record['uniacid'] = $_W['uniacid'];
		$record['openid'] = $_W['member']['uid'];
		$record['module'] = $params['module'];
		$record['type'] = $type;
		$record['tid'] = $params['tid'];
		$record['uniontid'] = date('YmdHis').$moduleid.random(8,1);
		$record['fee'] = $fee;
		$record['status'] = '0';
		$record['is_usecard'] = 0;
		$record['card_id'] = 0;
		$record['card_fee'] = $fee;
		$record['encrypt_code'] = '';
		$record['acid'] = $_W['acid'];
		if($type != 'delivery') {
			$iscard = pdo_fetchcolumn('SELECT iscard FROM ' . tablename('modules') . ' WHERE name = :name', array(':name' => $params['module']));
			if($setting['payment']['card']['switch'] == 2 && !empty($_GPC['card_id']) && !empty($_GPC['encrypt_code']) && !empty($_W['acid'])) {
				$card_id = base64_decode($_GPC['card_id']);
				$card = pdo_fetch('SELECT id,card_id,type,extra FROM ' . tablename('coupon') . ' WHERE acid = :acid AND card_id = :card_id', array(':acid' => $_W['acid'], ':card_id' => $card_id));
				$card['fee'] = $record['card_fee'];
				if(!empty($card)) {
					$record['is_usecard'] = 1;
					$record['card_type'] = 1;
					if($card['type'] == 'discount') {
						$card['fee'] = sprintf("%.2f", ($params['fee'] * ($card['extra'] / 100)));
					} elseif($card['type'] == 'cash') {
						$cash = iunserializer($card['extra']);
						if($params['fee'] >= $cash['least_cost']) {
														$card['fee'] =  sprintf("%.2f", ($params['fee'] -  $cash['reduce_cost']));
						}
					}
					$record['card_fee'] = $card['fee'];
					$record['card_id'] = $card['card_id'];
					$record['encrypt_code'] = trim($_GPC['encrypt_code']);
				}
			}
			if($setting['payment']['card']['switch'] == 3  && !empty($_GPC['coupon_id'])) {
				$coupon_id = intval($_GPC['coupon_id']);
								$coupon = pdo_fetch('SELECT * FROM ' . tablename('activity_coupon') . ' WHERE uniacid = :aid AND couponid = :id', array(':aid' => $_W['uniacid'], ':id' => $coupon_id));
				$use_modules = pdo_fetchall('SELECT module FROM ' . tablename('activity_coupon_modules') . ' WHERE uniacid = :uniacid AND couponid = :couponid', array(':uniacid' => $_W['uniacid'], ':couponid' => $coupon_id), 'module');
				$use_modules = array_keys($use_modules);
				if(!empty($coupon) && ($coupon['starttime'] <= TIMESTAMP  && $coupon['endtime'] >= TIMESTAMP) && in_array($params['module'], $use_modules)) {
					$coupon['fee'] = $record['card_fee'];
										$condition = '';
					if($iscard == 1) {
						$condition = " AND grantmodule = '{$params['module']}'";
					}
					$has = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity_coupon_record') . ' WHERE uid = :uid AND uniacid = :aid AND couponid = :cid AND status = 1 ' . $condition, array(':uid' => $_W['member']['uid'], ':aid' => $_W['uniacid'], ':cid' => $coupon_id));
					if($has > 0) {
						$record['is_usecard'] = 1;
						$record['card_type'] = 2;
						if($coupon['type'] == '1') {
							$coupon['fee'] = sprintf("%.2f", ($params['fee'] * $coupon['discount']));
						} elseif($coupon['type'] == '2') {
							if($params['fee'] >= $coupon['condition']) {
																$coupon['fee'] = sprintf("%.2f", ($params['fee'] -  $coupon['discount']));
							}
						}
					}
					$record['card_fee'] = $coupon['fee'];
					$record['card_id'] = $coupon_id;
					$record['encrypt_code'] = '';
				}
			}
		}

		if(pdo_insert('core_paylog', $record)) {
			$plid = pdo_insertid();
			$record['plid'] = $plid;
			$log = $record;
		} else {
			message('系统错误, 请稍后重试.');
		}
	}
	$ps = array();
	$ps['tid'] = $log['plid'];
	$ps['uniontid'] = $log['uniontid'];
	$ps['user'] = $_W['fans']['from_user'];
	$ps['total_fee'] = $ps['fee'] = $log['card_fee'];
	$ps['subject'] = $ps['title'] = $params['title'];
	$ps['out_trade_no'] = $params['out_trade_no'];
	/////////////二次开发/////////////////////////
	if($type == 'yunpay') {
		if(!empty($plid)) {
			$tag = array();
			$tag['acid'] = $_W['acid'];
			$tag['uid'] = $_W['member']['uid'];
			pdo_update('core_paylog', array('openid' => $_W['openid'], 'tag' => iserializer($tag)), array('plid' => $plid));
		}
		load()->model('payment');
		load()->func('communication');
		$sl = base64_encode(json_encode($ps));
		$auth = sha1($sl . $_W['uniacid'] . $_W['config']['setting']['authkey']);
		//echo $_W['siteroot'];exit;
		header("location: ".$_W['siteroot']."addons/weliam_indiana/yunpay/pay.php?i={$_W['uniacid']}&auth={$auth}&ps={$sl}");
		exit();
	}
}
