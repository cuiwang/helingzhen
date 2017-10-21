<?php
global $_W,$_GPC;
$op = !empty($_GPC['op'])?$_GPC['op']:'display';
$uniacid = $_W['uniacid'];
$condition = '';
if (empty($starttime) || empty($endtime)) {
	$starttime = strtotime(date('Y-m-d'));
	$endtime = strtotime(date('Y-m-d',strtotime('+1 day')));
}
if (!empty($_GPC['time'])) {
	$starttime = strtotime($_GPC['time']['start']);
	$endtime = strtotime($_GPC['time']['end']) ;
	$condition .= " AND  createtime >= '{$starttime}' AND  createtime <= '{$endtime}' ";
}
	
if($op=='display'){
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	
	$type = $_GPC['type'];
	switch($type){
		case 'person' : $condition_type =  "and openid not like '%machine%'";break;
		case 'machine' : $condition_type =  "and openid like '%machine%'";break;
		default : $condition_type = '';
	}
	
	$goodses = pdo_fetchall("SELECT * FROM ".tablename('weliam_indiana_consumerecord')." WHERE uniacid = '{$uniacid}' and status = 1 $condition $condition_type ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize);
	foreach($goodses as$key=>$value){
		$period = pdo_fetch("SELECT goodsid,periods FROM " . tablename('weliam_indiana_period') . " where period_number = '{$value['period_number']}'");
		$goods = pdo_fetch("SELECT title,picarr FROM " . tablename('weliam_indiana_goodslist') . " where id = '{$period['goodsid']}'");
		$info = m('member')->getInfoByOpenid($value['openid']);
		$goodses[$key]['title'] = $goods['title'];
		$goodses[$key]['picarr'] = $goods['picarr'];
		$goodses[$key]['periods'] = $period['periods'];
		$goodses[$key]['avatar'] = $info['avatar'];
		$goodses[$key]['nickname'] = $info['nickname'];
	}
	
	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_consumerecord') . " WHERE uniacid = '{$uniacid}' and status = 1 $condition ");
	$pager = pagination($total, $pindex, $psize);
	
	include $this->template('records');
}

if($op=='recharge'){
	$uniacid = $_W['uniacid'];
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	
	$goodses = pdo_fetchall("select * from".tablename('weliam_indiana_rechargerecord')."where status = 1 and uniacid = '{$_W['uniacid']}' $condition order by id desc limit ".($pindex - 1) * $psize.','.$psize);
	foreach($goodses as$key=>$value){
		$info = m('member')->getInfoByOpenid($value['openid']);
		$goodses[$key]['avatar'] = $info['avatar'];
		$goodses[$key]['nickname'] = $info['nickname'];
	}

	$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('weliam_indiana_rechargerecord') . " WHERE uniacid = '{$uniacid}' and status = 1 $condition ");
	$pager = pagination($total, $pindex, $psize);

	include $this->template('recharge');
}

