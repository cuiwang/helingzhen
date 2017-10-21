<?php
/**
 * 女神来了模块定义
 *
 * @author 微赞科技
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');
if($now <= $rbasic['tstart_time'] || $now >= $rbasic['tend_time']) {//判断活动时间是否开始及提示

		if ($now <= $rbasic['tstart_time']) {
			$fmdata = array(
				"success" => -1,
				"msg" => $rbasic['ttipstart'],
			);
			echo json_encode($fmdata);
			exit;
		}
		if ($now >= $rbasic['tend_time']) {
			$fmdata = array(
				"success" => -1,
				"msg" => $rbasic['ttipend'],
			);
			echo json_encode($fmdata);
			exit;
		}
	}
$jf = pdo_fetch("SELECT jifen_charge FROM " . tablename($this -> table_jifen) . ' WHERE rid = :rid ' . $uni . '', array(':rid' => $rid));
if ($_W['account']['level'] == 4) {
		$u_uniacid = $uniacid;
	}else{
		$u_uniacid = $cfg['u_uniacid'];
	}
if ($_GPC['type'] == 'charge') {
	$price = $_GPC['charge'];
	$weixin = $this->is_weixin();
	if ($weixin == false) {
		$data = array(
			'success' => -1,
			'msg' => '请使用微信客户端充值！'
		);
		echo json_encode($data);
		exit ;
	}
	if($now <= $rbasic['tstart_time'] || $now >= $rbasic['tend_time']) {//判断活动时间是否开始及提示

		if ($now <= $rbasic['tstart_time']) {
			$fmdata = array(
				"success" => -1,
				"msg" => $rbasic['ttipstart'],
			);
			echo json_encode($fmdata);
			exit;
		}
		if ($now >= $rbasic['tend_time']) {
			$fmdata = array(
				"success" => -1,
				"msg" => $rbasic['ttipend'],
			);
			echo json_encode($fmdata);
			exit;
		}
	}
	if (empty($from_user)) {
		$data = array(
			'success' => -1,
			'msg' => '未获取到openid，请重新打开页面'
		);
		echo json_encode($data);
		exit ;
	}

	$nickname = $this->getname($rid, $from_user);
//付款
	$orderid = date('ymdhis') . random(4, 1);
	//付款
	$datas = array(
		'uniacid' => $uniacid,
		'weid' => $uniacid,
		'rid' => $rid,
		'from_user' => $from_user,
		'mobile' => $this->getmobile($rid,$from_user),
		'ordersn' => $orderid,
		'payyz' => '',
		'title' => '积分充值',
		'price' => $price,
		'jifen' => $price*$jf['jifen_charge'],
		'realname' => $nickname,
		'avatar' => $avatar,
		'status' => '0',
		'paytype' => '6',
		'ispayvote' => '2',
		'remark' => '积分充值订单，'.$nickname.'充值'.$price.'元，'.$price*$jf['jifen_charge'].'积分',
		'ip' => getip(),
		'createtime' => time(),
	);
	$datas['iparr'] = getiparr($datas['ip']);
	pdo_insert($this->table_order, $datas);
	$log = pdo_get('core_paylog', array('uniacid' => $uniacid, 'module' => $_GPC['m'], 'tid' => $orderid));

	if (empty($log)) {
        $log = array(
            'uniacid' => $uniacid,
            'acid' => $_W['acid'],
            'openid' => $from_user,
            'module' => $this->module['name'],
            'tid' => $orderid,
            'fee' => $price,
            'card_fee' => $price,
            'status' => '0',
            'is_usecard' => '0',
        );
        pdo_insert('core_paylog', $log);
	}

	$toparams = array();
	$toparams['tid'] = $orderid;
	$toparams['rid'] = $rid;
	$toparams['user'] = $from_user;
	$toparams['fee'] = $price;
	$toparams['title'] = '积分充值';
	$toparams['content'] = '积分充值订单，'.$nickname.'充值'.$price.'元，'.$price*$jf['jifen_charge'].'积分';
	$toparams['ordersn'] = $orderid;
	$toparams['module'] = $this->module['name'];
	$toparams['payyz'] = '';
	$entoparams = base64_encode(json_encode($toparams));
	$fmdata = array(
			"success" => 1,
			"flag" => 1,
			"fee" => sprintf('%.2f', $price),
			"params" => $entoparams,
			"toparams" => $toparams,
			"msg" => '充值中',
		);
	echo json_encode($fmdata);
	exit();
}else{
	$templatename = $rbasic['templates'];
	$toye = $this -> templatec($templatename, $_GPC['do']);
	include $this -> template($toye);
}