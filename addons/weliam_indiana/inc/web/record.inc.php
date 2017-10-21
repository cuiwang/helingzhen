<?php
global $_W,$_GPC;
	$status=$_GPC['status'];
	$uniacid=$_W['uniacid'];
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$goodses = pdo_fetchall("SELECT * FROM ".tablename('weliam_indiana_consumerecord')." WHERE uniacid = '{$uniacid}' and status =1 ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	foreach($goodses as$key=>$value){
		$period = m('period')->getPeriodByPeriod_number($value['period_number']);
		$goods = m('goods')->getGoods($period['goodsid']);
		$info = m('member')->getInfoByOpenid($value['openid']);
		$goodses[$key]['title'] = $goods['title'];
		$goodses[$key]['picarr'] = $goods['picarr'];
		$goodses[$key]['periods'] = $period['periods'];
		$goodses[$key]['avatar'] = $info['avatar'];
		$goodses[$key]['nickname'] = $info['nickname'];
	}
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_consumerecord') . " WHERE uniacid = '{$uniacid}' and status = 1 ");
	$pager = pagination($total, $pindex, $psize);

	include $this->template('records');
?>