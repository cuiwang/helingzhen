<?php
global $_W,$_GPC;
$rid = intval($_GPC['rid']);
$weid = $_W['uniacid'];
for($i=1,$i<31,$i++){
	$data = array();
	$data = array(
		'openid' =>random(32),
		'rid' =>$rid,
		'isjoin' =>1,
		'lastupdate' =>TIMESTAMP,
		'isblacklist' =>0,
		'status' =>2,
		'othid' =>0,
		'vote'=>0,
		'verify'=>random(5,true),
		'weid'=>$weid,
		'nickname'=>'用户'.$i,
		'avatar'=>'http://xianchang.zhenshuxin.com/dz/170119.jpg',
		'sex'=>'1',
	);
	pdo_insert('weixin_flag',$data); 
}
message('插入成功！');
