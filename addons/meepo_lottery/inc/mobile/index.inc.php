<?php 
global $_W,$_GPC;
 checkauth();
 load()->func('db');
 load()->func('pdo');
 $table_member = 'meepo_lottery_members';
 $table_price = 'meepo_lottery_images';
 $table_price_detail = 'meepo_lottery_detail';
 $table_slide = 'meepo_lottery_slide_images';
 $table_share = 'meepo_lottery_setting';
 $sql = 'SELECT * FROM '.tablename($table_share).'WHERE uniacid = :uniacid ';
 $params = array(':uniacid' =>$_W['uniacid']);
 $share_result = pdo_fetch($sql,$params);
 if (empty($_W['fans']['nickname'])) {
	mc_oauth_userinfo();
}
$sql = 'SELECT * FROM'.tablename($table_member);
$members_lists = pdo_fetchall($sql);
	foreach($members_lists as $key => $val){
		if($val['uniacid']==0){
			$data['uniacid'] = $_W['uniacid'];
			$row = pdo_update($table_member,$data,array('members_id'=>$val['members_id']));
		}	
	}
$params = array(
				':status' => 0,
				':uniacid' =>$_W['uniacid']
			);
$sql = 'SELECT * FROM'.tablename($table_slide)."WHERE slide_status=:status AND uniacid = :uniacid";
$slide_results = pdo_fetchall($sql, $params);
 if(!empty($_W['fans']['openid'])){
 	$sql = 'SELECT * FROM '.tablename($table_member).'WHERE members_openid =:openid AND uniacid = :uniacid';
 	$params =array(
 		':openid' =>$_W['fans']['openid'],
 		':uniacid' =>$_W['uniacid']
 		);
 	$result = pdo_fetch($sql, $params);
 	if(!$result){
 		$data['members_openid'] = $_W['fans']['openid'];
 		$data['members_credits'] = 100;
 		$data['members_username'] = $_W['fans']['nickname'];
 		$data['members_thumbnail'] = $_W['fans']['avatar'];
 		$data['uniacid'] = $_W['uniacid'];
 		pdo_insert($table_member, $data); 
 	}
 }
 $sql = 'SELECT * FROM '.tablename($table_member).'WHERE uniacid = :uniacid';
 $params = array(':uniacid' =>$_W['uniacid']);
 $member_list = pdo_fetchall($sql,$params);

 $table_price = 'meepo_lottery_images';
 $sql = ' SELECT * FROM '.tablename($table_price).'WHERE images_status = :status AND uniacid = :uniacid ORDER BY images_number asc';
 $params = array(
    ':status' => 0,
    ':uniacid' =>$_W['uniacid']
 );
 $result_price = pdo_fetchall($sql,$params);
 $sql = 'SELECT * FROM '.tablename($table_price_detail).'WHERE detail_openid = :open_id AND uniacid = :uniacid ';
 $params =array(
 	':open_id' => $_W['fans']['openid'],
 	':uniacid' =>$_W['uniacid']
 );
 $user_detail = pdo_fetch($sql,$params);
$sql = 'SELECT * FROM '.tablename($table_price_detail).'WHERE uniacid = :uniacid ';
$params = array(':uniacid' =>$_W['uniacid']);
$price_results = pdo_fetchall($sql,$params);
foreach($price_results as $key => $val){
	$sql = 'SELECT * FROM '.tablename($table_member).'WHERE members_openid =:openid AND uniacid = :uniacid ';
	$params = array(
		':openid' =>$val['detail_openid'],
		':uniacid' =>$_W['uniacid']
	);
	$user = pdo_fetch($sql,$params);
	$price_results[$key]['detail_username'] = $user['members_username'];
	$price_results[$key]['detail_thumbnail'] = $user['members_thumbnail'];
}
include $this->template('index');

