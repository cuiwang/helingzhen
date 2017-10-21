<?php
global $_W, $_GPC;
$cservicelist = pdo_fetchall("SELECT content,name,thumb FROM " . tablename(BEST_CSERVICE) . " WHERE weid = '{$_W['uniacid']}' AND ctype = 1 ORDER BY displayorder ASC");
$kefuopenid = trim($_GPC['kefuopenid']);
$operation = empty($_GPC['op']) ? 'display' : $_GPC['op'];
if($operation == 'display'){
	$j = 24; //小时数
	$labels = array();
	$datas = array();
	for($i=0;$i<$j;$i++){
		$date = date('Y-m-d');
		$beginTime = strtotime($date." ".$i.":00:00");
		$endnTime = strtotime($date." ".($i+1).":00:00");
		$goodsnum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time >= {$beginTime} AND time<= {$endnTime} AND openid = '{$kefuopenid}'");
		$datas[] = $goodsnum;
		$labels[] = $i;
	}
	$labels = json_encode($labels);
	$datas = json_encode($datas);
}
if($operation == 'month'){
	$j = date(j); //获取当前月份天数
	$start_time = strtotime(date('Y-m-01'));  //获取本月第一天时间戳
	$labels = array();
	$datas = array();
	for($i=0;$i<$j;$i++){
		$date = date('Y-m-d',$start_time+$i*86400);
		$start_day_time = strtotime($date." 00:00:00");
		$end_day_time = strtotime($date." 23:59:59");
		$goodsnum = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename(BEST_CHAT)." WHERE weid = {$_W['uniacid']} AND time >= {$start_day_time} AND time<= {$end_day_time} AND openid = '{$kefuopenid}'");
		$datas[] = $goodsnum;
		$labels[] = $date;
	}
	$labels = json_encode($labels);
	$datas = json_encode($datas);
}
include $this->template('web/tongji');
?>