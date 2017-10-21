<?php
$replyid = intval($_GPC['replyid']);
$userid = intval($_GPC['userid']);
$user = pdo_fetch('SELECT * FROM '.tablename($this->table_user).' WHERE id=:userid',array(':userid'=>$userid));
if(!$user){
	die(json_encode(array('result'=>true,'msg'=>'不存在用户信息')));
}


$templogs = pdo_fetchall('SELECT * FROM ' . tablename( $this->table_log ) . ' WHERE popenid=:openid AND replyid=:replyid',array(':replyid'=>$replyid,':openid'=>$user['openid']));

$result = array();
foreach($templogs as $temp){
	$temp = pdo_fetch( 'select m.nickname as nickname,m.avatar as avatar from '
		. tablename ( $this->table_user ) . ' as u left join '
		. tablename ( 'mc_members' ) . ' as m on m.uid=u.uid '
		. 'WHERE u.openid=:openid',array(':openid'=>$temp['sopenid']));
	$result[] = $temp;
}


if (! $result) {
	die ( json_encode ( array (
			'result' => true,
			'msg' => '暂无' 
	) ) );
}

$str = '<div class="row">';
foreach ( $result as $item ) {
	$str .= '<div class="col-xs-2">';
	$str .= '<a href="#" class="thumbnail">';
	$str .= '<img src="' . $item ['avatar'] . '" alt="' . $item ['nickname'] . '">';
	$str .= '<div class="caption">';
	$str .= '<p style="font-size:.5em">' . $item ['nickname'] . '</p>';
	$str .= '</div>';
	$str .= '</a>';
	$str .= '</div>';
}
$str .= '</div>';
die ( json_encode ( array (
		'result' => true,
		'msg' => $str 
) ) );
?>