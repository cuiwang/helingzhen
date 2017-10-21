<?php
global $_W,$_GPC;
$weid = $_W['uniacid'];
$rid = intval($_GPC['rid']);

$data = array();
if($_W['isajax']){
	$get_point1 = $_GPC['team1_point'];
	$get_point2 = $_GPC['team2_point'];
	$data['topinfo']['team1'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team ORDER BY point DESC,createtime DESC LIMIT 0,7",array(':rid'=>$rid,':weid'=>$weid,':team'=>'1'));
	$data['topinfo']['team2'] = pdo_fetchall("SELECT * FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team ORDER BY point DESC,createtime DESC LIMIT 0,7",array(':rid'=>$rid,':weid'=>$weid,':team'=>'2'));
	$point1 = pdo_fetchcolumn("SELECT SUM(point) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'1'));
	if(!$point1){
		$point1 = 0;
	}
	$point2 = pdo_fetchcolumn("SELECT SUM(point) FROM ".tablename('weixin_bahe_team')." WHERE rid=:rid AND weid=:weid AND team=:team",array(':rid'=>$rid,':weid'=>$weid,':team'=>'2'));
	if(!$point2){
		$point2 = 0;
	}
	//$true_point1 = $point1 - $get_point1;
	//$true_point2 = $point2 - $get_point2;
	if($point1 > $point2){
		$data['result'] = 1;
	}
	if($point1 < $point2){
		$data['result'] = 2;
	}
	if($point1 == $point2){
		$data['result'] = 3;
	}
	if($point1==$get_point1 && $point2 == $get_point2){
			$data['result'] = 3;
	}
	$data['team1_point'] = $point1;
	$data['team2_point'] = $point2;
	die(json_encode($data));
}