<?php
load ()->model ( 'mc' );
$replyid = intval ( $_GPC ['replyid'] );
// 用户状态
$status = intval ( $_GPC ['status'] );
$reply = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_reply ) . " WHERE id = :replyid", array (
		':replyid' => $replyid 
) );

if ($_GPC ['op'] == 'verify') {
	$userid = intval ( $_GPC ['userid'] );
	$user = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_user ) . " WHERE id = :userid ORDER BY `id` DESC", array (
			':userid' => $userid 
	) );
	if ($user ['status'] == 2) {
		pdo_update ( $this->table_user, array (
				'status' => '3' 
		), array (
				'id' => $userid
		) );
	}
}

$pindex = max ( 1, intval ( $_GPC ['page'] ) );
$psize = 20;
if(empty($status)){
	$list = pdo_fetchall ( "SELECT u.*,m.avatar as avatar,m.nickname as nickname,p.prizename as prizename FROM " . tablename ( $this->table_user ) . ' as u left join ' . tablename ( 'mc_members' ) . ' as m on u.uid=m.uid left join ' . tablename ( $this->table_prize ) . ' as p on u.prizeid=p.id ' . " WHERE u.uniacid = :uniacid AND u.replyid = :replyid  ORDER BY u.hits DESC ,createtime,status,id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array (
			':replyid' => $replyid,
			':uniacid' => $_W ['uniacid'] 
	) );
	$total = pdo_fetchcolumn ( "SELECT COUNT(1) FROM " . tablename ( $this->table_user ) . ' as u left join ' . tablename ( 'mc_members' ) . ' as m on u.uid=m.uid left join ' . tablename ( $this->table_prize ) . ' as p on u.prizeid=p.id ' . ' WHERE u.uniacid = :uniacid AND u.replyid = :replyid ', array (
			':replyid' => $replyid,
			':uniacid' => $_W ['uniacid'] 
	) );
}else{
	$list = pdo_fetchall ( "SELECT u.*,m.avatar as avatar,m.nickname as nickname,p.prizename as prizename FROM " . tablename ( $this->table_user ) . ' as u left join ' . tablename ( 'mc_members' ) . ' as m on u.uid=m.uid left join ' . tablename ( $this->table_prize ) . ' as p on u.prizeid=p.id ' . " WHERE u.uniacid = :uniacid AND u.replyid = :replyid AND u.status=:status ORDER BY u.hits DESC ,createtime,status,id DESC LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array (
			':status' => $status,
			':replyid' => $replyid,
			':uniacid' => $_W ['uniacid']
	) );
	$total = pdo_fetchcolumn ( "SELECT COUNT(1) FROM " . tablename ( $this->table_user ) . ' as u left join ' . tablename ( 'mc_members' ) . ' as m on u.uid=m.uid left join ' . tablename ( $this->table_prize ) . ' as p on u.prizeid=p.id ' . ' WHERE u.uniacid = :uniacid AND u.replyid = :replyid AND u.status=:status', array (
			':status' => $status,
			':replyid' => $replyid,
			':uniacid' => $_W ['uniacid'] 
	) );
}

$properties_list = pdo_fetchall('SELECT * FROM '.tablename($this->table_property));
$properties = array();
foreach($properties_list as $key => $item){
	$properties[$item['propertykey']] = $item['propertyvalue'];
}
foreach($list as $key => $item){
	$str = '';
	if(!empty($item['userinfo'])){
	 	$temp = json_decode($item['userinfo']);
	 	if(!empty($temp)){
		 	foreach($temp as $k => $v){
		 		$str .= '<p>'.$properties[$k].'：'.$v.'</p>';
		 	}
		}
	}
 	$list[$key]['userinfo'] = $str;

 	if(!empty($item['sn']) && strstr($item['sn'],'bonus')){
		$temp = pdo_fetch('SELECT * FROM '.tablename($this->table_bonus).' WHERE id=:bonusid AND uniacid=:uniacid',array(':bonusid'=>$item['prizeid'],':uniacid'=>$_W['uniacid']));
		$list[$key]['prizename'] = $temp['bonusname'];
	}

	if(!empty($item['sn']) && strstr($item['sn'],'coupon')){
		$temp = pdo_fetch('SELECT * FROM '.tablename($this->table_coupon).' WHERE id=:couponid AND uniacid=:uniacid',array(':couponid'=>$item['prizeid'],':uniacid'=>$_W['uniacid']));
		$list[$key]['prizename'] = $temp['couponname'];
	}

}
$page = pagination ( $total, $pindex, $psize );
include $this->template ( 'web/user' );
?>