<?php
	global $_W,$_GPC;
	$op = !empty($_GPC['op'])?$_GPC['op']:'display';
	if($op=='display'){
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;
		$content='';
		$invites = pdo_fetchall("select *from".tablename("weliam_indiana_invite")."where uniacid={$_W['uniacid']} and type = 0 {$content} LIMIT " . ($pindex - 1) * $psize . ',' . $psize);	
		foreach($invites as$key=>$value){
			$address = m('member')->getInfoByOpenid($value['invite_openid']);
			$invites[$key]['invite_name']=$address['nickname'];
			$address = m('member')->getInfoByOpenid($value['beinvited_openid']);
			$invites[$key]['beinvited_name']=$address['nickname'];
		}
		$num = count($invites);
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_invite') . " WHERE uniacid={$_W['uniacid']} {$content} and type = 0");
		$pager = pagination($total, $pindex, $psize);
	
	}
	include $this->template('member');
?>