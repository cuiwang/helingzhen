<?php
$replyid = intval($_GPC['replyid']);
$reply = pdo_fetch ( "SELECT * FROM " . tablename ( $this->table_reply ) . " WHERE id = :replyid ORDER BY `id` DESC", array (
		':replyid' => $replyid 
) );
$openid = $_W['openid'];

// 是否存在用户
$user = pdo_fetch('SELECT * FROM '.tablename($this->table_user)
	.' WHERE replyid=:replyid AND uniacid=:uniacid AND openid=:openid',array(':replyid'=>$replyid,':uniacid'=>$_W['uniacid'],':openid'=>$openid));
if(!$user){
	$error = '用户不存在';
	include $this->template('no');
	exit();
}

// 获取用户昵称头像信息
$temp = pdo_fetch( 'select nickname,avatar from '
	. tablename ( 'mc_members' ) 
	. 'WHERE uid=:uid',array(':uid'=>$user['uid']));
$user['avatar'] = $temp['avatar'];
$user['nickname'] = $temp['nickname'];

$templogs = pdo_fetchall('SELECT * FROM ' . tablename( $this->table_log ) . ' WHERE popenid=:openid AND replyid=:replyid',array(':replyid'=>$replyid,':openid'=>$user['openid']));

$result = array();
foreach($templogs as $item){
	$temp = pdo_fetch( 'select m.nickname as nickname,m.avatar as avatar from '
		. tablename ( $this->table_user ) . ' as u left join '
		. tablename ( 'mc_members' ) . ' as m on m.uid=u.uid '
		. 'WHERE u.openid=:openid',array(':openid'=>$item['sopenid']));
	// $temp['time'] = date('Y-m-d',$item['createtime']);
	$temp['time'] = $item['createtime'];
	$result[] = $temp;
}
include $this->template('person');
?>