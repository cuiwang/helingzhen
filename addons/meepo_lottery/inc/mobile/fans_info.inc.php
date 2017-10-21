<?php 
global $_W,$_GPC;
load()->func('db');
load()->func('pdo');
$table_price = 'meepo_lottery_images';
$table_detail = 'meepo_lottery_detail';
$sql = ' SELECT * FROM '.tablename($table_price).'WHERE images_status = :status AND uniacid = :uniacid ORDER BY images_number asc';
$params = array(
    ':status' => 0,
    ':uniacid' =>$_W['uniacid']
 );
$result_price = pdo_fetchall($sql,$params);
if(!empty($_GPC['price_title'])){
	$data['detail_openid'] = $_GPC['openid'];
	$data['detail_price_title'] = $_GPC['price_title'];
	$data['detail_price_thumbnail'] = $_GPC['price_img'];
	$data['detail_price_date'] = time();
	$data['detail_realname'] = $_GPC['realname'];
	$data['detail_mobile'] = $_GPC['mobile'];
	$data['uniacid'] = $_GPC['uniacid'];
	$row = pdo_insert($table_detail,$data);
	if($row){
		$ajax['error'] = 0;
		$ajax['success'] = 1;
	}else{
		$ajax['error'] = 1;
		$ajax['success'] = 0;
	}
	die(json_encode($ajax));
}
