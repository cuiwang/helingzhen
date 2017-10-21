<?php

global $_W,$_GPC;
$uniacid = $_W['uniacid'];
load()->func('tpl');

$rid = intval($_GPC['rid']);
$id = intval($_GPC['id']);

$touser = pdo_fetch('SELECT * FROM ' . tablename('wxz_wzb_comment') . ' WHERE `uniacid` = :uniacid AND `id` = :id and `rid` = :rid', array(':uniacid' => $_W['uniacid'],':id' => $_GPC['id'],':rid' => $rid) );
//$_W['siteroot'].'/attachment/'.$_GPC['headimgurl'],
if (isset($_GPC['item']) && $_GPC['item'] == 'ajax' && $_GPC['key'] == 'setting') {
	$data=array(
		'uniacid'=>$_W['uniacid'],
		'uid'=>1,
		'ip'=>$_W['clientip'],
		'is_auth'=>1,
		'nickname'=>$_GPC['nickname'],
		'headimgurl'=>tomedia($_GPC['headimgurl']),
		'rid'=>$rid,
		'content'=>$_GPC['content'],
		'toid'=>$id,
		'touid'=>$touser['uid'],
		'tonickname'=>$touser['nickname'],
		'toheadimgurl'=>$touser['headimgurl'],
		'dateline'=>time()
	);

	pdo_insert('wxz_wzb_comment', $data);
}

include $this->template('reply');