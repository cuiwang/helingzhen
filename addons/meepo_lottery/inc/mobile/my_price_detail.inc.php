<?php 
global $_W,$_GPC;
load()->func('db');
load()->func('pdo');
$table_detail = 'meepo_lottery_detail';
$sql = 'SELECT * FROM'.tablename($table_detail);
$price_lists = pdo_fetchall($sql);
	foreach($price_lists as $key => $val){
		if($val['uniacid']==0){
			$data['uniacid'] = $_W['uniacid'];
			$row = pdo_update($table_detail,$data,array('detail_id'=>$val['detail_id']));
		}	
	}
if(!empty($_GPC['openid'])){
	$sql = ' SELECT * FROM '.tablename($table_detail).'WHERE detail_openid = :openid AND uniacid = :uniacid ORDER BY detail_id desc';
	$params = array(
		':openid' =>$_GPC['openid'],
		':uniacid' =>$_W['uniacid']
	);
	$results = pdo_fetchall($sql,$params);
	foreach($results as $key=>$val){
		$results[$key]['detail_price_date'] = date("m-d H:i",$val['detail_price_date']);
	}

}
die(json_encode($results));