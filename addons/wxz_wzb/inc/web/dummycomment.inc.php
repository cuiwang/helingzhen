<?php
global $_W,$_GPC;
load()->func('tpl');
$rid = $_GPC['rid'];
if (isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'setting') {

	$data=array(
		'uniacid'=>$_W['uniacid'],
		'uid'=>99999999,
		'ip'=>$_W['clientip'],
		'is_auth'=>1,
		'nickname'=>$_GPC['nickname'],
		'headimgurl'=>tomedia($_GPC['headimgurl']),
		'rid'=>$rid,
		'isadmin'=>1,
		'content'=>$_GPC['content'],
		'dateline'=>time()
	);
	pdo_insert('wxz_wzb_comment', $data);
}
include $this->template('dummyComment');