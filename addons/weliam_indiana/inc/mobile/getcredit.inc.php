<?php
	global $_W,$_GPC;
	load()->model('mc');
	load()->model('activity');
	$openid = m('user') -> getOpenid();
	$couponid = $_GPC['id'];
	$uid = $_W['member']['uid'];
	$module = "weliam_indiana";
	
	$user = mc_fetch($uid, array('groupid'));
	$groupid = $user['groupid'];
	$coupon = pdo_fetch("SELECT * FROM " . tablename('activity_coupon') . " WHERE `couponid` = :couponid LIMIT 1", array(':couponid' => $couponid));
	$pcount = pdo_fetchcolumn("SELECT count(*) FROM " . tablename('activity_coupon_record') . " WHERE `uid` = :uid AND `couponid` = :couponid", array(':couponid' => $couponid, ':uid' => $uid));
	$coupongroup = pdo_fetchall("SELECT groupid FROM " . tablename('activity_coupon_allocation') . " WHERE `couponid` = :couponid", array(':couponid' => $couponid), 'groupid');
	$group = array_keys($coupongroup);
	$couponmodules = pdo_fetchall("SELECT module FROM " . tablename('activity_coupon_modules') . " WHERE `couponid` = :couponid", array(':couponid' => $couponid), 'module');
	$modules = array_keys($couponmodules);
	if (empty($coupon)) {
		die(json_encode(array("errno"=>-1,"message"=>"未找到指定优惠券")));
	} elseif (!in_array($module, $modules)) {
		die(json_encode(array("errno"=>-1,"message"=>"该优惠券只能在特定的模块中领取")));
	} elseif (empty($coupongroup)) {
		die(json_encode(array("errno"=>-1,"message"=>"该优惠券未指定可使用的会员组")));
	} elseif (!in_array($groupid, $group)) {
		die(json_encode(array("errno"=>-1,"message"=>"您所在的用户组没有领取该优惠券的权限")));
	} elseif ($coupon['starttime'] > TIMESTAMP) {
		die(json_encode(array("errno"=>-1,"message"=>"优惠券活动尚未开始")));
	} elseif ($coupon['endtime'] < TIMESTAMP) {
		die(json_encode(array("errno"=>-1,"message"=>"优惠券活动已经结束")));
	} elseif ($coupon['dosage'] >= $coupon['amount']) {
		die(json_encode(array("errno"=>-1,"message"=>"优惠券已经发放完毕")));
	} elseif ($pcount >= $coupon['limit']) {
		die(json_encode(array("errno"=>-1,"message"=>"用户领取优惠券数量已经超过限制")));
	}
	$creditnames = array();
	$unisettings = uni_setting($_W['uniacid'], array('creditnames'));
	if (!empty($unisettings) && !empty($unisettings['creditnames'])) {
		foreach ($unisettings['creditnames'] as $key => $credit) {
			$creditnames[$key] = $credit['title'];
		}
	}
	$credit = mc_credit_fetch($uid, array($coupon['credittype']));
	if ($credit[$coupon['credittype']] < $coupon['credit']) {
		return error(-1, '您的' . $creditnames[$coupon['credittype']] . '数量不够,无法兑换.');
	}
	mc_credit_update($uid, $coupon['credittype'], -1 * $coupon['credit'], array($uid, '优惠券兑换:' . $coupon['title'] . ' 消耗 ' . $creditnames[$coupon['credittype']] . ':' . $coupon['credit']));
	$remark = "通过{$module}模块领取优惠券";
	$insert = array(
		'couponid' => $couponid,
		'uniacid' => $_W['uniacid'],
		'uid' => $uid,
		'grantmodule' => $module,
		'granttime' => TIMESTAMP,
		'status' => 1,
		'remark' => $remark
	);
	pdo_insert('activity_coupon_record', $insert);
	$recordid = pdo_insertid();
	pdo_update('activity_coupon', array('dosage' => $coupon['dosage'] + 1), array('couponid' => $couponid));
	/*二维码*/
	require_once IA_ROOT . '/addons/weliam_indiana/source/qrcode.class.php';
	$createqrcode =  new creat_qrcode();
	$createqrcode->creategroupQrcode($recordid);
	
	die(json_encode(array("errno"=>1,"message"=>"领取成功")));
?>