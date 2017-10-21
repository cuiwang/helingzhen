<?php
/**
 * Created by PhpStorm.
 * 充值
 * User: yike
 * Date: 2016/6/15
 * Time: 15:18
 */
if (!defined('IN_IA')) {
    exit('Access Denied');
}
global $_W, $_GPC;
$operation = !empty($_GPC['op']) ? $_GPC['op'] : 'recharge';
$setdata = pdo_fetch("select * from " . tablename('yike_guess_sysset') . ' where uniacid=:uniacid limit 1', array(
    ':uniacid' => $_W['uniacid']
));
$set = unserialize($setdata['sets']);
$bet_name = $set['bet_name']['bet_name'];
if ($operation == 'recharge') {
    //充值
	$fee = $_GPC['money'];
	if (!empty($fee)) {
		$data = array('unicaid' => $_W['uniacid'], 'uid' => $_W['member']['uid'], 'money' => $fee, 'type' => 1, 'status' => 0, 'created_time' => time());
		$result = pdo_insert('yike_guess_recharge', $data);
		$id = pdo_insertid();
		$params = array(
		    'tid' => $id,			//充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
		    'ordersn' => $id,		//收银台中显示的订单号
		    'title' => $bet_name.'充值',	//收银台中显示的标题
		    'fee' => $fee,			//收银台中显示需要支付的金额,只能大于 0
		    'user' => $_W['member']['uid'],	//付款用户, 付款的用户名(选填项)
		    'type'  => 'recharge'
		);
		$this->pay($params);
	}else{
		message('充值金额不能为0!!', $this->createMobileUrl('index', array()), 'error');
	}
}