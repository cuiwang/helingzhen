<?php
defined('IN_IA') or exit('Access Denied');
$merchant=$this->merchant();
$this -> backlists();
	load() -> func('tpl');
	global $_W,$_GPC;
	$id = intval($_GPC['id']);
	$con =  "uniacid = '{$_W['uniacid']}' and is_hexiao=0 and mobile<>'虚拟' and merchantid='{$merchant['id']}' "  ;
	if($id){
		$con .= " and merchantid = {$id} ";
	}
	$status = $_GPC['status'];
	if($status!=''){
		$con .= " and status = {$status} ";
	}
	$cha =time() - strtotime(date('Y-m-d'));
	$starttime = empty($_GPC['time']['start']) ? strtotime(date('Y-m-d')) - 7 * 86400 : strtotime($_GPC['time']['start']);
	$endtime = empty($_GPC['time']['end']) ? strtotime(date('Y-m-d'))+86400 : strtotime($_GPC['time']['end'])+86400;
	$s = $starttime;
	$e = $endtime;
	$list = array();
	$j=0;
	while($e >= $s){
		
		$listone = pdo_fetchall("SELECT id  FROM " . tablename('tg_order') . " WHERE $con   AND createtime >= :createtime AND createtime <= :endtime ORDER BY createtime ASC", array( ':createtime' => $e-86400, ':endtime' => $e));
		$list[$j]['gnum'] = count($listone);
		$list[$j]['createtime'] =  $e-86400;
		$j++;
		$e = $e-86400;
	}
//	echo "<pre>";print_r(date('Y-m-d H:i:s',$starttime)."====".date('Y-m-d H:i:s',$endtime));exit;
	$day = $hit = array();
	if (!empty($list)) {
		foreach ($list as $row) {
			$day[] = date('m-d', $row['createtime']);
			$hit[] = intval($row['gnum']);
		}
	}
	
	for ($i = 0; $i = count($hit) < 2; $i++) {
		$day[] = date('m-d', $endtime);
		$hit[] = $day[$i] == date('m-d', $endtime) ? $hit[0] : '0';
	}
	include $this -> template('web/data');
