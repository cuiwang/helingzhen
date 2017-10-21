<?php 
global $_W,$_GPC;
$table_sign = 'meepo_lottery_sign';
$table_setting = 'meepo_lottery_setting';
load()->func('db');
load()->func('pdo');
$sql = 'SELECT sign_count FROM '.tablename($table_setting).'WHERE uniacid = :uniacid';
$params =array(
	 	':uniacid' =>$_W['uniacid']
	 );
$result = pdo_fetch($sql,$params);
$sign_count = $result['sign_count'];
if($sign_count == ''){
	$sign_count == 10;
}
if(!empty($_GPC['openid'])){
	$sql =' SELECT * FROM '.tablename($table_sign).'WHERE sign_openid =:openid AND uniacid=:uniacid';
	$params = array(
		':openid' => $_GPC['openid'],
		':uniacid' =>$_W['uniacid']
	);
	$result = pdo_fetch($sql,$params);
	if($result){
		$record_date = date('Y-m-d',$result['sign_date']);
		$now_date = date('Y-m-d');
		if($record_date == $now_date){
			$ajax['message'] = '你已经签到过了!';
			die(json_encode($ajax));
		}else{
			$data['sign_date'] = time();
			$data['sign_times'] = $result['sign_times'] + 1;
			$data['uniacid'] = $_W['uniacid'];
			$row = pdo_update($table_sign ,$data,array('sign_openid' => $_GPC['openid'],'uniacid'=>$_W['uniacid']));
			$credit = mc_credit_update($_W['member']['uid'],'credit1', $sign_count, array($_W['member']['uid'], '签到获取积分：10'));
			if($row && $credit){
				$ajax['message'] = '签到成功,增加'.$sign_count.'积分!';
			}
			die(json_encode($ajax));
		}
	}else{
		$data['sign_openid'] = $_GPC['openid'];
		$data['sign_date'] = time();
		$data['uniacid'] = $_W['uniacid'];
		$row = pdo_insert($table_sign,$data);
		$credit = mc_credit_update($_W['member']['uid'],'credit1', $sign_count, array($_W['member']['uid'], '签到获取积分：10'));
		if($row && $credit){
			$ajax['message'] = '签到成功,增加'.$sign_count.'积分!';
		}
		die(json_encode($ajax));
	}
}
