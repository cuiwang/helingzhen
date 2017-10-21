<?php
if (PHP_SAPI == 'cli')
	die ( 'This example should only be run from a Web Browser' );
$replyid = intval ( $_GPC ['replyid'] );
// 用户状态
$status = intval ( $_GPC ['status'] );
$reply = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_reply ) . " WHERE id = :replyid", array (
		':replyid' => $replyid 
) );

if(empty($status)){
	$list = pdo_fetchall ( "SELECT u.*,m.avatar as avatar,m.nickname as nickname,p.prizename as prizename FROM " . tablename ( $this->table_user ) . ' as u left join ' . tablename ( 'mc_members' ) . ' as m on u.uid=m.uid left join ' . tablename ( $this->table_prize ) . ' as p on u.prizeid=p.id ' . " WHERE u.uniacid = :uniacid AND u.replyid = :replyid  ORDER BY u.hits DESC ,createtime,status", array (
			':replyid' => $replyid,
			':uniacid' => $_W ['uniacid'] 
	) );

}else{
	$list = pdo_fetchall ( "SELECT u.*,m.avatar as avatar,m.nickname as nickname,p.prizename as prizename FROM " . tablename ( $this->table_user ) . ' as u left join ' . tablename ( 'mc_members' ) . ' as m on u.uid=m.uid left join ' . tablename ( $this->table_prize ) . ' as p on u.prizeid=p.id ' . " WHERE u.uniacid = :uniacid AND u.replyid = :replyid AND u.status=:status ORDER BY u.hits DESC ,createtime,status,id DESC", array (
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
		 		$str .= ''.$properties[$k].'：'.$v.',';
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


// 头
$tableheader = array (
		$this->encode ( 'ID' ),
		$this->encode ( '昵称' ),
		$this->encode ( '个人信息' ),
		$this->encode ( 'SN' ),
		$this->encode ( '阅数(查看)' ),
		$this->encode ( '参加时间' ),
		$this->encode ( '状态' ),
		$this->encode ( '奖品名称' ) 
);
$html = "\xEF\xBB\xBF";
foreach ( $tableheader as $value ) {
	$html .= $value . "\t";
}
$html .= "\n";

foreach ( $list as $value ) {
	$html .= $value ['id'] . "\t";
	$html .= $this->encode ( $value ['nickname'] ) . "\t";
	if ($value['userinfo'] == '')
		$html .= $this->encode ( '暂无' ) . "\t";
	else
		$html .= $this->encode ( $value['userinfo'] ) . "\t";

	if ($value['sn'] == '')
		$html .= $this->encode ( '暂未选择奖项' ) . "\t";
	else
		$html .= $this->encode ( $value['sn'] ) . "\t";

	$html .= $this->encode ( $value ['hits'] ) . "\t";
	$html .= $this->encode ( $value ['createtime'] ) . "\t";

	if ($value['status'] == '1')
		$html .= $this->encode ( '正常' ) . "\t";
	else if($value['status'] == '2')
		$html .= $this->encode ( '已兑奖' ) . "\t";
	else if($value['status'] == '3')
		$html .= $this->encode ( '已核销' ) . "\t";
	else
		$html .= $this->encode ( '不正常' ) . "\t";

	if ($value['prizename'] == '')
		$html .= $this->encode ( '暂未选择奖项' ) . "\t";
	else
		$html .= $this->encode ( $value['prizename'] ) . "\t";

	$html .= "\n";
}
header ( "Content-type:text/csv" );
header ( "Content-Disposition:attachment; filename=\"" . $reply ['name'] . "数据.xls\"" );
echo $html;
exit ();
?>